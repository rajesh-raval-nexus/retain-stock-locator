<?php

add_action( 'wp_ajax_rsl_get_stock_list', 'rsl_get_stock_list_ajax' );
add_action( 'wp_ajax_nopriv_rsl_get_stock_list', 'rsl_get_stock_list_ajax' );

function rsl_get_stock_list_ajax() {
    check_ajax_referer( 'rsl_ajax_nonce', 'security' );
    global $xmlPath;

    // Pagination
    $page     = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $vdpPerPage = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
    $offset   = ($page - 1) * $vdpPerPage;

    // Filters sent via AJAX
    $filters = isset($_POST['filters']) && is_array($_POST['filters']) ? $_POST['filters'] : [];

    // Fetch all listings
    $allListings = rsl_parse_listings( $xmlPath );    

    // Apply filters if any
    if ( ! empty($filters) ) {
        $allListings = rsl_apply_filters($allListings, $filters);        
    }

    // Optional: sorting if filter includes sort key
    if ( isset($filters['sort']) && $filters['sort'] !== '' ) {
        $allListings = rsl_sort($allListings, $filters['sort']);
    }

    if (!empty($filters['keyword'])) {
        $allListings = rsl_search($allListings, $filters['keyword']);
    }

    if(empty($allListings)){
        $no_result_found = "<h2 class='no-stock-available'>". __('No stock available', 'retain-stock-locator')."</h2>";

        wp_send_json_success([
            'html'        => $no_result_found,
            'next_page'   => 0,
            'has_more'    => false,
            'total_found' => 0,
            'max_pages'   => 0
        ]);
    }

    // Total filtered results
    $total_results = count($allListings);
    $max_pages = rsl_get_max_pages($allListings, $vdpPerPage);

    // Paginate
    $paged_listings = rsl_load_more($allListings, $offset, $vdpPerPage);

    // Build HTML
    $html = '';
    $global_index_start = 1; // First product number on this page
    $global_index = $global_index_start;

    $custom_sections = [
        [
            'slug'     => 'why_choose_us',
            'is_enabled' => get_field('do_you_want_to_show_why_choose_us_section','option'),
            'position' => get_field('after_how_many_stock_list_you_want_to_show_why_choose_us', 'option'),
            'template' => 'templates/vdp-listing/why-choose-us.php'
        ],
        [
            'slug'     => 'filter_by_price',
            'is_enabled' => get_field('do_you_want_to_show_machinery_price_filter','option'),
            'position' => get_field('after_how_many_stock_list_you_want_to_show', 'option'),
            'template' => 'templates/vdp-listing/farm-machinery-by-price.php'
        ],
        [
            'slug'     => 'filter_by_brand',
            'is_enabled' => get_field('do_you_want_to_show_machinery_by_brand_section','option'),
            'position' => get_field('after_how_many_stock_list_you_want_to_show_brand', 'option'),
            'template' => 'templates/vdp-listing/farm-machinery-by-brand.php'
        ],
    ];

    foreach ( $paged_listings as $item ) {
        ob_start();
        include RSL_PLUGIN_DIR . 'templates/parts/product-card.php';
        $html .= ob_get_clean();

        // Check if any custom section should appear after this product
        foreach ($custom_sections as $section) {
            if ($section['is_enabled'] && $section['position'] == $global_index) {
                ob_start();
                require RSL_PLUGIN_DIR . $section['template'];
                $html .= ob_get_clean();
            }
        }

        $global_index++;
    }

    // Generate AJAX pagination HTML
    ob_start();
    core_ajax_pagination($total_results, $vdpPerPage, $page); //
    $pagination_html = ob_get_clean();

    wp_send_json_success([
        'html'        => $html,
        'next_page'   => $page + 1,
        'pagination'   => $pagination_html,
        'total_found' => $total_results,
        'max_pages'   => $max_pages
    ]);
}
