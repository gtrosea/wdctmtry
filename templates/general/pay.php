<?php $invoice = wds_data( 'invoice' ); ?>
<?php $order_id = wds_data( 'order_id' ); ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php do_action( 'wds_head' ); ?>
</head>
<body id="kt_body" class="print-content-only">

	<div class="d-flex flex-column flex-root">

		<div class="d-flex flex-column flex-center flex-column-fluid">

			<div class="d-flex flex-column flex-center d-flex flex-column flex-center p-10 w-100">

				<div class="content flex-column-fluid w-100 w-lg-1000px">

					<div class="card">

						<div class="card-body p-lg-20">

							<?php wds_dark_mode(); ?>

							<div class="d-flex flex-column flex-xl-row">

								<div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">

									<div class="mt-n1">

										<a href="<?php echo esc_url( home_url() ); ?>" class="d-block pb-10">
											<?php if ( wds_data( 'logo_light' ) && wds_data( 'logo_dark' ) ) : ?>
												<img src="<?php echo esc_url( wds_data( 'logo_light' ) ); ?>" class="w-200px theme-light-show" alt="Logo" />
												<img src="<?php echo esc_url( wds_data( 'logo_dark' ) ); ?>" class="w-200px theme-dark-show" alt="Logo" />
											<?php else : ?>
												<span class="fs-2qx lh-1 fw-bold"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
											<?php endif; ?>
										</a>

										<div class="m-0">

											<div class="fw-bold fs-3 text-gray-800 mb-8"><?php echo esc_html( wds_lang( 'invoice' ) ); ?> #<?php echo esc_html( $invoice->number ); ?></div>

											<div class="row g-5 mb-11">

												<div class="col-sm-6">

													<div class="fw-semibold fs-7 text-gray-600 mb-1"><?php echo esc_html( wds_lang( 'issue_date' ) ); ?>:</div>

													<?php $created_at = $invoice->created_at; ?>
													<?php $formatted_created_at = date_i18n( 'd F Y', strtotime( $created_at ) ); ?>
													<div class="fw-bold fs-6 text-gray-800"><?php echo esc_html( $formatted_created_at ); ?></div>

												</div>

												<div class="col-sm-6">

													<div class="fw-semibold fs-7 text-gray-600 mb-1"><?php echo esc_html( wds_lang( 'due_date' ) ); ?>:</div>

													<?php $due_date_at = $invoice->due_date_at; ?>
													<?php $formatted_due_date_at = date_i18n( 'd F Y', strtotime( $due_date_at ) ); ?>
													<div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
														<span class="pe-2"><?php echo esc_html( $formatted_due_date_at ); ?></span>
														<span class="fs-7 text-danger d-flex align-items-center">
															<span class="bullet bullet-dot bg-danger me-2"></span><?php echo esc_html( wds_lang( 'due_in' ) . ' ' . wds_option( 'invoice_due_date' ) . ' ' . wds_lang( 'day' ) ); ?></span>
													</div>

												</div>

											</div>

											<div class="row g-5 mb-12">

												<div class="col-sm-6">

													<div class="fw-semibold fs-7 text-gray-600 mb-1"><?php echo esc_html( wds_lang( 'issue_for' ) ); ?>:</div>

													<div class="fw-bold fs-6 text-gray-800"><?php echo esc_html( wds_user_name( $invoice->user_id ) ); ?></div>

													<div class="fw-semibold fs-7 text-gray-600"><?php echo esc_html( wds_user_email( $invoice->user_id ) ); ?></div>

												</div>

												<div class="col-sm-6">

													<div class="fw-semibold fs-7 text-gray-600 mb-1"><?php echo esc_html( wds_lang( 'issue_by' ) ); ?>:</div>

													<div class="fw-bold fs-6 text-gray-800"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>

													<div class="fw-semibold fs-7 text-gray-600"><?php echo esc_html( get_bloginfo( 'admin_email' ) ); ?></div>

												</div>

											</div>

											<div class="flex-grow-1">

												<div class="table-responsive border-bottom mb-9">

													<table class="table mb-3">

														<thead>

															<tr class="border-bottom fs-6 fw-bold text-muted">

																<th class="min-w-175px pb-2"><?php echo esc_html( wds_lang( 'product' ) ); ?></th>

																<th class="min-w-100px text-end pb-2"><?php echo esc_html( wds_lang( 'price' ) ); ?></th>

															</tr>

														</thead>

														<tbody>

															<tr class="fw-bold text-gray-700 fs-5 text-end">

																<td class="d-flex align-items-center pt-6">
																	<i class="ki-solid ki-chart text-primary fs-2 me-2"></i><?php echo esc_html( wds_invoice_summary( $invoice, 'product_title' ) ); ?>
																</td>

																<td class="pt-6 fw-bolder"><?php echo esc_html( wds_convert_money( wds_invoice_summary( $invoice, 'product_price' ) ) ); ?></td>

															</tr>

														</tbody>

													</table>

												</div>

												<div class="d-flex justify-content-end">

													<div class="mw-300px">

														<div class="d-flex flex-stack mb-3">

															<div class="fw-semibold pe-10 text-gray-600 fs-7"><?php echo esc_html( wds_lang( 'subtotal' ) ); ?></div>

															<div class="text-end fw-bold fs-6 text-gray-800"><?php echo esc_html( wds_convert_money( wds_invoice_summary( $invoice, 'subtotal' ) ) ); ?></div>

														</div>

														<?php if ( ! empty( wds_invoice_summary( $invoice, 'unique_number' ) ) ) : ?>

															<div class="d-flex flex-stack mb-3">

																<div class="fw-semibold pe-10 text-gray-600 fs-7"><?php echo esc_html( wds_option( 'unique_number_label' ) ); ?></div>

																<div class="text-end fw-bold fs-6 text-success"><?php echo esc_html( wds_option( 'unique_number_type' ) . ' ' . wds_convert_money( wds_invoice_summary( $invoice, 'unique_number' ) ) ); ?></div>

															</div>

														<?php endif; ?>

														<?php if ( ! empty( wds_invoice_summary( $invoice, 'discount' ) ) ) : ?>

															<div class="d-flex flex-stack mb-3">

																<div class="fw-semibold pe-10 text-gray-600 fs-7"><?php echo esc_html( wds_lang( 'discount' ) ); ?></div>

																<div class="text-end fw-bold fs-6 text-danger"><?php echo esc_html( '- ' . wds_convert_money( wds_invoice_summary( $invoice, 'discount' ) ) ); ?></div>

															</div>

														<?php endif; ?>

														<?php if ( ! empty( wds_invoice_summary( $invoice, 'addon_price' ) ) && wds_addon_fixed() ) : ?>

															<div class="d-flex flex-stack mb-3">

																<div class="fw-semibold pe-10 text-gray-600 fs-7"><?php echo esc_html( wds_lang( 'addon' ) ); ?></div>

																<div class="text-end fw-bold fs-6 text-info">+<?php echo esc_html( wds_convert_money( wds_invoice_summary( $invoice, 'addon_price' ) ) ); ?></div>

															</div>

														<?php endif; ?>

														<div class="d-flex flex-stack">

															<div class="fw-semibold pe-10 text-gray-600 fs-7"><?php echo esc_html( wds_lang( 'total' ) ); ?></div>

															<div class="text-end fw-bold fs-6 text-gray-800"><?php echo esc_html( wds_convert_money( wds_invoice_summary( $invoice, 'total' ) ) ); ?></div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

								<div class="m-0">

									<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">

										<div class="d-flex flex-stack mb-8">

											<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'status' ) ); ?>:</div>

											<?php
											if ( 'unpaid' == $invoice->status ) :
												echo '<span class="badge badge-light-warning">' . esc_html( wds_lang( 'pay_pending' ) ) . '</span>';
											elseif ( 'completed' == $invoice->status ) :
												echo '<span class="badge badge-light-success">' . esc_html( wds_lang( 'pay_success' ) ) . '</span>';
											elseif ( 'cancelled' == $invoice->status ) :
												echo '<span class="badge badge-light-danger">' . esc_html( wds_lang( 'pay_cancelled' ) ) . '</span>';
											else :
												echo '<span class="badge badge-light-info">' . esc_html( ucfirst( $invoice->status ) ) . '</span>';
											endif;
											?>

										</div>

										<?php $product_type = wds_get_order_meta( $order_id, 'product_type' ); ?>
										<?php $membership_type = wds_get_order_meta( $order_id, 'membership_type' ); ?>

										<?php if ( 'digital' != $product_type ) : ?>

											<h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary text-uppercase"><?php echo esc_html( wds_lang( 'trx_order_details' ) ); ?></h6>

											<div class="d-flex flex-stack mb-6">

												<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_membership_type' ) ); ?>:</div>

												<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( $membership_type ); ?></div>

											</div>

											<?php if ( 'addon' != $membership_type ) : ?>

												<div class="d-flex flex-stack mb-6">

													<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_user_active_period' ) ); ?>:</div>

													<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_order_meta( $order_id, 'membership_duration' ) ); ?></div>

												</div>

												<div class="d-flex flex-stack mb-6">

													<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_invitation_active_period' ) ); ?>:</div>

													<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_order_meta( $order_id, 'invitation_duration' ) ); ?></div>

												</div>

												<?php if ( 'reseller' != $membership_type ) : ?>

													<div class="d-flex flex-stack mb-6">

														<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?>:</div>

														<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_order_meta( $order_id, 'invitation_quota' ) ); ?></div>

													</div>

												<?php endif; ?>

											<?php endif; ?>

											<?php if ( 'addon' == $membership_type || 'reseller' == $membership_type ) : ?>

												<div class="d-flex flex-stack mb-6">

													<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?>:</div>

													<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_order_meta( $order_id, 'reseller_invitation_quota' ) ); ?></div>

												</div>

												<div class="d-flex flex-stack mb-6">

													<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'trx_client_quota' ) ); ?>:</div>

													<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_order_meta( $order_id, 'reseller_client_quota' ) ); ?></div>

												</div>

											<?php endif; ?>

											<?php $renew = wds_get_order_meta( $order_id, 'renew_price' ); ?>

											<?php if ( ! empty( $renew ) ) : ?>

												<div class="d-flex flex-stack mb-6">

													<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'renew_price' ) ); ?>:</div>

													<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_convert_money( $renew ) ); ?></div>

												</div>

											<?php endif; ?>

										<?php endif; ?>

										<?php $gateway = $invoice->gateway; ?>

										<h6 class="mt-12 mb-8 fw-bolder text-gray-600 text-hover-primary text-uppercase"><?php echo esc_html( wds_lang( 'payment_method' ) ); ?></h6>

										<div class="d-flex flex-stack mb-6">

											<div class="fw-semibold text-gray-600 fs-6"><?php echo esc_html( wds_lang( 'payment_method' ) ); ?>:</div>

											<div class="fw-bold text-gray-800 text-capitalize fs-6"><?php echo esc_html( wds_get_gateway_label( $gateway ) ); ?></div>

										</div>

										<div>

											<div class="fw-semibold text-gray-600 fs-6 mb-2"><?php echo esc_html( wds_lang( 'payment_instruction' ) ); ?>:</div>

											<div class="text-gray-800"><?php echo esc_html( wds_gateway( $gateway, 'instruction' ) ); ?></div>

										</div>

										<?php echo 'completed' == $invoice->status || 'cancelled' == $invoice->status ? '' : wds_gateway( $gateway, 'action' ); // phpcs:ignore ?>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="mt-8 text-center">

						<div class="text-dark"><?php echo wp_kses_post( wds_option( 'copyright' ) ); ?></div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<i class="ki-duotone ki-arrow-up">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>

	<?php do_action( 'wds_footer' ); ?>

</body>
</html>