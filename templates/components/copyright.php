<div id="kt_footer" class="footer mt-5 py-4 d-flex flex-column flex-md-row flex-center">

	<div class="text-muted fw-semibold"><?php echo wp_kses_post( wds_user_meta( wds_data( 'reseller_id' ), '_branding_name' ) ? 'Copyright © ' . gmdate( 'Y' ) . ' - ' . wds_user_meta( wds_data( 'reseller_id' ), '_branding_name' ) : wds_option( 'copyright' ) ); ?></div>

</div>