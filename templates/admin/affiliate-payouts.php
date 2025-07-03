<?php $unpaid = wds_data( 'data_unpaid' ); ?>
<?php $withdraw = wds_data( 'data_withdraw' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php esc_html_e( 'Affiliate Payouts', 'wds-notrans' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Admin', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'Affiliate Payouts', 'wds-notrans' ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card ">

		<div class="card-header pt-6 pb-4">

			<div class="card-title">

				<div>

					<h3><?php esc_html_e( 'Unpaid Payout Commission', 'wds-notrans' ); ?></h3>

					<div class="d-flex align-items-center position-relative mt-5">

						<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>

						<input type="text" id="table_filter_search" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="<?php esc_attr_e( 'Search user', 'wds-notrans' ); ?>" />

					</div>

				</div>

			</div>

		</div>

		<div class="card-body py-4">

			<div class="table-responsive">

				<table id="table_primary" class="table align-middle table-row-dashed fs-6 gy-5 wds-table">

					<thead>

						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-100px text-start"><?php esc_html_e( 'User', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Amount', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Actions', 'wds-notrans' ); ?></th>
						</tr>

					</thead>

					<tbody class="fw-semibold text-gray-600">

						<?php foreach ( $unpaid as $data ) : ?>

							<tr>

								<td class="d-flex align-items-center">
									<div class="symbol symbol-circle symbol-40px overflow-hidden me-3">
										<a href="#">
											<div class="symbol-label">
												<img src="<?php echo esc_url( wds_user_avatar( $data->user_id ) ); ?>" alt="<?php echo esc_attr( wds_user_name( $data->user_id ) ); ?>" class="w-100" />
											</div>
										</a>
									</div>
									<div class="d-flex flex-column">
										<div class="fs-7 text-gray-800 mb-1"><?php echo esc_html( wds_user_name( $data->user_id ) ); ?></div>
										<span class="fs-8"><?php echo esc_html( $data->email ); ?></span>
									</div>
								</td>

								<td class="text-center pe-0" data-order="<?php echo number_format( $data->unpaid, 0 ); ?>">
									<div class="badge badge-warning fw-bold"><?php echo number_format( $data->unpaid, 0 ) == 0 ? '-' : esc_html( wds_convert_money( $data->unpaid ) ); ?></div>
								</td>

								<td class="text-center pe-0">
									<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
										<span><?php esc_html_e( 'Actions', 'wds-notrans' ); ?></span>
										<i class="ki-duotone ki-down fs-5 ms-1"></i>
									</a>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_payout<?php echo esc_attr( $data->user_id ); ?>"><?php esc_html_e( 'Payout Now', 'wds-notrans' ); ?></a>
										</div>
									</div>
								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		</div>

	</div>

	<div class="card mt-5 mt-lg-10">

		<div class="card-header pt-6 pb-4">

			<div class="card-title">

				<div>

					<h3><?php esc_html_e( 'Withdraw Commission', 'wds-notrans' ); ?></h3>

					<div class="d-flex align-items-center position-relative mt-5">

						<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>

						<input type="text" id="table_filter_search2" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="<?php esc_attr_e( 'Search user', 'wds-notrans' ); ?>" />

					</div>

				</div>

			</div>

		</div>

		<div class="card-body py-4">

			<div class="table-responsive">

				<table id="table_secondary" class="table align-middle table-row-dashed fs-6 gy-5 wds-table">

					<thead>

						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-100px text-start"><?php esc_html_e( 'User', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Amount', 'wds-notrans' ); ?></th>
							<th class="min-w-200px text-center"><?php esc_html_e( 'Method', 'wds-notrans' ); ?></th>
							<th class="min-w-125px text-center"><?php esc_html_e( 'Date', 'wds-notrans' ); ?></th>
						</tr>

					</thead>

					<tbody class="fw-semibold text-gray-600">

						<?php foreach ( $withdraw as $data ) : ?>

							<tr>

								<td class="d-flex align-items-center">
									<div class="symbol symbol-circle symbol-40px overflow-hidden me-3">
										<a href="#">
											<div class="symbol-label">
												<img src="<?php echo esc_url( wds_user_avatar( $data->user_id ) ); ?>" alt="<?php echo esc_attr( wds_user_name( $data->user_id ) ); ?>" class="w-100" />
											</div>
										</a>
									</div>
									<div class="d-flex flex-column">
										<div class="fs-7 text-gray-800 mb-1"><?php echo esc_html( wds_user_name( $data->user_id ) ); ?></div>
										<span class="fs-8"><?php echo esc_html( $data->email ); ?></span>
									</div>
								</td>

								<td class="text-center pe-0">
									<div class="badge badge-success fw-bold"><?php echo esc_html( wds_convert_money( $data->amount ) ); ?></div>
								</td>

								<td class="text-center pe-0"><?php echo esc_html( $data->method ); ?></td>

								<td class="text-center pe-0" data-order="<?php echo esc_attr( $data->created_at ); ?>"><?php echo esc_html( wds_date_format( strtotime( $data->created_at ) ) ); ?></td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		</div>

	</div>

</div>

<?php foreach ( $unpaid as $data ) : ?>

	<?php $id = $data->user_id; ?>

	<div class="modal fade" id="modal_payout<?php echo esc_attr( $id ); ?>" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered mw-500px">

			<div class="modal-content">

				<div class="modal-header">

					<h2 class="fw-bold"><?php esc_html_e( 'Payout Commission', 'wds-notrans' ); ?></h2>

					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>

				</div>

				<div class="modal-body scroll-y">

					<form action="" method="POST" class="form mark-paid-form" data-form-id="<?php echo esc_attr( $id ); ?>">

						<div class="mb-6">

							<label class="fw-semibold fs-6 mb-2"><?php esc_html_e( 'Affiliate Name', 'wds-notrans' ); ?></label>

							<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" value="<?php echo esc_attr( wds_user_name( $id ) ); ?>" readonly />

						</div>

						<div class="mb-6">

							<label class="fw-semibold fs-6 mb-2"><?php esc_html_e( 'Affiliate Email', 'wds-notrans' ); ?></label>

							<input type="text" class="form-control form-control-solid mb-3 mb-lg-0" value="<?php echo esc_attr( $data->email ); ?>" readonly />

						</div>

						<div class="mb-6">

							<label class="fw-semibold fs-6 mb-2"><?php esc_html_e( 'Commission Amount', 'wds-notrans' ); ?></label>

							<input type="text" id="_amount<?php echo esc_attr( $id ); ?>" class="form-control form-control-solid mb-3 mb-lg-0" data-value="<?php echo esc_attr( floatval( $data->unpaid ) ); ?>" value="<?php echo esc_attr( wds_convert_money( floatval( $data->unpaid ) ) ); ?>" readonly />

						</div>

						<div class="mb-6">

							<label class="fw-semibold fs-6 mb-2"><?php esc_html_e( 'Payout Method', 'wds-notrans' ); ?></label>

							<?php
							$methods = wds_user_meta( $id, '_affiliate_payment_method' );
							if ( 'banktransfer' == $methods ) :
								$method = 'Bank Transfer';
							else :
								$method = __( 'Empty payout method, please ask affiliate to add their payout method!', 'wds-notrans' );
							endif;
							?>

							<input type="text" id="_method<?php echo esc_attr( $id ); ?>" class="form-control form-control-solid mb-3 mb-lg-0" value="<?php echo esc_attr( $method ); ?>" readonly />

						</div>

						<?php if ( $methods ) : ?>

							<div class="mb-6">

								<label class="fw-semibold fs-6 mb-2"><?php esc_html_e( 'Payout Method Account', 'wds-notrans' ); ?></label>

								<input type="text" id="_account<?php echo esc_attr( $id ); ?>" class="form-control form-control-solid mb-3 mb-lg-0" value="<?php echo esc_attr( wds_user_meta( $id, '_affiliate_payment_method_account' ) ); ?>" readonly />

							</div>

						<?php endif; ?>

						<div class="text-center mt-12">
							<button type="button" class="btn btn-light me-3" data-bs-dismiss="modal"><?php esc_html_e( 'Cancel', 'wds-notrans' ); ?></button>
							<button type="button" class="btn btn-primary fw-bold flex-shrink-0 mark-paid-submit" <?php echo $methods ? '' : 'disabled'; ?>>
								<span class="indicator-label"><?php esc_html_e( 'Mark Paid', 'wds-notrans' ); ?></span>
								<span class="indicator-progress"><span class="spinner-border spinner-border-sm align-middle"></span></span>
							</button>
						</div>

						<input type="hidden" name="user_id" value="<?php echo esc_attr( $id ); ?>" />

					</form>

				</div>

			</div>

		</div>

	</div>

<?php endforeach; ?>