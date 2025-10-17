<?php     
    // $vdpPerPage = get_field('vdp_per_page', 'option');
    // $max_pages = rsl_get_max_pages($all_stock_data, $vdpPerPage);
    // $current_page = 2;

    $vdpPerPage   = get_field('vdp_per_page', 'option');
    $total_stocks = count($all_stock_data);
    $current_page = 1;
?>

<div class="load-more-btn my-4 row">
                <!-- <div class="col">
                    <button next-page= <?php echo $current_page;?> max-pages= <?php echo $max_pages; ?> class="gfam-default-btn w-auto mx-auto d-block load-more-stocks-btn" >
                        Load More...
                    </button>
                </div> -->

                <?php 
                $total_items = count($all_stock_data);
                $per_page    = get_field('vdp_per_page', 'option');
                $current_page = max(1, get_query_var('paged', 1));
                $total_pages = ceil($total_items / $per_page);

                // if ($total_pages > 1) {
                //     echo paginate_links([
                //         'base'      => '%_%',
                //         'format'    => '?paged=%#%',
                //         'current'   => $current_page,
                //         'total'     => $total_pages,
                //         'prev_text' => '&laquo; Prev',
                //         'next_text' => 'Next &raquo;',
                //         'type'      => 'list'
                //     ]);
                // }

                core_ajax_pagination($total_items, $per_page, $current_page);
                // rsl_ajax_paginate($total_stocks, $vdpPerPage, $current_page);?>
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
