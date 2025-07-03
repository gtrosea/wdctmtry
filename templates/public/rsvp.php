<?php
$rsvp        = wds_data( 'data_rsvp' );
$data        = wds_data( 'data_attendance' );
$post_id     = wds_data( 'post_id' );
$title       = get_the_title( $post_id );
$integration = wds_option( 'rsvp_integration' ); ?>

<div class="d-flex flex-column flex-root">

	<div class="d-flex flex-column flex-center flex-column-fluid">

		<?php if ( ! wds_data( 'rsvp_activate' ) ) : ?>

			<div class="p-10 d-flex flex-column flex-center w-100">

				<div class="content flex-column-fluid w-100 w-md-550px">

					<div class="mb-10 text-center">

						<?php require_once wds_get_template( 'public/_logo.php' ); ?>

					</div>

					<div class="card">

						<div class="card-body">

							<?php wds_dark_mode(); ?>

							<h1 class="mb-15 text-center fw-bold fs-1"><?php echo esc_html( wds_data( 'rsvp_title' ) ); ?></h1>

							<form action="" class="form-inline d-flex align-items-center">

								<input type="password" name="rsvp_pass" class="form-control" placeholder="<?php echo esc_attr( wds_lang( 'public_rsvp_password' ) ); ?>" />

								<input type="button" id="rsvp_btn" value="<?php echo esc_attr( wds_lang( 'submit' ) ); ?>" class="btn btn-primary ms-3">

							</form>

						</div>

					</div>

					<div class="mt-8 text-center">

						<div class="text-dark"><?php echo wp_kses_post( wds_data( 'copyright' ) ); ?></div>

					</div>


				</div>

			</div>

		<?php else : ?>

			<div class="d-flex flex-column flex-center d-flex flex-column flex-center p-10 w-100">

				<div class="content flex-column-fluid w-100 ">

					<div class="text-center mb-10">

						<?php require_once wds_get_template( 'public/_logo.php' ); ?>

					</div>

					<h1 class="fw-bold fs-1 text-center mb-10"><?php echo esc_html( wds_data( 'rsvp_title' ) ); ?></h1>

					<?php require_once wds_get_template( 'partials/rsvp_data.php' ); ?>

					<div class="mt-8 text-center">

						<div class="text-dark"><?php echo wp_kses_post( wds_data( 'copyright' ) ); ?></div>

					</div>

				</div>

			</div>

		<?php endif; ?>

	</div>

</div>