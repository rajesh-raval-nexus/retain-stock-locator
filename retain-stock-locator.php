<?php
/**
 * Plugin Name: Retain Stock Locator
 * Description: A stock locator plugin that imports XML feed and displays listings with filters & enquiry forms.
 * Version: 1.0.0
 * Author: Retain Media
 * Text Domain: retain-stock-locator
 * Domain Path: /languages
 * Requires Plugins: secure-custom-fields
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define plugin constants
 */
define( 'RSL_VERSION', '1.0.0' );
define( 'RSL_PLUGIN_FILE', __FILE__ );
define( 'RSL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RSL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once RSL_PLUGIN_DIR . 'loader.php';

