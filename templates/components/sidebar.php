<?php
$page = wds_data( 'page' );

$invitation_menu  = wds_url( 'invitation' );
$client_menu      = wds_url( 'client' );
$marketing_menu   = wds_url( 'marketing' );
$landingpage_menu = wds_url( 'landingpage' );
$access_menu      = wds_url( 'access' );

$hide_client    = 'hide' == wds_option( 'client_type' ) ? false : true;
$hide_marketing = 'hide' == wds_option( 'marketing_type' ) ? false : true;

$custom_menu   = wds_engine( 'menu' );
$user_group    = wds_user_group();
$_referral     = ! empty( wds_option( 'affiliate_hide' ) ) && in_array( $user_group, wds_option( 'affiliate_hide' ) ) ? false : true;
$hide_referral = wds_user_affiliate_status() == 'inactive' ? false : $_referral;

$menu_invitation = array(
	'dashboard/invitation',
	'dashboard/invitation/edit',
	'dashboard/invitation/rsvp',
	'dashboard/invitation/create',
); ?>

<div id="kt_aside" class="aside card" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">

	<div class="aside-menu flex-column-fluid px-4">

		<div id="kt_aside_menu_wrapper" class="hover-scroll-overlay-y mh-100 my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">

			<div id="#kt_aside_menu" class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" data-kt-menu="true">

				<div class="menu-item">
					<div class="menu-content">
						<span class="menu-heading fw-bold text-uppercase fs-7"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></span>
					</div>
				</div>

				<div class="menu-item">
					<a href="<?php echo esc_url( $invitation_menu ); ?>" class="menu-link <?php echo in_array( $page, $menu_invitation ) ? 'active' : ''; ?>">
						<span class="menu-icon">
							<i class="ki-duotone ki-tablet-book fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</span>
						<span class="menu-title"><?php echo esc_html( wds_lang( 'invitation' ) ); ?></span>
					</a>
				</div>

				<?php if ( wds_is_digital() ) : ?>

					<div class="menu-item">
						<a href="<?php echo esc_url( $access_menu ); ?>" class="menu-link <?php echo 'dashboard/access' == $page ? 'active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-cloud-download fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title"><?php echo esc_html( wds_lang( 'access' ) ); ?></span>
						</a>
					</div>

				<?php endif; ?>

				<?php if ( ! empty( $custom_menu ) ) : ?>

					<?php foreach ( $custom_menu as $item ) : ?>

						<?php
						$title   = wds_sanitize_data_field( $item, 'title' );
						$icon    = wds_sanitize_data_field( $item, 'icon' );
						$url     = wds_sanitize_data_field( $item, 'url' );
						$tab     = wds_sanitize_data_field( $item, 'new_tab' );
						$group   = wds_sanitize_data_field( $item, 'group', array() );
						$product = wds_sanitize_data_field( $item, 'product', array() );

						$newtab = $tab ? 'target="_blank"' : '';

						if ( empty( $group ) || in_array( $user_group, $group ) ) :
							if ( empty( $product ) | in_array( wds_user_membership(), $product ) ) :
								?>

								<div class="menu-item">
									<a href="<?php echo esc_url( $url ); ?>" <?php echo esc_attr( $newtab ); ?> class="menu-link">
										<span class="menu-icon">
											<i class="bi bi-<?php echo esc_attr( $icon ); ?> fs-3"></i>
										</span>
										<span class="menu-title"><?php echo esc_html( $title ); ?></span>
									</a>
								</div>

							<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>

				<?php endif; ?>

				<?php if ( 'reseller' == $user_group ) : ?>

					<div class="menu-item pt-5">
						<div class="menu-content">
							<span class="menu-heading fw-bold text-uppercase fs-7"><?php echo esc_html( wds_lang( 'reseller' ) ); ?></span>
						</div>
					</div>

					<?php if ( $hide_client ) : ?>
						<div class="menu-item">
							<a href="<?php echo esc_url( $client_menu ); ?>" class="menu-link <?php echo 'dashboard/client' == $page ? 'active' : ''; ?>">
								<span class="menu-icon">
									<i class="ki-duotone ki-people fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
									</i>
								</span>
								<span class="menu-title"><?php echo esc_html( wds_lang( 'client' ) ); ?></span>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( $hide_marketing ) : ?>
						<div class="menu-item">
							<a href="<?php echo esc_url( $marketing_menu ); ?>" class="menu-link <?php echo 'dashboard/marketing' == $page ? 'active' : ''; ?>">
								<span class="menu-icon">
									<i class="ki-duotone ki-share fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
										<span class="path5"></span>
										<span class="path6"></span>
									</i>
								</span>
								<span class="menu-title"><?php echo esc_html( wds_lang( 'marketing' ) ); ?></span>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( wds_is_replica() ) : ?>

						<div class="menu-item">
							<a href="<?php echo esc_url( $landingpage_menu ); ?>" class="menu-link <?php echo 'dashboard/landingpage' == $page || 'dashboard/landingpage/edit' == $page ? 'active' : ''; ?>">
								<span class="menu-icon">
									<i class="ki-duotone ki-monitor-mobile fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title"><?php echo esc_html( wds_lang( 'landingpage' ) ); ?></span>
							</a>
						</div>

					<?php endif; ?>

				<?php endif; ?>

				<div class="menu-item pt-5">
					<div class="menu-content">
						<span class="menu-heading fw-bold text-uppercase fs-7"><?php echo esc_html( wds_lang( 'account' ) ); ?></span>
					</div>
				</div>

				<div class="menu-item">
					<a href="<?php echo esc_url( wds_url( 'overview' ) ); ?>" class="menu-link<?php echo 'account/overview' == $page ? ' active' : ''; ?>">
						<span class="menu-icon">
							<i class="ki-duotone ki-home-1 fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</span>
						<span class="menu-title"><?php echo esc_html( wds_lang( 'overview' ) ); ?></span>
					</a>
				</div>

				<div class="menu-item">
					<a href="<?php echo esc_url( wds_url( 'settings' ) ); ?>" class="menu-link<?php echo 'account/settings' == $page ? ' active' : ''; ?>">
						<span class="menu-icon">
							<i class="ki-duotone ki-setting fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</span>
						<span class="menu-title"><?php echo esc_html( wds_lang( 'settings' ) ); ?></span>
					</a>
				</div>

				<div class="menu-item">
					<a href="<?php echo esc_url( wds_url( 'transactions' ) ); ?>" class="menu-link<?php echo 'account/transactions' == $page ? ' active' : ''; ?>">
						<span class="menu-icon">
							<i class="ki-duotone ki-purchase fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</span>
						<span class="menu-title"><?php echo esc_html( wds_lang( 'transactions' ) ); ?></span>
					</a>
				</div>

				<?php if ( $hide_referral ) : ?>
					<div class="menu-item">
						<a href="<?php echo esc_url( wds_url( 'referrals' ) ); ?>" class="menu-link<?php echo 'account/referrals' == $page ? ' active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-dollar fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title"><?php echo esc_html( wds_lang( 'referrals' ) ); ?></span>
						</a>
					</div>
				<?php endif; ?>

				<?php if ( wds_is_admin() ) : ?>

					<div class="menu-item pt-5">
						<div class="menu-content">
							<span class="menu-heading fw-bold text-uppercase fs-7"><?php esc_html_e( 'Administrator', 'wds-notrans' ); ?></span>
						</div>
					</div>

					<div class="menu-item">
						<a href="<?php echo esc_url( admin_url() ); ?>" target="_blank" class="menu-link">
							<span class="menu-icon">
								<i class="ki-duotone ki-abstract-28 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title"><?php esc_html_e( 'Dashboard', 'wds-notrans' ); ?></span>
						</a>
					</div>

					<div class="menu-item">
						<a href="<?php echo esc_url( wds_url( 'affiliate' ) ); ?>" class="menu-link<?php echo 'admin/affiliate' == $page ? ' active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-user-tick fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
							<span class="menu-title"><?php esc_html_e( 'Affiliate', 'wds-notrans' ); ?></span>
						</a>
					</div>

					<div class="menu-item">
						<a href="<?php echo esc_url( wds_url( 'payouts' ) ); ?>" class="menu-link<?php echo 'admin/affiliate-payouts' == $page ? ' active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-credit-cart fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<span class="menu-title"><?php esc_html_e( 'Affiliate Payouts', 'wds-notrans' ); ?></span>
						</a>
					</div>

					<div class="menu-item">
						<a href="<?php echo esc_url( wds_url( 'statistics' ) ); ?>" class="menu-link<?php echo 'admin/statistics' == $page ? ' active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-graph-up fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
									<span class="path5"></span>
									<span class="path6"></span>
								</i>
							</span>
							<span class="menu-title"><?php esc_html_e( 'Statistics', 'wds-notrans' ); ?></span>
						</a>
					</div>

					<div class="menu-item">
						<a href="<?php echo esc_url( wds_url( 'users' ) ); ?>" class="menu-link<?php echo 'admin/users' == $page ? ' active' : ''; ?>">
							<span class="menu-icon">
								<i class="ki-duotone ki-profile-user fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
							</span>
							<span class="menu-title"><?php esc_html_e( 'Users', 'wds-notrans' ); ?></span>
						</a>
					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>

	<?php if ( ! empty( wds_option( 'support_link' ) ) ) : ?>

		<div id="kt_aside_footer" class="aside-footer flex-column-auto pt-5 pb-7 px-7">

			<a href="<?php echo esc_url( wds_option( 'support_link' ) ); ?>" class="btn btn-bg-light btn-color-gray-500 btn-active-color-gray-900 text-nowrap w-100">
				<span class="btn-label"><?php echo esc_html( wds_lang( 'support' ) ); ?></span>
				<i class="ki-duotone ki-rocket btn-icon fs-2 ms-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
			</a>

		</div>

	<?php endif; ?>

</div>