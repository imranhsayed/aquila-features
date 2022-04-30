<?php
/**
 * Module interface
 *
 * @package aquila-features
 */

namespace AquilaFeatures\Interfaces;

interface Module {

	/**
	 * Register the module in WordPress
	 *
	 * Most of the modules follow fluent interface.
	 */
	public function register_this();
}
