<?php
$data         = wds_data( 'data' );
$free         = wds_sanitize_data_field( $data, 'free' );
$product_id   = wds_sanitize_data_field( $data, 'product_id' );
$checkout_id  = wds_sanitize_data_field( $data, 'checkout_id' );
$selected     = wds_data( 'gateway' );
$gateways     = wds_data( 'gateways' );
$membership   = wds_data( 'membership_type' );
$addon_meta   = wds_data( 'addon' );
$addon_data   = wds_addon_data();
$hide_gateway = wds_option( 'gateway_hide' ) ? 'd-none' : '';

if ( 'trial' == $membership ) {
	$sk_content = wds_option( 'sk_content' );
} elseif ( 'member' == $membership ) {
	$sk_content = wds_option( 'sk_content_member' );
} else {
	$sk_content = wds_option( 'sk_content_reseller' );
}
?>

<h1 class="fw-bolder text-gray-900 text-uppercase text-center fs-3x mb-10"><?php echo esc_html( wds_lang( 'trx_checkout_title' ) ); ?></h1>

<form action="" method="POST" id="checkout_form" class="form w-100 mb-n8 mb-lg-n10" novalidate="novalidate" data-id="<?php echo esc_attr( $checkout_id ); ?>" data-type="membership">

	<div class="product">

		<h2 class="fs-2 mb-5"><?php echo esc_html( wds_lang( 'trx_order_details' ) ); ?></h2>

		<div class="d-flex flex-stack">

			<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'product' ) ); ?></div>

			<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_get_product_title( $product_id ) ); ?></div>

		</div>

		<div class="separator separator-dashed my-3"></div>

		<div class="d-flex flex-stack">

			<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'price' ) ); ?></div>

			<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_price' ) ); ?><span class="text-gray-400 fw-bold fs-6"><?php echo esc_html( wds_data( 'product_payment_type' ) ); ?></div>

		</div>

		<?php if ( wds_data( 'renew_price_data' ) ) : ?>

			<div class="separator separator-dashed my-3"></div>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'renew_price' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'renew_price' ) ); ?></div>

			</div>

		<?php endif; ?>

		<div class="separator separator-dashed my-3"></div>

		<div class="d-flex flex-stack">

			<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_membership_type' ) ); ?></div>

			<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_membership_type' ) ); ?></div>

		</div>

		<div class="separator separator-dashed my-3"></div>

		<?php if ( 'addon' != $membership ) : ?>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_user_active_period' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_membership_lifetime' ) ); ?></div>

			</div>

			<div class="separator separator-dashed my-3"></div>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_invitation_active_period' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_invitation_lifetime' ) ); ?></div>

			</div>

			<div class="separator separator-dashed my-3"></div>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_membership_quota' ) ); ?></div>

			</div>

			<?php if ( 'reseller' == $membership ) : ?>

				<div class="separator separator-dashed my-3"></div>

				<div class="d-flex flex-stack">

					<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_client_quota' ) ); ?></div>

					<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_client_quota' ) ); ?></div>

				</div>

			<?php endif; ?>

		<?php else : ?>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_invitation_quota' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_membership_quota' ) ); ?></div>

			</div>

			<div class="separator separator-dashed my-3"></div>

			<div class="d-flex flex-stack">

				<div class="text-gray-700 fw-semibold fs-6 me-2"><?php echo esc_html( wds_lang( 'trx_client_quota' ) ); ?></div>

				<div class="text-gray-900 fw-bolder fs-6"><?php echo esc_html( wds_data( 'product_client_quota' ) ); ?></div>

			</div>

		<?php endif; ?>

	</div>

	<?php if ( wds_check_array( $addon_meta, true ) && wds_check_array( $addon_data, true ) ) : ?>

		<div class="addon mt-12">

			<h2 class="fs-2 mb-5"><?php echo esc_html( wds_lang( 'addon' ) ); ?></h2>

			<?php foreach ( $addon_data as $addon ) : ?>

				<?php if ( in_array( $addon['id'], $addon_meta ) ) : ?>

					<div class="mt-4">

						<div class="form-check form-check-custom form-check-solid form-check-sm" data-id="<?php echo esc_attr( $addon['id'] ); ?>" data-title="<?php echo esc_attr( $addon['title'] ); ?>" data-price="<?php echo esc_attr( $addon['price'] ); ?>">

							<input type="checkbox" id="addon-<?php echo esc_attr( $addon['id'] ); ?>" class="form-check-input cursor-pointer" />

							<label for="addon-<?php echo esc_attr( $addon['id'] ); ?>" class="form-check-label cursor-pointer"><?php echo wp_kses_post( $addon['title'] ); ?> (<?php echo esc_html( wds_convert_money( $addon['price'] ) ); ?>) <?php echo $addon['link'] ? '(<a href="' . esc_url( $addon['link'] ) . '" target="_blank">' . esc_html( wds_lang( 'readmore' ) ) . '</a>)' : ''; ?></label>

						</div>

					</div>

				<?php endif; ?>

			<?php endforeach; ?>

		</div>

	<?php endif; ?>

	<?php require_once wds_get_template( 'partials/auth.php' ); ?>

	<?php if ( ! $free || 'trial' != $membership ) : ?>

		<div class="payment-method <?php echo esc_html( $hide_gateway ); ?>">

			<h2 class="fs-2 mb-0"><?php echo esc_html( wds_lang( 'payment_method' ) ); ?></h2>

			<?php if ( empty( $gateways ) ) : ?>

				<div class="alert alert-warning mt-5 mb-0"><?php echo esc_html( wds_lang( 'trx_gateway_empty' ) ); ?></div>

			<?php else : ?>

				<div class="row">

					<?php foreach ( $gateways as $key => $gateway ) : ?>

						<div class="col-md-6 mt-5" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo esc_attr( wds_gateway( $key, 'title' ) ); ?>">

							<label class="btn btn-transparant btn-outline btn-outline-dashed btn-active d-flex flex-stack p-6<?php echo ( $selected == $key ) ? ' btn-outline-primary' : ''; ?>">

								<div class="d-flex align-items-center me-2">

									<div class="form-check form-check-custom form-check-solid form-check-primary flex-shrink-0 me-6">
										<input type="radio" name="gateway" class="form-check-input" value="<?php echo esc_attr( $key ); ?>" <?php echo ( $selected == $key ) ? 'checked' : ''; ?> />
									</div>

									<?php if ( wds_gateway( $key, 'icon_enable' ) ) : ?>
										<img src="<?php echo esc_url( wds_gateway( $key, 'icon' ) ); ?>" class="h-40px" />
									<?php else : ?>
										<div class="fs-5 fw-bold"><?php echo esc_html( wds_get_gateway_label( $key ) ); ?></div>
									<?php endif; ?>

								</div>

							</label>

						</div>

					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>

		<div class="coupon my-12">

			<h2 class="fs-2 mb-5"><?php echo esc_html( wds_lang( 'coupon' ) ); ?></h2>

			<div class="d-flex">

				<input type="text" name="coupon" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_attr( wds_data( 'coupon_code' ) ); ?>" />

				<div class="d-grid">

					<button type="button" id="apply_coupon" class="btn btn-light-primary fw-bold flex-shrink-0">
						<span class="indicator-label"><?php echo esc_html( wds_lang( 'apply' ) ); ?></span>
						<span class="indicator-progress"><span class="spinner-border spinner-border-sm align-middle"></span></span>
					</button>

				</div>

			</div>

		</div>

	<?php endif; ?>

	<?php require_once wds_get_template( 'partials/sk.php' ); ?>

	<div class="summary mb-5">

		<h2 class="fs-2 mb-5"><?php echo esc_html( wds_lang( 'summary' ) ); ?></h2>

		<div class="notice bg-light-primary rounded border-primary border border-dashed p-6">

			<div id="sum-placeholder">

				<div class="placeholder-glow w-100 mb-3"><span class="placeholder w-100"></span></div>

				<div class="placeholder-glow w-100 mb-3"><span class="placeholder w-100"></span></div>

				<div class="placeholder-glow w-100"><span class="placeholder w-100"></span></div>

			</div>

			<div id="sum-data"></div>

		</div>

	</div>

	<div class="d-grid">

		<button type="submit" id="checkout_btn" class="btn btn-primary">
			<span class="indicator-label"><?php echo esc_html( wds_lang( 'trx_create_order' ) ); ?></span>
			<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
				<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
			</span>
		</button>

	</div>

	<div id="wds-referred"></div>

</form>