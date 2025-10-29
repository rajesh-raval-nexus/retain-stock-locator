<?php
global $xmlPath;

$stock_number = isset($_GET['stock_number']) ? sanitize_text_field($_GET['stock_number']) : '';

if ($stock_number) {

$allListingsData = rsl_parse_listings($xmlPath);

$vehicle_seach = RSL_PLUGIN_URL . 'assets/images/vehicle-seach.svg';

foreach ($allListingsData as $listing) {

  if ($listing['stock_number'] === $stock_number) { ?>
      <?php 
        // echo "<pre>";
        // print_r($listing);
        // echo "</pre>";
      ?>
      <!-- Header -->
      <div class="gfam-detail-header">
        <div class="container">
          <div class="row">
            <div class="col-xl-6 order-xl-1 order-2">
              <nav class="d-flex align-items-center gap-2 gfam-detail-breadcrumb">
                <a href="#" class="d-flex align-items-center">
                  <svg class="mb-1" width="19" height="22" viewBox="0 0 19 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M17.4865 22H13.5926C12.7593 22 12.0815 21.164 12.0815 20.1359V12.9631C12.0815 12.7504 11.9403 12.5761 11.7678 12.5761H7.22977C7.05735 12.5761 6.91606 12.7504 6.91606 12.9631V20.1359C6.91606 21.164 6.23834 22 5.40497 22H1.51109C0.677719 22 0 21.164 0 20.1359V10.4402C0 9.46529 0.344847 8.54357 0.948325 7.91433L7.76141 0.788774C8.7672 -0.262925 10.228 -0.262925 11.2338 0.788774L18.0517 7.91728C18.6552 8.54653 19 9.46824 19 10.4431V20.1359C19 21.164 18.3223 22 17.4889 22H17.4865ZM7.22738 11.099H11.7654C12.5988 11.099 13.2765 11.935 13.2765 12.9631V20.1359C13.2765 20.3486 13.4178 20.5229 13.5902 20.5229H17.4841C17.6565 20.5229 17.7978 20.3486 17.7978 20.1359V10.4431C17.7978 9.90251 17.6063 9.39143 17.271 9.04284L10.4531 1.91433C9.89514 1.33235 9.08571 1.33235 8.52773 1.91433L1.71465 9.03988C1.37938 9.38848 1.19019 9.89956 1.19019 10.4402V20.1359C1.19019 20.3486 1.33148 20.5229 1.50391 20.5229H5.39778C5.57021 20.5229 5.7115 20.3486 5.7115 20.1359V12.9631C5.7115 11.935 6.38921 11.099 7.22259 11.099H7.22738Z"
                      fill="#fff" />
                  </svg>
                </a>
                <span> > </span>
                <a href="#"><?php esc_html_e('Stock Locator', 'retain-stock-locator'); ?></a>
                <span> > </span>
                <span class="active"><?php echo $listing['item_specification']; ?></span>
              </nav>

            </div>
            <div class="col-xl-6 order-xl-2 order-1">
              <div class="gfam-detail-nav">
                <a href="#openGallery" class="active"><?php esc_html_e('Gallery', 'retain-stock-locator'); ?></a>
                <a href="#vehicleFeatures"><?php esc_html_e('Vehicle Features', 'retain-stock-locator'); ?></a>
                <a href="#dealerComments"><?php esc_html_e('Dealer Comments', 'retain-stock-locator'); ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Title -->
      <div class="gfam-detail-main-title">
        <div class="container">
          <h1 class="gfam-detail-title">
            <?php  if ( isset($listing['item_specification']) ) {
                  echo esc_html( $listing['item_specification'] );
              } ?>
          </h1>
          <div class="gfam-detail-price-section d-xl-none d-flex">
            <?php if (! empty($listing['price'])) { ?>  
              <div class="gfam-detail-price-label mb-0"><?php esc_html_e('Price', 'retain-stock-locator'); ?></div>
              <div class="gfam-detail-price"><?php echo '$' . esc_html( $listing['price'] ); ?></div>
            <?php } ?>
            <div class="gfam-detail-price-estimate">Est. $556/week*</div>
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
                                      <img src="<?php echo esc_url( $img_url ); ?>" 
                                          alt="Valtra Tractor <?php echo esc_attr( $index + 1 ); ?>" 
                                          class="img-fluid">
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
                    <button class="btn btn-warning me-2"><?php esc_html_e('Call', 'retain-stock-locator'); ?></button>
                    <button class="btn btn-warning"><?php esc_html_e('Message', 'retain-stock-locator'); ?></button>
                  </div>
                </div>
              </div>

              <!-- Vehicle Features Section -->
              <div id="vehicleFeatures" class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-vehicle-features">
                    <h2 class="gfam-detail-section-title"><?php esc_html_e('Vehicle', 'retain-stock-locator'); ?> <span><?php esc_html_e('Features', 'retain-stock-locator'); ?></span></h2>

                    <div class="row">
                      <div class="col-lg-3 col-md-6 col-3">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <svg width="34" height="42" viewBox="0 0 34 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M15.587 26.6294C15.2231 26.6294 14.8593 26.4931 14.5807 26.2148L9.89051 21.5283C9.33337 20.9716 9.33337 20.074 9.89051 19.5173C10.4476 18.9606 11.3459 18.9606 11.903 19.5173L15.587 23.1984L23.9554 14.8365C24.5125 14.2798 25.4108 14.2798 25.9679 14.8365C26.525 15.3932 26.525 16.2907 25.9679 16.8474L16.5932 26.2148C16.3147 26.4931 15.9508 26.6294 15.587 26.6294Z"
                                fill="black" />
                              <path
                                d="M16.7698 41.3706C15.7181 41.3706 14.6664 41.0695 13.7511 40.4617C11.4543 38.9449 0.0102539 31.1227 0.0102539 26.1919V8.33781C0.0102539 5.67928 8.89033 0.69165 16.7698 0.69165C24.6493 0.69165 33.5294 5.67928 33.5294 8.33781V26.1919C33.5294 31.1227 22.0854 38.9449 19.7886 40.4617C18.8733 41.0695 17.8216 41.3706 16.7698 41.3706ZM2.85279 8.63321V26.1919C2.85279 27.987 7.51458 32.9292 15.3202 38.0929C16.2014 38.6723 17.3384 38.678 18.2196 38.0929C26.0252 32.9292 30.6869 27.987 30.6869 26.1919V8.63321C29.7034 7.44596 23.7227 3.53196 16.7698 3.53196C9.81699 3.53196 3.83631 7.44596 2.85279 8.63321Z"
                                fill="black" />
                            </svg>
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php esc_html_e('Availability', 'retain-stock-locator'); ?></h5>
                            <p><?php echo esc_html( $listing['status'] ); ?></p>
                          </div>
                        </div>
                      </div>

                      <?php //if (! empty($listing['year'])) : ?>
                      <div class="col-lg-3 col-md-6 col-3">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <svg width="42" height="44" viewBox="0 0 42 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M38.0849 43.512H4.37249C2.40545 43.512 0.802246 41.9101 0.802246 39.9446V8.99663C0.802246 7.03113 2.40545 5.4292 4.37249 5.4292H38.0849C40.0519 5.4292 41.6551 7.03113 41.6551 8.99663V39.9446C41.6551 41.9101 40.0519 43.512 38.0849 43.512ZM4.37249 8.26951C3.96885 8.26951 3.64478 8.5933 3.64478 8.99663V39.9446C3.64478 40.3479 3.96885 40.6717 4.37249 40.6717H38.0849C38.4885 40.6717 38.8126 40.3479 38.8126 39.9446V8.99663C38.8126 8.5933 38.4885 8.26951 38.0849 8.26951H4.37249Z"
                                fill="black" />
                              <path
                                d="M40.2338 16.7336H2.22351C1.43897 16.7336 0.802246 16.0974 0.802246 15.3135C0.802246 14.5295 1.43897 13.8933 2.22351 13.8933H40.2338C41.0184 13.8933 41.6551 14.5295 41.6551 15.3135C41.6551 16.0974 41.0184 16.7336 40.2338 16.7336Z"
                                fill="black" />
                              <path
                                d="M27.7094 8.27518C26.9248 8.27518 26.2881 7.63895 26.2881 6.85502V1.75951C26.2881 0.975585 26.9248 0.339355 27.7094 0.339355C28.4939 0.339355 29.1306 0.975585 29.1306 1.75951V6.85502C29.1306 7.63895 28.4939 8.27518 27.7094 8.27518Z"
                                fill="black" />
                              <path
                                d="M14.7474 8.27518C13.9629 8.27518 13.3262 7.63895 13.3262 6.85502V1.75951C13.3262 0.975585 13.9629 0.339355 14.7474 0.339355C15.532 0.339355 16.1687 0.975585 16.1687 1.75951V6.85502C16.1687 7.63895 15.532 8.27518 14.7474 8.27518Z"
                                fill="black" />
                              <path
                                d="M18.8409 34.1333C18.4771 34.1333 18.1132 33.9969 17.8347 33.7186L13.0648 28.9526C12.5077 28.3959 12.5077 27.4983 13.0648 26.9416C13.622 26.3849 14.5202 26.3849 15.0774 26.9416L18.8466 30.7079L27.3856 22.1756C27.9428 21.6189 28.8409 21.6189 29.398 22.1756C29.9552 22.7323 29.9552 23.6298 29.398 24.1865L19.8529 33.7243C19.5743 34.0026 19.2105 34.139 18.8466 34.139L18.8409 34.1333Z"
                                fill="black" />
                            </svg>
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php echo __( 'Year', 'retain-stock-locator' ); ?></h5>
                            <p><?php if($listing['year']){
                              echo esc_html( $listing['year'] );
                            }else{ echo " - "; }  ?></p>
                          </div>
                        </div>
                      </div>
                       <?php //endif; ?>

                      <div class="col-lg-3 col-md-6 col-3">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="url(#clip0_1520_9644)">
                                <path
                                  d="M42.1928 25.6018C42.1928 30.3634 40.7924 34.6115 37.9914 38.3461C37.7113 38.7196 37.3379 38.9296 36.8711 38.9763C36.4042 39.023 35.9841 38.9063 35.6106 38.6262C35.2372 38.3461 35.0271 37.996 34.9804 37.5759C34.9337 37.1557 35.0504 36.7589 35.3305 36.3855C37.758 33.2111 38.9718 29.6165 38.9718 25.6018C38.9718 22.4274 38.1782 19.4864 36.591 16.7789C35.0038 14.0713 32.833 11.9239 30.0788 10.3367C27.3245 8.74949 24.3602 7.95589 21.1858 7.95589C18.0114 7.95589 15.047 8.74949 12.2928 10.3367C9.53853 11.9239 7.3678 14.0713 5.7806 16.7789C4.1934 19.4864 3.39979 22.4274 3.39979 25.6018C3.39979 29.6165 4.61354 33.2111 7.04102 36.3855C7.32111 36.7589 7.43782 37.1557 7.39114 37.5759C7.34446 37.996 7.13438 38.3461 6.76093 38.6262C6.38747 38.9063 5.96733 39.023 5.5005 38.9763C5.03368 38.9296 4.66022 38.7196 4.38012 38.3461C1.57918 34.6115 0.178711 30.3634 0.178711 25.6018C0.178711 21.8672 1.11236 18.3894 2.97965 15.1683C4.84695 11.9472 7.39114 9.40305 10.6122 7.53575C13.8333 5.66846 17.3578 4.73481 21.1858 4.73481C25.0137 4.73481 28.5383 5.66846 31.7593 7.53575C34.9804 9.40305 37.5246 11.9472 39.3919 15.1683C41.2592 18.3894 42.1928 21.8672 42.1928 25.6018ZM31.5493 15.7985C31.9227 16.0786 32.1095 16.4521 32.1095 16.9189C32.1095 17.3857 31.9227 17.7592 31.5493 18.0393L26.2275 23.5011C26.6943 24.3414 26.9277 25.2284 26.9277 26.162C26.9277 27.7492 26.3675 29.103 25.2471 30.2234C24.1268 31.3438 22.773 31.9039 21.1858 31.9039C19.5986 31.9039 18.2448 31.3438 17.1244 30.2234C16.004 29.103 15.4438 27.7492 15.4438 26.162C15.4438 24.5748 16.004 23.221 17.1244 22.1006C18.2448 20.9803 19.5986 20.4201 21.1858 20.4201C22.1194 20.4201 23.0064 20.6535 23.8467 21.1203L29.3085 15.7985C29.5886 15.4251 29.9621 15.2383 30.4289 15.2383C30.8957 15.2383 31.2692 15.4251 31.5493 15.7985ZM23.7066 26.162C23.7066 25.5085 23.4499 24.9483 22.9364 24.4815C22.4229 24.0146 21.8393 23.7812 21.1858 23.7812C20.5322 23.7812 19.9487 24.0146 19.4352 24.4815C18.9217 24.9483 18.6649 25.5085 18.6649 26.162C18.6649 26.8156 18.9217 27.3991 19.4352 27.9126C19.9487 28.4261 20.5322 28.6829 21.1858 28.6829C21.8393 28.6829 22.4229 28.4261 22.9364 27.9126C23.4499 27.3991 23.7066 26.8156 23.7066 26.162Z"
                                  fill="black" />
                              </g>
                              <defs>
                                <clipPath id="clip0_1520_9644">
                                  <rect width="42.0141" height="42.0141" fill="white"
                                    transform="matrix(1 0 0 -1 0.178711 42.8276)" />
                                </clipPath>
                              </defs>
                            </svg>
                          </div>
                          <div class="gfam-detail-feature-content">
                            <h5><?php esc_html_e('Odometer', 'retain-stock-locator'); ?></h5>
                            <?php if (! empty($listing['hours'])) : ?>
                              <p><?php echo number_format($listing['hours'], 0, '.', ','); ?> Kms</p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-3 col-md-6 col-3">
                        <div class="gfam-detail-feature-item">
                          <div class="gfam-detail-feature-icon">
                            <svg width="58" height="45" viewBox="0 0 58 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M7.72034 38.4444C4.56111 38.4444 2 35.895 2 32.7501V27.5111C2 23.897 4.81921 20.9401 8.38756 20.696L14.3399 7.40183C15.8109 4.1164 19.0861 2 22.6997 2H33.1216C36.5604 2 39.7089 3.91881 41.2728 6.96761L44.2161 12.7056M56 27.5111C56 23.7372 52.9267 20.6778 49.1356 20.6778H17.9025M27.2839 12.7056V2.11389M31.4025 38.4444H26.2541M19.9617 33.8889C17.4343 33.8889 15.3854 35.9285 15.3854 38.4444C15.3854 40.9604 17.4343 43 19.9617 43C22.489 43 24.538 40.9604 24.538 38.4444C24.538 35.9285 22.4892 33.8889 19.9617 33.8889ZM37.6339 33.8889C35.1066 33.8889 33.0577 35.9285 33.0577 38.4444C33.0577 40.9604 35.1066 43 37.6339 43C40.1612 43 42.2102 40.9604 42.2102 38.4444C42.2102 35.9285 40.1614 33.8889 37.6339 33.8889Z"
                                stroke="#0E0E0E" stroke-width="4" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                            </svg>
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
              <div id="dealerComments" class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-dealer-comments">
                    <h2 class="gfam-detail-section-title">
                      <?php esc_html_e('Dealer', 'retain-stock-locator'); ?>
                      <span><?php esc_html_e('Comments', 'retain-stock-locator'); ?></span>
                    </h2>

                    <div class="gfam-detail-comments-content">
                      <p><?php echo esc_html( $listing['description'] ); ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Vehicle Details Section -->
              <div id="vehicleDetails" class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-vehicle-details">
                    <h2 class="gfam-detail-section-title">
                      <?php esc_html_e('Vehicle', 'retain-stock-locator'); ?>
                      <span><?php esc_html_e('Details', 'retain-stock-locator'); ?></span>
                    </h2>
                    <div class="gfam-detail-details-table">
                            
                      <?php if (! empty($listing['make'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Make', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['make']); ?></div>
                        </div>
                      <?php endif; ?>

                      <?php if (! empty($listing['model'])) : ?>
                        <div class="gfam-detail-detail-row">
                          <div class="gfam-detail-detail-label"><?php esc_html_e('Model', 'retain-stock-locator'); ?></div>
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['model']); ?></div>
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
                          <div class="gfam-detail-detail-value"><?php echo esc_html($listing['price']); ?></div>
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
              <div class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-question-box">
                    <div class="row w-100 mx-auto">
                      <div class="col-lg-9 my-auto">
                        <div class="custom-select-wrapper">
                          <div class="custom-select">
                            <span class="selected d-block"><?php esc_html_e( 'Select a question', 'retain-stock-locator' ); ?></span>
                            <div class="custom-arrow">
                              <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.4435 1.39511C15.4435 1.53322 15.3901 1.67135 15.281 1.77709L9.2607 7.61035C8.41434 8.43042 7.04012 8.43042 6.19376 7.61035L0.173469 1.77709C-0.0448025 1.5656 -0.0448025 1.22461 0.173469 1.01312C0.39174 0.80163 0.743656 0.80163 0.961928 1.01312L6.98219 6.84648C7.18042 7.03855 7.44548 7.14638 7.72834 7.14638C8.01121 7.14638 8.27625 7.04071 8.47447 6.84648L14.4948 1.01312C14.713 0.801629 15.0649 0.801629 15.2832 1.01312C15.3923 1.11887 15.4458 1.25699 15.4458 1.39511L15.4435 1.39511Z" fill="#847878" />
                              </svg>
                            </div>
                            <ul class="options">
                              <li data-value="q1"><?php esc_html_e( 'Do you have any similar cars available?', 'retain-stock-locator' ); ?></li>
                              <li data-value="q2"><?php esc_html_e( 'How do I book a test drive?', 'retain-stock-locator' ); ?></li>
                              <li data-value="q3"><?php esc_html_e( 'What documents do I need?', 'retain-stock-locator' ); ?></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 text-end">
                        <button class="gfam-detail-button my-2 ask-question-btn" data-bs-toggle="modal"
                        data-bs-target="#askQuestionModal"><?php esc_html_e( 'Ask a Question', 'retain-stock-locator' ); ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                $allListings = rsl_parse_listings($xmlPath);
                $similardata = trim($listing['make']);

                $matchingListings = [];
                $counter = 0;
                
                foreach ($allListings as $item) {
                  if (isset($item['make']) && strcasecmp(trim($item['make']), $similardata) === 0) {
                    $matchingListings[] = $item;
                    $counter++;
                  }
                  if ($counter >= 3) {
                    break; // Stop after 5 matching records
                  }
                }
              ?>
              <!-- similar products Section -->
              <div class="row mt-lg-5 mt-4">
                <div class="col-12">
                  <div class="gfam-detail-similiar-product">
                    <h2 class="gfam-detail-section-title text-center"><?php esc_html_e('Similar Listings', 'retain-stock-locator'); ?> <span><?php esc_html_e('You Might Like', 'retain-stock-locator'); ?></span></h2>
                    <!-- Product Grid -->
                    <div class="gfam-product-grid owl-carousel owl-theme">
                      <?php foreach ($matchingListings as $listingItem) : 
                          $price = $listingItem['price'];
                          $hours = $listingItem['hours'];
                        ?>
                        <div class="gfam-product-card item">
                          <div class="gfam-product-image">
                            <div class="owl-carousel gfam-carousel">
                              <?php foreach ($listingItem['images'] as $image) : ?>
                                <div class="item">
                                  <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($listingItem['make'] . ' ' . $listingItem['model']); ?>" />
                                </div>
                              <?php endforeach; ?>
                            </div>
                          </div>
                          <div class="gfam-product-info">
                            <div class="gfam-product-badges mb-4">
                              <span class="gfam-badge gfam-badge-new">NEW</span>
                              <span class="gfam-badge gfam-badge-code"><?php echo esc_html($listingItem['stock_number']); ?></span>
                            </div>
                            <h3 class="gfam-product-title"><?php echo esc_html($listingItem['year'] . ' ' . $listingItem['make'] . ' ' . $listingItem['model']); ?></h3>
                            <!-- <p class="gfam-product-subtitle">
                                        Trailing Dry Fertiliser Spreaders
                                      </p> -->
                            <p class="gfam-product-subtitle"><?php echo esc_html($listingItem['type']); ?></p>

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

                            <!-- <button class="gfam-btn">See Details</button> -->
                            <a class="gfam-btn" href="<?php echo site_url('/listing-detail/?stock_number=' . $listingItem['stock_number']); ?>">
                              <?php esc_html_e( 'See Details', 'retain-stock-locator' ); ?>
                            </a>
                          </div>
                        </div>
                      <?php endforeach; ?>

                    </div>

                  </div>
                </div>
              </div>
            </div>

            <!-- Right Side - Price and Info -->
            <div class="col-xl-4 sticky-section">
              <div class="gfam-detail-sidebar">
                <!-- Price Section -->
                <div class="gfam-detail-price-section">
                  <?php if (! empty($listing['price'])) { ?>
                    <div class="gfam-detail-price-label mb-0 d-none d-xl-block">Price</div>
                    <div class="gfam-detail-price d-none d-xl-block"><?php echo '$' . $listing['price']; ?></div>
                  <?php } ?>

                  <div class="gfam-detail-price-estimate d-none d-xl-inline-block">Est. $556/week*</div>

                  <button class="gfam-detail-contact-btn d-none d-xl-block">Contact Us</button>

                  <div class="accordion gfam-detail-form-accordion d-none d-xl-block" id="gfam-detailAccordion">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="gfam-detailHeading">
                        <button class="accordion-button collapsed gfam-detail-toggle-btn gfam-detail-callback-btn"
                          type="button" data-bs-toggle="collapse" data-bs-target="#gfam-detailCollapse">
                          Request a Call Back
                        </button>
                      </h2>
                      <div id="gfam-detailCollapse" class="accordion-collapse collapse"
                        data-bs-parent="#gfam-detailAccordion">
                        <div class="accordion-body gfam-detail-form-box">
                          <h5 class="gfam-detail-title">REQUEST A <br> CALL BACK</h5>

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
                              <label class="form-check-label" for="gfam-detail-trade">I have trade in</label>
                            </div>

                            <div class="col-12 mt-3">
                              <button type="submit" class="btn gfam-detail-submit w-100">LET’S CHAT</button>
                            </div>
                          </form>

                          <div id="gfam-response" style="margin-top:10px;"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- Easy Steps Section -->
                <div class="gfam-detail-steps-section d-none d-xl-block">
                  <h3 class="gfam-detail-steps-title">Easy Steps to<br>Own Your Vehicle</h3>

                  <div class="gfam-detail-step-item">
                    <div class="gfam-detail-step-icon me-4">
                      <img src="<?php echo esc_url( $vehicle_seach ); ?>" alt="Vehicle Icon" style="max-width: unset;">
                      <!-- <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1520_9890)">
                          <g clip-path="url(#clip1_1520_9890)">
                            <path
                              d="M23.9274 4.86792C20.2261 4.86792 16.8713 7.04562 15.3645 10.4262L9.26742 24.1054C5.61234 24.3566 2.72461 27.3991 2.72461 31.1179V36.5086C2.72461 39.7446 5.34797 42.3679 8.58398 42.3679H10.19C10.1987 41.917 10.2102 41.4662 10.2246 41.0153C9.84316 40.2378 9.62813 39.3638 9.62813 38.4393L9.52148 34.282C9.52148 31.2075 10.147 30.0242 12.4292 29.5645C13.514 29.3459 14.4222 28.6068 14.8667 27.5934L22.3957 10.4262C23.9024 7.04562 27.2573 4.86792 30.9586 4.86792H23.9274Z"
                              fill="#EEF1FB" />
                            <path
                              d="M8.58398 42.3679C5.34797 42.3679 2.72461 39.7446 2.72461 36.5086V31.1179C2.72461 27.3991 5.61234 24.3566 9.26742 24.1054L15.3645 10.4262C16.8712 7.04562 20.226 4.86792 23.9274 4.86792H34.6026C38.125 4.86792 41.3501 6.8423 42.952 9.97941L45.9668 15.8836M58.0371 31.1179C58.0371 27.2347 54.8891 24.0867 51.0059 24.0867H19.0137M28.6231 15.8836V4.98511M32.8418 42.3679H27.5682M21.1229 37.6804C18.5341 37.6804 16.4354 39.7791 16.4354 42.3679C16.4354 44.9567 18.5341 47.0554 21.1229 47.0554C23.7116 47.0554 25.8104 44.9567 25.8104 42.3679C25.8104 39.7791 23.7118 37.6804 21.1229 37.6804Z"
                              stroke="#0E0E0E" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                            <path
                              d="M58.037 42.0164C58.037 46.5145 54.3906 50.2195 49.8925 50.2195C45.3944 50.2195 41.748 46.5731 41.748 42.075C41.748 37.5768 45.453 33.9304 49.9511 33.9304M43.916 48.5203L36.4746 55.9617"
                              stroke="#999999" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                          </g>
                        </g>
                        <defs>
                          <clipPath id="clip0_1520_9890">
                            <rect width="58.2301" height="58.2301" fill="white" transform="translate(0.380859 0.414795)" />
                          </clipPath>
                          <clipPath id="clip1_1520_9890">
                            <rect width="60" height="60" fill="white" transform="translate(0.380859 0.414795)" />
                          </clipPath>
                        </defs>
                      </svg> -->
                    </div>
                    <div class="gfam-detail-step-content">
                      <h4>Video Walkaround</h4>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                      <a href="#" class="gfam-detail-step-link" data-bs-toggle="modal"
                        data-bs-target="#gfamDetailModal">Send Message >></a>
                    </div>
                  </div>

                  <div class="gfam-detail-step-item">
                    <div class="gfam-detail-step-icon me-4">
                      <img src="<?php echo esc_url( $vehicle_seach ); ?>" alt="Vehicle Icon" style="max-width: unset;">
                      <!-- <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1520_9890)">
                          <g clip-path="url(#clip1_1520_9890)">
                            <path
                              d="M23.9274 4.86792C20.2261 4.86792 16.8713 7.04562 15.3645 10.4262L9.26742 24.1054C5.61234 24.3566 2.72461 27.3991 2.72461 31.1179V36.5086C2.72461 39.7446 5.34797 42.3679 8.58398 42.3679H10.19C10.1987 41.917 10.2102 41.4662 10.2246 41.0153C9.84316 40.2378 9.62813 39.3638 9.62813 38.4393L9.52148 34.282C9.52148 31.2075 10.147 30.0242 12.4292 29.5645C13.514 29.3459 14.4222 28.6068 14.8667 27.5934L22.3957 10.4262C23.9024 7.04562 27.2573 4.86792 30.9586 4.86792H23.9274Z"
                              fill="#EEF1FB" />
                            <path
                              d="M8.58398 42.3679C5.34797 42.3679 2.72461 39.7446 2.72461 36.5086V31.1179C2.72461 27.3991 5.61234 24.3566 9.26742 24.1054L15.3645 10.4262C16.8712 7.04562 20.226 4.86792 23.9274 4.86792H34.6026C38.125 4.86792 41.3501 6.8423 42.952 9.97941L45.9668 15.8836M58.0371 31.1179C58.0371 27.2347 54.8891 24.0867 51.0059 24.0867H19.0137M28.6231 15.8836V4.98511M32.8418 42.3679H27.5682M21.1229 37.6804C18.5341 37.6804 16.4354 39.7791 16.4354 42.3679C16.4354 44.9567 18.5341 47.0554 21.1229 47.0554C23.7116 47.0554 25.8104 44.9567 25.8104 42.3679C25.8104 39.7791 23.7118 37.6804 21.1229 37.6804Z"
                              stroke="#0E0E0E" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                            <path
                              d="M58.037 42.0164C58.037 46.5145 54.3906 50.2195 49.8925 50.2195C45.3944 50.2195 41.748 46.5731 41.748 42.075C41.748 37.5768 45.453 33.9304 49.9511 33.9304M43.916 48.5203L36.4746 55.9617"
                              stroke="#999999" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                          </g>
                        </g>
                        <defs>
                          <clipPath id="clip0_1520_9890">
                            <rect width="58.2301" height="58.2301" fill="white" transform="translate(0.380859 0.414795)" />
                          </clipPath>
                          <clipPath id="clip1_1520_9890">
                            <rect width="60" height="60" fill="white" transform="translate(0.380859 0.414795)" />
                          </clipPath>
                        </defs>
                      </svg> -->
                    </div>
                    <div class="gfam-detail-step-content">
                      <h4>Test Drive</h4>
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                      <a href="#" class="gfam-detail-step-link" data-bs-toggle="modal"
                        data-bs-target="#gfamtestdriverModal">Send Message >></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--Video walkthrogh Modal -->
      <div class="modal fade gfam-detail-modal" id="gfamDetailModal" tabindex="-1" aria-labelledby="gfamDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content">
            <div class="gfam-detail-modal-header">
              <h5 class="gfam-detail-modal-title" id="gfamDetailModalLabel">Request a Video Walkthrough</h5>
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
                      <span class="gfam-detail-dropdown-text">Make</span>
                      <svg class=" gfam-detail-dropdown-arrow" width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.4435 1.39511C15.4435 1.53322 15.3901 1.67135 15.281 1.77709L9.2607 7.61035C8.41434 8.43042 7.04012 8.43042 6.19376 7.61035L0.173469 1.77709C-0.0448025 1.5656 -0.0448025 1.22461 0.173469 1.01312C0.39174 0.80163 0.743656 0.80163 0.961928 1.01312L6.98219 6.84648C7.18042 7.03855 7.44548 7.14638 7.72834 7.14638C8.01121 7.14638 8.27625 7.04071 8.47447 6.84648L14.4948 1.01312C14.713 0.801629 15.0649 0.801629 15.2832 1.01312C15.3923 1.11887 15.4458 1.25699 15.4458 1.39511L15.4435 1.39511Z" fill="#847878"></path>
                              </svg>
                      <!-- <i class="fas fa-chevron-down gfam-detail-dropdown-arrow"></i> -->
                    </button>
                    <div class="gfam-detail-dropdown-menu" id="reqVideoDropdownMenu">
                      <div class="gfam-detail-dropdown-item" data-value="<?php echo esc_html($listing['make']); ?>"><?php echo esc_html($listing['make']); ?></div>
                      <!-- <div class="gfam-detail-dropdown-item" data-value="1760-r-ensuite">1760 R/ENSUITE (1)</div>
                      <div class="gfam-detail-dropdown-item" data-value="aero-full-ensuite">AERO FULL ENSUITE (1)</div>
                      <div class="gfam-detail-dropdown-item" data-value="all-terrain">ALL TERRAIN (1)</div>
                      <div class="gfam-detail-dropdown-item" data-value="birdsville-b7452sl">BIRDSVILLE B7452SL (1)</div> -->
                    </div>
                    <input type="text" name="make" id="gfamMakeInput" required style="visibility: hidden; position: absolute;">
                  </div>
                </div>

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn">Request a Video</button>
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
                      <!--<div class="gfam-detail-dropdown-item" data-value="1760-r-ensuite">1760 R/ENSUITE (1)</div>
                       <div class="gfam-detail-dropdown-item" data-value="aero-full-ensuite">AERO FULL ENSUITE (1)</div>
                      <div class="gfam-detail-dropdown-item" data-value="all-terrain">ALL TERRAIN (1)</div>
                      <div class="gfam-detail-dropdown-item" data-value="birdsville-b7452sl">BIRDSVILLE B7452SL (1)</div> -->
                    </div>
                    <input type="text" name="make" id="testDriveMakeInput" required style="visibility: hidden; position: absolute;">
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <label class="gfam-detail-label">Preferred Date</label>
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
                  <button type="submit" class="gfam-detail-request-btn">Request a Video</button>
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
              <h5 class="gfam-detail-modal-title" id="askQuestionModalLabel">Request a Ask a Question</h5>
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
                  <textarea class="form-control gfam-detail-input" name="comments" rows="4" placeholder="Comments"></textarea>
                </div>

                <div class="gfam-detail-form-group">
                  <button type="submit" class="gfam-detail-request-btn">Send a Request</button>
                </div>
              </form>
              <div id="askQuestionResponse" style="margin-top:10px;"></div>

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
    function smoothScroll(target, offset = 100, speed = 600) {
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
      autoplay: true,
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
      autoplay: true,
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
</script>