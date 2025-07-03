<div class="wrap wds-wrap">

	<?php
	if ( isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['error'] ) ), 'warning inline' );
	} elseif ( isset( $_GET['success'] ) && ! empty( $_GET['success'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['success'] ) ), 'info inline' );
	}
	?>

	<div id="wds-page" class="order-page">

		<div class="wds-page-content">

			<div class="wds-content-wrap section-server">

				<h3>Order #<?php echo esc_html( $order->ID ); ?></h3>

				<div>

					<h4 style="margin: 20px 0 0;"><?php esc_html_e( 'Detail Customer', 'wds-notrans' ); ?></h4>

					<hr style="margin-bottom: 15px;">

					<div class="wds-table">

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Name', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( wds_user_name( $user_id ) ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Email', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( wds_user_email( $user_id ) ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Phone', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( wds_user_phone( $user_id ) ); ?></div>

						</div>

					</div>

					<h4 style="margin: 30px 0 0;"><?php esc_html_e( 'Transactions', 'wds-notrans' ); ?></h4>

					<hr style="margin-bottom: 15px;">

					<div class="wds-table">

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Order Code', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $order->code ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Created', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $order->created_at ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Expired', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $expired ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Renew Price', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $renew ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Status', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( wds_get_order_statuses( $order->status ) ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Product', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $product->title ); ?></div>

						</div>

						<div class="wds-row">

							<div class="wds-th"><?php esc_html_e( 'Affiliate', 'wds-notrans' ); ?>:</div>

							<div class="wds-td"><?php echo esc_html( $affiliate ); ?></div>

						</div>

					</div>

				</div>

				<h3 style="margin-top: 35px;"><?php esc_html_e( 'Invoices', 'wds-notrans' ); ?></h3>

				<div>

					<?php foreach ( $invoices as $invoice ) : ?>

						<?php
						if ( empty( $invoice->ID ) ) {
							continue;
						}

						$type = __( 'New Order', 'wds-notrans' );
						if ( 'renew_order' == $invoice->type ) {
							$type = __( 'Renew Order', 'wds-notrans' );
						}

						$price = wds_convert_money( $invoice->total );
						if ( 0 == $invoice->total ) {
							$price = __( 'Free', 'wds-notrans' );
						}
						?>

						<h4 style="margin: 20px 0 0;">Invoice #<?php echo esc_html( $invoice->ID ); ?></h4>

						<hr style="margin-bottom: 15px;">

						<div class="wds-table">

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Invoice Number', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $invoice->number ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Created', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $invoice->created_at ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Due Date', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $invoice->due_date_at ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Status', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( wds_get_invoice_statuses( $invoice->status ) ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Type', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $type ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Total', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $price ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Gateway', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( wds_get_gateway_label( $invoice->gateway ) ); ?></div>

							</div>

						</div>

					<?php endforeach; ?>

				</div>

				<?php if ( ! empty( $addon ) ) : ?>

					<h3 style="margin-top: 35px;"><?php esc_html_e( 'Addons', 'wds-notrans' ); ?></h3>

					<form method="post" action="" name="post">

						<?php wp_nonce_field( 'weddingsaas-order' ); ?>

						<input type="hidden" name="ID" value="<?php echo esc_attr( $order_id ); ?>" />

						<div class="wds-table">

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Addon', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><?php echo esc_html( $addon ); ?></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><?php esc_html_e( 'Addon Link', 'wds-notrans' ); ?>:</div>

								<div class="wds-td"><input type="text" name="link" value="<?php echo esc_attr( $addon_link ); ?>" style="width:80%;" /></div>

							</div>

							<div class="wds-row">

								<div class="wds-th"><button type="submit" class="button button-primary" style="width:100px;"><?php esc_html_e( 'Save', 'weddingsaas' ); ?></button></div>

							</div>

						</div>

					</form>

				<?php endif; ?>

			</div>

		</div>

	</div>

</div>