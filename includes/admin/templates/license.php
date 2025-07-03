<div class="wrap">

	<h1><?php esc_html_e( 'WeddingSaas License', 'wds-notrans' ); ?></h1>

	<form action="" method="post">

		<?php wp_nonce_field( 'license_activation', 'weddingsaas_nonce' ); ?>

		<input type="hidden" name="weddingsaas_action" value="license_activation">

		<table class="form-table">

			<tbody>

				<?php
				foreach ( $products as $id => $args ) :

					$value   = '';
					$license = get_option( $id . '_license' );

					if ( wds_is_product_active( $id ) && isset( $license['expires'] ) && 'lifetime' == $license['expires'] ) {
						$value = __( 'Lisensi Anda berlaku seumur hidup', 'weddingsaas' );
					} elseif ( wds_is_product_active( $id ) && isset( $license['expires'] ) ) {
						$value = sprintf(
							/* translators: %s: The license date */
							__( 'Lisensi Anda berlaku sampai %s', 'weddingsaas' ),
							gmdate( 'F j, Y', strtotime( $license['expires'] ) )
						);
					}

					$readonly = wds_is_product_active( $id ) ? 'readonly="readonly"' : '';
					?>

					<tr>

						<th scope="row">
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $args['name'] ); ?></label>
						</th>

						<td>
							<input type="text" class="regular-text" name="<?php echo esc_attr( $id ) . '_license_key'; ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $readonly ); ?>>
							<?php if ( wds_is_product_active( $id ) ) : ?>
								<input type="submit" class="button button-secondary" name="<?php echo esc_attr( $id ) . '_deactivation'; ?>" value="<?php esc_html_e( 'Nonaktifkan', 'weddingsaas' ); ?>">
							<?php else : ?>
								<input type="submit" class="button button-primary" name="<?php echo esc_attr( $id ) . '_activation'; ?>" value="<?php esc_html_e( 'Aktifkan', 'weddingsaas' ); ?>">
							<?php endif; ?>
						</td>

					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

	</form>

</div>