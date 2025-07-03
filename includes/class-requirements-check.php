<?php

/**
 * WeddingSaas_Requirements_Check Class.
 *
 * @since 2.0.0
 */
final class WeddingSaas_Requirements_Check {

	/**
	 * Plugin file.
	 *
	 * @var string
	 */
	private $file = '';

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	private $base = '';

	/**
	 * Setup plugin requirements.
	 */
	public function __construct() {
		// Setup file & base
		$this->file = WDS_FILE;
		$this->base = WDS_BASE;

		// Run check
		if ( $this->check_requirements() ) {
			$this->load();
		}
	}

	/**
	 * Display notice in admin.
	 *
	 * @param string $message The notice message.
	 */
	private function message_fail_notice( $message ) {
		echo '<div class="notice notice-error" style="padding:10px 20px;"><p>' . esc_html( $message ) . '</p></div>';

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Check requirement.
	 *
	 * @return bool
	 */
	public function check_requirements() {
		$requirements = array(
			array(
				'check'   => ! version_compare( PHP_VERSION, WDS_PHP_VERSION, '>=' ),
				'message' => sprintf(
					'The WeddingSaas plugin requires PHP version %s+ or higher, the plugin is currently NOT RUNNING.',
					WDS_PHP_VERSION
				),
			),
			array(
				'check'   => ! version_compare( get_bloginfo( 'version' ), WDS_WP_VERSION, '>=' ),
				'message' => sprintf(
					'The WeddingSaas plugin requires WordPress version %s+. Since you are using an older version, the plugin is currently NOT RUNNING.',
					WDS_WP_VERSION
				),
			),
			array(
				'check'   => is_multisite(),
				'message' => 'The WeddingSaas plugin cannot run on multisite, please switch to single-site version.',
			),
		);

		foreach ( $requirements as $requirement ) {
			if ( $requirement['check'] ) {
				add_action(
					'admin_notices',
					function () use ( $requirement ) {
						$this->message_fail_notice( $requirement['message'] );
					}
				);

				deactivate_plugins( $this->base );
				unset( $_GET['activate'] );

				return false;
			}
		}

		return true;
	}

	/**
	 * Load normally.
	 */
	private function load() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! class_exists( 'CSF' ) && ! is_plugin_active( 'pelatform/pelatform.php' ) ) {
			require_once WDS_PATH . 'vendor/framework/codestar.php';
		}

		if ( ! class_exists( 'WeddingSaas' ) ) {
			require_once dirname( $this->file ) . '/includes/class-weddingsaas.php';
		}

		if ( class_exists( 'WeddingSaas' ) ) {
			add_action( 'plugins_loaded', array( $this, 'bootstrap' ), 5 );

			do_action( 'weddingsaas_loaded' );

			register_activation_hook( $this->file, array( $this, 'activation' ) );
			register_deactivation_hook( $this->file, array( $this, 'deactivation' ) );
		}

		if ( ! get_option( WDS_SLUG . '_installed' ) ) {
			update_option( WDS_SLUG . '_installed', true );
		}

		if ( ! get_option( WDS_SLUG . '_version' ) ) {
			update_option( WDS_SLUG . '_version', WDS_VERSION );
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'weddingsaas', false, dirname( $this->base ) . '/languages/' );
	}

	/**
	 * Bootstrap everything.
	 */
	public function bootstrap() {
		WeddingSaas::instance( $this->file );
	}

	/**
	 * Activation function fires when the plugin is activated.
	 */
	public function activation() {
		$this->bootstrap();

		wds_install();
	}

	/**
	 * Deactive function fires when the plugin is being deactivated.
	 */
	public function deactivation() {
		delete_option( WDS_SLUG . '_installed' );
		delete_option( WDS_SLUG . '_version' );

		wp_clear_scheduled_hook( 'wds/cron/15minutes' );
		wp_clear_scheduled_hook( 'wds/cron/hourly' );
		wp_clear_scheduled_hook( 'wds/cron/daily' );
		wp_clear_scheduled_hook( 'wds/cron/weekly' );
	}
}
