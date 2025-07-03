<?php

namespace WDS\Integrations;

use Jet_Form_Builder\Exceptions\Action_Exception;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * JetFormBuilder Class.
 */
class JetFormBuilder {

	/**
	 * Instance of the JetFormBuilder class.
	 *
	 * @var JetFormBuilder|null
	 */
	public static $instance = null;

	/**
	 * Initialize the JetFormBuilder instance.
	 *
	 * @return JetFormBuilder
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'modify_jetformbuilder' ) );
		add_action( 'jet-form-builder/custom-action/wds_register_client', array( $this, 'register_client' ) );
	}

	/**
	 * Modifies JetFormBuilder files for custom functionality if version changes.
	 *
	 * @return void
	 */
	public function modify_jetformbuilder() {
		if ( defined( 'JET_FORM_BUILDER_PATH' ) ) {
			$version      = JET_FORM_BUILDER_VERSION;
			$copy_version = get_option( 'wds_modify_jfb_version' );
			if ( $version != $copy_version ) {
				copy(
					WDS_INCLUDES . 'integrations/jet-form-builder/terms-query.php',
					JET_FORM_BUILDER_PATH . 'modules/option-query/terms-query.php'
				);
				update_option( 'wds_modify_jfb_version', $version );
			}
		}
	}

	/**
	 * Registers a new client based on JetFormBuilder form submission data.
	 *
	 * @param array $request The form request data.
	 * @throws Action_Exception Throws exception if registration fails.
	 * @return void
	 */
	public function register_client( $request ) {
		$reseller_id = wds_sanitize_data_field( $request, 'user_id', false );
		$name        = wds_sanitize_data_field( $request, 'name', false );
		$email       = wds_sanitize_data_field( $request, 'email', false );
		$phone       = wds_sanitize_data_field( $request, 'phone', false );
		$password    = wds_sanitize_data_field( $request, 'password', false );
		$price       = wds_sanitize_data_field( $request, 'price', false );

		if ( ! $reseller_id || ! $name || ! $email || ! $phone || ! $password || ! $price ) {
			$get_client_quota = wds_user_client_quota( $reseller_id );

			$client_data = array(
				'user_login'   => $email,
				'user_pass'    => $password,
				'user_email'   => $email,
				'first_name'   => $name,
				'display_name' => $name,
				'role'         => 'wds-member',
			);

			if ( empty( $get_client_quota ) || 0 == $get_client_quota ) {
				$error = __( 'Kuota klien Anda habis, silakan upgrade terlebih dahulu.', 'weddingsaas' );
				throw new Action_Exception( esc_html( $error ) );
			}

			$user_id = wp_insert_user( $client_data );
			if ( is_wp_error( $user_id ) ) {
				throw new Action_Exception( esc_html( $user_id->get_error_message() ) );
			}

			$client_args = array(
				'reseller_id' => $reseller_id,
				'client_id'   => $user_id,
			);

			$client_id = wds_insert_client( $client_args );
			if ( is_wp_error( $client_id ) ) {
				throw new Action_Exception( esc_html( $client_id->get_error_message() ) );
			}

			$product_id = wds_option( 'client_product' );
			$product    = wds_get_product( $product_id );
			$today      = current_time( 'timestamp' );

			if ( $product ) {
				$product_name         = esc_html( $product->title );
				$membership_duration  = wds_get_product_meta( $product_id, 'membership_duration' );
				$membership_period    = wds_get_product_meta( $product_id, 'membership_period' );
				$duration_new         = "+$membership_duration $membership_period";
				$membership_period_ts = wds_get_product_meta( $product_id, 'membership_lifetime' ) === 'yes' ? '' : strtotime( $duration_new, $today );
				$invitation_quota     = wds_get_product_meta( $product_id, 'invitation_quota' );
				$invitation_duration  = wds_get_product_meta( $product_id, 'invitation_duration' );
				$invitation_period    = wds_get_product_meta( $product_id, 'invitation_period' );
				$invitation_status    = wds_get_product_meta( $product_id, 'invitation_status' );
			} else {
				$active_period        = strtotime( '+1 year', $today );
				$product_name         = 'Gold Invitation';
				$invitation_quota     = 1;
				$invitation_duration  = 1;
				$invitation_period    = 'year';
				$invitation_status    = 'draft';
				$membership_period_ts = $active_period;
			}

			$metas = array(
				'_wds_user_status'         => 'active',
				'_wds_user_group'          => esc_html( wds_get_product_meta( $product_id, 'membership_type' ) ),
				'_wds_user_membership'     => $product_name,
				'_phone'                   => $phone,
				'_wds_user_active_period'  => $membership_period_ts,
				'_wds_invitation_quota'    => $invitation_quota,
				'_wds_invitation_duration' => $invitation_duration,
				'_wds_invitation_period'   => $invitation_period,
				'_wds_invitation_action'   => $invitation_status,
				'_password'                => $password,
			);

			foreach ( $metas as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}

			update_user_meta( $reseller_id, '_wds_client_quota', intval( $get_client_quota ) - 1 );
			do_action( 'wds_client_register', $client_id );

			if ( $price ) {
				$income_args = array(
					'user_id' => $reseller_id,
					'data_id' => $user_id,
					'type'    => 'client',
					'price'   => $price,
				);

				$income_id = wds_insert_income( $income_args );
				if ( is_wp_error( $income_id ) ) {
					throw new Action_Exception( esc_html( $income_id->get_error_message() ) );
				}
			}

			$success = __( 'Pendaftaran Klien Anda telah berhasil.', 'weddingsaas' );
			throw new Action_Exception( esc_html( $success ) );
		}
	}
}

JetFormBuilder::init();
