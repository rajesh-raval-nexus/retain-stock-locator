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
$product_images      = $item['images'];
$listing_type        = $item['listing_type'];
$stock_number        = $item['stock_number'];
$item_specification  = $item['item_specification'];
$hours               = $item['hours'];
$price               = $item['price'];
?>

<div class="col-lg-4 col-md-6 my-3">
    <div class="gfam-product-card">
        <div class="gfam-product-image">
            <div class="owl-carousel gfam-carousel">
                <?php if (!empty($product_images)) :
                    foreach ($product_images as $image) : ?>
                        <div class="item">
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
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

            <h3 class="gfam-product-title"><?php echo esc_html($product_title); ?></h3>
            <p class="gfam-product-subtitle"><?php echo esc_html($item_specification); ?></p>

            <div class="gfam-product-details">
                <div class="gfam-odometer">
                    <div class="gfam-odometer-icon">
                        <img src="<?php echo esc_url(RSL_PLUGIN_URL . 'assets/images/odomter.svg'); ?>" alt="Odometer">
                    </div>
                    <?php if (!empty($hours)) : ?>
                        <div class="gfam-odometer-info">
                            <span class="gfam-odometer-label">Odometer</span>
                            <span class="gfam-odometer-value"><?php echo number_format($hours, 0, '.', ','); ?> kms</span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($price)) : ?>
                    <div class="gfam-price-info">
                        <div class="gfam-price mb-0"><?php echo "$" . number_format($price, 0, '.', ','); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <a class="gfam-btn" href="<?php echo esc_url(site_url('/listing-detail/?stock_number=' . $stock_number)); ?>">
                See Details
            </a>
        </div>
    </div>
</div>
