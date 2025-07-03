<div class="wrap wds-wrap">

	<div id="wds-page" class="system-page">

		<h2><?php esc_html_e( 'System Information', 'wds-notrans' ); ?></h2>

		<div class="wds-page-content system-content">

			<div class="wds-content-wrap section-server">

				<h3><?php esc_html_e( 'Server Environment', 'wds-notrans' ); ?></h3>

				<div class="wds-table">

					<?php if ( ! empty( $systemservers ) ) : ?>

						<?php foreach ( $systemservers as $systemserver ) : ?>

							<div class="wds-row">

								<div class="system-info-th"><span><?php echo esc_html( $systemserver['title'] ); ?>:</span></div>

								<div class="system-info-th"><i class="<?php echo esc_attr( wds_system_icon( $systemserver['alert'] ) . ' color--' . $systemserver['alert'] ); ?>"></i></div>

								<div class="system-info-th"><?php echo wp_kses_post( $systemserver['data'] . ' ' . $systemserver['description'] ); ?></div>

							</div>

						<?php endforeach; ?>

					<?php endif; ?>

				</div>

			</div>

			<div class="wds-content-wrap section-wordpress">

				<h3><?php esc_html_e( 'WordPress Environment', 'wds-notrans' ); ?></h3>

				<div class="wds-table">

					<?php if ( ! empty( $systemwps ) ) : ?>

						<?php foreach ( $systemwps as $systemwp ) : ?>

							<div class="wds-row">

								<div class="system-info-th"><span><?php echo esc_html( $systemwp['title'] ); ?>:</span></div>

								<div class="system-info-th"><i class="<?php echo esc_attr( wds_system_icon( $systemwp['alert'] ) . ' color--' . $systemwp['alert'] ); ?>"></i></div>

								<div class="system-info-th"><?php echo wp_kses_post( $systemwp['data'] . ' ' . $systemwp['description'] ); ?></div>

							</div>

						<?php endforeach; ?>

					<?php endif; ?>

				</div>

			</div>

			<div class="wds-content-wrap section-theme">

				<h3><?php esc_html_e( 'Theme Information', 'wds-notrans' ); ?></h3>

				<div class="wds-table">

					<?php if ( ! empty( $systemthemes ) ) : ?>

						<?php foreach ( $systemthemes as $systemtheme ) : ?>

							<div class="wds-row">

								<div class="system-info-th"><span><?php echo esc_html( $systemtheme['title'] ); ?>:</span></div>

								<div class="system-info-th"><i class="<?php echo esc_attr( wds_system_icon( $systemtheme['alert'] ) . ' color--' . $systemtheme['alert'] ); ?>"></i></div>

								<div class="system-info-th"><?php echo wp_kses_post( $systemtheme['data'] . ' ' . $systemtheme['description'] ); ?></div>

							</div>

						<?php endforeach; ?>

					<?php endif; ?>

				</div>

			</div>

			<div class="wds-content-wrap section-plugin">

				<h3><?php esc_html_e( 'Active Plugins', 'wds-notrans' ); ?> (<?php echo count( $plugins_counts ); ?>)</h3>

				<div class="wds-table">

					<?php
					foreach ( $plugins_counts as $data ) :
						$plugin_info    = get_plugin_data( WP_PLUGIN_DIR . '/' . $data );
						$dirname        = dirname( $data );
						$version_string = '';
						$network_string = '';
						if ( ! empty( $plugin_info['Name'] ) ) :
							$plugin_name = $plugin_info['Name'];
							if ( ! empty( $plugin_info['PluginURI'] ) ) {
								$plugin_name = '<a href="' . $plugin_info['PluginURI'] . '" target="_blank">' . $plugin_name . '</a>';
							}
							$allowed_html = array(
								'a'      => array(
									'href'   => array(),
									'title'  => array(),
									'target' => array(),
								),
								'br'     => array(),
								'em'     => array(),
								'strong' => array(),
							);
							?>

							<div class="wds-row">

								<div class="system-info-th"><span><?php echo wp_kses( $plugin_name, $allowed_html ); ?></span></div>

								<div class="system-info-th"><i class="dashicons dashicons-thumbs-up color--success"></i></div>

								<div class="system-info-th"><span>by <?php echo wp_kses_post( $plugin_info['Author'] . ' &ndash; v' . $plugin_info['Version'] . $version_string . $network_string ); ?></span></div>

							</div>

						<?php endif; ?>

					<?php endforeach; ?>

				</div>

			</div>

		</div>

	</div>

</div>