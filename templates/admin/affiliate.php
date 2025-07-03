<?php $commission = wds_data( 'data_commission' ); ?>
<?php $results = wds_data( 'data_results' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php esc_html_e( 'Affiliate', 'wds-notrans' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Admin', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'Affiliate', 'wds-notrans' ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card">

		<div class="card-body py-10">

			<h2 class="mb-6"><?php esc_html_e( 'Affiliate Commission', 'wds-notrans' ); ?></h2>

			<div class="row">

				<div class="col-12 col-md-6 col-xl-3">
					<div class="card card-dashed flex-center text-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-primary pb-1 px-2"><?php esc_html_e( 'Total Commission', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $commission['total'] ? esc_html( strtok( wds_convert_money( $commission['total'] ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $commission['total'] ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-md-6 col-xl-3">
					<div class="card card-dashed flex-center text-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-success pb-1 px-2"><?php esc_html_e( 'Paid Commissions', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $commission['paid'] ? esc_html( strtok( wds_convert_money( $commission['paid'] ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $commission['paid'] ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-md-6 col-xl-3">
					<div class="card card-dashed flex-center text-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-warning pb-1 px-2"><?php esc_html_e( 'Unpaid Commissions', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $commission['unpaid'] ? esc_html( strtok( wds_convert_money( $commission['unpaid'] ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $commission['unpaid'] ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-md-6 col-xl-3">
					<div class="card card-dashed flex-center text-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-danger pb-1 px-2"><?php esc_html_e( 'Pending Commissions', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $commission['pending'] ? esc_html( strtok( wds_convert_money( $commission['pending'] ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $commission['pending'] ); ?>">0</span>
						</span>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="card mt-5 mt-xl-10">

		<div class="card-header border-0 pt-6">

			<div class="card-title">

				<div class="d-flex align-items-center position-relative my-1">

					<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>

					<input type="text" id="table_filter_search" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="<?php esc_attr_e( 'Search user', 'wds-notrans' ); ?>" />

				</div>

			</div>

			<div class="card-toolbar">

				<div class="d-flex justify-content-end">

					<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<i class="ki-duotone ki-exit-up fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						<span><?php esc_html_e( 'Export', 'wds-notrans' ); ?></span>
					</button>

					<div id="table_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" data-kt-export="copy"><?php esc_html_e( 'Copy to clipboard', 'wds-notrans' ); ?></a>
						</div>

						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" data-kt-export="excel"><?php esc_html_e( 'Export as Excel', 'wds-notrans' ); ?></a>
						</div>

						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" data-kt-export="csv"><?php esc_html_e( 'Export as CSV', 'wds-notrans' ); ?></a>
						</div>

						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" data-kt-export="pdf"><?php esc_html_e( 'Export as PDF', 'wds-notrans' ); ?></a>
						</div>

					</div>

					<div id="table_export_button" class="d-none"></div>

				</div>

			</div>

		</div>

		<div class="card-body py-4">

			<div class="table-responsive">

				<table id="table_primary" class="table align-middle table-row-dashed fs-6 gy-5 wds-table">

					<thead>

						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-100px text-start"><?php esc_html_e( 'User', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Lead/Sales', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Pending', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Unpaid', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Paid', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-center"><?php esc_html_e( 'Total', 'wds-notrans' ); ?></th>
							<th class="min-w-100px text-end"><?php esc_html_e( 'Conversion', 'wds-notrans' ); ?></th>
						</tr>

					</thead>

					<tbody class="fw-semibold text-gray-600">

						<?php foreach ( $results as $data ) : ?>

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
									<div class="badge badge-light fw-bold"><?php echo esc_html( $data->leads ); ?>/<?php echo esc_html( $data->sales ); ?></div>
								</td>

								<td class="text-center pe-0" data-order="<?php echo number_format( $data->pending, 0 ); ?>">
									<div class="badge badge-danger fw-bold"><?php echo number_format( $data->pending, 0 ) == 0 ? '-' : esc_html( wds_convert_money( $data->pending ) ); ?></div>
								</td>

								<td class="text-center pe-0" data-order="<?php echo number_format( $data->unpaid, 0 ); ?>">
									<div class="badge badge-warning fw-bold"><?php echo number_format( $data->unpaid, 0 ) == 0 ? '-' : esc_html( wds_convert_money( $data->unpaid ) ); ?></div>
								</td>

								<td class="text-center pe-0" data-order="<?php echo number_format( $data->paid, 0 ); ?>">
									<div class="badge badge-success fw-bold"><?php echo number_format( $data->paid, 0 ) == 0 ? '-' : esc_html( wds_convert_money( $data->paid ) ); ?></div>
								</td>

								<td class="text-center pe-0" data-order="<?php echo number_format( $data->total, 0 ); ?>">
									<div class="badge badge-primary fw-bold"><?php echo number_format( $data->total, 0 ) == 0 ? '-' : esc_html( wds_convert_money( $data->total ) ); ?></div>
								</td>

								<td class="text-end" data-order="<?php echo 0 == $data->sales ? 0 : esc_attr( round( ( $data->sales / $data->leads ) * 100, 2 ) ); ?>">
									<div class="badge badge-info fw-bold"><?php echo 0 == $data->sales ? 0 : esc_html( round( ( $data->sales / $data->leads ) * 100, 2 ) ); ?>%</div>
								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

		</div>

	</div>

</div>