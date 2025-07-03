<?php

namespace WDS\Frontend\Ajax;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Publics Class.
 */
class Publics {

	/**
	 * Get data message.
	 */
	public static function share_get_message() {
		$message  = '';
		$selected = wds_sanitize_data_field( $_POST, 'category', false );
		if ( $selected ) {
			$categories = wds_option( 'share_data' );
			foreach ( $categories as $category ) {
				if ( html_entity_decode( $category['title'] ) == $selected ) {
					$message = html_entity_decode( $category['text'] );
				}
			}

			wp_send_json_success( $message );
		}

		wp_die();
	}

	/**
	 * Process update data.
	 */
	public static function share_process_data() {
		$post = $_POST;

		$post_id  = wds_sanitize_data_field( $post, 'post_id', false );
		$restrict = wds_sanitize_data_field( $post, 'restrict', false );

		$guest   = $post['guest'];
		$message = $post['message'];

		if ( wds_option( 'restrict_invitation' ) && $post_id && $restrict ) {
			if ( 'yes' == $restrict ) {
				$data = array(
					'guest' => $guest,
					'text'  => $message,
				);

				update_post_meta( $post_id, '_restrict', $data );
			} else {
				update_post_meta( $post_id, '_restrict', '' );
			}
		}

		wds_delete_cache_post( intval( $post_id ) );
		wp_send_json_success(
			array(
				'guest'   => $guest,
				'text'    => $message,
				'message' => wds_lang( 'public_share_notice_success' ),
			)
		);
	}
}
