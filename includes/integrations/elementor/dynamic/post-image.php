<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Post_Image Class.
 */
class Dynamic_Post_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-post-image';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Post Image Field', 'wds-notrans' );
	}

	/**
	 * Defines the group this tag belongs to.
	 */
	public function get_group() {
		return array( 'weddingsaas' );
	}

	/**
	 * Retrieves the categories for the dynamic tag.
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$post = array(
			'_foto_cover'       => __( 'Foto Cover', 'wds-notrans' ),
			'_background_cover' => __( 'Background Cover', 'wds-notrans' ),
			'_foto_mempelai'    => __( 'Foto Kedua Mempelai', 'wds-notrans' ),
			'_foto_pembuka'     => __( 'Foto Pembuka', 'wds-notrans' ),
			'_foto_penutup'     => __( 'Foto Penutup', 'wds-notrans' ),
			'_foto_wanita'      => __( 'Foto Wanita', 'wds-notrans' ),
			'_foto_pria'        => __( 'Foto Pria', 'wds-notrans' ),
			'_foto_cerita_1'    => __( 'Cerita 1', 'wds-notrans' ),
			'_foto_cerita_2'    => __( 'Cerita 2', 'wds-notrans' ),
			'_foto_cerita_3'    => __( 'Cerita 3', 'wds-notrans' ),
			'_foto_cerita_4'    => __( 'Cerita 4', 'wds-notrans' ),
			'_nama_bank_1'      => __( 'Bank 1', 'wds-notrans' ),
			'_nama_bank_2'      => __( 'Bank 2', 'wds-notrans' ),
			'_nama_bank_3'      => __( 'Bank 3', 'wds-notrans' ),
			'_nama_bank_4'      => __( 'Bank 4', 'wds-notrans' ),
			'reseller'          => __( 'Branding Reseller*', 'wds-notrans' ),
		);

		$this->add_control(
			'post',
			array(
				'label'   => __( 'Field', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post,
				'default' => '_foto_pembuka',
			)
		);

		$this->add_control(
			'fallback_image',
			array(
				'label'     => __( 'Fallback', 'wds-notrans' ),
				'type'      => Controls_Manager::MEDIA,
				'separator' => 'before',
				'condition' => array( 'post!' => 'reseller' ),
			)
		);

		$this->add_control(
			'reseller',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array( 'logo' => __( 'Logo', 'wds-notrans' ) ),
				'default'   => 'logo',
				'condition' => array( 'post' => 'reseller' ),
			)
		);
	}

	/**
	 * Get value.
	 *
	 * @param array $options The options.
	 */
	public function get_value( array $options = array() ) {
		$post_field     = $this->get_settings( 'post' );
		$reseller_field = $this->get_settings( 'reseller' );
		$fallback       = $this->get_settings( 'fallback_image' );

		if ( ! $post_field ) {
			return;
		}

		$image_id  = 0;
		$image_url = '';
		$post_id   = get_the_ID();
		$user_id   = get_post_field( 'post_author', $post_id );

		if ( 'reseller' == $post_field ) {
			if ( 'logo' == $reseller_field ) {
				$image_fallback = wds_option( 'reseller_logo_fallback' );
				$hide_branding  = wds_option( 'reseller_hide' );
				$hide_branding  = ! empty( $hide_branding ) ? $hide_branding : array();

				$value = wds_user_meta( $user_id, '_branding_logo' );
				if ( wds_is_replica() ) {
					$wdr_logo = do_shortcode( '[wds_replica type="logo"]' );
					$value    = ! empty( $wdr_logo ) ? $wdr_logo : $value;
				}

				if ( ! empty( $value ) ) {
					if ( ! in_array( 'logo', $hide_branding ) ) {
						$image_id  = wds_attachment_url_to_postid( $value );
						$image_url = $value;
					} else {
						$image_id  = wds_attachment_url_to_postid( $image_fallback );
						$image_url = $image_fallback;
					}
				} else {
					$image_id  = wds_attachment_url_to_postid( $image_fallback );
					$image_url = $image_fallback;
				}
			}
		}

		$foto = array( '_foto_cover', '_background_cover', '_foto_mempelai', '_foto_pembuka', '_foto_penutup', '_foto_wanita', '_foto_pria', '_foto_cerita_1', '_foto_cerita_2', '_foto_cerita_3', '_foto_cerita_4' );
		if ( in_array( $post_field, $foto ) ) {
			$value = wds_post_meta( $post_id, $post_field );
			if ( ! empty( $value ) ) {
				if ( wds_check_array( $value, true ) ) {
					$image_id  = $value['id'];
					$image_url = $value['url'];
				} elseif ( is_numeric( $value ) ) {
					$image_id  = $value;
					$image_url = wp_get_attachment_url( $value );
				} else {
					$image_id  = wds_attachment_url_to_postid( $value );
					$image_url = $value;
				}
			} else {
				$image_id  = $fallback['id'];
				$image_url = $fallback['url'];
			}
		}

		$bank = array( '_nama_bank_1', '_nama_bank_2', '_nama_bank_3', '_nama_bank_4' );
		if ( in_array( $post_field, $bank ) ) {
			$value = wds_post_meta( $post_id, $post_field );
			if ( ! empty( $value ) ) {
				$img_id = wds_attachment_url_to_postid( $value );
				if ( $img_id ) {
					$image_id  = $img_id;
					$image_url = $value;
				} else {
					$image_id  = '';
					$image_url = $value;
				}
			} else {
				$image_id  = $fallback['id'];
				$image_url = $fallback['url'];
			}
		}

		$image_data = array(
			'id'  => $image_id,
			'url' => $image_url,
		);

		return $image_data;
	}
}
