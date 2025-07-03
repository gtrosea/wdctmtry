<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php esc_html_e( 'Users', 'wds-notrans' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Admin', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'Users', 'wds-notrans' ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card">

		<div class="card-body py-10">

			<div class="row">

				<div class="col-12 col-md-auto">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-primary pb-1 px-2"><?php esc_html_e( 'Users Count', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center d-none w-statistic">
							<span id="w-user" class="ms-2">0</span>
						</span>
						<div class="spinner-border"></div>
					</div>
				</div>

				<div class="col-12 col-md-auto">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-success pb-1 px-2"><?php esc_html_e( 'Active Users', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center d-none w-statistic">
							<span id="w-active" class="ms-2">0</span>
						</span>
						<div class="spinner-border"></div>
					</div>
				</div>

				<div class="col-12 col-md-auto">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-warning pb-1 px-2"><?php esc_html_e( 'Trial', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center d-none w-statistic">
							<span id="w-trial" class="ms-2">0</span>
						</span>
						<div class="spinner-border"></div>
					</div>
				</div>

				<div class="col-12 col-md-auto">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-danger pb-1 px-2"><?php esc_html_e( 'Member', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center d-none w-statistic">
							<span id="w-member" class="ms-2">0</span>
						</span>
						<div class="spinner-border"></div>
					</div>
				</div>

				<div class="col-12 col-md-auto">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-success pb-1 px-2"><?php esc_html_e( 'Reseller', 'wds-notrans' ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center d-none w-statistic">
							<span id="w-reseller" class="ms-2">0</span>
						</span>
						<div class="spinner-border"></div>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="card card-flush mt-5">

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

			<div class="card-toolbar flex-row-fluid justify-content-end gap-5">

				<div class="me-3">

					<a href="#" class="btn btn-light-primary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<i class="ki-duotone ki-filter fs-3"><span class="path1"></span><span class="path2"></span></i>               
						<span><?php esc_html_e( 'Filter', 'wds-notrans' ); ?></span>
					</a>
					
					<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="filter_menu">

						<div class="px-7 py-5">
							<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
						</div>

						<div class="separator border-gray-200"></div>

						<div class="px-7 py-5">

							<div style="max-height: 400px; overflow-y: auto;">

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Status', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="active">Active</option>
											<option value="inactive">Inactive</option>
										</select>
									</div>
								</div>

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Group', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="group" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="trial">Trial</option>
											<option value="member">Member</option>
											<option value="reseller">Reseller</option>
										</select>
									</div>
								</div>

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Expired', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="expired" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="min"><?php esc_html_e( 'Lowest', 'weddingsaas' ); ?></option>
											<option value="max"><?php esc_html_e( 'Highest', 'weddingsaas' ); ?></option>
										</select>
									</div>
								</div>

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Invitation Quota', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="invitation_quota" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="min"><?php esc_html_e( 'Lowest', 'weddingsaas' ); ?></option>
											<option value="max"><?php esc_html_e( 'Highest', 'weddingsaas' ); ?></option>
										</select>
									</div>
								</div>

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Client Quota', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="client_quota" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="min"><?php esc_html_e( 'Lowest', 'weddingsaas' ); ?></option>
											<option value="max"><?php esc_html_e( 'Highest', 'weddingsaas' ); ?></option>
										</select>
									</div>
								</div>

								<div class="mb-5">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Invitation Created', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="created" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="min"><?php esc_html_e( 'Lowest', 'weddingsaas' ); ?></option>
											<option value="max"><?php esc_html_e( 'Highest', 'weddingsaas' ); ?></option>
										</select>
									</div>
								</div>

								<div class="">
									<label class="form-label fw-semibold"><?php esc_html_e( 'Storage', 'weddingsaas' ); ?>:</label>
									<div>
										<select name="storage" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php esc_html_e( 'Select option', 'weddingsaas' ); ?>" data-dropdown-parent="#filter_menu">
											<option value=""><?php esc_html_e( 'Select option', 'weddingsaas' ); ?></option>
											<option value="min"><?php esc_html_e( 'Lowest', 'weddingsaas' ); ?></option>
											<option value="max"><?php esc_html_e( 'Highest', 'weddingsaas' ); ?></option>
										</select>
									</div>
								</div>

							</div>
							
							<div class="d-flex justify-content-end mt-5">
								<button type="reset" id="btn-reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>

								<button type="submit" id="btn-filter" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true"><?php echo esc_html( wds_lang( 'apply' ) ); ?></button>
							</div>

						</div>
					</div>
				</div>

				<button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
					<i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span class="path2"></span></i>
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

		<div class="card-body py-4">

			<div class="table-responsive">

				<table id="table_primary" class="table table-row-bordered align-middle gy-4 gs-9">

					<thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">

						<tr>
							<th class="min-w-125px"><?php esc_html_e( 'Name', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Status', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Group', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Expired', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Invitation Quota', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Client Quota', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Invitation Created', 'wds-notrans' ); ?></th>
							<th class="min-w-125px"><?php esc_html_e( 'Storage', 'wds-notrans' ); ?></th>
						</tr>

					</thead>

					<tbody class="text-gray-600 fw-semibold"></tbody>

				</table>

			</div>

		</div>

	</div>

</div>
