<?php $data = wds_data( 'data_reseller' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'landingpage' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'landingpage' ) ); ?></li>

		</ul>

	</div>

	<?php if ( wds_option( 'wdr_form' ) ) : ?>
		<div class="d-flex align-items-center py-2 py-md-1">
			<a href="<?php echo esc_url( wds_url( 'landingpage_edit' ) ); ?>" class="btn btn-primary fw-bold">
				<i class="ki-outline ki-notepad-edit fs-5 me-1"></i>
				<?php esc_html_e( 'Edit', 'weddingsaas' ); ?>
			</a>
		</div>
	<?php endif; ?>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card mb-5 mb-xl-10 <?php echo 'active' != $data['status'] ? 'd-none' : ''; ?>">
		<div class="card-header border-0 cursor-pointer">
			<div class="card-title m-0">
				<h3 class="fw-bold m-0"><?php echo esc_html( wds_lang( 'wdr_link_title' ) ); ?></h3>
			</div>
		</div>

		<div id="landing_data" class="collapse show">
			<div class="card-body border-top p-9">

				<div class="row mb-6">
					<label class="col-lg-4 col-form-label fw-semibold fs-6">Homepage</label>
					<div class="col-lg-8">
						<div class="d-flex">
							<input type="text" id="url_home" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_url( $data['url'] ); ?>" readonly />
							<button id="url_btn_home" class="btn btn-light-primary btn-active-primary fw-bold flex-shrink-0 url_btn" data-clipboard-target="#url_home"><?php echo esc_html( wds_lang( 'account_ref_copy_link' ) ); ?></button>
						</div>
					</div>
				</div>

				<?php
				$pages = wds_option( 'wdr_pages' );
				if ( wds_check_array( $pages, true ) ) :
					foreach ( $pages as $page ) :
						?>
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( $page['title'] ); ?></label>
							<div class="col-lg-8">
								<div class="d-flex">
									<input type="text" id="url_<?php echo esc_html( sanitize_title( $page['title'] ) ); ?>" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_url( $data['url'] . '/' . $page['page'] ); ?>" readonly />
									<button id="url_btn_<?php echo esc_html( sanitize_title( $page['title'] ) ); ?>" class="btn btn-light-primary btn-active-primary fw-bold flex-shrink-0 url_btn" data-clipboard-target="#url_<?php echo esc_html( sanitize_title( $page['title'] ) ); ?>"><?php echo esc_html( wds_lang( 'account_ref_copy_link' ) ); ?></button>	
								</div>
							</div>
						</div>
						<?php
					endforeach;
				endif;
				?>

			</div>
		</div>
	</div>

	<div class="card mb-5 mb-xl-10">

		<div class="card-header border-0 cursor-pointer">

			<div class="card-title m-0">

				<h3 class="fw-bold m-0"><?php echo esc_html( wds_lang( 'wdr_title' ) ); ?></h3>

			</div>

		</div>

		<div id="landing_generator" class="collapse show">

			<form action="" method="POST" id="landing_form" class="form">

				<div class="card-body border-top p-9">

					<div class="row mb-6">

						<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'brand_name' ) ); ?></label>

						<div class="col-lg-8 fv-row">

							<input type="text" class="form-control form-control-lg" value="<?php echo esc_attr( $data['branding_name'] ); ?>" disabled />

							<input type="hidden" name="name" id="name" value="<?php echo esc_attr( $data['branding_name'] ); ?>" />

							<div class="fs-7 fw-semibold text-muted mt-1">
								<span><?php echo esc_html( wds_lang( 'wdr_field_note' ) ); ?></span>
								<a href="<?php echo esc_url( wds_url( 'landingpage_settings' ) ); ?>"><?php echo esc_html( wds_lang( 'wdr_click' ) ); ?></a>
							</div>

						</div>

					</div>

					<div class="row mb-6">

						<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'brand_logo' ) ); ?></label>

						<div class="col-lg-8 fv-row">

							<img class="mb-3 mh-75px mw-100" src="<?php echo esc_url( $data['branding_logo'] ); ?>">

							<input type="hidden" name="logo" id="logo" value="<?php echo esc_attr( $data['branding_logo'] ); ?>" />

							<div class="fs-7 fw-semibold text-muted mt-1">
								<span><?php echo esc_html( wds_lang( 'wdr_field_note' ) ); ?></span>
								<a href="<?php echo esc_url( wds_url( 'landingpage_settings' ) ); ?>"><?php echo esc_html( wds_lang( 'wdr_click' ) ); ?></a>
							</div>

						</div>

					</div>

					<div class="row mb-6">

						<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'phone' ) ); ?></label>

						<div class="col-lg-8 fv-row">

							<input type="text" class="form-control form-control-lg" value="<?php echo esc_attr( $data['phone'] ); ?>" disabled />

							<input type="hidden" name="phone" id="phone" value="<?php echo esc_attr( $data['phone'] ); ?>" />

							<div class="fs-7 fw-semibold text-muted mt-1">
								<span><?php echo esc_html( wds_lang( 'wdr_field_note' ) ); ?></span>
								<a href="<?php echo esc_url( wds_url( 'landingpage_settings' ) ); ?>"><?php echo esc_html( wds_lang( 'wdr_click' ) ); ?></a>
							</div>

						</div>

					</div>

					<div class="row mb-6">

						<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'price' ) ); ?></label>

						<div class="col-lg-8 fv-row">

							<input type="text" class="form-control form-control-lg" value="<?php echo esc_attr( wds_convert_money( $data['invitation_price'] ) ); ?>" disabled />

							<input type="hidden" name="price" id="price" value="<?php echo esc_attr( $data['invitation_price'] ); ?>" />

							<div class="fs-7 fw-semibold text-muted mt-1">
								<span><?php echo esc_html( wds_lang( 'wdr_field_note' ) ); ?></span>
								<a href="<?php echo esc_url( wds_url( 'landingpage_settings' ) ); ?>"><?php echo esc_html( wds_lang( 'wdr_click' ) ); ?></a>
							</div>

						</div>

					</div>

					<?php if ( '1' == $data['host_number'] || '3' == $data['host_number'] ) : ?>

						<div class="row mb-6">

							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'wdr_select_host' ) ); ?></label>

							<div class="col-lg-8">

								<select id="lg--host" class="form-select" <?php echo 'blocked' == $data['status'] ? 'disabled' : ''; ?>>
									<option value="" <?php echo ! $data['value'] ? 'selected' : ''; ?> disabled><?php echo esc_html( wds_lang( 'select' ) ); ?></option>
									<option value="subdomain" <?php echo $data['value'] && $data['subdomain'] ? 'selected' : ''; ?>><?php esc_html_e( 'Subdomain', 'wds-notrans' ); ?></option>
									<option value="domain" <?php echo $data['value'] && $data['domain'] ? 'selected' : ''; ?>><?php esc_html_e( 'Custom Domain', 'wds-notrans' ); ?></option>
								</select>

							</div>

						</div>

						<div id="subdomain-row" class="row mb-6" style="display: none;">

							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php esc_html_e( 'Subdomain', 'wds-notrans' ); ?></label>

							<div class="col-lg-8">

								<div class="input-group input-group-lg">

									<input type="text" name="subdomain" id="subdomain" class="form-control" placeholder="example" <?php echo $data['value'] && $data['subdomain'] ? 'value="' . esc_attr( $data['subdomain'] ) . '"' : ''; ?> <?php echo 'blocked' == $data['status'] ? 'disabled' : ''; ?> />

									<span class="input-group-text">.<?php echo '1' == $data['host_number'] ? esc_html( wds_host() ) : esc_html( $data['host_custom'] ); ?></span>

								</div>

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_html( wds_lang( 'wdr_select_host_note_subdomain' ) ); ?></div>

							</div>

						</div>

						<div id="domain-row" class="row mb-6" style="display: none;">

							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php esc_html_e( 'Custom Domain', 'wds-notrans' ); ?></label>

							<div class="col-lg-8">

								<input type="text" name="domain" id="domain" class="form-control" placeholder="example.com,www.domain.com" <?php echo $data['value'] && $data['domain'] ? 'value="' . esc_attr( $data['domain'] ) . '"' : ''; ?> <?php echo 'blocked' == $data['status'] ? 'disabled' : ''; ?> />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_html( wds_lang( 'wdr_select_host_note_domain' ) ); ?></div>

							</div>

						</div>

					<?php else : ?>

						<div class="row mb-6">

							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php esc_html_e( 'Custom Domain', 'wds-notrans' ); ?></label>

							<div class="col-lg-8">

								<input type="hidden" name="subdomain" disabled />

								<input type="text" name="domain" id="domain" class="form-control" placeholder="example.com,www.domain.com" <?php echo $data['value'] && $data['domain'] ? 'value="' . esc_attr( $data['domain'] ) . '"' : ''; ?> <?php echo 'blocked' == $data['status'] ? 'disabled' : ''; ?> />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_html( wds_lang( 'wdr_select_host_note_domain' ) ); ?></div>

							</div>

						</div>

					<?php endif; ?>

					<?php if ( $data['value'] ) : ?>

						<div class="row mb-6">

							<label class="col-lg-4 col-form-label fw-semibold fs-6"><?php echo esc_html( wds_lang( 'status' ) ); ?></label>

							<div class="col-lg-8">

								<input type="text" class="form-control form-control-lg <?php echo 'active' == $data['status'] ? 'text-success' : 'text-danger'; ?>" value="<?php echo esc_attr( ucfirst( $data['status'] ) ); ?>" disabled />

								<div class="fs-7 fw-semibold text-muted mt-1">
									<span><?php echo esc_html( wds_lang( 'wdr_visit' ) ); ?></span>
									<a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank"><?php echo esc_html( wds_lang( 'wdr_click' ) ); ?></a>
								</div>

							</div>

						</div>

					<?php endif; ?>

				</div>

				<?php if ( ! $data['value'] ) : ?>

					<div class="card-footer d-flex justify-content-end py-6 px-9">

						<button type="button" id="landing_add_btn" class="btn btn-primary">
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'save' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

					</div>

				<?php else : ?>

					<div class="card-footer d-flex justify-content-end py-6 px-9">

						<button type="button" class="btn btn-danger me-3" <?php echo 'blocked' == $data['status'] ? 'disabled' : 'id="landing_delete_btn"'; ?>>
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'delete' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

						<button type="button" class="btn btn-primary" <?php echo 'blocked' == $data['status'] ? 'disabled' : 'id="landing_update_btn"'; ?>>
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'update' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

					</div>

				<?php endif; ?>

			</form>

		</div>

	</div>

</div>
