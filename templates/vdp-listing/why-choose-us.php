<div class="stc-why-choose-sec">
    <?php 
    $why_section = get_field('why_choose_us_section', 'option');

    // Get the group field object to access its sub-field defaults
    $group_object = get_field_object('why_choose_us_section', 'option');
    $sub_fields   = $group_object ? $group_object['sub_fields'] : [];

    // Get title
    $title = '';
    if ( !empty($why_section['why_choose_us_section_title']) ) {
        // Use the saved title
        $title = $why_section['why_choose_us_section_title'];
    } else {
        // Find the default value defined for the sub field
        foreach ( $sub_fields as $sf ) {
            if ( $sf['name'] === 'why_choose_us_section_title' && !empty($sf['default_value']) ) {
                $title = $sf['default_value'];
                break;
            }
        }
    }

    $features = !empty($why_section['why_choose_us_data']) ? $why_section['why_choose_us_data'] : [];
    ?>

    <div class="container gfam-why-choose-section my-5 p-lg-5 p-3 bg-white">

        <?php if ( $title ): ?>
            <h2 class="mb-3 text-lg-start text-center">
                <?php echo $title; // supports HTML ?>
            </h2>
        <?php endif; ?>

        <?php if ( $features ): ?>
            <div class="row justify-content-center gfam-features">
                <?php foreach ( $features as $feature ): 
                    $icon  = $feature['icon'];
                    $ftitle = $feature['title'];
                    if ( $icon ):
                        $icon_url = is_array($icon) ? $icon['url'] : $icon;
                ?>
                    <div class="col-md-4 my-2 gfam-feature-item d-flex align-items-center gap-4">
                        <img src="<?php echo esc_url($icon_url); ?>" class="mx-auto d-block" alt="icon" style="max-width:70px;" />
                        <?php if ( $ftitle ): ?>
                            <p class="mb-0 text-start"><?php echo $ftitle; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

