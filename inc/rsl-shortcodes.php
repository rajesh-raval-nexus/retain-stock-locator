<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render the stock locator template.
 */
function rsl_render_stock_locator( $atts ) {
    ob_start();    

    // Load the shop template
    include RSL_PLUGIN_DIR . 'templates/shop.php';

    return ob_get_clean();
}

/**
 * Get category filters by dispatching internal REST route.
 * Uses transient caching to avoid repeated XML parsing.
 *
 * @param int $cache_ttl seconds
 * @return array
 */
function rsl_get_category_filters_cached( $cache_ttl = 300 ) {
    $cache_key = 'rsl_category_filters_v1';
    $cached = get_transient( $cache_key );
    if ( false !== $cached ) {
        return $cached;
    }

    // Build and dispatch internal REST request
    $server  = rest_get_server(); // WP REST server
    $request = new WP_REST_Request( 'GET', '/rsl/v1/category-filters' );

    $response = $server->dispatch( $request );

    // If WP_Error or not a valid response, return empty array
    if ( is_wp_error( $response ) ) {
        return [];
    }

    // $response is WP_REST_Response (or similar). Get the data.
    $data = null;
    if ( method_exists( $response, 'get_data' ) ) {
        $data = $response->get_data();
    } elseif ( is_array( $response ) ) {
        $data = $response;
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
        return [];
    }

    // Cache result
    set_transient( $cache_key, $data, (int) $cache_ttl );

    return $data;
}

/**
 * Build accordion markup from category filters array.
 *
 * @param array  $filters (e.g. [ 'Tractor' => ['FWA/4WD'], ... ])
 * @param string $accordion_id
 * @return string HTML
 */
function rsl_build_category_accordion_html( $filters, $accordion_id = 'categoryAccordion' ) {
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No categories available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>
    <div class="accordion p-3" id="<?php echo esc_attr( $accordion_id ); ?>">
        <?php
        $i = 0;
        foreach ( $filters as $parent => $subtypes ) :
            $i++;
            $collapse_id         = $accordion_id . '_collapse_' . $i;
            $parent_checkbox_id  = $accordion_id . '_parent_' . $i;
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>"
                            aria-expanded="false">
                        <div class="form-check form-check-inline gap-3">
                            <input class="form-check-input rsl-filter-parent"
                                type="checkbox"
                                id="<?php echo esc_attr( $parent_checkbox_id ); ?>"
                                value="<?php echo esc_attr( $parent ); ?>"
                                data-filter-type="parent">
                            <label class="form-check-label"
                                for="<?php echo esc_attr( $parent_checkbox_id ); ?>">
                                <?php echo esc_html( $parent ); ?>
                            </label>
                        </div>
                    </button>
                </h2>
                <div id="<?php echo esc_attr( $collapse_id ); ?>"
                    class="accordion-collapse collapse">
                    <div class="accordion-body gfam-filter-content-item">
                        <?php if ( is_array( $subtypes ) && count( $subtypes ) ) :
                            $j = 0;
                            foreach ( $subtypes as $sub ) :
                                $j++;
                                $sub_id = $accordion_id . '_sub_' . $i . '_' . $j;
                                ?>
                                <div class="gfam-checkbox-item">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input rsl-filter-sub"
                                            type="checkbox"
                                            id="<?php echo esc_attr( $sub_id ); ?>"
                                            value="<?php echo esc_attr( $sub ); ?>"
                                            data-parent="<?php echo esc_attr( $parent ); ?>">
                                        <label class="form-check-label"
                                            for="<?php echo esc_attr( $sub_id ); ?>">
                                            <?php echo esc_html( $sub ); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach;
                        else : ?>
                            <div class="text-muted">
                                <?php esc_html_e( 'No subtypes', 'retain-stock-locator' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode: [rsl_category_options]
 */
function rsl_category_filters_shortcode( $atts = array() ) {
    // Fetch using server-side WP call + caching
    $filters = rsl_get_category_filters_cached( 300 ); // 5 minutes cache

    // Build HTML
    return rsl_build_category_accordion_html( $filters );
}

/**
 * Register shortcodes
 */
function rsl_register_shortcodes() {
    add_shortcode( 'retain_stock_locator', 'rsl_render_stock_locator' );
    add_shortcode( 'rsl_category_options', 'rsl_category_filters_shortcode' );
}
rsl_register_shortcodes();
