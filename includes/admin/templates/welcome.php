<div class="wrap wds-wrap">

	<div id="wds-page" class="welcome-page">

		<h2><?php esc_html_e( 'Welcome to WeddingSaas', 'wds-notrans' ); ?></h2>

		<div class="wds-page-content welcome-content">

			<div class="wds-content-wrap section-welcome">

				<h3>Hai <b style="color:#dc392d;"><?php echo esc_html( $user ); ?></b>!</h3>

				<span>
				<?php
				printf(
					/* translators: %s: The dosc url */
					wp_kses_post( 'Before starting, be sure to always check <mark class="alert--info">tutorial</mark> from <a href="%s" target="_blank" class="link---primary">Website</a>. We have prepared all kinds of tutorials and make it easier for you when using the product.', 'wds-notrans' ),
					esc_url( 'https://docs.pelatform.com/collection/weddingsaas-MROupjQJn6?utm_source=welcome-page&utm_campaign=wds-tutorial&utm_medium=admin' )
				);
				?>
				</span>

				<br /><br />

				<span>
				<?php
				printf(
					/* translators: %s: The support url */
					wp_kses_post( 'If you can\'t find the answer at <mark class="alert--info">tutorial</mark> or you are having problems, please contact us via <a href="%s" target="_blank" class="link---primary">send ticket</a>.', 'wds-notrans' ),
					esc_url( WDS_STORE . 'support/?utm_source=welcome-page&utm_campaign=wds-support&utm_medium=admin' )
				);
				?>
				</span>

				<br /><br />

				<span><?php esc_html_e( 'We are happy to help you and you will get back from us sooner than you expect.', 'wds-notrans' ); ?></span>

				<br /><br />

				<span style="font-size:13px !important;">
				<?php
				printf(
					/* translators: %s: The main web url */
					wp_kses_post( 'Thank you for using <a href="%s" target="_blank" class="link---primary"><strong>WeddingSaaS.ID</strong></a>!', 'wds-notrans' ),
					esc_url( WDS_STORE . '?utm_source=welcome-page&utm_campaign=homepage&utm_medium=wp-dash' )
				);
				?>
				</span>

			</div>

		</div>

	</div>

</div>