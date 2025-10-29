<div class="stc-why-choose-sec">
    <?php 
    $brand_section = get_field('machinery_brand_section', 'option');

    // Get the group field object to access sub-field defaults
    $group_object = get_field_object('machinery_brand_section', 'option');
    $sub_fields   = $group_object ? $group_object['sub_fields'] : [];

    // Get title (use saved value or fallback to default)
    $title = '';
    if ( !empty($brand_section['machinery_brand_section_title']) ) {
        $title = $brand_section['machinery_brand_section_title'];
    } else {
        foreach ( $sub_fields as $sf ) {
            if ( $sf['name'] === 'machinery_brand_section_title' && !empty($sf['default_value']) ) {
                $title = $sf['default_value'];
                break;
            }
        }
    }

    $brands = !empty($brand_section['machinery_brands']) ? $brand_section['machinery_brands'] : [];
    ?>

    <div class="container gfam-why-choose-section my-5 p-lg-5 p-3 bg-white">
        
        <?php if ( $title ): ?>
            <h2 class="mb-3 text-lg-start text-center d-flex flex-column d-lg-block">
                <?php echo $title; // allows HTML from backend ?>
            </h2>
        <?php endif; ?>

        <?php if ( $brands ): ?>
            <div class="row justify-content-center gfam-features">
                <?php foreach ( $brands as $brand ): 
                    $logo = $brand['logo'];
                    $url  = $brand['url'];
                    if ( $logo ):
                        $logo_url = is_array($logo) ? $logo['url'] : $logo; // handles both return types
                ?>
                    <div class="col-lg-3 col-md-3 col-6 my-2">
                        <?php if ( $url ): ?>
                            <a href="<?php echo esc_url($url); ?>" target="_blank">
                                <img src="<?php echo esc_url($logo_url); ?>" class="w-100 mx-auto d-block" alt="brand logo" />
                            </a>
                        <?php else: ?>
                            <img src="<?php echo esc_url($logo_url); ?>" class="w-100 mx-auto d-block" alt="brand logo" />
                        <?php endif; ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>