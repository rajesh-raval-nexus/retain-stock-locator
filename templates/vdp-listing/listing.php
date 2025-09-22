<!-- Product Grid -->
<div class="gfam-product-grid row">
    <?php 
        global $xmlPath;
        $allListings = rsl_load_more(rsl_parse_listings( $xmlPath ), 0, 9);
        
        foreach ($allListings as $item_key => $item) {
            $product_title = rsl_build_product_name($item); 
            $product_images = $item['images'];
            $listing_type = $item['listing_type'];
            $stock_number = $item['stock_number'];

            ?>
            <!-- Product Card 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="gfam-product-card">
                    <div class="gfam-product-image">
                        <div class="owl-carousel gfam-carousel">                                                        
                            <?php 
                            if(!empty($product_images)){
                                foreach ( $product_images as  $image) {?>
                                    <div class="item">
                                        <img src="<?php echo $image; ?>" alt="Farm Trailer" />
                                    </div>
                                <?php }
                            }                            
                            ?>
                        </div>
                    </div>
                    <div class="gfam-product-info">
                        <div class="gfam-product-badges mb-4">
                            <?php 
                                if(!empty($listing_type)){?>
                                    <span class="gfam-badge gfam-badge-new"><?php echo $listing_type; ?></span>
                                <?php }
                            ?>

                            <?php 
                                if(!empty($stock_number)){?>
                                    <span class="gfam-badge gfam-badge-code"><?php echo $stock_number; ?></span>
                                <?php }
                            ?>
                        </div>
                        <h3 class="gfam-product-title"><?php echo $product_title; ?></h3>
                        <p class="gfam-product-subtitle">
                            Trailing Dry Fertiliser Spreaders
                        </p>
                        <div class="gfam-product-details">
                            <div class="gfam-odometer">
                                <div class="gfam-odometer-icon">
                                    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M52 30.6799C52 36.5732 50.2667 41.831 46.8 46.4532C46.4533 46.9154 45.9911 47.1754 45.4133 47.2332C44.8356 47.291 44.3156 47.1466 43.8533 46.7999C43.3911 46.4532 43.1311 46.0199 43.0733 45.4999C43.0156 44.9799 43.16 44.4888 43.5067 44.0266C46.5111 40.0977 48.0133 35.6488 48.0133 30.6799C48.0133 26.751 47.0311 23.111 45.0667 19.7599C43.1022 16.4088 40.4156 13.751 37.0067 11.7866C33.5978 9.82211 29.9289 8.83989 26 8.83989C22.0711 8.83989 18.4022 9.82211 14.9933 11.7866C11.5844 13.751 8.89778 16.4088 6.93333 19.7599C4.96889 23.111 3.98667 26.751 3.98667 30.6799C3.98667 35.6488 5.48889 40.0977 8.49333 44.0266C8.84 44.4888 8.98444 44.9799 8.92667 45.4999C8.86889 46.0199 8.60889 46.4532 8.14667 46.7999C7.68444 47.1466 7.16444 47.291 6.58667 47.2332C6.00889 47.1754 5.54667 46.9154 5.2 46.4532C1.73333 41.831 0 36.5732 0 30.6799C0 26.0577 1.15556 21.7532 3.46667 17.7666C5.77778 13.7799 8.92667 10.631 12.9133 8.31989C16.9 6.00878 21.2622 4.85323 26 4.85323C30.7378 4.85323 35.1 6.00878 39.0867 8.31989C43.0733 10.631 46.2222 13.7799 48.5333 17.7666C50.8444 21.7532 52 26.0577 52 30.6799ZM38.8267 18.5466C39.2889 18.8932 39.52 19.3554 39.52 19.9332C39.52 20.511 39.2889 20.9732 38.8267 21.3199L32.24 28.0799C32.8178 29.1199 33.1067 30.2177 33.1067 31.3732C33.1067 33.3377 32.4133 35.0132 31.0267 36.3999C29.64 37.7866 27.9644 38.4799 26 38.4799C24.0356 38.4799 22.36 37.7866 20.9733 36.3999C19.5867 35.0132 18.8933 33.3377 18.8933 31.3732C18.8933 29.4088 19.5867 27.7332 20.9733 26.3466C22.36 24.9599 24.0356 24.2666 26 24.2666C27.1556 24.2666 28.2533 24.5554 29.2933 25.1332L36.0533 18.5466C36.4 18.0843 36.8622 17.8532 37.44 17.8532C38.0178 17.8532 38.48 18.0843 38.8267 18.5466ZM29.12 31.3732C29.12 30.5643 28.8022 29.871 28.1667 29.2932C27.5311 28.7154 26.8089 28.4266 26 28.4266C25.1911 28.4266 24.4689 28.7154 23.8333 29.2932C23.1978 29.871 22.88 30.5643 22.88 31.3732C22.88 32.1821 23.1978 32.9043 23.8333 33.5399C24.4689 34.1754 25.1911 34.4932 26 34.4932C26.8089 34.4932 27.5311 34.1754 28.1667 33.5399C28.8022 32.9043 29.12 32.1821 29.12 31.3732Z"
                                            fill="#272727"
                                        />
                                    </svg>
                                </div>
                                <div class="gfam-odometer-info">
                                    <span class="gfam-odometer-label">Odometer</span>
                                    <span class="gfam-odometer-value">89,352 kms</span>
                                </div>
                            </div>
                            <div class="gfam-price-info">
                                <div class="gfam-price mb-0">$45,000</div>
                            </div>
                        </div>
                        <button class="gfam-btn">See Details</button>
                    </div>
                </div>
            </div>       
        <?php }
    ?>       
</div>