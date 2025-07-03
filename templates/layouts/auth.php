<?php
wds_header();

$reseller_id = wds_data( 'wds_custome_host' ) && wds_data( 'reseller_id' ) ? wds_data( 'reseller_id' ) : 0;
$logo_link   = wds_user_meta( $reseller_id, '_branding_link' ) ? wds_user_meta( $reseller_id, '_branding_link' ) : home_url();
$site_name   = wds_user_meta( $reseller_id, '_branding_name' ) ? wds_user_meta( $reseller_id, '_branding_name' ) : get_bloginfo( 'name' );
$logo_light  = wds_user_meta( $reseller_id, '_branding_logo' ) ? wds_user_meta( $reseller_id, '_branding_logo' ) : wds_data( 'logo_light' );
$logo_dark   = wds_user_meta( $reseller_id, '_branding_logo' ) ? wds_user_meta( $reseller_id, '_branding_logo' ) : wds_data( 'logo_dark' );
$tagline     = wds_user_meta( $reseller_id, '_branding_description' ) ? wds_user_meta( $reseller_id, '_branding_description' ) : wds_option( 'tagline' );
$copyright   = wds_user_meta( $reseller_id, '_branding_name' ) ? 'Copyright © ' . gmdate( 'Y' ) . ' - ' . wds_user_meta( $reseller_id, '_branding_name' ) : wds_option( 'copyright' );
?>

<div class="d-flex flex-column flex-root">

	<div class="d-flex flex-column flex-column-fluid flex-lg-row">

		<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">

			<div class="d-flex flex-center flex-lg-start flex-column text-center text-lg-start">

				<a href="<?php echo esc_url( $logo_link ); ?>" class="mb-6">
					<?php if ( $logo_light && $logo_dark ) : ?>
						<img src="<?php echo esc_url( $logo_light ); ?>" class="w-200px theme-light-show" alt="Logo" />
						<img src="<?php echo esc_url( $logo_dark ); ?>" class="w-200px theme-dark-show" alt="Logo" />
					<?php else : ?>
						<span class="text-body theme-light-show fs-3qx lh-1 fw-bold"><?php echo esc_html( $site_name ); ?></span>
						<span class="text-white theme-dark-show fs-3qx lh-1 fw-bold"><?php echo esc_html( $site_name ); ?></span>
					<?php endif; ?>
				</a>

				<h2 class="text-body theme-light-show fw-semibold m-0"><?php echo wp_kses_post( $tagline ); ?></h2>

				<h2 class="text-white theme-dark-show fw-semibold m-0"><?php echo wp_kses_post( $tagline ); ?></h2>

			</div>

		</div>

		<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">

			<div class="bg-body position-relative d-flex flex-column align-items-stretch flex-center rounded-4 w-500px w-md-600px p-10 p-lg-20">

				<?php wds_dark_mode(); ?>

				<?php load_template( wds_data( 'template' ) ); ?>

				<div class="d-flex flex-center text-center px-lg-10">

					<div class="text-gray-500 fw-semibold fs-6"><?php echo wp_kses_post( $copyright ); ?></div>

				</div>

			</div>

		</div>

	</div>

</div>

<?php wds_footer(); ?>
