<div class="wrap">

	<?php
	if ( isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['error'] ) ), 'warning inline' );
	} elseif ( isset( $_GET['success'] ) && ! empty( $_GET['success'] ) ) {
		wds_add_notice( urldecode( wds_sanitize_text_field( $_GET['success'] ) ), 'info inline' );
	}
	?>

	<style>.csf-onload .csf-repeater-helper{display:none!important}</style>

	<form method="post" action="" name="post">

		<?php wp_nonce_field( 'weddingsaas-product' ); ?>

		<input type="hidden" name="ID" value="<?php echo esc_attr( $product_id ); ?>">

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content" style="position: relative;">

					<div id="titlediv">

						<div id="titlewrap">

							<input type="text" name="title" id="title" size="30" value="<?php echo esc_attr( wds_sanitize_text_field( $title ) ); ?>" spellcheck="true" autocomplete="off" placeholder="<?php esc_html_e( 'Product name', 'wds-notrans' ); ?>" required />

						</div>

					</div>

					<div class="inside">

						<div id="edit-slug-box" class="hide-if-no-js">
							<strong><?php esc_html_e( 'Permalink', 'wds-notrans' ); ?>:</strong>
							<span id="sample-permalink">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_url( $site_url_checkout ); ?><span id="editable-post-name"><?php echo esc_html( $slug ); ?></span>/</a>
							</span>
							&lrm;
							<span id="edit-slug-buttons">
								<button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="Edit permalink"><?php esc_html_e( 'Edit', 'wds-notrans' ); ?></button>
							</span>
							<span id="editable-post-name-full" style="display: none;"><?php echo esc_html( $slug ); ?></span>
						</div>

						<div id="slug-input-box" class="hide-if-no-js" style="display: none;">
							<strong><?php esc_html_e( 'Permalink', 'wds-notrans' ); ?>:</strong>
							<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_url( $site_url_checkout ); ?></a>
							<input type="text" name="slug" id="slug-input" value="<?php echo esc_attr( $slug ); ?>">
							<button type="button" class="save-slug button button-small hide-if-no-js" aria-label="Save slug"><?php esc_html_e( 'Ok', 'wds-notrans' ); ?></button>
							<button type="button" class="cancel-slug button button-small hide-if-no-js" aria-label="Cancel slug"><?php esc_html_e( 'Cancel', 'wds-notrans' ); ?></button>
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

											<div class="misc-pub-section csf-onload">

												<?php
												CSF::field(
													array(
														'id'      => 'status',
														'type'    => 'select',
														'options' => wds_get_product_statuses(),
													),
													$status,
												);
												?>

											</div>

											<div class="clear"></div>

										</div>

										<div class="clear"></div>

									</div>

									<div id="major-publishing-actions">

										<div id="publishing-action">

											<span class="spinner"></span>

											<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Save Product', 'wds-notrans' ); ?></button>

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

						<div class="postbox ">

							<div class="postbox-header">

								<h2 class="hndle ui-sortable-handle"><?php esc_html_e( 'Product Setup', 'wds-notrans' ); ?></h2>

							</div>

							<div class="csf-onload">

								<?php
								$type = array( 'membership' => __( 'Membership', 'wds-notrans' ) );
								if ( wds_is_digital() ) {
									$type = array_merge( $type, array( 'digital' => __( 'Digital Product', 'wds-notrans' ) ) );
								}

								$is_digital = array( 'product_type', '==', 'digital' );
								$no_digital = array( 'product_type', '!=', 'digital' );
								CSF::field(
									array(
										'id'      => 'product_type',
										'type'    => 'select',
										'title'   => __( 'Product Type', 'wds-notrans' ),
										'options' => $type,
									),
									wds_get_product_meta( $product_id, 'product_type' ),
								);

								// digital product
								CSF::field(
									array(
										'type'       => 'notice',
										'style'      => 'info',
										'content'    => __( 'Untuk akses download, silahkan atur di "post type <b>WDS Access</b>".', 'weddingsaas' ),
										'dependency' => array( $is_digital ),
									),
								);

								$no_trial    = array( 'membership_type', '!=', 'trial' );
								$is_addon    = array( 'membership_type', '==', 'addon' );
								$no_addon    = array( 'membership_type', '!=', 'addon' );
								$is_reseller = array( 'membership_type', 'any', 'reseller,addon' );
								$no_reseller = array( 'membership_type', 'not-any', 'reseller,addon' );
								CSF::field(
									array(
										'id'         => 'membership_type',
										'type'       => 'select',
										'title'      => __( 'Membership Type', 'wds-notrans' ),
										'options'    => array(
											'trial'    => __( 'Trial', 'wds-notrans' ),
											'member'   => __( 'Member', 'wds-notrans' ),
											'reseller' => __( 'Reseller', 'wds-notrans' ),
											'addon'    => __( 'Top Up Kuota', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital ),
									),
									wds_get_product_meta( $product_id, 'membership_type' ),
								);

								CSF::field(
									array(
										'type'       => 'subheading',
										'title'      => __( 'Membership', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_addon ),
									),
								);

								$m_no_lifetime = array( 'membership_lifetime', '==', 'no' );
								CSF::field(
									array(
										'id'         => 'membership_lifetime',
										'type'       => 'select',
										'title'      => __( 'Membership Lifetime?', 'wds-notrans' ),
										'options'    => array(
											'yes' => __( 'Yes', 'wds-notrans' ),
											'no'  => __( 'No', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital, $no_addon ),
									),
									wds_get_product_meta( $product_id, 'membership_lifetime' ),
								);

								CSF::field(
									array(
										'id'         => 'membership_duration',
										'type'       => 'number',
										'title'      => __( 'Membership Duration', 'wds-notrans' ),
										'attributes' => array( 'min' => '1' ),
										'dependency' => array( $no_digital, $no_addon, $m_no_lifetime ),
									),
									wds_get_product_meta( $product_id, 'membership_duration' ),
								);

								CSF::field(
									array(
										'id'         => 'membership_period',
										'type'       => 'select',
										'title'      => __( 'Membership Period', 'wds-notrans' ),
										'options'    => array(
											'day'   => __( 'Day', 'wds-notrans' ),
											'month' => __( 'Month', 'wds-notrans' ),
											'year'  => __( 'Year', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital, $no_addon, $m_no_lifetime ),
									),
									wds_get_product_meta( $product_id, 'membership_period' ),
								);

								CSF::field(
									array(
										'type'       => 'subheading',
										'title'      => __( 'Invitation', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_addon ),
									),
								);

								$inv_no_lifetime = array( 'invitation_lifetime', '==', 'no' );
								CSF::field(
									array(
										'id'         => 'invitation_lifetime',
										'type'       => 'select',
										'title'      => __( 'Invitation Lifetime?', 'wds-notrans' ),
										'options'    => array(
											'yes' => __( 'Yes', 'wds-notrans' ),
											'no'  => __( 'No', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital, $no_addon ),
									),
									wds_get_product_meta( $product_id, 'invitation_lifetime' ),
								);

								CSF::field(
									array(
										'id'         => 'invitation_duration',
										'type'       => 'number',
										'attributes' => array( 'min' => '1' ),
										'title'      => __( 'Invitation Duration', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_addon, $inv_no_lifetime ),
									),
									wds_get_product_meta( $product_id, 'invitation_duration' ),
								);

								CSF::field(
									array(
										'id'         => 'invitation_period',
										'type'       => 'select',
										'title'      => __( 'Invitation Period', 'wds-notrans' ),
										'options'    => array(
											'day'   => __( 'Day', 'wds-notrans' ),
											'month' => __( 'Month', 'wds-notrans' ),
											'year'  => __( 'Year', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital, $no_addon, $inv_no_lifetime ),
									),
									wds_get_product_meta( $product_id, 'invitation_period' ),
								);

								CSF::field(
									array(
										'id'         => 'invitation_quota',
										'type'       => 'number',
										'attributes' => array( 'min' => '1' ),
										'title'      => __( 'Invitation Quota', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_reseller ),
									),
									wds_get_product_meta( $product_id, 'invitation_quota' ),
								);

								CSF::field(
									array(
										'id'         => 'invitation_status',
										'type'       => 'select',
										'title'      => __( 'Invitation Status', 'wds-notrans' ),
										'options'    => array(
											'draft' => __( 'Draft', 'wds-notrans' ),
											'trash' => __( 'Trash', 'wds-notrans' ),
										),
										'dependency' => array( $no_digital, $no_reseller ),
									),
									wds_get_product_meta( $product_id, 'invitation_status' ),
								);

								CSF::field(
									array(
										'type'       => 'subheading',
										'title'      => __( 'Reseller', 'wds-notrans' ),
										'dependency' => array( $no_digital, $is_reseller ),
									),
								);

								CSF::field(
									array(
										'id'         => 'reseller_invitation_quota',
										'type'       => 'number',
										'attributes' => array( 'min' => '1' ),
										'title'      => __( 'Invitation Quota', 'wds-notrans' ),
										'desc'       => __( 'Quota for creating digital invitations.', 'wds-notrans' ),
										'dependency' => array( $no_digital, $is_reseller ),
									),
									wds_get_product_meta( $product_id, 'reseller_invitation_quota' ),
								);

								CSF::field(
									array(
										'id'         => 'reseller_client_quota',
										'type'       => 'number',
										'attributes' => array( 'min' => '0' ),
										'title'      => __( 'Client Quota', 'wds-notrans' ),
										'desc'       => __( 'Quota for registering clients on the website.', 'wds-notrans' ),
										'dependency' => array( $no_digital, $is_reseller ),
									),
									wds_get_product_meta( $product_id, 'reseller_client_quota' ),
								);

								CSF::field(
									array(
										'type'       => 'subheading',
										'title'      => __( 'Restrict Product', 'wds-notrans' ),
										'dependency' => array( $no_digital, $is_addon ),
									),
								);

								$restrict_product = wds_get_product_meta( $product_id, 'restrict_product' );
								CSF::field(
									array(
										'id'          => 'restrict_product',
										'type'        => 'checkbox',
										'title'       => __( 'Product', 'wds-notrans' ),
										'desc'        => __( 'If not selected, this product will display to all reseller.', 'wds-notrans' ),
										'placeholder' => __( 'Select an product', 'wds-notrans' ),
										'options'     => wds_get_product_restrict( 'reseller' ),
										'dependency'  => array( $no_digital, $is_addon ),
									),
									! empty( $restrict_product ) ? $restrict_product : array()
								);

								CSF::field(
									array(
										'type'  => 'subheading',
										'title' => __( 'Price', 'wds-notrans' ),
									),
								);

								CSF::field(
									array(
										'id'         => 'regular_price',
										'type'       => 'number',
										'title'      => __( 'Normal Price', 'wds-notrans' ),
										'desc'       => __( 'Leave blank if is free product.', 'wds-notrans' ),
										'attributes' => array( 'min' => '0' ),
									),
									wds_get_product_meta( $product_id, 'regular_price' ),
								);

								CSF::field(
									array(
										'id'         => 'renew_price',
										'type'       => 'number',
										'attributes' => array( 'min' => '0' ),
										'title'      => __( 'Renew Price', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_addon, $m_no_lifetime ),
									),
									wds_get_product_meta( $product_id, 'renew_price' ),
								);

								CSF::field(
									array(
										'type'       => 'subheading',
										'title'      => __( 'Addon Product', 'wds-notrans' ),
										'dependency' => array( $no_digital, $no_trial, $no_addon ),
									),
								);

								$addon_product = wds_get_product_meta( $product_id, 'addon' );
								CSF::field(
									array(
										'id'          => 'addon',
										'type'        => 'checkbox',
										'title'       => __( 'Product', 'wds-notrans' ),
										'placeholder' => __( 'Select an addon', 'wds-notrans' ),
										'options'     => wds_get_product_addon(),
										'dependency'  => array( $no_digital, $no_trial, $no_addon ),
									),
									! empty( $addon_product ) ? $addon_product : array()
								);

								CSF::field(
									array(
										'type'  => 'subheading',
										'title' => __( 'Affiliate', 'wds-notrans' ),
									),
								);

								CSF::field(
									array(
										'id'    => 'affiliate',
										'type'  => 'switcher',
										'title' => __( 'Affiliate', 'wds-notrans' ),
									),
									$affiliate,
								);

								CSF::field(
									array(
										'id'         => 'affiliate_commission',
										'type'       => 'text',
										'title'      => __( 'Affiliate Commision', 'wds-notrans' ),
										'desc'       => __( 'If fixed commissions put full integer. If percentage commission put integers with percentage symbol. <br>eg 50 for $50 commission or 50% for 50 percent commission.', 'wds-notrans' ),
										'dependency' => array( 'affiliate', '==', 'true' ),
									),
									wds_get_product_meta( $product_id, 'affiliate_commission' ),
								);

								CSF::field(
									array(
										'id'         => 'salespage_url',
										'type'       => 'text',
										'title'      => __( 'Salespage URL', 'wds-notrans' ),
										'desc'       => __( 'This salespage for redirect affiliate url.', 'wds-notrans' ),
										'dependency' => array( 'affiliate', '==', 'true' ),
									),
									wds_get_product_meta( $product_id, 'salespage_url' ),
								);
								?>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="clear"></div>

		</div>

	</form>

</div>

<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			var editSlugButton = $('.edit-slug');
			var slugBox = $('#edit-slug-box');
			var slugInputBox = $('#slug-input-box');
			var slugInput = $('#slug-input');
			var saveSlugButton = $('.save-slug');
			var cancelSlugButton = $('.cancel-slug');

			editSlugButton.on('click', function() {
				slugBox.hide();
				slugInput.val($('#editable-post-name').text());
				slugInputBox.show();
			});

			saveSlugButton.on('click', function() {
				var newSlug = slugInput.val();
				$('#editable-post-name').text(newSlug);
				slugInputBox.hide();
				slugBox.show();
			});

			cancelSlugButton.on('click', function() {
				slugInputBox.hide();
				slugBox.show();
			});
		});
	})(jQuery);
</script>