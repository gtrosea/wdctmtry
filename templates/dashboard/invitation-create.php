<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'create_invitation' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'create_invitation' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<div class="row g-5 g-xl-8">

		<div class="card">

			<div class="card-body">

				<ul id="category-list" class="nav nav-pills nav-pills-custom pb-3 overflow-y-auto flex-nowrap">

					<li class="nav-item me-3 me-lg-6 ajaxListPlaceholder">
						<a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden py-4 placeholder-glow w-100px">
							<span class="nav-text text-gray-700 fw-bold fs-6 lh-1 placeholder col-12"></span>
						</a>
					</li>

					<li class="nav-item ajaxListPlaceholder">
						<a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden py-4 placeholder-glow w-75px">
							<span class="nav-text text-gray-700 fw-bold fs-6 lh-1 placeholder col-12"></span>
						</a>
					</li>

				</ul>

				<div id="separator-subcategory" class="separator separator-content my-8 d-none">
					<span class="w-150px"><?php echo esc_html( wds_lang( 'dash_invitation_subcategory' ) ); ?></span>
				</div>

				<ul class="nav fs-8 mt-3 overflow-y-auto flex-nowrap ajaxSubPlaceholder">
					<li class="nav-item">
						<a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden p-0 placeholder-glow w-200px">
							<span class="nav-text text-gray-700 fw-bold fs-1 lh-1 placeholder col-12"></span>
						</a>
					</li>
				</ul>

				<ul id="subcategory-list" class="nav fs-8 mt-3 overflow-y-auto flex-nowrap"></ul>

				<div id="separator-subtheme" class="separator separator-content my-8 d-none">
					<span class="w-125px"><?php echo esc_html( wds_lang( 'dash_invitation_subtheme' ) ); ?></span>
				</div>

				<ul id="subtheme-list" class="nav fs-8 mt-3 overflow-y-auto flex-nowrap"></ul>

			</div>

		</div>

	</div>

	<div class="row g-5 g-xl-8 mt-5 ajaxCardPlaceholder">

		<div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">

			<div class="card h-100">

				<svg class="bd-placeholder-img card-img-top" width="100%" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
					<title>Placeholder</title>
					<rect width="100%" height="100%" fill="#868e96"></rect>
				</svg>

				<div class="card-body" aria-hidden="true">

					<p class="card-title placeholder-glow">
						<span class="placeholder col-6"></span>
					</p>

					<p class="card-text placeholder-glow">
						<span class="placeholder col-7"></span>
						<span class="placeholder col-4"></span>
					</p>

					<div class="d-flex flex-column">
						<div class="d-flex">
							<a href="#" tabindex="-1" class="btn btn-sm btn-primary justify-content-center me-2 disabled placeholder col-6"></a>
							<a href="#" tabindex="-1" class="btn btn-sm btn-success justify-content-center me-2 disabled placeholder col-6"></a>
						</div>
					</div>

				</div>

			</div>

		</div>

	</div>

	<div id="cardThemeContainer" class="row g-5 g-xl-8 mt-5"></div>

	<div class="modal fade" tabindex="-1" id="form_modal">

		<div class="modal-dialog modal-dialog-centered mw-650px">

			<div class="modal-content">

				<form action="" method="POST" id="invitation_form" class="form" novalidate="novalidate">

					<div class="modal-header">

						<h3 class="modal-title"><?php echo esc_html( wds_lang( 'dash_invitation_add' ) ); ?></h3>

						<div class="btn btn-icon btn-sm btn-bg-light btn-active-icon-danger" data-bs-dismiss="modal" aria-label="Close">
							<i class="ki-solid ki-cross fs-1"></i>
						</div>

					</div>

					<div class="modal-body">

						<div class="fv-row mb-7">

							<label class="required form-label"><?php echo esc_html( wds_lang( 'dash_invitation_form_title' ) ); ?></label>

							<input type="text" name="title" class="form-control" placeholder="<?php echo esc_html( wds_lang( 'dash_invitation_form_title_placeholder' ) ); ?>" />

						</div>

						<div class="fv-row mb-7">

							<label class="required form-label"><?php echo esc_html( wds_lang( 'dash_invitation_form_slug_title' ) ); ?></label>

							<div class="input-group mb-5">

								<span id="data-url" class="input-group-text"><?php echo esc_url( wp_parse_url( get_site_url(), PHP_URL_HOST ) . '/' ); ?></span>

								<input type="text" name="slug" class="form-control" aria-describedby="data-url" placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_slug_placeholder' ) ); ?>" />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo wp_kses_post( wds_lang( 'dash_invitation_form_slug_description' ) ); ?></div>

							</div>

						</div>

						<?php if ( 'reseller' == wds_user_group() ) : ?>

							<div class="fv-row mb-7">

								<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_form_price' ) ); ?></label>

								<input type="number" name="price" class="form-control" placeholder="99000" />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_html( wds_lang( 'dash_invitation_form_price_desc' ) ); ?></div>

							</div>

						<?php endif; ?>

						<input type="hidden" name="category" id="category" value="">

						<input type="hidden" name="taxonomy" id="taxonomy" value="">

						<input type="hidden" name="template" id="template" value="">

						<input type="hidden" name="template_name" id="templateName" value="">

					</div>

					<div class="modal-footer flex-center">

						<button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>

						<button type="submit" id="invitation_submit" class="btn btn-primary">
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'submit' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>