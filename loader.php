<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Always load required files
require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/includes/scf.php';
require_once __DIR__ . '/includes/shortcodes.php';
require_once __DIR__ . '/includes/helper.php';
require_once __DIR__ . '/includes/api.php';

// Always safe to register these
rsl_assets_register();
rsl_scf_register_hooks();
rsl_register_shortcodes();

// Check XML path only after plugins_loaded
add_action( 'plugins_loaded', function() {

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

    // Initialize helper + API only when XML path is available
    rsl_helper_init( $xmlPath );
    rsl_api_init( $xmlPath );

});
