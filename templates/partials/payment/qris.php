<?php
$invoice         = wds_data( 'invoice' );
$payment_link    = wds_option( 'payment_confirm_link' );
$payment_confirm = wds_option( 'payment_confirm' );
if ( 'whatsapp' == $payment_confirm ) {
	$phone   = wds_option( 'payment_confirm_phone' );
	$content = wds_option( 'payment_confirm_text' );

	$args = array(
		'name'    => wds_user_name( $invoice->user_id ),
		'email'   => wds_user_email( $invoice->user_id ),
		'invoice' => $invoice->number,
		'product' => wds_invoice_summary( $invoice, 'product_title' ),
		'price'   => wds_invoice_summary( $invoice, 'total' ),
	);

	$message = wds_email_replace_shortcode( $content, $args );
	$message = rawurlencode( $message );

	$payment_link = "https://wa.me/$phone?text=$message";
}

$qris = wds_option( 'qris_code' ); ?>

<div class="mt-8"></div>

<?php if ( ! empty( $qris ) ) : ?>
	<a href="<?php echo esc_url( $qris ); ?>" target="_blank">
		<img src="<?php echo esc_url( $qris ); ?>" class="w-200px mx-auto d-block" alt="QRIS" />
	</a>
<?php endif; ?>

<a href="<?php echo esc_url( $payment_link ); ?>" target="_blank" class="btn btn-success text-uppercase w-100 hover-scale shadow mt-5"><?php echo esc_html( wds_lang( 'payment_confirm' ) ); ?></a>