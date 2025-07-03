<a href="<?php echo esc_url( wds_data( 'logo_link' ) ); ?>" class="d-inline-block">
	<?php if ( ! empty( wds_data( 'logo_src' ) ) ) : ?>
		<img src="<?php echo esc_url( wds_data( 'logo_src' ) ); ?>" class="h-40px h-lg-60px" alt="Logo" />
	<?php else : ?>
		<?php if ( wds_data( 'logo_light' ) && wds_data( 'logo_dark' ) ) : ?>
			<img src="<?php echo esc_url( wds_data( 'logo_light' ) ); ?>" class="h-40px h-lg-60px theme-light-show" alt="Logo" />
			<img src="<?php echo esc_url( wds_data( 'logo_dark' ) ); ?>" class="h-40px h-lg-60px theme-dark-show" alt="Logo" />
		<?php else : ?>
			<span class="fs-2qx lh-1 fw-bold"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		<?php endif; ?>
	<?php endif; ?>
</a>