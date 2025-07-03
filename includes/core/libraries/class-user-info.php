<?php
/**
 * WeddingSaas User Info.
 *
 * Provides user information such as user agent, IP address, platform, browser, and device type.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Core/Libraries
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * WDS_User_Info Class.
 *
 * This class contains methods to retrieve various pieces of information about the user.
 */
class WDS_User_Info {

	/**
	 * Get the user agent string.
	 *
	 * @return string The user agent string.
	 */
	public static function get_user_agent() {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	/**
	 * Get the IP address of the user.
	 *
	 * @return string The IP address of the user.
	 */
	public static function get_ip() {
		$ipaddress = '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return sanitize_text_field( $ipaddress );
	}

	/**
	 * Get the operating system platform of the user.
	 *
	 * @return string The operating system platform.
	 */
	public static function get_platform() {
		$user_agent  = self::get_user_agent();
		$os_platform = 'Unknown OS Platform';
		$os_array    = array(
			'/windows nt 11.0/i'    => 'Windows 11',
			'/windows nt 10.0/i'    => 'Windows 10',
			'/windows nt 6.3/i'     => 'Windows 8.1',
			'/windows nt 6.2/i'     => 'Windows 8',
			'/windows nt 6.1/i'     => 'Windows 7',
			'/windows nt 6.0/i'     => 'Windows Vista',
			'/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     => 'Windows XP',
			'/windows xp/i'         => 'Windows XP',
			'/windows nt 5.0/i'     => 'Windows 2000',
			'/windows me/i'         => 'Windows ME',
			'/win98/i'              => 'Windows 98',
			'/win95/i'              => 'Windows 95',
			'/win16/i'              => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i'        => 'Mac OS 9',
			'/linux/i'              => 'Linux',
			'/ubuntu/i'             => 'Ubuntu',
			'/iphone/i'             => 'iPhone',
			'/ipod/i'               => 'iPod',
			'/ipad/i'               => 'iPad',
			'/android/i'            => 'Android',
			'/blackberry/i'         => 'BlackBerry',
			'/webos/i'              => 'Mobile',
			'/windows phone/i'      => 'Windows Phone',
			'/kindle/i'             => 'Kindle',
			'/silk/i'               => 'Silk',
			'/chromeos/i'           => 'Chrome OS',
		);

		foreach ( $os_array as $regex => $value ) {
			if ( preg_match( $regex, $user_agent ) ) {
				$os_platform = $value;
				break;
			}
		}

		return $os_platform;
	}

	/**
	 * Get the browser of the user.
	 *
	 * @return string The browser name and version.
	 */
	public static function get_browser() {
		$user_agent = self::get_user_agent();
		$browser    = 'Unknown Browser';
		$pattern    = '/(MSIE|(?!Gecko.+)Firefox|(?!AppleWebKit.+Chrome.+)Safari|(?!AppleWebKit.+)Chrome|AppleWebKit(?!.+Chrome|.+Safari)|Gecko(?!.+Firefox))(?: |\/)([\d\.apre]+)/';

		preg_match( $pattern, $user_agent, $matches );

		if ( isset( $matches[0] ) ) {
			$browser = $matches[0];
		}

		return $browser;
	}

	/**
	 * Get the type of device the user is using.
	 *
	 * @return string The type of device (Tablet, Mobile, or Desktop).
	 */
	public static function get_device() {
		$tablet_browser = 0;
		$mobile_browser = 0;

		if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) {
			++$tablet_browser;
		}

		if ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) {
			++$mobile_browser;
		}

		if ( ( strpos( strtolower( $_SERVER['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) > 0 ) || ( ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) || isset( $_SERVER['HTTP_PROFILE'] ) ) ) ) {
			++$mobile_browser;
		}

		$mobile_ua     = strtolower( substr( self::get_user_agent(), 0, 4 ) );
		$mobile_agents = array(
			'w3c ',
			'acs-',
			'alav',
			'alca',
			'amoi',
			'audi',
			'avan',
			'benq',
			'bird',
			'blac',
			'blaz',
			'brew',
			'cell',
			'cldc',
			'cmd-',
			'dang',
			'doco',
			'eric',
			'hipt',
			'inno',
			'ipaq',
			'java',
			'jigs',
			'kddi',
			'keji',
			'leno',
			'lg-c',
			'lg-d',
			'lg-g',
			'lge-',
			'maui',
			'maxo',
			'midp',
			'mits',
			'mmef',
			'mobi',
			'mot-',
			'moto',
			'mwbp',
			'nec-',
			'newt',
			'noki',
			'palm',
			'pana',
			'pant',
			'phil',
			'play',
			'port',
			'prox',
			'qwap',
			'sage',
			'sams',
			'sany',
			'sch-',
			'sec-',
			'send',
			'seri',
			'sgh-',
			'shar',
			'sie-',
			'siem',
			'smal',
			'smar',
			'sony',
			'sph-',
			'symb',
			't-mo',
			'teli',
			'tim-',
			'tosh',
			'tsm-',
			'upg1',
			'upsi',
			'vk-v',
			'voda',
			'wap-',
			'wapa',
			'wapi',
			'wapp',
			'wapr',
			'webc',
			'winw',
			'winw',
			'xda ',
			'xda-',
		);

		if ( in_array( $mobile_ua, $mobile_agents ) ) {
			++$mobile_browser;
		}

		if ( strpos( strtolower( self::get_user_agent() ), 'opera mini' ) > 0 ) {
			++$mobile_browser;
			// Check for tablets on opera mini alternative headers
			$stock_ua = strtolower( isset( $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] ) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : ( isset( $_SERVER['HTTP_DEVICE_STOCK_UA'] ) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : '' ) );
			if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua ) ) {
				++$tablet_browser;
			}
		}

		if ( $tablet_browser > 0 ) {
			return 'Tablet';
		} elseif ( $mobile_browser > 0 ) {
			return 'Mobile';
		} else {
			return 'Desktop';
		}
	}
}
