<?php
$invoice   = wds_data( 'invoice' );
$reference = $invoice->reference;

if ( strpos( $reference, 'https://' ) !== 0 ) {
	echo '<div class="text-danger fs-2 fw-bold mt-3 text-uppercase">' . esc_html( $reference ) . '</div>';
} else {
	echo '<a href="' . esc_url( $reference ) . '" target="_blank" class="btn btn-success text-uppercase w-100 hover-scale shadow mt-8">' . esc_html( wds_lang( 'pay_now' ) ) . '</a>';
}
