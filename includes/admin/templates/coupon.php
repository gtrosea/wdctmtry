<div class="wrap">

	<?php
	if ( isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['error'] ) ), 'warning inline' );
	} elseif ( isset( $_GET['success'] ) && ! empty( $_GET['success'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['success'] ) ), 'info inline' );
	}
	?>

	<form method="post" action="" name="post">

		<?php wp_nonce_field( 'weddingsaas-coupon' ); ?>

		<input type="hidden" name="ID" value="<?php echo esc_attr( $coupon_id ); ?>">

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content" style="position: relative;">

					<div id="titlediv">

						<div id="titlewrap">

							<input type="text" name="title" id="title" size="30" value="<?php echo esc_attr( wds_sanitize_text_field( $title ) ); ?>" spellcheck="true" autocomplete="off" placeholder="<?php esc_html_e( 'Coupon name', 'wds-notrans' ); ?>" required />

						</div>

					</div>

				</div>

				<div id="postbox-container-1" class="postbox-container">

					<div id="side-sortables" class="meta-box-sortables ui-sortable">

						<div id="submitdiv" class="postbox">

							<div class="postbox-header">

								<h2 class="hndle ui-sortable-handle"><?php esc_html_e( 'Publish', 'wds-notrans' ); ?></h2>

							</div>

							<div class="inside">

								<div id="submitpost" class="submitbox">

									<div id="minor-publishing">

										<div class="misc-publishing-actions">

											<div class="misc-pub-section">

												<div class="misc-pub-section csf-onload">

													<?php
													CSF::field(
														array(
															'id'      => 'status',
															'type'    => 'select',
															'options' => wds_get_coupon_statuses(),
														),
														$status,
													);
													?>

												</div>

											</div>

											<div class="clear"></div>

										</div>

										<div class="clear"></div>

									</div>

									<div id="major-publishing-actions">

										<div id="publishing-action">

											<span class="spinner"></span>

											<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Save Coupon', 'wds-notrans' ); ?></button>

										</div>

										<div class="clear"></div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div id="postbox-container-2" class="postbox-container">

					<div id="normal-sortables" class="ui-sortable">

						<div class="postbox">

							<div class="postbox-header">

								<h2 class="hndle ui-sortable-handle"><?php esc_html_e( 'Coupon Settings', 'wds-notrans' ); ?></h2>

							</div>

							<div class="csf-onload">

								<?php
								CSF::field(
									array(
										'id'    => 'code',
										'type'  => 'text',
										'title' => __( 'General Coupon Code', 'wds-notrans' ),
										'desc'  => __( 'The general coupon code.', 'wds-notrans' ),
									),
									wds_get_coupon_code( $coupon_id ),
								);

								CSF::field(
									array(
										'id'    => 'rebate',
										'type'  => 'text',
										'title' => __( 'Rebate Amount', 'wds-notrans' ),
										'desc'  => __( 'The amount of the discounted price, type fixed discounted without money simbol or percentage discount example: 50% or 50.', 'wds-notrans' ),
									),
									$rebate,
								);

								CSF::field(
									array(
										'id'    => 'is_private',
										'type'  => 'switcher',
										'title' => __( 'Private Coupon', 'wds-notrans' ),
										'desc'  => __( 'If on, coupon not visible for all affiliate.', 'wds-notrans' ),
									),
									$is_private,
								);

								CSF::field(
									array(
										'id'         => 'max_usage',
										'type'       => 'number',
										'title'      => __( 'Max Usage', 'wds-notrans' ),
										'desc'       => __( 'Maximum coupon usage, leave empty or 0 to unlimited usage.', 'wds-notrans' ),
										'attributes' => array( 'min' => '0' ),
									),
									$max_usage,
								);

								CSF::field(
									array(
										'id'          => 'products',
										'type'        => 'checkbox',
										'title'       => __( 'Product Include', 'wds-notrans' ),
										'desc'        => __( 'Coupon only work for this spesifict product, empty for use all product.', 'wds-notrans' ),
										'placeholder' => __( 'Select an product', 'wds-notrans' ),
										'options'     => $product_data,
									),
									$is_products
								);
								?>

							</div>

						</div>

						<div class="postbox ">

							<div class="postbox-header">

								<h2 class="hndle ui-sortable-handle"><?php esc_html_e( 'Coupon Code', 'wds-notrans' ); ?></h2>

							</div>

							<div class="csf-onload">

								<div class="csf-field">

									<?php $list_table->prepare_items(); ?>

									<form method="get" action="">

										<?php $list_table->views(); ?>

										<?php $list_table->display(); ?>

									</form>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="clear"></div>

		</div>

	</form>

</div>