jQuery(document).ready(function ($) {
  reinitCarousel();    

  const $original = $("#sortDropdownContainer");
  const $modalTarget = $("#sortDropdownModalContainer");

  if ($(window).width() < 1199.98) {
    $modalTarget.html($original.html());
  }

  // Handle category popup link click
  $(document).on("click", ".sidebar-modal .accordion-button .category-popup-link", function (e) {
    e.preventDefault();

    const $categorypopuplink = $(this);
    const $modal = $categorypopuplink.closest(".modal");

    // Collapse all accordion buttons
    $modal.find(".accordion-button").addClass("collapsed");

    // Expand the current accordion
    $categorypopuplink.closest(".accordion-button").removeClass("collapsed");

    // Remove active classes
    $modal.find(".category-body").removeClass("active");
    $modal.find(".subcategory-body").removeClass("active");

    // Activate targeted section
    const targetSelector = $categorypopuplink.closest(".category-link").attr("data-target");
    const $target = $modal.find(targetSelector);
    if ($target.length) $target.addClass("active");
  });

  // Handle back button clicks
  $(document).on("click", ".back-btn", function () {
    const $modal = $(this).closest(".modal");
    $modal.find(".subcategory-body").removeClass("active");
    $modal.find(".category-body").addClass("active");
  });

  // Switch Category Modal for mobile
  function switchCategoryModal() {
    if ($(window).width() > 1199.98) return;

    const modalPairs = [
      ["#popupCategoryDesktop", "#gfampopupCategoryMobile .modal-content"],
      ["#popupMakeDesktop", "#gfampopupMakeMobile .modal-content"],
      ["#popupTypeDesktop", "#gfampopupTypeMobile .modal-content"],
      ["#popupRangeDesktop", "#gfampopupRangeMobile .modal-content"],
      ["#popupYearDesktop", "#gfampopupYearMobile .modal-content"],
      ["#popupHourDesktop", "#gfampopupHourMobile .modal-content"],
    ];

    modalPairs.forEach(([desktopSel, mobileSel]) => {
      const $desktop = $(desktopSel);
      const $mobile = $(mobileSel);

      if ($desktop.length && $mobile.length && $.trim($desktop.html()) !== "") {
        $mobile.html($desktop.html());
        $desktop.empty();
      }
    });
  }

  switchCategoryModal();
  $(window).on("resize", switchCategoryModal);

  // Handle sub-modals show/hide behavior
  if ($(window).width() <= 1199.98) {
    const subModals = [
      "#gfampopupCategoryMobile",
      "#gfampopupMakeMobile",
      "#gfampopupTypeMobile",
      "#gfampopupRangeMobile",
      "#gfampopupYearMobile",
      "#gfampopupHourMobile",
    ];

    subModals.forEach((id) => {
      const $subModalEl = $(id);
      if ($subModalEl.length) {
        $subModalEl.on("show.bs.modal", function () {
          const $parentModal = $("#mobileFilterModal");
          if ($parentModal.length) {
            bootstrap.Modal.getInstance($parentModal[0])?.hide();
          }
        });

        $subModalEl.on("hidden.bs.modal", function () {
          const $parentModal = $("#mobileFilterModal");
          if ($parentModal.length) {
            bootstrap.Modal.getOrCreateInstance($parentModal[0]).show();
          }
        });
      }
    });
  }

  // $('.gfam-detail-comments-content').each(function(){
  //   let content = $(this).find('.readmore-text');
  //   let button = $(this).find('.readmore-toggle');
  //   let fullHeight = content.prop('scrollHeight');
  //   let collapsed = true;

  //   if(fullHeight <= 80){ button.hide(); return; }

  //   button.on('click', function(e){
  //     e.preventDefault();
  //     content.animate({ maxHeight: collapsed ? fullHeight : 80 }, 400);
  //     $(this).text(collapsed ? 'Show less' : 'Show more');
  //     collapsed = !collapsed;
  //   });
  // });

  // 
  const COLLAPSED_HEIGHT = 80;

  // unified handler for each wrapper
  $('.gfam-detail-comments-content').each(function() {
    const $wrapper = $(this);

    // find the content paragraph (your markup uses .readmore-text)
    const $content = $wrapper.find('.readmore-text').first();
    if (!$content.length) return;

    // ensure only one toggle exists (either .toggle-btn or .gfam-show-toggle)
    let $toggle = $wrapper.find('.toggle-btn, .gfam-show-toggle').first();
    if (!$toggle.length) {
      $toggle = $('<button type="button" class="gfam-show-toggle d-block mt-3">Show more</button>');
      $wrapper.append($toggle);
    }

    // function to setup/hide toggle after layout is settled
    function setupToggle() {
      // force a reflow to get an accurate height
      const fullHeight = $content[0].scrollHeight;

      // if content is short, hide toggle and reset styles
      if (fullHeight <= COLLAPSED_HEIGHT + 1) {
        $toggle.hide();
        $content.css({
          'max-height': '',
          'overflow': '',
          'transition': ''
        });
        $content.removeClass('expanded');
        return;
      }

      // show toggle and set initial collapsed state
      $toggle.show();
      if (!$content.hasClass('expanded')) {
        $content.css({
          'overflow': 'hidden',
          'max-height': COLLAPSED_HEIGHT + 'px',
          'transition': 'max-height 0.35s ease'
        });
        $toggle.text('Show more');
      } else {
        // if already expanded (rare on load), ensure full height
        $content.css('max-height', fullHeight + 'px');
        $toggle.text('Show less');
      }
    }

    // run setup on next animation frame (ensures scrollHeight is correct)
    requestAnimationFrame(setupToggle);

    // also run setup again after images/fonts load (in case description contains images)
    $(window).on('load', function() {
      requestAnimationFrame(setupToggle);
    });

    // click handler
    $toggle.off('click.gfamReadmore').on('click.gfamReadmore', function(e) {
      e.preventDefault();
      const isExpanded = $content.hasClass('expanded');

      if (isExpanded) {
        // collapse
        $content.removeClass('expanded');
        $content.css('max-height', COLLAPSED_HEIGHT + 'px');
        $toggle.text('Show more');
      } else {
        // expand to full scrollHeight
        const fullHeight = $content[0].scrollHeight;
        $content.addClass('expanded');
        $content.css('max-height', fullHeight + 'px');
        $toggle.text('Show less');
      }
    });

    // If content changes dynamically later, you can re-run setupToggle(); 
    // (e.g. after AJAX update)
  });
  // 
});

function reinitSeeMoreLess(){
  jQuery(".gfam-filter-wrapper").each(function () {
    const $wrapper = jQuery(this);
    const $tags = $wrapper.find(".gfam-filter-tags");
    
    if($tags.find(".gfam-filter-tag").length !== 0){
      $wrapper.css({
          transition: "all 0.4s ease",
          overflow: "hidden",
          height: 'auto',
        });
    }else{
      $wrapper.css({
          transition: "all 0.4s ease",
          overflow: "hidden",
          height: 0,
        });
    }
            
    let $toggleBtn = $wrapper.find(".gfam-show-toggle");
    if ($toggleBtn.length === 0) {
      $toggleBtn = jQuery('<button class="gfam-show-toggle">Show More</button>');
      $wrapper.append($toggleBtn);
    }

    // Expand/Collapse functionality
    $toggleBtn.on("click", function (e) {            
      if ($tags.hasClass("expanded")) {                
        $tags.css("max-height", "5.5em").removeClass("expanded");
        $toggleBtn.text("Show More");
      } else {                
        $tags.css("max-height", $tags[0].scrollHeight + "px").addClass("expanded");
        $toggleBtn.text("Show Less");
      }
    });

    const maxHeight = parseFloat($tags.css("max-height"));    
    if ($tags[0].scrollHeight <= maxHeight + 1) {
      $toggleBtn.hide();
    } else {
      $toggleBtn.show();
    }

    // Handle tag remove
    $tags.on("click", ".gfam-clear-tag", function () {
      const $tagItem = jQuery(this).closest(".gfam-filter-tag");
      $tagItem.remove();

      if ($tags[0].scrollHeight <= maxHeight + 1) {
        $toggleBtn.css({ visibility: "hidden", height: 0 });
      }

      if ($tags.find(".gfam-filter-tag").length === 0) {
        $wrapper.css({
          transition: "all 0.4s ease",
          overflow: "hidden",
          height: 0,
        });
      }
    });
  });
}

function reinitCarousel(){
    jQuery(".gfam-carousel").owlCarousel({
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
        0: { items: 1 },
        600: { items: 1 },
        1000: { items: 1 },
      },
    });
  }