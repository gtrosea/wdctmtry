<?php
$post_id    = intval( wds_data( 'post_id' ) );
$author_id  = intval( get_post_field( 'post_author', $post_id ) );
$_thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );
$thumbnail  = ! empty( $_thumbnail ) ? $_thumbnail : wds_option( 'placeholder' );
$title      = get_the_title( $post_id );
$permalink  = wds_invitation_open( $post_id );

$disable_button = wds_check_array( wds_option( 'wdr_disable_button' ) );
$permalink_open = wds_is_replica() && ( ! empty( $disable_button ) || in_array( 'open', $disable_button ) ) ? wds_invitation_open( $post_id, false ) : $permalink;

$slug          = basename( get_permalink( $post_id ) );
$publish_date  = get_the_date( '', $post_id );
$modified_date = get_the_modified_date( '', $post_id );
$author        = get_the_author_meta( 'first_name', $author_id ) . ' ' . get_the_author_meta( 'last_name', $author_id );
$expired       = do_shortcode( '[wds_invitation_duration_by_id]' );
$expired       = ! empty( $expired ) ? wds_lang( 'expired' ) . ' ' . $expired : wds_lang( 'active_lifetime' );
$visitor       = get_post_meta( $post_id, '_visitor', true );
$user_group    = wds_user_group();

$layout           = wds_option( 'invitation_edit_layout' );
$rsvp_integration = wds_option( 'rsvp_integration' );

$data_v1 = wds_option( 'invitation_edit_v1' );
$data_v2 = wds_option( 'invitation_edit_v2' );

$categories     = get_the_category( $post_id );
$id_category    = 0;
$jetformbuilder = '';

if ( $categories ) {
	foreach ( $categories as $category ) {
		$id_category = $category->cat_ID;
	}
}

$taxonomy        = wds_get_taxonomy_by_post_id( $post_id );
$theme_id        = wds_get_taxonomy_theme_id( $post_id, $taxonomy );
$parent_theme_id = wds_get_parent_taxonomy_id( $theme_id );
?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( $title ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'edit_invitation' ) ); ?></li>

		</ul>

	</div>

</div>

<?php do_action( 'wds_entry_content' ); ?>

<div class="card mb-5 mb-xxl-8">

	<div class="card-body pt-9 pb-5">

		<div class="d-flex flex-wrap flex-sm-nowrap">

			<div class="me-0 me-md-7 mb-4">

				<div class="d-flex justify-content-center w-md-200px h-md-200px">

					<?php if ( $thumbnail ) : ?>
						<img style="object-fit: cover; object-position: center;" src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $thumbnail ); ?>" class="lozad rounded h-100 w-100" alt="" />
					<?php else : ?>
						<img src="<?php echo esc_url( WDS_BLANK_IMAGE ); ?>" class="rounded h-100 w-100" alt="image" />
					<?php endif; ?>

				</div>

			</div>

			<div class="flex-grow-1">

				<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">

					<div class="d-flex flex-column">

						<div class="d-flex align-items-center mb-2">

							<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo esc_html( $title ); ?></a>

						</div>

						<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">

							<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
								<i class="ki-duotone ki-profile-circle fs-4 me-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
								<?php echo esc_html( wds_lang( 'dash_invitation_by' ) . ' ' . $author ); ?>
							</a>

							<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
								<i class="ki-duotone ki-calendar-add fs-4 me-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
									<span class="path6"></span>
								</i>
								<?php echo esc_html( wds_lang( 'dash_invitation_created' ) . ' ' . $publish_date ); ?>
							</a>

							<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
								<i class="ki-duotone ki-calendar-remove fs-4 me-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
									<span class="path6"></span>
								</i>
								<?php echo esc_html( $expired ); ?>
							</a>

						</div>

					</div>

					<div class="my-4">

						<div class="d-flex flex-wrap justify-content-between">

							<a href="<?php echo esc_url( wds_url( 'rsvp', $post_id ) ); ?>" class="btn btn-sm btn-secondary me-3 mb-3 flex-fill">
								<i class="ki-outline ki-messages"></i>
								<?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?>
							</a>

							<a href="<?php echo esc_url( wds_url( 'share', $post_id ) ); ?>" class="btn btn-sm btn-success me-0 me-md-3 mb-3 flex-fill">
								<i class="ki-outline ki-share"></i>
								<?php echo esc_html( wds_lang( 'dash_invitation_share' ) ); ?>
							</a>

							<a href="<?php echo esc_url( $permalink_open ); ?>" target="_blank" class="btn btn-sm btn-primary me-md-3 mb-3 w-100 w-md-auto" id="open-invitation">
								<i class="ki-outline ki-monitor-mobile"></i>
								<?php echo esc_html( wds_lang( 'dash_invitation_edit_open' ) ); ?>
							</a>

							<?php if ( 'reseller' == $user_group ) : ?>

								<a href="<?php echo esc_url( wds_url( 'share_client', $post_id ) ); ?>" class="btn btn-sm btn-warning mb-3 w-100 w-md-auto">
									<i class="ki-outline ki-send"></i>
									<?php echo esc_html( wds_lang( 'dash_invitation_send_client' ) ); ?>
								</a>

							<?php endif; ?>

						</div>

					</div>

				</div>

				<div class="d-flex flex-wrap flex-stack">

					<div class="d-flex flex-column flex-grow-1 pe-8">

						<div class="d-flex justify-content-between">

							<div class="border border-gray-300 border-dashed rounded w-100 py-3 px-4 me-4 text-center">
								<div class="d-flex align-items-center justify-content-center">
									<div class="fs-3 fw-bold" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $visitor ); ?>">0</div>
								</div>
								<div class="fw-semibold fs-6 text-gray-400"> <?php echo esc_html( wds_lang( 'dash_invitation_visitor' ) ); ?> </div>
							</div>

							<div class="border border-gray-300 border-dashed rounded w-100 py-3 px-4 me-4 text-center">
								<div class="d-flex align-items-center justify-content-center">
									<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( wds_invitation_comment_count( $post_id ) ); ?>">0</div>
								</div>
								<div class="fw-semibold fs-6 text-gray-400"><?php echo esc_html( wds_lang( 'dash_invitation_comment' ) ); ?></div>
							</div>

							<?php if ( 'default' == $rsvp_integration ) : ?>

								<div class="border border-gray-300 border-dashed rounded w-100 py-3 px-4 text-center">
									<div class="d-flex align-items-center justify-content-center">
										<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( wds_invitation_rsvp_count( $post_id ) ); ?>">0</div>
									</div>
									<div class="fw-semibold fs-6 text-gray-400"><?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?></div>
								</div>

							<?php endif; ?>

						</div>

					</div>

					<?php if ( ! wds_option( 'invitation_copy_link' ) ) : ?>

						<div class="col-xl-6">

							<div class="d-flex mt-6">

								<input type="text" name="search" id="permalink_link_input" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_url( $permalink ); ?>" readonly />

								<button id="permalink_link_copy_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0" data-clipboard-target="#permalink_link_input"><?php echo esc_html( wds_lang( 'dash_invitation_copy' ) ); ?></button>

							</div>

						</div>

					<?php endif; ?>

				</div>

			</div>

		</div>

	</div>

</div>

<?php if ( '1' == $layout && wds_check_array( $data_v1, true ) ) : ?>

	<?php
	foreach ( $data_v1 as $item ) {
		if ( in_array( $id_category, $item['category'] ) || empty( $item['category'] ) ) {
			$jetformbuilder = $item['shortcode'];
			break;
		}
	}
	?>

	<div id="kt_content" class="content flex-column-fluid">
		<div class="card mb-5 mb-xl-10">
			<div class="card-body py-10">
				<?php echo do_shortcode( $jetformbuilder ); ?>
			</div>
		</div>
	</div>

<?php elseif ( '2' == $layout && wds_check_array( $data_v2, true ) ) : ?>
	
	<div class="row g-6 g-xl-9 mb-6 mb-xl-9">

		<?php foreach ( $data_v2 as $item ) : ?>

			<?php
			$icon      = wds_sanitize_data_field( $item, 'icon' );
			$title     = wds_sanitize_data_field( $item, 'title' );
			$slug      = sanitize_title( $title );
			$shortcode = wds_sanitize_data_field( $item, 'shortcode' );
			$category  = wds_sanitize_data_field( $item, 'category', array() );
			$product   = wds_sanitize_data_field( $item, 'product', array() );
			$subtheme  = wds_sanitize_data_field( $item, 'subtheme', array() );

			if ( empty( $category ) || in_array( $id_category, $category ) ) :
				if ( empty( $product ) || in_array( wds_post_membership(), $product ) ) :
					if ( empty( $subtheme ) || in_array( $parent_theme_id, $subtheme ) ) :
						?>

						<div class="col-6 col-lg-4 col-xl-3">

							<div class="card h-100">

								<div class="card-body d-flex justify-content-center text-center flex-column p-6">

									<a href="#" class="text-gray-800 text-hover-primary d-flex flex-column" data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr( $slug ); ?>">

										<div class="symbol symbol-35px symbol-md-60px mb-5">
											<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $icon ); ?>" class="lozad rounded mw-100" />
										</div>

										<div class="fs-6 fs-md-5 fw-bold"><?php echo esc_html( $title ); ?></div>

									</a>

								</div>

							</div>
							
						</div>

						<div class="modal fade" id="<?php echo esc_attr( $slug ); ?>" tabindex="-1" aria-hidden="true">

							<div class="modal-dialog modal-dialog-centered mw-<?php echo ! empty( wds_option( 'invitation_edit_popup' ) ) ? esc_attr( wds_option( 'invitation_edit_popup' ) ) : 650; ?>px">

								<div class="modal-content">

									<div class="modal-header">

										<h2 class="fw-bold"><?php echo esc_html( $title ); ?></h2>

										<div id="add_invitation_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-bg-light btn-active-icon-danger">
											<i class="ki-solid ki-cross fs-1"></i>
										</div>

									</div>

									<div class="modal-body overlay py-10 px-lg-17">

										<div class="scroll-y ms-n2 me-n7 ps-2 pe-7" id="kt_modal_edit_invitation_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_invitation_header" data-kt-scroll-wrappers="#kt_modal_edit_invitation_scroll" data-kt-scroll-offset="300px">
											<?php echo do_shortcode( $shortcode ); ?>
										</div>

										<div id="form-overlay" class="overlay-layer bg-dark bg-opacity-5 d-none">
											<div class="spinner-border text-primary" role="status">
												<span class="visually-hidden">Loading...</span>
											</div>
										</div>

									</div>

								</div>

							</div>

						</div>

					<?php endif; ?>

				<?php endif; ?>

			<?php endif; ?>

		<?php endforeach; ?>

	</div>

<?php endif; ?>

<?php if ( ( ! wds_is_replica() && empty( $disable_button ) ) || ( ! empty( $disable_button ) && in_array( 'open', $disable_button ) ) ) : ?>

	<script type="text/javascript">
		document.getElementById('open-invitation').addEventListener('click', function(e) {
			e.preventDefault();

			var url = this.href;
			var id = '<?php echo esc_html( $post_id ); ?>';
			var windowName = 'undangan_' + id;
			var targetWindow = window.open('', windowName);

			if (targetWindow.location.href === 'about:blank') {
				targetWindow.location.href = url;
			} else if (targetWindow.location.href !== url) {
				targetWindow = window.open(url, '_blank');
			} else {
				targetWindow.location.reload();
			}

			targetWindow.focus();
		});
	</script>

<?php endif; ?>
