<div class="alert alert-dismissible alert-ct bg-light-<?php echo esc_attr( $style ); ?> border border-<?php echo esc_attr( $style ); ?> p-6 px-md-10 mb-5">

	<button type="button" class="position-absolute top-0 end-0 btn p-0 me-n1" data-bs-dismiss="alert">
		<i class="ki-duotone ki-cross-square fs-2x text-<?php echo esc_attr( $style ); ?>">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</button>

	<?php echo wp_kses_post( $message ); ?>

</div>