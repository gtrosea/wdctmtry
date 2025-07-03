<?php
/**
 * WeddingSaas Email Notifications.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Notifications
 */

namespace WDS\Notifications;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Email Class.
 *
 * This class provides methods to send email.
 */
class Email {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// includes\classes\checkout.php
		add_action( 'wds_user_activation', array( $this, 'on_user_activation' ) );

		// includes\classes\checkout.php, includes\frontend\main.php, includes\notifications\email.php
		add_action( 'wds_user_register', array( $this, 'on_user_register' ) );

		// includes\classes\cron.php
		add_action( 'wds_user_status_changed', array( $this, 'on_user_expired' ) );

		// includes\classes\checkout.php
		add_action( 'wds_insert_invoice_after', array( $this, 'order_notifications' ) ); // unpaid

		// includes\objects\invoice.php
		add_action( 'wds_update_invoice_status', array( $this, 'order_notifications' ) ); // completed, cancelled

		// includes\frontend\ajax\admin.php
		add_action( 'wds_success_payout', array( $this, 'on_commission_paid' ) );

		// includes\classes\cron.php
		add_action( 'wds_reminder_invoice', array( $this, 'on_invoice_reminder' ) );
		add_action( 'wds_reminder_membership', array( $this, 'on_expired_reminder' ) );

		// includes\frontend\ajax\dashboard.php
		add_action( 'wds_client_register', array( $this, 'on_client_register' ) );

		// plugin wds replica
		add_action( 'wds_status_domain', array( $this, 'on_custom_domain' ) );

		if ( 'default' == wds_option( 'rsvp_integration' ) ) {
			add_action( 'wds_insert_comment', array( $this, 'on_guest_commented' ) );
		} else {
			add_action( 'wp_insert_comment', array( $this, 'on_guest_commented' ) );
		}
	}

	/**
	 * Sends an email notification based on the target and provided content.
	 *
	 * @param string $target The notification target.
	 * @param string $email  The recipient's email address.
	 * @param array  $args The arguments to build the email content.
	 */
	private function email( $target, $email, $args ) {
		wds_email( $target )
			->to( $email )
			->content( $args )
			->send_email();
	}

	/**
	 * Sends a WhatsApp notification based on the target and provided content.
	 *
	 * @param string $target The notification target.
	 * @param string $phone  The recipient's phone number.
	 * @param array  $args The arguments to build the WhatsApp message content.
	 */
	private function whatsapp( $target, $phone, $args ) {
		wds_whatsapp( $target )
			->phone( $phone )
			->content( $args )
			->send_wa();
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $user_id The user ID.
	 */
	public function on_user_activation( $user_id ) {
		global $wpdb;

		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$login = wds_user_login( $user_id );
		$phone = wds_user_phone( $user_id );
		$key   = wp_generate_password( 20, false );

		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'ID' => $user_id ) );

		$verification_link = add_query_arg(
			array(
				'key'  => $key,
				'user' => $login,
			),
			wds_url( 'verify' )
		);

		$content_args = array(
			'customer-name'     => $name,
			'customer-email'    => $email,
			'customer-phone'    => $phone,
			'verification-link' => $verification_link,
		);

		$this->email( 'user_activation', $email, $content_args );
		if ( wds_option( 'user_activation_whatsapp_enable' ) ) {
			$this->whatsapp( 'user_activation', $phone, $content_args );
		}
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $user_id The user ID.
	 */
	public function on_user_register( $user_id ) {
		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );
		$group = wds_user_group( $user_id );
		$pass  = get_user_meta( $user_id, '_password', true );
		$pass  = wds_option( 'hide_password' ) ? wds_option( 'default_password' ) : $pass;

		$content_args = array(
			'customer-name'     => $name,
			'customer-email'    => $email,
			'customer-phone'    => $phone,
			'customer-password' => $pass,
		);

		$this->email( 'user_register', $email, $content_args );
		$this->whatsapp( 'user_register', $phone, $content_args );

		// WDS engine contact trial
		if ( 'trial' == $group ) {
			$mailketing_list = wds_engine( 'mailketing_trial' );
			if ( $mailketing_list ) {
				\WDS\Engine\Tools\Contact::mailketing_add_subscriber( $name, $email, $phone, $mailketing_list );
			}

			$starsender_group = wds_engine( 'starsender_trial' );
			if ( $starsender_group ) {
				\WDS\Engine\Tools\Contact::starsender_add_group( $name, $phone, $starsender_group );
			}

			$sendy_list = wds_engine( 'sendy_trial' );
			if ( $sendy_list ) {
				\WDS\Engine\Tools\Contact::sendy_add_subscriber( $name, $email, $sendy_list );
			}
		}

		delete_user_meta( $user_id, '_password' );
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $user_id The user ID.
	 */
	public function on_user_expired( $user_id ) {
		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );
		$group = wds_user_group( $user_id );
		$order = wds_user_order_id( $user_id );

		if ( $order ) {
			$encoded   = wds_encrypt_decrypt( $order );
			$renew_url = wds_url( 'renew', $encoded );
		} else {
			$renew_url = wds_url( 'upgrade' );
		}

		$content_args = array(
			'customer-name'  => $name,
			'customer-email' => $email,
			'renew-url'      => $renew_url,
		);

		if ( 'trial' == $group ) {
			$this->email( 'user_upgrade', $email, $content_args );
			$this->whatsapp( 'user_upgrade', $phone, $content_args );
		} else {
			$this->email( 'expired', $email, $content_args );
			$this->whatsapp( 'expired', $phone, $content_args );
		}
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $invoice_id The invoice ID.
	 */
	public function order_notifications( $invoice_id ) {
		$invoice = wds_get_invoice( $invoice_id );
		if ( ! $invoice ) {
			return;
		}

		if ( 'unpaid' == $invoice->status ) {
			$this->on_place_order( $invoice_id );
		} elseif ( 'completed' == $invoice->status ) {
			$this->on_invoice_completed( $invoice_id );
		} elseif ( 'cancelled' == $invoice->status ) {
			$this->on_invoice_cancelled( $invoice_id );
		}
	}

	/**
	 * Invoice place order.
	 *
	 * @param int $invoice_id The invoice ID.
	 */
	public function on_place_order( $invoice_id ) {
		$invoice  = wds_get_invoice( $invoice_id );
		$order    = wds_get_invoice_order( $invoice_id );
		$order_id = $order->order_id;
		$user_id  = $invoice->user_id;
		$summary  = $invoice->summary;

		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );

		$product_name = wds_sanitize_data_field( $summary, 'product_title' );
		$addons       = wds_get_order_meta( $order_id, 'addons' );
		$addons       = ! empty( $addons ) ? $addons : '-';
		$price        = wds_sanitize_data_field( $summary, 'total' );
		$free         = empty( $price ) || 0 == floatval( $price ) ? true : false;

		$gateway   = $invoice->gateway;
		$reference = $invoice->reference;
		$payment   = $this->get_payment_gateway( $invoice_id, $gateway, $reference );

		$content_args = array(
			'customer-name'   => $name,
			'customer-email'  => $email,
			'product-name'    => $product_name,
			'product-addon'   => html_entity_decode( $addons ),
			'invoice-number'  => wds_generate_invoice_format( $invoice_id ),
			'invoice-date'    => wds_date_format( strtotime( $invoice->created_at ) ),
			'invoice-amount'  => wds_convert_money( $price ),
			'payment-methods' => $payment,
		);

		if ( ! $free ) {
			$this->email( 'invoice_place_order', $email, $content_args );
			$this->whatsapp( 'invoice_place_order', $phone, $content_args );
		}
	}

	/**
	 * Invoice completed.
	 *
	 * @param int $invoice_id The invoice ID.
	 */
	public function on_invoice_completed( $invoice_id ) {
		$invoice  = wds_get_invoice( $invoice_id );
		$order    = wds_get_invoice_order( $invoice_id );
		$order_id = $order->order_id;
		$user_id  = $invoice->user_id;
		$summary  = $invoice->summary;

		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );
		$group = wds_user_group( $user_id );

		$product_name = wds_sanitize_data_field( $summary, 'product_title' );
		$addons       = wds_get_order_meta( $order_id, 'addons' );
		$addons       = ! empty( $addons ) ? $addons : '-';
		$price        = wds_sanitize_data_field( $summary, 'total' );
		$free         = empty( $price ) || 0 == floatval( $price ) ? true : false;

		$email_admin = wds_option( 'admin_setting_email' );
		$phone_admin = wds_option( 'admin_setting_whatsapp' );

		$content_args = array(
			'customer-name'  => $name,
			'customer-email' => $email,
			'customer-phone' => $phone,
			'product-name'   => $product_name,
			'product-addon'  => html_entity_decode( $addons ),
			'invoice-number' => wds_generate_invoice_format( $invoice_id ),
			'invoice-date'   => wds_date_format( strtotime( $invoice->created_at ) ),
			'invoice-amount' => $free ? wds_lang( 'free' ) : wds_convert_money( $invoice->total ),
		);

		// Insert Membership
		\WDS_Membership::insert( $order_id );

		// Automatic Account Activation after order
		if ( wds_option( 'account_activation' ) && ! wds_is_account_verified( $user_id ) ) {
			if ( floatval( $invoice->total ) > 0 ) {
				update_user_meta( $user_id, '_is_verified', true );
				do_action( 'wds_user_register', $user_id );
			}
		}

		// Send notifications to customer
		$this->email( 'invoice_completed', $email, $content_args );
		$this->whatsapp( 'invoice_completed', $phone, $content_args );

		// Send notifications to admin
		if ( wds_option( 'admin_invoice_completed_enable' ) ) {
			$this->email( 'admin_invoice_completed', $email_admin, $content_args );
			$this->whatsapp( 'admin_invoice_completed', $phone_admin, $content_args );
		}

		// Send notifications to affiliate
		if ( ! empty( $affiliate_id ) ) {
			$affiliate_id = wds_get_order_meta( $order_id, 'affiliate_id' );
			$commission   = \WDS\Models\Commission::where( 'user_id', $affiliate_id, '=' )->and_where( 'invoice_id', $invoice_id, '=' )->first();
			if ( $commission->ID <= 0 ) {
				return;
			}

			$aff_name       = wds_user_name( $affiliate_id );
			$aff_email      = wds_user_email( $affiliate_id );
			$aff_phone      = wds_user_phone( $affiliate_id );
			$aff_commission = wds_convert_money( $commission->amount );

			$affiliate_args = array(
				'affiliate-name'       => $aff_name,
				'affiliate-email'      => $aff_email,
				'affiliate-phone'      => $aff_phone,
				'affiliate-commission' => $aff_commission,
				'customer-name'        => $name,
				'customer-email'       => $email,
				'customer-phone'       => $phone,
				'product-name'         => $product_name,
				'invoice-number'       => wds_generate_invoice_format( $invoice_id ),
				'invoice-date'         => wds_date_format( strtotime( $invoice->created_at ) ),
				'invoice-amount'       => $free ? wds_lang( 'free' ) : wds_convert_money( $invoice->total ),
			);

			if ( $commission->amount > 0 ) {
				$this->email( 'affiliate_new_sales', $aff_email, $affiliate_args );
				$this->whatsapp( 'affiliate_new_sales', $aff_phone, $affiliate_args );
			}
		}

		// WDS engine contact
		if ( in_array( $group, array( 'member', 'reseller' ) ) ) {
			$mailketing_list = wds_engine( 'mailketing_' . $group );
			if ( $mailketing_list ) {
				\WDS\Engine\Tools\Contact::mailketing_add_subscriber( $name, $email, $phone, $mailketing_list );
			}

			$starsender_group = wds_engine( 'starsender_' . $group );
			if ( $starsender_group ) {
				\WDS\Engine\Tools\Contact::starsender_add_group( $name, $phone, $starsender_group );
			}

			$sendy_list = wds_engine( 'sendy_' . $group );
			if ( $sendy_list ) {
				\WDS\Engine\Tools\Contact::sendy_add_subscriber( $name, $email, $sendy_list );
			}
		}
	}

	/**
	 * Invoice cancelled.
	 *
	 * @param int $invoice_id The invoice ID.
	 */
	public function on_invoice_cancelled( $invoice_id ) {
		$invoice = wds_get_invoice( $invoice_id );

		if ( ! wds_option( 'invoice_cancelled_enable' ) ) {
			return;
		}

		$order    = wds_get_invoice_order( $invoice_id );
		$order_id = $order->order_id;
		$user_id  = $invoice->user_id;
		$summary  = $invoice->summary;

		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );

		$product_name = wds_sanitize_data_field( $summary, 'product_title' );
		$addons       = wds_get_order_meta( $order_id, 'addons' );
		$addons       = ! empty( $addons ) ? $addons : '-';
		$price        = wds_sanitize_data_field( $summary, 'total' );
		$free         = empty( $price ) || 0 == floatval( $price ) ? true : false;

		$gateway   = $invoice->gateway;
		$reference = $invoice->reference;

		if ( 'banktransfer' == $gateway ) {
			$payment = wds_banktransfer_email_format();
		} elseif ( 'qris' == $gateway ) {
			$payment = wds_email_payment_link( $invoice_id );
		} elseif ( strpos( $gateway, 'tripay' ) === 0 || strpos( $gateway, 'duitku' ) === 0 || 'xendit' == $gateway || 'midtrans' == $gateway || 'flip' == $gateway ) {
			$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
		} else {
			$payment = $gateway;
		}

		$content_args = array(
			'customer-name'   => $name,
			'customer-email'  => $email,
			'product-name'    => $product_name,
			'product-addon'   => html_entity_decode( $addons ),
			'invoice-number'  => wds_generate_invoice_format( $invoice_id ),
			'invoice-date'    => wds_date_format( strtotime( $invoice->created_at ) ),
			'invoice-amount'  => $free ? wds_lang( 'free' ) : wds_convert_money( $invoice->total ),
			'payment-methods' => $payment,
		);

		$this->email( 'invoice_cancelled', $email, $content_args );
		$this->whatsapp( 'invoice_cancelled', $phone, $content_args );

		\WDS_Membership::delete( $order_id );
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param array $args The data affiliate.
	 */
	public function on_commission_paid( $args ) {
		if ( empty( $args ) && $args['amount'] <= 0 ) {
			return;
		}

		$user_id = intval( $args['user_id'] );
		$name    = wds_user_name( $user_id );
		$email   = wds_user_email( $user_id );
		$phone   = wds_user_phone( $user_id );

		$content_args = array(
			'affiliate-name'  => $name,
			'affiliate-email' => $email,
			'affiliate-phone' => $phone,
			'commission-paid' => wds_convert_money( $args['amount'] ),
		);

		$this->email( 'affiliate_commission_paid', $email, $content_args );
		$this->whatsapp( 'affiliate_commission_paid', $phone, $content_args );
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param array $args The data affiliate.
	 */
	public function on_invoice_reminder( $args ) {
		$invoice_id = wds_sanitize_data_field( $args, 'invoice_id', 0 );
		$invoice    = wds_get_invoice( $invoice_id );
		if ( ! $invoice ) {
			return;
		}

		$order    = wds_get_invoice_order( $invoice_id );
		$order_id = $order->order_id;

		$day = wds_sanitize_data_field( $args, 'days_to' );

		$summary = $invoice->summary;
		$user_id = $invoice->user_id;
		$name    = wds_user_name( $user_id );
		$email   = wds_user_name( $user_id );
		$phone   = wds_user_phone( $user_id );

		$product_name = wds_sanitize_data_field( $summary, 'product_title' );
		$addons       = wds_get_order_meta( $order_id, 'addons' );
		$addons       = ! empty( $addons ) ? $addons : '-';
		$price        = wds_sanitize_data_field( $summary, 'total' );
		$free         = empty( $price ) || 0 == floatval( $price ) ? true : false;

		$gateway   = $invoice->gateway;
		$reference = $invoice->reference;
		$payment   = $this->get_payment_gateway( $invoice_id, $gateway, $reference );

		$content_args = array(
			'customer-name'   => $name,
			'customer-email'  => $email,
			'product-name'    => $product_name,
			'product-addon'   => html_entity_decode( $addons ),
			'invoice-number'  => wds_generate_invoice_format( $invoice_id ),
			'invoice-date'    => wds_date_format( strtotime( $invoice->created_at ) ),
			'invoice-amount'  => wds_convert_money( $price ),
			'payment-methods' => $payment,
		);

		if ( ! $free ) {
			$this->email( 'invoice_reminder' . $day, $email, $content_args );
			$this->whatsapp( 'invoice_reminder' . $day, $phone, $content_args );
		}
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param array $args The data expired.
	 */
	public function on_expired_reminder( $args ) {
		$order_id = wds_sanitize_data_field( $args, 'order_id', 0 );
		$order    = wds_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$day = wds_sanitize_data_field( $args, 'days_before' );

		$user_id = $order->user_id;
		$name    = wds_user_name( $user_id );
		$email   = wds_user_email( $user_id );
		$phone   = wds_user_phone( $user_id );
		$group   = wds_user_group( $user_id );

		$encoded   = wds_encrypt_decrypt( $order_id );
		$renew_url = wds_url( 'renew', $encoded );

		$content_args = array(
			'customer-name'  => $name,
			'customer-email' => $email,
			'renew-url'      => $renew_url,
		);

		if ( 'trial' != $group ) {
			$this->email( 'expired_reminder' . $day, $email, $content_args );
			$this->whatsapp( 'expired_reminder' . $day, $phone, $content_args );
		}
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $client_id The client ID.
	 */
	public function on_client_register( $client_id ) {
		$client = wds_get_client( $client_id );

		$user_id      = $client->client_id;
		$client_name  = wds_user_name( $user_id );
		$client_email = wds_user_email( $user_id );
		$client_phone = wds_user_phone( $user_id );
		$client_pass  = get_user_meta( $user_id, '_password', true );

		$reseller_id    = $client->reseller_id;
		$reseller_name  = wds_user_name( $reseller_id );
		$reseller_email = wds_user_email( $reseller_id );
		$reseller_phone = wds_user_phone( $reseller_id );
		$host           = wds_home_url() . '/auth/login/';

		if ( wds_is_replica() ) {
			$value  = false;
			$status = '';

			$query = wdr_get_by( "WHERE user_id = '$reseller_id'" );

			if ( $query && ! empty( $query->ID ) ) {
				$link   = '';
				$value  = true;
				$status = $query->status;

				if ( 'nothing' !== $query->domain ) {
					$link = 'https://' . $query->domain;
				}

				if ( 'nothing' !== $query->subdomain ) {
					$link = 'https://' . $query->subdomain . '.' . wds_option( 'wdr_domain_host' );
				}
			}

			if ( $value && 'active' === $status ) {
				$host = $link . '/auth/login/';
			}
		}

		$content_args = array(
			'reseller-name'     => $reseller_name,
			'replica-login-url' => $host,
			'client-name'       => $client_name,
			'client-email'      => $client_email,
			'client-phone'      => $client_phone,
			'client-password'   => $client_pass,
		);

		// Send to client.
		if ( wds_option( 'client_register_enable' ) ) {
			$this->email( 'client_register', $client_email, $content_args );
			$this->whatsapp( 'client_register', $client_phone, $content_args );
		}

		// Send to reseller.
		if ( wds_option( 'reseller_client_register_enable' ) ) {
			$this->email( 'reseller_client_register', $reseller_email, $content_args );
			$this->whatsapp( 'reseller_client_register', $reseller_phone, $content_args );
		}

		delete_user_meta( $user_id, '_password' );
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param array $args The data domain.
	 */
	public function on_custom_domain( $args ) {
		if ( empty( $args ) ) {
			return;
		}

		$user_id = intval( $args['user_id'] );
		$domain  = $args['domain'];
		$status  = $args['status'];

		$name  = wds_user_name( $user_id );
		$email = wds_user_email( $user_id );
		$phone = wds_user_phone( $user_id );

		$content_args = array(
			'reseller-name' => $name,
			'domain'        => $domain,
		);

		if ( 'unconnected' == $status ) {
			$this->email( 'reseller_domain_pending', $email, $content_args );
			$this->whatsapp( 'reseller_domain_pending', $phone, $content_args );
			if ( wds_option( 'admin_domain_pending_enable' ) ) {
				$email_admin = wds_option( 'admin_setting_email' );
				$phone_admin = wds_option( 'admin_setting_whatsapp' );
				$this->email( 'admin_domain_pending', $email_admin, $content_args );
				$this->whatsapp( 'admin_domain_pending', $phone_admin, $content_args );
			}
		} else {
			$this->email( 'reseller_domain_active', $email, $content_args );
			$this->whatsapp( 'reseller_domain_active', $phone, $content_args );
		}
	}

	/**
	 * Handles send email and WhatsApp message.
	 *
	 * @param int $comment_id The comment ID.
	 */
	public function on_guest_commented( $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( 1 == $comment->comment_approved ) {
			$post_id = $comment->comment_post_ID;
			$post    = get_post( $post_id );
			$author  = get_userdata( $post->post_author );

			// custom notification
			$custom_name     = wds_post_meta( $post_id, '_notif_name' );
			$custom_email    = wds_post_meta( $post_id, '_notif_email' );
			$custom_whatsapp = wds_post_meta( $post_id, '_notif_whatsapp' );

			$customer_name = empty( $custom_name ) ? $author->first_name : $custom_name;
			$post_title    = html_entity_decode( $post->post_title );

			$guest_name      = $comment->comment_author;
			$comment_date    = $comment->comment_date;
			$comment_content = $comment->comment_content;

			$integration = wds_option( 'rsvp_integration' );
			$attendance  = 'default' == $integration ? get_comment_meta( $comment_id, 'attendance', true ) : get_comment_meta( $comment_id, 'konfirmasi', true );
			$guest       = get_comment_meta( $comment_id, 'guest', true );
			$guest_total = ! empty( $guest ) ? $guest . ' orang' : '-';

			$content_args = array(
				'invitation-name' => $post_title,
				'customer-name'   => $customer_name,
				'guest-name'      => $guest_name,
				'date'            => $comment_date,
				'attendance'      => wds_rsvp_attendance( $attendance ),
				'guest-total'     => $guest_total,
				'comment'         => $comment_content,
			);

			if ( ! empty( $custom_email ) ) {
				$this->email( 'rsvp', $custom_email, $content_args );
			}

			if ( ! empty( $custom_whatsapp ) ) {
				$this->whatsapp( 'rsvp', $custom_whatsapp, $content_args );
			}
		}
	}

	/**
	 * Get payment gateway.
	 *
	 * @param int    $invoice_id The invoice ID.
	 * @param string $gateway The gateway id.
	 * @param string $reference The gateway reference.
	 */
	private function get_payment_gateway( $invoice_id, $gateway, $reference ) {
		if ( 'banktransfer' == $gateway ) {
			$payment = wds_banktransfer_email_format();
		} elseif ( 'qris' == $gateway ) {
			$payment = wds_email_payment_link( $invoice_id );
		} elseif ( strpos( $gateway, 'tripay' ) === 0 ) {
			$link = wds_option( 'tripay_payment_link' );
			if ( $link ) {
				$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
			} else {
				$payment = wds_email_payment_link( $invoice_id );
			}
		} elseif ( strpos( $gateway, 'duitku' ) === 0 ) {
			$link = wds_option( 'duitku_payment_link' );
			if ( $link ) {
				$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
			} else {
				$payment = wds_email_payment_link( $invoice_id );
			}
		} elseif ( 'xendit' == $gateway ) {
			$link = wds_option( 'xendit_payment_link' );
			if ( $link ) {
				$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
			} else {
				$payment = wds_email_payment_link( $invoice_id );
			}
		} elseif ( 'midtrans' == $gateway ) {
			$link = wds_option( 'midtrans_payment_link' );
			if ( $link ) {
				$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
			} else {
				$payment = wds_email_payment_link( $invoice_id );
			}
		} elseif ( 'flip' == $gateway ) {
			$link = wds_option( 'flip_payment_link' );
			if ( $link ) {
				$payment = wds_lang( 'trx_payment_link_payment' ) . $reference;
			} else {
				$payment = wds_email_payment_link( $invoice_id );
			}
		} else {
			$payment = $gateway;
		}

		return $payment;
	}
}

new Email();
