<?php $data = wds_data( 'data_upgrade' ); ?>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card">

		<div class="card-body p-lg-17">

			<div class="d-flex flex-column">

				<div class="mb-13 text-center">

					<h1 class="fs-2hx fw-bold mb-5"><?php echo esc_html( wds_lang( 'dash_upgrade' ) ); ?></h1>

					<div class="text-gray-600 fw-semibold fs-5"><?php echo isset( $_GET['type'] ) ? esc_html( wds_lang( 'dash_upgrade_description_topup' ) ) : esc_html( wds_lang( 'dash_upgrade_description' ) ); ?></div>

				</div>

				<?php if ( wds_check_array( $data, true ) ) : ?>

					<div class="row g-10">

						<?php
						foreach ( $data as $item ) :
							$product_id           = $item->ID;
							$title                = $item->title;
							$price                = wds_convert_money( $item->price );
							$membership_data      = $item->membership_data;
							$membership_type      = $item->membership_type;
							$payment_type         = $item->payment_type;
							$membership_period    = $item->membership_period;
							$invitation_period    = $item->invitation_period;
							$invitation_quota     = $item->invitation_quota;
							$res_invitation_quota = intval( $item->res_invitation_quota );
							$res_client_quota     = intval( $item->res_client_quota );
							$price_one            = 0;
							if ( ( $res_invitation_quota + $res_client_quota ) != 0 ) {
								$price_one = wds_convert_money( $item->price / ( $res_invitation_quota + $res_client_quota ) );
							}
							$checkout_link = $item->checkout_link;
							?>

							<div class="col-xl-4 col-md-6">

								<div class="d-flex h-100 align-items-center">

									<div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">

										<div class="mb-7 text-center">

											<h1 class="text-dark mb-5 fw-bolder"><?php echo esc_html( $title ); ?></h1>

											<div class="text-center">
												<span class="fs-1 fw-bold text-primary"><?php echo esc_html( $price ); ?></span>
												<span class="fs-7 fw-semibold opacity-50">/<span><?php echo esc_html( $payment_type ); ?></span></span>
											</div>

										</div>

										<div class="w-100 mb-10">

											<?php if ( 'member' == $membership_type || 'reseller' == $membership_type ) : ?>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_user_active_period' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $membership_period ); ?></span>
												</div>

												<div class="separator separator-dashed mb-3"></div>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_invitation_active_period' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $invitation_period ); ?></span>
												</div>

												<div class="separator separator-dashed mb-3"></div>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo 'reseller' == $membership_type ? esc_html( $res_invitation_quota ) : esc_html( $invitation_quota ); ?></span>
												</div>

												<?php if ( 'reseller' == $membership_type ) : ?>

													<div class="separator separator-dashed mb-3"></div>

													<div class="d-flex align-items-center mb-3">
														<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_client_quota' ) ); ?></span>
														<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $res_client_quota ); ?></span>
													</div>

													<div class="separator separator-dashed mb-3"></div>

													<div class="d-flex align-items-center mb-3">
														<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'dash_upgrade_price_one_invitation' ) ); ?></span>
														<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $price_one ); ?></span>
													</div>

												<?php endif; ?>

											<?php else : ?>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $res_invitation_quota ); ?></span>
												</div>

												<div class="separator separator-dashed mb-3"></div>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'trx_client_quota' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $res_client_quota ); ?></span>
												</div>

												<div class="separator separator-dashed mb-3"></div>

												<div class="d-flex align-items-center mb-3">
													<span class="fw-semibold fs-6 text-gray-800 flex-grow-1"><?php echo esc_html( wds_lang( 'dash_upgrade_price_one_invitation' ) ); ?></span>
													<span class="fw-semibold fs-6 text-gray-800"><?php echo esc_html( $price_one ); ?></span>
												</div>

											<?php endif; ?>

										</div>

										<a href="<?php echo esc_url( $checkout_link ); ?>" class="btn btn-sm btn-primary"><?php echo esc_html( wds_lang( 'dash_upgrade_now' ) ); ?></a>

									</div>

								</div>

							</div>

						<?php endforeach; ?>

					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>

</div>