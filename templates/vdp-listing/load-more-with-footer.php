<?php         
    $vdpPerPage   = get_field('vdp_per_page', 'option');
    $total_stocks = count($all_stock_data);
    $current_page = 1;
?>

<div class="load-more-btn my-4 row">               
            <?php 
            $total_items = count($all_stock_data);
            $per_page    = get_field('vdp_per_page', 'option');
            $current_page = max(1, get_query_var('paged', 1));
            $total_pages = ceil($total_items / $per_page);                
            core_ajax_pagination($total_items, $per_page, $current_page);
            ?>
        </div>
    </div>
</div>

<!-- Category Modal -->
<?php echo do_shortcode('[rsl_category_options]'); ?>

<!-- Make Modal -->
<?php echo do_shortcode('[rsl_make_model_options]');?>

<!-- Type Modal -->
<?php echo do_shortcode('[rsl_type_options]');?>

<!-- Price Range Modal -->    
<?php echo do_shortcode('[rsl_price_options]')?>

<!-- Year Modal -->
<?php echo do_shortcode('[rsl_year_options]')?>

<!-- Hours Modal -->
<?php echo do_shortcode('[rsl_hours_options]')?>
