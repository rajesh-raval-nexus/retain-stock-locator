<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register shortcodes
 */
function rsl_register_shortcodes() {        
    add_shortcode( 'retain_stock_locator', 'rsl_render_stock_locator' );
    add_shortcode( 'retain_stock_locator_detail', 'rsl_render_stock_locator_detail' );
    add_shortcode( 'rsl_category_options', 'rsl_category_filters_shortcode' );
    add_shortcode( 'rsl_make_model_options', 'rsl_make_model_filters_shortcode' );    
    add_shortcode( 'rsl_year_options', 'rsl_year_filters_shortcode' );    
    add_shortcode( 'rsl_type_options', 'rsl_type_filters_shortcode' );   
    add_shortcode( 'rsl_price_options', 'rsl_price_filters_shortcode' );   
    add_shortcode( 'rsl_hours_options', 'rsl_hours_filters_shortcode' );   
}

/**
 * Render the stock locator template.
 */
function rsl_render_stock_locator( $atts ) {
    ob_start();    
    include RSL_PLUGIN_DIR . 'templates/shop.php';
    return ob_get_clean();
}

function rsl_render_stock_locator_detail( $atts ) {
    ob_start();    
    include RSL_PLUGIN_DIR . 'templates/shop-detail.php';
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

    $data = rsl_get_category_filters(); // Direct call

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
 * Get XML attribute type filters directly (with caching).
 */
function rsl_get_xml_type_filters_cached( $attribute_name, $cache_ttl = 300 ) {
    global $xmlPath;
    $data = array();

    $cache_key = "rsl_{$attribute_name}_filters_v1";
    $cached = get_transient( $cache_key );

    if ( false !== $cached ) {
        return $cached;
    }
    
    $allListings = rsl_parse_listings( $xmlPath );

    foreach ( $allListings as $listing ) { 
      if(isset($listing[$attribute_name]) && !empty($listing[$attribute_name]) ){
        $data[] = $listing[$attribute_name];
      }               
    }

    $formatted_data = array_count_values($data);

    if ( ! empty( $formatted_data ) && is_array( $formatted_data ) ) {
        set_transient( $cache_key, $formatted_data, (int) $cache_ttl );
    }

    return $formatted_data ?: [];
}

/**
 * Get XML attribute price filters directly (with caching).
 */
function rsl_get_xml_price_filters_cached( $attribute_name, $cache_ttl = 300 ) {
    global $xmlPath;
    $prices = array();

    $cache_key = "rsl_{$attribute_name}_filters_v1";
    $cached = get_transient( $cache_key );

    if ( false !== $cached ) {
        return $cached;
    }
    
    $allListings = rsl_parse_listings( $xmlPath );

    foreach ( $allListings as $listing ) {                
      if(isset($listing[$attribute_name]) && !empty($listing[$attribute_name]) ){
        $prices[] = $listing[$attribute_name];
      }
    }    

    // Remove duplicates
    $unique_prices = array_unique($prices);

    if ( ! empty( $unique_prices ) && is_array( $unique_prices ) ) {
        set_transient( $cache_key, $unique_prices, (int) $cache_ttl );
    }

    return $unique_prices ?: [];
}

/**
 * Get XML attribute filters directly (with caching).
 */
function rsl_get_xml_year_filters_cached( $attribute_name, $cache_ttl = 300 ) {
    global $xmlPath;
    $data = array();

    $cache_key = "rsl_{$attribute_name}_filters_v1";
    $cached = get_transient( $cache_key );

    // if ( false !== $cached ) {
    //     return $cached;
    // }
    
    $allListings = rsl_parse_listings( $xmlPath );

    foreach ( $allListings as $listing ) {     
      
      if ( isset($listing[$attribute_name]) && !empty($listing[$attribute_name]) ) {
          $data[] = $listing[$attribute_name];
      }
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
 * Get XML Hours filters directly (with caching).
 */
function rsl_get_xml_hours_filters_cached( $attribute_name, $cache_ttl = 300 ) {
    global $xmlPath;
    $data = array();

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
    return rsl_build_category_modal_html( $filters );
}

/**
 * Shortcode: [rsl_make_model_options]
 */
function rsl_make_model_filters_shortcode( $atts = array() ) {           
    $filters = rsl_get_make_model_filters_cached( 300 );
    return rsl_build_make_model_modal_html( $filters );
}

/**
 * Shortcode: [rsl_type_options]
 */
function rsl_type_filters_shortcode( $atts = array() ) {        
    $filters = rsl_get_xml_type_filters_cached( 'listing_type', 300 );
    return rsl_build_type_filter_modal_html( $filters );
}

/**
 * Shortcode: [rsl_price_options]
 */
function rsl_price_filters_shortcode( $atts = array() ) {        
    $filters = rsl_get_xml_price_filters_cached( 'price', 300 );
    return rsl_build_price_filter_modal_html( $filters );
}

/**
 * Shortcode: [rsl_year_options]
 */
function rsl_year_filters_shortcode( $atts = array() ) {        
    $filters = rsl_get_xml_year_filters_cached( 'year', 300 );
    return rsl_build_year_filter_modal_html( $filters );
}

/**
 * Shortcode: [rsl_hours_options]
 */
function rsl_hours_filters_shortcode( $atts = array() ) {        
    $filters = rsl_get_xml_hours_filters_cached( 'hours', 300 );
    return rsl_build_hours_filter_modal_html( $filters );
}

/**
 * Build modal category HTML from category filters array (with counts and subtypes).
 */
function rsl_build_category_modal_html( $filters ) {
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No categories available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>

    <!-- Category Modal -->
    <div class="modal fade sidebar-modal" id="popupCategoryDesktop" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

          <!-- Main Category Body -->
          <div class="modal-body category-body active">
            <div class="modal-header border-0">
              <h5 class="modal-title"><?php esc_html_e( 'Categories', 'retain-stock-locator' ); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="gfam-filter-content-item">
              <?php
              $i = 0;
              foreach ( $filters as $parent => $data ) :
                  $i++;
                  $parent_checkbox_id = 'category_' . $i;
                  $parent_count       = isset( $data['count'] ) ? intval( $data['count'] ) : 0;
                  ?>
                  <div class="accordion-item category-link" data-target="#subcategory<?php echo esc_attr( $i ); ?>">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse">
                        <div class="form-check form-check-inline gap-3">
                          <input class="form-check-input rsl-filter-parent category-filter" type="checkbox" name="category[]" id="<?php echo esc_attr( $parent_checkbox_id ); ?>" value="<?php echo esc_attr( $parent ); ?>" />
                          <label class="form-check-label" for="<?php echo esc_attr( $parent_checkbox_id ); ?>">
                            <?php echo esc_html( $parent ); ?> (<?php echo $parent_count; ?>)
                          </label>
                          <div class="category-popup-link">
                          <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z" fill="black"></path>
                          </svg>
                          </div>
                        </div>
                        
                      </button>
                    </h2>
                  </div>
              <?php endforeach; ?>
            </div>
          </div>

          <?php
          // Subcategory Bodies
          $i = 0;
          foreach ( $filters as $parent => $data ) :
              $i++;
              $subtypes = isset( $data['subtypes'] ) ? $data['subtypes'] : [];
              ?>
              <div class="modal-body subcategory-body" id="subcategory<?php echo esc_attr( $i ); ?>">
                <div class="modal-header border-0">
                  <button class="btn btn-link p-0 me-2 back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M8.4 12.9L13.6 6.37L15.6 7.36L11.35 12L15.59 15.87L14.61 17.63L8.4 12.9Z" fill="#272727"/>
                    </svg>
                    <span class="d-none d-xl-inline"><?php esc_html_e( 'Categories', 'retain-stock-locator' ); ?></span>
                  </button>
                  <h5 class="modal-title"><?php esc_html_e( 'Subcategory', 'retain-stock-locator' ); ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="gfam-filter-content-item">
                  <?php
                  if ( is_array( $subtypes ) && count( $subtypes ) ) :
                      $j = 0;
                      foreach ( $subtypes as $sub => $subCount ) :
                          $j++;
                          $sub_id = 'sub' . $i . '-' . $j;
                          ?>
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse">
                                <div class="form-check form-check-inline gap-3">
                                  <input class="form-check-input rsl-filter-sub category-filters" name="category[]" type="checkbox" id="<?php echo esc_attr( $sub_id ); ?>" value="<?php echo esc_attr( $sub ); ?>" data-parent="<?php echo esc_attr( $parent ); ?>" />
                                  <label class="form-check-label" for="<?php echo esc_attr( $sub_id ); ?>">
                                    <?php echo esc_html( $sub ); ?> (<?php echo intval( $subCount ); ?>)
                                  </label>
                                </div>                                
                              </button>
                            </h2>
                          </div>
                      <?php endforeach;
                  else : ?>
                      <div class="text-muted"><?php esc_html_e( 'No sub-categories found', 'retain-stock-locator' ); ?></div>
                  <?php endif; ?>
                </div>
              </div>
          <?php endforeach; ?>

          <div class="modal-footer">
            <div class="gfam-btn-fixed row w-100 align-items-center">
              <div class="col-6 px-1">
                <a href="#" class="clear-btn" data-bs-dismiss="modal" data-type="category"><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
              </div>
              <div class="col-6 text-end px-1">
                <button class="gfam-btn w-auto rsl-apply-filter" data-search="category-filter-search" data-bs-dismiss="modal"><?php esc_html_e( 'Search', 'retain-stock-locator' ); ?></button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <?php
    return ob_get_clean();
}

/**
 * Build modal HTML for Make & Model filters.
 */
function rsl_build_make_model_modal_html( $filters ) {
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Make Or Model Available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>

    <!-- Make Modal -->
    <div class="modal fade sidebar-modal" id="popupMakeDesktop" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

          <!-- Main Make Body -->
          <div class="modal-body category-body active">
            <div class="modal-header border-0">
              <h5 class="modal-title"><?php esc_html_e( 'Make', 'retain-stock-locator' ); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Make Search -->
            <div class="input-group gfam-search-section">
              <input type="text" class="gfam-search-input" placeholder="<?php esc_attr_e( 'Search Make', 'retain-stock-locator' ); ?>" aria-label="Search Make" />
              <div class="input-group-append">
                <span class="input-group-text">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="24" height="24" rx="12" fill="#FDBD3D"/>
                <path d="M11.1374 7.65C10.3624 7.65 9.65615 7.8375 9.01865 8.2125C8.38115 8.5875 7.86865 9.1 7.48115 9.75C7.09365 10.4 6.8999 11.1062 6.8999 11.8687C6.8999 12.6312 7.09365 13.3375 7.48115 13.9875C7.86865 14.6375 8.38115 15.15 9.01865 15.525C9.65615 15.9 10.3624 16.0875 11.1374 16.0875C11.6124 16.0875 12.0749 16.0062 12.5249 15.8438C12.9749 15.6812 13.3874 15.4625 13.7624 15.1875L15.5249 16.9125C15.5999 17.0125 15.7062 17.0625 15.8437 17.0625C15.9812 17.0625 16.0937 17.0187 16.1812 16.9312C16.2687 16.8438 16.3124 16.7312 16.3124 16.5937C16.3124 16.4562 16.2624 16.35 16.1624 16.275L14.4374 14.5125C14.7124 14.1375 14.9312 13.725 15.0937 13.275C15.2562 12.825 15.3374 12.3625 15.3374 11.8875C15.3374 11.1125 15.1499 10.4 14.7749 9.75C14.3999 9.1 13.8874 8.5875 13.2374 8.2125C12.5874 7.8375 11.8874 7.65 11.1374 7.65ZM11.1374 8.5875C11.7374 8.5875 12.2874 8.7375 12.7874 9.0375C13.2874 9.3375 13.6812 9.7375 13.9687 10.2375C14.2562 10.7375 14.3999 11.2812 14.3999 11.8687C14.3999 12.4562 14.2562 13.0062 13.9687 13.5187C13.6812 14.0312 13.2874 14.4312 12.7874 14.7188C12.2874 15.0062 11.7374 15.15 11.1374 15.15C10.5374 15.15 9.9874 15.0062 9.4874 14.7188C8.9874 14.4312 8.5874 14.0312 8.2874 13.5187C7.9874 13.0062 7.8374 12.4562 7.8374 11.8687C7.8374 11.2812 7.9874 10.7375 8.2874 10.2375C8.5874 9.7375 8.9874 9.3375 9.4874 9.0375C9.9874 8.7375 10.5374 8.5875 11.1374 8.5875Z" fill="black"/>
                </svg>
                </span>
              </div>
            </div>

            <div class="gfam-filter-content-item">
              <?php
              $i = 0;
              foreach ( $filters as $make => $data ) :
                  $i++;
                  $make_checkbox_id = 'make_' . $i;
                  $make_count       = isset( $data['count'] ) ? intval( $data['count'] ) : 0;
                  ?>
                  <div class="accordion-item category-link" data-target="#submake<?php echo esc_attr( $i ); ?>">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse">
                        <div class="form-check form-check-inline gap-3">
                          <input class="form-check-input rsl-filter-parent" name="make-model[]" type="checkbox" id="<?php echo esc_attr( $make_checkbox_id ); ?>" value="<?php echo esc_attr( $make ); ?>" />
                          <label class="form-check-label" for="<?php echo esc_attr( $make_checkbox_id ); ?>">
                            <?php echo esc_html( $make ); ?> (<?php echo $make_count; ?>)
                          </label>
                          <div class="category-popup-link">
                          <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z" fill="black"></path>
                          </svg>
                          </div>
                        </div>
                        
                      </button>
                    </h2>
                  </div>
              <?php endforeach; ?>
            </div>
          </div>

          <?php
          // Model Subcategory Bodies
          $i = 0;
          foreach ( $filters as $make => $data ) :
              $i++;
              $models = isset( $data['models'] ) ? $data['models'] : [];
              ?>
              <div class="modal-body subcategory-body" id="submake<?php echo esc_attr( $i ); ?>">
                <div class="modal-header border-0">
                  <button class="btn btn-link p-0 me-2 back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M8.4 12.9L13.6 6.37L15.6 7.36L11.35 12L15.59 15.87L14.61 17.63L8.4 12.9Z" fill="#272727"/>
                    </svg>
                    <span class="d-none d-xl-inline"><?php esc_html_e( 'Makes', 'retain-stock-locator' ); ?></span>
                  </button>
                  <h5 class="modal-title"><?php esc_html_e( 'Models', 'retain-stock-locator' ); ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Model Search -->
                <div class="input-group gfam-search-section">
                  <input type="text" class="gfam-search-input" placeholder="<?php esc_attr_e( 'Search Model', 'retain-stock-locator' ); ?>" aria-label="Search Model" />
                  <div class="input-group-append">
                    <span class="input-group-text">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" rx="12" fill="#FDBD3D"/>
                    <path d="M11.1374 7.65C10.3624 7.65 9.65615 7.8375 9.01865 8.2125C8.38115 8.5875 7.86865 9.1 7.48115 9.75C7.09365 10.4 6.8999 11.1062 6.8999 11.8687C6.8999 12.6312 7.09365 13.3375 7.48115 13.9875C7.86865 14.6375 8.38115 15.15 9.01865 15.525C9.65615 15.9 10.3624 16.0875 11.1374 16.0875C11.6124 16.0875 12.0749 16.0062 12.5249 15.8438C12.9749 15.6812 13.3874 15.4625 13.7624 15.1875L15.5249 16.9125C15.5999 17.0125 15.7062 17.0625 15.8437 17.0625C15.9812 17.0625 16.0937 17.0187 16.1812 16.9312C16.2687 16.8438 16.3124 16.7312 16.3124 16.5937C16.3124 16.4562 16.2624 16.35 16.1624 16.275L14.4374 14.5125C14.7124 14.1375 14.9312 13.725 15.0937 13.275C15.2562 12.825 15.3374 12.3625 15.3374 11.8875C15.3374 11.1125 15.1499 10.4 14.7749 9.75C14.3999 9.1 13.8874 8.5875 13.2374 8.2125C12.5874 7.8375 11.8874 7.65 11.1374 7.65ZM11.1374 8.5875C11.7374 8.5875 12.2874 8.7375 12.7874 9.0375C13.2874 9.3375 13.6812 9.7375 13.9687 10.2375C14.2562 10.7375 14.3999 11.2812 14.3999 11.8687C14.3999 12.4562 14.2562 13.0062 13.9687 13.5187C13.6812 14.0312 13.2874 14.4312 12.7874 14.7188C12.2874 15.0062 11.7374 15.15 11.1374 15.15C10.5374 15.15 9.9874 15.0062 9.4874 14.7188C8.9874 14.4312 8.5874 14.0312 8.2874 13.5187C7.9874 13.0062 7.8374 12.4562 7.8374 11.8687C7.8374 11.2812 7.9874 10.7375 8.2874 10.2375C8.5874 9.7375 8.9874 9.3375 9.4874 9.0375C9.9874 8.7375 10.5374 8.5875 11.1374 8.5875Z" fill="black"/>
                    </svg>
                    </span>
                  </div>
                </div>

                <div class="gfam-filter-content-item">
                  <?php
                  if ( is_array( $models ) && count( $models ) ) :
                      $j = 0;
                      foreach ( $models as $model => $count ) :
                          $j++;
                          $model_id = 'sub' . $i . '-' . $j;
                          ?>
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse">
                                <div class="form-check form-check-inline gap-3">
                                  <input class="form-check-input rsl-filter-sub" name="make-model[]" type="checkbox" id="<?php echo esc_attr( $model_id ); ?>" value="<?php echo esc_attr( $model ); ?>" data-parent="<?php echo esc_attr( $make ); ?>" />
                                  <label class="form-check-label" for="<?php echo esc_attr( $model_id ); ?>">
                                    <?php echo esc_html( $model ); ?> (<?php echo intval( $count ); ?>)
                                  </label>
                                </div>
                              </button>
                            </h2>
                          </div>
                      <?php endforeach;
                  else : ?>
                      <div class="text-muted"><?php esc_html_e( 'No Model Available', 'retain-stock-locator' ); ?></div>
                  <?php endif; ?>
                </div>
              </div>
          <?php endforeach; ?>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <div class="gfam-btn-fixed row w-100 align-items-center">
              <div class="col-6 px-1">
                <a href="#" class="clear-btn" data-bs-dismiss="modal" data-type="make-model"><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
              </div>
              <div class="col-6 text-end px-1">
                <button class="gfam-btn w-auto rsl-apply-filter" data-bs-dismiss="modal"><?php esc_html_e( 'Search', 'retain-stock-locator' ); ?></button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <?php
    return ob_get_clean();
}

/**
 * Build modal HTML for Type filters (e.g., New, Used, Demo).
 *
 * @param array  $filters Array of types with counts. Example: [ 'New' => 22, 'Used' => 18 ]
 * @param string $modal_id Optional custom modal ID.
 *
 * @return string Modal HTML.
 */
function rsl_build_type_filter_modal_html( $filters, $modal_id = 'popupTypeDesktop' ) {
    if ( empty( $filters ) || ! is_array( $filters ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Type Available.', 'retain-stock-locator' ) . '</div>';
    }

    ob_start(); ?>

    <div class="modal fade sidebar-modal" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

          <!-- Type Filter Body -->
          <div class="modal-body category-body active">
            <div class="modal-header border-0">
              <h5 class="modal-title"><?php esc_html_e( 'Type', 'retain-stock-locator' ); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="gfam-filter-content-item">
              <?php
              $i = 0;                          
              foreach ( $filters as $type => $count ) :
                  $i++;
                  $checkbox_id = sanitize_title( $type ) . '_' . $i;
                  ?>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse">
                        <div class="form-check form-check-inline gap-3">
                          <input class="form-check-input rsl-filter-parent" name="type[]"
                                 type="checkbox"
                                 id="<?php echo esc_attr( $checkbox_id ); ?>"
                                 value="<?php echo esc_attr( $type ); ?>"
                                 data-filter-type="type">
                          <label class="form-check-label" for="<?php echo esc_attr( $checkbox_id ); ?>">
                            <?php echo esc_html( $type ); ?> (<?php echo intval( $count ); ?>)
                          </label>
                        </div>
                      </button>
                    </h2>
                  </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <div class="gfam-btn-fixed row w-100 align-items-center">
              <div class="col-6 px-1">
                <a href="#" class="clear-btn" data-bs-dismiss="modal" data-type="type"><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
              </div>
              <div class="col-6 text-end px-1">
                <button class="gfam-btn w-auto rsl-apply-filter" data-bs-dismiss="modal"><?php esc_html_e( 'Search', 'retain-stock-locator' ); ?></button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <?php
    return ob_get_clean();
}

function rsl_build_price_filter_modal_html( $prices, $modal_id = 'popupRangeDesktop' ) {

    if ( empty( $prices ) || ! is_array( $prices ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Price Available.', 'retain-stock-locator' ) . '</div>';
    }

    // Sort ascending
    sort( $prices );

    $min_price = min( $prices );
    $max_price = max( $prices );

    // Format prices for display
    $formatted_prices = array_map( function( $price ) {
        return '$'.number_format( $price );
    }, $prices );

    ob_start(); ?>

    <!-- Price Range Modal -->
    <div class="modal fade sidebar-modal" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <form id="priceRangeForm">
            <!-- Modal Header -->
            <div class="modal-header border-0">
              <h5 class="modal-title"><?php esc_html_e( 'Price Range', 'retain-stock-locator' ); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body with Tabs -->
            <div class="modal-body category-body active">

              <!-- Tabs -->
              <ul class="nav nav-tabs mb-3" id="priceRangeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link rsl-price-tabs active" id="select-tab" data-bs-toggle="tab" data-bs-target="#selectRange"
                    type="button" role="tab">
                    <?php esc_html_e( 'Select Range', 'retain-stock-locator' ); ?>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link rsl-price-tabs" id="enter-tab" data-bs-toggle="tab" data-bs-target="#enterRange" type="button"
                    role="tab">
                    <?php esc_html_e( 'Enter Range', 'retain-stock-locator' ); ?>
                  </button>
                </li>
              </ul>

              <!-- Tab Content -->
              <div class="tab-content" id="priceRangeTabsContent">

                <!-- Select Range Tab -->
                <div class="tab-pane fade show active" id="selectRange" role="tabpanel">
                  <div class="mb-4">
                    <label for="priceFromSelect" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                    <select class="form-select rsl-price-from" name="price-from" id="priceFromSelect">
                      <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                      <?php foreach ( $prices as $i => $price ): ?>
                        <option value="<?php echo esc_attr( $price ); ?>"><?php echo esc_html( $formatted_prices[ $i ] ); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="priceToSelect" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                    <select class="form-select rsl-price-to" name="price-to" id="priceToSelect">
                      <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                      <?php foreach ( $prices as $i => $price ): ?>
                        <option value="<?php echo esc_attr( $price ); ?>"><?php echo esc_html( $formatted_prices[ $i ] ); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Enter Range Tab -->
                <div class="tab-pane fade" id="enterRange" role="tabpanel">
                  <div class="mb-4">
                    <label for="priceFromInput" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                    <input type="number" class="form-control rsl-price-from" name="priceFromInput" id="priceFromInput" placeholder=""
                          min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" />
                  </div>
                  <div class="mb-4">
                    <label for="priceToInput" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                    <input type="number" class="form-control rsl-price-to" name="priceToInput" id="priceToInput" placeholder=""
                          min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" />
                  </div>
                </div>

              </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
              <div class="gfam-btn-fixed row w-100 align-items-center">
                <div class="col-6 px-1">
                  <a href="javascript:void(0);" class="clear-btn" data-bs-dismiss="modal" data-type="price-range"><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
                </div>
                <div class="col-6 text-end px-1">
                  <button type="submit" class="gfam-btn w-auto rsl-apply-filter" data-bs-dismiss="modal">
                    <?php esc_html_e( 'Search', 'retain-stock-locator' ); ?>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


    <?php
    return ob_get_clean();
}

/**
 * Build Year Filter Modal HTML
 *
 * @param array  $years      Array of years (values only).
 * @param string $modal_id   Modal ID.
 *
 * @return string
 */
function rsl_build_year_filter_modal_html( $years, $modal_id = 'popupYearDesktop' ) {

    if ( empty( $years ) || ! is_array( $years ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Year Available.', 'retain-stock-locator' ) . '</div>';
    }

    // Remove duplicates, sort numerically ascending
    sort( $years, SORT_NUMERIC );

    $min_year = min( $years );
    $max_year = max( $years );

    ob_start(); ?>
    <div class="modal fade sidebar-modal" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
        <form id="yearRangeForm">
                <!-- Modal Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title"><?php esc_html_e( 'Year', 'retain-stock-locator' ); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body with Tabs -->
                <div class="modal-body category-body active">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="yearRangeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rsl-year-tabs active" id="select-tab" data-bs-toggle="tab" data-bs-target="#selectyear" type="button" role="tab">
                                <?php esc_html_e( 'Select Year', 'retain-stock-locator' ); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rsl-year-tabs" id="enter-tab" data-bs-toggle="tab" data-bs-target="#enteryear" type="button" role="tab">
                                <?php esc_html_e( 'Enter Year', 'retain-stock-locator' ); ?>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="yearRangeTabsContent">

                        <!-- Select Range Tab -->
                        <div class="tab-pane fade show active" id="selectyear" role="tabpanel">
                            <div class="mb-4">
                                <label for="yearFromSelect" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                                <select class="form-select rsl-year-from" id="yearFromSelect" name="year-from" data-filter-type="year_from">
                                    <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                                    <?php foreach ( $years as $year ) : ?>
                                        <option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="yearToSelect" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                                <select class="form-select rsl-year-to" id="yearToSelect" name="year-to" data-filter-type="year_to">
                                    <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                                    <?php foreach ( $years as $year ) : ?>
                                        <option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Enter Range Tab -->
                        <div class="tab-pane fade" id="enteryear" role="tabpanel">
                            <div class="mb-4">
                                <label for="yearFromInput" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                                <input type="number" class="form-control rsl-year-from" name="yearFromInput" id="yearFromInput" placeholder="" data-filter-type="year_from" min="<?php echo esc_attr( $min_year ); ?>" max="<?php echo esc_attr( $max_year ); ?>" />
                            </div>
                            <div class="mb-4">
                                <label for="yearToInput" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                                <input type="number" class="form-control rsl-year-to" name="yearToInput" id="yearToInput" placeholder="" data-filter-type="year_to" min="<?php echo esc_attr( $min_year ); ?>" max="<?php echo esc_attr( $max_year ); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <div class="gfam-btn-fixed row w-100 align-items-center">
                        <div class="col-6 px-1">
                            <a href="javascript:void(0);" class="clear-btn rsl-clear-year" data-bs-dismiss="modal" data-type="year-range"><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
                        </div>
                        <div class="col-6 text-end px-1">
                            <button type="submit" class="gfam-btn w-auto rsl-apply-filter" data-bs-dismiss="modal" ><?php esc_html_e( 'Search', 'retain-stock-locator' ); ?></button>
                        </div>
                    </div>
                </div>
        </form>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Build Hours Filter Modal HTML
 *
 * @param array  $hours      Array of hours (values only).
 * @param string $modal_id   Modal ID.
 *
 * @return string
 */
function rsl_build_hours_filter_modal_html( $hours, $modal_id = 'popupHourDesktop' ) {

    if ( empty( $hours ) || ! is_array( $hours ) ) {
        return '<div class="rsl-no-filters p-3">' . esc_html__( 'No Hours Available.', 'retain-stock-locator' ) . '</div>';
    }

    // Sort hours numerically (smallest to largest)
    sort( $hours, SORT_NUMERIC );

    // Get min and max for input range validation
    $min_hour = min( $hours );
    $max_hour = max( $hours );

    ob_start(); ?>
    <div class="modal fade sidebar-modal" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
          <form id="hourRangeForm">
                <!-- Modal Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title"><?php esc_html_e( 'Hours', 'retain-stock-locator' ); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body with Tabs -->
                <div class="modal-body category-body active">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="hourRangeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rsl-hours-tabs active" id="select-tab" data-bs-toggle="tab" data-bs-target="#selecthour" type="button" role="tab">
                                <?php esc_html_e( 'Select Hours', 'retain-stock-locator' ); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rsl-hours-tabs" id="enter-tab" data-bs-toggle="tab" data-bs-target="#enterhour" type="button" role="tab">
                                <?php esc_html_e( 'Enter Hours', 'retain-stock-locator' ); ?>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="hourRangeTabsContent">

                        <!-- Select Range Tab -->
                        <div class="tab-pane fade show active" id="selecthour" role="tabpanel">
                            <div class="mb-4">
                                <label for="hourFromSelect" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                                <select class="form-select rsl-hours-from" name="hour-from" id="hourFromSelect" data-filter-type="hour_from">
                                    <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                                    <?php foreach ( $hours as $hour ) : ?>
                                        <option value="<?php echo esc_attr( $hour ); ?>"><?php echo esc_html( $hour ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="hourToSelect" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                                <select class="form-select rsl-hours-to" name="hour-to" id="hourToSelect" data-filter-type="hour_to">
                                    <option value=""><?php esc_html_e( 'Any', 'retain-stock-locator' ); ?></option>
                                    <?php foreach ( $hours as $hour ) : ?>
                                        <option value="<?php echo esc_attr( $hour ); ?>"><?php echo esc_html( $hour ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Enter Range Tab -->
                        <div class="tab-pane fade" id="enterhour" role="tabpanel">
                            <div class="mb-4">
                                <label for="hourFromInput" class="form-label"><?php esc_html_e( 'From', 'retain-stock-locator' ); ?></label>
                                <input type="number" class="form-control rsl-hours-from" name="hourFromInput" id="hourFromInput" placeholder="" data-filter-type="hour_from" min="<?php echo esc_attr( $min_hour ); ?>" max="<?php echo esc_attr( $max_hour ); ?>" />
                            </div>
                            <div class="mb-4">
                                <label for="hourToInput" class="form-label"><?php esc_html_e( 'To', 'retain-stock-locator' ); ?></label>
                                <input type="number" class="form-control rsl-hours-to" name="hourToInput" id="hourToInput" placeholder="" data-filter-type="hour_to" min="<?php echo esc_attr( $min_hour ); ?>" max="<?php echo esc_attr( $max_hour ); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <div class="gfam-btn-fixed row w-100 align-items-center">
                        <div class="col-6 px-1">
                            <a href="javascript:void(0);" class="clear-btn rsl-clear-hour" data-bs-dismiss="modal" data-type="hours-range" ><?php esc_html_e( 'Clear', 'retain-stock-locator' ); ?></a>
                        </div>
                        <div class="col-6 text-end px-1">
                            <button class="gfam-btn w-auto rsl-apply-filter"  data-bs-dismiss="modal"><?php esc_html_e( 'Search', 'retain-stock-locator' ); ?></button>
                        </div>
                    </div>
                </div>
          </form>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
