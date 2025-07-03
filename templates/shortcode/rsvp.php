<div class="saic-wrapper saic-default saic-border saic-full <?php echo ! comments_open( $post_id ) ? 'saic-comments-closed' : ''; ?>" style="overflow: hidden;">

	<div class="saic-wrap-link">

		<a href="<?php echo esc_url( $link ); ?>" id="saic-link-<?php echo esc_attr( $post_id ); ?>" class="saic-link saic-icon-link saic-icon-link-true auto-load-true">
			<i class="saico-comment"></i>
			<span><?php echo esc_html( $num ); ?></span>
			<?php echo esc_html( wds_post_meta( $post_id, '_rsvp_comments' ) ? wds_post_meta( $post_id, '_rsvp_comments' ) : wds_lang( 'rsvp_comment' ) ); ?>
		</a>

	</div>

	<div id="saic-wrap-comment-<?php echo esc_attr( $post_id ); ?>" class="saic-wrap-comments" style="display: none;" data-id="<?php echo esc_attr( $post_id ); ?>">

		<div id="saic-wrap-form-<?php echo esc_attr( $post_id ); ?>" class="saic-wrap-form saic-clearfix">

			<div id="saic-container-form-<?php echo esc_attr( $post_id ); ?>" class="saic-container-form">

				<div id="respond-<?php echo esc_attr( $post_id ); ?>" class="respond saic-clearfix">

					<form action="" method="POST" id="commentform-<?php echo esc_attr( $post_id ); ?>">

						<?php if ( ! is_user_logged_in() ) : ?>

							<p class="comment-form-author saic-field-1">
								<input type="text" name="author" id="author" class="saic-input" aria-required="true" placeholder="<?php echo esc_attr( $text_name ); ?>" value="<?php echo esc_attr( wds_sanitize_data_field( $_GET, 'to' ) ); ?>" />
								<span class="saic-required">*</span>
								<span class="saic-error-info saic-error-info-name"><?php echo esc_html( wds_lang( 'rsvp_req_name' ) ); ?></span>
							</p>

						<?php endif; ?>

						<?php if ( empty( $hide_attendance ) ) : ?>

							<div class="saic-wrap-attendance">
								<select name="attendance" id="attendance" class="saic-select">
									<option value="" selected="" disabled><?php echo esc_html( $text_confirm ); ?></option>
									<option value="present"><?php echo esc_html( $text_present ); ?></option>
									<option value="notpresent"><?php echo esc_html( $text_notpresent ); ?></option>
									<?php if ( empty( $hide_notsure ) ) : ?>
										<option value="notsure"><?php echo esc_html( $text_notsure ); ?></option>
									<?php endif; ?>
								</select>
								<span class="saic-required">*</span>
								<span class="saic-error-info saic-error-info-attendance"><?php echo esc_html( wds_lang( 'rsvp_req_attendance' ) ); ?></span>
							</div>

						<?php endif; ?>

						<div class="saic-wrap-guest" style="display: none;">
							<select name="guest" id="guest" class="saic-select">
								<option value="" selected="" disabled><?php echo esc_html( $text_guest ); ?></option>
								<?php
								$guest = $guest_max;
								for ( $i = 1; $i <= $guest; $i++ ) {
									echo '<option value="' . esc_attr( $i ) . '">' . esc_html( $i ) . ' ' . esc_html( $text_person ) . '</option>';
								}
								?>
							</select>
							<span class="saic-required">*</span>
							<span class="saic-error-info saic-error-info-guest"><?php echo esc_html( wds_lang( 'rsvp_req_guest' ) ); ?></span>
						</div>

						<div class="saic-wrap-textarea">
							<textarea name="comment" id="saic-textarea-<?php echo esc_attr( $post_id ); ?>" class="waci_comment saic-textarea autosize-textarea" aria-required="true" placeholder="<?php echo esc_attr( $text_comment ); ?>" rows="1"></textarea>
							<span class="saic-required">*</span>
							<span class="saic-error-info saic-error-info-text"><?php echo esc_html( wds_lang( 'rsvp_req_comment' ) ); ?></span>
						</div>

						<div class="saic-wrap-submit saic-clearfix">
							<p class="form-submit">
                                <?php echo get_comment_id_fields( $post_id ); // phpcs:ignore ?>
								<input type="hidden" name="commentpress" value="true" />
								<input type="submit" id="submit-<?php echo esc_attr( $post_id ); ?>" value="<?php echo esc_html( $text_submit ); ?>" />
							</p>
						</div>

					</form>

				</div>

			</div>

		</div>

		<div id="saic-comment-status-<?php echo esc_attr( $post_id ); ?>" class="saic-comment-status"></div>

		<ul id="saic-container-comment-<?php echo esc_attr( $post_id ); ?>" class="saic-container-comments saic-order-<?php echo esc_attr( $order ); ?> <?php echo ( $num > 1 ) ? 'saic-has-' . esc_attr( $num ) . '-comments saic-multiple-comments' : ''; ?>" data-order="<?php echo esc_attr( $order ); ?>"></ul>

		<div class="saic-holder-<?php echo esc_attr( $post_id ); ?> saic-holder"></div>

	</div>

</div>
