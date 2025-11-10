<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initialize asset loading hooks.
 */
function rsl_assets_init() {
    add_action( 'wp_enqueue_scripts', 'rsl_assets_enqueue_frontend', 999 );
}

function rsl_assets_enqueue_frontend() {

    /*cstm css and js*/
    if ( ! rsl_assets_is_script_loaded_by_src( 'lightgallery.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lightgallery-min',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lightgallery.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'lg-thumbnail.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lg-thumbnail',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lg-thumbnail.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'lg-zoom.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lg-zoom',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lg-zoom.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'bundle.js' ) ) {
        wp_enqueue_script(
            'rsl-bundle',
            RSL_PLUGIN_URL . 'assets/vendor/litepicker/bundle.js',
            [ 'jquery' ],
            null,
            true
        );
    }  
    if ( ! rsl_assets_is_script_loaded_by_src( 'timepicki.min.js' ) ) {
        wp_enqueue_script(
            'rsl-timepicki',
            RSL_PLUGIN_URL . 'assets/vendor/timepicki/timepicki.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    /* END cstm css and js*/ 

    // Load Font Awesome from CDN (check if already loaded by filename)    
    wp_enqueue_style(
        'rsl-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );    

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


    /*cstm css and js*/ 
    if ( ! rsl_assets_is_style_loaded_by_src( 'lightgallery-bundle.min.css' ) ) {
        wp_enqueue_style(
            'lightgallery-bundle',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lightgallery-bundle.min.css',
            [],
            '5.3.0'
        );
    }
    if ( ! rsl_assets_is_style_loaded_by_src( 'litepicker.css' ) ) {
        wp_enqueue_style(
            'litepicker',
            RSL_PLUGIN_URL . 'assets/vendor/litepicker/litepicker.css',
            [],
            '1.1.0'
        );
    }

    if ( ! rsl_assets_is_style_loaded_by_src( 'timepicki.min.css' ) ) {
        wp_enqueue_style(
            'timepicki',
            RSL_PLUGIN_URL . 'assets/vendor/timepicki/timepicki.min.css',
            [],
            '5.1.0'
        );
    }
    /* END cstm css and js*/ 

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

    // lightgallery
    if ( ! rsl_assets_is_script_loaded_by_src( 'lightgallery.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lightgallery-min',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lightgallery.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'lg-thumbnail.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lg-thumbnail',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lg-thumbnail.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'lg-zoom.min.js' ) ) {
        wp_enqueue_script(
            'rsl-lg-zoom',
            RSL_PLUGIN_URL . 'assets/vendor/lightgallery/lg-zoom.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }

     // litepicker
    if ( ! rsl_assets_is_script_loaded_by_src( 'bundle.js' ) ) {
        wp_enqueue_script(
            'rsl-bundle',
            RSL_PLUGIN_URL . 'assets/vendor/litepicker/bundle.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    if ( ! rsl_assets_is_script_loaded_by_src( 'timepicki.min.js' ) ) {
        wp_enqueue_script(
            'rsl-timepicki',
            RSL_PLUGIN_URL . 'assets/vendor/timepicki/timepicki.min.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    // Jquery validate
    if ( ! rsl_assets_is_script_loaded_by_src( 'jquery-validate.js' ) ) {
        wp_enqueue_script(
            'rsl-jquery-validate',
            RSL_PLUGIN_URL . 'assets/vendor/jquery-validate/jquery-validate.js',
            [ 'jquery' ],
            null,
            true
        );
    }
    // Main JS
    wp_enqueue_script(
        'rsl-main',
        RSL_PLUGIN_URL . 'assets/js/main.js',
        [ 'jquery' ],
        filemtime( RSL_PLUGIN_DIR . 'assets/js/main.js' ),
        true
    );

    // Custom JS
    wp_enqueue_script(
        'rsl-validation',
        RSL_PLUGIN_URL . 'assets/js/validation.js',
        [ 'jquery', 'rsl-jquery-validate' ],
        filemtime( RSL_PLUGIN_DIR . 'assets/js/validation.js' ),
        true
    );

    // Ajax Functions JS
    if ( ! rsl_assets_is_script_loaded_by_src( 'ajax-functions' ) && is_stock_locator_page() ) {
        wp_enqueue_script(
            'rsl-ajax-functions',
            RSL_PLUGIN_URL . 'assets/js/ajax-functions.js',
            [ 'jquery', 'rsl-custom' ],
            filemtime( RSL_PLUGIN_DIR . 'assets/js/ajax-functions.js' ),
            true
        );

        $filter_data = rsl_get_filter_data_for_localization();

        // Get selected Stock Locator page ID from ACF Options
        $stock_locator_page_id = get_field('select_stock_locator_page', 'option');

        // Fallback URL in case page isn't selected
        $current_page_url = $stock_locator_page_id ? get_the_permalink($stock_locator_page_id) : '';

        // Pass admin-ajax URL + other variables to JS
        wp_localize_script(
            'rsl-ajax-functions',
            'rsl_ajax_obj',
            array_merge(
                [
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'rsl_ajax_nonce' ),
                    'vdp_per_page' => get_field('vdp_per_page', 'option'),
                    'current_page_url' => $current_page_url,
                    'home_url' => home_url('/'),
                    'stock_page_url' => get_permalink($stock_locator_page_id),
                    'stock_page_title' => get_the_title($stock_locator_page_id),
                    'site_title' => get_bloginfo('name'),
                ],
                $filter_data
            )
        );
    }

    // Custom JS
    wp_enqueue_script(
        'rsl-custom',
        RSL_PLUGIN_URL . 'assets/js/custom.js',
        [ 'jquery', 'rsl-main', 'rsl-owl-carousel' ],
        filemtime( RSL_PLUGIN_DIR . 'assets/js/custom.js' ),
        true
    );    

    wp_enqueue_script('gfam-ajax', RSL_PLUGIN_URL . 'assets/js/cstm.js', ['jquery'], null, true);
    wp_localize_script('gfam-ajax', 'gfam_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('gfam_form_nonce')
    ]);
    
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

// Dynamic colors function
function theme_global_color_head() {

    $stock_locator_color_settings = get_field('stock_locator_page_badges_color_settings', 'option');

    // Ensure it's an array before accessing
    if ( ! is_array( $stock_locator_color_settings ) ) {
        $stock_locator_color_settings = [];
    }

    // Safely assign colors with defaults
    $type_field_bg_color = isset($stock_locator_color_settings['type_field_background_color_on_stock_listing'])
        ? $stock_locator_color_settings['type_field_background_color_on_stock_listing']
        : '#92191C';

    $stock_number_bg_color = isset($stock_locator_color_settings['stock_number_field_background_color'])
        ? $stock_locator_color_settings['stock_number_field_background_color']
        : '#272727';

    $badges_text_color = isset($stock_locator_color_settings['badges_text_color_on_stock_listing'])
        ? $stock_locator_color_settings['badges_text_color_on_stock_listing']
        : '#ffffff';

    $general_color_settings = get_field('general_color_options', 'option');

    $general_color_settings = get_field('general_color_options', 'option');

    // Ensure it's an array before accessing
    if ( ! is_array( $general_color_settings ) ) {
        $general_color_settings = [];
    }

    // Safely assign colors with defaults
    $button_bg_color = isset($general_color_settings['button_background_color'])
        ? $general_color_settings['button_background_color']
        : '#fdbd3d';

    $button_bg_hover_color = isset($general_color_settings['button_background_hover_color'])
        ? $general_color_settings['button_background_hover_color']
        : '#92191C';

    $button_text_color = isset($general_color_settings['button_text_color'])
        ? $general_color_settings['button_text_color']
        : '#272727';

    $button_text_hover_color = isset($general_color_settings['button_text_hover_color'])
        ? $general_color_settings['button_text_hover_color']
        : '#ffffff';

    $grey_box_bg_color = isset($general_color_settings['grey_box_background_color'])
        ? $general_color_settings['grey_box_background_color']
        : '#f8f8f8';

    $light_grey_box_bg_color = isset($general_color_settings['light_grey_box_background_color'])
        ? $general_color_settings['light_grey_box_background_color']
        : '#f5f5f5';

    
    // Define your color variables
    $red_badge_bg_color          = $type_field_bg_color; 
    $red_badge_txt_color         = $badges_text_color;
    $darkgrey_badge_bg_color     = $stock_number_bg_color;
    $yellow_btn_bg_color         = $button_bg_color;
    $yellow_btn_hover_bg_color   = $button_bg_hover_color;
    $yellow_btn_txt_color        = $button_text_color;
    $yellow_btn_txt_hover_color  = $button_text_hover_color;
    $grey_box_bg_color           = $grey_box_bg_color;
    $lightgrey_box_bg_color      = $light_grey_box_bg_color;

    echo "
    <style id='theme-global-colors'>
        :root {
            --red_badge_bg_color: {$red_badge_bg_color};
            --red_badge_txt_color: {$red_badge_txt_color};
            --darkgrey_badge_bg_color: {$darkgrey_badge_bg_color};
            --yellow_btn_bg_color: {$yellow_btn_bg_color};
            --yellow_btn_hover_bg_color: {$yellow_btn_hover_bg_color};
            --yellow_btn_txt_color: {$yellow_btn_txt_color};
            --yellow_btn_txt_hover_color: {$yellow_btn_txt_hover_color};
            --grey_box_bg_color: {$grey_box_bg_color};
            --lightgrey_box_bg_color: {$lightgrey_box_bg_color};
        }
    </style>
    ";
}
add_action('wp_head', 'theme_global_color_head');

// Code for the add Stock locator class in body
function rsl_plugin_body_class($classes){
    $classes[] = 'rsl-stock-locator';
    return $classes;
}
add_filter('body_class', 'rsl_plugin_body_class');