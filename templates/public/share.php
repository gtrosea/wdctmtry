<?php
$data    = wds_data( 'data_share' );
$display = wds_data( 'share_display' );
$ex      = __(
	'Contoh:
Nama Tamu 1
Nama Tamu 2
Nama Tamu 3',
	'weddingsaas'
); ?>

<script type="text/javascript">var WDSshare = <?php echo wp_json_encode( $data ); ?>;</script>

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

							<div class="text-dark"><?php echo esc_html( wds_lang( 'public_share_error_subtitle' ) ); ?></div>

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

							<div class="text-dark"><?php echo esc_html( wds_lang( 'public_share_error_subtitle' ) ); ?></div>

						</div>

					</div>

				<?php else : ?>

					<div class="mb-10 text-center">

						<?php require_once wds_get_template( 'public/_logo.php' ); ?>

					</div>

					<div class="card">

						<div class="card-body p-lg-20">

							<?php wds_dark_mode(); ?>

							<h1 class="mb-10 text-center fw-bold fs-1"><?php echo esc_html( wds_lang( 'public_share_title' ) ); ?></h1>

							<form action="" method="post" id="share_form">

								<div class="mb-5 fv-row d-flex flex-column">

									<label class="mb-0 fs-6 fw-semibold"><?php echo esc_html( wds_lang( 'public_share_guest_name' ) ); ?></label>

									<div class="mb-3 fs-7 fw-semibold text-muted">#<?php echo esc_html( wds_lang( 'public_share_guest_name_help' ) ); ?></div>

									<textarea name="guest" id="guest" class="form-control form-control-solid" rows="7" placeholder="<?php echo esc_attr( $ex ); ?>"></textarea>

								</div>

								<?php if ( wds_option( 'restrict_invitation' ) ) : ?>

									<div class="mb-6 fv-row form-check">

										<input type="checkbox" id="restrict" class="form-check-input cursor-pointer" <?php echo $data['is_restrict'] ? 'checked' : ''; ?> />

										<label for="restrict" class="form-check-label cursor-pointer"><?php echo esc_html( wds_lang( 'public_share_restrict' ) ); ?></label>

									</div>

								<?php endif; ?>

								<div class="mb-5 fv-row d-flex flex-column">

									<label class="mb-2 fs-6 fw-semibold"><?php echo esc_html( wds_lang( 'public_share_introductory_text' ) ); ?></label>

									<select name="category" id="select-category" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="<?php echo esc_html( wds_lang( 'public_share_introductory_select' ) ); ?>">
										<option value=""><?php echo esc_html( wds_lang( 'select' ) ); ?></option>
										<?php
										$shared = wds_option( 'share_data' );
										if ( wds_check_array( $shared, true ) ) {
											foreach ( $shared as $share ) {
												echo '<option value="' . esc_attr( $share['title'] ) . '">' . esc_html( $share['title'] ) . '</option>';
											}
										}
										?>
									</select>

								</div>

								<div class="mb-8 fv-row d-flex flex-column">

									<label class="mb-2 fs-6 fw-semibold"><?php echo esc_html( wds_lang( 'public_share_introductory_input' ) ); ?></label>

									<div class="overlay overlay-block">

										<textarea name="message" id="message" class="form-control form-control-solid" rows="15"></textarea>

										<div id="target-overlay" class="overlay-layer card-rounded bg-dark bg-opacity-5 d-none">
											<div class="spinner-border text-primary" role="status">
												<span class="visually-hidden">Loading...</span>
											</div>
										</div>

									</div>

									<div class="mt-2 fs-7 fw-semibold text-muted"><?php esc_html_e( 'Variable', 'wds-notrans' ); ?>: <?php echo esc_html( $data['variable'] ); ?></div>

								</div>

								<button type="submit" id="share_submit" class="btn btn-primary">
									<span class="indicator-label"><?php echo esc_html( wds_lang( 'public_share_button' ) ); ?></span>
									<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
									</span>
								</button>

							</form>

							<div class="separator separator-dashed border-primary my-15"></div>

							<div class="table-responsive">

								<table id="share_table" class="table mb-0 border table-hover table-rounded table-striped gy-6 gs-6">

									<thead>

										<tr class="text-gray-800 border-gray-200 fw-semibold fs-6 border-bottom-2">

											<th class="text-start min-w-125px"><?php echo esc_html( wds_lang( 'name' ) ); ?></th>

											<th class="text-end min-w-175px"><?php echo esc_html( wds_lang( 'action' ) ); ?></th>

										</tr>

									</thead>

									<tbody></tbody>

								</table>

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
