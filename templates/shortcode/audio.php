<form id="form--audio" enctype="multipart/form-data" data-id="<?php echo esc_attr( wds_data( 'post_id' ) ); ?>" data-type="<?php echo esc_attr( $audio_type ); ?>">

	<?php if ( wds_engine( 'audio_youtube' ) || wds_engine( 'audio_custom' ) ) : ?>

		<div class="d-flex nav-group nav-group-outline mb-8" data-kt-buttons="true">

			<button type="button" id="default" class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 w-100 <?php echo 'default' == $audio_type ? 'active' : ''; ?>"><?php esc_html_e( 'Default', 'wds-notrans' ); ?></button>

			<button type="button" id="youtube" class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 w-100 <?php echo 'youtube' == $audio_type ? 'active' : ''; ?> <?php echo wds_engine( 'audio_youtube' ) ? '' : 'd-none'; ?>"><?php esc_html_e( 'Youtube', 'wds-notrans' ); ?></button>

			<button type="button" id="custom" class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 w-100 <?php echo 'custom' == $audio_type ? 'active' : ''; ?> <?php echo wds_engine( 'audio_custom' ) ? '' : 'd-none'; ?>"><?php esc_html_e( 'Custom', 'wds-notrans' ); ?></button>

		</div>
		
	<?php endif; ?>

	<div class="data-default mb-5" <?php echo 'default' == $audio_type ? '' : 'style="display: none;"'; ?>>

		<label class="form-label mb-2"><?php echo esc_html( wds_lang( 'dash_invitation_audio_label' ) ); ?></label>

		<select id="audio" class="form-select" data-control="select2" data-placeholder="<?php echo esc_html( wds_lang( 'dash_invitation_audio_select' ) ); ?>" data-dropdown-parent="#<?php echo esc_attr( $parent ); ?>" data-allow-clear="true">
			<option></option>
			<?php
			foreach ( $source as $audio_link => $audio_title ) {
				$selected = $music_link == $audio_link ? 'selected="selected"' : '';
				echo '<option value="' . esc_url( $audio_link ) . '" data-audio="' . esc_attr( $audio_link ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $audio_title ) . '</option>';
			}
			?>
		</select>

		<div class="mt-5<?php echo esc_attr( $show_time_default ); ?>">
			<label class="form-label"><?php esc_html_e( 'Waktu Mulai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_start" class="form-control" value="<?php echo esc_html( $audio_start ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 10', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu mulai (dalam detik). Kosongkan jika ingin mulai dari awal.', 'weddingsaas' ); ?></span>
		</div>
		
		<div class="mt-5<?php echo esc_attr( $show_time_default ); ?>">
			<label class="form-label"><?php esc_html_e( 'Waktu Selesai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_end" class="form-control" value="<?php echo esc_html( $audio_end ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 180', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu berakhir (dalam detik). Kosongkan jika ingin selesai sampai akhir.', 'weddingsaas' ); ?></span>
		</div>

		<div class="mt-5 text-muted fs-7">
			<audio src="<?php echo esc_attr( $music_link ); ?>" id="preview_audio" class="w-100" controls controlsList="nodownload"></audio>
		</div>

		<?php if ( wds_engine( 'audio_note' ) ) : ?>
			<div class="alert alert-primary mt-5" role="alert">
				<?php echo wp_kses_post( wds_engine( 'audio_note' ) ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="data-youtube mb-8" <?php echo 'youtube' == $audio_type ? '' : 'style="display: none;"'; ?>>
		
		<div class="mb-5">
			<label class="form-label"><?php esc_html_e( 'Youtube Link', 'weddingsaas' ); ?></label>
			<input type="url" name="audio_youtube" class="form-control" value="<?php echo esc_url( $youtube_link ); ?>" placeholder="https://www.youtube.com/watch?v=fPMB12H340iI" />
		</div>

		<div class="mb-5">
			<label class="form-label"><?php esc_html_e( 'Waktu Mulai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_youtube_start" class="form-control" value="<?php echo esc_html( $audio_start ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 10', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu mulai (dalam detik). Kosongkan jika ingin mulai dari awal.', 'weddingsaas' ); ?></span>
		</div>
		
		<div class="mb-5">
			<label class="form-label"><?php esc_html_e( 'Waktu Selesai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_youtube_end" class="form-control" value="<?php echo esc_html( $audio_end ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 180', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu berakhir (dalam detik). Kosongkan jika ingin selesai sampai akhir.', 'weddingsaas' ); ?></span>
		</div>

		<div class="alert alert-primary" role="alert">
			<?php echo wp_kses_post( wds_engine( 'audio_youtube_note' ) ); ?>
		</div>
		
	</div>

	<div class="data-custom mb-8" <?php echo 'custom' == $audio_type ? '' : 'style="display: none;"'; ?>>
		
		<div class="form-group row">
			<label class="col-lg-3 col-form-label text-lg-right"><?php esc_html_e( 'Upload Audio', 'weddingsaas' ); ?></label>

			<div class="col-lg-9">
				<div class="dropzone dropzone-queue mb-2" id="dropzone_mp3">
					<div class="dropzone-panel mb-lg-0 mb-2">
						<a class="dropzone-select btn btn-sm btn-primary me-2 text-light"><?php esc_html_e( 'Klik Disini untuk upload', 'weddingsaas' ); ?></a>
					</div>

					<div class="dropzone-items wm-200px">
						<div class="dropzone-item" style="display:none">
							<div class="dropzone-file">
								<div class="dropzone-filename" title="some_image_file_name.jpg">
									<span data-dz-name>some_image_file_name.jpg</span>
									<strong>(<span data-dz-size>340kb</span>)</strong>
								</div>

								<div class="dropzone-error" data-dz-errormessage></div>
							</div>
							<div class="dropzone-progress">
								<div class="progress">
									<div
										class="progress-bar bg-primary"
										role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
									</div>
								</div>
							</div>
							<div class="dropzone-toolbar">
								<span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="mt-5 text-muted fs-7 <?php echo 'custom' == $audio_type && ! empty( $music_link ) ? '' : 'd-none'; ?>">
			<audio src="<?php echo esc_attr( $music_link ); ?>" id="preview_audio" class="w-100" controls controlsList="nodownload"></audio>
		</div>

		<div class="mt-5 <?php echo 'custom' == $audio_type && ! empty( $music_link ) && ! empty( wds_engine( 'audio_time_custom' ) ) ? '' : 'd-none'; ?>">
			<label class="form-label"><?php esc_html_e( 'Waktu Mulai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_custom_start" class="form-control" value="<?php echo esc_html( $audio_start ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 10', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu mulai (dalam detik). Kosongkan jika ingin mulai dari awal.', 'weddingsaas' ); ?></span>
		</div>
		
		<div class="mt-5 <?php echo 'custom' == $audio_type && ! empty( $music_link ) && ! empty( wds_engine( 'audio_time_custom' ) ) ? '' : 'd-none'; ?>">
			<label class="form-label"><?php esc_html_e( 'Waktu Selesai', 'weddingsaas' ); ?></label>
			<input type="number" name="audio_custom_end" class="form-control" value="<?php echo esc_html( $audio_end ); ?>" placeholder="<?php esc_attr_e( 'Contoh: 180', 'weddingsaas' ); ?>" />
			<span class="text-muted"><?php esc_html_e( 'Tentukan waktu berakhir (dalam detik). Kosongkan jika ingin selesai sampai akhir.', 'weddingsaas' ); ?></span>
		</div>

		<div class="alert alert-primary mt-5" role="alert">
			<?php echo wp_kses_post( wds_engine( 'audio_custom_note' ) ); ?>
		</div>
		
	</div>

	<button type="submit" id="form--audio--submit" class="mt-5 btn btn-primary w-100">
		<span class="indicator-label"><?php echo esc_html( wds_lang( 'save' ) ); ?></span>
		<span class="indicator-progress"><?php echo esc_html( wds_lang( 'please_wait' ) ); ?>...
			<span class="align-middle spinner-border spinner-border-sm ms-2"></span>
		</span>
	</button>

</form>
