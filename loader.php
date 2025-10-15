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
