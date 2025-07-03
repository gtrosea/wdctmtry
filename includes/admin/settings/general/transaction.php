<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$text = 'Halo Admin,
    
Saya sudah melakukan pembayaran di [site-name]
    
Invoice: [invoice]
Nama : [name]
Email : [email]
    
Berikut pesanan saya 
    
Produk: [product]
Total: [price]

Mohon segera diproses.

Terima kasih.';

$sk_text = 'Syarat dan ketentuan ini berlaku antara Penyedia Layanan (selanjutnya disebut “Kami”) dan Klien (selanjutnya disebut “Pengguna”). Dengan menggunakan layanan undangan digital, Pengguna menyetujui syarat dan ketentuan yang berlaku.
<h4><strong>1. Jenis Lisensi Penggunaan</strong></h4>
<strong>1.1. Lisensi Trial</strong>
<ul>
 	<li>Lisensi trial bersifat gratis dan terbatas untuk keperluan pribadi non-komersial.</li>
 	<li>Durasi penggunaan undangan versi trial adalah 2 hari.</li>
 	<li>Fitur kustomisasi dan tema terbatas.</li>
 	<li>Tidak diperkenankan untuk penggunaan komersial, termasuk menjual atau mendistribusikan ulang undangan versi trial.</li>
</ul>
<strong>1.2. Lisensi Personal</strong>
<ul>
 	<li>Lisensi personal diperuntukkan bagi Pengguna individu untuk acara pribadi, seperti pernikahan, ulang tahun, khitan atau aqiqah.</li>
 	<li>Lisensi ini tidak dapat digunakan untuk tujuan komersial, penjualan, atau distribusi kepada pihak ketiga.</li>
</ul>
<strong>1.3. Lisensi Reseller</strong>
<ul>
 	<li>Lisensi reseller memberikan hak kepada Pengguna untuk menjual ulang undangan digital yang dibuat menggunakan layanan kami.</li>
 	<li>Minimum harga jual ke end user yaitu Rp 49.000</li>
 	<li>Pengguna Reseller diizinkan untuk melakukan branding ulang undangan digital sesuai kebutuhan bisnis mereka.</li>
 	<li>Lisensi reseller mencakup hak akses penuh ke fitur premium dan semua tema.</li>
 	<li>Pengguna Reseller bertanggung jawab penuh atas penggunaan, pemasaran, dan layanan purna jual kepada klien mereka.</li>
</ul>
<h4><strong>2. Ketentuan Lisensi dan Penggunaan</strong></h4>
<ul>
 	<li>Pengguna dilarang untuk mengalihkan, menjual, atau meminjamkan lisensi yang diberikan tanpa persetujuan tertulis dari Kami.</li>
 	<li>Penggunaan Setiap kuota hanya berlaku untuk satu acara baik itu pernikahan, ulang tahun, khitan maupun aqiqah. Penggunaan lebih dari satu acara memerlukan kuota tambahan.</li>
</ul>
<h4><strong>3. Program Affiliate</strong></h4>
<strong>3.1. Ketentuan Affiliate</strong>
<ul>
 	<li>Program affiliate memberikan komisi 30% bagi Pengguna yang berhasil mengajak klien lain untuk menggunakan layanan kami melalui tautan affiliate yang disediakan.</li>
 	<li>Komisi akan dibayarkan setiap tanggal 1 dan 16</li>
 	<li>Komisi tidak berlaku untuk pembelian yang dilakukan oleh affiliate untuk diri sendiri (self-purchase).</li>
</ul>
<strong>3.2. Pelanggaran Program Affiliate</strong>
<ul>
 	<li>Dilarang melakukan spam atau praktik pemasaran yang tidak etis dalam mempromosikan tautan affiliate.</li>
 	<li>Pengguna affiliate yang terlibat dalam aktivitas penipuan atau praktik tidak sah lainnya akan dihapus dari program dan kehilangan hak atas komisi yang belum dibayarkan.</li>
</ul>
<h4><strong>4. Pembatalan dan Penghentian Lisensi</strong></h4>
<ul>
 	<li>Kami berhak membatalkan atau menangguhkan lisensi Pengguna jika ditemukan pelanggaran terhadap syarat dan ketentuan ini, tanpa pengembalian dana.</li>
 	<li>Pengguna dapat menghentikan langganan atau lisensi kapan saja, namun biaya yang telah dibayarkan tidak akan dikembalikan.</li>
</ul>
Dengan melanjutkan penggunaan layanan kami, Pengguna menyetujui seluruh syarat dan ketentuan yang berlaku.';

CSF::createSection(
	$prefix,
	array(
		'parent' => 'general',
		'title'  => __( 'Transactions', 'wds-notrans' ),
		'fields' => array(
			array(
				'type'  => 'heading',
				'title' => __( 'Kode Unik', 'weddingsaas' ),
			),
			array(
				'id'      => 'unique_number',
				'type'    => 'switcher',
				'title'   => __( 'Kode Unik', 'weddingsaas' ),
				'desc'    => __( 'Aktifkan fitur ini jika Anda ingin mengaktifkan kode unik di pembayaran.', 'weddingsaas' ),
				'default' => wds_v1_option( 'unique_number' ),
			),
			array(
				'id'         => 'unique_number_type',
				'type'       => 'select',
				'title'      => __( 'Tipe Kode Unik', 'weddingsaas' ),
				'options'    => array(
					'+' => __( 'Bertambah', 'weddingsaas' ),
					'-' => __( 'Berkurang', 'weddingsaas' ),
				),
				'default'    => wds_v1_option( 'unique_number_type', '+' ),
				'dependency' => array( 'unique_number', '==', 'true' ),
			),
			array(
				'id'         => 'unique_number_label',
				'type'       => 'text',
				'title'      => __( 'Label Kode Unik', 'weddingsaas' ),
				'default'    => wds_v1_option( 'unique_number_label', 'Kode Unik' ),
				'dependency' => array( 'unique_number', '==', 'true' ),
			),
			array(
				'id'         => 'unique_number_max',
				'type'       => 'number',
				'title'      => __( 'Maksimal Kode Unik', 'weddingsaas' ),
				'default'    => wds_v1_option( 'unique_number_max', 999 ),
				'dependency' => array( 'unique_number', '==', 'true' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Invoice', 'weddingsaas' ),
			),
			array(
				'type'    => 'content',
				'content' => 'Available Shortcodes:<br>{year} = will be replaced with current year<br>{month} = will be replaced with current month<br>{date} = will be replaced with current date<br>{number} = will replaced with the invoice number.',
			),
			array(
				'id'      => 'invoice_format',
				'type'    => 'text',
				'title'   => __( 'Format Invoice', 'weddingsaas' ),
				'default' => wds_v1_option( 'invoice_format', 'INV/{year}{month}{date}/{number}' ),
			),
			array(
				'id'      => 'invoice_due_date',
				'type'    => 'number',
				'title'   => __( 'Tenggat waktu', 'weddingsaas' ),
				'desc'    => 'min 1, max 7.',
				'default' => wds_v1_option( 'invoice_due_date', 3 ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Konfirmasi Pembayaran', 'weddingsaas' ),
			),
			array(
				'id'      => 'payment_confirm',
				'type'    => 'select',
				'title'   => __( 'Tipe Konfirmasi Pembayaran', 'weddingsaas' ),
				'desc'    => __( 'Pilih opsi konfirmasi pembayaran.', 'weddingsaas' ),
				'options' => array(
					'url'      => __( 'URL', 'weddingsaas' ),
					'whatsapp' => __( 'WhatsApp', 'weddingsaas' ),
				),
				'default' => wds_v1_option( 'payment_confirm_conditional', 'whatsapp' ),
			),
			array(
				'id'          => 'payment_confirm_link',
				'type'        => 'text',
				'title'       => __( 'Link Konfirmasi Pembayaran', 'weddingsaas' ),
				'placeholder' => 'https://',
				'default'     => wds_v1_option( 'payment_confirm_link', home_url( '/' ) ),
				'dependency'  => array( 'payment_confirm', '==', 'url' ),
			),
			array(
				'id'          => 'payment_confirm_phone',
				'type'        => 'number',
				'title'       => __( 'Nomor WhatsApp', 'weddingsaas' ),
				'placeholder' => '6285xxxxxx',
				'default'     => wds_v1_option( 'payment_confirm_whatsapp_phone' ),
				'dependency'  => array( 'payment_confirm', '==', 'whatsapp' ),
			),
			array(
				'id'         => 'payment_confirm_text',
				'type'       => 'textarea',
				'title'      => __( 'Kontent WhatsApp', 'weddingsaas' ),
				'desc'       => 'Shortcodes : [name], [email], [invoice], [product], [price], [site-name], [site-url]',
				'attributes' => array( 'rows' => 15 ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'payment_confirm_whatsapp', $text ),
				'dependency' => array( 'payment_confirm', '==', 'whatsapp' ),
			),

			array(
				'type'  => 'heading',
				'title' => __( 'Syarat & Ketentuan', 'weddingsaas' ),
			),
			array(
				'type'    => 'notice',
				'style'   => 'info',
				'content' => __( 'Fitur ini digunakan hanya di halaman checkout', 'weddingsaas' ),
			),
			array(
				'id'      => 'sk',
				'type'    => 'switcher',
				'title'   => __( 'Aktifkan', 'weddingsaas' ),
				'default' => wds_v1_option( 'wds_sk' ),
			),
			array(
				'id'         => 'sk_title',
				'type'       => 'text',
				'title'      => __( 'Judul', 'weddingsaas' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_title', 'Syarat dan Ketentuan' ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
			array(
				'id'         => 'sk_content',
				'type'       => 'wp_editor',
				'title'      => __( 'Trial', 'weddingsaas' ),
				'height'     => '500px',
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_content', $sk_text ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
			array(
				'id'         => 'sk_content_member',
				'type'       => 'wp_editor',
				'title'      => __( 'Member', 'weddingsaas' ),
				'height'     => '500px',
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_content', $sk_text ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
			array(
				'id'         => 'sk_content_reseller',
				'type'       => 'wp_editor',
				'title'      => __( 'Reseller', 'weddingsaas' ),
				'height'     => '500px',
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_content', $sk_text ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
			array(
				'id'         => 'sk_content_digital',
				'type'       => 'wp_editor',
				'title'      => __( 'Digital', 'weddingsaas' ),
				'height'     => '500px',
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_content', $sk_text ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
			array(
				'id'         => 'sk_agree',
				'type'       => 'text',
				'title'      => __( 'Setuju', 'weddingsaas' ),
				'sanitize'   => false,
				'default'    => wds_v1_option( 'wds_sk_agree', 'YA, Saya telah membaca dan menyetujui syarat & ketentuan di atas' ),
				'dependency' => array( 'sk', '==', 'true' ),
			),
		),
	)
);
