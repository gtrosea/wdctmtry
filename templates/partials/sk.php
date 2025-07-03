<?php if ( wds_option( 'sk' ) ) : ?>

	<h2 class="fs-2 mb-5"><?php echo wp_kses_post( wds_option( 'sk_title' ) ); ?></h2>

	<div class="scroll h-300px border p-5 mb-5"><?php echo wp_kses_post( $sk_content ); ?></div>

	<div class="border rounded-10 p-3 mb-7">

		<div class="form-check m-0">

			<input type="checkbox" name="agree_to_terms" id="agree_to_terms" class="form-check-input cursor-pointer" />

			<label for="agree_to_terms" class="form-check-label text-gray-900 cursor-pointer"><?php echo wp_kses_post( wds_option( 'sk_agree' ) ); ?></label>

		</div>

	</div>

<?php endif; ?>