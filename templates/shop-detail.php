<?php
global $xmlPath;

$stock_number = get_query_var('stock_number');

$stock_number_parts = explode('-', $stock_number);
$stock_number = strtoupper(end($stock_number_parts));

if ($stock_number) {

  $allListingsData = rsl_parse_listings($xmlPath);
  
  $video_walkthrough_icon = get_field('video_walkthrough_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/vehicle-seach.svg';
  $test_drive_icon = get_field('test_drive_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/vehicle-seach.svg';

  $detail_data_sections = get_field('detail_data_sections', 'option');
  $easy_steps_to_own_your_vehicle = get_field('easy_steps_to_own_your_vehicle', 'option');

  $detail_page = get_field('select_stock_locator_detail_page', 'option');
  
  $call_number = get_field('call_number', 'option');
  foreach ($allListingsData as $listing) {
  $listing_stock = str_replace(['-', ' ', '_'], '', $listing['stock_number']);

  if ($listing_stock === $stock_number) { ?>  
      <!-- Header -->
      <div class="gfam-detail-header">
        <div class="container">
          <div class="row">
            <div class="col-xl-7 order-xl-1 order-2">
              <nav class="d-flex align-items-center gap-2 gfam-detail-breadcrumb">
                <a href="<?php echo home_url(); ?>" class="d-flex align-items-center">
                  <svg class="mb-1" width="19" height="22" viewBox="0 0 19 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M17.4865 22H13.5926C12.7593 22 12.0815 21.164 12.0815 20.1359V12.9631C12.0815 12.7504 11.9403 12.5761 11.7678 12.5761H7.22977C7.05735 12.5761 6.91606 12.7504 6.91606 12.9631V20.1359C6.91606 21.164 6.23834 22 5.40497 22H1.51109C0.677719 22 0 21.164 0 20.1359V10.4402C0 9.46529 0.344847 8.54357 0.948325 7.91433L7.76141 0.788774C8.7672 -0.262925 10.228 -0.262925 11.2338 0.788774L18.0517 7.91728C18.6552 8.54653 19 9.46824 19 10.4431V20.1359C19 21.164 18.3223 22 17.4889 22H17.4865ZM7.22738 11.099H11.7654C12.5988 11.099 13.2765 11.935 13.2765 12.9631V20.1359C13.2765 20.3486 13.4178 20.5229 13.5902 20.5229H17.4841C17.6565 20.5229 17.7978 20.3486 17.7978 20.1359V10.4431C17.7978 9.90251 17.6063 9.39143 17.271 9.04284L10.4531 1.91433C9.89514 1.33235 9.08571 1.33235 8.52773 1.91433L1.71465 9.03988C1.37938 9.38848 1.19019 9.89956 1.19019 10.4402V20.1359C1.19019 20.3486 1.33148 20.5229 1.50391 20.5229H5.39778C5.57021 20.5229 5.7115 20.3486 5.7115 20.1359V12.9631C5.7115 11.935 6.38921 11.099 7.22259 11.099H7.22738Z"
                      fill="#fff" />
                  </svg>
                </a>
                <?php
                  $stock_locator_page = get_field('select_stock_locator_page', 'option');
                  $listingType = $listing['type'];
                  if ($stock_locator_page) {
                    global $wpdb;
                    $page_id    = intval($stock_locator_page);
                    $page_url   = get_permalink($page_id);
                     
                    $page_title = get_post_field('post_title', $page_id);
                      echo '<span> > </span>';
                      echo '<a href="' . esc_url($page_url) . '">' . esc_html($page_title) . '</a>';
                    if($listingType){
                      echo '<span> > </span>';
                      $listingType_slug = gfam_generate_slug_preserve_case($listingType);
                      echo '<a href="' . esc_url(trailingslashit($page_url) . $listingType_slug) . '">' . esc_html($listingType) . '</a>';
                    }  
                  }
                  ?>
                
                <span> > </span>
                <span class="active">
                    <?php 
                    $ttl = trim($listing['year'] . ' ' . $listing['make'] . ' ' . $listing['model']);
                    if (empty($ttl)) {
                        $ttl = $detail_page->post_title;
                    }
                    echo esc_html($ttl); ?>
                  </span>
              </nav>

            </div>
            <div class="col-xl-5 order-xl-2 order-1">
              <div class="gfam-detail-nav">
                <a href="#openGallery" class="active"><?php esc_html_e('Gallery', 'retain-stock-locator'); ?></a>
                <a href="#vehicleFeatures"><?php esc_html_e('Vehicle Features', 'retain-stock-locator'); ?></a>
                <?php if($listing['description'] != ''){?>
                  <a href="#dealerComments"><?php esc_html_e('Dealer Comments', 'retain-stock-locator'); ?></a>
                 <?php } ?> 
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Title -->
      <div class="gfam-detail-main-title">
        <div class="container">
          <h1 class="gfam-detail-title">
            <?php 
            $ttl = trim($listing['year'] . ' ' . $listing['make'] . ' ' . $listing['model']);
              if (empty($ttl)) {
                    $ttl = $detail_page->post_title;
                }
            echo esc_html($ttl); ?>
          </h1>
          <div class="gfam-detail-price-section d-xl-none d-flex">
            <?php if (! empty($listing['price'])) { ?>  
              <div class="gfam-detail-price-label mb-0"><?php esc_html_e('Price', 'retain-stock-locator'); ?></div>
              <div class="gfam-detail-price"><?php echo '$' . esc_html( $listing['price'] ); ?></div>
            <?php } ?>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="gfam-detail-content">
        <div class="container">
          <div class="row">
            <!-- Left Side - Image Gallery -->
            <div class="col-xl-8">
              <div class="gfam-detail-slider-container">
                <div class="gfam-detail-image-counter d-flex align-items-center gap-2">
                  <svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M18.4077 18.9483H1.92003C1.03493 18.9483 0.314453 18.2284 0.314453 17.344V5.47189C0.314453 4.53895 1.0754 3.77856 2.00907 3.77856H18.316C19.2496 3.77856 20.0106 4.53895 20.0106 5.47189V17.344C20.0106 18.2284 19.2901 18.9483 18.405 18.9483H18.4077ZM2.00907 5.12674C1.81748 5.12674 1.66369 5.28044 1.66369 5.47189V17.344C1.66369 17.4842 1.77971 17.6001 1.92003 17.6001H18.4077C18.548 17.6001 18.6641 17.4869 18.6641 17.344V5.47189C18.6641 5.28044 18.5103 5.12674 18.3187 5.12674H2.01177H2.00907Z"
                      fill="white" />
                    <path
                      d="M10.164 15.4293C7.92161 15.4293 6.09473 13.6066 6.09473 11.3632C6.09473 9.11985 7.91892 7.29712 10.164 7.29712C12.4092 7.29712 14.2333 9.11985 14.2333 11.3632C14.2333 13.6066 12.4092 15.4293 10.164 15.4293ZM10.164 8.6453C8.66369 8.6453 7.44396 9.86404 7.44396 11.3632C7.44396 12.8624 8.66369 14.0811 10.164 14.0811C11.6644 14.0811 12.8841 12.8624 12.8841 11.3632C12.8841 9.86404 11.6644 8.6453 10.164 8.6453Z"
                      fill="white" />
                    <path
                      d="M14.5895 5.12674H5.73578C5.51181 5.12674 5.30131 5.0162 5.17718 4.83015C5.05305 4.6441 5.02606 4.4095 5.10972 4.20188L6.0245 1.93696C6.378 1.06065 7.21724 0.494385 8.16441 0.494385H12.1608C13.1053 0.494385 13.9472 1.06065 14.3007 1.93696L15.2155 4.20188C15.2992 4.4095 15.2749 4.6468 15.1481 4.83015C15.0239 5.0162 14.8134 5.12674 14.5895 5.12674ZM6.7342 3.77856H13.5883L13.0459 2.43848C12.9002 2.07447 12.5494 1.8399 12.1581 1.8399H8.16171C7.76773 1.8399 7.41961 2.07447 7.2739 2.43848L6.7315 3.77856H6.7342Z"
                      fill="white" />
                  </svg>
                  <!-- <span>35</span> -->
                  <span><?php if ( isset($listing['images']) && is_array($listing['images']) ) {
                      $image_count = count($listing['images']);
                      echo $image_count;
                  } ?></span>
                </div>

                <?php 
                  $images = [];
                  if ( isset( $listing['images'] ) && is_array( $listing['images'] ) ) {
                      foreach ( $listing['images'] as $img ) {
                          $images[] = (string) $img; // just cast the string
                      }
                  }

                  if ( ! empty( $images ) ) : ?>

                      <!-- Main Slider -->
                      <div class="gfam-main-slider position-relative">
                          <div class="gfam-detail-main-slider owl-carousel">
                              <?php foreach ( $images as $index => $img_url ) : ?>
                                  <div class="item">
                                      <a href="#" class="detailGalleryOpen"><img src="<?php echo esc_url( $img_url ); ?>" 
                                          alt="Valtra Tractor <?php echo esc_attr( $index + 1 ); ?>" 
                                          class="img-fluid">
                                      </a>  
                                  </div>
                              <?php endforeach; ?>
                          </div>
                          <button class="gfam-detail-gallery-btn" id="openGallery">
                            <svg width="25" height="23" viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <rect x="0.588867" y="0.908203" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="9.62305" y="0.908203" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="18.6587" y="0.908203" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="0.588867" y="16.0059" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="9.62305" y="16.0059" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="18.6587" y="16.0059" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="0.588867" y="8.45801" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="9.62305" y="8.45801" width="6.25474" height="6.25474" fill="black"/>
                              <rect x="18.6587" y="8.45801" width="6.25474" height="6.25474" fill="black"/>
                              </svg>
                              <?php esc_html_e( 'view gallery', 'retain-stock-locator' ); ?>
                          </button>
                      </div>

                      <!-- Thumbnail Slider -->
                      <div class="gfam-detail-thumb-slider owl-carousel">
                          <?php foreach ( $images as $index => $img_url ) : ?>
                              <div class="item">
                                  <img src="<?php echo esc_url( $img_url ); ?>" 
                                      alt="Thumb <?php echo esc_attr( $index + 1 ); ?>" 
                                      class="img-fluid" 
                                      onclick="changeMainImage(<?php echo esc_attr( $index ); ?>)">
                              </div>
                          <?php endforeach; ?>
                      </div>

                      <!-- Hidden Gallery Images -->
                      <div id="gallery" style="display: none;">
                          <?php foreach ( $images as $index => $img_url ) : ?>
                              <a href="<?php echo esc_url( $img_url ); ?>" data-lg-size="1400-1400">
                                  <img src="<?php echo esc_url( $img_url ); ?>" 
                                      alt="Image <?php echo esc_attr( $index + 1 ); ?>">
                              </a>
                          <?php endforeach; ?>
                      </div>

                  <?php endif; ?>

                <div id="shared-caption" class="custom-caption" style="display: none;">
                  <div class="text-center mt-4">
                    <button onclick="window.location.href='tel:+<?php echo $call_number; ?>'" class="btn btn-warning me-2"><?php esc_html_e('Call', 'retain-stock-locator'); ?></button>
                    <button class="btn btn-warning btn-message-detail" data-bs-toggle="modal" data-bs-target="#contactUsfmModal"><?php esc_html_e('Message', 'retain-stock-locator'); ?></button>
                  </div>
                </div>
              </div>

              <!-- Vehicle Features Section -->
              <div id="vehicleFeatures" class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-vehicle-features">
                    <h2 class="gfam-detail-section-title">
                      <?php echo $detail_data_sections['vehicle_features_title'] ?? ''; ?>
                    </h2>

                    <div class="gfam-feature-listing">
                      <div class="gfam-feature-listing-box">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            
                          <?php 
                            $availability_icon = get_field('availibility_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/availibility-icon.svg';
                          ?>
                          <img src="<?php echo $availability_icon;?>" alt="Availability">

                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php esc_html_e('Availability', 'retain-stock-locator'); ?></h5>
                            <p><?php echo esc_html( $listing['status'] ); ?></p>
                          </div>
                        </div>
                      </div>

                      <div class="gfam-feature-listing-box">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <?php 
                              $year_icon = get_field('year_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/year-icon.svg';
                            ?>
                            <img src="<?php echo $year_icon;?>" alt="Year">
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php echo __( 'Year', 'retain-stock-locator' ); ?></h5>
                            <p><?php if($listing['year']){
                              echo esc_html( $listing['year'] );
                            }else{ echo " N/A "; }  ?></p>
                          </div>
                        </div>
                      </div>
                       <?php //endif; ?>

                      <div class="gfam-feature-listing-box">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <?php 
                              $odometer_icon = get_field('odometer_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/odomter-icon.svg';
                            ?>
                            <img src="<?php echo $odometer_icon;?>" alt="Odometer">
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php esc_html_e('Odometer', 'retain-stock-locator'); ?></h5>
                            <?php if (! empty($listing['hours'])) { ?>
                              <p><?php echo number_format($listing['hours'], 0, '.', ','); ?> Kms</p>
                            <?php }else{
                              echo "<p>N/A</p>";
                            } ?>
                          </div>
                        </div>
                      </div>

                      <div class="gfam-feature-listing-box">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <?php 
                              $body_type_icon = get_field('body_type_icon', 'option') ?: RSL_PLUGIN_URL . 'assets/images/body-type-icon.svg';
                            ?>
                            <img src="<?php echo $body_type_icon;?>" alt="Body Type">
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php esc_html_e('Body Type', 'retain-stock-locator'); ?></h5>
                            <p><?php echo esc_html( $listing['type'] ); ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Dealer Comments Section -->
              <?php if($listing['description'] != ''){?>
                  <div id="dealerComments" class="row mt-lg-5 mt-4">
                    <div class="col-12">
                        <div class="gfam-detail-dealer-comments">
                          <h2 class="gfam-detail-section-title">
                            <?php echo $detail_data_sections['dealer_comments_title'] ?? ''; ?>
                          </h2>

                          <div class="gfam-detail-comments-content">
                            <p class="add-read-more show-less-content"><?php echo wp_kses_post($listing['description']); ?></p>
                          </div>
                        </div>
                        
                      </div>
                </div>
              <?php } ?>

              <?php include RSL_PLUGIN_DIR . 'templates/parts/sidebar-mobile.php'; ?>

              <!-- Vehicle Details Section -->
              <div id="vehicleDetails" class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-vehicle-details">
                    <h2 class="gfam-detail-section-title">
                      <?php echo $detail_data_sections['vehicle_details_title'] ?? ''; ?>
                    </h2>
                    <div class="gfam-detail-details-table">
                            
                      <?php if (! empty($listing['make'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Make', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value gfam-detail-make-val"><?php echo esc_html($listing['make']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['model'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Model', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value gfam-detail-model-val"><?php echo esc_html($listing['model']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['year'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Year', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['year']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['listing_type'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Condition', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['listing_type']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['price'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Price', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo '$' . esc_html($listing['price']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['hours'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Odometer/Hours', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['hours']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['status'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Stock', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['status']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['type'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Body', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['type']); ?></div>
                        </div>
                      <?php endif; ?>

                       <?php if (! empty($listing['status'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Availability', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['status']); ?></div>
                        </div>
                      <?php endif; ?>

                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Location', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php esc_html_e( 'Australia', 'retain-stock-locator' ); ?></div>
                        </div>
                      
                    </div>

                  </div>
                </div>
              </div>

              <!-- Ask question section -->
               <?php
                $ask_question_section = get_field('ask_question_section','option');

                if ($ask_question_section && !empty($ask_question_section['ask_question_lists'])): ?>
                  <div class="row mt-lg-5 mt-4">
                    <div class="col-12">
                      <div class="gfam-detail-question-box">
                        <div class="row w-100 mx-auto">
                          <div class="col-lg-9 my-auto px-0">
                            <div class="custom-select-wrapper">
                              <div class="custom-select">
                                  <span class="selected d-block"><?php esc_html_e( 'Select a question', 'retain-stock-locator' ); ?></span>
                                  <div class="custom-arrow">
                                    <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M15.4435 1.39511C15.4435 1.53322 15.3901 1.67135 15.281 1.77709L9.2607 7.61035C8.41434 8.43042 7.04012 8.43042 6.19376 7.61035L0.173469 1.77709C-0.0448025 1.5656 -0.0448025 1.22461 0.173469 1.01312C0.39174 0.80163 0.743656 0.80163 0.961928 1.01312L6.98219 6.84648C7.18042 7.03855 7.44548 7.14638 7.72834 7.14638C8.01121 7.14638 8.27625 7.04071 8.47447 6.84648L14.4948 1.01312C14.713 0.801629 15.0649 0.801629 15.2832 1.01312C15.3923 1.11887 15.4458 1.25699 15.4458 1.39511L15.4435 1.39511Z" fill="#847878" />
                                    </svg>
                                  </div>
                                  <ul class="options">
                                        <?php foreach ($ask_question_section['ask_question_lists'] as $index => $item): 
                                            $question = $item['question'];
                                            $data_value = 'q' . ($index + 1);
                                            ?>
                                            <li data-value="<?php echo esc_attr($data_value); ?>">
                                                <?php echo esc_html($question); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-3 text-end px-0">
                                <button class="gfam-detail-button my-2 ask-question-btn" data-bs-toggle="modal"
                                data-bs-target="#askQuestionModal"><?php esc_html_e( 'Ask a Question', 'retain-stock-locator' ); ?></button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  <?php endif; ?>

               <?php
                $allListings = rsl_parse_listings($xmlPath);
                $similardata = trim($listing['make']);
                $stock_number_like = trim($listing['stock_number']);

                $matchingListings = [];
                $counter = 0;

                foreach ($allListings as $item) {

                    $stock_number_loop = trim($item['stock_number']);

                    // Skip if stock number matches
                    if ($stock_number_like === $stock_number_loop) {
                        continue;
                    }

                    // Check by make
                    if (isset($item['make']) && strcasecmp(trim($item['make']), $similardata) === 0) {
                        $matchingListings[] = $item;
                        $counter++;
                    }

                    // Stop after 3 results
                    if ($counter >= 3) {
                        break;
                    }
                }
                ?>
              <!-- similar products Section -->
              <div class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-similiar-product">
                    <?php if (!empty($matchingListings)) : ?>
                        <h2 class="gfam-detail-section-title text-center"><?php esc_html_e('Similar Listings', 'retain-stock-locator'); ?> <span><?php esc_html_e('You Might Like', 'retain-stock-locator'); ?></span></h2>
                    <?php endif; ?>
                    <!-- Product Grid -->
                    <div class="gfam-product-grid row owl-carousel owl-theme">
                      <?php foreach ($matchingListings as $listingItem) : 
                          $price = $listingItem['price'];
                          $hours = $listingItem['hours'];
                        ?>
                        <?php
                            if ($detail_page) {
                                
                                $detail_page_slug = $detail_page->post_name;

                                $stock_numberlike = strtolower($listingItem['stock_number']);

                                // 
                                $slug_title_like = strtolower(trim($listingItem['year'] . '-' . $listingItem['make'] . '-' . $listingItem['model']));
                                $slug_title_like = sanitize_title($slug_title_like);
                                
                                if (!empty($slug_title_like)) {
                                    $final_slug = "{$slug_title_like}-{$stock_numberlike}";
                                } else {
                                    $final_slug = "{$stock_numberlike}";
                                }

                                $detail_url = site_url("/{$detail_page_slug}/{$final_slug}/");
                                // 

                                $product_title = trim($listingItem['year'] . ' ' . $listingItem['make'] . ' ' . $listingItem['model']);
                                // if (empty($product_title)) {
                                //     $product_title = $detail_page->post_title;
                                // }
                                
                            }
                          ?>
                         <div class="item col-lg-4 col-md-6 my-3">
							              <div class="gfam-product-card">
                              <div class="gfam-product-image">
                                  <div class="owl-carousel gfam-carousel">
                                      <?php foreach ($listingItem['images'] as $image) : ?>
                                      <div class="item">
                                        <a href="<?php echo $detail_url; ?>">
                                          <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($listingItem['make'] . ' ' . $listingItem['model']); ?>" />
                                        </a>
                                      </div>
                                      <?php endforeach; ?>
                                    </div>
                                </div>
                              <div class="gfam-product-info">
                                <div class="gfam-product-badges mb-4">
                                  <span class="gfam-badge gfam-badge-new"><?php echo esc_html($listingItem['listing_type']); ?></span>
                                  <span class="gfam-badge gfam-badge-code"><?php echo esc_html($listingItem['stock_number']); ?></span>
                                </div>
                                <a href="<?php echo $detail_url; ?>"><h3 class="gfam-product-title"><?php echo esc_html($product_title); ?></h3></a>
                                
                                <p class="gfam-product-subtitle"><?php echo esc_html($listingItem['type']); ?></p>

                                <div class="gfam-product-details">
                                  <div class="gfam-odometer">
                                      <div class="gfam-odometer-icon">
                                          <img src="<?php echo esc_url(RSL_PLUGIN_URL . 'assets/images/odomter.svg'); ?>" alt="Odometer">
                                      </div>
                                      <div class="gfam-odometer-info">
                                        <span class="gfam-odometer-label">Odometer</span>
                                        <?php if (!empty($hours)) { ?>
                                          <span class="gfam-odometer-value"><?php echo number_format($hours, 0, '.', ','); ?> kms</span>
                                        <?php }else{ ?>
                                            <span class="gfam-odometer-value">N/A</span>
                                        <?php } ?>
                                      </div>
                                  </div>

                                  <div class="gfam-price-info">
                                        <?php if (!empty($price)) { ?>
                                          <div class="gfam-price mb-0"><?php echo "$" . number_format($price, 0, '.', ','); ?></div>
                                          <?php }else{ ?>
                                            <div class="gfam-price mb-0">N/A</div>
                                          <?php } ?>
                                      </div>
                                </div>
                                <a class="gfam-btn" href="<?php echo esc_url($detail_url); ?>">
                                    <?php esc_html_e('See Details', 'retain-stock-locator'); ?>
                                </a>
                              </div>
                          </div>
                        </div>
                      <?php endforeach; ?>

                    </div>

                  </div>
                </div>
              </div>
            </div>

            <!-- Right Side - Price and Info -->
            <div class="d-none d-md-block col-xl-4 sticky-section sticky-section-for-desktop">
              <div class="gfam-detail-sidebar">
                <!-- Price Section -->
                <div class="gfam-detail-price-section">
                  <?php if (! empty($listing['price'])) { ?>
                    <div class="gfam-detail-price-label mb-0 d-none d-xl-block"><?php esc_html_e('Price', 'retain-stock-locator'); ?></div>
                    <div class="gfam-detail-price d-none d-xl-block"><?php echo '$' . $listing['price']; ?></div>
                  <?php } ?>

                  <button class="gfam-detail-contact-btn d-block" data-bs-toggle="modal" data-bs-target="#contactUsfmModal"><?php esc_html_e('Contact Us', 'retain-stock-locator'); ?> </button>
                  
                  <div class="accordion gfam-detail-form-accordion d-block" id="gfam-detailAccordion">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="gfam-detailHeading">
                        <button class="accordion-button collapsed gfam-detail-toggle-btn gfam-detail-callback-btn"
                          type="button" data-bs-toggle="collapse" data-bs-target="#gfam-detailCollapse">
                          <?php esc_html_e('Request a Call Back', 'retain-stock-locator'); ?>
                        </button>
                      </h2>
                      <div id="gfam-detailCollapse" class="accordion-collapse collapse"
                        data-bs-parent="#gfam-detailAccordion">
                        <div class="accordion-body gfam-detail-form-box">
                          <form id="gfam-form">
                            <div class="row">
                              <div class="col-6 my-2">
                                <input type="text" class="form-control gfam-detail-input" name="first_name" placeholder="First Name" required>
                              </div>
                              <div class="col-6 my-2">
                                <input type="text" class="form-control gfam-detail-input" name="last_name" placeholder="Last Name" required>
                              </div>
                              <div class="col-12 my-2">
                                <input type="email" class="form-control gfam-detail-input" name="email" placeholder="Email" required>
                              </div>
                              <div class="col-12 my-2">
                                <input type="tel" class="form-control gfam-detail-input" name="phone" placeholder="Phone">
                              </div>
                              <div class="col-12 my-2">
                                <textarea class="form-control gfam-detail-input" name="comments" rows="4" placeholder="Comments"></textarea>
                              </div>
                            </div>

                            <div class="col-12 my-2 form-check d-flex align-items-center gap-2 bg-white p-2 rounded">
                              <input class="form-check-input ms-0" type="checkbox" name="trade_in" value="Yes" id="gfam-detail-trade">
                              <label class="form-check-label" for="gfam-detail-trade"><?php esc_html_e('I have trade in', 'retain-stock-locator'); ?></label>
                            </div>

                            <div class="col-12 mt-3">
                              <button type="submit" class="btn gfam-detail-submit w-100"><?php esc_html_e('Submit', 'retain-stock-locator'); ?></button>
                            </div>
                          </form>

                          <div id="gfam-response" style="margin-top:10px;"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- Easy Steps Section -->
                <div class="gfam-detail-steps-section d-block">
                  <h3 class="gfam-detail-steps-title"><?php echo $easy_steps_to_own_your_vehicle['steps_vehicle_title'] ?? ''; ?></h3>

                  <div class="gfam-detail-step-item">
                    <div class="gfam-detail-step-icon me-4">
                      <img src="<?php echo esc_url( $video_walkthrough_icon ); ?>" alt="Vehicle Icon" style="max-width: unset;">
                    </div>
                    <div class="gfam-detail-step-content">
                      <h4><?php echo $easy_steps_to_own_your_vehicle['video_walkaround_title'] ?? ''; ?></h4>
                      <p><?php echo $easy_steps_to_own_your_vehicle['video_walkaround_sub_title'] ?? ''; ?></p>
                      <a href="#" class="gfam-detail-step-link" data-bs-toggle="modal"
                        data-bs-target="#gfamDetailModal"><?php esc_html_e('Send Message >>', 'retain-stock-locator'); ?></a>
                    </div>
                  </div>

                  <div class="gfam-detail-step-item">
                    <div class="gfam-detail-step-icon me-4">
                      <img src="<?php echo esc_url( $test_drive_icon ); ?>" alt="Vehicle Icon" style="max-width: unset;">
                    </div>
                    <div class="gfam-detail-step-content">
                      <h4><?php echo $easy_steps_to_own_your_vehicle['test_drive_title'] ?? ''; ?></h4>
                      <p><?php echo $easy_steps_to_own_your_vehicle['test_drive_sub_title'] ?? ''; ?></p>
                      <a href="#" class="gfam-detail-step-link" data-bs-toggle="modal"
                        data-bs-target="#gfamtestdriverModal"><?php esc_html_e('Send Message >>', 'retain-stock-locator'); ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--Video walkthrogh Modal END -->
          </div>
        </div>
      </div>

      <!--Video walkthrogh Modal -->
      <div class="modal fade gfam-detail-modal" id="gfamDetailModal" tabindex="-1" aria-labelledby="gfamDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content">
            <div class="gfam-detail-modal-header">
              <h5 class="gfam-detail-modal-title" id="gfamDetailModalLabel"><?php esc_html_e('Request a Video Walkthrough', 'retain-stock-locator'); ?></h5>
              <button type="button" class="gfam-detail-close-btn" data-bs-dismiss="modal" aria-label="Close">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.929001 17.916C0.701378 17.916 0.47377 17.8334 0.299496 17.6648C-0.0490509 17.3276 -0.0490509 16.7839 0.299496 16.4467L16.2758 0.98973C16.6243 0.652512 17.1862 0.652512 17.5348 0.98973C17.8833 1.32695 17.8833 1.87064 17.5348 2.20786L1.55853 17.6648C1.38425 17.8334 1.15662 17.916 0.929001 17.916Z" fill="white"/>
                <path d="M16.9053 17.916C16.6777 17.916 16.45 17.8334 16.2758 17.6648L0.299496 2.20786C-0.0490509 1.87064 -0.0490509 1.32695 0.299496 0.98973C0.648044 0.652512 1.20998 0.652512 1.55853 0.98973L17.5348 16.4467C17.8833 16.7839 17.8833 17.3276 17.5348 17.6648C17.3605 17.8334 17.1329 17.916 16.9053 17.916Z" fill="white"/>
                </svg>
              </button>
            </div>
            <div class="modal-body gfam-detail-modal-body">


              <form id="requestVideoForm">
                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="first_name" placeholder="First Name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="last_name" placeholder="Last Name" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="tel" class="form-control gfam-detail-form-control" name="phone" placeholder="Phone" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="post_code" placeholder="Post Code" required>
                    </div>
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <input type="email" class="form-control gfam-detail-form-control" name="email" placeholder="Email Address" required>
                </div>

                <div class="gfam-detail-form-group">
                  <div class="gfam-detail-dropdown-modal">
                    <button type="button" class="gfam-detail-dropdown-toggle" id="reqVideoDropdown">
                          <span class="gfam-detail-dropdown-text"><?php esc_html_e('Make', 'retain-stock-locator'); ?></span>
                          <svg class=" gfam-detail-dropdown-arrow" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.4435 1.39511C15.4435 1.53322 15.3901 1.67135 15.281 1.77709L9.2607 7.61035C8.41434 8.43042 7.04012 8.43042 6.19376 7.61035L0.173469 1.77709C-0.0448025 1.5656 -0.0448025 1.22461 0.173469 1.01312C0.39174 0.80163 0.743656 0.80163 0.961928 1.01312L6.98219 6.84648C7.18042 7.03855 7.44548 7.14638 7.72834 7.14638C8.01121 7.14638 8.27625 7.04071 8.47447 6.84648L14.4948 1.01312C14.713 0.801629 15.0649 0.801629 15.2832 1.01312C15.3923 1.11887 15.4458 1.25699 15.4458 1.39511L15.4435 1.39511Z" fill="#847878"></path>
                          </svg>
                      
                    </button>
                    <div class="gfam-detail-dropdown-menu" id="reqVideoDropdownMenu">
                      <div class="gfam-detail-dropdown-item" data-value="<?php echo esc_html($listing['make']); ?>"><?php echo esc_html($listing['make']); ?></div>
                    </div>
                    <input type="text" name="make" id="gfamMakeInput" required style="visibility: hidden; position: absolute;">
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn"><?php esc_html_e('Submit', 'retain-stock-locator'); ?></button>
                </div>
              </form>
              <div id="reqVideoFrmResponse" style="margin-top:10px;"></div>

            </div>
          </div>
        </div>
      </div>

      <!-- Test driver Modal -->
      <div class="modal fade gfam-detail-modal" id="gfamtestdriverModal" tabindex="-1"
        aria-labelledby="gfamtestdriverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content">
            <div class="gfam-detail-modal-header">
              <h5 class="gfam-detail-modal-title" id="gfamtestdriverModalLabel">Request a Test Drive Time</h5>
              <button type="button" class="gfam-detail-close-btn" data-bs-dismiss="modal" aria-label="Close">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.929001 17.916C0.701378 17.916 0.47377 17.8334 0.299496 17.6648C-0.0490509 17.3276 -0.0490509 16.7839 0.299496 16.4467L16.2758 0.98973C16.6243 0.652512 17.1862 0.652512 17.5348 0.98973C17.8833 1.32695 17.8833 1.87064 17.5348 2.20786L1.55853 17.6648C1.38425 17.8334 1.15662 17.916 0.929001 17.916Z" fill="white"/>
                <path d="M16.9053 17.916C16.6777 17.916 16.45 17.8334 16.2758 17.6648L0.299496 2.20786C-0.0490509 1.87064 -0.0490509 1.32695 0.299496 0.98973C0.648044 0.652512 1.20998 0.652512 1.55853 0.98973L17.5348 16.4467C17.8833 16.7839 17.8833 17.3276 17.5348 17.6648C17.3605 17.8334 17.1329 17.916 16.9053 17.916Z" fill="white"/>
                </svg>

              </button>
            </div>
            <div class="modal-body gfam-detail-modal-body">

              <form id="requestTestDriveForm">
                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="first_name" placeholder="First Name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="last_name" placeholder="Last Name" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="tel" class="form-control gfam-detail-form-control" name="phone" placeholder="Phone" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="post_code" placeholder="Post Code" required>
                    </div>
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <input type="email" class="form-control gfam-detail-form-control" name="email" placeholder="Email Address" required>
                </div>

                <div class="gfam-detail-form-group">
                  <div class="gfam-detail-dropdown-modal">
                    <button type="button" class="gfam-detail-dropdown-toggle" id="testDriveDropdown">
                      <span class="gfam-detail-dropdown-text">Make</span>
                      <!-- <i class="fas fa-chevron-down gfam-detail-dropdown-arrow"></i> -->
                      <svg class="gfam-detail-dropdown-arrow" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.4435 1.39511C15.4435 1.53322 15.3901 1.67135 15.281 1.77709L9.2607 7.61035C8.41434 8.43042 7.04012 8.43042 6.19376 7.61035L0.173469 1.77709C-0.0448025 1.5656 -0.0448025 1.22461 0.173469 1.01312C0.39174 0.80163 0.743656 0.80163 0.961928 1.01312L6.98219 6.84648C7.18042 7.03855 7.44548 7.14638 7.72834 7.14638C8.01121 7.14638 8.27625 7.04071 8.47447 6.84648L14.4948 1.01312C14.713 0.801629 15.0649 0.801629 15.2832 1.01312C15.3923 1.11887 15.4458 1.25699 15.4458 1.39511L15.4435 1.39511Z" fill="#847878"></path>
                              </svg>
                    </button>
                    <div class="gfam-detail-dropdown-menu" id="testDriveDropdownMenu">
                      <div class="gfam-detail-dropdown-item" data-value="<?php echo esc_html($listing['make']); ?>"><?php echo esc_html($listing['make']); ?></div>
                    </div>
                    <input type="text" name="make" id="testDriveMakeInput" required style="visibility: hidden; position: absolute;">
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <label class="gfam-detail-label"><?php esc_html_e('Preferred Date', 'retain-stock-locator'); ?></label>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" id="gfam-detail-datepicker" name="preferred_date" class="form-control gfam-detail-form-control" placeholder="Select Date" readonly required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" id="gfam-detail-timepicker" name="preferred_time" class="form-control gfam-detail-form-control" placeholder="Select Time" readonly required>
                    </div>
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn"><?php esc_html_e('Submit', 'retain-stock-locator'); ?></button>
                </div>
              </form>

              <div id="gfamDetailResponse" style="margin-top:10px;"></div>

            </div>
          </div>
        </div>
      </div>
      
      <!-- askQuestionModal  -->
      <div class="modal fade gfam-detail-modal" id="askQuestionModal" tabindex="-1" aria-labelledby="askQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content">
            <div class="gfam-detail-modal-header">
              <h5 class="gfam-detail-modal-title" id="askQuestionModalLabel"><?php esc_html_e('How Can We Help You?', 'retain-stock-locator'); ?></h5>
              <button type="button" class="gfam-detail-close-btn" data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="modal-body gfam-detail-modal-body">


              <form id="askQuestionModalForm">
                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="first_name" placeholder="First Name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="last_name" placeholder="Last Name" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="tel" class="form-control gfam-detail-form-control" name="phone" placeholder="Phone" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="post_code" placeholder="Post Code" required>
                    </div>
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <input type="email" class="form-control gfam-detail-form-control" name="email" placeholder="Email Address" required>
                </div>

                <div class="col-12 my-2">
                  <textarea class="form-control gfam-detail-input comments-question" name="comments" rows="4" placeholder="Comments"></textarea>
                </div>

                <input type="hidden" name="ask_question_fm_val" class="ask_question_fm_val" value="">

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn"><?php esc_html_e('Send a Request', 'retain-stock-locator'); ?></button>
                </div>
                

              </form>
              <div id="askQuestionResponse" style="margin-top:10px;"></div>

            </div>
          </div>
        </div>
      </div>

      <!-- contactUsModalForm  -->
      <div class="modal fade gfam-detail-modal" id="contactUsfmModal" tabindex="-1" aria-labelledby="contactUsfmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content">
            <div class="gfam-detail-modal-header">
              <h5 class="gfam-detail-modal-title" id="contactUsfmModalLabel"><?php esc_html_e('Contact Us', 'retain-stock-locator'); ?></h5>
              <button type="button" class="gfam-detail-close-btn" data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="modal-body gfam-detail-modal-body">


              <form id="contactUsModalForm">
                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="first_name" placeholder="First Name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="text" class="form-control gfam-detail-form-control" name="last_name" placeholder="Last Name" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="tel" class="form-control gfam-detail-form-control" name="phone" placeholder="Phone" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="gfam-detail-form-group">
                      <input type="email" class="form-control gfam-detail-form-control" name="email" placeholder="Email Address" required>
                    </div>
                  </div>
                </div>

                <div class="col-12 my-2">
                  <textarea class="form-control gfam-detail-input" name="comments" rows="4" placeholder="Comments"></textarea>
                </div>

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn"><?php esc_html_e('Submit', 'retain-stock-locator'); ?></button>
                </div>
                

              </form>
              <div id="contactUsModalResponse" style="margin-top:10px;"></div>

            </div>
          </div>
        </div>
      </div>

    <?php } ?>

<?php  }
} else {
  esc_html_e( '<p>No stock selected</p>', 'retain-stock-locator' );
}
?>


<script>
  jQuery(document).ready(function($) {
    // Smooth scroll helper
    function smoothScroll(target, offset = 150, speed = 600) {
      if ($(target).length) {
        $('html, body').animate({
          scrollTop: $(target).offset().top - offset
        }, speed);
      }
    }

    // Handle clicks on all anchor links starting with #
    $(document).on('click', 'a[href^="#"]', function(e) {
      var target = $(this).attr('href');

      // Only scroll if target exists and is not just "#"
      if (target && target.length > 1 && $(target).length) {
        e.preventDefault();

        // Scroll to the target
        smoothScroll(target);

        // Update active class if inside a nav
        var $nav = $(this).closest('.gfam-detail-nav');
        if ($nav.length) {
          $nav.find('a').removeClass('active');
          $(this).addClass('active');
        }
      }
    });

    $('.gfam-product-grid.row.main').owlCarousel({
      loop: false,
      margin: 30,
      nav: true,
      dots: false,
      autoplay: true,
      smartSpeed: 800,
      navText: [
        '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.95232 0.790918C7.17969 0.790918 7.40708 0.876348 7.58116 1.05078C7.92933 1.39964 7.92933 1.96206 7.58116 2.31092L2.22005 7.68271C2.00689 7.89629 2.00689 8.24513 2.22005 8.45872L7.58116 13.8305C7.92933 14.1793 7.92933 14.7418 7.58116 15.0906C7.23299 15.4395 6.67165 15.4395 6.32348 15.0906L0.962415 9.71891C0.0564681 8.81116 0.0564682 7.33383 0.962415 6.42608L6.32348 1.05434C6.49756 0.87991 6.72494 0.794438 6.95232 0.794438L6.95232 0.790918Z" fill="black"/><path d="M1.17257 7.17737L12.7012 7.17737C13.1914 7.17737 13.5894 7.57607 13.5894 8.06732C13.5894 8.55858 13.1914 8.95728 12.7012 8.95728L1.17257 8.95727C0.682293 8.95727 0.284387 8.55858 0.284387 8.06732C0.284387 7.57607 0.682293 7.17737 1.17257 7.17737Z" fill="black"/></svg>',
        '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.48567 14.9093C7.25829 14.9093 7.03091 14.8238 6.85683 14.6494C6.50866 14.3006 6.50866 13.7381 6.85683 13.3893L12.2179 8.01749C12.4311 7.8039 12.4311 7.45506 12.2179 7.24147L6.85683 1.86973C6.50866 1.52087 6.50866 0.958408 6.85683 0.609546C7.205 0.260685 7.76634 0.260685 8.11451 0.609546L13.4756 5.98129C14.3815 6.88904 14.3815 8.36636 13.4756 9.27411L8.11451 14.6459C7.94043 14.8203 7.71304 14.9058 7.48567 14.9058V14.9093Z" fill="black"/><path d="M13.2664 8.52331H1.73779C1.24752 8.52331 0.849609 8.12461 0.849609 7.63336C0.849609 7.14211 1.24752 6.74341 1.73779 6.74341H13.2664C13.7567 6.74341 14.1546 7.14211 14.1546 7.63336C14.1546 8.12461 13.7567 8.52331 13.2664 8.52331Z" fill="black"/></svg>',
      ],
      responsive: {
        0: {
          items: 1
        },
        768: {
          items: 2
        },
        992: {
          items: 3
        },
        1200: {
          items: 2
        },
        1600: {
          items: 3
        }
      }
    });

    // Initialize Main Slider
    $('.gfam-detail-main-slider').owlCarousel({
      items: 1,
      loop: true,
      nav: true,
      dots: false,
      autoplay: false,
      autoplayTimeout: 5000,
      margin: 15,
      navText: [
        '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.95232 0.790918C7.17969 0.790918 7.40708 0.876348 7.58116 1.05078C7.92933 1.39964 7.92933 1.96206 7.58116 2.31092L2.22005 7.68271C2.00689 7.89629 2.00689 8.24513 2.22005 8.45872L7.58116 13.8305C7.92933 14.1793 7.92933 14.7418 7.58116 15.0906C7.23299 15.4395 6.67165 15.4395 6.32348 15.0906L0.962415 9.71891C0.0564681 8.81116 0.0564682 7.33383 0.962415 6.42608L6.32348 1.05434C6.49756 0.87991 6.72494 0.794438 6.95232 0.794438L6.95232 0.790918Z" fill="black"/><path d="M1.17257 7.17737L12.7012 7.17737C13.1914 7.17737 13.5894 7.57607 13.5894 8.06732C13.5894 8.55858 13.1914 8.95728 12.7012 8.95728L1.17257 8.95727C0.682293 8.95727 0.284387 8.55858 0.284387 8.06732C0.284387 7.57607 0.682293 7.17737 1.17257 7.17737Z" fill="black"/></svg>',
        '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.48567 14.9093C7.25829 14.9093 7.03091 14.8238 6.85683 14.6494C6.50866 14.3006 6.50866 13.7381 6.85683 13.3893L12.2179 8.01749C12.4311 7.8039 12.4311 7.45506 12.2179 7.24147L6.85683 1.86973C6.50866 1.52087 6.50866 0.958408 6.85683 0.609546C7.205 0.260685 7.76634 0.260685 8.11451 0.609546L13.4756 5.98129C14.3815 6.88904 14.3815 8.36636 13.4756 9.27411L8.11451 14.6459C7.94043 14.8203 7.71304 14.9058 7.48567 14.9058V14.9093Z" fill="black"/><path d="M13.2664 8.52331H1.73779C1.24752 8.52331 0.849609 8.12461 0.849609 7.63336C0.849609 7.14211 1.24752 6.74341 1.73779 6.74341H13.2664C13.7567 6.74341 14.1546 7.14211 14.1546 7.63336C14.1546 8.12461 13.7567 8.52331 13.2664 8.52331Z" fill="black"/></svg>',
      ],
    });

    // Initialize Thumbnail Slider
    $('.gfam-detail-thumb-slider').owlCarousel({
      items: 3,
      loop: false,
      nav: true,
      dots: false,
      margin: 35,
      navText: [
        '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.95232 0.790918C7.17969 0.790918 7.40708 0.876348 7.58116 1.05078C7.92933 1.39964 7.92933 1.96206 7.58116 2.31092L2.22005 7.68271C2.00689 7.89629 2.00689 8.24513 2.22005 8.45872L7.58116 13.8305C7.92933 14.1793 7.92933 14.7418 7.58116 15.0906C7.23299 15.4395 6.67165 15.4395 6.32348 15.0906L0.962415 9.71891C0.0564681 8.81116 0.0564682 7.33383 0.962415 6.42608L6.32348 1.05434C6.49756 0.87991 6.72494 0.794438 6.95232 0.794438L6.95232 0.790918Z" fill="black"/><path d="M1.17257 7.17737L12.7012 7.17737C13.1914 7.17737 13.5894 7.57607 13.5894 8.06732C13.5894 8.55858 13.1914 8.95728 12.7012 8.95728L1.17257 8.95727C0.682293 8.95727 0.284387 8.55858 0.284387 8.06732C0.284387 7.57607 0.682293 7.17737 1.17257 7.17737Z" fill="black"/></svg>',
        '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.48567 14.9093C7.25829 14.9093 7.03091 14.8238 6.85683 14.6494C6.50866 14.3006 6.50866 13.7381 6.85683 13.3893L12.2179 8.01749C12.4311 7.8039 12.4311 7.45506 12.2179 7.24147L6.85683 1.86973C6.50866 1.52087 6.50866 0.958408 6.85683 0.609546C7.205 0.260685 7.76634 0.260685 8.11451 0.609546L13.4756 5.98129C14.3815 6.88904 14.3815 8.36636 13.4756 9.27411L8.11451 14.6459C7.94043 14.8203 7.71304 14.9058 7.48567 14.9058V14.9093Z" fill="black"/><path d="M13.2664 8.52331H1.73779C1.24752 8.52331 0.849609 8.12461 0.849609 7.63336C0.849609 7.14211 1.24752 6.74341 1.73779 6.74341H13.2664C13.7567 6.74341 14.1546 7.14211 14.1546 7.63336C14.1546 8.12461 13.7567 8.52331 13.2664 8.52331Z" fill="black"/></svg>',
      ],
      responsive: {
        0: {
          items: 3,
          margin: 5,
        },
        600: {
          items: 3
        }
      }
    });

    $(".gfam-carousel").owlCarousel({
      loop: true,
      margin: 0,
      nav: true,
      dots: false,
      autoplay: false,
      autoplayTimeout: 4000,
      autoplayHoverPause: true,
      items: 1,
      navText: [
        '<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.95232 0.790918C7.17969 0.790918 7.40708 0.876348 7.58116 1.05078C7.92933 1.39964 7.92933 1.96206 7.58116 2.31092L2.22005 7.68271C2.00689 7.89629 2.00689 8.24513 2.22005 8.45872L7.58116 13.8305C7.92933 14.1793 7.92933 14.7418 7.58116 15.0906C7.23299 15.4395 6.67165 15.4395 6.32348 15.0906L0.962415 9.71891C0.0564681 8.81116 0.0564682 7.33383 0.962415 6.42608L6.32348 1.05434C6.49756 0.87991 6.72494 0.794438 6.95232 0.794438L6.95232 0.790918Z" fill="black"/><path d="M1.17257 7.17737L12.7012 7.17737C13.1914 7.17737 13.5894 7.57607 13.5894 8.06732C13.5894 8.55858 13.1914 8.95728 12.7012 8.95728L1.17257 8.95727C0.682293 8.95727 0.284387 8.55858 0.284387 8.06732C0.284387 7.57607 0.682293 7.17737 1.17257 7.17737Z" fill="black"/></svg>',
        '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.48567 14.9093C7.25829 14.9093 7.03091 14.8238 6.85683 14.6494C6.50866 14.3006 6.50866 13.7381 6.85683 13.3893L12.2179 8.01749C12.4311 7.8039 12.4311 7.45506 12.2179 7.24147L6.85683 1.86973C6.50866 1.52087 6.50866 0.958408 6.85683 0.609546C7.205 0.260685 7.76634 0.260685 8.11451 0.609546L13.4756 5.98129C14.3815 6.88904 14.3815 8.36636 13.4756 9.27411L8.11451 14.6459C7.94043 14.8203 7.71304 14.9058 7.48567 14.9058V14.9093Z" fill="black"/><path d="M13.2664 8.52331H1.73779C1.24752 8.52331 0.849609 8.12461 0.849609 7.63336C0.849609 7.14211 1.24752 6.74341 1.73779 6.74341H13.2664C13.7567 6.74341 14.1546 7.14211 14.1546 7.63336C14.1546 8.12461 13.7567 8.52331 13.2664 8.52331Z" fill="black"/></svg>',
      ],
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 1,
        },
        1000: {
          items: 1,
        },
      },
    });

    // Add custom animation when modal is shown
    const modal = document.getElementById('gfamDetailModal');
    modal.addEventListener('show.bs.modal', function() {
      this.classList.add('fade');
    });

    modal.addEventListener('shown.bs.modal', function() {
      this.classList.add('show');
    });

    modal.addEventListener('hide.bs.modal', function() {
      this.classList.remove('show');
    });

    // Custom dropdowns by class
    const dropdowns = document.querySelectorAll('.gfam-detail-dropdown-modal');

    dropdowns.forEach(dropdown => {
      const toggleBtn = dropdown.querySelector('.gfam-detail-dropdown-toggle');
      const dropdownMenu = dropdown.querySelector('.gfam-detail-dropdown-menu');
      const dropdownText = dropdown.querySelector('.gfam-detail-dropdown-text');
      const dropdownInput = dropdown.querySelectorAll('input[name="make"]'); // NodeList
      const dropdownItems = dropdown.querySelectorAll('.gfam-detail-dropdown-item');

      // Auto-select if only one dropdown item exists
      if (dropdownItems.length === 1) {
        //One item → auto-select it
        const item = dropdownItems[0];
        item.classList.add('selected');
        dropdownText.textContent = item.textContent;
        dropdownText.style.color = '#333';

        dropdownInput.forEach(input => {
          input.value = item.getAttribute('data-value');
        });
      } else if (dropdownItems.length === 0) {
        // No items → clear text and input
        dropdownText.textContent = 'Select an option';
        dropdownText.style.color = '#999';

        dropdownInput.forEach(input => {
          input.value = '';
        });
      }

      const closeDropdown = () => {
        dropdown.classList.remove('active');
        dropdownMenu.classList.remove('show');
        document.removeEventListener('click', outsideClickListener);
      };

      const outsideClickListener = (e) => {
        if (!dropdown.contains(e.target)) closeDropdown();
      };

      const toggleDropdown = (e) => {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = dropdownMenu.classList.contains('show');

        // Close other dropdowns
        document.querySelectorAll('.gfam-detail-dropdown-menu.show').forEach(menu => {
          menu.classList.remove('show');
          menu.closest('.gfam-detail-dropdown-modal')?.classList.remove('active');
        });

        if (!isOpen) {
          dropdownMenu.classList.add('show');
          dropdown.classList.add('active');
          document.addEventListener('click', outsideClickListener);
        }
      };

      toggleBtn?.addEventListener('click', toggleDropdown);

      dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
          // Remove previous selection
          dropdownItems.forEach(i => i.classList.remove('selected'));

          // Set new selection
          item.classList.add('selected');
          dropdownText.textContent = item.textContent;
          dropdownText.style.color = '#333';

          // Update all hidden inputs in this dropdown (usually only one)
          dropdownInput.forEach(input => {
            input.value = item.getAttribute('data-value');

            // Trigger validation manually for this input
            if (typeof $ !== 'undefined' && $(input).closest('form').length) {
              $(input).closest('form').validate().element(input);
            }
          });

          closeDropdown();
        });
      });

      // Close dropdown on Escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDropdown();
      });
    });

    // Datepicker
    const picker = new Litepicker({
      element: document.getElementById('gfam-detail-datepicker'),
      singleMode: false,
      numberOfMonths: 1,
      numberOfColumns: 1,
      format: 'DD/MM/YYYY',
      dropdowns: {
        minYear: 2020,
        maxYear: 2034,
        months: true,
        years: true
      },
      setup: (picker) => {
        picker.on('render', () => {
          // Custom styling already handled via CSS
        });
      }
    });
    // Timepicker
    $('#gfam-detail-timepicker').timepicki({
      show_meridian: true, // 12-hour format
      min_hour_value: 1,
      max_hour_value: 12,
      step_size_minutes: 1,
      overflow_minutes: true,
      increase_direction: 'up',
      disable_keyboard_mobile: true
    });

    var gallery = lightGallery(document.getElementById('gallery'), {
      selector: 'a'
    });

    $('#openGallery').on('click', function() {
      gallery.openGallery();
    });
    
    $('.detailGalleryOpen').on('click', function(e) {
      e.preventDefault();
      gallery.openGallery();
    });

    // Event listeners for showing/hiding the custom button
    $('#gallery').on('lgAfterOpen', function() {
      $('#shared-caption').fadeIn();
    });

    $('#gallery').on('lgBeforeClose', function() {
      $('#shared-caption').hide();
    });



  });

  // function changeMainImage(index) {
  //   $('.gfam-detail-main-slider').trigger('to.owl.carousel', index);
  //   $('.gfam-detail-thumb-slider img').removeClass('active');
  //   $('.gfam-detail-thumb-slider .item').eq(index).find('img').addClass('active');
  // }

  function changeMainImage(index) {
    jQuery('.gfam-detail-main-slider').trigger('to.owl.carousel', index);
    jQuery('.gfam-detail-thumb-slider img').removeClass('active');
    jQuery('.gfam-detail-thumb-slider .item').eq(index).find('img').addClass('active');
  }

  // let lgInstance;

  // document.addEventListener("DOMContentLoaded", function () {
  //   const galleryElement = document.getElementById("gallery");

  //   lgInstance = lightGallery(galleryElement, {
  //     selector: 'a',
  //     plugins: [lgZoom],
  //     download: false,
  //     zoom: true,
  //     showZoomInOutIcons: false,
  //     actualSize: false
  //   });

  //   document.getElementById("openGallery").addEventListener("click", function () {
  //     lgInstance.openGallery(0);
  //   });
  // });

  // document.addEventListener('DOMContentLoaded', function() {
  // });

  document.querySelectorAll('.toggle-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const text = this.previousElementSibling;
        text.classList.toggle('expanded');
        this.textContent = text.classList.contains('expanded') ? 'Show less' : 'Show more';
      });
    });

 jQuery(document).ready(function ($) {
  function AddReadMore() {
    var carLmt = 300;
    var readMoreTxt = " read more";
    var readLessTxt = " read less";

    $(".add-read-more").each(function () {
      var content = $(this).html().trim();

      // Skip if already processed
      if ($(this).find(".second-section").length) return;

      var tempDiv = $("<div>").html(content);
      var fullText = tempDiv.text();

      if (fullText.length > carLmt) {
        var visibleHTML = "";
        var hiddenHTML = "";
        var currentLength = 0;

        tempDiv.contents().each(function () {
          if (currentLength >= carLmt) {
            hiddenHTML += $("<div>").append($(this).clone()).html();
            return;
          }

          var nodeText = $(this).text();
          if (currentLength + nodeText.length <= carLmt) {
            visibleHTML += $("<div>").append($(this).clone()).html();
            currentLength += nodeText.length;
          } else {
            var remaining = carLmt - currentLength;
            if (this.nodeType === 3) {
              visibleHTML += this.nodeValue.substring(0, remaining);
              hiddenHTML += this.nodeValue.substring(remaining);
            } else {
              visibleHTML += $("<div>").append($(this).clone()).html();
            }
            currentLength = carLmt;
          }
        });

        var finalHTML =
          "<span class='first-section'>" +
          visibleHTML +
          "</span><span class='second-section'>" +
          hiddenHTML +
          "</span><span class='read-more' title='Click to Show More'>" +
          readMoreTxt +
          "</span><span class='read-less' title='Click to Show Less'>" +
          readLessTxt +
          "</span>";

        $(this).html(finalHTML);
      }
    });

    // Toggle content
    $(document).on("click", ".read-more, .read-less", function () {
      var container = $(this).closest(".add-read-more");
      container.toggleClass("show-less-content show-more-content");

      // Toggle visibility
      if (container.hasClass("show-more-content")) {
        container.find(".read-more").hide();
        container.find(".read-less").css("display", "block");
      } else {
        container.find(".read-more").css("display", "block");
        container.find(".read-less").hide();
      }
    });

    // Initial state setup
    $(".add-read-more").each(function () {
      $(this).find(".read-more").css("display", "block");
      $(this).find(".read-less").hide();
    });
  }

  AddReadMore();
});

</script>

<style>
/* .gfam-loader {
  color: #333;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.gfam-loader .spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #ccc;
  border-top-color: #92191C;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
} */
</style>