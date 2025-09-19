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

  const original = document.getElementById("sortDropdownContainer");
  const modalTarget = document.getElementById("sortDropdownModalContainer");
  if (window.innerWidth < 1199.98) {
    const desktopSidebar = document.getElementById("gfamSidebarDesktop");
    const mobileSidebar = document.getElementById("gfamSidebarMobile");
    if (mobileSidebar && desktopSidebar) {
      mobileSidebar.innerHTML = desktopSidebar.innerHTML;
    }
    if (modalTarget && original) {
      modalTarget.innerHTML = original.innerHTML;
    }
  }
});
