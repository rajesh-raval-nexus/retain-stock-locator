<?php     
    $vdpPerPage = get_field('vdp_per_page', 'option');
    $max_pages = rsl_get_max_pages($all_stock_data, $vdpPerPage);
    $current_page = 2;
?>

<div class="load-more-btn my-4 row">
                <div class="col">
                    <button next-page= <?php echo $current_page;?> max-pages= <?php echo $max_pages; ?> class="gfam-default-btn w-auto mx-auto d-block load-more-stocks-btn" >
                        Load More...
                    </button>
                </div>
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
