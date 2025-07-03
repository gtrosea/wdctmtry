<?php $data = wds_data( 'data' ); ?>

<?php wds_header(); ?>

<?php do_action( 'wds_start_content' ); ?>

<div class="d-flex flex-column flex-root">

	<div class="page d-flex flex-row flex-column-fluid">

		<div id="kt_wrapper" class="wrapper d-flex flex-column flex-row-fluid">

			<?php wds_topbar(); ?>

			<div class="d-flex flex-column-fluid">

				<?php wds_sidebar(); ?>

				<div class="d-flex flex-column flex-column-fluid container-fluid">

					<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

						<div class="page-title d-flex flex-column me-3">

							<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( get_the_title() ); ?></h1>

							<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

								<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

								<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'access' ) ); ?></li>

							</ul>

						</div>

					</div>

					<div class="row g-6 g-xl-9 mb-6 mb-xl-9">

						<?php if ( ! empty( $data ) ) : ?>

							<?php foreach ( $data as $x ) : ?>

								<div class="col-md-6 col-lg-4 col-xl-3">

									<div class="card h-100">

										<div class="card-body d-flex justify-content-center text-center flex-column p-8">

											<div class="text-gray-800 text-hover-primary d-flex flex-column">

												<div class="symbol symbol-50px mb-5">
													<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $x['icon'] ); ?>" class="lozad rounded mw-100" />
												</div>

												<div class="fs-5 fs-md-4 fw-bold mb-2"><?php echo wp_kses_post( $x['title'] ); ?></div>

												<div class="fs-6 fw-semibold text-gray-600"><?php echo wp_kses_post( $x['desc'] ); ?></div>

												<a href="<?php echo esc_url( $x['url'] ); ?>" target="_blank" class="btn btn-primary mx-auto mt-4 d-block w-100"><?php echo esc_html( wds_lang( 'download' ) ); ?></a>

											</div>

										</div>

									</div>

								</div>

							<?php endforeach; ?>

						<?php endif; ?>

					</div>

					<?php wds_copyright(); ?>

				</div>

			</div>

		</div>

	</div>

</div>

<?php do_action( 'wds_end_content' ); ?>

<?php wds_footer(); ?>