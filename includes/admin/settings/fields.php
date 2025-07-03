<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prefix = WDS_SLUG . '_settings';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'      => 'Settings',
		'menu_slug'       => 'weddingsaas-settings',
		'menu_type'       => 'menu',
		'menu_capability' => 'manage_options',
		'theme'           => 'light',
		'menu_hidden'     => true,
		'show_bar_menu'   => false,
		'sticky_header'   => false,
		'class'           => 'wds-framework',
		'framework_title' => wp_kses_post( 'WDS Settings<br/><small>Version: ' . WDS_VERSION . '</small>' ),
		'footer_text'     => wp_kses_post( 'The Plugin will Created By <a href="https://pelatform.com" target="_blank">Pelatform Dev</a>' ),
	)
);

CSF::createSection(
	$prefix,
	array(
		'id'    => 'general',
		'title' => __( 'General', 'wds-notrans' ),
	)
);

require_once 'general/main.php';
require_once 'general/currency.php';
require_once 'general/affiliate.php';
require_once 'general/transaction.php';

do_action( 'wds_general_settings', $prefix );

CSF::createSection(
	$prefix,
	array(
		'id'    => 'membership',
		'title' => __( 'Memberships', 'wds-notrans' ),
	)
);

require_once 'membership/main.php';
require_once 'membership/invitation.php';
require_once 'membership/invitation-edit.php';
require_once 'membership/share.php';
require_once 'membership/rsvp.php';
require_once 'membership/client.php';
require_once 'membership/reseller.php';
require_once 'membership/marketing.php';
require_once 'membership/upgrade.php';

do_action( 'wds_membership_settings', $prefix );

CSF::createSection(
	$prefix,
	array(
		'id'    => 'notification',
		'title' => __( 'Notifications', 'wds-notrans' ),
	)
);

require_once 'notification/_content.php';
require_once 'notification/main.php';
require_once 'notification/user.php';
require_once 'notification/client.php';
require_once 'notification/admin.php';
require_once 'notification/reseller.php';
require_once 'notification/affiliate.php';
require_once 'notification/invoice.php';
require_once 'notification/expired.php';
require_once 'notification/rsvp.php';

do_action( 'wds_notification_settings', $prefix );

CSF::createSection(
	$prefix,
	array(
		'id'    => 'gateway',
		'title' => __( 'Payment Gateways', 'wds-notrans' ),
	)
);

require_once 'gateway/general.php';
require_once 'gateway/bank.php';
require_once 'gateway/qris.php';
if ( wds_is_flip() ) {
	require_once 'gateway/flip.php';
}
require_once 'gateway/duitku.php';
require_once 'gateway/tripay.php';
require_once 'gateway/xendit.php';
if ( wds_is_midtrans() ) {
	require_once 'gateway/midtrans.php';
}

do_action( 'wds_gateway_settings', $prefix );

CSF::createSection(
	$prefix,
	array(
		'id'    => 'addon',
		'title' => __( 'Addons', 'wds-notrans' ),
	)
);

if ( wds_is_theme() ) {
	require_once 'addon/theme.php';
}

if ( wds_is_replica() ) {
	require_once 'addon/replica.php';
}

if ( wds_is_buktam() ) {
	require_once 'addon/buktam.php';
}

do_action( 'wds_addon_settings', $prefix );

CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Misc', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'    => 'uninstall_on_delete',
				'type'  => 'switcher',
				'title' => __( 'Hapus Data', 'weddingsaas' ),
				'desc'  => __( 'Aktifkan fitur ini jika Anda ingin menghapus semua data saat plugin dihapus.', 'weddingsaas' ),
			),
			array(
				'title' => __( 'Ekspor Impor Data', 'weddingsaas' ),
				'type'  => 'backup',
			),
		),
	)
);
