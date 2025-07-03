<?php wds_header(); ?>

<?php do_action( 'wds_start_content' ); ?>

<div class="d-flex flex-column flex-root">

	<div class="d-flex flex-column flex-center flex-column-fluid">

		<div class="d-flex flex-column flex-center p-10">

			<div class="mb-12">

				<a href="<?php echo esc_url( home_url() ); ?>">
					<?php if ( wds_data( 'logo_light' ) && wds_data( 'logo_dark' ) ) : ?>
						<img src="<?php echo esc_url( wds_data( 'logo_light' ) ); ?>" class="w-200px theme-light-show" alt="Logo" />
						<img src="<?php echo esc_url( wds_data( 'logo_dark' ) ); ?>" class="w-200px theme-dark-show" alt="Logo" />
					<?php else : ?>
						<span class="text-body theme-light-show fs-3qx lh-1 fw-bold"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
						<span class="text-white theme-dark-show fs-3qx lh-1 fw-bold"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<?php endif; ?>
				</a>

			</div>

			<div class="card card-flush w-lg-650px py-5">

				<?php wds_dark_mode(); ?>

				<div class="card-body py-15 px-lg-15 py-lg-20">

					<?php load_template( wds_data( 'template' ) ); ?>

				</div>

			</div>

		</div>

	</div>

</div>

<?php do_action( 'wds_end_content' ); ?>

<?php wds_footer(); ?>