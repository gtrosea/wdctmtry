<?php

namespace WDS\Integrations\Elementor;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Helper Class.
 */
class Helper {

	/**
	 * Check if widget is used in the current page.
	 *
	 * @param string $key The widget name.
	 * @return bool True if the widget is used, false otherwise.
	 */
	public static function is_widget_used( $key ) {
		// Check if we are in a singular post or page
		if ( ! is_singular() ) {
			return false;
		}

		// Get the current Elementor document
		$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );

		if ( ! $document ) {
			return false;
		}

		// Get the widgets in the current document
		$content_data = $document->get_elements_data();
		if ( empty( $content_data ) ) {
			return false;
		}

		// Recursively search for the RSVP widget
		return self::find_widget_in_content( $content_data, $key );
	}

	/**
	 * Recursively search for a widget in Elementor content data.
	 *
	 * @param array  $elements The Elementor elements data.
	 * @param string $widget_name The widget name to search for.
	 * @return bool True if the widget is found, false otherwise.
	 */
	public static function find_widget_in_content( $elements, $widget_name ) {
		foreach ( $elements as $element ) {
			if ( isset( $element['widgetType'] ) && $element['widgetType'] === $widget_name ) {
				return true;
			}

			if ( ! empty( $element['elements'] ) ) {
				if ( self::find_widget_in_content( $element['elements'], $widget_name ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Processes a value based on the specified callback type.
	 *
	 * @since 2.1.2
	 *
	 * @param mixed  $value    The value to process.
	 * @param string $callback The type of processing to apply ('date', 'initial', or 'currency').
	 * @param string $option   Additional option for formatting (e.g., date format for 'date').
	 *
	 * @return mixed The processed value.
	 */
	public static function callback( $value, $callback, $option = '' ) {
		if ( 'date' == $callback && ! empty( $option ) ) {
			$timestamp = is_numeric( $value ) ? $value : strtotime( $value );
			$language  = wds_post_meta( get_the_ID(), '_language' );
			if ( $language && class_exists( '\IntlDateFormatter' ) ) {
				$pattern   = str_replace(
					array( 'l', 'j', 'F', 'Y' ),
					array( 'EEEE', 'd', 'MMMM', 'yyyy' ),
					$option
				);
				$formatter = new \IntlDateFormatter(
					$language,
					\IntlDateFormatter::FULL,
					\IntlDateFormatter::NONE,
					null,
					null,
					$pattern
				);
				$value     = $formatter->format( $timestamp );
			} else {
				$value = date_i18n( $option, $timestamp );
			}
		} elseif ( 'initial' == $callback ) {
			$value = substr( $value, 0, 1 );
		} elseif ( 'currency' == $callback ) {
			$value = wds_convert_money( intval( $value ) );
		}
		return $value;
	}
}
