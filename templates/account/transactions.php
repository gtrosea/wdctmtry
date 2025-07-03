<?php $data = wds_data( 'data_invoices' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html( wds_lang( 'transactions' ) ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php echo esc_html( wds_lang( 'account' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'transactions' ) ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<?php if ( wds_check_array( $data, true ) ) : ?>

		<div class="row g-5 g-xl-8">

			<?php
			foreach ( $data as $invoice ) :

				$gateway = $invoice->gateway;
				$gateway = empty( $gateway ) ? '-' : ( 'system' == $gateway ? ucfirst( $gateway ) : wds_get_gateway_label( $gateway ) );

				if ( 'cancelled' == $invoice->status ) {
					$stats = wds_lang( 'cancelled' );
				} elseif ( 'unpaid' == $invoice->status ) {
					$stats = wds_lang( 'unpaid' );
				} else {
					$stats = wds_lang( 'completed' );
				}
				?>

				<div class="col-md-6 col-lg-12 col-xl-6 col-xxl-4">

					<div class="card h-100">

						<div class="card-header ribbon ribbon-end ribbon-clip">
							<div class="ribbon-label"><?php echo esc_html( $stats ); ?><span class="ribbon-inner bg-<?php echo esc_attr( $invoice->color ); ?>"></span></div>
							<div class="card-title"><a href="<?php echo esc_url( $invoice->link ); ?>" target="_blank">#<?php echo esc_html( $invoice->number ); ?></a></div>
						</div>

						<div class="card-body">

							<div class="d-flex flex-stack">

								<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'issue_date' ) ); ?> :</div>

								<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( $invoice->created ); ?></div>

							</div>

							<div class="separator separator-dashed my-3"></div>

							<div class="d-flex flex-stack">

								<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'due_date' ) ); ?> :</div>

								<div class="text-gray-900 fw-bolder fs-6"><?php echo 'completed' == $invoice->status ? '-' : esc_html( $invoice->duedate ); ?></div>

							</div>

							<div class="separator separator-dashed my-3"></div>

							<div class="d-flex flex-stack">

								<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'product' ) ); ?> :</div>

								<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( $invoice->product ); ?></div>

							</div>

							<div class="separator separator-dashed my-3"></div>

							<div class="d-flex flex-stack">

								<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'total' ) ); ?> :</div>

								<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( $invoice->price ); ?></div>

							</div>

							<div class="separator separator-dashed my-3"></div>

							<div class="d-flex flex-stack">

								<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'payment_method' ) ); ?> :</div>

								<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( $gateway ); ?></div>

							</div>

							<?php
							if ( 'completed' == $invoice->status ) :
								$addons     = wds_get_order_meta( $invoice->order_id, 'addons' );
								$addon_link = wds_get_order_meta( $invoice->order_id, 'addon_link' );
								if ( ! empty( $addons ) ) :
									$addons = is_array( $addons ) ? implode( ', ', $addons ) : $addons;
									?>
									<div class="separator separator-dashed my-3"></div>

									<div class="d-flex flex-stack">

										<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'addon' ) ); ?> :</div>

										<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( $addons ); ?></div>

									</div>
									<?php
								endif;
								if ( ! empty( $addon_link ) ) :
									?>
									<a href="<?php echo esc_url( $addon_link ); ?>" target="_blank" class="btn btn-primary mt-8 text-uppercase w-100"><?php echo esc_html( wds_lang( 'download' ) ); ?></a>
								<?php endif; ?>
							<?php else : ?>
								<a href="<?php echo esc_url( $invoice->link ); ?>" target="_blank" class="btn btn-success mt-8 text-uppercase w-100"><?php echo esc_html( wds_lang( 'pay_now' ) ); ?></a>
							<?php endif; ?>

						</div>

					</div>

				</div>

			<?php endforeach; ?>

		</div>

	<?php else : ?>

		<div class="alert bg-light-danger d-flex flex-center border-danger flex-column w-md-500px py-10 px-10 px-lg-20 m-0 me-md-auto ms-md-0">

			<i class="ki-duotone ki-information-5 fs-5tx text-danger mb-5">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
			</i>

			<div class="text-center">

				<h1 class="fw-bold mb-5"><?php echo esc_html( wds_lang( 'account_trx_empty_title' ) ); ?></h1>

				<div class="separator separator-dashed border-danger opacity-25 mb-5"></div>

				<span><?php echo wp_kses_post( wds_lang( 'account_trx_empty_subtitle' ) ); ?></span>

			</div>

		</div>

	<?php endif; ?>

</div>