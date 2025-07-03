<?php $data = wds_option( 'marketing_data' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'marketing' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'marketing' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<div class="row g-6 g-xl-9 mb-6 mb-xl-9">

		<?php if ( wds_check_array( $data, true ) ) : ?>

			<?php foreach ( $data as $item ) : ?>

				<?php
				$icon  = wds_sanitize_data_field( $item, 'icon' );
				$title = wds_sanitize_data_field( $item, 'title' );
				$desc  = wds_sanitize_data_field( $item, 'desc' );
				$url   = wds_sanitize_data_field( $item, 'url' );
				?>

				<div class="col-md-6 col-lg-4 col-xl-3">

					<div class="card h-100">

						<div class="card-body d-flex justify-content-center text-center flex-column p-8">

							<a href="<?php echo esc_url( $url ); ?>" target="_blank" class="text-gray-800 text-hover-primary d-flex flex-column">

								<div class="symbol symbol-50px mb-5">
									<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $icon ); ?>" class="lozad rounded mw-100" />
								</div>

								<div class="fs-5 fs-md-4 fw-bold mb-2"><?php echo wp_kses_post( $title ); ?></div>

								<div class="fs-6 fw-semibold text-gray-600"><?php echo wp_kses_post( $desc ); ?></div>

							</a>

						</div>

					</div>

				</div>

			<?php endforeach; ?>

		<?php endif; ?>

	</div>

</div>