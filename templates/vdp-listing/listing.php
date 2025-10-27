<!-- Product Grid -->
<div class="gfam-product-grid row">
    <?php 
        global $xmlPath;
        $vdpPerPage = get_field('vdp_per_page', 'option');
        $all_stock_data = rsl_parse_listings( $xmlPath );
        $paginated_listings = rsl_load_more($all_stock_data, 0, $vdpPerPage);

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
                
        foreach ($paginated_listings as $item) {
            include RSL_PLUGIN_DIR . 'templates/parts/product-card.php';

            // Check if any custom section should appear after this product
            foreach ($custom_sections as $section) {
                if ($section['is_enabled'] && $section['position'] == $global_index) {                                      
                    include RSL_PLUGIN_DIR . $section['template'];
                }
            }

            $global_index++;
        }
    ?>       
</div>