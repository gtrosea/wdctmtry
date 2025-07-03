<?php $data = wds_data( 'data_access' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'access' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'access' ) ); ?></li>

		</ul>

	</div>

</div>

<?php if ( wds_check_array( $data, true ) ) : ?>

	<div class="row g-6 g-xl-9 mb-6 mb-xl-9">

		<?php foreach ( $data as $post ) : ?>

			<?php
			$title = $post['title'];
			$thumb = $post['thumbnail'];
			$thumb = ! empty( $thumb ) ? $thumb : WDS_BLANK_IMAGE;
			$link  = get_permalink( $post['post_id'] );
			?>

			<div class="col-md-6 col-lg-4 col-xl-3">

				<div class="card h-100">

					<div class="card-body d-flex justify-content-center text-center flex-column p-8">

						<div class="text-gray-800 text-hover-primary d-flex flex-column">

							<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $thumb ); ?>" class="lozad rounded mw-100 mb-5 rounded-4" />

							<div class="fs-5 fs-md-4 fw-bold mb-2"><?php echo esc_html( $title ); ?></div>

							<a href="<?php echo esc_url( $link ); ?>" target="_blank" class="btn btn-primary mx-auto mt-4 d-block w-100"><?php echo esc_html( wds_lang( 'access_product' ) ); ?></a>

						</div>

					</div>

				</div>

			</div>

		<?php endforeach; ?>

	</div>

<?php else : ?>

	<div id="kt_content" class="content flex-column-fluid">

		<div class="alert bg-light-danger d-flex flex-center border-danger flex-column w-md-500px py-10 px-10 px-lg-20 m-0 me-md-auto ms-md-0">

			<i class="ki-duotone ki-information-5 fs-5tx text-danger mb-5">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
			</i>

			<div class="text-center">

				<h1 class="fw-bold mb-5"><?php echo esc_html( wds_lang( 'dash_access_title' ) ); ?></h1>

				<div class="separator separator-dashed border-danger opacity-25 mb-5"></div>

				<span><?php echo wp_kses_post( wds_lang( 'dash_access_subtitle' ) ); ?></span>

			</div>

		</div>

	</div>

<?php endif; ?>