<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rsl_parse_listings( $xmlPath ) {
    $dealer = simplexml_load_file( $xmlPath );
    $listings = [];

    foreach ( $dealer->listing as $listing ) {
        $entry = [
            'dealer_id'    => (string)$dealer['id'],
            'dealer_name'  => (string)$dealer->name,
            'stock_number' => (string)$listing->stock_number,
            'industry'     => (string)$listing->industry,
            'type'         => (string)$listing->type,
            'subtype'      => (string)$listing->subtype,
            'make'         => (string)$listing->make,
            'model'        => (string)$listing->model,
            'year'         => rsl_get_attribute_value( $listing, 'Year' ),
            'status'       => rsl_get_attribute_value( $listing, 'Status' ),
            'listing_type' => rsl_get_attribute_value( $listing, 'Listing Type' ),
            'price'        => rsl_get_attribute_value( $listing, 'Price' ),
            'hours'        => rsl_get_attribute_value( $listing, 'Hours' ),
        ];
        $listings[] = $entry;
    }

    return $listings;
}

function rsl_get_attribute_value( $listing, $attrName ) {
    foreach ( $listing->attributes->attribute as $attr ) {
        if ( (string)$attr['name'] === $attrName ) {
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

function rsl_get_category_filters( $xmlPath ) {
    $allListings = rsl_parse_listings( $xmlPath );
    $typeTree = [];

    foreach ($allListings as $item) {
        $type = trim($item['type']);
        $subtype = trim($item['subtype']);
        if (!$type) continue;

        if (!isset($typeTree[$type])) {
            $typeTree[$type] = [];
        }

        if ($subtype && !in_array($subtype, $typeTree[$type])) {
            $typeTree[$type][] = $subtype;
        }
    }

    ksort($typeTree);
    foreach ($typeTree as &$subtypes) {
        sort($subtypes);
    }

    return $typeTree;
}
