jQuery(document).ready(function ($) {
  $(".price-input input").each(function () {
    const $priceInput = $(this).closest(".price-input");
    const $rangeInput = $priceInput.siblings(".range-input").find("input");
    const $range = $priceInput.siblings(".slider").find(".progress");
    let priceGap = parseInt($rangeInput.eq(0).attr("step")); // Gap based on step

    // Initialize slider position based on default values
    function initializeSlider() {
      let minValue = parseInt($priceInput.find(".input-min").val());
      let maxValue = parseInt($priceInput.find(".input-max").val());

      $rangeInput.eq(0).val(minValue);
      $rangeInput.eq(1).val(maxValue);
      $range.css(
        "left",
        ((minValue - $rangeInput.eq(0).attr("min")) /
          ($rangeInput.eq(0).attr("max") - $rangeInput.eq(0).attr("min"))) *
          100 +
          "%"
      );
      $range.css(
        "right",
        100 -
          ((maxValue - $rangeInput.eq(1).attr("min")) /
            ($rangeInput.eq(1).attr("max") - $rangeInput.eq(1).attr("min"))) *
            100 +
          "%"
      );
    }

    // Call the initialize function on page load
    initializeSlider();

    // Event listener for input change
    $priceInput.find("input").on("input", function () {
      let minValue = parseInt($priceInput.find(".input-min").val());
      let maxValue = parseInt($priceInput.find(".input-max").val());

      if (
        maxValue - minValue >= priceGap &&
        maxValue <= $rangeInput.eq(1).attr("max")
      ) {
        if ($(this).hasClass("input-min")) {
          $rangeInput.eq(0).val(minValue);
          $range.css(
            "left",
            ((minValue - $rangeInput.eq(0).attr("min")) /
              ($rangeInput.eq(0).attr("max") - $rangeInput.eq(0).attr("min"))) *
              100 +
              "%"
          );
        } else {
          $rangeInput.eq(1).val(maxValue);
          $range.css(
            "right",
            100 -
              ((maxValue - $rangeInput.eq(1).attr("min")) /
                ($rangeInput.eq(1).attr("max") -
                  $rangeInput.eq(1).attr("min"))) *
                100 +
              "%"
          );
        }
      }
    });

    // Event listener for slider input
    $rangeInput.on("input", function () {
      let minValue = parseInt($rangeInput.eq(0).val());
      let maxValue = parseInt($rangeInput.eq(1).val());

      if (maxValue - minValue < priceGap) {
        if ($(this).hasClass("range-min")) {
          $rangeInput.eq(0).val(maxValue - priceGap);
        } else {
          $rangeInput.eq(1).val(minValue + priceGap);
        }
      } else {
        $priceInput.find(".input-min").val(minValue);
        $priceInput.find(".input-max").val(maxValue);
        $range.css(
          "left",
          ((minValue - $rangeInput.eq(0).attr("min")) /
            ($rangeInput.eq(0).attr("max") - $rangeInput.eq(0).attr("min"))) *
            100 +
            "%"
        );
        $range.css(
          "right",
          100 -
            ((maxValue - $rangeInput.eq(1).attr("min")) /
              ($rangeInput.eq(1).attr("max") - $rangeInput.eq(1).attr("min"))) *
              100 +
            "%"
        );
      }
    });
  });

  // car list images slider
  $(".car-image").owlCarousel({
    loop: true,
    margin: 10,
    dots: false,
    navText: [
      "<i class='bi bi-chevron-left'></i>",
      "<i class='bi bi-chevron-right'></i>",
    ],
    infinite: true,
    responsiveClass: true,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,

    responsive: {
      0: {
        items: 1,
        nav: true,
      },
      600: {
        items: 3,
        nav: false,
      },
      1000: {
        items: 1,
        nav: true,
        loop: false,
      },
    },
  });
  var changeSlide = 2; // mobile -1, desktop + 1
  // Resize and refresh page. slider-two slideBy bug remove
  var slide = changeSlide;
  if ($(window).width() < 600) {
    var slide = changeSlide;
    slide--;
  } else if ($(window).width() > 999) {
    var slide = changeSlide;
    slide++;
  } else {
    var slide = changeSlide;
  }

    $(".one").owlCarousel({
      nav: true,
      items: 1,
      dots: true,
      autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
      navText: [
        "<i class='bi bi-arrow-left-short'></i>",
        "<i class='bi bi-arrow-right-short'></i>",
      ],
    });
    $(".two").owlCarousel({
      nav: true,
      margin: 15,
      autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    dots: false,
      navText: [
        "<i class='bi bi-arrow-left-short'></i>",
        "<i class='bi bi-arrow-right-short'></i>",
      ],
      mouseDrag: false,
      touchDrag: false,
      responsive: {
        0: {
          items: changeSlide + 1,
          slideBy: changeSlide + 1,
        },
        600: {
          items: changeSlide,
          slideBy: changeSlide,
        },
        1000: {
          items: changeSlide + 1,
          slideBy: changeSlide + 1,
        },
      },
    });
    var owl = $(".one");
    owl.owlCarousel();
    owl.on("translated.owl.carousel", function (event) {
      $(".right").removeClass("nonr");
      $(".left").removeClass("nonl");
      if ($(".one .owl-next").is(".disabled")) {
        $(".slider-main .right").addClass("nonr");
      }
      if ($(".one .owl-prev").is(".disabled")) {
        $(".slider-main .left").addClass("nonl");
      }
      $(".slider-two .item").removeClass("active");
      var c = $(".slider-main .owl-item.active").index();
      $(".slider-two .item").eq(c).addClass("active");
      var d = Math.ceil((c + 1) / slide) - 1;
      $(".slider-two .owl-dots .owl-dot").eq(d).trigger("click");
    });
    $(".right").click(function () {
      $(".slider-main .owl-next").trigger("click");
    });
    $(".left").click(function () {
      $(".slider-main .owl-prev").trigger("click");
    });
    $(".slider-two .item").click(function () {
      var b = $(".item").index(this);
      $(".slider-main .owl-dots .owl-dot").eq(b).trigger("click");
      $(".slider-two .item").removeClass("active");
      $(this).addClass("active");
    });
    var owl2 = $(".two");
    owl2.owlCarousel();
    owl2.on("translated.owl.carousel", function (event) {
      $(".right-t").removeClass("nonr-t");
      $(".left-t").removeClass("nonl-t");
      if ($(".two .owl-next").is(".disabled")) {
        $(".slider-two .right-t").addClass("nonr-t");
      }
      if ($(".two .owl-prev").is(".disabled")) {
        $(".slider-two .left-t").addClass("nonl-t");
      }
    });
    $(".right-t").click(function () {
      $(".slider-two .owl-next").trigger("click");
    });
    $(".left-t").click(function () {
      $(".slider-two .owl-prev").trigger("click");
    });

    
   
 

 
      let isExpanded = false;
  
      $('.show-all').click(function () {
        if (!isExpanded) {
          // Expand all collapse items
          $('#categoryAccordion .accordion-collapse').each(function () {
            let collapse = new bootstrap.Collapse(this, {
              toggle: false
            });
            collapse.show();
          });
          $(this).html('Show less <i class="fas fa-chevron-up"></i>');
        } else {
          // Collapse all
          $('#categoryAccordion .accordion-collapse').each(function () {
            let collapse = new bootstrap.Collapse(this, {
              toggle: false
            });
            collapse.hide();
          });
          $(this).html('Show all <i class="fas fa-chevron-down"></i>');
        }
  
        isExpanded = !isExpanded;
      });

});