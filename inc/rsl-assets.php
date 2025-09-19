<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initialize asset loading hooks.
 */
function rsl_assets_init() {
    add_action( 'wp_enqueue_scripts', 'rsl_assets_enqueue_frontend', 999 );
}

/**
 * Enqueue frontend styles and scripts.
 */
function rsl_assets_enqueue_frontend() {
    // Load Font Awesome from CDN (check if already loaded by filename)
    if ( ! rsl_assets_is_style_loaded_by_src( 'font-awesome' ) ) {
        wp_enqueue_style(
            'rsl-font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            [],
            '6.4.0'
        );
    }

    // Bootstrap CSS
    if ( ! rsl_assets_is_style_loaded_by_src( 'bootstrap.min.css' ) ) {
        wp_enqueue_style(
            'rsl-bootstrap',
            RSL_PLUGIN_URL . 'assets/vendor/bootstrap/css/bootstrap.min.css',
            [],
            '5.3.0'
        );
    }

    // Bootstrap Icons
    if ( ! rsl_assets_is_style_loaded_by_src( 'bootstrap-icons' ) ) {
        wp_enqueue_style(
            'rsl-bootstrap-icons',
            RSL_PLUGIN_URL . 'assets/vendor/bootstrap/css/bootstrap-icons.min.css',
            [],
            '1.10.5'
        );
    }

    // Owl Carousel CSS
    if ( ! rsl_assets_is_style_loaded_by_src( 'owl.carousel.min.css' ) ) {
        wp_enqueue_style(
            'rsl-owl-carousel',
            RSL_PLUGIN_URL . 'assets/vendor/owlcarousel/owl.carousel.min.css',
            [],
            '2.3.4'
        );
    }

    // Custom CSS
    wp_enqueue_style(
        'rsl-custom-style',
        RSL_PLUGIN_URL . 'assets/css/style2.css',
        [],
        filemtime( RSL_PLUGIN_DIR . 'assets/css/style2.css' )
    );

    wp_enqueue_style(
        'rsl-responsive-style',
        RSL_PLUGIN_URL . 'assets/css/responsive.css',
        [],
        filemtime( RSL_PLUGIN_DIR . 'assets/css/responsive.css' )
    );

    // =====================
    // Scripts
    // =====================

    wp_enqueue_script( 'jquery' );

    // Popper (Bootstrap dependency)
    if ( ! rsl_assets_is_script_loaded_by_src( 'popper.min.js' ) ) {
        wp_enqueue_script(
            'rsl-popper',
            RSL_PLUGIN_URL . 'assets/vendor/bootstrap/js/popper.min.js',
            [],
            null,
            true
        );
    }

    // Bootstrap JS
    if ( ! rsl_assets_is_script_loaded_by_src( 'bootstrap.min.js' ) ) {
        wp_enqueue_script(
            'rsl-bootstrap',
            RSL_PLUGIN_URL . 'assets/vendor/bootstrap/js/bootstrap.min.js',
            [ 'jquery', 'rsl-popper' ],
            null,
            true
        );
    }

    // Owl Carousel
    if ( ! rsl_assets_is_script_loaded_by_src( 'owl.carousel' ) ) {
        wp_enqueue_script(
            'rsl-owl-carousel',
            RSL_PLUGIN_URL . 'assets/vendor/owlcarousel/owl.carousel.js',
            [ 'jquery' ],
            null,
            true
        );
    }

    // Custom JS
    wp_enqueue_script(
        'rsl-main',
        RSL_PLUGIN_URL . 'assets/js/main.js',
        [ 'jquery' ],
        filemtime( RSL_PLUGIN_DIR . 'assets/js/main.js' ),
        true
    );

    wp_enqueue_script(
        'rsl-custom',
        RSL_PLUGIN_URL . 'assets/js/custom.js',
        [ 'jquery', 'rsl-main', 'rsl-owl-carousel' ],
        filemtime( RSL_PLUGIN_DIR . 'assets/js/custom.js' ),
        true
    );
}

/**
 * Check if style already loaded by partial src match.
 */
function rsl_assets_is_style_loaded_by_src( $needle ) {
    global $wp_styles;
    if ( empty( $wp_styles->registered ) ) return false;

    foreach ( $wp_styles->registered as $style ) {
        if ( strpos( $style->src, $needle ) !== false ) {
            return true;
        }
    }
    return false;
}

/**
 * Check if script already loaded by partial src match.
 */
function rsl_assets_is_script_loaded_by_src( $needle ) {
    global $wp_scripts;
    if ( empty( $wp_scripts->registered ) ) return false;

    foreach ( $wp_scripts->registered as $script ) {
        if ( strpos( $script->src, $needle ) !== false ) {
            return true;
        }
    }
    return false;
}
