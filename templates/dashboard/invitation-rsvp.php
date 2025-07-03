<?php
$rsvp        = wds_data( 'data_rsvp' );
$data        = wds_data( 'data_attendance' );
$post_id     = wds_data( 'post_id' );
$title       = get_the_title( $post_id );
$integration = wds_option( 'rsvp_integration' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php esc_html_e( 'RSVP', 'wds-notrans' ); ?> <?php echo do_shortcode( '[wds_title_by_id]' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-600"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'RSVP', 'wds-notrans' ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php require_once wds_get_template( 'partials/rsvp_data.php' ); ?>

</div>