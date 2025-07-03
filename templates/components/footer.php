<?php if ( wds_data( 'target' ) != 'auth' ) : ?>
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<i class="ki-duotone ki-arrow-up">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>
<?php endif; ?>

<?php if ( 'dashboard/invitation/edit' == wds_data( 'page' ) || 'dashboard/landingpage/edit' == wds_data( 'page' ) ) : ?>
	<?php wp_footer(); ?>
<?php endif; ?>
	
<?php do_action( 'wds_footer' ); ?>

<?php do_action( 'wds_footer_data' ); ?>

<?php if ( 'dashboard/invitation/edit' == wds_data( 'page' ) || 'dashboard/landingpage/edit' == wds_data( 'page' ) ) : ?>
	<?php wds_template_section( 'partials/jfb_scripts.php' ); ?>
<?php endif; ?>

</body>
</html>