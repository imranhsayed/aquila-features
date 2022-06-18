<?php
/**
 * Plugin Class.
 *
 * @package aquila-features
 */

namespace AquilaFeatures;

/**
 * Class Plugin.
 */
class Plugin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize plugin
	 */
	private function init() {
		define( 'AQUILA_FEATURES_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
		define( 'AQUILA_FEATURES_PLUGIN_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
		define( 'AQUILA_FEATURES_PLUGIN_BUILD_PATH', AQUILA_FEATURES_PLUGIN_PATH . '/assets/build' );
		define( 'AQUILA_FEATURES_PLUGIN_BUILD_URL', AQUILA_FEATURES_PLUGIN_URL . '/assets/build' );
		define( 'AQUILA_FEATURES_PLUGIN_VERSION', '1.0.0' );

		new Assets();
		new Patterns();
	}
}

