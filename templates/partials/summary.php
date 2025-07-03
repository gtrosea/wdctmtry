<div class="d-flex flex-stack mb-3 subtotal">
	<span class="text-gray-500 fw-bold fs-6 title"><?php echo esc_html( wds_lang( 'subtotal' ) ); ?><?php echo $text_addon ? ' <span class="text-info">(+ Addon)</span>' : ''; ?></span>
	<span class="text-gray-500 fw-semibold d-block value"><?php echo esc_html( $subtotal ); ?></span>
</div>

<?php if ( $discount ) : ?>

	<div class="d-flex flex-stack mb-3 discount">
		<span class="text-danger fw-bold fs-6 title"><?php echo esc_html( wds_lang( 'discount' ) ); ?></span>
		<span class="text-danger fw-semibold d-block value"><?php echo esc_html( $discount ); ?></span>
	</div>

<?php endif; ?>

<?php if ( $unique ) : ?>

	<div class="d-flex flex-stack mb-3 unique">
		<span class="text-success fw-bold fs-6 title"><?php echo esc_html( wds_option( 'unique_number_label' ) ); ?></span>
		<span class="text-success fw-semibold d-block value"><?php echo esc_html( $unique ); ?></span>
	</div>

<?php endif; ?>

<?php if ( $addon ) : ?>

	<div class="d-flex flex-stack mb-3 addon">
		<span class="text-info fw-bold fs-6 title"><?php echo esc_html( wds_lang( 'addon' ) ); ?></span>
		<span class="text-info fw-semibold d-block value"><?php echo esc_html( $addon ); ?></span>
	</div>

<?php endif; ?>

<div class="separator separator-dashed mt-6 mb-3"></div>

<div class="d-flex flex-stack total">
	<span class="text-gray-800 fw-bold fs-3 title"><?php echo esc_html( wds_lang( 'total' ) ); ?></span>
	<span class="text-gray-800 fw-semibold fs-3 d-block value"><?php echo esc_html( $total ); ?></span>
</div>