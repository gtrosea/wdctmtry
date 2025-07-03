<?php $data = wds_data( 'data_reseller' ); ?>

<div id="kt_toolbar" class="toolbar mb-5 mb-lg-7">

	<div class="page-title d-flex flex-column me-3">

		<h1 class="d-flex fw-bold my-1 fs-3"><?php echo esc_html_e( 'Edit Landing Page', 'wds-notrans' ); ?></h1>

		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">

			<li class="breadcrumb-item text-gray-600"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php echo esc_html( wds_lang( 'landingpage' ) ); ?></li>

			<li class="breadcrumb-item text-gray-500"><?php esc_html_e( 'Edit', 'wds-notrans' ); ?></li>

		</ul>

	</div>

</div>

<div id="kt_content" class="content flex-column-fluid">

	<div class="card mb-5 mb-xxl-8">

		<div class="card-body pt-9 pb-9">

			<div class="d-flex flex-wrap flex-sm-nowrap">

				<div class="me-0 me-md-7 mb-4">

					<div class="d-flex justify-content-center w-md-200px">

						<?php if ( $data['branding_logo'] ) : ?>
							<img class="mb-3 mh-75px mw-100" src="<?php echo esc_url( $data['branding_logo'] ); ?>">
						<?php else : ?>
							<img src="<?php echo esc_url( WDS_BLANK_IMAGE ); ?>" class="rounded h-100 w-100" alt="image" />
						<?php endif; ?>

					</div>

				</div>

				<div class="flex-grow-1">

					<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">

						<div class="d-flex flex-column">

							<div class="d-flex align-items-center mb-2">

								<a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo esc_attr( $data['branding_name'] ); ?></a>

							</div>

							<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">

								<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
									<i class="ki-duotone ki-profile-circle fs-4 me-1">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
									<?php echo esc_html_e( 'Oleh ', 'weddingsaas' ) . esc_html( wds_user_name() ); ?>
								</a>

								<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
									<i class="ki-duotone ki-calendar-add fs-4 me-1">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
										<span class="path6"></span>
									</i>
									<?php echo esc_html( wds_lang( 'status' ) ) . ' ' . esc_html( ucfirst( $data['status'] ) ); ?>
								</a>

							</div>

						</div>

						<div class="my-4">

							<div class="d-flex flex-wrap justify-content-between">

								<?php if ( '#' == $data['url'] ) : ?>
									<a href="<?php echo esc_url( wds_url( 'landingpage' ) ); ?>" class="btn btn-sm btn-info me-md-3 mb-3 w-100 w-md-auto">
										<i class="ki-outline ki-monitor-mobile"></i>
										<?php echo esc_html( wds_lang( 'wdr_create_landing_page' ) ); ?>
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( $data['url'] ); ?>" target="_blank" class="btn btn-sm btn-primary me-md-3 mb-3 w-100 w-md-auto" id="open-invitation">
										<i class="ki-outline ki-monitor-mobile"></i>
										<?php echo esc_html( wds_lang( 'wdr_open_landing_page' ) ); ?>
									</a>
								<?php endif; ?>

							</div>

						</div>

					</div>

					<div class="d-flex flex-wrap flex-stack">

						<div class="col-xl-6">
							<div class="d-flex">
								<input type="text" name="search" id="permalink_link_input" class="form-control form-control-solid me-3 flex-grow-1" value="<?php echo esc_url( $data['url'] ); ?>" readonly />
								<button id="permalink_link_copy_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0" data-clipboard-target="#permalink_link_input"><?php echo esc_html( wds_lang( 'dash_invitation_copy' ) ); ?></button>
							</div>
						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="row g-6 g-xl-9 mb-6 mb-xl-9">

		<?php foreach ( $data['form'] as $item ) : ?>

			<?php
			$icon      = wds_sanitize_data_field( $item, 'icon' );
			$title     = wds_sanitize_data_field( $item, 'title' );
			$slug      = sanitize_title( $title );
			$shortcode = wds_sanitize_data_field( $item, 'shortcode' );
			$product   = wds_sanitize_data_field( $item, 'product', array() );
			?>

			<?php if ( empty( $product ) || in_array( wds_user_membership(), $product ) ) : ?>

				<div class="col-6 col-lg-4 col-xl-3">

					<div class="card h-100">

						<div class="card-body d-flex justify-content-center text-center flex-column p-6">

							<a href="#" class="text-gray-800 text-hover-primary d-flex flex-column" data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr( $slug ); ?>">

								<div class="symbol symbol-35px symbol-md-60px mb-5">
									<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $icon ); ?>" class="lozad rounded mw-100" />
								</div>

								<div class="fs-6 fs-md-5 fw-bold"><?php echo esc_html( $title ); ?></div>

							</a>

						</div>

					</div>
					
				</div>

				<div class="modal fade" id="<?php echo esc_attr( $slug ); ?>" tabindex="-1" aria-hidden="true">

					<div class="modal-dialog modal-dialog-centered mw-<?php echo ! empty( wds_option( 'invitation_edit_popup' ) ) ? esc_attr( wds_option( 'invitation_edit_popup' ) ) : 650; ?>px">

						<div class="modal-content">

							<div class="modal-header">

								<h2 class="fw-bold"><?php echo esc_html( $title ); ?></h2>

								<div id="add_invitation_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-bg-light btn-active-icon-danger">
									<i class="ki-solid ki-cross fs-1"></i>
								</div>

							</div>

							<div class="modal-body overlay py-10 px-lg-17">

								<div class="scroll-y ms-n2 me-n7 ps-2 pe-7" id="kt_modal_edit_invitation_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_invitation_header" data-kt-scroll-wrappers="#kt_modal_edit_invitation_scroll" data-kt-scroll-offset="300px">
									<?php echo do_shortcode( $shortcode ); ?>
								</div>

								<div id="form-overlay" class="overlay-layer bg-dark bg-opacity-5 d-none">
									<div class="spinner-border text-primary" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</div>

							</div>

						</div>

					</div>

				</div>

			<?php endif; ?>

		<?php endforeach; ?>

	</div>

</div>
