<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register shortcodes
 */
function rsl_register_shortcodes() {        
    add_shortcode( 'retain_stock_locator', 'rsl_render_stock_locator' );
    add_shortcode( 'rsl_category_options', 'rsl_category_filters_shortcode' );
    add_shortcode( 'rsl_make_model_options', 'rsl_make_model_filters_shortcode' );    
    add_shortcode( 'rsl_year_options', 'rsl_year_filters_shortcode' );    
}

/**
 * Render the stock locator template.
 */
function rsl_render_stock_locator( $atts ) {
    ob_start();    
    include RSL_PLUGIN_DIR . 'templates/shop.php';
    return ob_get_clean();
}

/**
 * Get category filters directly (with caching).
 */
function rsl_get_category_filters_cached( $cache_ttl = 300 ) {
    $cache_key = 'rsl_category_filters_v1';
    $cached = get_transient( $cache_key );
    
    if ( false !== $cached ) {
        return $cached;
    }

    $data = rsl_get_category_filters(); // ✅ Direct call

    if ( ! empty( $data ) && is_array( $data ) ) {
        set_transient( $cache_key, $data, (int) $cache_ttl );
    }

    return $data ?: [];
}

/**
 * Get Make/Model filters directly (with caching).
 */
function rsl_get_make_model_filters_cached( $cache_ttl = 300 ) {
    $cache_key = 'rsl_make_model_filters_v1';
    $cached = get_transient( $cache_key );
    if ( false !== $cached ) {
        return $cached;
    }

    $data = rsl_get_make_model_filters(); // Direct call

    if ( ! empty( $data ) && is_array( $data ) ) {
        set_transient( $cache_key, $data, (int) $cache_ttl );
    }

    return $data ?: [];
}

/**
 * Get XML attribute filters directly (with caching).
 */
function rsl_get_xml_year_filters_cached( $attribute_name, $cache_ttl = 300 ) {
    global $xmlPath;

    $cache_key = "rsl_{$attribute_name}_filters_v1";
    $cached = get_transient( $cache_key );

    if ( false !== $cached ) {
        return $cached;
    }
    
    $allListings = rsl_parse_listings( $xmlPath );

    foreach ( $allListings as $listing ) {                
        $data[] = $listing[$attribute_name];
    }

    $data = array_unique(
        array_filter(
            array_map('trim', $data),
            function($val) {
                return $val !== '' && $val !== null;
            }
        )
    );

    if ( ! empty( $data ) && is_array( $data ) ) {
        set_transient( $cache_key, $data, (int) $cache_ttl );
    }

    return $data ?: [];
}

/**
 * Shortcode: [rsl_category_options]
 */
function rsl_category_filters_shortcode( $atts = array() ) {    
    $filters = rsl_get_category_filters_cached( 300 );
    return rsl_build_category_accordion_html( $filters );
}

/**
 * Shortcode: [rsl_make_model_options]
 */
function rsl_make_model_filters_shortcode( $atts = array() ) {           
    $filters = rsl_get_make_model_filters_cached( 300 );
    return rsl_build_make_model_accordion_html( $filters );
}

/**
 * Shortcode: [rsl_year_options]
 */
function rsl_year_filters_shortcode( $atts = array() ) {        
    $filters = rsl_get_xml_year_filters_cached( 'year', 300 );
    return rsl_build_year_filter_html( $filters );
}

/**
 * Build accordion markup from make and model filters array with counts.
 */
function rsl_build_make_model_accordion_html( $filters, $accordion_id = 'makeModelAccordion' ) {
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Make Or Model Available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>
    <div class="accordion p-3" id="<?php echo esc_attr( $accordion_id ); ?>">
        <?php
        $i = 0;
        foreach ( $filters as $make => $data ) :
            $i++;
            $collapse_id         = $accordion_id . '_collapse_' . $i;
            $parent_checkbox_id  = $accordion_id . '_parent_' . $i;
            $make_count          = isset( $data['count'] ) ? (int) $data['count'] : 0;
            $models              = isset( $data['models'] ) ? $data['models'] : [];
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
                                value="<?php echo esc_attr( $make ); ?>"
                                data-filter-type="parent">
                            <label class="form-check-label"
                                for="<?php echo esc_attr( $parent_checkbox_id ); ?>">
                                <?php echo esc_html( $make ); ?>
                                <span class="text-muted">(<?php echo $make_count; ?>)</span>
                            </label>
                        </div>
                    </button>
                </h2>
                <div id="<?php echo esc_attr( $collapse_id ); ?>"
                    class="accordion-collapse collapse">
                    <div class="accordion-body gfam-filter-content-item">
                        <?php if ( is_array( $models ) && count( $models ) ) :
                            $j = 0;
                            foreach ( $models as $model => $count ) :
                                $j++;
                                $sub_id = $accordion_id . '_sub_' . $i . '_' . $j;
                                ?>
                                <div class="gfam-checkbox-item">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input rsl-filter-sub"
                                            type="checkbox"
                                            id="<?php echo esc_attr( $sub_id ); ?>"
                                            value="<?php echo esc_attr( $model ); ?>"
                                            data-parent="<?php echo esc_attr( $make ); ?>">
                                        <label class="form-check-label"
                                            for="<?php echo esc_attr( $sub_id ); ?>">
                                            <?php echo esc_html( $model ); ?>
                                            <span class="text-muted">(<?php echo (int) $count; ?>)</span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach;
                        else : ?>
                            <div class="text-muted">
                                <?php esc_html_e( 'No Model Available', 'retain-stock-locator' ); ?>
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
 * Build accordion markup from year filters array.
 */
function rsl_build_year_filter_html( $filters, $accordion_id = 'yearAccordion' ) {
    
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Year Available.', 'retain-stock-locator' ) . '</div>';
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
                                <?php echo esc_html( $subtypes ); ?>
                            </label>
                        </div>
                    </button>
                </h2>                
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Build accordion markup from category filters array (with counts).
 */
function rsl_build_category_accordion_html( $filters, $accordion_id = 'categoryAccordion' ) {
    
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No categories available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>
    <div class="accordion p-3" id="<?php echo esc_attr( $accordion_id ); ?>">
        <?php
        $i = 0;
        foreach ( $filters as $parent => $data ) :
            $i++;
            $collapse_id         = $accordion_id . '_collapse_' . $i;
            $parent_checkbox_id  = $accordion_id . '_parent_' . $i;

            $parent_count = isset( $data['count'] ) ? intval( $data['count'] ) : 0;
            $subtypes     = isset( $data['subtypes'] ) ? $data['subtypes'] : [];
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
                                (<?php echo $parent_count; ?>)
                            </label>
                        </div>
                    </button>
                </h2>
                <div id="<?php echo esc_attr( $collapse_id ); ?>"
                    class="accordion-collapse collapse">
                    <div class="accordion-body gfam-filter-content-item">
                        <?php if ( is_array( $subtypes ) && count( $subtypes ) ) :
                            $j = 0;
                            foreach ( $subtypes as $sub => $subCount ) :
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
                                            (<?php echo intval($subCount); ?>)
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
