<?php
/**
 * Product Card Partial
 * 
 * @param array $item
 */

if ( empty($item) || !is_array($item) ) {
    return;
}

$product_title       = rsl_build_product_name($item);
$product_images      = (isset($_POST) && $_POST['action'] == 'rsl_get_stock_list') ? $item['images'] : (array) $item['images'][0];
$listing_type        = !empty($item['listing_type']) ? $item['listing_type'] : 'N/A';
$stock_number = !empty($item['stock_number'])
    ? strtolower(str_replace(['-', ' ', '_'], '', $item['stock_number']))
    : 'N/A';

$item_specification  = !empty($item['item_specification']) ? $item['item_specification'] : 'N/A';
$hours               = !empty($item['hours']) ? (int) $item['hours'] : 'N/A';
$price               = !empty($item['price']) ? (int) $item['price'] : 'N/A';

$detail_page = get_field('select_stock_locator_detail_page', 'option');

$detail_page_slug = isset($detail_page->post_name) ? $detail_page->post_name : 'stock-detail';

$slug_title = strtolower(trim($item['year'] . '-' . $item['make'] . '-' . $item['model']));
$slug_title = sanitize_title($slug_title);
if($slug_title !=''){
    $slug_title = $slug_title;
}

if (!empty($slug_title)) {
    $final_slug = "{$slug_title}-{$stock_number}";
} else {
    $final_slug = "{$stock_number}";
}

$detail_url = site_url("/{$detail_page_slug}/{$final_slug}/");

?>

<div class="col-lg-4 col-md-6 my-3">
    <div class="gfam-product-card">
        <div class="gfam-product-image">
            <div class="owl-carousel gfam-carousel">
                <?php if (!empty($product_images)) :
                    foreach ($product_images as $image) : 
                        $static_placeholder_url = RSL_PLUGIN_URL . 'assets/images/sample.png';
                        $img_url = (isset($_POST) && $_POST['action'] == 'rsl_get_stock_list') ? $image : $static_placeholder_url;
                    ?>
                        <div class="item">
                            <a href="<?php echo $detail_url; ?>">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                            </a>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
        <div class="gfam-product-info">
            <div class="gfam-product-badges mb-4">
                <?php if (!empty($listing_type)) : ?>
                    <span class="gfam-badge gfam-badge-new"><?php echo esc_html($listing_type); ?></span>
                <?php endif; ?>

                <?php if (!empty($stock_number)) : ?>
                    <span class="gfam-badge gfam-badge-code"><?php echo esc_html($stock_number); ?></span>
                <?php endif; ?>
            </div>
            
            <a href="<?php echo $detail_url; ?>"><h3 class="gfam-product-title"><?php echo esc_html($product_title); ?></h3></a>            
            <p class="gfam-product-subtitle"><?php echo esc_html($item_specification); ?></p>

            <div class="gfam-product-details">
                <div class="gfam-odometer">
                    <div class="gfam-odometer-icon">
                        <img src="<?php echo esc_url(RSL_PLUGIN_URL . 'assets/images/odomter.svg'); ?>" alt="Odometer">
                    </div>                    
                    <div class="gfam-odometer-info">
                        <span class="gfam-odometer-label">Odometer</span>
                        <span class="gfam-odometer-value"><?php echo (is_string($hours)) ? $hours : number_format($hours, 0, '.', ',').' kms'; ?></span>
                    </div>
                </div>

                <?php if (!empty($price)) : ?>
                    <div class="gfam-price-info">
                        <div class="gfam-price mb-0"><?php echo (is_string($price)) ? $price : "$" . number_format($price, 0, '.', ','); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <a class="gfam-btn" href="<?php echo esc_url($detail_url); ?>">
                <?php esc_html_e('See Details', 'retain-stock-locator'); ?>
            </a>
            
        </div>
    </div>
</div>
