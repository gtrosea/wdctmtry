<div class="card">

	<div class="card-body py-10">

		<div class="row">

			<div class="col-6 col-md-6 col-xl-3">

				<div class="card card-dashed flex-center my-3 p-3">

					<span class="fs-4 fw-semibold text-primary pb-1 px-2"><?php echo esc_html( wds_lang( 'rsvp_total' ) ); ?></span>

					<span class="fw-bold d-flex justify-content-center">
						<span class="fs-3" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $data[0]->all ); ?>">0</span>
					</span>

				</div>

			</div>

			<div class="col-6 col-md-6 col-xl-3">

				<div class="card card-dashed flex-center my-3 p-3">

					<span class="fs-4 fw-semibold text-success pb-1 px-2"><?php echo esc_html( wds_lang( 'rsvp_attendance_present' ) ); ?></span>

					<span class="fw-bold d-flex justify-content-center">
						<span class="fs-3" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $data[0]->present ); ?>  ">0</span>
					</span>

				</div>

			</div>

			<div class="col-6 col-md-6 col-xl-3">

				<div class="card card-dashed flex-center my-3 p-3">

					<span class="fs-4 fw-semibold text-warning pb-1 px-2"><?php echo esc_html( wds_lang( 'rsvp_attendance_notpresent' ) ); ?></span>

					<span class="fw-bold d-flex justify-content-center">
						<span class="fs-3" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $data[0]->notpresent ); ?>">0</span>
					</span>

				</div>

			</div>

			<div class="col-6 col-md-6 col-xl-3">

				<div class="card card-dashed flex-center my-3 p-3">

					<span class="fs-4 fw-semibold text-danger pb-1 px-2"><?php echo esc_html( wds_lang( 'rsvp_attendance_notsure' ) ); ?></span>

					<span class="fw-bold d-flex justify-content-center">
						<span class="fs-3" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $data[0]->notsure ); ?>">0</span>
					</span>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="card card-flush mt-5">

	<div class="card-header align-items-center py-5 gap-2 gap-md-5">

		<div class="card-title">

			<div class="d-flex align-items-center position-relative my-1">

				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>

				<input type="text" id="table_rsvp_search" class="form-control form-control-solid w-250px ps-12" placeholder="<?php esc_attr( wds_lang( 'search' ) ); ?> RSVP" data-kt-filter="search" />

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

			<div id="table_rsvp_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

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

			<div id="table_rsvp_button" class="d-none"></div>

		</div>

	</div>

	<div class="card-body pt-0">

		<div class="table-responsive">

			<table id="rsvp_table" class="table table-row-bordered align-middle gy-4 gs-9" data-title="<?php echo esc_attr( $title ); ?>">

				<thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">

					<tr>
						<th class="text min-w-100px"><?php echo esc_html( wds_lang( 'name' ) ); ?></th>
						<?php if ( 'default' == $integration ) : ?>
							<th class="text min-w-100px"><?php echo esc_html( wds_lang( 'rsvp_confirmation_attendance' ) ); ?></th>
							<th class="text min-w-100px"><?php echo esc_html( wds_lang( 'rsvp_guest' ) ); ?></th>
						<?php elseif ( 'other' == $integration ) : ?>
							<th class="text min-w-200px"><?php echo esc_html( wds_lang( 'rsvp_confirmation_attendance' ) ); ?></th>
						<?php endif; ?>
						<th class="text min-w-300px"><?php echo esc_html( wds_lang( 'rsvp_comment' ) ); ?></th>
					</tr>

				</thead>

				<tbody class="fw-semibold text-gray-600">

					<?php if ( 'default' == $integration && wds_check_array( $rsvp, true ) ) : ?>

						<?php foreach ( $rsvp as $x ) : ?>

							<tr>
								<td class="text">
									<span class="fw-bold"><?php echo esc_html( $x->name ); ?></span>
								</td>
								<td class="text">
									<span class="fw-bold"><?php echo esc_html( $x->attendance ); ?></span>
								</td>
								<td class="text">
									<span class="fw-bold"><?php echo esc_html( $x->guest ); ?></span>
								</td>
								<td class="text">
									<span class="fw-bold"><?php echo wp_kses_post( $x->comment ); ?></span>
								</td>
							</tr>

						<?php endforeach; ?>

					<?php elseif ( 'other' == $integration && wds_check_array( $rsvp, true ) ) : ?>

						<?php foreach ( $rsvp as $x ) : ?>

							<tr>
								<td class="text">
									<span class="fw-bold"><?php echo esc_html( $x->name ); ?></span>
								</td>
								<td class="text">
									<span class="fw-bold"><?php echo esc_html( $x->attendance ); ?></span>
								</td>
								<td class="text">
									<span class="fw-bold"><?php echo wp_kses_post( $x->comment ); ?></span>
								</td>
							</tr>

						<?php endforeach; ?>

					<?php endif; ?>

				</tbody>

			</table>

		</div>

	</div>

</div>