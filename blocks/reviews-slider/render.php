<?php

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }

  $title = get_field( 'title' ) ?: '';
  $rm_bp = get_field( 'rm_bp' );
  $colors = get_field( 'colors' ) ?: array();
  $bg_color = $colors['bg_color'] ?? 'background';
  $title_color = $colors['title_color'] ?? 'dark';
  $text_color = $colors['text_color'] ?? 'dark';
  $card_color = $colors['block-color'] ?? 'secondary';


  $items = get_field( 'reviews', 'option' ) ?: array();


  $section_classes = 'blueprint-' . basename( __DIR__ );
  $section_classes .= ' bg-' . $bg_color;
  $section_classes .= ' text-' . $text_color;
  if ( $rm_bp === true ) $section_classes .= ' pb-0';

?>


<section class="<?= esc_attr( $section_classes ); ?>" style="--reviews-card-bg: var(--color-<?php echo esc_attr( $card_color ); ?>);">
  <div class="container mx-auto">
    <?php if ( ! empty( $items ) ) : ?>
      <div class="flex justify-between items-center mb-8 flex-wrap gap-5">
        <div>
            <?php if ( $title ) : ?>
            <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('title'); ?> class="m-0 text-<?php echo esc_attr( $title_color ); ?>"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        <div class="flex justify-center gap-3">
          <div class="w-10 h-10 cursor-pointer transition-opacity duration-300 flex items-center justify-center hover:opacity-70 review-btn-prev" aria-label="<?php esc_attr_e( 'Vorige review', 'blueprint' ); ?>">
            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
              <path id="arrow_circle_right_24dp_5F6368_FILL0_wght200_GRAD0_opsz24" d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(160 -800) rotate(180)" fill="#fcf6f1"></path>
            </svg>
          </div>
          <div class="w-10 h-10 cursor-pointer transition-opacity duration-300 flex items-center justify-center hover:opacity-70 review-btn-next" aria-label="<?php esc_attr_e( 'Volgende review', 'blueprint' ); ?>">
            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
              <path id="arrow_circle_right_24dp_5F6368_FILL0_wght200_GRAD0_opsz24" d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(-120 840)" fill="#fcf6f1"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="swiper reviews" role="region" aria-label="<?php esc_attr_e( 'Reviews', 'blueprint' ); ?>">
        <div class="swiper-wrapper">
          <?php foreach ( $items as $item ) :
            $logo_choice = $item['logo'] ?? null;
            $rating = isset( $item['rating'] ) ? (int) $item['rating'] : 5;
            $review = $item['review_text'] ?? '';
            $author = $item['author_name'] ?? '';
            $company = $item['author_company'] ?? '';
            $link = $item['link'] ?? '';
          ?>
            <div class="swiper-slide h-auto">
              <article class="bg-[var(--reviews-card-bg)] p-8 rounded-[18px] w-full box-border flex flex-col justify-between h-full" >
                <div class="w-full">
                  <div class="flex flex-wrap items-center gap-[20px] mb-4">
                    <?php if ( $logo_choice ) : ?>
                      <div class="review-logo">
                        <?php
                        if ( is_numeric( $logo_choice ) ) {
                          echo wp_get_attachment_image( (int) $logo_choice, 'thumbnail', false, array('class' => 'w-[44px] h-[44px] object-contain rounded-[8px] mb-3') );
                        } else {
                          switch ( $logo_choice ) {
                            case 'google':
                              echo '<img src="' . get_template_directory_uri() . '/assets/icons/brands/google.svg" alt="Google Logo" style="height:40px; width:auto;" />';
                              break;

                            case 'facebook':
                              echo '<img src="' . get_template_directory_uri() . '/assets/icons/brands/facebook.svg" alt="Facebook Logo" style="height:40px; width:auto;" />';
                              break;

                            case 'klantenvertellen':
                              echo '<img src="' . get_template_directory_uri() . '/assets/icons/brands/klanten-vertellen.svg" alt="Klantenvertellen Logo" style="height:40px;" />';
                              break;

                            default:
                              echo esc_html( $logo_choice );
                          }
                        }
                        ?>
                      </div>
                    <?php endif; ?>

                    <div class="review-stars" aria-hidden="true">
                      <?php for ( $i = 0; $i < $rating; $i++ ) : ?>
                        <span class="text-[18px]">★</span>
                      <?php endfor; ?>
                    </div>
                  </div>
                  <div class="mt-[10px] mb-[16px] leading-[1.6]"><?php echo wp_kses_post( $review ); ?></div>
                </div>
                <div class="font-semibold mt-2"><?php echo esc_html( $author ); ?><?php if ( $company ) echo ' - ' . esc_html( $company ); ?></div>

                <?php
                  $link_url = '';
                  $link_title = 'Lees meer';
                  if ( $link ) {
                    if ( is_array( $link ) ) {
                      $link_url = $link['url'] ?? '';
                      $link_title = $link['title'] ?? $link_title;
                    } else {
                      $link_url = $link;
                    }
                  }

                  if ( $link_url ) : ?>
                  <div class="review-cta"><a class="inline-block mt-3 button" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a></div>
                <?php endif; ?>
              </article>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
      <?php else : ?>
        <div class="no-reviews text-center py-20 text-<?php echo esc_attr( $text_color ); ?>">
          <p><i>Er zijn nog geen reviews toegevoegd. Voeg reviews toe via de thema instellingen.</i></p>
        </div>
    <?php endif; ?>
  </div>
</section>

<script>
(function() {
  function initReviewsSwiper() {
    var reviewsEl = document.querySelector('.<?php echo 'blueprint-' . basename( __DIR__ ); ?> .reviews');
    if (!reviewsEl) return;
    
    // Destroy existing instance if any
    if (reviewsEl.swiper) {
      reviewsEl.swiper.destroy(true, true);
    }
    
    // find the block wrapper (section)
    var container = reviewsEl.closest('.<?php echo 'blueprint-' . basename( __DIR__ ); ?>');
    var slides = reviewsEl.querySelectorAll('.swiper-slide');
    var slideCount = slides.length;

    var swiperInstance = new Swiper(reviewsEl, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      autoHeight: false,
      navigation: {
        nextEl: container ? container.querySelector('.review-btn-next') : null,
        prevEl: container ? container.querySelector('.review-btn-prev') : null,
      },
      pagination: {
        el: reviewsEl.querySelector('.swiper-pagination'),
        clickable: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
        1280: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
      }
    });
    console.log('reviews-swiper', swiperInstance, 'slideCount', slideCount);
  }

  // Init on load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReviewsSwiper);
  } else {
    initReviewsSwiper();
  }

  // Re-init for block editor
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=reviews-slider', initReviewsSwiper);
  }
})();
</script>