<?php
/**
 * WeddingSaas Request.
 *
 * This class handles incoming HTTP requests, including parsing JSON input, URL parameters, and handling nonce verification.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

namespace WDS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Request Class.
 */
class Request {

	/**
	 * The request data that contains all merged inputs from the request.
	 *
	 * @var array $data The merged request data from the input body and URL parameters.
	 */
	public $data;

	/**
	 * Constructor.
	 *
	 * Reads the incoming request body (assumed to be JSON), merges it with URL parameters, and validates nonce if present.
	 * If nonce is valid, stores the parsed request data in the `$data` property.
	 */
	public function __construct() {
		// Get the raw request input body.
		$input   = file_get_contents( 'php://input' );
		$request = json_decode( $input, true );

		// Get URL parameters (GET).
		$url_params = $_GET;

		// Remove 'q' parameter from URL parameters if it exists.
		if ( isset( $url_params['q'] ) ) {
			unset( $url_params['q'] );
		}

		// Merge URL parameters into the request body data.
		$request = wp_parse_args( $url_params, $request );

		// Check if nonce exists and is valid, then store the data.
		if ( isset( $request['nonce'] ) && wds_verify_nonce( $request['nonce'] ) ) {
			$this->data = $request;
		}
	}

	/**
	 * Magic getter method.
	 *
	 * Allows accessing request data as object properties.
	 *
	 * @param string $name The name of the data key.
	 * @return mixed The value associated with the key, or false if not found.
	 */
	public function __get( $name ) {
		if ( isset( $this->data[ $name ] ) ) {
			return $this->data[ $name ];
		}

		return false;
	}

	/**
	 * Magic setter method.
	 *
	 * Allows dynamically setting request data.
	 *
	 * @param string $name  The name of the data key to set.
	 * @param mixed  $value The value to assign to the key.
	 * @return array The updated request data.
	 */
	public function __set( $name, $value ) {
		$request          = $this->attributes;
		$request[ $name ] = $value;

		return $request;
	}
}
