<?php
/**
 * WeddingSaas Data Meta.
 *
 * This class handles the get, add, update, and delete data meta operations for different models.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Meta Class.
 *
 * This class provides methods to interact with the database for models.
 */
class WDS_Meta {

	/**
	 * Get the valid keys for database operations.
	 *
	 * @return array List of valid keys.
	 */
	private function key() {
		return array(
			'product',
			'order',
			'checkout',
		);
	}

	/**
	 * Retrieve a data meta from the database.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param int    $id  The ID of the record to retrieve.
	 * @param string $meta_key The meta name.
	 * @param bool   $single Whether to return a single value.
	 * @param bool   $only Whether to return a all data meta.
	 * @return mixed The retrieved data if found, or false if not found or invalid input.
	 */
	public function get_meta( $key = false, $id = false, $meta_key = false, $single = true, $only = false ) {
		$value = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $id || ! $meta_key ) {
			return $value;
		}

		switch ( $key ) {
			case 'product':
				$query = WDS\Models\Product_Meta::query( 'WHERE product_id = %d AND meta_key = %s', $id, $meta_key );
				break;

			case 'order':
				$query = WDS\Models\Order_Meta::query( 'WHERE order_id = %d AND meta_key = %s', $id, $meta_key );
				break;

			case 'checkout':
				$query = WDS\Models\Checkout_Meta::query( 'WHERE checkout_id = %d AND meta_key = %s', $id, $meta_key );
				break;
		}

		if ( $single ) {
			$meta = $query->first();
			if ( $meta ) {
				$value = maybe_unserialize( $meta->meta_value );
			}
		} else {
			$meta    = array();
			$is_meta = $query->get();
			if ( $is_meta ) {
				foreach ( $is_meta as $key => $value ) {
					$meta[] = maybe_unserialize( $value->meta_value );
				}
			}

			$value = $meta;
		}

		if ( $only ) {
			$value = $query->first();
		}

		return $value;
	}

	/**
	 * Add a new data meta to the database.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param int    $id  The ID of the record to add.
	 * @param string $meta_key The meta name.
	 * @param mixed  $meta_value The meta value.
	 * @param bool   $unique Whether to enforce unique key.
	 * @return mixed The id added meta if successful, or false if invalid input or failure.
	 */
	public function add_meta( $key = false, $id = false, $meta_key = false, $meta_value = false, $unique = false ) {
		$meta_id = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $id || ! $meta_key || ! $meta_value ) {
			return $meta_id;
		}

		$id = intval( $id );

		switch ( $key ) {
			case 'product':
				$type  = 'product_id';
				$model = 'WDS\Models\Product_Meta';
				break;

			case 'order':
				$type  = 'order_id';
				$model = 'WDS\Models\Order_Meta';
				break;

			case 'checkout':
				$type  = 'checkout_id';
				$model = 'WDS\Models\Checkout_Meta';
				break;
		}

		if ( $unique ) {
			$query = "WHERE $type = '$id' AND meta_key = '$meta_key'";
			$exist = call_user_func( array( $model, 'query' ), $query )->first();
			if ( $exist && isset( $exist->meta_id ) && $exist->meta_id > 0 ) {
				return $meta_id;
			}
		}

		$args = array(
			$type        => $id,
			'meta_key'   => wp_unslash( $meta_key ),
			'meta_value' => wp_unslash( $meta_value ),
		);

		$meta_id = call_user_func( array( $model, 'data' ), $args )->create();

		return $meta_id;
	}

	/**
	 * Update existing data meta to the database.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param int    $id  The ID of the record to update.
	 * @param string $meta_key The meta name.
	 * @param mixed  $meta_value The meta value.
	 * @return mixed The id updated meta if successful, or false if invalid input or failure.
	 */
	public function update_meta( $key = false, $id = false, $meta_key = false, $meta_value = false ) {
		$meta_id = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $id || ! $meta_key ) {
			return $meta_id;
		}

		$id = intval( $id );

		$check_meta = $this->get_meta( $key, $id, $meta_key, true, true );
		if ( ! $check_meta || $check_meta->meta_id <= 0 ) {
			return $this->add_meta( $key, $id, $meta_key, $meta_value );
		}

		switch ( $key ) {
			case 'product':
				$type  = 'product_id';
				$model = 'WDS\Models\Product_Meta';
				break;

			case 'order':
				$type  = 'order_id';
				$model = 'WDS\Models\Order_Meta';
				break;

			case 'checkout':
				$type  = 'checkout_id';
				$model = 'WDS\Models\Checkout_Meta';
				break;
		}

		$args = array(
			$type        => $id,
			'meta_key'   => wp_unslash( $meta_key ),
			'meta_value' => wp_unslash( $meta_value ),
		);

		$meta_id = $check_meta->meta_id;

		call_user_func( array( $model, 'data' ), $args )->update( array( 'meta_id' => $meta_id ) );

		return $meta_id;
	}

	/**
	 * Delete a data meta from database.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param int    $id  The ID of the record to delete.
	 * @param string $meta_key The meta name.
	 * @param mixed  $meta_value The meta value.
	 * @return bool The deleted meta if successful, or false if invalid input or failure.
	 */
	public function delete_meta( $key = false, $id = false, $meta_key = false, $meta_value = false ) {
		$deleted = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $id || ! $meta_key ) {
			return $deleted;
		}

		$id = intval( $id );

		$check_meta = $this->get_meta( $key, $id, $meta_key, true, true );
		if ( ! $check_meta || $check_meta->meta_id <= 0 ) {
			return $deleted;
		}

		switch ( $key ) {
			case 'product':
				$type  = 'product_id';
				$model = 'WDS\Models\Product_Meta';
				break;

			case 'order':
				$type  = 'order_id';
				$model = 'WDS\Models\Order_Meta';
				break;

			case 'checkout':
				$type  = 'checkout_id';
				$model = 'WDS\Models\Checkout_Meta';
				break;
		}

		$args = array(
			$type      => $id,
			'meta_key' => $meta_key,
		);

		if ( $meta_value ) {
			$args['meta_value'] = wp_unslash( $meta_value );
		}

		$deleted = call_user_func( array( $model, 'delete' ), $args );

		return $deleted;
	}

	/**
	 * Retrieve a product meta from the database or cache.
	 *
	 * @param int    $id  The ID of the record to retrieve.
	 * @param string $meta_key The meta name.
	 * @param bool   $single Whether to return a single value.
	 * @return mixed The retrieved data if found, or false if not found or invalid input.
	 */
	public function get_product_meta( $id = false, $meta_key = false, $single = true ) {
		if ( ! $id || ! $meta_key ) {
			return false;
		}

		$cache_key = 'wds_product_meta';
		$all_meta  = wp_cache_get( $cache_key, 'wds_data' );

		if ( false === $all_meta ) {
			$all_meta  = WDS\Models\Product_Meta::query( '' )->all();
			$meta_data = array();
			foreach ( $all_meta as $meta ) {
				$meta_data[ $meta->product_id ][ $meta->meta_key ] = $meta->meta_value;
			}

			wp_cache_set( $cache_key, $meta_data, 'wds_data', WEEK_IN_SECONDS );
			$all_meta = $meta_data;
		}

		$product_meta = $all_meta[ $id ] ?? array();

		if ( $single ) {
			return $meta_key ? ( isset( $product_meta[ $meta_key ] ) ? $product_meta[ $meta_key ] : null ) : reset( $product_meta );
		} else {
			return $meta_key ? ( isset( $product_meta[ $meta_key ] ) ? $product_meta[ $meta_key ] : array() ) : $product_meta;
		}
	}
}
