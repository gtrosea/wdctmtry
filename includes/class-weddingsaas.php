<?php

if ( ! class_exists( 'WeddingSaas' ) ) :

	/**
	 * WeddingSaas Class.
	 *
	 * @since 1.0.0
	 */
	final class WeddingSaas {

		/**
		 * @var WeddingSaas The one true WeddingSaas
		 */
		private static $instance;

		/**
		 * WDS loader file.
		 *
		 * @var string
		 */
		private $file = '';

		/**
		 * WDS Database Object.
		 *
		 * @var WDS_Database
		 */
		public $database;

		/**
		 * WDS Invitation Object.
		 *
		 * @var WDS_Invitation
		 */
		public $invitation;

		/**
		 * WDS Meta Object.
		 *
		 * @var WDS_Meta
		 */
		public $meta;

		/**
		 * WDS Roles Object.
		 *
		 * @var WDS_Roles
		 */
		public $roles;

		/**
		 * WDS Session Object.
		 *
		 * @var WDS_Session
		 */
		public $session;

		/**
		 * Main WeddingSaas Instance.
		 *
		 * @param string $file The plugin file.
		 * @return WeddingSaas The one true WeddingSaas
		 */
		public static function instance( $file = '' ) {
			// Return if already instantiated
			if ( self::is_instantiated() ) {
				return self::$instance;
			}

			// Setup the singleton
			self::setup_instance( $file );

			// Bootstrap
			self::$instance->setup_files();

			// APIs
			self::$instance->database   = new WDS_Database();
			self::$instance->invitation = new WDS_Invitation();
			self::$instance->meta       = new WDS_Meta();
			self::$instance->roles      = new WDS_Roles();
			self::$instance->session    = new WDS_Session();

			// Return the instance.
			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Metode Tidak Diizinkan.', 'weddingsaas' ), '1.0' );
		}

		/**
		 * Disable un-serializing of the class.
		 *
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Metode Tidak Diizinkan.', 'weddingsaas' ), '1.0' );
		}

		/**
		 * Return whether the main loading class has been instantiated or not.
		 *
		 * @return boolean True if instantiated. False if not.
		 */
		private static function is_instantiated() {
			// Return true if instance is correct class
			if ( ! empty( self::$instance ) && ( self::$instance instanceof WeddingSaas ) ) {
				return true;
			}

			// Return false if not instantiated correctly
			return false;
		}

		/**
		 * Setup the singleton instance
		 *
		 * @param string $file The plugin file.
		 */
		private static function setup_instance( $file = '' ) {
			if ( empty( $file ) && defined( WDS_FILE ) ) {
				$file = WDS_FILE;
			}

			self::$instance       = new WeddingSaas();
			self::$instance->file = $file;
		}

		/**
		 * Include required files.
		 */
		private function setup_files() {
			$this->include_core();
			$this->include_classes();
			$this->include_object();
			$this->include_functions();
			$this->include_contents();
			$this->include_engine();
			$this->include_notifications();
			$this->include_gateways();
			$this->include_frontend();
			$this->include_integrations();

			if ( is_admin() ) {
				$this->include_admin();
			}
		}

		/**
		 * Setup all of the components.
		 */
		private function include_core() {
			require_once WDS_INCLUDES . 'core/abstracts/database.php';
			require_once WDS_INCLUDES . 'core/abstracts/frontend.php';
			require_once WDS_INCLUDES . 'core/abstracts/notification.php';
			require_once WDS_INCLUDES . 'core/abstracts/payment-gateway.php';

			require_once WDS_INCLUDES . 'core/libraries/class-user-info.php';
			require_once WDS_INCLUDES . 'core/libraries/tgmpa.php';

			require_once WDS_INCLUDES . 'core/models/affiliate.php';
			require_once WDS_INCLUDES . 'core/models/checkout.php';
			require_once WDS_INCLUDES . 'core/models/checkoutmeta.php';
			require_once WDS_INCLUDES . 'core/models/client.php';
			require_once WDS_INCLUDES . 'core/models/commission-withdrawal.php';
			require_once WDS_INCLUDES . 'core/models/commission.php';
			require_once WDS_INCLUDES . 'core/models/coupon-code.php';
			require_once WDS_INCLUDES . 'core/models/coupon-usage.php';
			require_once WDS_INCLUDES . 'core/models/coupon.php';
			require_once WDS_INCLUDES . 'core/models/income.php';
			require_once WDS_INCLUDES . 'core/models/invoice-order.php';
			require_once WDS_INCLUDES . 'core/models/invoice.php';
			require_once WDS_INCLUDES . 'core/models/order-log.php';
			require_once WDS_INCLUDES . 'core/models/order.php';
			require_once WDS_INCLUDES . 'core/models/ordermeta.php';
			require_once WDS_INCLUDES . 'core/models/product.php';
			require_once WDS_INCLUDES . 'core/models/productmeta.php';
			require_once WDS_INCLUDES . 'core/models/replica.php';
			require_once WDS_INCLUDES . 'core/models/setup.php';

			require_once WDS_INCLUDES . 'functions/option.php';
			require_once WDS_INCLUDES . 'functions/helper.php';
		}

		/**
		 * Setup classes.
		 */
		private function include_classes() {
			require_once WDS_INCLUDES . 'classes/builder.php';
			require_once WDS_INCLUDES . 'classes/collection.php';
			require_once WDS_INCLUDES . 'classes/handler.php';

			require_once WDS_INCLUDES . 'classes/request.php';
			require_once WDS_INCLUDES . 'classes/response.php';

			require_once WDS_INCLUDES . 'classes/renew.php';
			require_once WDS_INCLUDES . 'classes/checkout.php';
			if ( wds_is_digital() ) {
				require_once WDS_INCLUDES . 'classes/checkout-digital.php';
			}

			require_once WDS_INCLUDES . 'classes/cron.php';
			require_once WDS_INCLUDES . 'classes/invitation.php';
			require_once WDS_INCLUDES . 'classes/statistics.php';

			require_once WDS_INCLUDES . 'classes/database.php';
			require_once WDS_INCLUDES . 'classes/datameta.php';
			require_once WDS_INCLUDES . 'classes/roles.php';
			require_once WDS_INCLUDES . 'classes/session.php';
		}

		/**
		 * Setup all of the object.
		 */
		private function include_object() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'objects/currency.php';
				require_once WDS_INCLUDES . 'objects/statuses.php';
				require_once WDS_INCLUDES . 'objects/product.php';
				require_once WDS_INCLUDES . 'objects/coupon.php';
				require_once WDS_INCLUDES . 'objects/invoice.php';
				require_once WDS_INCLUDES . 'objects/order.php';
				require_once WDS_INCLUDES . 'objects/checkout.php';
				require_once WDS_INCLUDES . 'objects/affiliate.php';
				require_once WDS_INCLUDES . 'objects/commission.php';
				require_once WDS_INCLUDES . 'objects/withdraw.php';
				require_once WDS_INCLUDES . 'objects/client.php';
				require_once WDS_INCLUDES . 'objects/income.php';
				if ( wds_is_replica() ) {
					require_once WDS_INCLUDES . 'objects/replica.php';
				}
			}
		}

		/**
		 * Setup functions.
		 */
		private function include_functions() {
			require_once WDS_INCLUDES . 'functions/content.php';
			require_once WDS_INCLUDES . 'functions/invitation.php';
			require_once WDS_INCLUDES . 'functions/misc.php';
			require_once WDS_INCLUDES . 'functions/template.php';
			require_once WDS_INCLUDES . 'functions/url.php';
			require_once WDS_INCLUDES . 'functions/user.php';

			require_once WDS_INCLUDES . 'install.php';
		}

		/**
		 * Setup contents.
		 */
		private function include_contents() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'contents/classes.php';
				require_once WDS_INCLUDES . 'contents/membership.php';
				require_once WDS_INCLUDES . 'contents/post.php';
				require_once WDS_INCLUDES . 'contents/category.php';
				require_once WDS_INCLUDES . 'contents/shortcode.php';
				require_once WDS_INCLUDES . 'contents/user.php';
			}
		}

		/**
		 * Setup engines.
		 */
		private function include_engine() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'engine/classes.php';
			}
		}

		/**
		 * Setup notifications.
		 */
		private function include_notifications() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'notifications/functions.php';
				require_once WDS_INCLUDES . 'notifications/classes.php';
				require_once WDS_INCLUDES . 'notifications/email.php';
				require_once WDS_INCLUDES . 'notifications/whatsapp.php';
			}
		}

		/**
		 * Setup gateway.
		 */
		private function include_gateways() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'gateways/functions.php';
				require_once WDS_INCLUDES . 'gateways/duitku.php';
				require_once WDS_INCLUDES . 'gateways/tripay.php';
				require_once WDS_INCLUDES . 'gateways/xendit.php';
				require_once WDS_INCLUDES . 'gateways/midtrans.php';
				require_once WDS_INCLUDES . 'gateways/main.php';
				require_once WDS_INCLUDES . 'gateways/classes.php';
			}
		}

		/**
		 * Setup frontend.
		 */
		private function include_frontend() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'frontend/functions.php';
				require_once WDS_INCLUDES . 'frontend/ajax.php';
				require_once WDS_INCLUDES . 'frontend/main.php';
				require_once WDS_INCLUDES . 'frontend/classes.php';
				require_once WDS_INCLUDES . 'frontend/compatibility.php';
			}
		}

		/**
		 * Setup integrations.
		 */
		private function include_integrations() {
			if ( wds_is_active() ) {
				require_once WDS_INCLUDES . 'integrations/jet-form-builder/jfb.php';
				if ( class_exists( 'RankMath' ) ) {
					require_once WDS_INCLUDES . 'integrations/rankmath.php';
				}
				if ( did_action( 'elementor/loaded' ) ) {
					require_once WDS_INCLUDES . 'integrations/elementor.php';
				}
			}
		}

		/**
		 * Setup administration.
		 */
		private function include_admin() {
			if ( ! class_exists( 'WP_List_Table' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			}
			require_once WDS_INCLUDES . 'admin/tables/products.php';
			require_once WDS_INCLUDES . 'admin/tables/coupons.php';
			require_once WDS_INCLUDES . 'admin/tables/coupon-codes.php';
			require_once WDS_INCLUDES . 'admin/tables/invoices.php';
			require_once WDS_INCLUDES . 'admin/tables/orders.php';
			if ( wds_is_replica() ) {
				require_once WDS_INCLUDES . 'admin/tables/domain.php';
				require_once WDS_INCLUDES . 'admin/tables/subdomain.php';
			}

			require_once WDS_INCLUDES . 'admin/class-menu.php';
			require_once WDS_INCLUDES . 'admin/class-pages.php';
			require_once WDS_INCLUDES . 'admin/class-admin.php';
			require_once WDS_INCLUDES . 'admin/class-handle.php';
			require_once WDS_INCLUDES . 'admin/class-ajax.php';

			require_once WDS_INCLUDES . 'admin/plugin.php';
			require_once WDS_INCLUDES . 'admin/system.php';
		}
	}

endif; // End if class_exists check.

/**
 * Returns the instance of WeddingSaas.
 *
 * @return WeddingSaas The one true WeddingSaas instance.
 */
function WDS() { // phpcs:ignore
	return WeddingSaas::instance();
}
