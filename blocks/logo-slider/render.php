<?php

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
      echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
      return;
    }
  }

  $title    = get_field( 'title' ) ?: '';
  $rm_bp    = get_field( 'rm_pb' );
  $colors   = get_field( 'colors' ) ?: array();
  $bg_color    = $colors['bg_color'] ?? 'background';
  $title_color = $colors['title_color'] ?? 'dark';

  $items           = get_field( 'logoslider', 'option' ) ?: array();
  $slides_per_view = intval( get_field( 'slides_per_view' ) ?: 6 );

  $section_classes = 'blueprint-' . basename( __DIR__ );
  $section_classes .= ' bg-' . $bg_color;
  if ( $rm_bp === true ) $section_classes .= ' pb-0';

?>

<section class="<?= esc_attr( $section_classes ); ?>">
  <div class="container">

    <?php if ( $title ) : ?>
      <h2 class="text-2xl leading-tight !mb-[40px] text-center text-<?php echo esc_attr( $title_color ); ?>"><?php echo wp_kses_post( $title ); ?></h2>
    <?php endif; ?>

    <?php if ( ! empty( $items ) ) : ?>
      <div class="swiper logo-slider">
        <div class="swiper-wrapper items-center">
          <?php foreach ( [ ...$items, ...$items ] as $item ) :
            if ( $item === null ) : ?>
              <div class="swiper-slide flex items-center justify-center" aria-hidden="true"></div>
            <?php continue; endif;
            $logo = $item['logo'] ?? null;
            $link = $item['link'] ?? null;
            $img_id = is_array( $logo ) ? ( $logo['id'] ?? null ) : null;
            if ( ! $img_id ) continue;
            $link_url    = is_array( $link ) ? ( $link['url'] ?? '' ) : '';
            $link_target = is_array( $link ) ? ( $link['target'] ?? '' ) : '';
            $link_title  = is_array( $link ) ? ( $link['title'] ?? '' ) : '';
          ?>
            <div class="swiper-slide flex items-center justify-center">
              <?php if ( $link_url ) : ?>
                <a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target ? ' target="' . esc_attr( $link_target ) . '" rel="noopener"' : ''; ?><?php echo $link_title ? ' aria-label="' . esc_attr( $link_title ) . '"' : ''; ?> class="logo-link flex items-center justify-center">
                  <?php echo wp_get_attachment_image( $img_id, 'medium', false, array( 'class' => 'logo-img max-h-[150px] w-auto object-contain rounded-[0]', 'loading' => 'lazy' ) ); ?>
                </a>
              <?php else : ?>
                <?php echo wp_get_attachment_image( $img_id, 'medium', false, array( 'class' => 'logo-img max-h-[150px] w-auto object-contain rounded-[0]', 'loading' => 'lazy' ) ); ?>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php else : ?>
      <p class="text-center py-10 italic opacity-60">Er zijn nog geen logo's toegevoegd. Voeg logo's toe via Blueprint &rsaquo; Repeaters &rsaquo; Logoslider.</p>
    <?php endif; ?>

  </div>
</section>

<script>
(function() {
  function initLogoSlider() {
    var el = document.querySelector('.<?php echo 'blueprint-' . basename( __DIR__ ); ?> .logo-slider');
    if (!el) return;
    if (el.swiper) el.swiper.destroy(true, true);

    new Swiper(el, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      speed: 5000,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
      },
      breakpoints: {
        640: { slidesPerView: Math.min(3, <?php echo intval( $slides_per_view ); ?>), spaceBetween: 30 },
        1024: { slidesPerView: <?php echo intval( $slides_per_view ); ?>, spaceBetween: 40 },
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogoSlider);
  } else {
    initLogoSlider();
  }

  if (window.acf) {
    window.acf.addAction('render_block_preview/type=logo-slider', initLogoSlider);
  }
})();
</script>
