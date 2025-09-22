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
            'type'         => (string)$listing->type,
            'subtype'      => (string)$listing->subtype,
            'make'         => (string)$listing->make,
            'model'        => (string)$listing->model,
            'year'         => rsl_get_attribute_value($listing, 'Year'),
            'status'       => rsl_get_attribute_value($listing, 'Status'),
            'listing_type' => rsl_get_attribute_value($listing, 'Listing Type'),
            'price'        => rsl_get_attribute_value($listing, 'Price'),
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

function rsl_apply_filters( $listings, $filters ) {
    if ( isset($filters['make']) ) {
        $listings = array_filter($listings, function($l) use ($filters) {
            return stripos($l['make'], $filters['make']) !== false;
        });
    }
    if ( isset($filters['model']) ) {
        $listings = array_filter($listings, function($l) use ($filters) {
            return stripos($l['model'], $filters['model']) !== false;
        });
    }
    if ( isset($filters['type']) ) {
        $listings = array_filter($listings, function($l) use ($filters) {
            return $l['type'] === $filters['type'];
        });
    }
    // Extend with other filters...

    return $listings;
}

function rsl_load_more( $listings, $offset = 0, $limit = 10 ) {
    return array_slice($listings, $offset, $limit);
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