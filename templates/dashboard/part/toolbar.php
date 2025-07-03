<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></li>

		</ul>

	</div>

	<div class="d-flex align-items-center py-2 py-md-1">

		<div class="me-3">

			<a href="#" class="btn btn-primary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
				<i class="ki-solid ki-element-8 fs-5 me-1"></i>
				<?php esc_html_e( 'Layout', 'wds-notrans' ); ?>
			</a>

			<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true">

				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold"><?php esc_html_e( 'Pilih Layout', 'weddingsaas' ); ?></div>
				</div>

				<div class="separator border-gray-200"></div>

				<form>

					<div class="px-7 py-5">

						<div class="mb-10">

							<div class="d-flex">

								<?php
								$layouts = array( 'table', 'grid' );
								foreach ( $layouts as $x ) {
									$checked = wds_user_layout() == $x ? 'checked' : '';
									echo '<label class="form-check form-check-sm form-check-custom form-check-solid me-5 cursor-pointer"><input type="radio" name="layout" class="form-check-input cursor-pointer" value="' . esc_attr( $x ) . '" ' . esc_attr( $checked ) . '/><span class="form-check-label">' . esc_html( ucfirst( $x ) ) . '</span></label>';
								}
								?>

							</div>

						</div>

						<div class="d-flex justify-content-end">

							<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>

							<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true"><?php echo esc_html( wds_lang( 'apply' ) ); ?></button>

						</div>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>