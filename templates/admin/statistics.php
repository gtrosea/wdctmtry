<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php esc_html_e( 'Statistics', 'wds-notrans' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Admin', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'Statistics', 'wds-notrans' ); ?></li>

		</ul>

	</div>

	<div class="d-flex align-items-center py-2 py-md-1">

		<div class="input-group">

			<span class="input-group-text">

				<i class="ki-duotone ki-calendar-8 fs-1 ms-2 me-0">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
					<span class="path4"></span>
					<span class="path5"></span>
					<span class="path6"></span>
				</i>
			</span>

			<input id="wds_daterangepicker" class="form-control" placeholder="<?php esc_attr_e( 'Pick date range', 'wds-notrans' ); ?>" />

		</div>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="row g-5 g-xl-8">

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-leads" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Leads', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-badge text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-sales" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Sales', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-chart-line-star text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-5 mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-conversion" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0%</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Conversion', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-percentage text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="row g-5 g-xl-8">

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-revenue" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Revenue', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-two-credit-cart text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-commission" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Commissions', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-briefcase text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-xl-4">

			<div class="card card-xl-stretch mb-5 mb-xl-8">

				<div class="card-body d-flex align-items-center pt-5 pb-5">

					<div class="d-flex flex-column flex-grow-1 d-none w-statistic">

						<a href="#" id="w-profit" class="fw-bold text-gray-900 fs-4 mb-2 text-hover-primary">0</a>

						<span class="fw-semibold text-muted fs-5"><?php esc_html_e( 'Nett Profit', 'wds-notrans' ); ?></span>

					</div>

					<div class="spinner-border"></div>

					<div class="d-flex flex-column flex-grow-1">

						<div class="symbol symbol-50px symbol-circle w-40px me-5 align-self-end">

							<span class="symbol-label bg-light-primary">
								<i class="ki-duotone ki-bill text-primary fs-1">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
									<span class="path6"></span>
								</i>
							</span>

						</div>

					</div>

				</div>

			</div>
			
		</div>

	</div>

	<div class="row g-5 g-xl-8">

		<div class="col-xl-12">

			<div class="card card-flush mb-5 mb-xl-10 overlay overlay-block statistic-loader-overlay">

				<div class="card-header pt-7">

					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-800"><?php esc_html_e( 'Chart', 'wds-notrans' ); ?></span>
						<span id="w-chart-date" class="text-gray-400 mt-1 fw-semibold fs-6 statistic-date"></span>
					</h3>
					
				</div>

				<div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
					<div id="wds_charts" class="h-500px w-100 min-h-auto ps-4 pe-6"></div>
				</div>

				<div class="overlay-layer card-rounded bg-dark bg-opacity-5 statistic-loader">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden"><?php esc_html_e( 'Loading', 'wds-notrans' ); ?>...</span>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="card mb-5 mb-xl-8">

		<div class="card-header border-0 pt-5">

			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bold fs-3 mb-1"><?php esc_html_e( 'Products', 'wds-notrans' ); ?></span>
				<span class="text-muted mt-1 fw-semibold fs-7 statistic-date"></span>
			</h3>

		</div>

		<div class="card-body py-3">

			<div id="productTabelContainer" class="table-responsive overlay overlay-block statistic-loader-overlay">

				<table id="productTable" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">

					<thead>
						<tr class="fw-bold text-muted bg-light">
							<th class="ps-4 min-w-300px rounded-start"><?php esc_html_e( 'Products', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Sales', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Revenue', 'wds-notrans' ); ?></th>
							<th class="min-w-125px text-end rounded-end"></th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>

				</table>

				<div class="overlay-layer card-rounded bg-dark bg-opacity-5 .statistic-loader">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden"><?php esc_html_e( 'Loading', 'wds-notrans' ); ?>...</span>
					</div>
				</div>

			</div>
			
		</div>

	</div>

</div>