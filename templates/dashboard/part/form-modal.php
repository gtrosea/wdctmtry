<div class="modal fade" id="modal_add_invitation" tabindex="-1" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered mw-650px">

		<div class="modal-content">

			<form action="" method="POST" id="invitation_form" class="form" novalidate="novalidate">

				<div id="kt_modal_add_invitation_header" class="modal-header">

					<h2 class="fw-bold"><?php echo esc_html( wds_lang( 'dash_invitation_add' ) ); ?></h2>

					<div id="add_invitation_close" class="btn btn-icon btn-sm btn-bg-light btn-active-icon-danger" data-bs-dismiss="modal">
						<i class="ki-solid ki-cross fs-1"></i>
					</div>

				</div>

				<div class="modal-body py-10 px-lg-17">

					<div id="kt_modal_add_invitation_scroll" class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_invitation_header" data-kt-scroll-wrappers="#kt_modal_add_invitation_scroll" data-kt-scroll-offset="300px">

						<div class="fv-row mb-7">

							<label class="required form-label"><?php echo esc_html( wds_lang( 'dash_invitation_form_title' ) ); ?></label>

							<input type="text" name="title" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_title_placeholder' ) ); ?>" />

						</div>

						<div class="fv-row mb-7">

							<label class="required form-label"><?php echo esc_html( wds_lang( 'dash_invitation_form_slug_title' ) ); ?></label>

							<div class="input-group mb-5">

								<span id="data-url" class="input-group-text"><?php echo esc_url( wp_parse_url( get_site_url(), PHP_URL_HOST ) . '/' ); ?></span>

								<input type="text" name="slug" class="form-control" aria-describedby="data-url" placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_slug_placeholder' ) ); ?>" />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo wp_kses_post( wds_lang( 'dash_invitation_form_slug_description' ) ); ?></div>

							</div>

						</div>

						<?php if ( $template_count > 1 ) : ?>

							<div class="fv-row mb-7">

								<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_category' ) ); ?></label>

								<select name="category" id="select-category" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_select_category' ) ); ?>">
									<option value=""><?php echo esc_html( wds_lang( 'dash_invitation_form_select_category' ) ); ?></option>
									<?php
									$options    = array();
									$categories = wds_categories_with_sub_by_membership();
									foreach ( $categories as $category ) {
										$parent_category = get_term( $category->parent, 'category' );
										$parent_name     = $parent_category && ! is_wp_error( $parent_category ) ? $parent_category->name . ' | ' : '';

										$option = array(
											'name'  => $parent_name . $category->name,
											'value' => $category->term_id,
										);

										$options[] = $option;
									}

									usort(
										$options,
										function ( $a, $b ) {
											return strcmp( $a['name'], $b['name'] );
										}
									);

									foreach ( $options as $option ) {
										echo '<option value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['name'] ) . '</option>';
									}
									?>
								</select>

							</div>

							<input type="hidden" id="taxonomy" name="taxonomy" value="">

							<div class="fv-row mb-7">

								<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_form_theme' ) ); ?></label>

								<select name="template" id="template" class="form-select" data-control="select2" data-dropdown-parent="#invitation_form" data-placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_theme_select' ) ); ?>">
								</select>

							</div>

						<?php elseif ( 1 == $template_count ) : ?>

							<?php
							$selected   = '';
							$categories = wds_categories_with_sub_by_membership();
							foreach ( $categories as $category ) {
								echo '<input type="hidden" name="category" value="' . esc_attr( $category->term_id ) . '">';
								echo '<input type="hidden" name="taxonomy" value="' . esc_attr( wds_get_selected_taxonomy( $category->term_id ) ) . '">';
								$selected = wds_get_selected_taxonomy( $category->term_id );
							}
							?>

							<div class="fv-row mb-7">

								<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_form_theme' ) ); ?></label>

								<select name="template" id="template" class="form-select" data-control="select2" data-dropdown-parent="#invitation_form" data-placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_theme_select' ) ); ?>">
									<?php
									$terms = wds_invitation_get_template_taxonomy( $selected );
									foreach ( $terms as $term ) {
										echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
									}
									?>
								</select>

							</div>

						<?php endif; ?>

						<?php if ( 'reseller' == $user_group ) : ?>

							<div class="fv-row mb-7">

								<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_form_price' ) ); ?></label>

								<input type="number" name="price" class="form-control" placeholder="99000" />

								<div class="fs-7 fw-semibold text-muted mt-1"><?php echo esc_html( wds_lang( 'dash_invitation_form_price_desc' ) ); ?></div>

							</div>

						<?php endif; ?>

					</div>

				</div>

				<div class="modal-footer flex-center">

					<button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo esc_html( wds_lang( 'cancel' ) ); ?></button>

					<button type="submit" id="invitation_submit" class="btn btn-primary">
						<span class="indicator-label"><?php echo esc_html( wds_lang( 'submit' ) ); ?></span>
						<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
						</span>
					</button>

				</div>

			</form>

		</div>

	</div>

</div>