<div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">

	<form action="" method="POST" id="wds_auth_form" class="form w-100" novalidate="novalidate">

		<div class="text-center mb-10">

			<h1 class="text-dark fw-bolder mb-3"><?php echo esc_html( wds_lang( 'auth_lp_header' ) ); ?></h1>

			<div class="text-gray-500 fw-semibold fs-6"><?php echo esc_html( wds_lang( 'auth_lp_subheader' ) ); ?></div>

		</div>

		<div class="fv-row mb-8">
			<input type="text" name="email" class="form-control bg-transparent" placeholder="<?php echo esc_attr( wds_lang( 'email' ) ); ?>" autocomplete="off" />
		</div>

		<div class="d-flex flex-wrap justify-content-center pb-lg-0">

			<button type="submit" id="wds_auth_submit" class="btn btn-primary me-4">
				<span class="indicator-label"><?php echo esc_html( wds_lang( 'submit' ) ); ?></span>
				<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
					<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
				</span>
			</button>

			<a href="<?php echo esc_url( wds_url( 'login' ) ); ?>" class="btn btn-light"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></a>
			
		</div>

	</form>

</div>