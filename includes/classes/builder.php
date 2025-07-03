<?php
/**
 * WeddingSaas Builder.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

namespace WDS;

use WDS\Collection;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Builder Class.
 */
class Builder {

	/**
	 * @var string $table The table name with prefix.
	 */
	private $table;

	/**
	 * @var array $columns The columns of the table.
	 */
	private $columns;

	/**
	 * @var array $attributes The attributes for the model.
	 */
	private $attributes;

	/**
	 * @var callable $model The model callback function.
	 */
	private $model;

	/**
	 * @var \wpdb $wpdb The WordPress database object.
	 */
	private $wpdb = null;

	/**
	 * @var string $where The WHERE clause of the query.
	 */
	private $where = array();

	/**
	 * @var string $query The SQL query.
	 */
	private $query;

	/**
	 * @var string $order The ORDER BY clause of the query.
	 */
	private $order;

	/**
	 * @var int $offset The offset for the query.
	 */
	private $offset = 0;

	/**
	 * @var int $limit The limit for the query.
	 */
	private $limit = 20;

	/**
	 * @var array $parameters The parameters for the prepared statement.
	 */
	private $parameters;

	/**
	 * @var string $select The SELECT clause of the query.
	 */
	private $select = '*';

	/**
	 * @var array $joins The JOIN clauses of the query.
	 */
	private $joins = array();

	/**
	 * @var string $sql The complete SQL query.
	 */
	private $sql;

	/**
	 * Builder constructor.
	 *
	 * @param string   $table The table name without prefix.
	 * @param array    $columns The columns of the table.
	 * @param array    $attributes The attributes for the model.
	 * @param callable $model The model callback function.
	 */
	public function __construct( $table, $columns, $attributes, $model ) {
		global $wpdb;

		$this->wpdb       = $wpdb;
		$this->table      = $wpdb->prefix . $table;
		$this->columns    = $columns;
		$this->attributes = $attributes;
		$this->model      = $model;

		return $this; // phpcs:ignore
	}

	/**
	 * Get the columns of the table.
	 *
	 * @return array The columns of the table.
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * Set the ORDER BY clause of the query.
	 *
	 * @param mixed ...$parameters The columns and order types.
	 * @return $this
	 */
	public function order( ...$parameters ) {
		$params = array();

		if ( is_array( $parameters[0] ) ) {
			$params = $parameters;
		} else {
			$params[] = array(
				strval( $parameters[0] ),
				isset( $parameters[1] ) ? $parameters[1] : 'ASC',
			);
		}

		$order = array();
		foreach ( $params as $param ) {
			$field   = wds_sanitize_data_field( $param, 0 );
			$type    = wds_sanitize_data_field( $param, 1, 'ASC' );
			$type    = in_array( $type, array( 'ASC', 'DESC' ) ) ? $type : 'ASC';
			$order[] = "$field $type";
		}

		$order = implode( ',', $order );

		$this->order = " ORDER BY $order";

		return $this;
	}

	/**
	 * Set the WHERE clause of the query.
	 *
	 * @param string $column The column name.
	 * @param mixed  $value The value to compare.
	 * @param string $operator The comparison operator.
	 * @return $this
	 */
	public function where( $column, $value, $operator ) {
		$this->where = "WHERE $column $operator '$value'";

		return $this;
	}

	/**
	 * Add an AND condition to the WHERE clause.
	 *
	 * @param string $column The column name.
	 * @param mixed  $value The value to compare.
	 * @param string $operator The comparison operator.
	 * @return $this
	 */
	public function and_where( $column, $value, $operator ) {
		if ( ! $this->where ) {
			$this->where .= <<<SQL
                WHERE $column $operator "$value"
            SQL; // phpcs:ignore
		} else {
			$this->where .= <<<SQL
                AND $column $operator "$value"
            SQL; // phpcs:ignore
		}

		return $this;
	}

	/**
	 * Add an OR condition to the WHERE clause.
	 *
	 * @param string $column The column name.
	 * @param mixed  $value The value to compare.
	 * @param string $operator The comparison operator.
	 * @return $this
	 */
	public function or_where( $column, $value, $operator ) {
		if ( ! $this->where ) {
			$this->where .= <<<SQL
                WHERE $column $operator "$value"
            SQL; // phpcs:ignore
		} else {
			$this->where .= <<<SQL
                OR $column $operator "$value"
            SQL; // phpcs:ignore
		}

		return $this;
	}

	/**
	 * Set a custom SQL query.
	 *
	 * @param string $sql The SQL query.
	 * @param mixed  ...$parameters The parameters for the query.
	 * @return $this
	 */
	public function query( $sql, ...$parameters ) {
		if ( ! $this->query ) {
			$this->query = $sql;
		} else {
			$this->query .= " $sql";
		}

		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * Set the SELECT clause of the query.
	 *
	 * @param mixed ...$selects The columns to select.
	 * @return $this
	 */
	public function select( ...$selects ) {
		$array_select = array();
		foreach ( $selects as $select ) {
			if ( is_string( $select ) ) {
				if ( substr( $select, 0, 8 ) == 'wds' ) {
					$array_select[] = $this->wpdb->prefix . $select;
				} else {
					$array_select[] = $select;
				}
			} elseif ( is_array( $select ) ) {
				foreach ( $select as $s ) {
					if ( substr( $s, 0, 8 ) == 'wds' ) {
						$array_select[] = $this->wpdb->prefix . $s;
					} else {
						$array_select[] = $s;
					}
				}
			} else {
				continue;
			}
		}

		if ( $array_select ) {
			$this->select = implode( ',', $array_select );
			$this->select = str_replace( '{prefix}', $this->wpdb->prefix, $this->select );
		}

		return $this;
	}

	/**
	 * Add a JOIN clause to the query.
	 *
	 * @param string $type The type of join (e.g., 'INNER', 'LEFT').
	 * @param string $table The table to join.
	 * @param array  $match The columns to match on.
	 * @return $this
	 */
	public function join( $type, $table, $match ) {
		$type          = strtoupper( $type );
		$table         = $this->wpdb->prefix . $table;
		$match         = $this->table . '.' . $match[0] . ' ' . $match[2] . ' ' . $table . '.' . $match[1];
		$this->joins[] = "$type JOIN $table ON $match";

		return $this;
	}

	/**
	 * Add a LEFT JOIN clause to the query.
	 *
	 * @param string $join_table The table to join.
	 * @param array  $match The columns to match on.
	 * @return $this
	 */
	public function left_join( $join_table, $match ) {
		$join_table    = $this->wpdb->prefix . $join_table;
		$operator      = wds_sanitize_data_field( $match, 2, '=' );
		$match         = $this->wpdb->prefix . $match[0] . ' ' . $operator . ' ' . $this->wpdb->prefix . $match[1];
		$this->joins[] = "LEFT JOIN $join_table ON $match";

		return $this;
	}

	/**
	 * Add a RIGHT JOIN clause to the query.
	 *
	 * @param string $join_table The table to join.
	 * @param array  $match The columns to match on.
	 * @return $this
	 */
	public function right_join( $join_table, $match ) {
		$join_table    = $this->wpdb->prefix . $join_table;
		$operator      = wds_sanitize_data_field( $match, 2, '=' );
		$match         = $this->wpdb->prefix . $match[0] . ' ' . $operator . ' ' . $this->wpdb->prefix . $match[1];
		$this->joins[] = "RIGHT JOIN $join_table ON $match";

		return $this;
	}

	/**
	 * Set the LIMIT and OFFSET for pagination.
	 *
	 * @param int $limit The number of results per page.
	 * @param int $page The current page number.
	 * @return $this
	 */
	public function paginate( $limit, $page = 1 ) {
		$limit        = intval( $limit );
		$offset       = $limit * ( intval( $page ) - 1 );
		$this->offset = $offset;
		$this->limit  = $limit;

		return $this;
	}

	/**
	 * Get the count of results.
	 *
	 * @param bool $debug If true, print the SQL query.
	 * @return int The count of results.
	 */
	public function count( $debug = false ) {
		$this->prepare( true, true );

		$result = $this->wpdb->get_var( $this->sql ); // phpcs:ignore

		if ( true === $debug ) {
			print_r( $this->sql ); // phpcs:ignore
		}

		return intval( $result );
	}

	/**
	 * Prepare the SQL query.
	 *
	 * @param bool $only_one If true, prepare for a single result.
	 * @param bool $count If true, prepare for a count query.
	 */
	private function prepare( $only_one = false, $count = false ) {
		$join = '';
		if ( $this->joins ) {
			$join = implode( ' ', $this->joins );
		}

		if ( $count ) {
			$sql = "SELECT COUNT(*) FROM $this->table $join";
		} elseif ( $only_one ) {
			$sql = "SELECT $this->select FROM $this->table $join";
		} else {
			$sql = "SELECT SQL_CALC_FOUND_ROWS $this->select FROM $this->table $join";
		}

		if ( $this->where ) {
			$sql .= " $this->where";
		}

		if ( $this->query ) {
			$sql .= " $this->query";
		}

		if ( ! $count && $this->order ) {
			$sql .= " $this->order";
		}

		if ( ! $count ) {
			$sql .= " LIMIT $this->limit OFFSET $this->offset";
		}

		if ( $this->parameters ) {
			$sql = $this->wpdb->prepare( $sql, $this->parameters ); // phpcs:ignore
		}

		$this->sql = $sql;
	}

	/**
	 * Get the all results as a collection.
	 *
	 * @return Collection The results collection.
	 */
	public function all() {
		$this->limit = 999999999;

		return $this->get();
	}

	/**
	 * Get the results as a collection.
	 *
	 * @param bool $debug If true, print the SQL query.
	 * @return Collection The results collection.
	 */
	public function get( $debug = false ) {
		$this->prepare();

		$result = $this->wpdb->get_results( $this->sql ); // phpcs:ignore

		$items = array_map( $this->model, $result );

		$collection                   = new Collection( $items );
		$collection->count            = $this->wpdb->num_rows;
		$collection->found            = $this->wpdb->get_var( 'SELECT FOUND_ROWS()' );
		$collection->results_per_page = $this->limit;
		$collection->current_num_page = isset( $_GET['pg'] ) ? intval( $_GET['pg'] ) : 1;
		$collection->max_num_pages    = ceil( $collection->found / $this->limit );

		$next = intval( $collection->current_num_page ) + 1;
		$prev = intval( $collection->current_num_page ) - 1;

		if ( $collection->current_num_page <= 1 ) {
			$prev = false;
		}

		if ( $collection->current_num_page >= $collection->max_num_pages ) {
			$next = false;
		}

		$collection->pagination = (object) array(
			'show' => $collection->max_num_pages > 1 ? true : false,
			'next' => $next,
			'prev' => $prev,
		);

		if ( true === $debug ) {
			print_r( $this->sql ); // phpcs:ignore
		}

		return $collection;
	}

	/**
	 * Get the first result.
	 *
	 * @param bool $debug If true, print the SQL query.
	 * @return mixed The first result.
	 */
	public function first( $debug = false ) {
		$this->prepare( true );
		$result = $this->wpdb->get_row( $this->sql ); // phpcs:ignore

		if ( true === $debug ) {
			print_r( $this->sql ); // phpcs:ignore
		}

		return call_user_func( $this->model, $result );
	}

	/**
	 * Get the raw results.
	 *
	 * @param bool $debug If true, print the SQL query.
	 * @return array The raw results.
	 */
	public function result( $debug = false ) {
		$this->prepare( true );
		$result = $this->wpdb->get_results( $this->sql ); // phpcs:ignore

		if ( true === $debug ) {
			print_r( $this->sql ); // phpcs:ignore
		}

		return $result;
	}

	/**
	 * Set the data for insertion or update.
	 *
	 * @param array $data The data to set.
	 * @return $this
	 */
	public function data( $data ) {
		$inserted_data = array();
		$formats       = array();
		foreach ( (array) $this->columns as $column => $type ) {

			if ( ! isset( $data[ $column ] ) ) {
				continue;
			}

			$value = $data[ $column ];

			if ( 'integer' == $type ) {
				$value = intval( $value );
			} elseif ( 'array' == $type ) {
				$value = \maybe_serialize( $value );
			} elseif ( 'price' == $type ) {
				$value = floatval( $value );
			} elseif ( 'content' == $type ) {
				$value     = wp_kses_post( $value );
				$formats[] = '%s';
			} elseif ( is_array( $value ) ) {
					$value = \maybe_serialize( $value );
			} else {
				$value = \sanitize_text_field( $value );
			}

			$inserted_data[ $column ] = $value;
		}

		$this->attributes = $inserted_data;

		return $this;
	}

	/**
	 * Insert data into the database.
	 *
	 * @return int|\WP_Error The ID of the inserted row or WP_Error on failure.
	 */
	public function create() {
		if ( empty( $this->columns ) ) {
			return false;
		}

		if ( empty( $this->attributes ) ) {
			return new \WP_Error( 'failed', __( 'Tidak ada data yang dapat dimasukkan', 'weddingsaas' ) );
		}

		$data = array();
		foreach ( $this->attributes as $field => $value ) {
			$data[ $field ] = 'NULL' === $value || 'null' === $value ? null : $value;
		}

		$insert = $this->wpdb->insert( $this->table, $data );

		if ( empty( $insert ) ) {
			return new \WP_Error( 'failed', __( 'Gagal memasukkan data ke dalam database', 'weddingsaas' ) );
		}

		return $this->wpdb->insert_id;
	}

	/**
	 * Update data in the database.
	 *
	 * @param array $where The WHERE clause conditions.
	 * @return int|\WP_Error The number of rows updated or WP_Error on failure.
	 */
	public function update( $where = array() ) {
		if ( empty( $this->columns ) ) {
			return new \WP_Error( 'error', __( 'Kolom basis data kosong', 'weddingsaas' ) );
		}

		if ( ! is_array( $this->attributes ) ) {
			return new \WP_Error( 'error', __( 'Data yang diperbarui tidak boleh kosong', 'weddingsaas' ) );
		}

		if ( ! is_array( $where ) ) {
			return new \WP_Error( 'error', __( 'Tolong berikan kolom dimana', 'weddingsaas' ) );
		}

		foreach ( $where as $key => $value ) {
			if ( ! array_key_exists( $key, $this->columns ) ) {
				return new \WP_Error(
					'failed',
					sprintf(
						/* translators: %s: The key */
						__( '"%s" adalah kolom yang tidak valid', 'weddingsaas' ),
						$key
					)
				);
			}
		}

		$data = array();
		foreach ( $this->attributes as $field => $value ) {
			$data[ $field ] = 'NULL' === $value || 'null' === $value ? null : $value;
		}

		$updated = $this->wpdb->update( $this->table, $data, $where );

		return $updated;
	}

	/**
	 * Delete data from the database.
	 *
	 * @param array $where The WHERE clause conditions.
	 * @return int|\WP_Error The number of rows deleted or WP_Error on failure.
	 */
	public function delete( $where = array() ) {
		if ( empty( $this->columns ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			return new \WP_Error( 'failed', __( 'Tolong berikan kolom dimana', 'weddingsaas' ) );
		}

		foreach ( $where as $key => $value ) {
			if ( ! array_key_exists( $key, $this->columns ) ) {
				return new \WP_Error(
					'failed',
					sprintf(
						/* translators: %s: The key */
						__( '"%s" adalah kolom yang tidak valid', 'weddingsaas' ),
						$key
					)
				);
			}
		}

		return $this->wpdb->delete( $this->table, $where );
	}

	/**
	 * Get the sum of a column.
	 *
	 * @param string $column The column to sum.
	 * @param bool   $debug If true, print the SQL query.
	 * @return float The sum of the column.
	 */
	public function sum( $column, $debug = false ) {
		$column = sanitize_text_field( $column );

		$this->sql = "SELECT SUM($column) AS total FROM {$this->table}";

		if ( $this->joins ) {
			$join       = implode( ' ', $this->joins );
			$this->sql .= " $join";
		}

		if ( $this->where ) {
			$this->sql .= " $this->where";
		}

		if ( $this->parameters ) {
		  $this->sql = $this->wpdb->prepare( $this->sql, $this->parameters ); // phpcs:ignore
		}

		if ( $debug ) {
		  echo "<pre>{$this->sql}</pre>"; // phpcs:ignore
		}

		$result = $this->wpdb->get_var( $this->sql ); // phpcs:ignore

		return $result ? floatval( $result ) : 0;
	}
}
