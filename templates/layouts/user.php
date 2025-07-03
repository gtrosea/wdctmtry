<?php wds_header(); ?>

<?php do_action( 'wds_start_content' ); ?>

<div class="d-flex flex-column flex-root">

	<div class="page d-flex flex-row flex-column-fluid">

		<div id="kt_wrapper" class="wrapper d-flex flex-column flex-row-fluid">

			<?php wds_topbar(); ?>

			<div class="d-flex flex-column-fluid">

				<?php wds_sidebar(); ?>

				<div class="d-flex flex-column flex-column-fluid container-fluid">

					<?php load_template( wds_data( 'template' ) ); ?>

					<?php wds_copyright(); ?>

				</div>

			</div>

		</div>

	</div>

</div>

<?php do_action( 'wds_end_content' ); ?>

<?php wds_footer(); ?>