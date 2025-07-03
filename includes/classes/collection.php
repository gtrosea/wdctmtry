<?php
/**
 * WeddingSaas Collection.
 *
 * A class representing a collection of items with pagination information.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Classes
 */

namespace WDS;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Collection Class.
 */
class Collection implements ArrayAccess, IteratorAggregate {

	/**
	 * @var int $count The number of items in the collection.
	 */
	public $count = 0;

	/**
	 * @var int $found The total number of items found.
	 */
	public $found = 0;

	/**
	 * @var int $results_per_page The number of results per page.
	 */
	public $results_per_page = 20;

	/**
	 * @var int $current_num_page The current page number.
	 */
	public $current_num_page = 1;

	/**
	 * @var int $max_num_pages The maximum number of pages.
	 */
	public $max_num_pages = 0;

	/**
	 * @var object $pagination The pagination information.
	 */
	public $pagination;

	/**
	 * @var array $items The items in the collection.
	 */
	protected $items = array();

	/**
	 * Collection constructor.
	 *
	 * @param array $items The initial items in the collection.
	 */
	public function __construct( $items = array() ) {
		$this->items = $items;
	}

	/**
	 * Get an iterator for the items in the collection.
	 *
	 * @return ArrayIterator An iterator for the items.
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->items );
	}

	/**
	 * Get the number of items in the collection.
	 *
	 * @return int The number of items.
	 */
	public function count(): int {
		return count( $this->items );
	}

	/**
	 * Get the total number of items found.
	 *
	 * @return int The total number of items found.
	 */
	public function found(): int {
		return $this->found;
	}

	/**
	 * Get the number of results per page.
	 *
	 * @return int The number of results per page.
	 */
	public function results_per_page(): int {
		return $this->results_per_page;
	}

	/**
	 * Get the current page number.
	 *
	 * @return int The current page number.
	 */
	public function current_num_page(): int {
		return $this->current_num_page;
	}

	/**
	 * Get the maximum number of pages.
	 *
	 * @return int The maximum number of pages.
	 */
	public function max_num_pages(): int {
		return $this->max_num_pages;
	}

	/**
	 * Get the pagination information.
	 *
	 * @param bool|string $url The base URL for pagination links.
	 * @return object The pagination information.
	 */
	public function pagination( $url = false ): object {
		if ( $url ) {
			$this->pagination->next_url = esc_url( $url ) . $this->pagination->next_url;
			$this->pagination->prev_url = esc_url( $url ) . $this->pagination->prev_url;
		}

		return $this->pagination;
	}

	/**
	 * Add an item to the collection.
	 *
	 * @param mixed $item The item to add.
	 * @return $this The collection instance.
	 */
	public function add( $item ) {
		$this->items[] = $item;

		return $this;
	}

	/**
	 * Check if an offset exists.
	 *
	 * @param mixed $key The offset to check.
	 * @return bool True if the offset exists, false otherwise.
	 */
	public function offsetExists( $key ): bool {
		return isset( $this->items[ $key ] );
	}

	/**
	 * Get the value at a given offset.
	 *
	 * @param mixed $key The offset to retrieve.
	 * @return mixed The value at the given offset, or null if not set.
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $key ) {
		return $this->items[ $key ] ?? null;
	}

	/**
	 * Set the value at a given offset.
	 *
	 * @param mixed $key The offset to set.
	 * @param mixed $value The value to set.
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $key, $value ): void {
		if ( is_null( $key ) ) {
			$this->items[] = $value;
		} else {
			$this->items[ $key ] = $value;
		}
	}

	/**
	 * Unset the value at a given offset.
	 *
	 * @param mixed $key The offset to unset.
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $key ): void {
		unset( $this->items[ $key ] );
	}
}
