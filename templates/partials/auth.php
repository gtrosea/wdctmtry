<?php
$is_login = is_user_logged_in();
if ( $is_login ) {
	$hide_pass = true;
} elseif ( wds_option( 'hide_password' ) ) {
	$hide_pass = true;
} else {
	$hide_pass = false;
} ?>

<div class="account my-12">

	<div class="d-flex flex-stack fs-4 py-3 mb-3">

		<h2 class="mb-0 fs-2"><?php echo esc_html( wds_lang( 'trx_account_details' ) ); ?></h2>

		<a href="<?php echo esc_url( wds_data( 'auth_link' ) ); ?>" class="btn btn-sm btn-light-primary"><?php echo esc_html( wds_data( 'auth_title' ) ); ?></a>

	</div>

	<div class="input-group fv-row mb-5">

		<span class="input-group-text">
			<i class="ki-duotone ki-profile-circle fs-1">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
			</i>
		</span>

		<input type="text" name="fullname" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'fullname' ) ); ?>" <?php echo $is_login ? 'value="' . esc_attr( wds_user_name() ) . '" disabled' : ''; ?> autocomplete="off" />

	</div>

	<div class="input-group fv-row ">

		<span class="input-group-text">
			<i class="ki-duotone ki-sms fs-1">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</span>

		<input type="email" name="email" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'email' ) ); ?>" <?php echo $is_login ? 'value="' . esc_attr( wds_user_email() ) . '" disabled' : ''; ?> autocomplete="off" />

	</div>

	<div class="text-muted fs-7 mb-5"><?php echo esc_html( wds_lang( 'email_preferred' ) ); ?></div>

	<div class="input-group fv-row mb-5">

		<span class="input-group-text">
			<i class="ki-duotone ki-whatsapp fs-1">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</span>

		<input type="number" name="phone" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'phone' ) ); ?>" <?php echo $is_login ? 'value="' . esc_attr( wds_user_phone() ) . '" disabled' : ''; ?> autocomplete="off" />

	</div>

	<?php if ( ! $hide_pass ) : ?>

		<div class="input-group fv-row mb-5">

			<span class="input-group-text">
				<i class="ki-duotone ki-lock-2 fs-1">
					<i class="path1"></i>
					<i class="path2"></i>
					<i class="path3"></i>
					<i class="path4"></i>
					<i class="path5"></i>
				</i>
			</span>

			<input type="password" name="password" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'password' ) ); ?>" autocomplete="off" />

			<span class="btn btn-sm btn-icon position-absolute translate-middle end-0 me-n2 toggle-password" style="top:23px;z-index:9;">
				<i class="ki-duotone ki-eye-slash fs-1 d-none">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
					<span class="path4"></span>
				</i>
				<i class="ki-duotone ki-eye fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</span>

		</div>

	<?php endif; ?>

</div>