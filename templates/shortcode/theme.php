<form id="form--theme" data-id="<?php echo esc_attr( wds_data( 'post_id' ) ); ?>">

	<?php if ( $show_category ) : ?>
		<div class="fv-row mb-7">

			<label class="required fs-6 fw-semibold mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_category' ) ); ?></label>

			<select name="category" id="select-category" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_select_category' ) ); ?>" data-dropdown-parent="#<?php echo esc_attr( $parent ); ?>">
				<option value="">asd<?php echo esc_html( wds_lang( 'dash_invitation_form_select_category' ) ); ?></option>
				<?php
				$options    = array();
				$categories = wds_categories_with_sub_by_membership( wds_post_membership() );
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
					$selected = $option['value'] == $category_id ? 'selected' : '';
					echo '<option value="' . esc_attr( $option['value'] ) . '" ' . esc_html( $selected ) . '>' . esc_html( $option['name'] ) . '</option>';
				}
				?>
			</select>

		</div>
	<?php endif; ?>

	<input type="hidden" id="taxonomy" name="taxonomy" value="<?php echo esc_attr( $taxonomy ); ?>">

	<div class="fv-row mb-7">

		<label class="required fs-6 fw-semibold mb-2 <?php echo $show_category ? '' : 'd-none'; ?>"><?php echo esc_html( wds_lang( 'dash_invitation_form_theme' ) ); ?></label>

		<select id="invitation-theme" class="form-select" data-control="select2" data-placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_form_theme_select' ) ); ?>" data-dropdown-parent="#<?php echo esc_attr( $parent ); ?>">
			<option></option>
			<?php
			foreach ( $terms as $term ) {
				$term_membership = wds_check_array( wds_term_meta( $term->term_id, '_membership' ) );
				if ( empty( $term_membership ) || in_array( wds_post_membership(), $term_membership ) ) {
					if ( ! in_array( $term->term_id, WDS()->invitation->get_subtheme_ids() ) ) {
						$theme_id = wds_term_meta( $term->term_id, '_theme' );
						if ( ! $is_theme || ( $is_theme && $theme_id ) ) {
							$selected  = $term_id == $term->term_id ? 'selected="selected"' : '';
							$thumbnail = WDS()->invitation->get_term_thumbnail( $term->term_id );
							$preview   = $is_theme ? get_the_permalink( $theme_id ) : wds_term_meta( $term->term_id, '_preview' );
							echo '<option value="' . esc_attr( $term->term_id ) . '" data-permalink="' . esc_url( $preview ) . '" data-thumbnail="' . esc_url( $thumbnail ) . '" data-id="' . esc_attr( $post_id ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $term->name ) . '</option>';
						}
					}
				}
			}
			?>
		</select>

	</div>

	<div id="theme-preview" class="my-6 text-center">
		<a href="<?php echo esc_url( $spreview ); ?>" target="_blank">
			<img src="<?php echo esc_url( WDS()->invitation->get_term_thumbnail( $term_id ) ); ?>" class="w-100 w-md-75">
			<span class="mt-3 d-block text-muted fs-7"><?php echo esc_html( wds_lang( 'dash_invitation_theme_note' ) ); ?></span>
		</a>
	</div>

	<button type="submit" id="form--theme--submit" class="btn btn-primary w-100">
		<span class="indicator-label"><?php echo esc_html( wds_lang( 'save' ) ); ?></span>
		<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
			<span class="align-middle spinner-border spinner-border-sm ms-2"></span>
		</span>
	</button>

</form>
