<?php

if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/screenshot.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/screenshot.png';
    if ( file_exists( $screenshot_file ) ) {
        echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
        return;
    }
}

  
$rm_bp = get_field( 'rm_bp' );
$title = get_field( 'title' ) ?: '';
$colors = get_field( 'colors' ) ?: array();
$bg_color = $colors['bg_color'] ?? 'white';
$title_color = $colors['title_color'] ?? 'dark';
$images = get_field( 'images' ) ?: array();

$section_classes = 'blueprint-' . basename( __DIR__ ) . ' ' . 'block__' . basename( __DIR__ );
$section_classes .= ' bg-' . $bg_color;
$section_classes .= ' text-' . $title_color;
if ( $rm_bp === true ) $section_classes .= ' pb-0';
?>

<section class="<?= esc_attr( $section_classes ); ?>">
  <div class="container">
    <?php if ( ! empty( $images ) ) : ?>
      <div class="image-slider-header flex justify-between flex-wrap gap-[20px] align-items-center mb-[30px]">
        <div>
            <?php if ( $title ) : ?>
            <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('title'); ?> class="image-slider-title text-<?php echo esc_attr( $title_color ); ?>"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        <div class="image-slider-navigation flex gap-[10px]">
          <div class="image-slider-btn-prev carousel-btn" aria-label="<?php esc_attr_e( 'Vorige afbeelding', 'blueprint' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(160 -800) rotate(180)" fill="currentColor"></path>
            </svg>
          </div>
          <div class="image-slider-btn-next carousel-btn" aria-label="<?php esc_attr_e( 'Volgende afbeelding', 'blueprint' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(-120 840)" fill="currentColor"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="swiper image-slider-swiper" role="region" aria-label="<?php esc_attr_e( 'Afbeeldingen slider', 'blueprint' ); ?>">
        <div class="swiper-wrapper">
          <?php foreach ( $images as $slide ) :
            $image_id = $slide['image'] ?? null;
          ?>
            <div class="swiper-slide">
              <?php if ( $image_id ) : ?>
                <div class="image-slide-wrapper">
                  <?php echo wp_get_attachment_image( $image_id, 'full', false, array( 'loading' => 'lazy' ) ); ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
(function() {
  function initImageSlider() {
    var sliderEl = document.querySelector('.<?php echo 'block__' . basename( __DIR__ ); ?>  .image-slider-swiper');
    if (!sliderEl) return;
    
    // Destroy existing instance if any
    if (sliderEl.swiper) {
      sliderEl.swiper.destroy(true, true);
    }
    
    var container = sliderEl.closest('.<?php echo 'block__' . basename( __DIR__ ); ?> ');
    var slides = sliderEl.querySelectorAll('.swiper-slide');
    var slideCount = slides.length;

    new Swiper(sliderEl, {
      slidesPerView: 1,
      spaceBetween: 0,
      effect: 'slide',
      speed: 600,
      navigation: {
        nextEl: container.querySelector('.image-slider-btn-next'),
        prevEl: container.querySelector('.image-slider-btn-prev'),
      },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      }
    });
  }

  // Init on load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initImageSlider);
  } else {
    initImageSlider();
  }

  // Re-init for block editor
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=image-slider', initImageSlider);
  }
})();
</script>
