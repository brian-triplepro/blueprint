document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.usps').forEach(function (uspsEl) {
    var slides = uspsEl.querySelectorAll('.swiper-slide');
    var slideCount = slides ? slides.length : 0;

    var options = {
      slidesPerView: 1,
      spaceBetween: 20,
      autoplay:  {
        delay: 5000,
        disableOnInteraction: false,
      },
      loop: true,
      breakpoints: {
        640:  { slidesPerView: Math.min( 2, slideCount, 4 ) },
        1024: { slidesPerView: Math.min( 3, slideCount, 4 ) },
        1440: { slidesPerView: Math.min( 4, slideCount, 4 ) },
      }
    };

    new Swiper(uspsEl, options);
  });

});