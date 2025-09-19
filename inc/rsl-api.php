<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Global variable to store the helper instance.
global $rsl_helper;
$rsl_helper = null;

/**
 * Initialize RSL API.
 *
 * @param string $xmlPath Path to XML file.
 */
function rsl_api_init( $xmlPath ) {
    global $rsl_helper;

    // Load helper instance.
    if ( ! function_exists( 'rsl_helper_init' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'functions-rsl_helper.php';
    }

    $rsl_helper = rsl_helper_init( $xmlPath );

    // Register REST routes.
    add_action( 'rest_api_init', 'rsl_api_register_routes' );
}

/**
 * Register REST API routes.
 */
function rsl_api_register_routes() {
    register_rest_route( 'rsl/v1', '/listings', [
        'methods'  => 'GET',
        'callback' => 'rsl_api_get_listings',
        'args'     => [
            'filters' => [
                'description' => 'Array of filters',
                'type'        => 'array',
            ],
            'search' => [
                'description' => 'Search query',
                'type'        => 'string',
            ],
            'sort' => [
                'description' => 'Sort key (price_asc, year_desc etc)',
                'type'        => 'string',
            ],
            'offset' => [
                'description' => 'Offset for pagination',
                'type'        => 'integer',
            ],
            'limit' => [
                'description' => 'Limit for pagination',
                'type'        => 'integer',
            ],
        ],
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( 'rsl/v1', '/category-filters', [
        'methods'  => 'GET',
        'callback' => 'rsl_api_get_category_filters',
        'permission_callback' => '__return_true',
    ] );
}

/**
 * REST: Get listings.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function rsl_api_get_listings( $request ) {
    global $rsl_helper;

    if ( ! $rsl_helper ) {
        return rest_ensure_response( [] );
    }

    $listings = rsl_helper_parse_listings();

    // Apply filters
    $filters = $request->get_param('filters');
    if ( $filters ) {
        $listings = rsl_helper_apply_filters( $listings, $filters );
    }

    // Search
    $search = $request->get_param('search');
    if ( $search ) {
        $listings = rsl_helper_search( $listings, $search );
    }

    // Sorting
    $sort = $request->get_param('sort');
    if ( $sort ) {
        $listings = rsl_helper_sort( $listings, $sort );
    }

    // Pagination
    $offset = intval( $request->get_param('offset') ?? 0 );
    $limit  = intval( $request->get_param('limit') ?? 10 );
    $listings = rsl_helper_load_more( $listings, $offset, $limit );

    return rest_ensure_response( array_values( $listings ) );
}

/**
 * REST: Get category filters.
 *
 * @return WP_REST_Response
 */
function rsl_api_get_category_filters() {
    return rest_ensure_response( rsl_helper_get_category_filters() );
}
