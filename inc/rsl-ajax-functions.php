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
        $no_result_found = "<h2>". __('No stock available', 'retain-stock-locator')."</h2>";

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
    foreach ( $paged_listings as $item ) {
        ob_start();
        include RSL_PLUGIN_DIR . 'templates/parts/product-card.php';
        $html .= ob_get_clean();
    }

    // Generate AJAX pagination HTML
    $pagination_html = core_ajax_pagination_html($total_results, $vdpPerPage, $page);

    wp_send_json_success([
        'html'        => $html,
        'next_page'   => $page + 1,
        'pagination'   => $pagination_html,
        'total_found' => $total_results,
        'max_pages'   => $max_pages
    ]);
}
