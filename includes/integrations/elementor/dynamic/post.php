<?php

namespace WDS\Integrations\Elementor;

use Elementor\Controls_Manager;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Dynamic_Post Class.
 */
class Dynamic_Post extends \Elementor\Core\DynamicTags\Tag {

	/**
	 * Retrieves the name of the dynamic tag.
	 */
	public function get_name() {
		return 'wds-post-text';
	}

	/**
	 * Retrieves the title for the dynamic tag.
	 */
	public function get_title() {
		return __( 'Post Field', 'wds-notrans' );
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
		return array(
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::DATETIME_CATEGORY,
		);
	}

	/**
	 * Registers controls for selecting sections, values, and filtering options.
	 */
	protected function register_controls() {
		$post = array(
			'cover'      => __( 'Cover', 'wds-notrans' ),
			'audio'      => __( 'Audio', 'wds-notrans' ),
			'event'      => __( 'Acara v1 (Listing)', 'wds-notrans' ),
			'event_v2'   => __( 'Acara v2', 'wds-notrans' ),
			'calendar'   => __( 'Google Calendar*', 'wds-notrans' ),
			'gift'       => __( 'Kado Digital', 'wds-notrans' ),
			'lovestory'  => __( 'Love Story', 'wds-notrans' ),
			'live'       => __( 'Live Streaming*', 'wds-notrans' ),
			'bride'      => __( 'Mempelai Wanita', 'wds-notrans' ),
			'groom'      => __( 'Mempelai Pria', 'wds-notrans' ),
			'nonwedding' => __( 'Non Wedding', 'wds-notrans' ),
			'inviting'   => __( 'Turut Mengundang', 'wds-notrans' ),
			'teks'       => __( 'Teks', 'wds-notrans' ),
			'reseller'   => __( 'Branding Reseller*', 'wds-notrans' ),
			'author'     => __( 'Author*', 'wds-notrans' ),
		);

		$this->add_control(
			'post',
			array(
				'label'   => __( 'Field', 'wds-notrans' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post,
				'default' => 'cover',
			)
		);

		$this->add_control(
			'cover',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'_tanggal_acara_cover' => __( 'Cover Event Date', 'wds-notrans' ),
					'_tanggal_acara'       => __( 'Countdown Event Date', 'wds-notrans' ),
					'_guest_name'          => __( 'Guest Name', 'wds-notrans' ),
				),
				'default'   => '_tanggal_acara_cover',
				'condition' => array( 'post' => 'cover' ),
			)
		);

		$this->add_control(
			'audio',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'_audio'         => __( 'Audio Link MP3', 'wds-notrans' ),
					'_audio_name'    => __( 'Audio Name', 'wds-notrans' ),
					'_audio_youtube' => __( 'Audio Link Youtube', 'wds-notrans' ),
					'_audio_start'   => __( 'Audio Start', 'wds-notrans' ),
					'_audio_end'     => __( 'Audio End', 'wds-notrans' ),
				),
				'default'   => '_audio',
				'condition' => array( 'post' => 'audio' ),
			)
		);

		$this->add_control(
			'event',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'_nama'      => __( 'Nama Acara', 'wds-notrans' ),
					'_tanggal'   => __( 'Tanggal', 'wds-notrans' ),
					'_waktu'     => __( 'Waktu', 'wds-notrans' ),
					'_lokasi'    => __( 'Lokasi', 'wds-notrans' ),
					'_alamat'    => __( 'Alamat', 'wds-notrans' ),
					'_maps_link' => __( 'Link Google Maps', 'wds-notrans' ),
				),
				'default'   => '_nama',
				'condition' => array( 'post' => 'event' ),
			)
		);

		$this->add_control(
			'event_v2',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$event_v2,
				'condition' => array( 'post' => 'event_v2' ),
			)
		);

		$this->add_control(
			'event_v2_date_format',
			array(
				'type'      => \Elementor\Controls_Manager::SELECT,
				'label'     => __( 'Format', 'wds-notrans' ),
				'options'   => Default_Meta::$event_v2_format,
				'condition' => array(
					'event_v2' => array( '_tanggal_acara_1', '_tanggal_acara_2', '_tanggal_acara_3', '_tanggal_acara_4' ),
				),
			)
		);

		$this->add_control(
			'calendar',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$calendar,
				'default'   => '_calendar_title',
				'condition' => array( 'post' => 'calendar' ),
			)
		);

		$this->add_control(
			'gift',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$gift,
				'default'   => '_fitur_kado',
				'condition' => array( 'post' => 'gift' ),
			)
		);

		$this->add_control(
			'bride_and_groom',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'full_name'        => __( 'Nama Lengkap', 'wds-notrans' ),
					'nickname'         => __( 'Nama Panggilan', 'wds-notrans' ),
					'initial_nickname' => __( 'Inisial nama panggilan', 'wds-notrans' ),
					'description'      => __( 'Deskripsi', 'wds-notrans' ),
					'family'           => __( 'Keluarga Besar', 'wds-notrans' ),
					'facebook'         => __( 'Facebook', 'wds-notrans' ),
					'instagram'        => __( 'Instagram', 'wds-notrans' ),
					'twitter'          => __( 'Twitter', 'wds-notrans' ),
					'tiktok'           => __( 'Tiktok', 'wds-notrans' ),
					'youtube'          => __( 'Youtube', 'wds-notrans' ),
				),
				'default'   => 'full_name',
				'condition' => array( 'post' => array( 'bride', 'groom' ) ),
			)
		);

		$this->add_control(
			'lovestory',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$lovestory,
				'condition' => array( 'post' => 'lovestory' ),
			)
		);

		$this->add_control(
			'live',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$live,
				'default'   => '_fitur_live_streaming',
				'condition' => array( 'post' => 'live' ),
			)
		);

		$this->add_control(
			'nonwedding',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$nonwedding,
				'condition' => array( 'post' => 'nonwedding' ),
			)
		);

		$this->add_control(
			'teks',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => Default_Meta::$teks,
				'condition' => array( 'post' => 'teks' ),
			)
		);

		$this->add_control(
			'reseller',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'brand_name'  => __( 'Brand Name', 'wds-notrans' ),
					'logo_link'   => 'Link Logo',
					'description' => __( 'Description', 'wds-notrans' ),
					'instagram'   => __( 'Instagram', 'wds-notrans' ),
					'facebook'    => __( 'Facebook', 'wds-notrans' ),
					'tiktok'      => __( 'Tiktok', 'wds-notrans' ),
					'twitter'     => __( 'Twitter', 'wds-notrans' ),
					'youtube'     => __( 'Youtube', 'wds-notrans' ),
				),
				'default'   => 'brand_name',
				'condition' => array( 'post' => 'reseller' ),
			)
		);

		$this->add_control(
			'author',
			array(
				'label'     => __( 'Value', 'wds-notrans' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'_wds_user_group'      => __( 'User Group', 'wds-notrans' ),
					'_wds_user_membership' => __( 'Membership', 'wds-notrans' ),
					'_phone'               => __( 'Phone', 'wds-notrans' ),
				),
				'default'   => '_wds_user_group',
				'condition' => array( 'post' => 'author' ),
			)
		);
	}

	/**
	 * Renders the custom field value with optional filtering and formatting.
	 */
	public function render() {
		$post_field        = $this->get_settings( 'post' );
		$event_field       = $this->get_settings( 'event' );
		$event_v2_field    = $this->get_settings( 'event_v2' );
		$event_v2_format   = $this->get_settings( 'event_v2_date_format' );
		$cover_field       = $this->get_settings( 'cover' );
		$calendar_field    = $this->get_settings( 'calendar' );
		$audio_field       = $this->get_settings( 'audio' );
		$gift_field        = $this->get_settings( 'gift' );
		$bride_groom_field = $this->get_settings( 'bride_and_groom' );
		$lovestory_field   = $this->get_settings( 'lovestory' );
		$live_field        = $this->get_settings( 'live' );
		$inviting_field    = $this->get_settings( 'inviting' );
		$nonwedding_field  = $this->get_settings( 'nonwedding' );
		$teks_field        = $this->get_settings( 'teks' );
		$reseller_field    = $this->get_settings( 'reseller' );
		$author_field      = $this->get_settings( 'author' );

		if ( ! $post_field ) {
			return;
		}

		$post_id     = get_the_ID();
		$user_id     = get_post_field( 'post_author', $post_id );
		$field_value = '';

		if ( 'cover' == $post_field ) {
			if ( '_tanggal_acara_cover' == $cover_field ) {
				$field_value = wds_post_meta( $post_id, $cover_field );
			} elseif ( '_tanggal_acara' == $cover_field ) {
				$timestamp   = wds_post_meta( $post_id, $cover_field );
				$field_value = date_i18n( 'Y-m-d H:i:s', $timestamp );
			} elseif ( '_guest_name' == $cover_field ) {
				$field_value = isset( $_GET['to'] ) ? wds_sanitize_text_guest_name( urldecode( $_GET['to'] ) ) : '';
			}
		} elseif ( 'audio' == $post_field ) {
			if ( '_audio' == $audio_field ) {
				$field_value = wds_post_meta( $post_id, $audio_field );
			} elseif ( '_audio_name' == $audio_field ) {
				$field_value = WDS()->invitation->get_audio_name( wds_post_meta( $post_id, '_audio' ) );
			} elseif ( '_audio_youtube' == $audio_field ) {
				$field_value = wds_post_meta( $post_id, $audio_field );
			} elseif ( '_audio_start' == $audio_field ) {
				$field_value = wds_post_meta( $post_id, $audio_field );
			} elseif ( '_audio_end' == $audio_field ) {
				$field_value = wds_post_meta( $post_id, $audio_field );
			}
		} elseif ( 'event' == $post_field ) {
			if ( '_nama' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			} elseif ( '_tanggal' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			} elseif ( '_waktu' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			} elseif ( '_lokasi' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			} elseif ( '_alamat' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			} elseif ( '_maps_link' == $event_field ) {
				$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_acara" dynamic_field_post_meta_custom="' . $event_field . '"]' );
			}
		} elseif ( 'event_v2' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$event_v2 );
			if ( in_array( $event_v2_field, $allowed_fields ) ) {
				$meta     = wds_post_meta( $post_id, $event_v2_field );
				$language = wds_post_meta( $post_id, '_language' );

				if ( preg_match( '/^_tanggal_acara_([1-4])$/', $event_v2_field, $matches ) ) {
					$acara_num = $matches[1];

					$custom = trim( wds_post_meta( $post_id, "_tanggal_acara_{$acara_num}_custom" ) );

					if ( $language && class_exists( '\IntlDateFormatter' ) ) {
						// Convert WordPress date format to IntlDateFormatter pattern
						$pattern = str_replace(
							array( 'l', 'j', 'F', 'Y' ),
							array( 'EEEE', 'd', 'MMMM', 'yyyy' ),
							$event_v2_format
						);

						$formatter = new \IntlDateFormatter(
							$language,
							\IntlDateFormatter::FULL,
							\IntlDateFormatter::NONE,
							null,
							null,
							$pattern
						);

						$formatted = $formatter->format( $meta );

						if ( $custom ) {
							$custom_hari = trim( wds_post_meta( $post_id, "_tanggal_acara_{$acara_num}_hari" ) );
							$custom_tgl  = trim( wds_post_meta( $post_id, "_tanggal_acara_{$acara_num}_tanggal" ) );

							if ( $custom_hari ) {
								$hari_asli = ( new \IntlDateFormatter( $language, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'EEEE' ) )->format( $meta );
								$formatted = str_replace( $hari_asli, $custom_hari, $formatted );
							}

							if ( $custom_tgl ) {
								$tgl_asli  = ( new \IntlDateFormatter( $language, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'd' ) )->format( $meta );
								$formatted = preg_replace( '/\b' . preg_quote( $tgl_asli, '/' ) . '\b/', $custom_tgl, $formatted, 1 );
							}
						}

						$field_value = $formatted;
					} else {
						// Fallback to WordPress date_i18n if IntlDateFormatter is not available
						$field_value = date_i18n( $event_v2_format, $meta );
					}
				} else {
					$field_value = $meta;
				}
			}
		} elseif ( 'calendar' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$calendar );
			if ( in_array( $calendar_field, $allowed_fields ) ) {
				$meta = wds_post_meta( $post_id, $calendar_field );
				if ( '_calendar_start' == $calendar_field || '_calendar_end' == $calendar_field ) {
					$field_value = str_replace( 'T', ' ', $meta );
				} else {
					$field_value = $meta;
				}
			}
		} elseif ( 'gift' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$gift );
			if ( in_array( $gift_field, $allowed_fields ) ) {
				$meta = wds_post_meta( $post_id, $gift_field );
				if ( '_nama' == $gift_field || '_rekening' == $gift_field ) {
					$field_value = do_shortcode( '[jet_engine_data dynamic_field_source="meta" dynamic_field_post_meta="_amplop_digital" dynamic_field_post_meta_custom="' . $gift_field . '"]' );
				} elseif ( '_nama_bank_1' == $gift_field || '_nama_bank_2' == $gift_field || '_nama_bank_3' == $gift_field ) {
					$args        = array(
						'post_type'   => 'wds-bank',
						'meta_key'    => '_link',
						'meta_value'  => wds_post_meta( $post_id, $gift_field ),
						'numberposts' => 1,
					);
					$posts       = get_posts( $args );
					$field_value = ! empty( $posts ) ? $posts[0]->post_title : '';
				} else {
					$field_value = $meta;
				}
			}
		} elseif ( 'bride' == $post_field ) {
			if ( 'full_name' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_nama_lengkap_wanita' );
			} elseif ( 'nickname' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_nama_panggilan_wanita' );
			} elseif ( 'initial_nickname' == $bride_groom_field ) {
				$meta        = wds_post_meta( $post_id, '_nama_panggilan_wanita' );
				$field_value = substr( $meta, 0, 1 );
			} elseif ( 'description' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_deskripsi_wanita' );
			} elseif ( 'family' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_keluarga_wanita' );
			} elseif ( 'facebook' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_facebook_wanita' );
			} elseif ( 'instagram' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_instagram_wanita' );
			} elseif ( 'twitter' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_twitter_wanita' );
			} elseif ( 'tiktok' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_tiktok_wanita' );
			} elseif ( 'youtube' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_youtube_wanita' );
			}
		} elseif ( 'groom' == $post_field ) {
			if ( 'full_name' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_nama_lengkap_pria' );
			} elseif ( 'nickname' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_nama_panggilan_pria' );
			} elseif ( 'initial_nickname' == $bride_groom_field ) {
				$meta        = wds_post_meta( $post_id, '_nama_panggilan_pria' );
				$field_value = substr( $meta, 0, 1 );
			} elseif ( 'description' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_deskripsi_pria' );
			} elseif ( 'family' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_keluarga_pria' );
			} elseif ( 'facebook' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_facebook_pria' );
			} elseif ( 'instagram' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_instagram_pria' );
			} elseif ( 'twitter' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_twitter_pria' );
			} elseif ( 'tiktok' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_tiktok_pria' );
			} elseif ( 'youtube' == $bride_groom_field ) {
				$field_value = wds_post_meta( $post_id, '_youtube_pria' );
			}
		} elseif ( 'lovestory' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$lovestory );
			if ( in_array( $lovestory_field, $allowed_fields ) ) {
				$field_value = wds_post_meta( $post_id, $lovestory_field );
			}
		} elseif ( 'live' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$live );
			if ( in_array( $live_field, $allowed_fields ) ) {
				$field_value = wds_post_meta( $post_id, $live_field );
			}
		} elseif ( 'inviting' == $post_field ) {
			$field_value = wds_post_meta( $post_id, '_mengundang' );
		} elseif ( 'nonwedding' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$nonwedding );
			if ( in_array( $nonwedding_field, $allowed_fields ) ) {
				$field_value = wds_post_meta( $post_id, $nonwedding_field );
			}
		} elseif ( 'teks' == $post_field ) {
			$allowed_fields = array_keys( Default_Meta::$teks );
			if ( in_array( $teks_field, $allowed_fields ) ) {
				$field_value = wds_post_meta( $post_id, $teks_field );
			}
		} elseif ( 'reseller' == $post_field ) {
			if ( 'brand_name' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_name' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="name"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_name_fallback' );
				}
			} elseif ( 'logo_link' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_link' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="link"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_link_fallback' );
				}
			} elseif ( 'description' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_branding_description' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="description"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
				if ( empty( $field_value ) ) {
					$field_value = wds_option( 'reseller_desc_fallback' );
				}
			} elseif ( 'instagram' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_instagram' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="instagram"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
			} elseif ( 'facebook' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_facebook' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="facebook"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
			} elseif ( 'tiktok' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_tiktok' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="tiktok"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
			} elseif ( 'twitter' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_twitter' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="twitter"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
			} elseif ( 'youtube' == $reseller_field ) {
				$field_value = wds_user_meta( $user_id, '_youtube' );
				if ( wds_is_replica() ) {
					$wdr_shortcode = do_shortcode( '[wds_replica type="youtube"]' );
					$field_value   = ! empty( $wdr_shortcode ) ? $wdr_shortcode : $field_value;
				}
			}
		} elseif ( 'author' == $post_field ) {
			if ( '_wds_user_group' == $author_field ) {
				$field_value = get_user_meta( $user_id, $author_field, true );
			} elseif ( '_wds_user_membership' == $author_field ) {
				$field_value = get_user_meta( $user_id, $author_field, true );
			} elseif ( '_phone' == $author_field ) {
				$field_value = get_user_meta( $user_id, $author_field, true );
			}
		}

		if ( ! preg_match( '/\.(mp3|wav|jpg|png|pdf|docx)$/i', $field_value ) ) {
			$field_value = preg_replace( '/\*(.*?)\*/', '<b>$1</b>', $field_value ); // Bold
		}

		echo wp_kses_post( nl2br( $field_value ) );
	}
}
