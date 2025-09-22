<?php 

include RSL_PLUGIN_DIR .'templates/vdp-listing/breadcrumb.php';

?>
<div class="container">
    <div class="mobile-header d-xl-none">
        <div class="gfam-searchbar d-flex align-items-center px-3 py-2 rounded">
            <input type="text" class="form-control border-0 shadow-none bg-transparent gfam-search-input" placeholder="Search" />
            <button class="gfam-search-btn border-0 rounded-circle d-flex justify-content-center align-items-center">
                <i class="fas fa-search text-dark"></i>
            </button>
        </div>
        <div class="row text-center justify-content-center p-3">
            <div class="col-6" style="border-right: 1px solid #e0e0e0;">
                <button class="modal-btn bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal" data-bs-target="#mobileFilterModal">
                    <img src=<?php echo RSL_PLUGIN_URL . "assets/images/filter-icon.svg" ?> alt="filter-icon">
                    Filter
                </button>
            </div>
            <div class="col-6">
                <button class="modal-btn bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal" data-bs-target="#mobileSortModal">
                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.5 15V7.5M6.5 15L4.25 12.75M6.5 15L8.75 12.75M12.5 3V10.5M12.5 3L14.75 5.25M12.5 3L10.25 5.25" stroke="#444343" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Sort by
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile Modal -->
    <div class="modal fade modal-slide-up" id="mobileFilterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header gfam-filter-header p-2 px-4">
                    <button type="button" class="text-white bg-transparent border-0 outline-none" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
                    <span>Filter By</span>
                    <span class="gfam-clear-all">Clear All</span>
                </div>
                <div class="modal-body p-0">
                    <div id="gfamSidebarMobile"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sort Modal (Mobile Only) -->
    <div class="modal fade modal-slide-up" id="mobileSortModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header gfam-filter-header p-2 px-4">
                    <button type="button" class="text-white bg-transparent border-0 outline-none" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
                    <span>Sort By</span>
                    <span class="">&nbsp;</span>
                </div>

                <div class="modal-body">
                    <div id="sortDropdownModalContainer"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Wrapper -->

    <div class="gfam-main-wrapper row">
        <!-- Sidebar -->
        <div class="gfam-sidebar col-xl-4 sticky-section d-none d-xl-block" id="gfamSidebarDesktop">
            <!-- Filter Header -->
            <div class="gfam-filter-header">
                <span>Filter By</span>
                <span class="gfam-clear-all">Clear All</span>
            </div>

            <!-- Filter Content -->
            <div class="gfam-filter-content">
                <div class="accordion gfam-filter-section" id="mainAccordion">
                    <!-- Main Toggle: Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="mainHeading">
                            <button class="accordion-button gfam-filter-header-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainCollapse">
                                <span>Category</span>
                            </button>
                        </h2>

                        <!-- Collapsible Body (all filter content inside here) -->
                        <div id="mainCollapse" class="accordion-collapse collapse" data-bs-parent="#mainAccordion">
                            <div class="accordion-body p-0">
                                <!-- Search -->
                                <div class="input-group gfam-search-section">
                                    <input type="text" class="gfam-search-input" placeholder="Type a keyword" aria-label="Type a keyword" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                </div>

                                <!-- Nested Accordion for Categories -->                               
                                <?php echo do_shortcode('[rsl_category_options]');?>
                                <!-- Show All -->
                                <div class="px-3 mb-2">
                                    <span class="show-all">Show all <i class="fas fa-chevron-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion gfam-filter-section" id="mainAccordion2">
                    <!-- Main Toggle: Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="mainHeading">
                            <button class="accordion-button gfam-filter-header-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainCollapse2">
                                <span>Make & Model</span>
                            </button>
                        </h2>

                        <!-- Collapsible Body (all filter content inside here) -->
                        <div id="mainCollapse2" class="accordion-collapse collapse" data-bs-parent="#mainAccordion2">
                            <div class="accordion-body p-0">
                                <!-- Search -->
                                <div class="input-group gfam-search-section">
                                    <input type="text" class="gfam-search-input" placeholder="Type a keyword" aria-label="Type a keyword" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                </div>

                                <!-- Nested Accordion for make and model -->                                

                                <?php echo do_shortcode('[rsl_make_model_options]');?>

                                <!-- Show All -->
                                <div class="px-3 mb-2">
                                    <span class="show-all">Show all <i class="fas fa-chevron-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion gfam-filter-section" id="mainAccordion3">
                    <!-- Main Toggle: Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="mainHeading">
                            <button class="accordion-button gfam-filter-header-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainCollapse3">
                                <span>Price Range</span>
                            </button>
                        </h2>

                        <!-- Collapsible Body (all filter content inside here) -->
                        <div id="mainCollapse3" class="accordion-collapse collapse" data-bs-parent="#mainAccordion3">
                            <div class="accordion-body p-0">
                                <div class="gfam-price-range d-flex align-items-center justify-content-center mb-3 gap-2 px-3">
                                    <div class="input-group gfam-price-input">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" placeholder="0" />
                                    </div>
                                    <span>-</span>
                                    <div class="input-group gfam-price-input">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" placeholder="0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion gfam-filter-section" id="mainAccordion4">
                    <!-- Main Toggle: Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="mainHeading">
                            <button class="accordion-button gfam-filter-header-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainCollapse4">
                                <span>Year</span>
                            </button>
                        </h2>

                        <!-- Collapsible Body (all filter content inside here) -->
                        <div id="mainCollapse4" class="accordion-collapse collapse" data-bs-parent="#mainAccordion4">
                            <div class="accordion-body p-0">
                                <!-- Nested Accordion for Categories -->
                                <?php echo do_shortcode('[rsl_year_options]');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion gfam-filter-section" id="mainAccordion5">
                    <!-- Main Toggle: Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="mainHeading">
                            <button class="accordion-button gfam-filter-header-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainCollapse5">
                                <span>Hours</span>
                            </button>
                        </h2>

                        <!-- Collapsible Body (all filter content inside here) -->
                        <div id="mainCollapse5" class="accordion-collapse collapse" data-bs-parent="#mainAccordion5">
                            <div class="accordion-body p-0">
                                <div class="gfam-price-range d-flex align-items-center justify-content-center mb-3 gap-2 px-3">
                                    <div class="input-group gfam-price-input">
                                        <input type="number" class="form-control" placeholder="0" />
                                    </div>
                                    <span>-</span>
                                    <div class="input-group gfam-price-input">
                                        <input type="number" class="form-control" placeholder="0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 gfam-btn-fixed">
                    <button class="gfam-btn w-100">Show Available Stock</button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="gfam-content col-xl-8">
            <div class="gfam-applied-filters my-3">
                <strong class="me-2">Applied filters:</strong>

                <div class="d-inline-flex flex-wrap gap-2">
                    <span class="gfam-filter-tag">Filter 1 ×</span>
                    <span class="gfam-filter-tag">Filter 2 ×</span>
                    <span class="gfam-filter-tag">Filter 3 ×</span>
                </div>

                <hr class="mt-3 mb-0" />
            </div>
            <!-- Content Header -->
            <div class="gfam-content-header mb-3">
                <div class="row align-items-center gfam-header-row justify-content-between">
                    <!-- Title -->
                    <div class="col-md-3 my-2">
                        <h5 class="gfam-title fw-bold mb-0">Farm Trailers For Sale</h5>
                    </div>

                    <!-- Search Bar -->
                    <div class="col-md-4 my-2 d-none d-xl-block">
                        <div class="gfam-searchbar d-flex align-items-center px-3 py-2 rounded">
                            <input type="text" class="form-control border-0 shadow-none bg-transparent gfam-search-input" placeholder="Search" />
                            <button class="gfam-search-btn border-0 rounded-circle d-flex justify-content-center align-items-center">
                                <i class="fas fa-search text-dark"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="col-md-auto my-2 d-flex justify-content-end d-none d-xl-block" id="sortDropdownContainer">
                        <div class="gfam-sort d-flex align-items-center">
                            <span class="me-1 d-none d-xl-block">Sort by:</span>
                            <div class="dropdown">
                                <button class="bg-transparent border-0 dropdown-toggle gfam-sort-btn d-none d-xl-block" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Relevancy
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="d-block d-xl-none">
                                        <a class="dropdown-item" href="#">Relevancy</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Price (Low - High)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Price (High - Low)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Make / Model (A - Z)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Make / Model (Z - A)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Year (Youngest - Oldest)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Year (Oldest - Youngest)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">KMs (Low - High)</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">KMs (High - Low)</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Newest</a></li>
                                    <li><a class="dropdown-item" href="#">Oldest</a></li>
                                    <li>
                                        <a class="dropdown-item" href="#">Latest Update</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>