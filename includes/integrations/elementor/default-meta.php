<?php

namespace WDS\Integrations\Elementor;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Default_Meta Class.
 */
class Default_Meta {

	/** @var array **/
	public static $callback;

	/** @var array **/
	public static $button_size;

	/** @var array **/
	public static $calendar;

	/** @var array **/
	public static $wedding_reseller;

	/** @var array **/
	public static $event_v2;

	/** @var array **/
	public static $event_v2_format;

	/** @var array **/
	public static $gift;

	/** @var array **/
	public static $live;

	/** @var array **/
	public static $teks;

	/** @var array **/
	public static $lovestory;

	/** @var array **/
	public static $nonwedding;

	/**
	 * Init class.
	 */
	public static function init() {
		self::$callback = array(
			'date'     => 'Tanggal',
			'initial'  => 'Inisial',
			'currency' => 'Mata Uang',
		);

		self::$button_size = array(
			'xs' => 'Extra Small',
			'sm' => 'Small',
			'md' => 'Medium',
			'lg' => 'Large',
			'xl' => 'Extra Large',
		);

		self::$calendar = array(
			'_calendar_title'       => 'Title',
			'_calendar_start'       => 'Date Time Start',
			'_calendar_end'         => 'Date Time End',
			'_calendar_location'    => 'Location',
			'_calendar_description' => 'Description',
		);

		self::$wedding_reseller = array(
			'user_id'     => 'User ID',
			'membership'  => 'Membership',
			'name'        => 'Brand Name',
			'link'        => 'Link Logo',
			'description' => 'Description',
			'phone'       => 'Phone',
			'whatsapp'    => 'URL WhatsApp',
			'price'       => 'Invitation Price',
			'instagram'   => 'Instagram',
			'facebook'    => 'Facebook',
			'tiktok'      => 'Tiktok',
			'twitter'     => 'Twitter',
			'youtube'     => 'Youtube',
		);

		self::$event_v2 = array(
			'_nama_acara_1'        => 'Nama Acara 1',
			'_tanggal_acara_1'     => 'Tanggal Acara 1',
			'_waktu_acara_1'       => 'Waktu Acara 1',
			'_lokasi_acara_1'      => 'Lokasi Acara 1',
			'_alamat_acara_1'      => 'Alamat Acara 1',
			'_gmaps_embed_acara_1' => 'Embed Google Maps 1',
			'_gmaps_link_acara_1'  => 'Link Google Maps 1',
			'_acara_kedua'         => 'Aktifkan Acara Kedua ?',
			'_nama_acara_2'        => 'Nama Acara 2',
			'_tanggal_acara_2'     => 'Tanggal Acara 2',
			'_waktu_acara_2'       => 'Waktu Acara 2',
			'_lokasi_acara_2'      => 'Lokasi Acara 2',
			'_alamat_acara_2'      => 'Alamat Acara 2',
			'_gmaps_embed_acara_2' => 'Embed Google Maps 2',
			'_gmaps_link_acara_2'  => 'Link Google Maps 2',
			'_acara_ketiga'        => 'Aktifkan Acara Ketiga ?',
			'_nama_acara_3'        => 'Nama Acara 3',
			'_tanggal_acara_3'     => 'Tanggal Acara 3',
			'_waktu_acara_3'       => 'Waktu Acara 3',
			'_lokasi_acara_3'      => 'Lokasi Acara 3',
			'_alamat_acara_3'      => 'Alamat Acara 3',
			'_gmaps_embed_acara_3' => 'Embed Google Maps 3',
			'_gmaps_link_acara_3'  => 'Link Google Maps 3',
			'_acara_keempat'       => 'Aktifkan Acara Keempat ?',
			'_nama_acara_4'        => 'Nama Acara 4',
			'_tanggal_acara_4'     => 'Tanggal Acara 4',
			'_waktu_acara_4'       => 'Waktu Acara 4',
			'_lokasi_acara_4'      => 'Lokasi Acara 4',
			'_alamat_acara_4'      => 'Alamat Acara 4',
			'_gmaps_embed_acara_4' => 'Embed Google Maps 4',
			'_gmaps_link_acara_4'  => 'Link Google Maps 4',
		);

		self::$event_v2_format = array(
			'l, j F Y' => 'Hari, Tgl Bulan Tahun',
			'j F Y'    => 'Tgl Bulan Tahun',
			'F Y'      => 'Bulan Tahun',
			'l'        => 'Hari',
			'j'        => 'Tanggal',
			'F'        => 'Bulan',
			'Y'        => 'Tahun',
		);

		self::$gift = array(
			'_fitur_kado'              => 'Aktifkan Fitur Kado',
			'_nama_penerima_kado'      => 'Nama Penerima Kado',
			'_alamat_kado'             => 'Alamat',
			'_whatsapp'                => 'WhatsApp Confirmation',
			'_nama'                    => 'Bank Account Name (Listing)',
			'_rekening'                => 'Bank Account Number (Listing)',
			'_nama_pemilik_rekening_1' => 'Nama Pemilik Rekening 1',
			'_no_rekening_1'           => 'No Rekening 1',
			'_nama_bank_1'             => 'Nama Bank 1',
			'_rekening_kedua'          => 'Aktifkan Rekening ke 2?',
			'_nama_pemilik_rekening_2' => 'Nama Pemilik Rekening 2',
			'_no_rekening_2'           => 'No Rekening 2',
			'_nama_bank_2'             => 'Nama Bank 2',
			'_rekening_ketiga'         => 'Aktifkan Rekening ke 3?',
			'_nama_pemilik_rekening_3' => 'Nama Pemilik Rekening 3',
			'_no_rekening_3'           => 'No Rekening 3',
			'_nama_bank_3'             => 'Nama Bank 3',
			'_rekening_keempat'        => 'Aktifkan Rekening ke 4?',
			'_nama_pemilik_rekening_4' => 'Nama Pemilik Rekening 4',
			'_no_rekening_4'           => 'No Rekening 4',
			'_nama_bank_4'             => 'Nama Bank 4',
		);

		self::$live = array(
			'_fitur_live_streaming'     => 'Fitur Live Streaming',
			'_live_streaming_zoom'      => 'Zoom v1',
			'_live_streaming_instagram' => 'Instagram v1',
			'_tombol_live_streaming'    => 'Tombol Live Streaming v2',
			'_link_live_streaming'      => 'Link Live Streaming v2',
		);

		self::$teks = array(
			'_teks_judul'        => 'Judul Undangan',
			'_teks_pembuka'      => 'Pembuka',
			'_teks_mempelai'     => 'Mempelai',
			'_teks_acara'        => 'Acara',
			'_teks_rsvp'         => 'RSVP',
			'_teks_kado_digital' => 'Kado Digital',
			'_teks_penutup'      => 'Penutup',
			'_yang_mengundang'   => 'Yang Mengundang',
		);

		self::$lovestory = array(
			'_judul_cerita_1'     => 'Judul Cerita 1',
			'_tanggal_cerita_1'   => 'Tanggal Cerita 1',
			'_deskripsi_cerita_1' => 'Deskripsi Cerita 1',
			'_love_story_2'       => 'Aktifkan Cerita ke 2',
			'_judul_cerita_2'     => 'Judul Cerita 2',
			'_tanggal_cerita_2'   => 'Tanggal Cerita 2',
			'_deskripsi_cerita_2' => 'Deskripsi Cerita 2',
			'_love_story_3'       => 'Aktifkan Cerita ke 3',
			'_judul_cerita_3'     => 'Judul Cerita 3',
			'_tanggal_cerita_3'   => 'Tanggal Cerita 3',
			'_deskripsi_cerita_3' => 'Deskripsi Cerita 3',
			'_love_story_4'       => 'Aktifkan Cerita ke 4',
			'_judul_cerita_4'     => 'Judul Cerita 4',
			'_tanggal_cerita_4'   => 'Tanggal Cerita 4',
			'_deskripsi_cerita_4' => 'Deskripsi Cerita 4',
		);

		self::$nonwedding = array(
			'_nama_anak'     => 'Nama Anak',
			'_profile'       => 'Profile / Dekripsi',
			'_ultah_ke'      => 'Ultah keberapa',
			'_anak_ke'       => 'Anak keberapa',
			'_nama_ayah'     => 'Nama Ayah',
			'_nama_ibu'      => 'Nama Ibu',
			'_ttl'           => 'Tempat, tanggal lahir',
			'_jam_lahir'     => 'Jam Lahir',
			'_berat_lahir'   => 'Berat Lair',
			'_panjang_lahir' => 'Panjang Lahir',
			'_alamat_lahir'  => 'Alamat Lahiran',
		);
	}
}

Default_Meta::init();
