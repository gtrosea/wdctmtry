<h1 class="fw-bolder text-gray-900 text-center mb-7"><?php echo esc_html( wds_lang( 'trx_payment_cancelled_title' ) ); ?></h1>

<div class="fw-semibold fs-6 text-gray-500 text-center mb-7"><?php echo esc_html( wds_lang( 'trx_payment_cancelled_subtitle' ) ); ?></div>

<div class="text-center">

	<img src="<?php echo esc_url( WDS_URL . 'assets/img/ok.png' ); ?>" class="mw-100 mh-300px theme-light-show" alt="">

	<img src="<?php echo esc_url( WDS_URL . 'assets/img/ok-dark.png' ); ?>" class="mw-100 mh-300px theme-dark-show" alt="">

	<a href="<?php echo esc_url( wds_data( 'checkout' ) ); ?>" class="btn btn-primary mx-auto mt-5 d-block w-300px"><?php echo esc_html( wds_lang( 'trx_payment_cancelled_button' ) ); ?></a>
	
</div>