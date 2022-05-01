<?php
/**
 * Aquila Features Plugin
 *
 * @package aquila-features
 * @author  Imran Sayed
 *
 * @wordpress-plugin
 * Plugin Name:       Aquila Features
 * Plugin URI:        https://codeytek.com/aquila-features/
 * Description:       Adds Gutenberg Blocks.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Imran Sayed
 * Author URI:        https://codeytek.com/about/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aquila-features
 * Domain Path:       /languages
 */

/**
 * Bootstrap the plugin.
 */
require_once 'vendor/autoload.php';

use AquilaFeatures\Plugin;

if ( class_exists( 'AquilaFeatures\Plugin' ) ) {
	$the_plugin = new Plugin();
}

register_activation_hook( __FILE__, [ $the_plugin, 'activate' ] );

register_deactivation_hook( __FILE__, [ $the_plugin, 'deactivate' ] );
