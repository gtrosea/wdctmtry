<?php

namespace WDS\Integrations\Elementor;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * CommentPress Class.
 */
class CommentPress {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'autoptimize_filter_js_exclude', array( $this, 'autoptimize_exclude_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_enqueue' ) );

		add_shortcode( 'wds_rsvp', array( $this, 'shortcode' ) );

		add_action( 'wp_ajax_get_comments', array( $this, 'get_comments' ) );
		add_action( 'wp_ajax_nopriv_get_comments', array( $this, 'get_comments' ) );

		add_action( 'wp_ajax_insert_comment', array( $this, 'insert_comment' ) );
		add_action( 'wp_ajax_nopriv_insert_comment', array( $this, 'insert_comment' ) );

		// Admin dashboard
		add_action( 'add_meta_boxes_comment', array( $this, 'add_edit_meta_box' ) );
		add_action( 'edit_comment', array( $this, 'edit_meta_fields' ) );
		add_filter( 'comment_author', array( $this, 'add_meta_in_author_comment' ), 10000, 2 );
	}

	/**
	 * Exclude Scripts from Autoptimize
	 *
	 * @param string $ao_noptimize The current list of scripts excluded from optimization.
	 * @return string Updated list of excluded scripts.
	 */
	public function autoptimize_exclude_scripts( $ao_noptimize ) {
		$ao_noptimize = $ao_noptimize . ',jquery,wds-rsvp.js';

		return $ao_noptimize;
	}

	/**
	 * Enqueue scripts.
	 */
	public function add_enqueue() {
		// if ( 'default' != wds_option( 'rsvp_integration' ) ) {
		//  return;
		// }

		// if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		//  $post_id = wds_sanitize_data_field( $_GET, 'post' );
		// } else {
		//  $post = get_post();
		//  if ( ! $post || ! Helper::is_widget_used( 'wds_rsvp' ) ) { // ! has_shortcode( $post->post_content, 'wds_rsvp' )
		//      return;
		//  }

		//  $post_id = get_the_ID();
		// }

		// $post = get_post();
		// if ( ! $post || ! Helper::is_widget_used( 'wds_rsvp' ) ) { // ! has_shortcode( $post->post_content, 'wds_rsvp' )
		//  return;
		// }

		$post_id   = get_the_ID();
		$guest_max = wds_post_meta( $post_id, '_rsvp_max' ) ? wds_post_meta( $post_id, '_rsvp_max' ) : wds_option( 'rsvp_guest_max' );

		wp_register_style( 'saic_style', WDS_URL . 'assets/plugins/custom/commentpress/saic_style.css', array(), WDS_VERSION, 'screen' );
		wp_register_script( 'saic_library', WDS_URL . 'assets/plugins/custom/commentpress/saic_lib.js', array( 'jquery' ), WDS_VERSION, true );
		wp_register_script( 'wds_rsvp', wds_assets( 'js/wds-rsvp.js' ), array( 'jquery' ), WDS_VERSION, true );

		wp_enqueue_style( 'saic_style' );
		wp_enqueue_script( 'saic_library' );
		wp_enqueue_script( 'wds_rsvp' );

		wp_localize_script(
			'wds_rsvp',
			'WDS_RSVP',
			array(
				// 'post_id'          => $post_id,
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'wds_rsvp' ),
				'jPagesNum'        => wds_option( 'rsvp_guest_page' ),
				'textCounterNum'   => wds_option( 'rsvp_text_limit' ),
				'thanksComment'    => wds_lang( 'rsvp_thanks_comment' ),
				'duplicateComment' => wds_lang( 'rsvp_duplicate_comment' ),
				'guestMax'         => $guest_max,
				'textNavNext'      => __( 'Next', 'weddingsaas' ),
				'textNavPrev'      => __( 'Previous', 'weddingsaas' ),
				// 'thanksReplyComment' => wds_lang( 'rsvp_thanks_reply_comment' ),
				// 'textWriteComment'   => wds_lang( 'rsvp_write_comment' ),
			)
		);
	}

	/**
	 * Display the RSVP form and comment list for a post via a shortcode.
	 *
	 * @param array $atts Shortcode attributes:
	 *                   - 'post_id' (int) The ID of the post. Defaults to the current post.
	 *                   - 'get' (int) The number of comments to retrieve. Defaults to 500.
	 *                   - 'order' (string) The order in which comments are displayed.
	 * @return void Outputs the RSVP form and comment list HTML.
	 */
	public function shortcode( $atts = '' ) {

		global $post;

		$atts = shortcode_atts(
			array(
				'post_id' => $post->ID,
				'get'     => (int) 500,
				'order'   => wds_option( 'rsvp_order' ),
			),
			$atts
		);

		$post_id = $atts['post_id'];
		$join_id = wds_post_meta( $post_id, '_rsvp_join' ) ? wds_post_meta( $post_id, '_rsvp_join_id' ) : '';
		$post_id = $join_id ? $join_id : $post_id;
		$get     = $atts['get'];
		$order   = $atts['order'];
		$num     = get_comments_number( $post_id );

		$link = '?post_id=' . $post_id . '&comments=' . $num . '&get=' . $get . '&order=' . $order;

		$hide_attendance = wds_post_meta( $post_id, '_rsvp_hide_attendance' ) ? 'yes' : wds_option( 'rsvp_hide_attendance' );
		$hide_notsure    = wds_post_meta( $post_id, '_rsvp_hide_notsure' ) ? 'yes' : wds_option( 'rsvp_hide_notsure' );
		$guest_max       = wds_post_meta( $post_id, '_rsvp_max' ) ? wds_post_meta( $post_id, '_rsvp_max' ) : wds_option( 'rsvp_guest_max' );
		$text_name       = wds_post_meta( $post_id, '_rsvp_name' ) ? wds_post_meta( $post_id, '_rsvp_name' ) : wds_lang( 'rsvp_name' );
		$text_confirm    = wds_post_meta( $post_id, '_rsvp_confirm' ) ? wds_post_meta( $post_id, '_rsvp_confirm' ) : wds_lang( 'rsvp_confirmation_attendance' );
		$text_present    = wds_post_meta( $post_id, '_rsvp_present' ) ? wds_post_meta( $post_id, '_rsvp_present' ) : wds_lang( 'rsvp_attendance_present' );
		$text_notpresent = wds_post_meta( $post_id, '_rsvp_notpresent' ) ? wds_post_meta( $post_id, '_rsvp_notpresent' ) : wds_lang( 'rsvp_attendance_notpresent' );
		$text_notsure    = wds_post_meta( $post_id, '_rsvp_notsure' ) ? wds_post_meta( $post_id, '_rsvp_notsure' ) : wds_lang( 'rsvp_attendance_notsure' );
		$text_guest      = wds_post_meta( $post_id, '_rsvp_guest' ) ? wds_post_meta( $post_id, '_rsvp_guest' ) : wds_lang( 'rsvp_guest' );
		$text_person     = wds_post_meta( $post_id, '_rsvp_person' ) ? wds_post_meta( $post_id, '_rsvp_person' ) : wds_lang( 'rsvp_person' );
		$text_comment    = wds_post_meta( $post_id, '_rsvp_comment' ) ? wds_post_meta( $post_id, '_rsvp_comment' ) : wds_lang( 'rsvp_write_comment' );
		$text_submit     = wds_post_meta( $post_id, '_rsvp_submit' ) ? wds_post_meta( $post_id, '_rsvp_submit' ) : wds_lang( 'rsvp_send' );

		include wds_get_template( 'shortcode/rsvp.php' );
	}

	/**
	 * Retrieves and displays the comments for a specific post via AJAX.
	 *
	 * @return void Outputs the HTML for the comments list and exits the script.
	 */
	public function get_comments() {
		global $post;

		$_post = $_POST;

		if ( ! wp_verify_nonce( wds_sanitize_data_field( $_post, 'nonce' ), 'wds_rsvp' ) ) {
			die( 'Busted!' );
		}

		$post_id = (int) wds_sanitize_data_field( $_post, 'post_id', $post->ID );
		$get     = (int) wds_sanitize_data_field( $_post, 'get', 500 );
		$order   = wds_sanitize_data_field( $_post, 'order', wds_option( 'rsvp_order' ) );

		$comments_args = array(
			'post_id' => $post_id,
			'number'  => $get,
			'order'   => $order,
			'orderby' => 'comment_date',
			'offset'  => 0,
			'status'  => 'approve',
		);

		$comments = get_comments( $comments_args );
		$depth    = get_option( 'thread_comments_depth' );

		wp_list_comments(
			array(
				'callback'  => array( $this, 'get_comment_html' ),
				'max_depth' => $depth,
			),
			$comments
		);

		wp_die();
	}

	/**
	 * Retrieves and displays the comments for a specific post via AJAX.
	 *
	 * @return void Outputs the HTML for the comments list and exits the script.
	 */
	public function insert_comment() {
		$post = $_POST;

		if ( ! wp_verify_nonce( wds_sanitize_data_field( $post, 'nonce' ), 'wds_rsvp' ) || ! wds_sanitize_data_field( $post, 'commentpress' ) ) {
			die( 'error-' . esc_html__( 'Maaf, terdapat kesalahan. Ucapan tidak terkirim.', 'weddingsaas' ) );
		}

		$post_id = (int) wds_sanitize_data_field( $post, 'comment_post_ID', false );
		if ( ! $post_id ) {
			die( 'error-' . esc_html__( 'ID undangan tidak ditemukan.', 'weddingsaas' ) );
		}

		$author     = wds_sanitize_data_field( $post, 'author', wds_user_name() );
		$email      = is_user_logged_in() ? wds_user_email() : 'anonymous@wordpress.com';
		$attendance = wds_sanitize_data_field( $post, 'attendance' );
		$guest      = wds_sanitize_data_field( $post, 'guest' );
		$content    = wp_kses_post( $post['comment'] );

		if ( ! comments_open( $post_id ) ) {
			die( 'error-' . esc_html( wds_lang( 'rsvp_closed' ) ) );
		}

		if ( wp_check_comment_disallowed_list( $author, '', '', $content, '', '' ) ) {
			die( 'error-' . esc_html( wds_lang( 'rsvp_spam' ) ) );
		}

		$user_ip = \WDS_User_Info::get_ip();

		$commentdata = array(
			'comment_post_ID'      => $post_id,
			'comment_content'      => $content,
			'comment_type'         => '',
			'comment_parent'       => 0,
			'user_id'              => get_current_user_id(),
			'comment_author'       => $author,
			'comment_author_email' => $email,
			'comment_author_url'   => home_url(),
			'comment_author_IP'    => $user_ip,
			'comment_meta'         => array(
				'attendance' => $attendance,
				'guest'      => $guest,
			),
		);

		$comment_id = wp_insert_comment( $commentdata );

		if ( is_wp_error( $comment_id ) ) {
			echo 'error-Error processing form.';
		}

		do_action( 'wds_insert_comment', $comment_id );

		$this->get_comment_html( get_comment( $comment_id ), array(), 0 );

		wp_die();
	}

	/**
	 * Retrieves the appropriate RSVP comment icon based on the given key.
	 *
	 * @param string $key The RSVP status key ('present', 'notpresent', or 'notsure').
	 * @return string The SVG code for the corresponding icon, or an empty string if the key is not recognized.
	 */
	public function get_comment_icon( $key ) {
		$yes = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" xml:space="preserve" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 20 20"><path fill="#3d9a62" d="M17.645 8.032c-.294-.307-.599-.625-.714-.903-.106-.256-.112-.679-.118-1.089-.012-.762-.025-1.626-.626-2.227s-1.465-.614-2.227-.626c-.41-.006-.833-.012-1.089-.118-.278-.115-.596-.42-.903-.714-.54-.518-1.152-1.105-1.968-1.105-.816 0-1.428.587-1.968 1.105-.307.294-.625.599-.903.714-.256.106-.679.112-1.089.118-.762.012-1.626.025-2.227.626s-.614 1.465-.626 2.227c-.006.41-.012.833-.118 1.089-.115.278-.42.596-.714.903C1.837 8.572 1.25 9.184 1.25 10c0 .816.587 1.428 1.105 1.968.294.307.599.625.714.903.106.256.112.679.118 1.089.012.762.025 1.626.626 2.227s1.465.614 2.227.626c.41.006.833.012 1.089.118.278.115.596.42.903.714.54.518 1.152 1.105 1.968 1.105.816 0 1.428-.587 1.968-1.105.307-.294.625-.599.903-.714.256-.106.679-.112 1.089-.118.762-.012 1.626-.025 2.227-.626s.614-1.465.626-2.227c.006-.41.012-.833.118-1.089.115-.278.42-.596.714-.903.518-.54 1.105-1.152 1.105-1.968 0-.816-.587-1.428-1.105-1.968Zm-3.343-2.461a.882.882 0 0 0-1.222.256l-4.26 6.509-2.036-1.885a.885.885 0 0 0-1.2 1.297l2.815 2.604c.01.009.023.011.033.02.025.02.04.048.067.067.037.025.08.03.121.048a.86.86 0 0 0 .145.058.817.817 0 0 0 .147.023.883.883 0 0 0 .212-.003.89.89 0 0 0 .086-.02.887.887 0 0 0 .247-.103l.039-.028c.052-.036.108-.062.152-.11.031-.034.045-.078.071-.116l.003-.004 4.835-7.389a.89.89 0 0 0-.255-1.224Z"/></svg>';

		$no = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" xml:space="preserve" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 20 20"><path fill="#d90a11" d="M17.645 8.032c-.294-.307-.599-.625-.714-.903-.106-.256-.112-.679-.118-1.089-.012-.762-.025-1.626-.626-2.227s-1.465-.614-2.227-.626c-.41-.006-.833-.012-1.089-.118-.278-.115-.596-.42-.903-.714-.54-.518-1.152-1.105-1.968-1.105-.816 0-1.428.587-1.968 1.105-.307.294-.625.599-.903.714-.256.106-.679.112-1.089.118-.762.012-1.626.025-2.227.626s-.614 1.465-.626 2.227c-.006.41-.012.833-.118 1.089-.115.278-.42.596-.714.903C1.837 8.572 1.25 9.184 1.25 10c0 .816.587 1.428 1.105 1.968.294.307.599.625.714.903.106.256.112.679.118 1.089.012.762.025 1.626.626 2.227s1.465.614 2.227.626c.41.006.833.012 1.089.118.278.115.596.42.903.714.54.518 1.152 1.105 1.968 1.105.816 0 1.428-.587 1.968-1.105.307-.294.625-.599.903-.714.256-.106.679-.112 1.089-.118.762-.012 1.626-.025 2.227-.626s.614-1.465.626-2.227c.006-.41.012-.833.118-1.089.115-.278.42-.596.714-.903.518-.54 1.105-1.152 1.105-1.968 0-.816-.587-1.428-1.105-1.968Zm-3.94-1.737a1 1 0 0 0-1.418 0L10 8.592 7.713 6.295a1.002 1.002 0 0 0-1.418 1.418L8.592 10l-2.297 2.287a.998.998 0 0 0 0 1.418 1 1 0 0 0 1.418 0L10 11.408l2.287 2.297a.998.998 0 0 0 1.418 0 1 1 0 0 0 0-1.418L11.408 10l2.297-2.287a.998.998 0 0 0 0-1.418Z"/></svg>';

		$yes_or_no = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" xml:space="preserve" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" clip-rule="evenodd" viewBox="0 0 20 20"><path fill="#ffda73" d="M17.645 8.032c-.294-.307-.599-.625-.714-.903-.106-.256-.112-.679-.118-1.089-.012-.762-.025-1.626-.626-2.227s-1.465-.614-2.227-.625c-.41-.007-.833-.013-1.089-.119-.278-.115-.596-.42-.903-.714-.54-.518-1.152-1.105-1.968-1.105-.816 0-1.428.587-1.968 1.105-.307.294-.625.599-.903.714-.256.106-.679.112-1.089.119-.762.011-1.626.024-2.227.625s-.614 1.465-.625 2.227c-.007.41-.013.833-.119 1.089-.115.278-.42.596-.714.903C1.837 8.572 1.25 9.184 1.25 10c0 .816.587 1.428 1.105 1.968.294.307.599.625.714.903.106.256.112.679.119 1.089.011.762.024 1.626.625 2.227s1.465.614 2.227.626c.41.006.833.012 1.089.118.278.115.596.42.903.714.54.518 1.152 1.105 1.968 1.105.816 0 1.428-.587 1.968-1.105.307-.294.625-.599.903-.714.256-.106.679-.112 1.089-.118.762-.012 1.626-.025 2.227-.626s.614-1.465.626-2.227c.006-.41.012-.833.118-1.089.115-.278.42-.596.714-.903.518-.54 1.105-1.152 1.105-1.968 0-.816-.587-1.428-1.105-1.968ZM10 15a.942.942 0 0 1-.937-.937c0-.515.423-.938.937-.938s.938.423.938.938A.942.942 0 0 1 10 15Zm.625-3.82v.07a.628.628 0 0 1-.625.625.628.628 0 0 1-.625-.625v-.625c0-.342.282-.625.625-.625a1.57 1.57 0 0 0 1.562-1.562A1.57 1.57 0 0 0 10 6.875c-.857 0-1.563.706-1.563 1.563a.628.628 0 0 1-.625.625.628.628 0 0 1-.625-.625A2.826 2.826 0 0 1 10 5.626a2.825 2.825 0 0 1 2.812 2.812 2.82 2.82 0 0 1-2.187 2.742Z"/></svg>';

		switch ( $key ) {
			case 'present':
				$icon = $yes;
				break;
			case 'notpresent':
				$icon = $no;
				break;
			case 'notsure':
				$icon = $yes_or_no;
				break;
			default:
				$icon = $yes;
				break;
		}

		return $icon;
	}

	/**
	 * Retrieve and format comment text.
	 *
	 * Fetches the comment content from the database for a given comment ID,
	 * applies various formatting and WordPress filters to prepare it for display.
	 *
	 * @param int $comment_id The ID of the comment to retrieve.
	 * @return string The formatted comment content.
	 */
	public function get_comment_text( $comment_id ) {
		global $wpdb;

		// $comment_content = $wpdb->get_var( "SELECT comment_content FROM $wpdb->comments WHERE comment_ID = " . $comment_id );
		$comment_content = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT comment_content FROM $wpdb->comments WHERE comment_ID = %d",
				$comment_id
			)
		);
		$comment_content = wptexturize( $comment_content );
		$comment_content = nl2br( $comment_content );
		$comment_content = convert_chars( $comment_content );
		$comment_content = make_clickable( $comment_content );
		$comment_content = convert_smilies( $comment_content );
		$comment_content = force_balance_tags( $comment_content );
		$comment_content = wpautop( $comment_content );
		$comment_content = do_shortcode( $comment_content );

		return $comment_content;
	}

	/**
	 * Outputs the HTML structure for a single comment in the RSVP system.
	 *
	 * @param WP_Comment $comment The comment object.
	 * @param array      $args    An array of arguments passed to the comment template.
	 * @param int        $depth   The depth of the comment in the comment thread.
	 * @return void Outputs the HTML for the comment.
	 */
	public function get_comment_html( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		// extract( $args, EXTR_SKIP );

		$comment_post_id = $comment->comment_post_ID;
		$comment_id      = $comment->comment_ID;
		$comment_date    = $comment->comment_date;
		$autor_id        = $comment->user_id;
		$autor_name      = $comment->comment_author;
		$autor_info      = get_userdata( $autor_id );
		if ( is_object( $autor_info ) ) {
			$autor_name = $autor_info->display_name;
		}

		$avatar = wds_option( 'rsvp_avatar' );
		$avatar = 'image' == wds_option( 'rsvp_avatar_option' ) && ! empty( $avatar ) ? $avatar : 'https://ui-avatars.com/api/?background=random&color=random&name=' . rawurlencode( $autor_name );

		$atendence = get_comment_meta( $comment_id, 'attendance', true );
		$icon      = $this->get_comment_icon( $atendence );
		$content   = $this->get_comment_text( $comment_id );
		$time      = wdsrsvp_get_time_since( $comment_date, $comment_post_id );

		include wds_get_template( 'shortcode/rsvp_data.php' );
	}

	/**
	 * Displays the custom meta box for comment attendance in the comment edit screen.
	 *
	 * @param object $comment The comment object being edited.
	 * @return void This function does not return any value but outputs HTML to the comment edit screen.
	 */
	public function get_comment_meta_box( $comment ) {
		$meta = get_comment_meta( $comment->comment_ID, 'attendance', true );
		wp_nonce_field( 'meta_fields_update', 'meta_fields_update', false );

		$label = wds_lang( 'rsvp_confirmation_attendance' );

		$attendances = array(
			''           => $label,
			'present'    => wds_lang( 'rsvp_attendance_present' ),
			'notpresent' => wds_lang( 'rsvp_attendance_notpresent' ),
			'notsure'    => wds_lang( 'rsvp_attendance_notsure' ),
		);

		echo '<p>';
		echo '<label for="attendance" style="display:block;margin-bottom:8px;">' . esc_html( $label ) . '</label>';
		echo '<select name="attendance" id="attendance" class="form-control widefat">';
		foreach ( $attendances as $key => $attendance ) {
			$selected = in_array( $key, array( $meta, strtolower( $meta ) ) ) ? 'selected="selected"' : '';
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $key ),
				esc_attr( $selected ),
				esc_html( $attendance )
			);
		}
		echo '</select>';
		echo '</p>';
	}

	/**
	 * Adds a custom meta box to the comment edit screen for attendance information.
	 *
	 * @return void This function does not return any value but adds a meta box to the WordPress comment edit screen.
	 */
	public function add_edit_meta_box() {
		add_meta_box(
			'title',
			__( 'Attendance', 'wds-notrans' ),
			array( $this, 'get_comment_meta_box' ),
			'comment',
			'normal',
			'high'
		);
	}

	/**
	 * Saves the attendance data for a comment when it is edited.
	 *
	 * @param int $comment_id The ID of the comment being edited.
	 * @return void This function does not return any value but updates the comment meta in the database.
	 */
	public function edit_meta_fields( $comment_id ) {
		if ( ! isset( $_POST['meta_fields_update'] ) || ! wp_verify_nonce( $_POST['meta_fields_update'], 'meta_fields_update' ) ) {
			return;
		}

		$attendance = wds_sanitize_data_field( $_POST, 'attendance', false );
		if ( $attendance ) {
			update_comment_meta( $comment_id, 'attendance', strtolower( $attendance ) );
		} else {
			delete_comment_meta( $comment_id, 'attendance' );
		}
	}

	/**
	 * Adds attendance information to the author's comment display.
	 *
	 * @param string $name The author's name.
	 * @param int    $comment_id The ID of the comment being processed.
	 * @return string The modified name, including the attendance status and any associated guest count.
	 */
	public function add_meta_in_author_comment( $name, $comment_id ) {
		$attendance = get_comment_meta( $comment_id, 'attendance', true );
		$guest      = get_comment_meta( $comment_id, 'guest', true );

		$person = 'present' == $attendance && $guest ? ' (' . $guest . ' ' . wds_lang( 'rsvp_person' ) . ')' : '';

		if ( 'present' == $attendance ) {
			$text = '<span style="color:#3D9A62">' . wds_lang( 'rsvp_attendance_present' ) . '</span>' . $person;
		} elseif ( 'notpresent' == $attendance ) {
			$text = '<span style="color:#D90A11">' . wds_lang( 'rsvp_attendance_notpresent' ) . '</span>';
		} elseif ( 'notsure' == $attendance ) {
			$text = '<span style="color:#4264AA">' . wds_lang( 'rsvp_attendance_notsure' ) . '</span>';
		} else {
			$text = '';
		}

		return $name . ' - ' . $text;
	}
}

new CommentPress();
