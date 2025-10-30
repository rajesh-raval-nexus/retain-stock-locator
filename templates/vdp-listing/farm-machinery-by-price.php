<div class="stc-why-choose-sec">
    <?php 
    $machinery_section = get_field('machinery_price_section', 'option');

    // Get the group field object to access sub-field defaults
    $group_object = get_field_object('machinery_price_section', 'option');
    $sub_fields   = $group_object ? $group_object['sub_fields'] : [];

    // Get title (use saved value or fallback to default)
    $title = '';
    if ( !empty($machinery_section['machinery_price_section_title']) ) {
        $title = $machinery_section['machinery_price_section_title'];
    } else {
        foreach ( $sub_fields as $sf ) {
            if ( $sf['name'] === 'machinery_price_section_title' && !empty($sf['default_value']) ) {
                $title = $sf['default_value'];
                break;
            }
        }
    }

    $prices = !empty($machinery_section['machinery_prices']) ? $machinery_section['machinery_prices'] : [];
    ?>

    <div class="container gfam-why-choose-section my-5 p-lg-5 p-3 bg-white">
        <?php if ( $title ) : ?>
            <h2 class="mb-3 text-lg-start text-center d-lg-block d-flex flex-column">
                <?php echo wp_kses_post( $title ); // supports HTML ?>
            </h2>
        <?php endif; ?>

        <?php if ( $prices ) : ?>
            <div class="row justify-content-center">
                <?php foreach ( $prices as $item ) : 
                    $type  = $item['above_under_select']; // 'above' or 'under'
                    $price = $item['price']; // numeric value only
                    $formatted_price = number_format( (float) $price );

                    // Generate label text
                    if ( $type === 'above' ) {
                        $label = 'Above $' . $formatted_price;
                    } else {
                        $label = 'Under $' . $formatted_price;
                    }
                    $is_active_cls = '';

                    if(isset($filters['filter_type'], $filters['filter_price']) && ($type == $filters['filter_type'] && $price == $filters['filter_price']) ){
                        $is_active_cls = ' active';
                    }

                ?>
                    <div class="col-md-4 col-10 my-2">
                        <button type="button" class="gfam-price-btn w-100 text-center block-price-filter<?php echo $is_active_cls;?>" data-filter-type='<?php echo $type; ?>' data-filter-price=<?php echo $price; ?>>
                            <?php echo esc_html( $label ); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>