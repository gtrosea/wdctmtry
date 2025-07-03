<div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">

	<form action="" method="POST" id="wds_auth_form" class="form w-100" novalidate="novalidate">

		<div class="text-center mb-10">

			<h1 class="fw-bolder mb-3"><?php echo esc_html( wds_lang( 'auth_login_header' ) ); ?></h1>

		</div>

		<div class="fv-row mb-8">
			<input type="text" name="email" class="form-control bg-transparent" placeholder="<?php echo esc_attr( wds_lang( 'email' ) ); ?>" autocomplete="off" />
		</div>

		<div class="fv-row mb-4">
			<input type="password" name="password" class="form-control bg-transparent" placeholder="<?php echo esc_attr( wds_lang( 'password' ) ); ?>" autocomplete="off" />
			<span class="btn btn-sm btn-icon position-absolute translate-middle end-0 me-n2 toggle-password" style="top:23px;">
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

		<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
			<div class="form-check form-check-custom form-check-solid">
				<input type="checkbox" name="rememberme" id="rememberme" class="form-check-input cursor-pointer" />
				<label for="rememberme" class="form-check-label cursor-pointer"><?php echo esc_html( wds_lang( 'auth_login_remember' ) ); ?></label>
			</div>
			<a href="<?php echo esc_url( wds_url( 'lostpass' ) ); ?>" class="link-primary"><?php echo esc_html( wds_lang( 'auth_login_lost' ) ); ?></a>
		</div>

		<div class="d-grid">
			<button type="submit" id="wds_auth_submit" class="btn btn-primary">
				<span class="indicator-label"><?php echo esc_html( wds_lang( 'login' ) ); ?></span>
				<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
					<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
				</span>
			</button>
		</div>

		<div class="text-gray-500 text-center fs-6 mt-5">
			<?php echo esc_html( wds_lang( 'auth_login_signup_text' ) ); ?>
			<a href="<?php echo esc_url( wds_option( 'signup_link' ) ); ?>" class="link-primary">
				<?php echo esc_html( wds_lang( 'auth_login_signup' ) ); ?>
			</a>
		</div>

	</form>

</div>