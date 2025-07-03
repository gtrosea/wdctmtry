<?php $product = wds_data( 'data_products' ); ?>
<?php $summary = wds_data( 'data_summary' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'account_ref_header' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php echo esc_html( wds_lang( 'account' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'account_ref_header' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<div class="card">

		<div class="card-header card-header-stretch">

			<div class="card-title">

				<h3><?php echo esc_html( wds_lang( 'account_ref_title' ) ); ?></h3>

			</div>

		</div>

		<div class="card-body py-10">

			<p class="fs-6 fw-semibold text-gray-600"><?php echo esc_html( wds_lang( 'account_ref_subtitle' ) ); ?></p>

			<div class="d-flex mb-10">

				<input type="text" id="general_link_input" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_attr( wds_affiliate_link() ); ?>" readonly />

				<button id="general_link_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0" data-clipboard-target="#general_link_input"><?php echo esc_html( wds_lang( 'account_ref_copy_link' ) ); ?></button>

			</div>

			<h4 class="text-gray-800 mb-5"><?php echo esc_html( wds_lang( 'account_ref_generate_title' ) ); ?></h4>

			<form action="" method="POST" id="generate_form" class="card-title w-100">

				<div class="d-flex flex-column flex-sm-row">

					<select name="product" id="product" class="form-select form-control-solid" data-control="select2" data-placeholder="<?php echo esc_attr( wds_lang( 'select' ) . ' ' . wds_lang( 'product' ) ); ?>">

						<option value=""><?php echo esc_html( wds_lang( 'select' ) . ' ' . wds_lang( 'product' ) ); ?></option>
						<?php
						foreach ( $product as $item ) :
							$id    = $item->ID;
							$title = $item->title;
							if ( $item->affiliate && ! empty( wds_get_product_meta( $id, 'affiliate_commission' ) ) ) {
								if ( ! empty( wds_user_affiliate_commission() ) ) {
									$commission = wds_user_affiliate_commission() . '%';
								} else {
									$commission = wds_get_product_meta( $id, 'affiliate_commission' );
								}
								echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $title ) . ' (' . esc_html( wds_lang( 'commission' ) . ' ' . $commission ) . ')</option>';
							}
						endforeach;
						?>
					</select>

					<button type="submit" id="generate_submit" class="btn btn-primary ms-0 ms-md-3 mt-3 mt-md-0">
						<span class="indicator-label"><?php echo esc_html( wds_lang( 'generate' ) ); ?></span>
						<span class="indicator-progress">
							<span class="spinner-border spinner-border-sm align-middle"></span>
						</span>
					</button>

				</div>
				
			</form>

			<div id="result_generate_link" class="d-none">

				<p class="fs-6 fw-semibold text-gray-600 py-4 m-0"><?php esc_html_e( 'Sales Page:', 'wds-notrans' ); ?></p>

				<div class="d-flex flex-column flex-sm-row">

					<input type="text" id="salespage_link_input" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_attr( wds_affiliate_link() ); ?>" readonly />

					<button id="salespage_link_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0 mt-3 mt-md-0" data-clipboard-target="#salespage_link_input"><?php echo esc_html( wds_lang( 'account_ref_copy_link' ) ); ?></button>

				</div>

				<p class="fs-6 fw-semibold text-gray-600 py-4 m-0"><?php esc_html_e( 'Checkout Page:', 'wds-notrans' ); ?></p>

				<div class="d-flex flex-column flex-sm-row">

					<input type="text" id="checkout_link_input" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_attr( wds_affiliate_link() ); ?>" readonly />

					<button id="checkout_link_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0 mt-3 mt-md-0" data-clipboard-target="#checkout_link_input"><?php echo esc_html( wds_lang( 'account_ref_copy_link' ) ); ?></button>

				</div>

			</div>

		</div>

	</div>

	<div class="card mt-5 mt-xl-10">

		<div class="card-header card-header-stretch">

			<div class="card-title">

				<h3><?php echo esc_html( wds_lang( 'account_ref_statistics' ) ); ?></h3>

			</div>
			
		</div>

		<div class="card-body py-10">

			<div class="row">

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-primary pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_total_commisions' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $summary->commission->total ? esc_html( strtok( wds_convert_money( $summary->commission->total ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->commission->total ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-success pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_paid_commisions' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $summary->commission->paid ? esc_html( strtok( wds_convert_money( $summary->commission->paid ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->commission->paid ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-warning pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_unpaid_commisions' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $summary->commission->unpaid ? esc_html( strtok( wds_convert_money( $summary->commission->unpaid ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->commission->unpaid ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-danger pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_pending_commisions' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center"><?php echo $summary->commission->pending ? esc_html( strtok( wds_convert_money( $summary->commission->pending ), ' ' ) ) : ''; ?>
							<span class="ms-2" data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->commission->pending ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-warning pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_total_clicks' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center">
							<span data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->clicks ); ?>">0</span>
							<span>/</span>
							<span data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->uclicks ); ?>">0</span>
						</span>
					</div>
				</div>

				<div class="col-12 col-lg-4">
					<div class="card card-dashed flex-center min-w-175px my-3 p-6">
						<span class="fs-4 fw-semibold text-primary pb-1 px-2"><?php echo esc_html( wds_lang( 'account_ref_total_sales' ) ); ?></span>
						<span class="fs-lg-2x fw-bold d-flex justify-content-center">
							<span data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->leads ); ?>">0</span>
							<span>/</span>
							<span data-kt-countup="true" data-kt-countup-value="<?php echo esc_attr( $summary->sales ); ?>">0</span>
						</span>
					</div>
				</div>

			</div>

			<p class="fs-5 fw-semibold text-gray-600 py-6"><?php echo wp_kses_post( wds_lang( 'account_ref_notes' ) ); ?></p>

			<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">

				<i class="ki-duotone ki-bank fs-2tx text-primary me-4">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>

				<div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">

					<div class="mb-3 mb-md-0 fw-semibold">

						<h4 class="text-gray-900 fw-bold"><?php echo esc_html( wds_lang( 'account_ref_wd_title' ) ); ?></h4>

						<div class="fs-6 text-gray-700 pe-7"><?php echo esc_html( wds_lang( 'account_ref_wd_subtitle' ) ); ?></div>

					</div>

					<a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#wd-form-modal"><?php echo esc_html( wds_lang( 'account_ref_wd_btn' ) ); ?></a>

				</div>

			</div>

		</div>

	</div>

	<?php if ( wds_data( 'data_coupons' ) ) : ?>

		<div class="card mt-5 mt-xl-10">

			<div class="card-header card-header-stretch">

				<div class="card-title">

					<h3><?php echo esc_html( wds_lang( 'account_ref_coupon_header' ) ); ?></h3>
					
				</div>

			</div>

			<div class="card-body py-0">

				<div class="table-responsive">

					<table class="table table-row-bordered align-middle gy-6">

						<thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">

							<tr>

								<th class="min-w-125px"><?php echo esc_html( wds_lang( 'account_ref_coupon_row1' ) ); ?></th>

								<th class="min-w-125px"><?php echo esc_html( wds_lang( 'account_ref_coupon_row2' ) ); ?></th>

								<th class="min-w-125px"><?php echo esc_html( wds_lang( 'account_ref_coupon_row3' ) ); ?></th>

							</tr>

						</thead>

						<tbody class="fs-6 fw-semibold text-gray-600">

							<?php foreach ( wds_data( 'data_coupons' ) as $coupon ) : ?>

								<tr>

									<td><?php echo esc_html( $coupon['code'] ); ?></td>

									<td><?php echo esc_html( $coupon['rebate'] ); ?></td>

									<td>
										<form action="" method="POST" class="d-flex coupon_form" data-form-id="<?php echo esc_attr( $coupon['id'] ); ?>">
											
											<input type="hidden" name="coupon_id" value="<?php echo esc_attr( $coupon['id'] ); ?>" />

											<input type="text" name="coupon_input" class="form-control me-3 flex-grow-1 min-w-175px" value="<?php echo esc_attr( $coupon['code_user'] ); ?>" required />

											<button class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0 coupon_submit"><?php echo esc_html( wds_lang( 'account_ref_coupon_btn' ) ); ?></button>

											<button class="btn btn-danger fw-bold flex-shrink-0 coupon_delete ms-2"><?php echo esc_html( wds_lang( 'account_ref_coupon_btn_delete' ) ); ?></button>

										</form>
									</td>

								</tr>

							<?php endforeach; ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	<?php endif; ?>

</div>

<div class="modal fade" id="wd-form-modal" tabindex="-1" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered mw-650px">

		<div class="modal-content rounded">

			<div class="modal-header pb-0 border-0 justify-content-end">

				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>

			</div>

			<div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">

				<form action="#" method="POST" id="wd_form" class="form">

					<div class="mb-13 text-center">

						<h1 class="mb-0"><?php echo esc_html( wds_lang( 'account_ref_wd_modal_title' ) ); ?></h1>

					</div>

					<div class="mb-10">

						<div class="mb-3">

							<div class="d-flex align-items-center fs-5 fw-semibold">
								<span class="required"><?php echo esc_html( wds_lang( 'select' ) . ' ' . wds_lang( 'payment_method' ) ); ?></span>
							</div>

						</div>

						<?php $method = wds_user_meta( get_current_user_id(), '_affiliate_payment_method' ); ?>

						<div class="fv-row">

							<div class="btn-group w-100" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">

								<label for="banktransfer" class="btn btn-outline btn-active-primary btn-color-muted<?php echo 'banktransfer' == $method ? ' active' : ''; ?>" data-kt-button="true">

									<input type="radio" name="method" id="banktransfer" class="btn-check" value="banktransfer" <?php echo 'banktransfer' == $method ? 'checked="checked"' : ''; ?> />

									<span>Bank Transfer</span>

								</label>

								<label for="paypal" class="btn btn-outline btn-active-primary btn-color-muted<?php echo 'paypal' == $method ? ' active' : ''; ?>" data-kt-button="true">

									<input type="radio" name="method" id="paypal" class="btn-check" value="paypal" <?php echo 'paypal' == $method ? 'checked="checked"' : ''; ?> />

									<span>Paypal</span>

								</label>

							</div>

						</div>

					</div>

					<div class="d-flex flex-column fv-row mb-8">

						<label for="account" class="fs-6 fw-semibold mb-2">
							<span class="required"><?php echo esc_html( wds_lang( 'payment_method_account' ) ); ?></span>
						</label>

						<textarea name="account" id="account" class="form-control form-control-solid" rows="3" placeholder="Bank Transfer eg: Bank Name | Jhone Doe | 1234567890 or Paypal eg: yourname@gmail.com"><?php echo esc_html( wds_user_meta( get_current_user_id(), '_affiliate_payment_method_account' ) ); ?></textarea>

					</div>

					<div class="text-center">

						<button type="submit" id="wd_submit" class="btn btn-primary">
							<span class="indicator-label"><?php echo esc_html( wds_lang( 'account_ref_wd_modal_btn' ) ); ?></span>
							<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>