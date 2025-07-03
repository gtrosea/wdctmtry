<?php
/**
 * WeddingSaas Database.
 *
 * This class handles the get, add, update, and delete database operations for different models.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WDS_Database Class.
 *
 * This class provides methods to interact with the database for models.
 */
class WDS_Database {

	/**
	 * Get the valid keys for database operations.
	 *
	 * @return array List of valid keys.
	 */
	private function key() {
		return array(
			'product',
			'coupon',
			'code',
			'invoice',
			'order',
			'checkout',
			'affiliate',
			'commission',
			'withdraw',
			'client',
			'income',
			'replica',
		);
	}

	/**
	 * Generate string.
	 *
	 * @param int $length The length string.
	 * @return string The generate string.
	 */
	public function generate( $length = 10 ) {
		return substr( base_convert( sha1( uniqid( microtime( true ), true ) ), 16, 36 ), 0, intval( $length ) );
	}

	/**
	 * Retrieve a record from the database based on key and ID.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param int    $id  The ID of the record to retrieve.
	 * @return mixed The retrieved object if found, or false if not found or invalid input.
	 */
	public function get( $key = false, $id = false ) {
		$found = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $id ) {
			return $found;
		}

		if ( is_object( $id ) && isset( $id->ID ) ) {
			$id = $id->ID;
		} elseif ( wds_check_array( $id, true ) && isset( $id['ID'] ) ) {
			$id = $id['ID'];
		} elseif ( is_int( $id ) || is_string( $id ) ) {
			$id = $id;
		} else {
			return $found;
		}

		$data_id = intval( $id );
		$query   = "WHERE ID = '$data_id'";

		switch ( $key ) {
			case 'product':
				$found = WDS\Models\Product::query( $query )->first();
				break;

			case 'coupon':
				$found = WDS\Models\Coupon::query( $query )->first();
				break;

			case 'code':
				$found = WDS\Models\Coupon_Code::query( "WHERE code_id = '$data_id'" )->first();
				break;

			case 'invoice':
				$found = WDS\Models\Invoice::query( $query )->first();
				break;

			case 'order':
				$found = WDS\Models\Order::query( $query )->first();
				break;

			case 'checkout':
				$found = WDS\Models\Checkout::query( $query )->first();
				break;

			case 'affiliate':
				$found = WDS\Models\Affiliate::query( $query )->first();
				break;

			case 'commission':
				$found = WDS\Models\Commission::query( $query )->first();
				break;

			case 'withdraw':
				$found = WDS\Models\Commission_Withdrawal::query( $query )->first();
				break;

			case 'client':
				$found = WDS\Models\Client::query( $query )->first();
				break;

			case 'income':
				$found = WDS\Models\Income::query( $query )->first();
				break;

			case 'replica':
				$found = WDS\Models\Replica::query( $query )->first();
				break;
		}

		if ( 'code' == $key ) {
			return $found && $found->code_id > 0 ? $found : false;
		}

		return $found && $found->ID > 0 ? $found : false;
	}

	/**
	 * Add a new record to the database.
	 *
	 * @param string $key  The type of data to add.
	 * @param array  $data The data to insert.
	 * @return mixed The id added object if successful, or false if invalid input or failure.
	 */
	public function add( $key = false, $data = array() ) {
		$data_id = false;

		if ( ! $key || ! in_array( $key, $this->key() ) ) {
			return $data_id;
		}

		$data = apply_filters( 'wds_' . $key . '_add_data', $data );

		if ( ! wds_check_array( $data, true ) ) {
			wds_log( 'Data added not found or not an array. Key = ' . $key );
			return $data_id;
		}

		if ( isset( $data['ID'] ) ) {
			unset( $data['ID'] );
		} elseif ( isset( $data['code_id'] ) ) {
			unset( $data['code_id'] );
		}

		$args = array();

		switch ( $key ) {
			case 'product':
				$args  = $data;
				$title = wds_sanitize_data_field( $data, 'title', false );
				$slug  = $title ? strtolower( str_replace( ' ', '-', $title ) ) : $this->generate( 8 );

				$product_args = array(
					'slug'   => $slug,
					'status' => wds_sanitize_data_field( $data, 'status', 'active' ),
				);

				foreach ( WDS\Models\Product::get_columns() as $column => $type ) {
					if ( in_array( $column, array( 'ID', 'slug', 'status' ) ) ) {
						unset( $args[ $column ] );
						continue;
					}

					if ( isset( $args[ $column ] ) ) {
						$product_args[ $column ] = $args[ $column ];
						unset( $args[ $column ] );
					}
				}
				$data_id = WDS\Models\Product::data( $product_args )->create();
				break;

			case 'coupon':
				$coupon_args = array( 'status' => wds_sanitize_data_field( $data, 'status', 'active' ) );
				foreach ( WDS\Models\Coupon::get_columns() as $column => $type ) {
					if ( in_array( $column, array( 'ID', 'status' ) ) ) {
						continue;
					}

					if ( isset( $data[ $column ] ) ) {
						$coupon_args[ $column ] = $data[ $column ];
						unset( $data[ $column ] );
					}
				}

				if ( ! isset( $coupon_args['products'] ) || empty( $coupon_args['products'] ) ) {
					$coupon_args['products'] = 'NULL';
				}

				$data_id = WDS\Models\Coupon::data( $coupon_args )->create();
				break;

			case 'code':
				foreach ( WDS\Models\Coupon_Code::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Coupon_Code::data( $args )->create();
				break;

			case 'invoice':
				foreach ( WDS\Models\Invoice::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Invoice::data( $args )->create();
				break;

			case 'order':
				$order_args = array( 'status' => wds_sanitize_data_field( $data, 'status', 'inactive' ) );
				foreach ( WDS\Models\Order::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$order_args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Order::data( $order_args )->create();
				break;

			case 'checkout':
				foreach ( WDS\Models\Checkout::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Checkout::data( $args )->create();
				break;

			case 'affiliate':
				foreach ( WDS\Models\Affiliate::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Affiliate::data( $args )->create();
				break;

			case 'commission':
				foreach ( WDS\Models\Commission::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Commission::data( $args )->create();
				break;

			case 'withdraw':
				foreach ( WDS\Models\Commission_Withdrawal::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Commission_Withdrawal::data( $args )->create();
				break;

			case 'client':
				foreach ( WDS\Models\Client::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Client::data( $args )->create();
				break;

			case 'income':
				foreach ( WDS\Models\Income::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Income::data( $args )->create();
				break;

			case 'replica':
				foreach ( WDS\Models\Replica::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$data_id = WDS\Models\Replica::data( $args )->create();
				break;
		}

		if ( is_wp_error( $data_id ) ) {
			wds_log( 'Failed add ' . $key . ' to database. Error: ' . $data_id->get_error_message() );
			return $data_id;
		}

		if ( 'product' == $key ) {
			$this->handle_product_meta( $data_id, $args, $data );
		}

		return $data_id;
	}

	/**
	 * Update a record in the database.
	 *
	 * @param string $key  The type of data to update.
	 * @param array  $data The data to update.
	 * @return bool False if invalid input, true if update logic is implemented correctly.
	 */
	public function update( $key = false, $data = array() ) {
		$updated = false;

		if ( ! $key || ! in_array( $key, $this->key() ) ) {
			return $updated;
		}

		$data = apply_filters( 'wds_' . $key . '_update_data', $data );

		if ( ! wds_check_array( $data, true ) ) {
			wds_log( 'Data not found or not an array. Key = ' . $key );
			return $updated;
		}

		$data_id = 0;

		if ( 'code' == $key && isset( $data['code_id'] ) ) {
			$data_id = intval( $data['code_id'] ?? 0 );
			unset( $data['code_id'] );
		} elseif ( isset( $data['ID'] ) ) {
			$data_id = intval( $data['ID'] ?? 0 );
			unset( $data['ID'] );
		}

		if ( $data_id <= 0 ) {
			wds_log( 'ID not found in array data. Key = ' . $key );
			return $updated;
		}

		$args = array();

		switch ( $key ) {
			case 'product':
				$product_args = array();

				$args = $data;
				foreach ( WDS\Models\Product::get_columns() as $column => $type ) {
					if ( isset( $args[ $column ] ) ) {
						$product_args[ $column ] = $args[ $column ];
						unset( $args[ $column ] );
					}
				}

				if ( ! isset( $args['updated_at'] ) ) {
					$product_args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\Product::data( $product_args )->update( array( 'ID' => $data_id ) );
				break;

			case 'coupon':
				foreach ( WDS\Models\Coupon::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}

				if ( ! isset( $args['products'] ) || empty( $args['products'] ) ) {
					$args['products'] = 'NULL';
				}

				if ( ! isset( $data['updated_at'] ) ) {
					$args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\coupon::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'code':
				foreach ( WDS\Models\Coupon_Code::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Coupon_Code::data( $args )->update( array( 'code_id' => $data_id ) );
				break;

			case 'invoice':
				foreach ( WDS\Models\Invoice::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}

				if ( ! isset( $args['updated_at'] ) ) {
					$args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\Invoice::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'order':
				$order_args = array();

				$args = $data;
				foreach ( WDS\Models\Order::get_columns() as $column => $type ) {
					if ( isset( $args[ $column ] ) ) {
						$order_args[ $column ] = $args[ $column ];
						unset( $args[ $column ] );
					}
				}

				if ( ! isset( $args['updated_at'] ) ) {
					$order_args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\Order::data( $order_args )->update( array( 'ID' => $data_id ) );
				break;

			case 'checkout':
				foreach ( WDS\Models\Checkout::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}

				if ( ! isset( $args['updated_at'] ) ) {
					$args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\Checkout::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'affiliate':
				foreach ( WDS\Models\Affiliate::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Affiliate::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'commission':
				foreach ( WDS\Models\Commission::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Commission::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'withdraw':
				foreach ( WDS\Models\Commission_Withdrawal::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Commission_Withdrawal::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'client':
				foreach ( WDS\Models\Client::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Client::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'income':
				foreach ( WDS\Models\Income::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}
				$updated = WDS\Models\Income::data( $args )->update( array( 'ID' => $data_id ) );
				break;

			case 'replica':
				foreach ( WDS\Models\Replica::get_columns() as $column => $type ) {
					if ( isset( $data[ $column ] ) ) {
						$args[ $column ] = $data[ $column ];
					}
				}

				if ( ! isset( $args['updated_at'] ) ) {
					$args['updated_at'] = gmdate( 'Y-m-d H:i:s' );
				}

				$updated = WDS\Models\Replica::data( $args )->update( array( 'ID' => $data_id ) );
				break;
		}

		if ( is_wp_error( $updated ) ) {
			wds_log( 'Failed update ' . $key . ' to database. Error: ' . $updated->get_error_message() );
			return $updated;
		}

		if ( 'product' == $key && ! isset( $data['nometa'] ) ) {
			$this->handle_product_meta( $data_id, $args, $data );
		}

		return $updated;
	}

	/**
	 * Delete a record from the database.
	 *
	 * @param string $key The type of data to delete.
	 * @param int    $id  The ID of the record to delete.
	 * @return bool True on successful deletion, false if invalid input or failure.
	 */
	public function delete( $key = false, $id = 0 ) {
		$deleted = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || $id <= 0 ) {
			return $deleted;
		}

		$data_id = intval( $id );
		$query   = array( 'ID' => $data_id );

		switch ( $key ) {
			case 'product':
				$deleted = WDS\Models\Product::delete( $query );
				break;

			case 'coupon':
				$deleted = WDS\Models\Coupon::delete( $query );
				break;

			case 'code':
				$deleted = WDS\Models\Coupon_Code::delete( array( 'code_id' => $data_id ) );
				break;

			case 'invoice':
				$deleted = WDS\Models\Invoice::delete( $query );
				break;

			case 'order':
				$deleted = WDS\Models\Order::delete( $query );
				break;

			case 'checkout':
				$deleted = WDS\Models\Checkout::delete( $query );
				break;

			case 'affiliate':
				$deleted = WDS\Models\Affiliate::delete( $query );
				break;

			case 'commission':
				$deleted = WDS\Models\Commission::delete( $query );
				break;

			case 'withdraw':
				$deleted = WDS\Models\Commission_Withdrawal::delete( $query );
				break;

			case 'client':
				$deleted = WDS\Models\Client::delete( $query );
				break;

			case 'income':
				$deleted = WDS\Models\Income::delete( $query );
				break;

			case 'replica':
				$deleted = WDS\Models\Replica::delete( $query );
				break;
		}

		return $deleted;
	}

	/**
	 * Retrieve a record from the database based on key, and query.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param string $query  The query to search.
	 * @return mixed The retrieved object if found, or false if not found or invalid input.
	 */
	public function get_by_query( $key = false, $query = false ) {
		$found = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! $query ) {
			return $found;
		}

		switch ( $key ) {
			case 'product':
				$found = WDS\Models\Product::query( $query )->first();
				break;

			case 'coupon':
				$found = WDS\Models\Coupon::query( $query )->first();
				break;

			case 'code':
				$found = WDS\Models\Coupon_Code::query( $query )->first();
				break;

			case 'invoice':
				$found = WDS\Models\Invoice::query( $query )->first();
				break;

			case 'order':
				$found = WDS\Models\Order::query( $query )->first();
				break;

			case 'checkout':
				$found = WDS\Models\Checkout::query( $query )->first();
				break;

			case 'affiliate':
				$found = WDS\Models\Affiliate::query( $query )->first();
				break;

			case 'commission':
				$found = WDS\Models\Commission::query( $query )->first();
				break;

			case 'withdraw':
				$found = WDS\Models\Commission_Withdrawal::query( $query )->first();
				break;

			case 'client':
				$found = WDS\Models\Client::query( $query )->first();
				break;

			case 'income':
				$found = WDS\Models\Income::query( $query )->first();
				break;

			case 'replica':
				$found = WDS\Models\Replica::query( $query )->first();
				break;
		}

		if ( 'code' == $key ) {
			return $found && $found->code_id > 0 ? $found : false;
		}

		return $found && $found->ID > 0 ? $found : false;
	}

	/**
	 * Retrieve a data active from the database.
	 *
	 * @param string $key The type of data to retrieve.
	 * @param string $sort The data sorting.
	 * @return mixed The retrieved array if found, or false if not found or invalid input.
	 */
	public function get_data_active( $key = false, $sort = 'DESC' ) {
		$found = false;

		if ( ! $key || ! in_array( $key, $this->key() ) || ! in_array( $sort, array( 'DESC', 'ASC' ) ) ) {
			return $found;
		}

		switch ( $key ) {
			case 'product':
				$found = WDS\Models\Product::query( 'WHERE status = "active"' )->order( 'ID', $sort )->get();
				break;

			case 'coupon':
				$found = WDS\Models\Coupon::query( 'WHERE status = "active"' )->order( 'ID', $sort )->get();
				break;

			case 'invoice':
				$found = WDS\Models\Invoice::query( 'WHERE status = "completed"' )->order( 'ID', $sort )->get();
				break;

			case 'order':
				$found = WDS\Models\Order::query( 'WHERE status = "active"' )->order( 'ID', $sort )->get();
				break;

			case 'commission':
				$found = WDS\Models\Commission::query( 'WHERE status = "paid"' )->order( 'ID', $sort )->get();
				break;
		}

		return $found && $found->found() > 0 ? $found : false;
	}

	/**
	 * Handle product meta.
	 *
	 * @param int   $product_id Product ID.
	 * @param array $args Product argumens data.
	 * @param array $data Product meta data.
	 */
	private function handle_product_meta( $product_id, $args, $data ) {
		$is_digital = 'digital' == $data['product_type'] ? true : false;
		if ( $is_digital ) {
			$args['payment_type'] = 'onetime';
		} else {
			$membership_lifetime = wds_sanitize_data_field( $data, 'membership_lifetime', 'no' );
			$membership_type     = wds_sanitize_data_field( $data, 'membership_type', 'trial' );
			$payment_type        = 'yes' === $membership_lifetime || 'trial' === $membership_type ? 'onetime' : 'subscription';

			$args['payment_type']     = $payment_type;
			$args['renew_duration']   = $data['membership_duration'];
			$args['renew_period']     = $data['membership_period'];
			$args['restrict_product'] = isset( $data['restrict_product'] ) ? $data['restrict_product'] : array();
			$args['addon']            = isset( $data['addon'] ) ? $data['addon'] : array();

			if ( 'addon' === $membership_type ) {
				$args['payment_type'] = 'onetime';
			}
		}

		foreach ( $args as $meta_key => $meta_value ) {
			WDS()->meta->update_meta( 'product', $product_id, $meta_key, $meta_value );
		}

		WDS()->meta->delete_meta( 'product', $product_id, 'status' );
		wds_delete_cache( 'wds_product_meta' );
	}

	/**
	 * Retrieve a product from the database or cache.
	 *
	 * @param string $key The key of data to retrieve.
	 * @param int    $value The value of data to retrieve.
	 * @param bool   $single The single or muliple of data to retrieve.
	 * @return mixed The retrieved object if found, or false if not found or invalid input.
	 */
	public function get_product( $key = false, $value = false, $single = false ) {
		if ( ! $key || ! $value ) {
			return false;
		}

		$cache_key    = 'wds_products';
		$all_products = wp_cache_get( $cache_key, 'wds_data' );

		if ( false === $all_products ) {
			$all_products  = WDS\Models\Product::query( '' )->all();
			$products_data = array();
			foreach ( $all_products as $product ) {
				$products_data[ $product->ID ] = $product;
			}

			wp_cache_set( $cache_key, $products_data, 'wds_data', WEEK_IN_SECONDS );
			$all_products = $products_data;
		}

		$filtered_products = array_filter(
			$all_products,
			function ( $product ) use ( $key, $value ) {
				return isset( $product->{$key} ) && $product->{$key} == $value;
			}
		);

		if ( $single ) {
			return ! empty( $filtered_products ) ? reset( $filtered_products ) : false;
		} else {
			return ! empty( $filtered_products ) ? $filtered_products : array();
		}
	}
}
