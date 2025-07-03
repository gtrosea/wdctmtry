<style type="text/css">.sales-proof{position:fixed;height:auto;width:300px;background-color:#fff;border-radius:50px;box-shadow:2px 5px 10px 1px rgb(0 0 0 / .2);box-sizing:border-box;transition:0.5s;z-index:99}.sales-proof>div{display:flex;align-items:center}.sales-proof-image{position:relative;display:inline-block;flex-shrink:0;border-radius:.475rem}.sales-proof-image img{width:60px;height:60px;object-fit:cover;border-radius:50%;margin-left:5px}.sales-proof-text{display:flex;flex-direction:column;margin-left:8px;height:75px;justify-content:center}.sales-proof-text .name{font-size:13px;font-weight:700;color:#071437;line-height:1.4;margin-bottom:0!important}.sales-proof-text .product{display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;font-size:12px;color:#99a1b7;line-height:1.2;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px!important}.sales-proof-text .verified{display:flex;align-items:center;color:#99a1b7;font-size:12px;font-weight:500}.sales-proof-text .verified img{height:auto;width:12px;margin-right:5px}</style>

<div id="wds-salesproof" class="sales-proof" style="<?php echo esc_attr( $style ); ?>">

	<div class="sales-proof-wrap">

		<div class="sales-proof-image">

			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />

		</div>

		<div class="sales-proof-text">

			<p class="name"></p>

			<p class="product"></p>

			<div class="verified">

				<img src="<?php echo esc_url( WDS_URL . 'assets/img/verified.png' ); ?>" alt="verified icon" />

				<span><?php echo wp_kses_post( $text2 ); ?></span>

			</div>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var salesProof = $('#wds-salesproof');
		var invoices = <?php echo wp_json_encode( $array_invoices ); ?>;
		var delay = <?php echo esc_html( $delay * 1000 ); ?>;
		var time = <?php echo esc_html( $time * 1000 ); ?>;
		var index = 0;

		function showNextSalesProof() {
			if (index < invoices.length) {
				var invoice = invoices[index];
				var name = invoice.name;
				var product = invoice.product;

				setTimeout(function() {
					salesProof.css('<?php echo esc_html( $y ); ?>', '20px');
					salesProof.find('.name').html(name);
					salesProof.find('.product').html(product);

					setTimeout(function() {
						salesProof.css('<?php echo esc_html( $y ); ?>', '-100px');
						index++;
						showNextSalesProof();
					}, time);
				}, delay);
			}
		}

		showNextSalesProof();
	});
</script>