<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rsl_parse_listings( $xmlPath ) {
    $dealer = simplexml_load_file( $xmlPath );
    $listings = [];

    foreach ( $dealer->listing as $listing ) {        
        
        // Collect all image URLs
        $images = [];
        if ( isset( $listing->Images->Image ) ) {
            foreach ( $listing->Images->Image as $img ) {
                $images[] = (string) $img['url'];
            }
        }

        // Helper: trim all fields that may contain trailing spaces
        $clean = fn($v) => trim((string)$v);

        $entry = [
            'dealer_id'             => (string)$dealer['id'],
            'dealer_name'           => trim((string)$dealer->name),
            'stock_number'          => trim((string)$listing->stock_number),
            'industry'              => trim((string)$listing->industry),
            'item_specification'    => trim((string)$listing->model_specific),
            'type'                  => $clean($listing->type),
            'subtype'               => $clean($listing->subtype),
            'make'                  => $clean($listing->make),
            'model'                 => $clean($listing->model),
            'listing_type'          => trim(rsl_get_attribute_value($listing, 'Listing Type')),
            'year'                  => trim(rsl_get_attribute_value($listing, 'Year')),
            'description'           => trim(rsl_get_attribute_value($listing, 'Description')),
            'status'                => trim(rsl_get_attribute_value($listing, 'Status')),
            'price'                 => trim(rsl_get_attribute_value($listing, 'Retail Price')),
            'hours'                 => trim(rsl_get_attribute_value($listing, 'Hours')),
            'images'                => $images,
        ];

        $listings[] = $entry;
    }

    return $listings;
}

function rsl_get_filter_data_for_localization() {
    global $xmlPath;
    
    $listings = rsl_parse_listings($xmlPath);

    $makes = [];
    $models = [];
    $categories = [];
    $types = [];

    foreach ($listings as $listing) {
        if (!empty($listing['make'])) {
            $makes[] = $listing['make'];
        }
        if (!empty($listing['model'])) {
            $models[] = $listing['model'];
        }
        if (!empty($listing['listing_type'])) {
            $types[] = $listing['listing_type'];
        }
        if (!empty($listing['subtype'])) {
            $categories[] = $listing['type'];
            $categories[] = $listing['subtype'];
        }
    }

    // Remove duplicates and sort
    $makes       = array_values(array_unique($makes));
    $models      = array_values(array_unique($models));
    $categories  = array_values(array_unique($categories));
    $types       = array_values(array_unique($types));

    sort($makes);
    sort($models);
    sort($categories);
    sort($types);

    // Build slug maps (slug => original)
    $slugify = function($arr) {
        $map = [];
        foreach ($arr as $item) {
            $slug = sanitize_title($item); // WordPress-style slug
            $map[$slug] = $item;
        }
        return $map;
    };

    return [
        'validMakes'        => $makes,
        'validModels'       => $models,
        'validCategories'   => $categories,
        'validTypes'        => $types,
        'slugMap' => [
            'makes'       => $slugify($makes),
            'models'      => $slugify($models),
            'categories'  => $slugify($categories),
            'types'       => $slugify($types),
        ]
    ];
}

function rsl_get_attribute_value( $listing, $attrName ) {    

    foreach ($listing->attributes->attribute as $attr) {
        if ((string)$attr['name'] === $attrName) {
            return (string)$attr;
        }
    }
    return null;
}

if (! function_exists('stripslashes_deep')) {
    function stripslashes_deep($value) {
        return is_array($value)
            ? array_map('stripslashes_deep', $value)
            : stripslashes($value);
    }
}

function rsl_apply_filters( $listings, $filters ) {

    // Clean all incoming filter values ONCE
    $filters = stripslashes_deep($filters);

    return array_filter($listings, function($l) use ($filters) {

        $matches = []; // store all matching results here

        // --- Category/Subtype ---
        if ( !empty($filters['categories']) ) {
            $matches[] = in_array($l['type'], $filters['categories']) || in_array($l['subtype'], $filters['categories']);
        }

        // --- Make/Model ---
        if ( !empty($filters['make']) ) {
            $matches[] = in_array($l['make'], $filters['make']);
        }

        if ( !empty($filters['model']) ) {
            $matches[] = in_array($l['model'], $filters['model']);
        }

        // --- Listing Type i.e New, Used ---
        if ( !empty($filters['type']) ) {
            $matches[] = in_array($l['listing_type'], $filters['type']);
        }

        // --- Price Range ---
        if ( !empty($filters['price_from']) || !empty($filters['price_to']) ) {
            $price = intval($l['price']);
            $match = true;
            if ( !empty($filters['price_from']) && $price < intval($filters['price_from']) ) $match = false;
            if ( !empty($filters['price_to']) && $price > intval($filters['price_to']) ) $match = false;
            $matches[] = $match;
        }

        // --- Year Range ---
        if ( !empty($filters['year_from']) || !empty($filters['year_to']) ) {
            $year = intval($l['year']);
            $match = true;
            if ( !empty($filters['year_from']) && $year < intval($filters['year_from']) ) $match = false;
            if ( !empty($filters['year_to']) && $year > intval($filters['year_to']) ) $match = false;
            $matches[] = $match;
        }
        
        // --- Hours Range ---
        if ( !empty($filters['hours_from']) || !empty($filters['hours_to']) ) {
            $year = intval($l['hours']);
            $match = true;
            if ( !empty($filters['hours_from']) && $year < intval($filters['hours_from']) ) $match = false;
            if ( !empty($filters['hours_to']) && $year > intval($filters['hours_to']) ) $match = false;
            $matches[] = $match;
        }

        // If no filters applied → keep all listings
        if (empty($matches)) {
            return true;
        }

        // --- Combine Logic ---
        // Default: all filters must match (AND logic)
        // Optional: if you want OR logic, you can easily switch below.
        $andLogic = true;

        if ($andLogic) {
            return !in_array(false, $matches, true); // all must be true
        } else {
            return in_array(true, $matches, true); // any can be true
        }
    });
}

function rsl_load_more( $listings, $offset = 0, $limit = 10 ) {
    return array_slice($listings, $offset, $limit);
}

/**
 * Get max number of pages.
 */
function rsl_get_max_pages( $stock_data, $per_page = 10 ) {
    if ( $per_page < 1 ) $per_page = 10; // fallback
    return (int) ceil( count($stock_data) / $per_page );
}

function rsl_search( $listings, $query ) {
    $query = strtolower(trim($query));

    return array_filter($listings, function ($l) use ($query) {
        $make  = isset($l['make']) ? strtolower(trim($l['make'])) : '';
        $model = isset($l['model']) ? strtolower(trim($l['model'])) : '';
        $title = trim("$make $model"); // Combine both for full-title search

        // Match if query appears in make, model, or combined title
        return strpos($make, $query) !== false ||
               strpos($model, $query) !== false ||
               strpos($title, $query) !== false;
    });
}

/**
 * Core PHP AJAX-ready pagination
 *
 * @param int $total_items Total number of items
 * @param int $per_page Items per page
 * @param int $current_page Current page number
 * @param int $adjacents Number of pages to show on each side of current
 */
function core_ajax_pagination($total_items, $per_page, $current_page = 1, $adjacents = 2) {
    // echo $total_items;die();
    $total_pages = ceil($total_items / $per_page);
    if ($total_pages <= 1) return;

    echo '<ul class="ajax-pagination">';

    // Previous button
    if ($current_page > 1) {
        echo '<li><span class="prev-page" data-page="' . ($current_page - 1) . '">
        <img src="' . esc_url(RSL_PLUGIN_URL . 'assets/images/left-arrow.svg') . '" class="w-100 mx-auto" alt="" />
        </span></li>';
    } else {
        echo '<li><span class="prev-page disabled">
        <img src="' . esc_url(RSL_PLUGIN_URL . 'assets/images/left-arrow.svg') . '" class="w-100 mx-auto" alt="" />
        </span></li>';
    }

    // Pages
    for ($i = 1; $i <= $total_pages; $i++) {
        // Always show first, last, current, and adjacents
        if ($i == 1 || $i == $total_pages || ($i >= $current_page - $adjacents && $i <= $current_page + $adjacents)) {
            if ($i == $current_page) {
                echo '<li><span class="current">' . $i . '</span></li>';
            } else {
                echo '<li><span class="page-number" data-page="' . $i . '">' . $i . '</span></li>';
            }
        }
        // Show ellipses if there’s a gap
        elseif ($i == 2 && $current_page - $adjacents > 2) {
            echo '<li><span class="dots">…</span></li>';
        }
        elseif ($i == $total_pages - 1 && $current_page + $adjacents < $total_pages - 1) {
            echo '<li><span class="dots">…</span></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        echo '<li><span class="next-page" data-page="' . ($current_page + 1) . '">
                <img src="' . esc_url(RSL_PLUGIN_URL . 'assets/images/right-arrow.svg') . '" class="w-100 mx-auto" alt="" />
              </span></li>';
    } else {
        echo '<li><span class="next-page disabled">
        <img src="' . esc_url(RSL_PLUGIN_URL . 'assets/images/right-arrow.svg') . '" class="w-100 mx-auto" alt="" />
        </span></li>';
    }

    echo '</ul>';
}

function rsl_sort($listings, $sortKey) {
    usort($listings, function ($a, $b) use ($sortKey) {
        switch ($sortKey) {

            // Price
            case 'price_asc':
                return floatval($a['price']) <=> floatval($b['price']);
            case 'price_desc':
                return floatval($b['price']) <=> floatval($a['price']);

            // Year
            case 'year_asc':
                return intval($b['year']) <=> intval($a['year']);
            case 'year_desc':
                return intval($a['year']) <=> intval($b['year']);

            // KMs
            case 'kms_asc':
                return intval($a['hours']) <=> intval($b['hours']);
            case 'kms_desc':
                return intval($b['hours']) <=> intval($a['hours']);

            // Make / Model
            case 'make_model_az':
                $makeCmp = strcasecmp($a['make'], $b['make']);
                if ($makeCmp === 0) {
                    return strcasecmp($a['model'], $b['model']);
                }
                return $makeCmp;

            case 'make_model_za':
                $makeCmp = strcasecmp($b['make'], $a['make']);
                if ($makeCmp === 0) {
                    return strcasecmp($b['model'], $a['model']);
                }
                return $makeCmp;

            // Newest / Oldest (assuming array is time-ordered or has 'created' field)
            case 'newest':
                // return strtotime($b['created'] ?? 0) <=> strtotime($a['created'] ?? 0);
                return 0;
            case 'oldest':
                // return strtotime($a['created'] ?? 0) <=> strtotime($b['created'] ?? 0);
                return 0;

            // Latest Update (if you have 'updated' field)
            case 'latest_update':
                // return strtotime($b['updated'] ?? 0) <=> strtotime($a['updated'] ?? 0);
                return 0;

            default:
                return 0; // Relevancy or no sorting
        }
    });

    return $listings;
}

function rsl_get_category_filters() {
    global $xmlPath;
    
    $allListings = rsl_parse_listings( $xmlPath );
    $typeTree = [];

    foreach ( $allListings as $item ) {
        $type    = trim($item['type']);
        $subtype = trim($item['subtype']);
        if ( ! $type ) continue;

        // Ensure type array exists
        if ( ! isset($typeTree[$type]) ) {
            $typeTree[$type] = [
                'count'    => 0,
                'subtypes' => []
            ];
        }

        // Increment type count
        $typeTree[$type]['count']++;

        // Add / increment subtype count
        if ( $subtype ) {
            if ( ! isset($typeTree[$type]['subtypes'][$subtype]) ) {
                $typeTree[$type]['subtypes'][$subtype] = 0;
            }
            $typeTree[$type]['subtypes'][$subtype]++;
        }
    }

    // Sort types alphabetically
    ksort($typeTree);

    // Sort subtypes alphabetically inside each type
    foreach ( $typeTree as &$data ) {
        ksort($data['subtypes']);
    }

    return $typeTree;
}


function rsl_get_make_model_filters() {
    global $xmlPath;
    $allListings = rsl_parse_listings( $xmlPath );
    $makeModelTree = [];

    foreach ( $allListings as $item ) {
        $make  = trim($item['make']);
        $model = trim($item['model']);
        if ( ! $make ) continue;

        // Ensure make entry exists
        if ( ! isset( $makeModelTree[$make] ) ) {
            $makeModelTree[$make] = [
                'count'  => 0,
                'models' => []
            ];
        }

        // Increment make count
        $makeModelTree[$make]['count']++;

        // Add/increment model count
        if ( $model ) {
            if ( ! isset( $makeModelTree[$make]['models'][$model] ) ) {
                $makeModelTree[$make]['models'][$model] = 0;
            }
            $makeModelTree[$make]['models'][$model]++;
        }
    }

    // Sort alphabetically
    ksort( $makeModelTree );
    foreach ( $makeModelTree as &$data ) {
        ksort( $data['models'] );
    }

    return $makeModelTree;
}

function rsl_build_product_name( $item ) {
    $parts = [];

    if ( !empty($item['year']) ) {
        $parts[] = trim($item['year']);
    }
    if ( !empty($item['make']) ) {
        $parts[] = trim($item['make']);
    }
    if ( !empty($item['model']) ) {
        $parts[] = trim($item['model']);
    }

    // Join with single spaces
    return implode(' ', $parts);
}

if ( ! function_exists( 'enable_svg_uploads' ) ) {
    function enable_svg_uploads( $mimes ) {
        // Add SVG mime type
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter( 'upload_mimes', 'enable_svg_uploads' );
}

/**
 * Check if the current page is the Stock Locator page
 *
 * @return bool
 */
function is_stock_locator_page() {
    // Get selected Stock Locator page ID from ACF Options
    $page_id = get_field('select_stock_locator_page', 'option');

    // Bail early if not set
    if ( ! $page_id ) {
        return false;
    }

    // Get permalink for the Stock Locator page
    $stock_locator_url = trailingslashit( get_permalink( $page_id ) );

    // Get current page URL
    $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Remove query string
    $current_url_clean = strtok( $current_url, '?' );

    // Normalize trailing slashes
    $current_url_clean    = trailingslashit( strtolower( $current_url_clean ) );
    $stock_locator_url    = trailingslashit( strtolower( $stock_locator_url ) );

    // Check if current URL starts with the stock locator base
    return str_starts_with( $current_url_clean, $stock_locator_url );
}

function gfam_add_listing_detail_rewrite_rule() {
    $detail_page = get_field('select_stock_locator_detail_page', 'option');

    if ($detail_page) {
        $detail_page_slug = $detail_page->post_name;

        add_rewrite_rule(
            '^' . $detail_page_slug . '/([^/]+)/?',
            'index.php?pagename=' . $detail_page_slug . '&stock_number=$matches[1]',
            'top'
        );
    }
}
add_action('init', 'gfam_add_listing_detail_rewrite_rule');

// Register query var so WordPress recognizes it
function gfam_add_stock_number_query_var($vars) {
    $vars[] = 'stock_number';
    return $vars;
}
add_filter('query_vars', 'gfam_add_stock_number_query_var');

//Auto-flush rewrite rules when ACF option "select_stock_locator_detail_page" changes
add_action('acf/save_post', 'gfam_schedule_rewrite_check_after_acf_option_save', 20);
function gfam_schedule_rewrite_check_after_acf_option_save($post_id) {
    // Only run for the ACF Options page
    if ($post_id !== 'options') {
        return;
    }

    // Get the selected detail page
    $detail_page = get_field('select_stock_locator_detail_page', 'option');

    if ($detail_page) {
        // Handle both Post Object and URL return types
        if (is_object($detail_page) && isset($detail_page->post_name)) {
            $detail_slug = $detail_page->post_name;
        } else {
            $detail_slug = basename(parse_url($detail_page, PHP_URL_PATH));
        }

        // If a valid slug exists, store it as an option flag
        if (!empty($detail_slug)) {
            update_option('gfam_pending_flush', $detail_slug);
        }
    }
}

// Run this on the next init (or admin_init) to flush rewrite rules only once
add_action('admin_init', 'gfam_maybe_flush_rewrite_rules');
add_action('init', 'gfam_maybe_flush_rewrite_rules');
function gfam_maybe_flush_rewrite_rules() {
    $pending_flush = get_option('gfam_pending_flush');

    if (!empty($pending_flush)) {
        flush_rewrite_rules();
        delete_option('gfam_pending_flush');
    }
}

add_filter('pre_get_document_title', 'gfam_dynamic_stock_detail_title');
add_filter('wpseo_title', 'gfam_dynamic_stock_detail_title');

function gfam_dynamic_stock_detail_title($title) {
    global $xmlPath;
    $stock_number = get_query_var('stock_number');
    $stock_number_parts = explode('-', $stock_number);
    $stock_number = strtoupper(end($stock_number_parts));

    if (empty($stock_number)) {
        return $title;
    }

    $allListingsData = rsl_parse_listings($xmlPath);
    if (empty($allListingsData)) {
        return $title;
    }

    foreach ($allListingsData as $listing) {
        $listing_stock = str_replace(['-', ' ', '_'], '', $listing['stock_number']);
        if ($listing_stock === $stock_number) {
            $ttl = trim($listing['year'] . ' ' . $listing['make'] . ' ' . $listing['model']);
            $detail_page = get_field('select_stock_locator_detail_page', 'option');
            if (empty($ttl)) {
                $ttl = $detail_page->post_title;
            }

            return esc_html($ttl . ' - ' . get_bloginfo('name'));
        }
    }

    return $title;
}

add_action('wp_head', 'gfam_force_dynamic_meta_for_stock_detail', 20);
function gfam_force_dynamic_meta_for_stock_detail() {
    global $xmlPath;

    $stock_number = get_query_var('stock_number');
    $stock_number_parts = explode('-', $stock_number);
    $stock_number = strtoupper(end($stock_number_parts));
    if (empty($stock_number)) {
        return;
    }

    $allListingsData = rsl_parse_listings($xmlPath);
    if (empty($allListingsData)) {
        return;
    }

    foreach ($allListingsData as $listing) {
        $listing_stock = str_replace(['-', ' ', '_'], '', $listing['stock_number']);
        if ($listing_stock === $stock_number) {
            $title = trim($listing['year'] . ' ' . $listing['make'] . ' ' . $listing['model']);
            $detail_page = get_field('select_stock_locator_detail_page', 'option');
            if (empty($title)) {
                $title = $detail_page->post_title;
            }
            $meta_title = esc_html($title);
            $meta_desc  = esc_attr($listing['item_specification']);

            // Output meta only once — after SEO plugins finish
            echo "\n<!-- Dynamic SEO Meta -->\n";
            echo '<meta name="title" content="' . $meta_title . '">' . "\n";
            echo '<meta name="description" content="' . $meta_desc . '">' . "\n";
            echo '<meta property="og:title" content="' . $meta_title . '">' . "\n";
            echo '<meta property="og:description" content="' . $meta_desc . '">' . "\n";
            echo '<meta name="twitter:title" content="' . $meta_title . '">' . "\n";
            echo '<meta name="twitter:description" content="' . $meta_desc . '">' . "\n";
            echo "<!-- End Dynamic SEO Meta -->\n";

            break;
        }
    }
}



/* site map link generate in the Yoast SEO */

// Register a custom VDP sitemap with Yoast
add_action('init', function() {
    $vdp_page_id = get_field('select_stock_locator_page', 'option');

    if ($vdp_page_id) {
        $vdp_page = get_post($vdp_page_id);
        if ($vdp_page && !empty($vdp_page->post_name)) {
            $slug = $vdp_page->post_name;

            // Create dynamic rewrite rule like "stock-locator.xml"
            add_rewrite_rule($slug . '\.xml$', 'index.php?vdp_sitemap=1', 'top');
        }
    }
});

add_filter('query_vars', function($vars) {
    $vars[] = 'vdp_sitemap';
    return $vars;
});

add_action('template_redirect', function() {
    if (get_query_var('vdp_sitemap')) {
        gfam_output_vdp_sitemap();
        exit;
    }
});

// Add dynamic sitemap link into Yoast main sitemap index
add_filter('wpseo_sitemap_index', function($sitemap_index) {
    $vdp_page_id = get_field('select_stock_locator_page', 'option');
    if ($vdp_page_id) {
        $vdp_page = get_post($vdp_page_id);
        if ($vdp_page && !empty($vdp_page->post_name)) {
            $slug = $vdp_page->post_name;
            $home_url = home_url();
            $sitemap_index .= '<sitemap>';
            $sitemap_index .= '<loc>' . esc_url($home_url . '/' . $slug . '.xml') . '</loc>';
            $sitemap_index .= '<lastmod>' . esc_html(date('c')) . '</lastmod>';
            $sitemap_index .= '</sitemap>';
        }
    }
    return $sitemap_index;
});


function gfam_output_vdp_sitemap() {
    global $xmlPath;

    header('Content-Type: application/xml; charset=UTF-8');

    // Get the VDP detail page from ACF options
    $vdp_detail_page = get_field('select_stock_locator_detail_page', 'option');
    
    if (empty($vdp_detail_page) || !is_object($vdp_detail_page)) {
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
        exit;
    }

    $vdp_page_url = get_permalink($vdp_detail_page->ID);
    $allListingsData = rsl_parse_listings($xmlPath);
    $today = date('c');

 

    // Output XML header with Yoast XSL
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<?xml-stylesheet type="text/xsl" href="' . esc_url(home_url('/main-sitemap.xsl')) . '"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    if (!empty($allListingsData)) {
        foreach ($allListingsData as $listing) {
            if (!empty($listing['stock_number'])) {

                   $stock_number = !empty($listing['stock_number'])
                    ? strtolower(str_replace(['-', ' ', '_'], '', $listing['stock_number']))
                    : 'N/A';
                    
                    $slug_title_like = strtolower(trim($listing['year'] . '-' . $listing['make'] . '-' . $listing['model']));
                    $slug_title_like = sanitize_title($slug_title_like);
                    if($slug_title_like !=''){
                        $slug_title_like = $slug_title_like. '-' . $stock_number;
                    }else{
                        $slug_title_like = gfam_generate_slug_preserve_case($vdp_detail_page->post_name). '-' . $stock_number;
                    }

                $url = trailingslashit($vdp_page_url) .''. $slug_title_like . '/';
                echo "  <url>\n";
                echo '    <loc>' . esc_url($url) . "</loc>\n";
                echo '    <lastmod>' . esc_html($today) . "</lastmod>\n";
                echo "  </url>\n";
            }
        }
    }

    echo '</urlset>';
}

function gfam_generate_slug_preserve_case($text) {
    $text = str_replace(['/', ' '], '-', $text);
    $text = trim($text, '-');
    return strtolower($text);
}
