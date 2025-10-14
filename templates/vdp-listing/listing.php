<!-- Product Grid -->
<div class="gfam-product-grid row">
    <?php 
        global $xmlPath;
        $vdpPerPage = get_field('vdp_per_page', 'option');
        $all_stock_data = rsl_parse_listings( $xmlPath );
        $paginated_listings = rsl_load_more($all_stock_data, 0, $vdpPerPage);
                
        foreach ($paginated_listings as $item) {
            include RSL_PLUGIN_DIR . 'templates/parts/product-card.php';
        }
    ?>       
</div>