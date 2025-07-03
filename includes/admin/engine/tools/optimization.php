<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

CSF::createSection(
	$prefix,
	array(
		'parent' => 'tools',
		'title'  => __( 'Optimizations', 'wds-notrans' ),
		'fields' => array(
			array(
				'id'    => 'image_resize',
				'type'  => 'switcher',
				'title' => __( 'Auto Resize Image', 'wds-notrans' ),
			),
			array(
				'id'         => 'image_resize_data',
				'type'       => 'fieldset',
				'fields'     => array(
					array(
						'id'      => 'width',
						'type'    => 'text',
						'title'   => __( 'Max Width (px)', 'wds-notrans' ),
						'default' => get_option( '_wds_image_resize_widths', '1920' ),
					),
					array(
						'id'      => 'height',
						'type'    => 'text',
						'title'   => __( 'Max Height (px)', 'wds-notrans' ),
						'default' => get_option( '_wds_image_resize_heights', '1920' ),
					),
				),
				'dependency' => array( 'image_resize', '==', 'true' ),
			),
			array(
				'id'      => 'convert_webp',
				'type'    => 'switcher',
				'title'   => __( 'Convert Image to WebP', 'wds-notrans' ),
				'desc'    => __( 'Konversi Gambar yang Diunggah ke Format WebP.', 'weddingsaas' ),
				'default' => get_option( '_wds_convert_webp' ),
			),
			array(
				'id'         => 'compress_image',
				'type'       => 'switcher',
				'title'      => __( 'Compress Image', 'wds-notrans' ),
				'desc'       => __( 'Fitur ini berjalan jika Anda tidak menggunakan "Convert Image to WebP".', 'weddingsaas' ),
				'default'    => get_option( '_wds_compress_image' ),
				'dependency' => array( 'convert_webp', '==', 'false' ),
			),
			array(
				'id'      => 'remove_metadata',
				'type'    => 'switcher',
				'title'   => __( 'Remove Metadata', 'wds-notrans' ),
				'desc'    => __( 'Ini akan menghapus SEMUA metadata: EXIF, komentar, profil warna, lokasi, dan apa pun yang bukan merupakan data piksel.', 'weddingsaas' ),
				'default' => get_option( '_wds_remove_metadata' ),
			),
			array(
				'id'      => 'autoremove_attachments',
				'type'    => 'switcher',
				'title'   => __( 'Auto Remove Attachments', 'wds-notrans' ),
				'desc'    => __( 'Menghapus semua media yang dilampirkan ke postingan ketika post / undangan dihapus.', 'weddingsaas' ),
				'default' => get_option( '_wds_autoremove_attachments' ),
			),
			array(
				'id'      => 'disable_generated_image',
				'type'    => 'switcher',
				'title'   => __( 'Disable Auto-generated Image Sizes', 'wds-notrans' ),
				'desc'    => __( 'Ketika mengunggah media apa pun, WordPress secara otomatis menghasilkan ukuran ekstra lainnya. Hal ini dapat menghabiskan ruang ekstra di server Anda. Dengan menggunakan fitur ini, Anda dapat menonaktifkan gambar-gambar ekstra tersebut.', 'weddingsaas' ),
				'default' => get_option( '_disable_generated_image' ),
			),
			array(
				'id'          => 'autoreplace_image',
				'type'        => 'textarea',
				'title'       => __( 'Auto Replace Image', 'wds-notrans' ),
				'desc'        => __( 'Masukkan meta field yang ingin Anda gunakan. Pisahkan dengan koma dan tanpa spasi. (Tidak mendukung repeater).', 'weddingsaas' ),
				'placeholder' => 'Ex. _foto_pembuka,_foto_penutup',
				'default'     => get_option( '_autoreplace_image' ),
			),
			array(
				'id'          => 'autoreplace_gallery',
				'type'        => 'textarea',
				'title'       => __( 'Auto Replace Gallery', 'wds-notrans' ),
				'desc'        => __( 'Masukkan meta field yang ingin Anda gunakan. Pisahkan dengan koma dan tanpa spasi. (Tidak mendukung repeater).', 'weddingsaas' ),
				'placeholder' => 'Ex. _gallery',
			),
			array(
				'id'          => 'autoreplace_image_user',
				'type'        => 'textarea',
				'title'       => __( 'Auto Replace Image User', 'wds-notrans' ),
				'desc'        => __( 'Masukkan user meta field yang ingin Anda gunakan. Pisahkan dengan koma dan tanpa spasi. (Tidak mendukung repeater).', 'weddingsaas' ),
				'placeholder' => 'Ex. _branding_logo,_branding_favicon',
			),
			array(
				'id'          => 'autoreplace_gallery_user',
				'type'        => 'textarea',
				'title'       => __( 'Auto Replace Gallery User', 'wds-notrans' ),
				'desc'        => __( 'Masukkan user meta field yang ingin Anda gunakan. Pisahkan dengan koma dan tanpa spasi. (Tidak mendukung repeater).', 'weddingsaas' ),
				'placeholder' => 'Ex. _portfolio',
			),
		),
	)
);
