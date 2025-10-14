jQuery(document).ready(function ($) {
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
      '<i class="fas fa-chevron-left"></i>',
      '<i class="fas fa-chevron-right"></i>',
    ],
    responsive: {
      0: { items: 1 },
      600: { items: 1 },
      1000: { items: 1 },
    },
  });

  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    $(".gfam-sort-btn").text($(this).text());
  });

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

  // Filter wrapper logic
  $(".gfam-filter-wrapper").each(function () {
    const $wrapper = $(this);
    const $tags = $wrapper.find(".gfam-filter-tags");

    const $toggleBtn = $('<button class="gfam-show-toggle">Show More</button>');
    $wrapper.append($toggleBtn);

    // Expand/Collapse functionality
    $toggleBtn.on("click", function () {
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
      const $tagItem = $(this).closest(".gfam-filter-tag");
      $tagItem.remove();

      if ($tags[0].scrollHeight <= maxHeight + 1) {
        $toggleBtn.css({ visibility: "hidden", height: 0 });
      }

      if ($tags.find(".gfam-filter-tag").length === 0) {
        $wrapper.css({
          transition: "all 0.4s ease",
          overflow: "hidden",
          height: 0,
          padding: 0,
          margin: 0,
        });
      }
    });
  });

  
});
