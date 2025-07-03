<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Cronjob
wp_clear_scheduled_hook( 'weddingsaas/cron/10minutes' );
wp_clear_scheduled_hook( 'weddingsaas/cron/hourly' );
wp_clear_scheduled_hook( 'weddingsaas/cron/daily' );

// License
delete_option( 'weddingsaas_pro_license' );
delete_transient( 'weddingsaas_pro_license_check' );

// Engine
delete_option( 'wdss_settings' );

// Addons
delete_option( '_enable_addon' );
delete_option( '_weddingsaas_addon' );
