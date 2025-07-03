<div class="wds-nav__wrapper">

	<nav class="wds-nav" aria-label="Secondary menu">

		<ul class="wds-nav__tabs">

			<?php foreach ( $menus as $slug => $data ) : ?>

				<?php
				if ( $menu_cpt ) {
					$_group = wds_sanitize_data_field( $data, 'menu_group' );
					if ( $menu_group != $_group ) {
						continue;
					}
				}

				if ( 'weddingsaas-product-new' == $slug ) {
					continue;
				}

				$class = '';
				if ( $page == $slug ) {
					$class = ' active';
				} elseif ( isset( $screen->post_type ) && $screen->post_type == $slug ) {
					$class = ' active';
				}

				if ( 'weddingsaas-product-new' == $page && 'weddingsaas-product' == $slug ) {
					$class = ' active';
				}

				$target = '';
				if ( 'weddingsaas-shortcode' == $slug ) {
					$target = ' target="_blank"';
				}
				?>

				<li class="wds-nav__tabs--item<?php echo esc_attr( $class ); ?>">
					<a href="<?php echo esc_url( $data['url'] ); ?>"<?php echo esc_attr( $target ); ?> class="tab"><?php echo esc_html( $data['menu_title'] ); ?></a>
				</li>

			<?php endforeach; ?>

		</ul>

	</nav>

</div>