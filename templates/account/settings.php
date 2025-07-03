<?php $data = wds_data( 'data_user' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'account_settings' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php echo esc_html( wds_lang( 'account' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'settings' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<div class="card mb-5 mb-xl-10">

		<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

			<div class="card-title m-0">

				<h3 class="fw-bold m-0"><?php echo esc_html( wds_lang( 'account_settings_profile_header' ) ); ?></h3>

			</div>

		</div>

		<div id="kt_account_profile_details" class="collapse show">

			<form action="" method="POST" id="profile_form" class="form">

				<div class="card-body border-top p-9">

					<div class="row mb-6">
						<label for="email" class="col-lg-4 col-form-label fw-semibold fs-6"><span class="required"><?php echo esc_html( wds_lang( 'email' ) ); ?></span></label>
						<div class="col-lg-8 fv-row">
							<input type="email" id="email" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['email'] ); ?>" required disabled />
						</div>
					</div>

					<div class="row mb-6">
						<label for="fullname" class="col-lg-4 col-form-label fw-semibold fs-6"><span class="required"><?php echo esc_html( wds_lang( 'fullname' ) ); ?></span></label>
						<div class="col-lg-8 fv-row">
							<input type="text" name="fullname" id="fullname" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['fullname'] ); ?>" required />
						</div>
					</div>

					<div class="row mb-6">
						<label for="phone" class="col-lg-4 col-form-label fw-semibold fs-6"><span class="required"><?php echo esc_html( wds_lang( 'phone' ) ); ?></span></label>
						<div class="col-lg-8 fv-row">
							<input type="number" name="phone" id="phone" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['phone'] ); ?>" required />
						</div>
					</div>

				</div>

				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<button type="button" id="profile_submit" class="btn btn-primary">
						<span class="indicator-label"><?php echo esc_html( wds_lang( 'save_change' ) ); ?></span>
						<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
						</span>
					</button>
				</div>

			</form>

		</div>

	</div>

	<?php if ( wds_data( 'reseller' ) ) : ?>

		<div class="card mb-5 mb-xl-10">

			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_reseller" aria-expanded="true" aria-controls="kt_account_reseller">

				<div class="card-title m-0">

					<h3 class="fw-bold m-0"><?php echo esc_html( wds_lang( 'reseller' ) ); ?></h3>

				</div>

			</div>

			<div id="kt_account_reseller" class="collapse show">

				<form action="" method="POST" enctype="multipart/form-data" id="reseller_form" class="form">

					<div class="card-body border-top p-9">

						<?php if ( wds_data( 'name' ) ) : ?>

							<div class="row mb-6">
								<label for="brand_name" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'brand_name' ) ); ?></label>
								<div class="col-lg-8 fv-row">
									<input type="text" name="brand_name" id="brand_name" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['branding_name'] ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'logo' ) ) : ?>

							<div class="row mb-6">
								<label for="logo" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'brand_logo' ) ); ?></label>
								<div class="col-lg-8 fv-row">
									<img class="mb-3 mh-75px mw-100" src="<?php echo esc_attr( $data['branding_logo'] ); ?>">
									<input type="file" name="logo" id="logo" class="form-control form-control-lg form-control-solid" accept="image/*" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'link' ) ) : ?>

							<div class="row mb-6">
								<label for="link" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'brand_link' ) ); ?></label>
								<div class="col-lg-8 fv-row">
									<input type="text" name="link" id="link" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['branding_link'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_logo_link' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'desc' ) ) : ?>

							<div class="row mb-6">
								<label for="description" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'description' ) ); ?></label>
								<div class="col-lg-8 fv-row">
									<input type="text" name="description" id="description" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['branding_description'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_description' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'instagram' ) ) : ?>

							<div class="row mb-6">
								<label for="instagram" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'link' ) ); ?> Instagram</label>
								<div class="col-lg-8 fv-row">
									<input type="url" name="instagram" id="instagram" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['instagram'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_instagram' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'facebook' ) ) : ?>

							<div class="row mb-6">
								<label for="facebook" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'link' ) ); ?> Facebook</label>
								<div class="col-lg-8 fv-row">
									<input type="url" name="facebook" id="facebook" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['facebook'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_facebook' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'tiktok' ) ) : ?>

							<div class="row mb-6">
								<label for="tiktok" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'link' ) ); ?> Tiktok</label>
								<div class="col-lg-8 fv-row">
									<input type="url" name="tiktok" id="tiktok" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['tiktok'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_tiktok' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'twitter' ) ) : ?>

							<div class="row mb-6">
								<label for="twitter" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'link' ) ); ?> Twitter (X)</label>
								<div class="col-lg-8 fv-row">
									<input type="url" name="twitter" id="twitter" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['twitter'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_twitter' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_data( 'youtube' ) ) : ?>

							<div class="row mb-6">
								<label for="youtube" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'link' ) ); ?> Youtube</label>
								<div class="col-lg-8 fv-row">
									<input type="url" name="youtube" id="youtube" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['youtube'] ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'account_settings_youtube' ) ); ?>" />
								</div>
							</div>

						<?php endif; ?>

						<?php if ( wds_is_replica() ) : ?>

							<div class="row mb-6">
								<label for="invitation_price" class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'inv_price' ) ); ?></label>
								<div class="col-lg-8 fv-row">
									<input type="number" name="invitation_price" id="invitation_price" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['invitation_price'] ); ?>" />
									<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_attr( wds_lang( 'account_settings_price_note' ) ); ?></div>
								</div>
							</div>

						<?php else : ?>

							<input type="hidden" name="invitation_price" />

						<?php endif; ?>

					</div>

					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="button" id="reseller_submit" class="btn btn-primary">
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'save_change' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>
					</div>

				</form>

			</div>

		</div>

	<?php endif; ?>

	<div class="card">

		<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">

			<div class="card-title m-0">

				<h3 class="fw-bold m-0"><?php echo esc_html( wds_lang( 'account_settings_login_header' ) ); ?></h3>

			</div>

		</div>

		<div id="kt_account_signin_method" class="collapse show">

			<div class="card-body border-top p-9">

				<div class="d-flex flex-wrap align-items-center">

					<div id="change_email_data">
						<div class="fs-6 fw-bold mb-1"><?php echo esc_html( wds_lang( 'email' ) ); ?></div>
						<div class="fw-semibold text-gray-600"><?php echo esc_html( $data['email'] ); ?></div>
					</div>

					<div id="change_email_edit" class="flex-row-fluid d-none">

						<form action="" method="POST" id="change_email_form" class="form" novalidate="novalidate">

							<div class="row mb-6">

								<div class="col-lg-6 mb-4 mb-lg-0">
									<div class="fv-row mb-0">
										<label for="emailaddress" class="form-label fs-6 fw-bold mb-3"><span class="required"><?php echo esc_html( wds_lang( 'email_new' ) ); ?></span></label>
										<input type="email" name="emailaddress" id="emailaddress" class="form-control form-control-lg form-control-solid" value="<?php echo esc_attr( $data['email'] ); ?>" />
									</div>
								</div>

								<div class="col-lg-6">
									<div class="fv-row mb-0">
										<label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3"><span class="required"><?php echo esc_html( wds_lang( 'password_confirm' ) ); ?></span></label>
										<input type="password" name="confirmemailpassword" id="confirmemailpassword" class="form-control form-control-lg form-control-solid" />
									</div>
								</div>

							</div>

							<div class="d-flex">
								<button type="button" id="change_email_submit" class="btn btn-primary me-2 px-6"><?php echo esc_html( wds_lang( 'account_settings_email_update' ) ); ?></button>
								<button type="button" id="change_email_cancel" class="btn btn-color-gray-400 btn-active-light-primary px-6"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>
							</div>

						</form>

					</div>

					<div id="change_email_button" class="ms-auto">
						<button class="btn btn-light btn-active-light-primary"><?php echo esc_html( wds_lang( 'account_settings_email_change' ) ); ?></button>
					</div>

				</div>

				<div class="separator separator-dashed my-6"></div>

				<div class="d-flex flex-wrap align-items-center">

					<div id="change_password_data">
						<div class="fs-6 fw-bold mb-1"><?php echo esc_html( wds_lang( 'password' ) ); ?></div>
						<div class="fw-semibold text-gray-600">************</div>
					</div>

					<div id="change_password_edit" class="flex-row-fluid d-none">

						<form action="" method="POST" id="change_password_form" class="form" novalidate="novalidate">

							<div class="row mb-1">

								<div class="col-lg-12 mb-4">
									<div class="fv-row mb-0">
										<label for="currentPassword" class="form-label fs-6 fw-bold mb-3"><span class="required"><?php echo esc_html( wds_lang( 'password_current' ) ); ?></span></label>
										<input type="password" name="password" id="currentPassword" class="form-control form-control-lg form-control-solid" />
									</div>
								</div>

								<div class="col-lg-6 mb-4 mb-lg-0">
									<div class="fv-row mb-0">
										<label for="newpassword" class="form-label fs-6 fw-bold mb-3"><span class="required"><?php echo esc_html( wds_lang( 'password_new' ) ); ?></span></label>
										<input type="password" name="pass1" id="newpassword" class="form-control form-control-lg form-control-solid" />
									</div>
								</div>

								<div class="col-lg-6">
									<div class="fv-row mb-0">
										<label for="confirmpassword" class="form-label fs-6 fw-bold mb-3"><span class="required"><?php echo esc_html( wds_lang( 'password_new_confirm' ) ); ?></span></label>
										<input type="password" name="pass2" id="confirmpassword" class="form-control form-control-lg form-control-solid" />
									</div>
								</div>

							</div>

							<div class="form-text mb-5"><?php echo esc_html( wds_lang( 'account_settings_password_instruction' ) ); ?></div>

							<div class="d-flex">
								<button type="button" id="change_password_submit" class="btn btn-primary me-2 px-6"><?php echo esc_html( wds_lang( 'account_settings_password_update' ) ); ?></button>
								<button type="button" id="change_password_cancel" class="btn btn-color-gray-400 btn-active-light-primary px-6"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>
							</div>

						</form>

					</div>

					<div id="change_password_button" class="ms-auto">
						<button class="btn btn-light btn-active-light-primary"><?php echo esc_html( wds_lang( 'account_settings_password_reset' ) ); ?></button>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>