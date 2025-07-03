<div class="card-header align-items-center py-5 gap-2 <?php echo $grid ? 'd-block d-sm-flex gap-sm-5' : 'gap-md-5'; ?>">

	<?php if ( $grid ) : ?>

		<form action="" class="card-title me-0 me-sm-2 mb-5 mb-sm-2 w-100 w-sm-auto">

			<div class="d-flex align-items-center w-100">

				<div class="position-relative w-100 me-3">

					<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>

					<input type="text" name="search" class="form-control form-control-solid ps-10" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_search' ) ); ?>" />

				</div>

				<button type="submit" class="btn btn-primary"><?php echo esc_html( wds_lang( 'search' ) ); ?></button>

			</div>

		</form>

	<?php else : ?>

		<div class="card-title">

			<div class="d-flex align-items-center position-relative my-1">

				<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>

				<input type="text" id="table_invitation_search" class="form-control form-control-solid w-250px ps-12" placeholder="<?php echo esc_attr( wds_lang( 'dash_invitation_search' ) ); ?>" data-kt-filter="search" />

			</div>

		</div>

	<?php endif; ?>

	<div class="card-toolbar flex-row-fluid justify-content-end<?php echo $grid ? '' : ' gap-5'; ?>">

		<?php if ( ! $grid ) : ?>

			<button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
				<i class="ki-duotone ki-exit-down fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
				<?php esc_html_e( 'Export', 'wds-notrans' ); ?>
			</button>

			<div id="table_invitation_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

				<div class="menu-item px-3">
					<a href="#" class="menu-link px-3" data-kt-export="copy">
						<?php esc_html_e( 'Copy to clipboard', 'wds-notrans' ); ?>
					</a>
				</div>

				<div class="menu-item px-3">
					<a href="#" class="menu-link px-3" data-kt-export="excel">
						<?php esc_html_e( 'Export as Excel', 'wds-notrans' ); ?>
					</a>
				</div>

				<div class="menu-item px-3">
					<a href="#" class="menu-link px-3" data-kt-export="csv">
						<?php esc_html_e( 'Export as CSV', 'wds-notrans' ); ?>
					</a>
				</div>

				<div class="menu-item px-3">
					<a href="#" class="menu-link px-3" data-kt-export="pdf">
						<?php esc_html_e( 'Export as PDF', 'wds-notrans' ); ?>
					</a>
				</div>

			</div>

			<div id="table_invitation_button" class="d-none"></div>

		<?php endif; ?>

		<?php if ( 'active' == $status && $quota > 0 ) : ?>

			<?php if ( '1' == $form_layout ) : ?>

				<button type="button" class="btn btn-primary w-100 w-sm-auto hover-scale" data-bs-toggle="modal" data-bs-target="#modal_add_invitation">
					<i class="ki-duotone ki-plus fs-2"></i><?php echo esc_html( wds_lang( 'dash_invitation_add' ) ); ?>
				</button>

			<?php else : ?>

				<a href="<?php echo esc_url( wds_url( 'create' ) ); ?>" class="btn btn-primary w-100 w-sm-auto hover-scale">
					<i class="ki-duotone ki-plus fs-2"></i><?php echo esc_html( wds_lang( 'dash_invitation_add' ) ); ?>
				</a>

			<?php endif; ?>

		<?php elseif ( 'reseller' == $user_group ) : ?>

			<?php if ( 'active' == $status ) : ?>

				<a href="<?php echo esc_url( $upgrade_topup ); ?>" class="btn btn-danger w-100 w-md-auto">
					<i class="ki-duotone ki-rocket">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					<?php echo esc_html( wds_lang( 'dash_invitation_upgrade' ) ); ?>
				</a>

			<?php else : ?>

				<a href="<?php echo esc_url( $upgrade_reseller ); ?>" class="btn btn-danger w-100 w-md-auto">
					<i class="ki-duotone ki-rocket">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
					<?php echo esc_html( wds_lang( 'dash_invitation_upgrade' ) ); ?>
				</a>

			<?php endif; ?>

		<?php else : ?>

			<a href="<?php echo esc_url( $upgrade_member ); ?>" class="btn btn-danger w-100 w-md-auto">
				<i class="ki-duotone ki-rocket">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>
				<?php echo esc_html( wds_lang( 'dash_invitation_upgrade' ) ); ?>
			</a>

		<?php endif; ?>

	</div>

</div>