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
                $images[] = (string) $img['url']; // attribute url=""
            }
        }        
        
        $entry = [
            'dealer_id'    => (string)$dealer['id'],
            'dealer_name'  => (string)$dealer->name,
            'stock_number' => (string)$listing->stock_number,
            'industry'     => (string)$listing->industry,
            'item_specification' => (string)$listing->model_specific,
            'type'         => (string)$listing->type,
            'subtype'      => (string)$listing->subtype,
            'make'         => (string)$listing->make,
            'model'        => (string)$listing->model,
            'year'         => rsl_get_attribute_value($listing, 'Year'),
            'status'       => rsl_get_attribute_value($listing, 'Status'),
            'listing_type' => rsl_get_attribute_value($listing, 'Listing Type'),
            'price'        => rsl_get_attribute_value($listing, 'Retail Price'),
            'hours'        => rsl_get_attribute_value($listing, 'Hours'),
            'images'       => $images,
        ];
        $listings[] = $entry;
    }

    return $listings;
}

function rsl_get_attribute_value( $listing, $attrName ) {    

    foreach ($listing->attributes->attribute as $attr) {
        if ((string)$attr['name'] === $attrName) {
            return (string)$attr;
        }
    }
    return null;
}

// function rsl_apply_filters( $listings, $filters ) {    

//     $hasCategory     = !empty($filters['categories']);
//     $hasMakeModel    = !empty($filters['makeModel']);    
//     $hasYearSelected = ( ( isset($filters['year_from']) && !empty($filters['year_from']) ) || ( isset($filters['year_to']) && !empty($filters['year_to'])));    

//     $listings = array_filter($listings, function($l) use ($filters, $hasCategory, $hasMakeModel) {

//         // Category/Subtype
//         $matchCategory = true;
//         if ( $hasCategory ) {
//             $matchCategory = in_array($l['type'], $filters['categories']) || in_array($l['subtype'], $filters['categories']);
//         }

//         // Make/Model
//         $matchMakeModel = true;
//         if ( $hasMakeModel ) {
//             $matchMakeModel = in_array($l['make'], $filters['makeModel']) || in_array($l['model'], $filters['makeModel']);
//         }
//         // // Year range
//         $matchYear = true;
//         if ( $hasYearSelected ) {
//             $year = intval($l['year']);
//             if ( isset($filters['year_from']) && $filters['year_from'] !== '' && $year < intval($filters['year_from']) ) {
//                 $matchYear = false;
//             }
//             if ( isset($filters['year_to']) && $filters['year_to'] !== '' && $year > intval($filters['year_to']) ) {
//                 $matchYear = false;
//             }
//         }

//         // Logic: if both category and make/model exist = AND, else = OR
//         if ( $hasCategory && $hasMakeModel ) {
//             return $matchCategory && $matchMakeModel;
//         } else if( $hasCategory ) {
//             return $matchCategory;
//         }else if( $hasMakeModel ){
//             return $matchMakeModel;
//         }
//     });

//     return $listings;
// }

function rsl_apply_filters( $listings, $filters ) {

    return array_filter($listings, function($l) use ($filters) {

        $matches = []; // store all matching results here

        // --- Category/Subtype ---
        if ( !empty($filters['categories']) ) {
            $matches[] = in_array($l['type'], $filters['categories']) || in_array($l['subtype'], $filters['categories']);
        }

        // --- Make/Model ---
        if ( !empty($filters['makeModel']) ) {
            $matches[] = in_array($l['make'], $filters['makeModel']) || in_array($l['model'], $filters['makeModel']);
        }

        // --- Listing Type i.e New, Used ---
        if ( !empty($filters['Type']) ) {
            $matches[] = in_array($l['listing_type'], $filters['Type']);
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
        return strpos(strtolower($l['make']), $query) !== false ||
               strpos(strtolower($l['model']), $query) !== false;
    });
}

function rsl_sort( $listings, $sortKey ) {
    usort($listings, function ($a, $b) use ($sortKey) {
        switch ($sortKey) {
            case 'price_asc': return floatval($a['price']) <=> floatval($b['price']);
            case 'price_desc': return floatval($b['price']) <=> floatval($a['price']);
            case 'year_desc': return intval($b['year']) <=> intval($a['year']);
            case 'year_asc': return intval($a['year']) <=> intval($b['year']);
            default: return 0;
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