<div id="wds-admin-header" class="wds-admin-header">

	<div id="wds-admin-header-wrapper">

		<img src="<?php echo esc_url( WDS_LOGO ); ?>" class="wds-admin-header-logo" alt="WeddingSaas" />

		<div class="wds-admin-header-page-title-wrap">

			<span class="wds-admin-header-separator">/</span>

			<h1 class="wds-admin-header-page-title"><?php echo esc_html( $page_title ); ?></h1>

			<?php if ( $button ) : ?>

				<a href="<?php echo esc_url( $menus[ $data['id'] ]['btn_url'] ); ?>" class="page-title-action button"><?php echo esc_html( $menus[ $data['id'] ]['btn_text'] ); ?></a>

			<?php endif; ?>

		</div>

	</div>

</div>