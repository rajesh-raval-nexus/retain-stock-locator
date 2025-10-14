<?php 

include RSL_PLUGIN_DIR .'templates/vdp-listing/breadcrumb.php';

?>

<div class="mobile-header d-xl-none">
  <div class="gfam-searchbar d-flex align-items-center px-3 py-2 rounded">
    <input type="text" class="form-control border-0 shadow-none bg-transparent gfam-search-input"
      placeholder="Search" />
    <button class="gfam-search-btn border-0 rounded-circle d-flex justify-content-center align-items-center">
      <i class="fas fa-search text-dark"></i>
    </button>
  </div>
  <div class="row text-center justify-content-center p-3">
    <div class="col-6" style="border-right: 1px solid #e0e0e0">
      <button class="modal-btn bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal"
        data-bs-target="#mobileFilterModal">
        <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M7.2503 3.74988C7.05139 3.74988 6.86063 3.8289 6.71997 3.96955C6.57932 4.11021 6.5003 4.30097 6.5003 4.49988C6.5003 4.6988 6.57932 4.88956 6.71997 5.03021C6.86063 5.17087 7.05139 5.24988 7.2503 5.24988C7.44922 5.24988 7.63998 5.17087 7.78063 5.03021C7.92129 4.88956 8.0003 4.6988 8.0003 4.49988C8.0003 4.30097 7.92129 4.11021 7.78063 3.96955C7.63998 3.8289 7.44922 3.74988 7.2503 3.74988ZM5.12781 3.74988C5.28276 3.31073 5.57011 2.93046 5.95025 2.66148C6.3304 2.39249 6.78462 2.24805 7.2503 2.24805C7.71599 2.24805 8.17021 2.39249 8.55035 2.66148C8.9305 2.93046 9.21785 3.31073 9.3728 3.74988H14.7503C14.9492 3.74988 15.14 3.8289 15.2806 3.96955C15.4213 4.11021 15.5003 4.30097 15.5003 4.49988C15.5003 4.6988 15.4213 4.88956 15.2806 5.03021C15.14 5.17087 14.9492 5.24988 14.7503 5.24988H9.3728C9.21785 5.68903 8.9305 6.06931 8.55035 6.33829C8.17021 6.60728 7.71599 6.75172 7.2503 6.75172C6.78462 6.75172 6.3304 6.60728 5.95025 6.33829C5.57011 6.06931 5.28276 5.68903 5.12781 5.24988H4.25031C4.05139 5.24988 3.86063 5.17087 3.71998 5.03021C3.57932 4.88956 3.50031 4.6988 3.50031 4.49988C3.50031 4.30097 3.57932 4.11021 3.71998 3.96955C3.86063 3.8289 4.05139 3.74988 4.25031 3.74988H5.12781ZM11.7503 8.24989C11.5514 8.24989 11.3606 8.3289 11.22 8.46955C11.0793 8.61021 11.0003 8.80097 11.0003 8.99989C11.0003 9.1988 11.0793 9.38956 11.22 9.53021C11.3606 9.67087 11.5514 9.74989 11.7503 9.74989C11.9492 9.74989 12.14 9.67087 12.2806 9.53021C12.4213 9.38956 12.5003 9.1988 12.5003 8.99989C12.5003 8.80097 12.4213 8.61021 12.2806 8.46955C12.14 8.3289 11.9492 8.24989 11.7503 8.24989ZM9.6278 8.24989C9.78276 7.81074 10.0701 7.43046 10.4503 7.16148C10.8304 6.89249 11.2846 6.74805 11.7503 6.74805C12.216 6.74805 12.6702 6.89249 13.0504 7.16148C13.4305 7.43046 13.7179 7.81074 13.8728 8.24989H14.7503C14.9492 8.24989 15.14 8.3289 15.2806 8.46955C15.4213 8.61021 15.5003 8.80097 15.5003 8.99989C15.5003 9.1988 15.4213 9.38956 15.2806 9.53021C15.14 9.67087 14.9492 9.74989 14.7503 9.74989H13.8728C13.7179 10.189 13.4305 10.5693 13.0504 10.8383C12.6702 11.1073 12.216 11.2517 11.7503 11.2517C11.2846 11.2517 10.8304 11.1073 10.4503 10.8383C10.0701 10.5693 9.78276 10.189 9.6278 9.74989H4.25031C4.05139 9.74989 3.86063 9.67087 3.71998 9.53021C3.57932 9.38956 3.50031 9.1988 3.50031 8.99989C3.50031 8.80097 3.57932 8.61021 3.71998 8.46955C3.86063 8.3289 4.05139 8.24989 4.25031 8.24989H9.6278ZM7.2503 12.7499C7.05139 12.7499 6.86063 12.8289 6.71997 12.9696C6.57932 13.1102 6.5003 13.301 6.5003 13.4999C6.5003 13.6988 6.57932 13.8896 6.71997 14.0302C6.86063 14.1709 7.05139 14.2499 7.2503 14.2499C7.44922 14.2499 7.63998 14.1709 7.78063 14.0302C7.92129 13.8896 8.0003 13.6988 8.0003 13.4999C8.0003 13.301 7.92129 13.1102 7.78063 12.9696C7.63998 12.8289 7.44922 12.7499 7.2503 12.7499ZM5.12781 12.7499C5.28276 12.3107 5.57011 11.9305 5.95025 11.6615C6.3304 11.3925 6.78462 11.248 7.2503 11.248C7.71599 11.248 8.17021 11.3925 8.55035 11.6615C8.9305 11.9305 9.21785 12.3107 9.3728 12.7499H14.7503C14.9492 12.7499 15.14 12.8289 15.2806 12.9696C15.4213 13.1102 15.5003 13.301 15.5003 13.4999C15.5003 13.6988 15.4213 13.8896 15.2806 14.0302C15.14 14.1709 14.9492 14.2499 14.7503 14.2499H9.3728C9.21785 14.689 8.9305 15.0693 8.55035 15.3383C8.17021 15.6073 7.71599 15.7517 7.2503 15.7517C6.78462 15.7517 6.3304 15.6073 5.95025 15.3383C5.57011 15.0693 5.28276 14.689 5.12781 14.2499H4.25031C4.05139 14.2499 3.86063 14.1709 3.71998 14.0302C3.57932 13.8896 3.50031 13.6988 3.50031 13.4999C3.50031 13.301 3.57932 13.1102 3.71998 12.9696C3.86063 12.8289 4.05139 12.7499 4.25031 12.7499H5.12781Z"
            fill="#444343" />
        </svg>
        Filter
      </button>
    </div>
    <div class="col-6">
      <button class="modal-btn bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal"
        data-bs-target="#mobileSortModal">
        <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.5 15V7.5M6.5 15L4.25 12.75M6.5 15L8.75 12.75M12.5 3V10.5M12.5 3L14.75 5.25M12.5 3L10.25 5.25"
            stroke="#444343" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
      <div class="modal-header gfam-filter-header">
        <span class="gfam-clear-all"></span>
        <span>Filter</span>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body p-0">
        <!-- Filter Content -->
        <div class="gfam-filter-content gfam-filter-section">
          <!-- Search -->
          <div class="input-group gfam-search-section">
            <input type="text" class="gfam-search-input" placeholder="Quick Search" aria-label="Quick Search" />
            <div class="input-group-append">
              <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
          </div>

          <ul class="gfam-filter-list">
            <li class="d-block w-100">
              <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
                data-bs-target="#gfampopupCategoryMobile">
                <span>Category</span>
                <button class="gfam-arrow">
                  <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                      fill="black" />
                  </svg>
                </button>
              </div>
              <!-- Selected Filters -->
              <div class="gfam-filter-wrapper">
                <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3 selected-category-options-list">
                  <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>
                </div>
              </div>
            </li>
            <li class="d-block w-100">
              <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
                data-bs-target="#gfampopupMakeMobile">
                <span>Make & Model</span>
                <button class="gfam-arrow">
                  <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                      fill="black" />
                  </svg>
                </button>
              </div>
              <!-- Selected Filters -->
              <div class="gfam-filter-wrapper">
                <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3">
                  <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>

                </div>
              </div>
            </li>
            <li class="d-block w-100">
              <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
                data-bs-target="#gfampopupTypeMobile">
                <span>Type</span>
                <button class="gfam-arrow">
                  <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                      fill="black" />
                  </svg>
                </button>
              </div>
              <!-- Selected Filters -->
              <div class="gfam-filter-wrapper">
                <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3">
                  <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
                  <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>

                </div>
              </div>
            </li>
            <li data-bs-toggle="modal" data-bs-target="#gfampopupRangeMobile">
              <span>Price Range</span>
              <button class="gfam-arrow">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                    fill="black" />
                </svg>
              </button>
            </li>
            <li data-bs-toggle="modal" data-bs-target="#gfampopupYearMobile">
              <span>Year</span>
              <button class="gfam-arrow">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                    fill="black" />
                </svg>
              </button>
            </li>
            <li data-bs-toggle="modal" data-bs-target="#gfampopupHourMobile">
              <span>Hours</span>
              <button class="gfam-arrow">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                    fill="black" />
                </svg>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Mobile category Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupCategoryMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">

      <div id="popupCategoryMobile"></div>
      <!-- Footer -->
      <div class="modal-footer">
        <div class="gfam-btn-fixed row w-100 align-items-center">
          <div class="col-6">
            <a href="#" class="clear-btn">Clear</a>
          </div>
          <div class="col-6 text-end">
            <button class="gfam-btn w-auto">Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Mobile make Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupMakeMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">
      <div id="popupMakeMobile"></div>

    </div>
  </div>
</div>
<!-- Mobile Type Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupTypeMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">
      <div id="popupTypeMobile"></div>

    </div>
  </div>
</div>
<!-- Mobile Range Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupRangeMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">
      <div id="popupRangeMobile"></div>

    </div>
  </div>
</div>
<!-- Mobile Year Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupYearMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">
      <div id="popupYearMobile"></div>

    </div>
  </div>
</div>
<!-- Mobile Hour Modal -->
<div class="modal fade modal-slide-up sidebar-modal" id="gfampopupHourMobile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">

    <div class="modal-content">
      <div id="popupHourMobile"></div>

    </div>
  </div>
</div>
<!-- Sort Modal (Mobile Only) -->
<div class="modal fade modal-slide-up" id="mobileSortModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header gfam-filter-header">
        <span class="">&nbsp;</span>
        <span>Sort By</span>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

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
      <span>Filter</span>
      <!-- <span class="gfam-clear-all">Clear All</span> -->
    </div>

    <!-- Filter Content -->
    <div class="gfam-filter-content gfam-filter-section">
      <!-- Search -->
      <div class="input-group gfam-search-section">
        <input type="text" class="gfam-search-input" placeholder="Quick Search" aria-label="Quick Search" />
        <div class="input-group-append">
          <span class="input-group-text"><i class="fa fa-search"></i></span>
        </div>
      </div>
      <ul class="gfam-filter-list">
        <li class="d-block w-100">
          <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
            data-bs-target="#popupCategoryDesktop">
            <span>Category</span>
            <button class="gfam-arrow">
              <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                  fill="black" />
              </svg>
            </button>
          </div>
          <!-- Selected Filters -->
          <div class="gfam-filter-wrapper">
            <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3">
              <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>

            </div>
          </div>              
        </li>
        <li class="d-block w-100">
          <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
            data-bs-target="#popupMakeDesktop">
            <span>Make & Model</span>
            <button class="gfam-arrow">
              <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                  fill="black" />
              </svg>
            </button>
          </div>
          <!-- Selected Filters -->
          <div class="gfam-filter-wrapper">
            <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3">
              <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>

            </div>
          </div>
        </li>
        <li class="d-block w-100">
          <div class="d-flex w-100 justify-content-between" data-bs-toggle="modal"
            data-bs-target="#popupTypeDesktop">
            <span>Type</span>
            <button class="gfam-arrow">
              <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                  fill="black" />
              </svg>
            </button>
          </div>
          <!-- Selected Filters -->
          <div class="gfam-filter-wrapper">
            <div class="gfam-filter-tags d-inline-flex flex-wrap gap-2 mt-3">
              <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 4 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 5 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 6 <span class="gfam-clear-tag">×</span></span>
              <span class="gfam-filter-tag">Filter 7 <span class="gfam-clear-tag">×</span></span>

            </div>
          </div>
        </li>
        <li data-bs-toggle="modal" data-bs-target="#popupRangeDesktop">
          <span>Price Range</span>
          <button class="gfam-arrow">
            <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                fill="black" />
            </svg>
          </button>
        </li>
        <li data-bs-toggle="modal" data-bs-target="#popupYearDesktop">
          <span>Year</span>
          <button class="gfam-arrow">
            <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                fill="black" />
            </svg>
          </button>
        </li>
        <li data-bs-toggle="modal" data-bs-target="#popupHourDesktop">
          <span>Hours</span>
          <button class="gfam-arrow">
            <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9.05989 6.9401C9.34079 7.22135 9.49857 7.6026 9.49857 8.0001C9.49857 8.3976 9.34079 8.77885 9.05989 9.0601L3.40389 14.7181C3.12249 14.9994 2.74089 15.1573 2.34304 15.1572C1.94518 15.1571 1.56365 14.999 1.28239 14.7176C1.00113 14.4362 0.843168 14.0546 0.843262 13.6567C0.843355 13.2589 1.00149 12.8774 1.28289 12.5961L5.87889 8.0001L1.28289 3.4041C1.00951 3.12132 0.858142 2.74249 0.861373 2.34919C0.864604 1.9559 1.02218 1.5796 1.30016 1.30136C1.57814 1.02311 1.95429 0.86518 2.34759 0.861578C2.74088 0.857976 3.11986 1.00899 3.40289 1.2821L9.06089 6.9391L9.05989 6.9401Z"
                fill="black" />
            </svg>
          </button>
        </li>
      </ul>
    </div>

  </div>

  <!-- Main Content -->
  <div class="gfam-content col-xl-8">
    <!-- <div class="gfam-applied-filters my-3">
    <strong class="me-2">Applied filters:</strong>

    <div class="d-inline-flex flex-wrap gap-2">
      <span class="gfam-filter-tag">Filter 1 <span class="gfam-clear-tag">×</span></span>
      <span class="gfam-filter-tag">Filter 2 <span class="gfam-clear-tag">×</span></span>
      <span class="gfam-filter-tag">Filter 3 <span class="gfam-clear-tag">×</span></span>
    </div>

    <hr class="mt-3 mb-0" />
  </div> -->
    <!-- Content Header -->
    <div class="gfam-content-header mb-3">
      <div class="row align-items-center gfam-header-row justify-content-between">
        <!-- Title -->
        <div class="col-md-3 my-2">
          <h1 class="gfam-title fw-bold mb-0">Farm Trailers For Sale</h1>
        </div>

        <!-- Search Bar -->
        <div class="col-md-4 my-2 d-none d-xl-block">
          <div class="gfam-searchbar d-flex align-items-center ps-0 pe-3 py-2 rounded">
            <input type="text" class="form-control border-0 shadow-none bg-transparent gfam-search-input"
              placeholder="Search" />
            <button
              class="gfam-search-btn border-0 rounded-circle d-flex justify-content-center align-items-center">
              <i class="fas fa-search text-dark"></i>
            </button>
          </div>
        </div>

        <!-- Sort Dropdown -->
        <div class="col-md-auto my-2 d-flex justify-content-end d-none d-xl-block" id="sortDropdownContainer">
          <div class="gfam-sort d-flex align-items-center">
            <span class="me-1  d-none d-xl-block">Sort by:</span>
            <div class="dropdown">
              <button class="bg-transparent border-0 dropdown-toggle gfam-sort-btn  d-none d-xl-block" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Relevancy
              </button>
              <ul class="dropdown-menu">
                <li class=" d-block d-xl-none">
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