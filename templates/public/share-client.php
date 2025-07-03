<?php $display = wds_data( 'share_display' ); ?>

<div class="d-flex flex-column flex-root">

	<div class="d-flex flex-column flex-center flex-column-fluid">

		<div class="p-10 d-flex flex-column flex-center w-100">

			<div class="content flex-column-fluid w-100 w-md-700px">

				<?php if ( 'empty_param' == $display ) : ?>

					<div class="px-10 py-10 mb-10 alert bg-light-danger d-flex flex-center flex-column px-lg-20">

						<i class="mb-5 ki-duotone ki-information-5 fs-5tx text-danger">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>

						<div class="text-center">

							<h1 class="mb-5 fw-bold"><?php echo esc_html( wds_lang( 'public_share_empty_parameter' ) ); ?></h1>

							<div class="mb-5 opacity-25 separator separator-dashed border-danger"></div>

							<div class="text-dark"><?php echo esc_html( wds_lang( 'public_share_client_error' ) ); ?></div>

						</div>

					</div>

				<?php elseif ( 'incorret_param' == $display ) : ?>

					<div class="px-10 py-10 mb-10 alert bg-light-danger d-flex flex-center flex-column px-lg-20">

						<i class="mb-5 ki-duotone ki-information-5 fs-5tx text-danger">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>

						<div class="text-center">

							<h1 class="mb-5 fw-bold"><?php echo esc_html( wds_lang( 'public_share_incorrect_parameter' ) ); ?></h1>

							<div class="mb-5 opacity-25 separator separator-dashed border-danger"></div>

							<div class="text-dark"><?php echo esc_html( wds_lang( 'public_share_client_error' ) ); ?></div>

						</div>

					</div>

				<?php else : ?>

					<div class="mb-10 text-center">

						<?php require_once wds_get_template( 'public/_logo.php' ); ?>

					</div>

					<div class="card">

						<div class="card-body p-lg-20">

							<?php wds_dark_mode(); ?>

							<h1 class="mb-10 text-center fw-bold fs-1"><?php echo esc_html( wds_lang( 'public_share_client_title' ) ); ?></h1>

							<div class="mb-8 d-flex flex-column">

								<label class="mb-2 fs-6 fw-semibold"><?php echo esc_html( wds_lang( 'public_share_client_message' ) ); ?></label>

								<textarea name="message" id="message_copy_input" class="form-control form-control-solid" data-kt-autosize="true"><?php echo wp_kses_post( wds_data( 'share_message' ) ); ?></textarea>

							</div>

							<div class="mt-2">

								<button id="message_copy_btn" class="btn btn-primary" data-clipboard-target="#message_copy_input"><?php echo esc_html( wds_lang( 'public_share_client_button' ) ); ?></button>

							</div>

						</div>

					</div>

					<div class="mt-8 text-center">

						<div class="text-dark"><?php echo wp_kses_post( wds_data( 'copyright' ) ); ?></div>

					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>

</div>