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

// Add this to your plugin activation
function rsl_activate_plugin() {
    rsl_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'rsl_activate_plugin');

// Add this to your plugin deactivation
function rsl_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'rsl_deactivate_plugin');

// Add admin notice to remind users to save permalinks after plugin update
function rsl_admin_notice_rewrite_flush() {
    if (get_transient('rsl_flush_rewrite_rules_flag')) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p><?php _e('Stock Locator: Please go to <strong>Settings > Permalinks</strong> and click "Save Changes" to update your rewrite rules.', 'your-text-domain'); ?></p>
        </div>
        <?php
        delete_transient('rsl_flush_rewrite_rules_flag');
    }
}
add_action('admin_notices', 'rsl_admin_notice_rewrite_flush');

// Set the flag when plugin is updated
function rsl_plugin_updated() {
    set_transient('rsl_flush_rewrite_rules_flag', true, 60);
}
register_activation_hook(__FILE__, 'rsl_plugin_updated');