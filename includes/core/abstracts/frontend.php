<?php
/**
 * WeddingSaas Frontend.
 *
 * Abstract base class for frontend.
 *
 * @since 1.0.0
 * @package WeddingSaas
 * @subpackage Core/Abstracts
 */

namespace WDS\Abstracts;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Frontend Class.
 */
abstract class Frontend {

	/**
	 * The unique identifier for the frontend instance.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The title of the frontend instance.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * The target for the frontend instance.
	 *
	 * @var string
	 */
	protected $target;

	/**
	 * The template for the frontend instance.
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * Get the frontend ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the frontend title.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get the frontend target.
	 *
	 * @return string
	 */
	public function get_target() {
		return $this->target;
	}

	/**
	 * Get the frontend template.
	 *
	 * @return string
	 */
	public function get_template() {
		return $this->template;
	}

	/**
	 * Process the frontend action.
	 *
	 * @param mixed $request Request data or parameters.
	 * @return void
	 */
	public function process_action( $request ) {
		return;
	}

	/**
	 * Load the frontend instance.
	 *
	 * @return void
	 */
	public function load() {
		return;
	}
}
