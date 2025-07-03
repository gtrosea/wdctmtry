<h1 class="fw-bolder text-gray-900 text-center mb-7"><?php echo esc_html( wds_lang( 'auth_verify_header' ) ); ?></h1>

<div class="fw-semibold fs-6 text-gray-500 text-center mb-7"><?php echo esc_html( wds_lang( 'auth_verify_subheader' ) ); ?></div>

<?php if ( isset( $_GET['resend'] ) && 'success' == $_GET['resend'] ) : ?>
	<div class="alert alert-success d-flex align-items-center p-5">
		<i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
		<div class="d-flex flex-column">
			<span class="text-black"><?php echo esc_html( wds_lang( 'auth_verify_resend_success' ) ); ?></span>
		</div>
	</div>
<?php else : ?>
	<div class="text-center">
		<a href="<?php echo esc_url( wds_url( 'verify' ) . '?resend=true' ); ?>" class="btn btn-primary"><?php echo esc_html( wds_lang( 'auth_verify_resend' ) ); ?></a>
	</div>
<?php endif; ?>

<div class="text-center">

	<img src="<?php echo esc_url( WDS_URL . 'assets/img/email-activation.png' ); ?>" class="mw-100 mh-200px mt-10" alt="" />
	
</div>
