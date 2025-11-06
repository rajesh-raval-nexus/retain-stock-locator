<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $xmlPath;
$xmlPath = '';

// Always load required files
require_once __DIR__ . '/inc/rsl-assets.php';
require_once __DIR__ . '/inc/rsl-scf.php';
require_once __DIR__ . '/inc/rsl-shortcodes.php';
require_once __DIR__ . '/inc/rsl-helper.php';
require_once __DIR__ . '/inc/rsl-ajax-functions.php';
require_once __DIR__ . '/inc/shop-detail-function.php';

// Always safe to register these
rsl_assets_init();
rsl_scf_register_hooks();
rsl_register_shortcodes();

// Check XML path only after plugins_loaded
add_action( 'plugins_loaded', function() {
    global $xmlPath;

    $xmlPath = get_field( 'xml_file_url', 'option' );

    if ( empty( $xmlPath ) ) {
        // Admin notice if missing
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong><?php esc_html_e( 'Retain Stock Locator:', 'retain-stock-locator' ); ?></strong>
                    <?php esc_html_e( 'Please set your XML File URL in plugin settings (SCF Options) to enable Stock Locator functionality.', 'retain-stock-locator' ); ?>
                </p>
            </div>
            <?php
        });
        return;
    }

});

// Add rewrite rules
add_action('init', 'rsl_add_rewrite_rules');
function rsl_add_rewrite_rules() {
    // Get the selected page ID from ACF Options
    $page_id = get_field('select_stock_locator_page', 'option');


    // Only proceed if a valid page is selected
    if (empty($page_id) || !is_numeric($page_id)) {
        return;
    }

    // Get the page slug from the ID
    $page_slug = get_post_field('post_name', $page_id);

    if (empty($page_slug)) {
        return;
    }

    // Add the rewrite rule
    add_rewrite_rule(
        '^' . $page_slug . '/(.+?)/?$',
        'index.php?pagename=' . $page_slug . '&rsl_filter_path=$matches[1]',
        'top'
    );
}

// Register the query var
function rsl_query_vars($vars) {
    $vars[] = 'rsl_filter_path';
    return $vars;
}
add_filter('query_vars', 'rsl_query_vars');