<div class="modal fade" tabindex="-1" id="popup_modal_stacked" data-bs-backdrop="static">

	<div class="modal-dialog modal-dialog-centered">

		<div class="modal-content">

			<div class="modal-header">

				<h3 class="modal-title"><?php echo wp_kses_post( $title ); ?></h3>

				<div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal" aria-label="Close" id="close_popup">
					<i class="ki-solid ki-cross fs-1"></i>
				</div>

			</div>

			<div class="modal-body p-5">

				<?php echo wp_kses_post( $content ); ?>

			</div>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		setTimeout(function() {
			$('#popup_modal_stacked').modal('show');
		}, <?php echo (int) esc_html( $delay ) * 1000; ?>);

		$('#close_popup').on('click', function() {
			var d = new Date();
			d.setTime(d.getTime() + (<?php echo esc_html( $interval ); ?> * 24 * 60 * 60 * 1000));
			var expires = "expires=" + d.toUTCString();
			document.cookie = '<?php echo esc_html( $cookie ); ?>=true; ' + expires + '; path=/';
		});
	});
</script>