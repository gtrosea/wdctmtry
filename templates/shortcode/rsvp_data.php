<li id="saic-item-comment-<?php echo esc_attr( $comment_id ); ?>" <?php comment_class( 'saic-item-comment' ); ?>>

	<div id="saic-comment-<?php echo esc_attr( $comment_id ); ?>" class="saic-comment saic-clearfix">

		<div class="saic-comment-avatar">

			<img src="<?php echo esc_url( $avatar ); ?>" class="avatar avatar-28 photo" height="28" width="28" />

		</div>

		<div class="saic-comment-content">

			<div class="saic-comment-info">

				<span class="saic-commenter-name" title="<?php echo esc_attr( $autor_name ); ?>"><?php echo esc_html( $autor_name ); ?></span>

				<span class="saic-author-mark saic-post-author-<?php echo esc_html( $atendence ); ?>"><?php echo $icon; // phpcs:ignore ?></span>

			</div>

			<div class="saic-comment-text">

				<?php echo wp_kses_post( $content ); ?>

			</div>

			<div class="saic-comment-time"><?php echo esc_html( $time ); ?></div>

			<?php if ( wds_is_admin() ) : ?>

				<div class="saic-comment-actions">

					<a href="<?php echo esc_url( admin_url( 'comment.php?action=editcomment&c=' ) . $comment_id ); ?>" target="_blank" class="saic-edit-link"><?php esc_html_e( 'Edit', 'wds-notrans' ); ?></a>

				</div>

			<?php endif; ?>

		</div>

	</div>

</li>