<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php do_action( 'wds_head' ); ?>
</head>
<body id="kt_body">

	<?php do_action( 'wds_start_content' ); ?>

	<?php require_once wds_data( 'template' ); ?>

	<?php do_action( 'wds_end_content' ); ?>

	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<i class="ki-duotone ki-arrow-up">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>
	
	<?php do_action( 'wds_footer' ); ?>

	<?php do_action( 'wds_footer_data' ); ?>

</body>
</html>
