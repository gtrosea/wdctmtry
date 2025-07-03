<div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">

	<form action="" method="POST" id="wds_auth_form" class="form w-100" novalidate="novalidate">

		<div class="text-center mb-10">

			<h1 class="text-dark fw-bolder mb-3"><?php echo esc_html( wds_lang( 'auth_rp_header' ) ); ?></h1>

			<div class="text-gray-500 fw-semibold fs-6">
				<span><?php echo esc_html( wds_lang( 'auth_rp_subheader' ) ); ?></span>
				<a href="<?php echo esc_url( wds_url( 'login' ) ); ?>" class="link-primary fw-bold"><?php echo esc_html( wds_lang( 'login' ) ); ?></a>
			</div>

		</div>

		<div class="fv-row mb-8" data-kt-password-meter="true">

			<div class="mb-1">

				<div class="position-relative mb-3">
					<input type="password" name="new_password" class="form-control bg-transparent" placeholder="<?php echo esc_attr( wds_lang( 'password' ) ); ?>" autocomplete="off" />
					<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
						<i class="ki-duotone ki-eye-slash fs-2 d-none">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
							<span class="path4"></span>
						</i>
						<i class="ki-duotone ki-eye fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>
					</span>
				</div>

				<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
					<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
				</div>

			</div>

			<div class="text-muted"><?php echo esc_html( wds_lang( 'auth_rp_instruction' ) ); ?></div>

		</div>

		<div class="fv-row mb-8">
			<input type="password" name="confirm_password" class="form-control bg-transparent" placeholder="<?php echo esc_attr( wds_lang( 'password_confirm' ) ); ?>" autocomplete="off" />
		</div>

		<div class="d-grid mb-10">
			<button type="submit" id="wds_auth_submit" class="btn btn-primary">
				<span class="indicator-label"><?php echo esc_html( wds_lang( 'submit' ) ); ?></span>
				<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
					<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
				</span>
			</button>
		</div>

		<input type="hidden" name="rp_login" value="<?php echo isset( $_GET['login'] ) ? esc_html( $_GET['login'] ) : ''; ?>" />

		<input type="hidden" name="rp_key" value="<?php echo isset( $_GET['key'] ) ? esc_html( $_GET['key'] ) : ''; ?>" />

	</form>

</div>