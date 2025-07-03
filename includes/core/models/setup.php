<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Create custom tables for the plugin in the WordPress database.
 *
 * Each table includes an auto-incrementing ID as the primary key.
 * Additional fields are defined as per the needs of the plugin.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object.
 * @return void
 */
function wds_setup_tables() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$prefix = $wpdb->prefix . WDS_MODEL;

	/* Affiliate Table */
	$table = $prefix . '_affiliate';
	$sql   = "CREATE TABLE {$table} (
        ID bigint NOT NULL AUTO_INCREMENT,
        affiliate_id int(11) NOT NULL,
        visited_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        product_id int(11) NOT NULL,
        uri varchar(255) NOT NULL,
        referer varchar(255) NULL,
        device varchar(255) NULL,
        ip varchar(255) NULL,
        browser varchar(255) NULL,
        platform varchar(255) NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_commission';
	$sql   = "CREATE TABLE {$table} (
        ID bigint NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        invoice_id int(11) NOT NULL,
        order_id int(11) NOT NULL,
        product_id int(11) NOT NULL,
        amount decimal(11, 4) NOT NULL,
        status varchar(255) NOT NULL,
        note longtext NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_commission_withdrawal';
	$sql   = "CREATE TABLE {$table} (
        ID bigint NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        amount decimal(11, 4) NOT NULL,
        method varchar(255) NOT NULL,
        note longtext NULL,
        proof_of_payment int(11) NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Checkout Table */
	$table = $prefix . '_checkout';
	$sql   = "CREATE TABLE {$table} (
        ID int(11) NOT NULL AUTO_INCREMENT,
        user varchar(255) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_checkoutmeta';
	$sql   = "CREATE TABLE {$table} (
        meta_id bigint(11) NOT NULL AUTO_INCREMENT,
        checkout_id int(11) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Coupon Table */
	$table = $prefix . '_coupon';
	$sql   = "CREATE TABLE {$table} (
        ID int(11) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        rebate varchar(255) NOT NULL,
        is_private BOOL DEFAULT 0 NOT NULL,
        max_usage int(11) DEFAULT 0 NOT NULL,
        products longtext NULL,
        users longtext NULL,
        status varchar(255) DEFAULT 'draft' NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_coupon_code';
	$sql   = "CREATE TABLE {$table} (
        code_id int(11) NOT NULL AUTO_INCREMENT,
        coupon_id int(11) NOT NULL,
        user_id int(11) NULL,
        code varchar(255) NOT NULL,
        PRIMARY KEY (code_id)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_coupon_usage';
	$sql   = "CREATE TABLE {$table} (
        usage_id int(11) NOT NULL AUTO_INCREMENT,
        coupon_id int(11) NOT NULL,
        code_id int(11) NOT NULL,
        invoice_id int(11) NOT NULL,
        PRIMARY KEY (usage_id)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Invoice Table */
	$table = $prefix . '_invoice';
	$sql   = "CREATE TABLE {$table} (
        ID bigint NOT NULL AUTO_INCREMENT,
        number varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        summary longtext NOT NULL,
        total decimal(20, 4) NOT NULL,
        gateway varchar(255) NOT NULL,
        reference varchar(255) NULL,
        type varchar(255) NOT NULL DEFAULT 'place_order',
        status varchar(255) NOT NULL,
        due_date_at datetime NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_invoice_order';
	$sql   = "CREATE TABLE {$table} (
        invoice_order_id bigint NOT NULL AUTO_INCREMENT,
        invoice_id int(11) NOT NULL,
        order_id int(11) NOT NULL,
        PRIMARY KEY (invoice_order_id)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Order Table */
	$table = $prefix . '_order';
	$sql   = "CREATE TABLE {$table} (
        ID int(11) NOT NULL AUTO_INCREMENT,
        code varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        product_id int(11) NOT NULL,
        status varchar(255) DEFAULT 'inactive' NOT NULL,
        expired_at datetime NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_order_log';
	$sql   = "CREATE TABLE {$table} (
        log_id bigint(11) NOT NULL AUTO_INCREMENT,
        order_id int(11) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        note longtext NULL,
        PRIMARY KEY (log_id)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_ordermeta';
	$sql   = "CREATE TABLE {$table} (
        meta_id bigint(11) NOT NULL AUTO_INCREMENT,
        order_id int(255) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Product Table */
	$table = $prefix . '_product';
	$sql   = "CREATE TABLE {$table} (
        ID int(11) NOT NULL AUTO_INCREMENT,
        slug varchar(255) NOT NULL,
        title varchar(255) NOT NULL,
        description longtext NULL,
        affiliate BOOL DEFAULT 1 NOT NULL,
        status varchar(255) DEFAULT 'draft' NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_productmeta';
	$sql   = "CREATE TABLE {$table} (
        meta_id int(11) NOT NULL AUTO_INCREMENT,
        product_id int(11) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value longtext NULL,
        PRIMARY KEY (meta_id)
    ) $charset_collate;";
	dbDelta( $sql );

	/* Reseller Table */
	$table = $prefix . '_client';
	$sql   = "CREATE TABLE {$table} (
        ID INT(11) NOT NULL AUTO_INCREMENT,
        reseller_id INT(11) NOT NULL,
        client_id INT(11) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );

	$table = $prefix . '_income';
	$sql   = "CREATE TABLE {$table} (
        ID INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        data_id INT(11) NOT NULL,
        type VARCHAR(255) NOT NULL,
        price decimal(11, 4) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";
	dbDelta( $sql );
}
