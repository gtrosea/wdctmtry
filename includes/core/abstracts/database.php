<?php
/**
 * WeddingSaas Database.
 *
 * Abstract base class for database models.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Core/Abstracts
 */

namespace WDS\Abstracts;

use WDS\Builder;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Database Class.
 */
abstract class Database {

	/**
	 * @var string $table The database table name.
	 */
	protected $table;

	/**
	 * @var array $columns The columns in the database table.
	 */
	protected $columns;

	/**
	 * @var array $attributes The attributes of the model.
	 */
	protected $attributes = array();

	/**
	 * Magic static method to handle dynamic static method calls.
	 *
	 * @param string $method The method name being called.
	 * @param array  $parameters The parameters passed to the method.
	 * @return mixed The result of the method call.
	 */
	public static function __callStatic( $method, $parameters ) {
		return ( new static() )->$method( ...$parameters );
	}

	/**
	 * Magic setter method for setting model attributes.
	 *
	 * @param string $name The attribute name.
	 * @param mixed  $value The attribute value.
	 * @return mixed The set value.
	 */
	public function __set( $name, $value ) {
		if ( isset( $this->columns[ $name ] ) ) {
			$type = $this->columns[ $name ];
			if ( 'integer' == $type ) {
				$value = intval( $value );
			} elseif ( 'array' == $type ) {
				$value = \maybe_serialize( $value );
			} elseif ( 'price' == $type ) {
				$value = floatval( $value );
			} elseif ( 'content' == $type ) {
				$value = stripslashes_deep( wp_kses_post( $value ) );
			} else {
				if ( is_array( $value ) ) {
					$value = \maybe_serialize( $value );
				}
				$value = \sanitize_text_field( $value );
			}
		}

		return $this->attributes[ $name ] = $value; // phpcs:ignore
	}

	/**
	 * Magic getter method for getting model attributes.
	 *
	 * @param string $name The attribute name.
	 * @return mixed|null The attribute value or null if not set.
	 */
	public function __get( $name ) {
		$data = $this->attributes;

		if ( array_key_exists( $name, $data ) ) {
			return maybe_unserialize( $data[ $name ] );
		}

		return null;
	}

	/**
	 * Magic method to check if an attribute is set.
	 *
	 * @param string $name The attribute name.
	 * @return bool True if the attribute is set, false otherwise.
	 */
	public function __isset( $name ) {
		$data = $this->attributes;

		return isset( $data[ $name ] );
	}

	/**
	 * Create a new model instance from an array of data.
	 *
	 * @param array $data The data to initialize the model with.
	 * @return static The model instance.
	 */
	public static function model( $data = array() ) {
		$model = new static();

		$data_output = array();
		$data_array  = (array) $data;
		foreach ( $data_array as $key => $value ) {
			$data_output[ $key ] = maybe_unserialize( $value );
		}

		$model->attributes = $data_output;

		return $model;
	}

	/**
	 * Magic method to handle dynamic method calls.
	 *
	 * @param string $method The method name being called.
	 * @param array  $parameters The parameters passed to the method.
	 * @return mixed The result of the method call.
	 */
	public function __call( $method, $parameters ) {
		$builder = new Builder( $this->table, $this->columns, $this->attributes, array( $this, 'model' ) );
		return $builder->$method( ...$parameters );
	}
}
