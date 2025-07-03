<?php

namespace JFB_Modules\Option_Query;

use JFB_Modules\Option_Query\Interfaces\Option_Query_It;
use JFB_Modules\Option_Query\Traits\Option_Query_Trait;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Terms_Query Class.
 *
 * @package JFB_Modules\Option_Query
 */
class Terms_Query implements Option_Query_It {

	use Option_Query_Trait;

	/**
	 * Returns the item ID for terms.
	 *
	 * @return string
	 */
	public function rep_item_id() {
		return 'terms';
	}

	/**
	 * Fetches terms based on query parameters.
	 *
	 * Filters terms according to custom logic for specific pages and taxonomies
	 * and yields filtered term data.
	 *
	 * @return \Generator Yields an array with term data
	 */
	public function fetch(): \Generator {
		$taxonomy = $this->get_query( 'taxonomy' );
		if ( ! $taxonomy ) {
			return;
		}

		if ( ! $this->has_query( 'hide_empty' ) ) {
			$this->set_query( 'hide_empty', false );
		}

		$terms = get_terms(
			apply_filters_deprecated(
				'jet-form-builder/render-choice/query-options/terms',
				array( $this->get_query_params(), $this->get_settings() ),
				'3.3.1',
				'jet-form-builder/option-query/set-in-block'
			)
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		// Custom filter for WeddingSaas on dashboard/invitation/edit page.
		if ( wds_is_page() == 'dashboard/invitation/edit' ) {
			// Filter terms for category taxonomy
			if ( 'category' == $taxonomy ) {
				foreach ( $terms as $term ) {
					$tax = wds_term_meta( $term->term_id, '_template' );
					// Filter terms by theme and membership if applicable
					if ( $tax ) {
						$term_membership = wds_term_meta( $term->term_id, '_membership' ) ? wds_term_meta( $term->term_id, '_membership' ) : array();
						if ( in_array( wds_post_membership(), $term_membership ) || empty( $term_membership ) ) {
							yield $this->prepare_term_item( $term );
						}
					}
				}
			} else {
				// Exclude parent subcategories if not using category taxonomy
				$parent_subcategory_ids = $this->get_parent_subcategory_ids( $terms );
				foreach ( $terms as $term ) {
					$term_membership = wds_term_meta( $term->term_id, '_membership' ) ? wds_term_meta( $term->term_id, '_membership' ) : array();
					// Filter by membership and exclude parent categories
					if ( ! in_array( $term->term_id, $parent_subcategory_ids ) &&
						( in_array( wds_post_membership(), $term_membership ) || empty( $term_membership ) ) ) {
						yield $this->prepare_term_item( $term );
					}
				}
			}
		} else {
			// Default handling for terms
			foreach ( $terms as $term ) {
				yield $this->prepare_term_item( $term );
			}
		}
	}

	/**
	 * Prepares a term item for output based on settings.
	 *
	 * @param object $term Term object.
	 * @return array Prepared term item data.
	 */
	private function prepare_term_item( $term ): array {
		$item = array(
			'object_id' => $term->term_id,
			'value'     => $term->term_id,
			'label'     => apply_filters( 'jet-form-builder/render-choice/label/terms', $term->name, $term ),
		);

		$value_from = $this->get_setting( 'value_from' );

		if ( ! empty( $value_from ) ) {
			if ( isset( $term->$value_from ) ) {
				$item['value'] = $term->$value_from;
			} else {
				$item['value'] = get_term_meta( $term->term_id, $value_from, true );
			}
		}

		$calc_from = $this->get_setting( 'calc_from' );

		if ( ! empty( $calc_from ) ) {
			$item['calculate'] = get_term_meta( $term->term_id, $calc_from, true );
		}

		return $item;
	}

	/**
	 * Gets IDs of terms that have children in a taxonomy.
	 *
	 * @param array $terms Array of term objects.
	 * @return array IDs of terms that have children.
	 */
	private function get_parent_subcategory_ids( $terms ): array {
		$parent_ids = array();

		foreach ( $terms as $term ) {
			if ( is_object( $term ) ) {
				$children = get_term_children( $term->term_id, $term->taxonomy );
				if ( ! empty( $children ) ) {
					$parent_ids[] = $term->term_id;
				}
			}
		}

		return $parent_ids;
	}
}
