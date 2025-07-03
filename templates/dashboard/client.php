<?php
$data          = wds_data( 'data_client' );
$status        = wds_user_status();
$user_group    = wds_user_group();
$quota         = wds_user_client_quota();
$upgrade       = wds_url( 'upgrade_reseller' );
$upgrade_topup = wds_url( 'upgrade_quota' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'client' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'client' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<div class="card card-flush">

		<div class="card-header border-0 pt-6">

			<div class="card-title">

				<div class="d-flex align-items-center position-relative my-1">

					<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>

					<input type="text" id="table_client_search" class="form-control form-control-solid w-250px ps-13" placeholder="<?php echo esc_attr( wds_lang( 'dash_client_search' ) ); ?>" data-kt-filter="search" />

				</div>

			</div>

			<div class="card-toolbar flex-row-fluid justify-content-end gap-5">

				<button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
					<i class="ki-duotone ki-exit-down fs-2">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					<?php esc_html_e( 'Export', 'wds-notrans' ); ?>
				</button>

				<div id="table_client_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

					<div class="menu-item px-3">
						<a href="#" class="menu-link px-3" data-kt-export="copy">
							<?php esc_html_e( 'Copy to clipboard', 'wds-notrans' ); ?>
						</a>
					</div>

					<div class="menu-item px-3">
						<a href="#" class="menu-link px-3" data-kt-export="excel">
							<?php esc_html_e( 'Export as Excel', 'wds-notrans' ); ?>
						</a>
					</div>

					<div class="menu-item px-3">
						<a href="#" class="menu-link px-3" data-kt-export="csv">
							<?php esc_html_e( 'Export as CSV', 'wds-notrans' ); ?>
						</a>
					</div>

					<div class="menu-item px-3">
						<a href="#" class="menu-link px-3" data-kt-export="pdf">
							<?php esc_html_e( 'Export as PDF', 'wds-notrans' ); ?>
						</a>
					</div>

				</div>

				<div id="table_client_button" class="d-none"></div>

				<?php if ( 'active' == $status && $quota > 0 ) : ?>

					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
						<i class="ki-duotone ki-plus fs-2"></i>
						<?php echo esc_html( wds_lang( 'dash_client_add' ) ); ?>
					</button>

				<?php else : ?>

					<?php if ( 'active' == $status ) : ?>

						<a href="<?php echo esc_url( $upgrade_topup ); ?>" class="btn btn-danger">
							<i class="ki-duotone ki-rocket">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<?php echo esc_html( wds_lang( 'dash_invitation_upgrade' ) ); ?>
						</a>

					<?php else : ?>

						<a href="<?php echo esc_url( $upgrade ); ?>" class="btn btn-danger">
							<i class="ki-duotone ki-rocket">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<?php echo esc_html( wds_lang( 'dash_invitation_upgrade' ) ); ?>
						</a>

					<?php endif; ?>

				<?php endif; ?>

			</div>

		</div>

		<div class="card-body py-4">

			<div class="table-responsive">

				<table id="client_table" class="table table-row-bordered align-middle gy-4 gs-9">

					<thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">

						<tr>
							<th class="min-w-125px"><?php echo esc_html( wds_lang( 'fullname' ) ); ?></th>
							<th class="min-w-125px"><?php echo esc_html( wds_lang( 'phone' ) ); ?></th>
							<th class="min-w-125px"><?php echo esc_html( wds_lang( 'dash_client_join' ) ); ?></th>
						</tr>

					</thead>

					<tbody class="text-gray-600 fw-semibold">

						<?php if ( wds_check_array( $data, true ) ) : ?>

							<?php foreach ( $data as $client ) : ?>

								<tr>
									<td class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
											<div class="symbol-label">
												<img src="<?php echo esc_url( wds_user_avatar( $client->email, 100 ) ); ?>" class="w-100" alt="<?php echo esc_attr( $client->name ); ?>" />
											</div>
										</div>
										<div class="d-flex flex-column">
											<span class="text-gray-800"><?php echo esc_html( $client->name ); ?></span>
											<span><?php echo esc_html( $client->email ); ?></span>
										</div>
									</td>
									<td><?php echo esc_html( $client->phone ); ?></td>
									<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $client->join ) ) ); ?></td>
								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

		</div>

	</div>

	<?php if ( 'active' == $status && $quota > 0 ) : ?>

		<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">

			<div class="modal-dialog modal-dialog-centered mw-650px">

				<div class="modal-content">

					<form action="" method="POST" id="client_form" class="form" novalidate="novalidate">

						<div id="kt_modal_add_user_header" class="modal-header">

							<h2 class="fw-bold"><?php echo esc_html( wds_lang( 'dash_client_add' ) ); ?></h2>

							<div class="btn btn-icon btn-sm btn-bg-light btn-active-icon-danger" data-bs-dismiss="modal">
								<i class="ki-solid ki-cross fs-1"></i>
							</div>

						</div>

						<div class="modal-body px-5 my-7">

							<div id="kt_modal_add_user_scroll" class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">

								<input type="hidden" name="reseller_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" required />

								<div class="fv-row mb-7">
									<label class="required fw-semibold fs-6 mb-2"><?php echo esc_html( wds_lang( 'fullname' ) ); ?></label>
									<input type="text" name="fullname" class="form-control" placeholder="" autocomplete="off" required />
								</div>

								<div class="fv-row mb-7">
									<label class="required fw-semibold fs-6 mb-2"><?php echo esc_html( wds_lang( 'email' ) ); ?></label>
									<input type="email" name="email" class="form-control" placeholder="" autocomplete="off" required />
								</div>

								<div class="fv-row mb-7">
									<label class="required fw-semibold fs-6 mb-2"><?php echo esc_html( wds_lang( 'phone' ) ); ?></label>
									<input type="number" name="phone" class="form-control" placeholder="" autocomplete="off" required />
								</div>

								<div class="fv-row mb-7">
									<label class="required fw-semibold fs-6 mb-2"><?php echo esc_html( wds_lang( 'password' ) ); ?></label>
									<input type="text" name="password" class="form-control" placeholder="" autocomplete="off" required />
								</div>

								<div class="fv-row mb-7">
									<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'select' ) . ' ' . wds_lang( 'product' ) ); ?></label>
									<select name="product" id="product" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="<?php echo esc_attr( wds_lang( 'select' ) . ' ' . wds_lang( 'product' ) ); ?>">
										<?php
										$products        = wds_get_product_restrict( 'client' );
										$products_client = wds_check_array( wds_option( 'client_product' ) );
										foreach ( $products as $id => $product ) :
											if ( empty( $products_client ) || in_array( $id, $products_client ) ) {
												echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $product ) . '</option>';
											}
										endforeach;
										?>
									</select>
								</div>

								<div class="fv-row mb-7">
									<label class="required fw-semibold fs-6 mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_form_price' ) ); ?></label>
									<input type="number" name="price" class="form-control" placeholder="" autocomplete="off" required />
									<div class="fs-7 fw-semibold text-muted"><?php echo esc_html( wds_lang( 'dash_invitation_form_price_desc' ) ); ?></div>
								</div>

							</div>

						</div>

						<div class="modal-footer flex-center">

							<button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>

							<button type="submit" id="client_submit" class="btn btn-primary">
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

	<?php endif; ?>

</div>