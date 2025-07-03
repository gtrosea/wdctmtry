<?php
$data    = wds_data( 'data_invitation' );
$max_num = wds_data( 'max_num_pages' );
$layout  = wds_data( 'layout' );
$grid    = 'grid' == $layout ? true : false;

$status     = wds_user_status();
$user_group = wds_user_group();
$quota      = wds_user_invitation_quota();

$upgrade_member   = wds_url( 'upgrade' );
$upgrade_reseller = wds_url( 'upgrade_reseller' );
$upgrade_topup    = wds_url( 'upgrade_quota' );
$template_count   = wds_templates_count();

$search = get_query_var( 'search' ) ? get_query_var( 'search' ) : '';

$form_layout   = wds_option( 'invitation_form_layout' );
$action_layout = wds_option( 'invitation_action_layout' ); ?>

<?php if ( $grid ) : ?>
	<style type="text/css">@media only screen and (max-width:375px){.col-md-6.col-lg-12.col-xl-6 .d-flex.flex-column.mt-5:not(.flexct) .d-flex{flex-direction:column;position:relative}.col-md-6.col-lg-12.col-xl-6 .d-flex.flex-column.mt-5:not(.flexct) .d-flex a:nth-child(1){position:absolute;left:0;width:48%!important}.col-md-6.col-lg-12.col-xl-6 .d-flex.flex-column.mt-5:not(.flexct) .d-flex a:nth-child(2){position:absolute;right:0;width:48%!important;margin-right:0!important}.col-md-6.col-lg-12.col-xl-6 .d-flex.flex-column.mt-5:not(.flexct) .d-flex a:nth-child(3){margin-top:40px}}</style>
<?php elseif ( '2' == $action_layout ) : ?>
	<style type="text/css">.btn-group-undangan{display:flex;flex-wrap:nowrap;overflow-x:auto;-webkit-overflow-scrolling:touch;-ms-overflow-style:-ms-autohiding-scrollbar;scrollbar-width:none;margin-bottom:0;justify-content:center}</style>
<?php endif; ?>

<?php require_once wds_get_template( 'dashboard/part/toolbar.php' ); ?>

<div id="kt_content" class="content flex-column-fluid">

	<?php do_action( 'wds_entry_content' ); ?>

	<?php if ( $grid ) : ?>

		<div class="card card-flush mb-5 mb-lg-7">

			<?php require_once wds_get_template( 'dashboard/part/card-header.php' ); ?>

		</div>

		<?php if ( ! empty( $data ) ) : ?>

			<div class="row g-5 g-xl-8">

				<?php foreach ( $data as $invitation ) : ?>

					<?php
					$_id        = $invitation->ID;
					$thumbnail  = $invitation->thumbnail;
					$_thumbnail = ! empty( $thumbnail ) ? $thumbnail : wds_option( 'thumbnail_placeholder' );
					$_title     = $invitation->title;
					$_edit      = wds_url( 'edit', $_id );
					$_status    = $invitation->status;
					$_category  = $invitation->category;
					$_permalink = $invitation->permalink;
					$_slug      = $invitation->slug;
					$_visitor   = $invitation->visitor;
					$_rsvp      = $invitation->rsvp;
					$_comment   = $invitation->comment . ' ';
					$expired    = ! empty( wds_post_meta( $_id, '_wds_pep_period' ) ) ? date_i18n( get_option( 'date_format' ), wds_post_meta( $_id, '_wds_pep_period' ) ) : '';
					$_expired   = ! empty( $expired ) ? $expired : wds_lang( 'active_lifetime' );
					?>

					<div class="col-md-6 col-lg-12 col-xl-6" data-wds-row="invitation" data-post-id="<?php echo esc_attr( $_id ); ?>" data-post-title="<?php echo esc_attr( $_title ); ?>">

						<div class="card h-100">

							<div class="card-header ribbon ribbon-end ribbon-clip">
								<div class="ribbon-label">
									<?php if ( 'publish' == $_status ) : ?>
										<?php echo esc_html( wds_lang( 'active' ) ); ?>
										<span class="ribbon-inner bg-success"></span>
									<?php elseif ( 'draft' == $_status ) : ?>
										<?php echo esc_html( wds_lang( 'expired' ) ); ?>
										<span class="ribbon-inner bg-warning"></span>
									<?php elseif ( 'pending' == $_status ) : ?>
										<?php echo esc_html( wds_lang( 'inactive' ) ); ?>
										<span class="ribbon-inner bg-info"></span>
									<?php else : ?>
										<?php esc_html_e( 'Pending Delete', 'wds-notrans' ); ?>
										<span class="ribbon-inner bg-danger"></span>
									<?php endif; ?>
								</div>
								<div data-wds-row="title" class="card-title"><?php echo esc_html( $_title ); ?></div>
							</div>

							<div class="card-body">

								<div class="d-flex flex-stack">

									<div class="d-flex align-items-center">

										<div class="symbol symbol-70px symbol-md-90px me-5">
											<?php if ( $_thumbnail ) : ?>
												<img src="<?php echo esc_url( WDS_URL . 'assets/img/spinner.gif' ); ?>" data-src="<?php echo esc_url( $_thumbnail ); ?>" class="lozad rounded" alt="" />
											<?php else : ?>
												<img src="<?php echo esc_url( WDS_BLANK_IMAGE ); ?>" alt="image" />
											<?php endif; ?>
										</div>

										<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
											<span class="text-gray-700 fw-bold"><?php echo 'publish' == $_status ? esc_html( $_expired ) : ''; ?></span>
											<span class="text-gray-700"><?php echo $template_count > 1 ? esc_html( $_category ) : ''; ?></span>
											<span class="text-gray-700">
												<?php echo esc_html( $_visitor . ' ' . wds_lang( 'dash_invitation_visitor' ) . ', ' . $_comment . wds_lang( 'dash_invitation_comment' ) ); ?>
											</span>
											<?php if ( 'default' == wds_option( 'rsvp_integration' ) ) : ?>
												<span class="text-gray-700"><?php echo esc_html( $_rsvp . ' ' . wds_lang( 'dash_invitation_rsvp' ) ); ?></span>
											<?php endif; ?>
										</div>

									</div>

									<?php if ( 'active' == $status ) : ?>

										<?php if ( 'publish' == $_status ) : ?>

											<div class="ms-1">

												<button type="button" class="btn btn-sm btn-icon btn-bg-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
													<i class="ki-outline ki-dots-vertical fs-1"></i>
												</button>

												<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px pb-3" data-kt-menu="true">

													<div class="menu-item px-3">
														<div class="menu-content fs-6 fw-bold px-3 py-4"><?php esc_html_e( 'Menu', 'wds-notrans' ); ?></div>
													</div>

													<div class="separator mb-3 opacity-75"></div>

													<div class="menu-item px-3">
														<a href="<?php echo esc_url( wds_url( 'rsvp', $_id ) ); ?>" class="menu-link px-3">
															<i class="ki-outline ki-messages fs-4 me-2"></i>
															<?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?>
														</a>
													</div>

													<?php if ( $expired ) : ?>
														<div class="menu-item px-3">
															<a href="#" class="menu-link px-3" data-wds-row="extend">
																<i class="ki-outline ki-key fs-4 me-2"></i>
																<?php echo esc_html( wds_lang( 'extend' ) ); ?>
															</a>
														</div>
													<?php endif; ?>

													<?php if ( 'reseller' == $user_group ) : ?>
														<div class="menu-item px-3">
															<a href="<?php echo esc_url( wds_url( 'share_client', $_id ) ); ?>" class="menu-link px-3">
																<i class="ki-outline ki-send fs-4 me-2"></i>
																<?php echo esc_html( wds_lang( 'dash_invitation_send_client' ) ); ?>
															</a>
														</div>
													<?php endif; ?>

												</div>

											</div>

										<?php endif; ?>

									<?php endif; ?>

								</div>

								<?php if ( 'active' == $status ) : ?>

									<div class="d-flex flex-column mt-5<?php echo 'publish' != $_status ? ' flexct' : ''; ?>">

										<div class="d-flex">

											<?php if ( 'publish' == $_status ) : ?>

												<a href="<?php echo esc_url( wds_invitation_open( $_id ) ); ?>" target="_blank" class="btn btn-sm btn-primary justify-content-center w-100 me-2">
													<i class="ki-outline ki-click"></i>
													<?php echo esc_html( wds_lang( 'dash_invitation_open' ) ); ?>
												</a>

												<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" class="btn btn-sm btn-success justify-content-center w-100 me-2">
													<i class="ki-outline ki-notepad-edit"></i>
													<?php echo esc_html( wds_lang( 'dash_invitation_edit' ) ); ?>
												</a>

												<a href="<?php echo esc_url( wds_url( 'share', $invitation->ID ) ); ?>" class="btn btn-sm btn-warning justify-content-center w-100">
													<i class="ki-outline ki-send"></i>
													<?php echo esc_html( wds_lang( 'dash_invitation_share' ) ); ?>
												</a>

											<?php else : ?>

												<?php if ( 'pending' == $_status ) : ?>

													<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" class="btn btn-sm btn-light-primary justify-content-center w-100 me-2">
														<?php echo esc_html( wds_lang( 'dash_invitation_edit' ) ); ?>
													</a>
													
													<a href="#" class="btn btn-sm btn-light-success justify-content-center w-100" data-wds-row="activate">
														<?php echo esc_html( wds_lang( 'dash_invitation_activate' ) ); ?>
													</a>

												<?php else : ?>

													<a href="#" class="btn btn-sm btn-light-primary justify-content-center w-100" data-wds-row="activate">
														<?php echo esc_html( wds_lang( 'dash_invitation_activate' ) ); ?>
													</a>

												<?php endif; ?>

											<?php endif; ?>

										</div>

									</div>

								<?php endif; ?>

							</div>

						</div>

					</div>

				<?php endforeach; ?>

			</div>

			<?php
			$pagination = paginate_links(
				array(
					'base'      => esc_url_raw( add_query_arg( 'page', '%#%' ) ),
					'format'    => '?page=%#%',
					'current'   => max( 1, get_query_var( 'page' ) ),
					'total'     => wds_data( 'max_num_pages' ),
					'type'      => 'array',
					'prev_text' => '<i class="previous"></i> Previous',
					'next_text' => 'Next <i class="next"></i>',
				)
			);

			if ( $pagination ) {
				echo '<ul class="pagination mt-10">';

				foreach ( $pagination as $page ) {
					$class = strpos( $page, 'current' ) !== false ? 'page-item active' : 'page-item';
					echo '<li class="' . esc_attr( $class ) . '">' . wp_kses_post( str_replace( 'page-numbers', 'page-link', $page ) ) . '</li>';
				}

				echo '</ul>';
			}
			?>

		<?php endif; ?>

	<?php else : ?>

		<div class="card card-flush">

			<?php require_once wds_get_template( 'dashboard/part/card-header.php' ); ?>

			<div class="card-body pt-0">

				<div class="table-responsive">

					<table id="invitation_table" class="table table-row-bordered align-middle gy-4 gs-9">

						<thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">

							<tr>
								<th class="min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_title' ) ); ?></th>
								<th class="text-center min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_visitor' ) ); ?></th>
								<th class="text-center min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?></th>
								<?php if ( $template_count > 1 ) : ?>
									<th class="text-center min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_category' ) ); ?></th>
								<?php endif; ?>
								<th class="text-center min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_status' ) ); ?></th>
								<th class="text-end min-w-100px"><?php echo esc_html( wds_lang( 'dash_invitation_actions' ) ); ?></th>
							</tr>

						</thead>

						<tbody class="fw-semibold text-gray-600">

							<?php if ( ! empty( $data ) ) : ?>

								<?php foreach ( $data as $invitation ) : ?>

									<tr>
										<td>
											<div class="d-flex align-items-center">
												<?php if ( 'active' == $status ) : ?>

													<?php if ( 'publish' == $invitation->status ) : ?>

														<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" class="symbol symbol-50px">
															<span class="symbol-label" style="background-image:url(<?php echo esc_url( $invitation->thumbnail ); ?>);"></span>
														</a>

														<div class="ms-5">
															<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-wds-row="title"><?php echo esc_html( $invitation->title ); ?></a>
														</div>

													<?php else : ?>

														<span class="symbol symbol-50px">
															<span class="symbol-label" style="background-image:url(<?php echo esc_url( $invitation->thumbnail ); ?>);"></span>
														</span>

														<div class="ms-5">
															<span class="text-gray-800 text-hover-primary fs-5 fw-bold" data-wds-row="title"><?php echo esc_html( $invitation->title ); ?></span>
														</div>

													<?php endif; ?>

												<?php else : ?>

													<?php if ( 'publish' == $invitation->status ) : ?>

														<a href="<?php echo esc_url( $invitation->permalink ); ?>" target="_blank" class="symbol symbol-50px">
															<span class="symbol-label" style="background-image:url(<?php echo esc_url( $invitation->thumbnail ); ?>);"></span>
														</a>

														<div class="ms-5">
															<a href="<?php echo esc_url( $invitation->permalink ); ?>" target="_blank" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-wds-row="title"><?php echo esc_html( $invitation->title ); ?></a>
														</div>

													<?php else : ?>

														<span class="symbol symbol-50px">
															<span class="symbol-label" style="background-image:url(<?php echo esc_url( $invitation->thumbnail ); ?>);"></span>
														</span>

														<div class="ms-5">
															<span class="text-gray-800 text-hover-primary fs-5 fw-bold" data-wds-row="title"><?php echo esc_html( $invitation->title ); ?></span>
														</div>

													<?php endif; ?>

												<?php endif; ?>

											</div>
										</td>

										<td class="text-center">
											<span class="fw-bold"><?php echo esc_html( $invitation->visitor ); ?></span>
										</td>

										<td class="text-center">
											<span class="fw-bold"><?php echo wp_kses_post( $invitation->comment ); ?></span>
										</td>

										<?php if ( $template_count > 1 ) : ?>
											<td class="text-center">
												<span class="fw-bold"><?php echo esc_html( $invitation->category ); ?></span>
											</td>
										<?php endif; ?>

										<td class="text-center">
											<?php if ( 'publish' == $invitation->status ) : ?>
												<div class="badge badge-success"><?php echo esc_html( wds_lang( 'active' ) ); ?></div>
											<?php elseif ( 'draft' == $invitation->status ) : ?>
												<div class="badge badge-warning"><?php echo esc_html( wds_lang( 'expired' ) ); ?></div>
											<?php elseif ( 'pending' == $invitation->status ) : ?>
												<div class="badge badge-secondary"><?php echo esc_html( wds_lang( 'inactive' ) ); ?></div>
											<?php else : ?>
												<div class="badge badge-danger"><?php esc_html_e( 'Pending Delete', 'wds-notrans' ); ?></div>
											<?php endif; ?>
										</td>

										<td class="text-end">

											<span data-wds-row="post_id" style="display:none;"><?php echo esc_html( $invitation->ID ); ?></span>

											<?php if ( 'active' == $status ) : ?>

												<?php if ( 'publish' == $invitation->status ) : ?>

													<?php if ( '1' == $action_layout ) : ?>

														<a href="#" class="btn btn-sm btn-light-primary btn-flex btn-center btn-active-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
															<?php echo esc_html( wds_lang( 'dash_invitation_actions' ) ); ?>
															<i class="ki-duotone ki-down fs-5 ms-1"></i>
														</a>

														<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4 overflow-visible" data-kt-menu="true">

															<div class="menu-item px-3">
																<a href="<?php echo esc_url( wds_invitation_open( $invitation->ID ) ); ?>" target="_blank" class="menu-link px-3"><?php esc_html_e( 'Open', 'wds-notrans' ); ?></a>
															</div>

															<div class="menu-item px-3">
																<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" class="menu-link px-3"><?php esc_html_e( 'Edit', 'wds-notrans' ); ?></a>
															</div>

															<div class="menu-item px-3">
																<a href="<?php echo esc_url( wds_url( 'share', $invitation->ID ) ); ?>" class="menu-link px-3"><?php echo esc_html( wds_lang( 'dash_invitation_share' ) ); ?></a>
															</div>

															<div class="menu-item px-3">
																<a href="<?php echo esc_url( wds_url( 'rsvp', $invitation->ID ) ); ?>" class="menu-link px-3"><?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?></a>
															</div>

														</div>

													<?php else : ?>

														<div class="btn-group-undangan justify-content-end">

															<a href="<?php echo esc_url( wds_invitation_open( $invitation->ID ) ); ?>" target="_blank" class="btn btn-sm btn-primary btn-flex btn-center me-2">
																<i class="ki-outline ki-click"></i>
																<?php esc_html_e( 'Open', 'wds-notrans' ); ?>
															</a>

															<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" target="_blank" class="btn btn-sm btn-success btn-flex btn-center me-2">
																<i class="ki-outline ki-notepad-edit"></i>
																<?php esc_html_e( 'Edit', 'wds-notrans' ); ?>
															</a>

															<a href="<?php echo esc_url( wds_url( 'share', $invitation->ID ) ); ?>" target="_blank" class="btn btn-sm btn-warning btn-flex btn-center me-2">
																<i class="ki-outline ki-share"></i>
																<?php echo esc_html( wds_lang( 'dash_invitation_share' ) ); ?>
															</a>

															<a href="<?php echo esc_url( wds_url( 'rsvp', $invitation->ID ) ); ?>" target="_blank" class="btn btn-sm btn-danger btn-flex btn-center">
																<i class="ki-outline ki-messages"></i>
																<?php echo esc_html( wds_lang( 'dash_invitation_rsvp' ) ); ?>
															</a>

														</div>

													<?php endif; ?>

												<?php else : ?>

													<?php if ( 'pending' == $invitation->status ) : ?>

														<a href="<?php echo esc_url( wds_url( 'edit', $invitation->ID ) ); ?>" target="_blank" class="btn btn-sm btn-light-primary btn-flex btn-center btn-active-primary" style="margin-right:5px;"><?php esc_html_e( 'Edit', 'wds-notrans' ); ?></a>

														<a href="#" class="btn btn-sm btn-light-primary btn-flex btn-center btn-active-primary" data-wds-row="activate"><?php echo esc_html( wds_lang( 'dash_invitation_activate' ) ); ?></a>

													<?php else : ?>

														<a href="#" class="btn btn-sm btn-light-primary btn-flex btn-center btn-active-primary" data-wds-row="activate"><?php echo esc_html( wds_lang( 'dash_invitation_activate' ) ); ?></a>

													<?php endif; ?>

												<?php endif; ?>

											<?php endif; ?>

										</td>

									</tr>

								<?php endforeach; ?>

							<?php endif; ?>

						</tbody>

					</table>

				</div>

			</div>

		</div>

	<?php endif; ?>

	<?php if ( 'active' == $status && $quota > 0 && '1' == $form_layout ) : ?>

		<?php require_once wds_get_template( 'dashboard/part/form-modal.php' ); ?>

	<?php endif; ?>

</div>