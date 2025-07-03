<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$admin_email = get_option( 'admin_email' );

$site_name = get_bloginfo( 'name' );

$shortcode = '<b><h3>Shortcode :</h3></b><p><code>[customer-name]</code> <code>[customer-email]</code> <code>[customer-phone]</code> <code>[site-name]</code> <code>[site-url]</code> <code>[product-name]</code> <code>[product-addon]</code> <code>[invoice-number]</code> <code>[invoice-date]</code> <code>[invoice-amount]</code> <code>[payment-methods]</code> <code>[renew-url]</code> <code>[login-url]</code></p>';

$sc_global = '<code>[site-name]</code> <code>[site-url]</code> <code>[login-url]</code>';

$activation_sc      = "<b><h3>Shortcode :</h3></b><p><code>[customer-name]</code> <code>[customer-email]</code> <code>[customer-phone]</code> <code>[verification-link]</code> $sc_global </p>";
$activation_prefix  = 'user_activation_';
$activation_subject = 'Aktivasi Akun Anda di [site-name]';
$activation_content = 'Halo [customer-name],

Klik link dibawah ini untuk aktivasi akun Anda di [site-name].

<a href="[verification-link]">[verification-link]</a>

Terima kasih.';

$register_sc      = "<b><h3>Shortcode :</h3></b><p><code>[customer-name]</code> <code>[customer-email]</code> <code>[customer-phone]</code> <code>[customer-password]</code> $sc_global </p>";
$register_prefix  = 'user_register_';
$register_subject = '[customer-name], Selamat Pendaftaran Anda Berhasil';
$register_content = 'Halo [customer-name],

Terima kasih telah mendaftar di [site-name]. Kami sangat senang Anda telah bergabung dengan kami.

Berikut adalah rincian pendaftaran Anda:
Nama: [customer-name]
Email: [customer-email]
Nomor Telepon: [customer-phone]

Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan lebih lanjut.

Terima kasih.';

$upgrade_prefix  = 'user_upgrade_';
$upgrade_subject = '[customer-name], Masa Aktif Trial di [site-name] Telah Berakhir';
$upgrade_content = 'Halo [customer-name]

Masa aktif Trial Anda telah berakhir, segera upgrade supaya tetap bisa mengakses semua fitur di [site-name]

Upgrade disini : [site-url]

Terimakasih.';

$client_register_sc      = '<b><h3>Shortcode :</h3></b><p><code>[reseller-name]</code> <code>[replica-login-url]</code> <code>[client-name]</code> <code>[client-email]</code> <code>[client-phone]</code> <code>[client-password]</code> <code>[site-name]</code> <code>[site-url]</code> <code>[login-url]</code></p>';
$client_register_prefix  = 'client_register_';
$client_register_subject = 'Pendaftaran Anda di [site-name] Telah Berhasil';
$client_register_content = 'Halo [client-name],

Akun Anda telah berhasil didaftarkan di [site-name].

Berikut adalah rincian pendaftaran Anda:

Nama: [client-name]
Email: [client-email]
Password : [client-password]

Login : [login-url]

Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan lebih lanjut.

Terima kasih.';

$admin_prefix = 'admin_setting_';

$admin_invoice_completed_prefix  = 'admin_invoice_completed_';
$admin_invoice_completed_subject = 'Order #[invoice-number] dari [customer-name] telah selesai';
$admin_invoice_completed_content = 'Pesanan dari [customer-name] dengan email [customer-email] telah selesai.

Rincian Pembayaran:

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Semoga bertambah lagi.';

$reseller_client_register_sc      = '<b><h3>Shortcode :</h3></b><p><code>[reseller-name]</code> <code>[client-name]</code> <code>[client-email]</code> <code>[client-phone]</code> <code>[client-password]</code> <code>[site-name]</code> <code>[site-url]</code> <code>[login-url]</code></p>';
$reseller_client_register_prefix  = 'reseller_client_register_';
$reseller_client_register_subject = '[reseller-name], Client Anda Berhasil Terdaftar';
$reseller_client_register_content = 'Halo [reseller-name],

Pendaftaran client Anda di [site-name] telah berhasil.

Berikut adalah data client Anda:

Nama: [client-name]
Whatsapp : [client-phone]
Email: [client-email]
Password : [client-password]

Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan lebih lanjut.

Terima kasih.';

$reseller_domain_sc = '<b><h3>Shortcode :</h3></b><p><code>[reseller-name]</code> <code>[domain]</code>' . $sc_global . '</p>';

$reseller_domain_pending_prefix  = 'reseller_domain_pending_';
$reseller_domain_pending_subject = 'Status Custom Domain Anda - [domain]';
$reseller_domain_pending_content = 'Halo [reseller-name]

Terima kasih atas permintaan custom domain [domain]. Saat ini, status permintaan Anda adalah *Pending* dan sedang menunggu konfirmasi.

Kami akan menginformasikan Anda segera setelah dikonfirmasi.

Salam,
Tim Support';

$admin_domain_pending_prefix  = 'admin_domain_pending_';
$admin_domain_pending_subject = 'Request Custom Domain - [domain]';
$admin_domain_pending_content = 'Halo Admin

Ada yang request custom domain

- Nama: [reseller-name]
- Domain: [domain]

Segera konfirmasi.';

$reseller_domain_active_prefix  = 'reseller_domain_active_';
$reseller_domain_active_subject = 'Custom Domain Anda Telah Aktif - [domain]';
$reseller_domain_active_content = 'Halo [reseller-name]

Selamat! Custom domain Anda, [domain], telah dikonfirmasi dan sekarang berstatus *Aktif*. Anda sudah dapat menggunakan domain ini untuk link undangan digital Anda.

Jika ada yang ingin ditanyakan, jangan ragu untuk menghubungi kami.

Salam,
Tim Support';

$affiliate_sc = "<b><h3>Shortcode :</h3></b><p> <code>[affiliate-name]</code> <code>[affiliate-email]</code> <code>[affiliate-phone]</code> <code>[affiliate-commission]</code> <code>[commission-paid]</code> <code>[customer-name]</code> <code>[customer-email]</code> <code>[customer-phone]</code> <code>[product-name]</code> <code>[product-addon]</code> <code>[invoice-number]</code> <code>[invoice-date]</code> <code>[invoice-amount]</code> $sc_global </p>";

$affiliate_new_sales_prefix  = 'affiliate_new_sales_';
$affiliate_new_sales_subject = 'Alhamdulillah, Komisi untuk Anda dari Order #[invoice-number]';
$affiliate_new_sales_content = 'Halo [affiliate-name],

Salah satu pelanggan referensi Anda telah berhasil melakukan pembelian dan pembayaran di [site-name]. Anda telah diberikan penghargaan karena telah memberikan kami pelanggan dengan detail berikut.

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Komisi: [affiliate-commission]

Terima kasih.';

$affiliate_commission_paid_prefix  = 'affiliate_commission_paid_';
$affiliate_commission_paid_subject = 'Alhamdulillah, Komisi Afiliasi Anda Sudah Cair';
$affiliate_commission_paid_content = 'Halo [affiliate-name],

Komisi Anda sebesar [commission-paid] sudah kami cairkan ya.

Silahkan cek rekening banknya ya.

Terima kasih.';

$invoice_place_order_prefix  = 'invoice_place_order_';
$invoice_place_order_subject = 'Invoice Pembayaran #[invoice-number]';
$invoice_place_order_content = 'Halo [customer-name],

Berikut adalah rincian invoice pembayaran Anda:

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Mohon segera lakukan pembayaran supaya kami dapat memproses pesanan Anda. Anda dapat melakukan pembayaran dengan menggunakan metode pembayaran berikut:

[payment-methods]

Jika Anda telah melakukan pembayaran, abaikan pesan ini. Setelah pembayaran berhasil, kami akan mengirimkan konfirmasi pesanan Anda.

Terima kasih.';

$invoice_reminder1_prefix  = 'invoice_reminder1_';
$invoice_reminder1_subject = 'Reminder Pembayaran #[invoice-number]';
$invoice_reminder1_content = 'Halo [customer-name],

Kami ingin memberitahukan bahwa pesanan berikut ini

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Sudah 1 hari [customer-name] belum menyelesaikan pembayaran.

Mohon segera lakukan pembayaran supaya kami dapat memproses pesanan Anda. Anda dapat melakukan pembayaran dengan menggunakan metode pembayaran berikut:
[payment-methods]

Jika Anda telah melakukan pembayaran, abaikan pesan ini. Setelah pembayaran berhasil, kami akan mengirimkan konfirmasi pesanan Anda.

Terima kasih.';

$invoice_reminder2_prefix  = 'invoice_reminder2_';
$invoice_reminder2_subject = 'Reminder Pembayaran #[invoice-number]';
$invoice_reminder2_content = 'Halo [customer-name],

Kami ingin memberitahukan bahwa pesanan berikut ini

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Sudah 2 hari [customer-name] belum menyelesaikan pembayaran.

Mohon segera lakukan pembayaran supaya kami dapat memproses pesanan Anda. Anda dapat melakukan pembayaran dengan menggunakan metode pembayaran berikut:
[payment-methods]

Jika Anda telah melakukan pembayaran, abaikan pesan ini. Setelah pembayaran berhasil, kami akan mengirimkan konfirmasi pesanan Anda.

Terima kasih.';

$invoice_reminder3_prefix  = 'invoice_reminder3_';
$invoice_reminder3_subject = 'Reminder Pembayaran #[invoice-number]';
$invoice_reminder3_content = 'Halo [customer-name],

Kami ingin memberitahukan bahwa pesanan berikut ini

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Sudah 3 hari [customer-name] belum menyelesaikan pembayaran.

Mohon segera lakukan pembayaran supaya kami dapat memproses pesanan Anda. Anda dapat melakukan pembayaran dengan menggunakan metode pembayaran berikut:
[payment-methods]

Jika Anda telah melakukan pembayaran, abaikan pesan ini. Setelah pembayaran berhasil, kami akan mengirimkan konfirmasi pesanan Anda.

Terima kasih.';

$invoice_cancelled_prefix  = 'invoice_cancelled_';
$invoice_cancelled_subject = 'Invoice #[invoice-number] Telah Kami Batalkan';
$invoice_cancelled_content = 'Halo [customer-name],

Pesananmu dengan detail sebagai berikut :

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Telah kami lakukan batalkan ya, mungkin belum berjodoh. Semoga berjodoh untuk produk [site-url] lainnya ya

Terima kasih.';

$invoice_completed_prefix  = 'invoice_completed_';
$invoice_completed_subject = 'Pembayaran Invoice #[invoice-number] Telah Berhasil';
$invoice_completed_content = 'Halo [customer-name],

Pembayaran Anda di [site-name] telah berhasil.

Rincian Pembayaran:

Nomor Invoice: #[invoice-number]
Tanggal Invoice: [invoice-date]
Produk : [product-name]
Jumlah Pembayaran: [invoice-amount]

Terima kasih atas order Anda. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.

Terima kasih.';

$expired_sc = "<b><h3>Shortcode :</h3></b><p><code>[customer-name]</code> <code>[customer-email]</code> <code>[renew-url]</code> $sc_global </p>";

$expired_prefix  = 'expired_';
$expired_subject = '[customer-name], Membership Anda Telah Kadaluarsa';
$expired_content = 'Halo [customer-name]

Membership Anda telah expired, segera perpanjang / upgrade supaya tetap bisa mengakses semua fitur di [site-name]

Perpanjang disini : [renew-url]

Terima kasih.';

$expired_reminder1_prefix  = 'expired_reminder1_';
$expired_reminder1_subject = '[customer-name], Besok Membership Anda Akan Kadaluarsa';
$expired_reminder1_content = 'Halo [customer-name]

Besok membership Anda kadaluarsa, segera perpanjang / upgrade supaya tetap bisa mengakses semua fitur di [site-name]

Perpanjang disini : [renew-url]

Terima kasih.';

$expired_reminder2_prefix  = 'expired_reminder2_';
$expired_reminder2_subject = '[customer-name], 2 Hari Lagi Membership Anda Akan Kadaluarsa';
$expired_reminder2_content = 'Halo [customer-name]

2 hari lagi membership Anda kadaluarsa, segera perpanjang / upgrade supaya tetap bisa mengakses semua fitur di [site-name]

Perpanjang disini : [renew-url]

Terima kasih.';

$expired_reminder3_prefix  = 'expired_reminder3_';
$expired_reminder3_subject = '[customer-name], 3 Hari Lagi Membership Anda Akan Kadaluarsa';
$expired_reminder3_content = 'Halo [customer-name]

3 hari lagi membership Anda kadaluarsa, segera perpanjang / upgrade supaya tetap bisa mengakses semua fitur di [site-name]

Perpanjang disini : [renew-url]

Terima kasih.';

$rsvp_sc      = '<b><h3>Shortcode :</h3></b><p><code>[invitation-name]</code> <code>[customer-name]</code> <code>[guest-name]</code> <code>[date]</code> <code>[comment]</code> <code>[attendance]</code> <code>[guest-total]</code>' . $sc_global . '</p>';
$rsvp_prefix  = 'rsvp_';
$rsvp_subject = 'Ucapan Baru di Undangan [invitation-name]';
$rsvp_content = 'Halo [customer-name]

Anda memiliki ucapan baru pada undangan [invitation-name]

Nama : [guest-name]
Tanggal : [date]
Kehadiran : [attendance]
Jumlah Tamu : [guest-total]

Ucapan : [comment]';
