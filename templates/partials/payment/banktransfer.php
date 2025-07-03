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
		'price'   => wds_convert_money( intval( wds_invoice_summary( $invoice, 'total' ) ) ),
	);

	$message = wds_email_replace_shortcode( $content, $args );
	$message = rawurlencode( $message );

	$payment_link = "https://wa.me/$phone?text=$message";
}

$banks = wds_option( 'banktransfer_bank' ); ?>

<div class="mt-8"></div>

<?php if ( ! empty( $banks ) ) : ?>

	<?php foreach ( wds_option( 'banktransfer_bank' ) as $data ) : ?>

		<?php
		$bank_name = $data['name'];
		if ( 'other' == $bank_name ) {
			$bank_name = $data['name_input'];
		}
		?>

		<div class="d-flex flex-stack">

			<div class="flex-grow-1">
				<span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?php echo esc_html( $data['account_number'] ); ?></span>
				<span class="text-muted fw-semibold d-block fs-7"><?php echo esc_html( $bank_name ); ?> (<?php echo esc_html( $data['account_name'] ); ?>)</span>
			</div>

			<button type="button" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary btn-copy w-30px h-30px" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bank-number="<?php echo esc_attr( $data['account_number'] ); ?>">
				<i class="ki-duotone ki-copy fs-3"></i>
			</button>

		</div>

		<div class="separator separator-dashed my-4"></div>

	<?php endforeach; ?>

<?php endif; ?>

<a href="<?php echo wp_kses_post( $payment_link ); ?>" target="_blank" class="btn btn-success text-uppercase w-100 hover-scale shadow mt-5"><?php echo esc_html( wds_lang( 'payment_confirm' ) ); ?></a>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		var copyButtons = document.querySelectorAll('.btn-copy');
		copyButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				var bankNumber = this.getAttribute('data-bank-number');
				copyToClipboard(bankNumber);
				showCopiedTooltip(this);
			});
		});

		function copyToClipboard(text) {
			var textarea = document.createElement('textarea');
			textarea.value = text;
			document.body.appendChild(textarea);
			textarea.select();
			document.execCommand('copy');
			document.body.removeChild(textarea);
		}

		function showCopiedTooltip(button) {
			var tooltip = new bootstrap.Tooltip(button, {
				title: '<?php echo esc_html( wds_lang( 'copied' ) ); ?>',
				placement: 'top'
			});

			tooltip.show();
			setTimeout(function() {
				tooltip.dispose();
			}, 1000);
		}
	});
</script>