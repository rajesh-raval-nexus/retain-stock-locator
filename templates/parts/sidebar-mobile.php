<!-- Right Side - Price and Info -->
            <div class="col-xl-4 sticky-section sticky-section-for-mobile">
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
                      <img src="<?php echo esc_url( $vehicle_seach ); ?>" alt="Vehicle Icon" style="max-width: unset;">
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
                      <img src="<?php echo esc_url( $vehicle_seach ); ?>" alt="Vehicle Icon" style="max-width: unset;">
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