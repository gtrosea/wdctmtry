<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php if ( 'dashboard/invitation/edit' == wds_data( 'page' ) || 'dashboard/landingpage/edit' == wds_data( 'page' ) ) : ?>
		<?php wp_head(); ?>
	<?php endif; ?>
	<?php do_action( 'wds_head' ); ?>
	<?php if ( wds_data( 'bg_light' ) && wds_data( 'bg_dark' ) && in_array( wds_data( 'target' ), array( 'auth', 'both' ) ) ) : ?>
		<style type="text/css">
			body {
				background-image: url('<?php echo esc_url( wds_data( 'bg_light' ) ); ?>');
			}
			[data-bs-theme="dark"] body {
				background-image: url('<?php echo esc_url( wds_data( 'bg_dark' ) ); ?>');
			}
		</style>
	<?php endif; ?>
</head>
<?php if ( in_array( wds_data( 'target' ), array( 'auth', 'both' ) ) ) : ?>
	<body id="kt_body" class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
<?php elseif ( wds_data( 'target' ) == 'user' ) : ?>
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
<?php else : ?>
	<body id="kt_body">
<?php endif; ?>
