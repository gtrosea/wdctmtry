<?php
/**
 * WeddingSaas Post.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Contents
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Post Class.
 */
class Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'csf_post_meta_value_unserialize', array( $this, 'modify_before_display' ), 10, 3 );
		add_filter( 'manage_post_posts_columns', array( $this, 'modify_post_table' ) );
		add_action( 'manage_post_posts_custom_column', array( $this, 'modify_post_table_row' ), 10, 2 );

		add_action( 'transition_post_status', array( $this, 'pending_post' ), 10, 3 );
		add_action( 'transition_post_status', array( $this, 'publish_pending_post' ), 10, 3 );
		add_action( 'transition_post_status', array( $this, 'publish_post' ), 10, 3 );

		add_action( 'added_post_meta', array( $this, 'modify_post_meta_added' ), 10, 4 );
		add_filter( 'post_row_actions', array( $this, 'add_custom_row_actions' ), 10, 2 );
		add_action( 'admin_bar_menu', array( $this, 'add_custom_admin_bar_menu' ), 999 );
		add_action( 'added_post_meta', array( $this, 'modify_post_meta_changed' ), 10, 4 );
		add_action( 'updated_post_meta', array( $this, 'modify_post_meta_changed' ), 10, 4 );
		add_action( 'updated_user_meta', array( $this, 'delete_user_cache' ), 10, 4 );
		add_action( 'wp', array( $this, 'update_visit_count' ) );

		add_action( 'wp_head', array( $this, 'add_custom_scripts_header' ) );
		add_action( 'wp_footer', array( $this, 'add_custom_scripts_footer' ) );
	}

	/**
	 * Modify post meta value before display.
	 *
	 * @param mixed  $value   The current value of the post meta field.
	 * @param int    $post_id The ID of the post.
	 * @param string $key     The meta key being retrieved.
	 * @return mixed The modified value for display.
	 */
	public function modify_before_display( $value, $post_id, $key ) {
		if ( '_wds_pep_period' == $key || '_wds_del_period' == $key ) {
			if ( ! empty( $value ) ) {
				// $value = date_i18n( 'd M Y', $value );
				$value = gmdate( 'd M Y', $value );
			}
		}

		return $value;
	}

	/**
	 * Modify the columns displayed in the post table.
	 *
	 * @param array $columns The existing columns in the post table.
	 * @return array Modified columns.
	 */
	public function modify_post_table( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'comments' == $key ) {
				$new_columns['wds_pep_period'] = __( 'Active Period', 'wds-notrans' );
				$new_columns['wds_del_period'] = __( 'Auto Delete', 'wds-notrans' );
			}
		}

		return $new_columns;
	}

	/**
	 * Modify the data displayed in custom columns in the post table.
	 *
	 * @param string $column  The name of the column.
	 * @param int    $post_id The ID of the post being displayed.
	 */
	public static function modify_post_table_row( $column, $post_id ) {
		switch ( $column ) {
			case 'wds_pep_period':
				$period = wds_post_meta( $post_id, '_wds_pep_period' );
				$delete = wds_post_meta( $post_id, '_wds_del_period' );
				if ( ! empty( $period ) ) {
					echo esc_html( date_i18n( get_option( 'date_format' ), $period ) );
				} elseif ( ! empty( $delete ) ) {
					echo '-';
				} else {
					echo esc_html( wds_lang( 'lifetime' ) );
				}
				break;
			case 'wds_del_period':
				$period = wds_post_meta( $post_id, '_wds_del_period' );
				if ( ! empty( $period ) ) {
					echo esc_html( date_i18n( get_option( 'date_format' ), $period ) );
				} else {
					echo '-';
				}
				break;
		}
	}

	/**
	 * Handle post transition to 'pending' status.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post object.
	 */
	public function pending_post( $new_status, $old_status, $post ) {
		if ( 'pending' === $new_status && 'pending' !== $old_status && 'post' === $post->post_type ) {
			$post_id    = $post->ID;
			$user_id    = get_post_field( 'post_author', $post_id );
			$quota      = intval( wds_user_invitation_quota( $user_id ) );
			$new_quota  = $quota - 1;
			$membership = wds_user_membership( $user_id );
			$post_slug  = get_post_field( 'post_name', $post_id );

			if ( $quota > 0 ) {
				update_user_meta( $user_id, '_wds_invitation_quota', $new_quota );
				update_post_meta( $post_id, '_wds_membership', $membership );
			} else {
				wp_trash_post( $post_id );
			}

			wds_delete_cache_user( $user_id );
			update_post_meta( $post_id, '_slug', $post_slug );
		}
	}

	/**
	 * Handle post transition from 'pending' to 'publish' status.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post object.
	 */
	public function publish_pending_post( $new_status, $old_status, $post ) {
		if ( 'publish' === $new_status && 'publish' !== $old_status && 'pending' == $old_status && 'post' === $post->post_type ) {
			$post_id = $post->ID;
			$user_id = get_post_field( 'post_author', $post_id );

			$invitation_duration = wds_user_meta( $user_id, '_wds_invitation_duration' );
			$invitation_period   = wds_user_meta( $user_id, '_wds_invitation_period' );
			$invitation_action   = wds_user_meta( $user_id, '_wds_invitation_action' );

			if ( $invitation_duration ) {
				$today        = current_time( 'timestamp' );
				$duration_new = "+$invitation_duration $invitation_period";
				$expired      = strtotime( $duration_new, $today );

				update_post_meta( $post_id, '_wds_pep_period', $expired );
				update_post_meta( $post_id, '_wds_pep_action', $invitation_action );
			} else {
				update_post_meta( $post_id, '_wds_pep_period', '' );
				update_post_meta( $post_id, '_wds_pep_action', '' );
				update_post_meta( $post_id, '_wds_del_period', '' );
			}
		}
	}

	/**
	 * Handle post transition to 'publish' status.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post object.
	 */
	public function publish_post( $new_status, $old_status, $post ) {
		if ( 'publish' === $new_status && 'publish' !== $old_status && 'pending' !== $old_status && 'post' === $post->post_type ) {
			$post_id   = $post->ID;
			$user_id   = get_post_field( 'post_author', $post_id );
			$quota     = intval( wds_user_invitation_quota( $user_id ) );
			$new_quota = $quota - 1;

			$membership          = wds_user_membership( $user_id );
			$invitation_duration = wds_user_meta( $user_id, '_wds_invitation_duration' );
			$invitation_period   = wds_user_meta( $user_id, '_wds_invitation_period' );
			$invitation_action   = wds_user_meta( $user_id, '_wds_invitation_action' );

			$user_group   = wds_user_group( $user_id );
			$wds_order_id = intval( wds_user_order_id( $user_id ) );

			$post_slug = get_post_field( 'post_name', $post_id );

			if ( $quota > 0 ) {
				update_user_meta( $user_id, '_wds_invitation_quota', $new_quota );

				if ( $invitation_duration ) {
					$today        = current_time( 'timestamp' );
					$duration_new = "+$invitation_duration $invitation_period";
					$expired      = strtotime( $duration_new, $today );

					update_post_meta( $post_id, '_wds_membership', $membership );
					update_post_meta( $post_id, '_wds_pep_period', $expired );
					update_post_meta( $post_id, '_wds_pep_action', $invitation_action );
					update_post_meta( $post_id, '_wds_del_period', '' );

					if ( 'trial' == $user_group ) {
						update_post_meta( $post_id, '_wds_order_id', $wds_order_id );
					}
				} else {
					update_post_meta( $post_id, '_wds_membership', $membership );
					update_post_meta( $post_id, '_wds_pep_period', '' );
					update_post_meta( $post_id, '_wds_pep_action', '' );
					update_post_meta( $post_id, '_wds_del_period', '' );
				}
			} else {
				wp_trash_post( $post_id );
			}

			wds_delete_cache_user( $user_id );
			update_post_meta( $post_id, '_slug', $post_slug );
		}
	}

	/**
	 * Modify post meta when added.
	 *
	 * @param int    $meta_id    ID of the metadata entry.
	 * @param int    $post_id    Post ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 */
	public function modify_post_meta_added( $meta_id, $post_id, $meta_key, $meta_value ) {
		if ( '_price' == $meta_key ) {
			$user_id = get_post_field( 'post_author', $post_id );

			$income_args = array(
				'user_id' => $user_id,
				'data_id' => $post_id,
				'type'    => 'invitation',
				'price'   => $meta_value,
			);

			$income_id = wds_insert_income( $income_args );
		}
	}

	/**
	 * Add custom row actions for posts in the post list table.
	 *
	 * @param array   $actions The existing row actions.
	 * @param WP_Post $post    The current post object.
	 * @return array Modified row actions.
	 */
	public function add_custom_row_actions( $actions, $post ) {
		if ( 'post' == $post->post_type ) {
			$edit_invitation_url = wds_url( 'edit', $post->ID );

			$actions['edit_invitation'] = '<a href="' . esc_url( $edit_invitation_url ) . '" target="_blank">' . __( 'Edit Undangan', 'weddingsaas' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Add custom menu items to the WordPress admin bar.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance, passed by reference.
	 */
	public function add_custom_admin_bar_menu( $wp_admin_bar ) {
		if ( ! wds_is_admin() && ! wds_is_editor() ) {
			return;
		}

		global $post;

		if ( is_single() && 'post' == $post->post_type ) {
			$edit_invitation_url = wds_url( 'edit', $post->ID );

			$args_edit = array(
				'id'    => 'edit_invitation',
				'title' => __( 'Edit Undangan', 'weddingsaas' ),
				'href'  => esc_url( $edit_invitation_url ),
				'meta'  => array(
					'target' => '_blank',
				),
			);

			$wp_admin_bar->add_node( $args_edit );

			$view_comments_url = admin_url( 'edit-comments.php?p=' . $post->ID . '&comment_status=approved' );
			$args_comments     = array(
				'id'    => 'view_comments',
				'title' => __( 'Lihat Ucapan', 'weddingsaas' ),
				'href'  => esc_url( $view_comments_url ),
				'meta'  => array(
					'target' => '_blank',
				),
			);

			$wp_admin_bar->add_node( $args_comments );
		}

		if ( 'dashboard/invitation/edit' == wds_is_page() ) {
			$url  = admin_url( 'admin.php?page=weddingsaas-settings#tab=memberships/invitation-edit' );
			$args = array(
				'id'    => 'edit_invitation_form',
				'title' => __( 'Edit Form', 'weddingsaas' ),
				'href'  => esc_url( $url ),
				'meta'  => array(
					'target' => '_blank',
				),
			);

			$wp_admin_bar->add_node( $args );
		}

		if ( 'dashboard/landingpage/edit' == wds_is_page() ) {
			$url  = admin_url( 'admin.php?page=weddingsaas-settings#tab=addons/replica' );
			$args = array(
				'id'    => 'edit_landingpage_form',
				'title' => __( 'Edit Form', 'weddingsaas' ),
				'href'  => esc_url( $url ),
				'meta'  => array(
					'target' => '_blank',
				),
			);

			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * Modify post meta when updated.
	 *
	 * @param int    $meta_id    ID of the metadata entry.
	 * @param int    $post_id    Post ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 */
	public function modify_post_meta_changed( $meta_id, $post_id, $meta_key, $meta_value ) {
		if ( '_slug' == $meta_key ) {
			$new_slug  = sanitize_title( $meta_value );
			$post_data = array(
				'ID'        => $post_id,
				'post_name' => $new_slug,
			);

			$update = wp_update_post( $post_data );

			if ( $update ) {
				$post         = get_post( $post_id );
				$slug_updated = $post->post_name;
				update_post_meta( $post_id, $meta_key, $slug_updated );
			}
		}

		if ( '_price' == $meta_key ) {
			wds_update_income( 'invitation', $post_id, $meta_value );
		}

		if ( '_visitor' != $meta_key ) {
			wds_delete_cache_post( $post_id );
		}
	}

	/**
	 * Delete user cache when user meta is updated.
	 *
	 * @param int    $meta_id    ID of the metadata entry.
	 * @param int    $user_id    User ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 */
	public function delete_user_cache( $meta_id, $user_id, $meta_key, $meta_value ) {
		wds_delete_cache_user( $user_id );
	}

	/**
	 * Update the visit count for a post.
	 */
	public function update_visit_count() {
		if ( ! is_single() ) {
			return;
		}

		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			$post_type = get_post_type( $post_id );
			if ( 'post' === $post_type ) {
				$meta        = intval( get_post_meta( $post_id, '_visitor', true ) );
				$visit_count = ! empty( $meta ) ? $meta : 0;
				++$visit_count;
				update_post_meta( $post_id, '_visitor', $visit_count );
			}
		}
	}

	/**
	 * Add custom scripts header.
	 */
	public function add_custom_scripts_header() {
		$header_scripts = wds_post_meta( get_the_ID(), '_header_scripts' );

		if ( ! empty( $header_scripts ) ) {
			$allowed_html = array(
				'script' => array(),
				'style'  => array(),
				'link'   => array(
					'href'  => array(),
					'rel'   => array(),
					'type'  => array(),
					'media' => array(),
				),
				'meta'   => array(
					'name'     => array(),
					'content'  => array(),
					'property' => array(),
				),
				'div'    => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
				'span'   => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
			);
			echo wp_kses( $header_scripts, $allowed_html );
		}
	}

	/**
	 * Add custom scripts footer.
	 */
	public function add_custom_scripts_footer() {
		$footer_scripts = wds_post_meta( get_the_ID(), '_footer_scripts' );

		if ( ! empty( $footer_scripts ) ) {
			$allowed_html = array(
				'script' => array(),
				'style'  => array(),
				'link'   => array(
					'href'  => array(),
					'rel'   => array(),
					'type'  => array(),
					'media' => array(),
				),
				'meta'   => array(
					'name'     => array(),
					'content'  => array(),
					'property' => array(),
				),
				'div'    => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
				'span'   => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
			);
			echo wp_kses( $footer_scripts, $allowed_html );
		}
	}
}

new Post();
