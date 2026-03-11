<?php

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }
    
  // Load ACF field-values and assign defaults.
  $title = get_field( 'title' );

  // fetch medewerkers posts instead of options repeater
  $employees = get_posts( array(
      'post_type'      => 'medewerkers',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
  ) );

  $team_members = array();
  if ( ! empty( $employees ) ) {
      foreach ( $employees as $emp ) {
          $image_id = get_post_thumbnail_id( $emp->ID );
          // fallback to title if custom name not set
          $name = get_field( 'employee_name', $emp->ID );
          if ( ! $name ) {
              $name = get_the_title( $emp );
          }

          $team_members[] = array(
              'image'    => $image_id,
              'name'     => $name,
              'email'    => get_field( 'employee_email', $emp->ID ),
              'phone'    => get_field( 'employee_phone', $emp->ID ),
              'linkedin' => get_field( 'employee_linkedin', $emp->ID ),
              'url'      => get_permalink( $emp->ID ),
          );
      }
  }

  $colors = get_field( 'colors' ) ?: array();
  $background_color = $colors['bg_color'] ?? 'background';
  $title_color = $colors['title_color'] ?? 'dark';
  $text_color = $title_color;

  $card_style = get_field( 'card_style' ) ?: array();
  $card_background_color = $card_style['card_bg_color'] ?? 'primary';
  $card_text_color = $card_style['card_text_color'] ?? 'light';

  $cta1 = get_field( 'cta_1' ) ?: array();
  $button_title = $cta1['title'] ?? 'Lees meer';
  $button_style = $cta1['cta_style'] ?? 'accent';
  $button_url = $cta1['cta_link']['url'] ?? '';
  $button_target = $cta1['cta_link']['target'] ?? '';

  $rm_bp = get_field( 'rm_pb' );
?>

<?php
    // build section classes like call-to-action
    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
    if ( $rm_bp === true ) $section_classes .= ' pb-0';
?>

<section class="<?= esc_attr( $section_classes ); ?>">
  <div class="container">
    <?php if ( ! empty( $team_members ) ) : ?>
      <div class="team-header flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-[20px]">
        <?php if ( $title ) : ?>
            <h2 class="text-2xl leading-tight m-0 text-<?php echo esc_attr( $title_color ); ?>"><?php echo wp_kses_post( $title ); ?></h2>
        <?php endif; ?>
        <div class="team-navigation flex gap-4">
          <div class="team-btn-prev carousel-btn cursor-pointer flex items-center justify-center w-10 h-10 transition-opacity transition-transform duration-300 hover:opacity-70 hover:scale-110" aria-label="<?php esc_attr_e( 'Vorige medewerker', 'blueprint' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(160 -800) rotate(180)" fill="currentColor"></path>
            </svg>
          </div>
          <div class="team-btn-next carousel-btn cursor-pointer flex items-center justify-center w-10 h-10 transition-opacity transition-transform duration-300 hover:opacity-70 hover:scale-110" aria-label="<?php esc_attr_e( 'Volgende medewerker', 'blueprint' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path d="M143.137-818.889l-3.923,3.923a.942.942,0,0,0-.291.743,1.094,1.094,0,0,0,.333.743,1.047,1.047,0,0,0,.772.312,1,1,0,0,0,.758-.312l5.265-5.264A1.723,1.723,0,0,0,146.59-820a1.723,1.723,0,0,0-.539-1.257l-5.308-5.307a1.014,1.014,0,0,0-.743-.312,1.014,1.014,0,0,0-.743.312,1.047,1.047,0,0,0-.312.772,1,1,0,0,0,.312.758l3.88,3.923h-9.8a1.074,1.074,0,0,0-.792.32,1.078,1.078,0,0,0-.319.793,1.072,1.072,0,0,0,.319.791,1.077,1.077,0,0,0,.792.318ZM140.007-800a19.493,19.493,0,0,1-7.8-1.574,20.214,20.214,0,0,1-6.353-4.273,20.18,20.18,0,0,1-4.277-6.348,19.448,19.448,0,0,1-1.576-7.8,19.493,19.493,0,0,1,1.574-7.8,20.214,20.214,0,0,1,4.273-6.353,20.182,20.182,0,0,1,6.348-4.277,19.449,19.449,0,0,1,7.8-1.576,19.493,19.493,0,0,1,7.8,1.574,20.214,20.214,0,0,1,6.353,4.273,20.181,20.181,0,0,1,4.277,6.348,19.449,19.449,0,0,1,1.576,7.8,19.492,19.492,0,0,1-1.574,7.8,20.213,20.213,0,0,1-4.273,6.353,20.18,20.18,0,0,1-6.348,4.277A19.448,19.448,0,0,1,140.007-800ZM140-802.222a17.158,17.158,0,0,0,12.611-5.167A17.158,17.158,0,0,0,157.778-820a17.158,17.158,0,0,0-5.167-12.611A17.158,17.158,0,0,0,140-837.778a17.158,17.158,0,0,0-12.611,5.167A17.158,17.158,0,0,0,122.222-820a17.158,17.158,0,0,0,5.167,12.611A17.158,17.158,0,0,0,140-802.222ZM140-820Z" transform="translate(-120 840)" fill="currentColor"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="swiper team-members" role="region" aria-label="<?php esc_attr_e( 'Team leden', 'blueprint' ); ?>">
        <div class="swiper-wrapper">
          <?php foreach ( $team_members as $member ) :
            $image_id = $member['image'] ?? null;
            $name = $member['name'] ?? '';
            $email = $member['email'] ?? '';
            $phone = $member['phone'] ?? '';
            $linkedin = $member['linkedin'] ?? '';
            $profile_url = $member['url'] ?? '';
          ?>
            <div class="swiper-slide">
              <article class="team-member bg-<?php echo esc_attr( $card_background_color ); ?> text-<?php echo esc_attr( $card_text_color ); ?> rounded-[30px] overflow-hidden shadow-md  hover:shadow-lg relative mx-auto">
                <?php if ( $image_id ) : ?>
                  <div class="member-photo">
                    <?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'loading' => 'lazy', 'class'=>'w-full h-auto object-cover aspect-[3/4]' ) ); ?>
                  </div>
                <?php endif; ?>

                <?php if ( $name ) : ?>
                  <div class="member-name absolute inset-x-0 bottom-0 text-lg font-semibold text-white text-center py-5 px-6 bg-gradient-to-t from-black/85 via-black/60 to-transparent z-10">
                    <?php echo esc_html( $name ); ?>

                    <?php if ( $email || $phone || $linkedin ) : ?>
                      <div class="member-icons flex justify-center gap-3 mt-2">
                        <?php if ( $email ) : ?>
                          <a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-icon text-white" aria-label="<?php esc_attr_e( 'E-mail', 'blueprint' ); ?>">
                            <span class="dashicons dashicons-email" aria-hidden="true"></span>
                          </a>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                          <a href="tel:<?php echo esc_attr( $phone ); ?>" class="contact-icon text-white" aria-label="<?php esc_attr_e( 'Telefoon', 'blueprint' ); ?>">
                            <span class="dashicons dashicons-phone" aria-hidden="true"></span>
                          </a>
                        <?php endif; ?>
                        <?php if ( $linkedin ) : ?>
                          <a href="<?php echo esc_url( $linkedin ); ?>" class="contact-icon text-white" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'LinkedIn', 'blueprint' ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 640"><path d="M196.3 512L103.4 512L103.4 212.9L196.3 212.9L196.3 512zM149.8 172.1C120.1 172.1 96 147.5 96 117.8C96 103.5 101.7 89.9 111.8 79.8C121.9 69.7 135.6 64 149.8 64C164 64 177.7 69.7 187.8 79.8C197.9 89.9 203.6 103.6 203.6 117.8C203.6 147.5 179.5 172.1 149.8 172.1zM543.9 512L451.2 512L451.2 366.4C451.2 331.7 450.5 287.2 402.9 287.2C354.6 287.2 347.2 324.9 347.2 363.9L347.2 512L254.4 512L254.4 212.9L343.5 212.9L343.5 253.7L344.8 253.7C357.2 230.2 387.5 205.4 432.7 205.4C526.7 205.4 544 267.3 544 347.7L544 512L543.9 512z" fill="#fff" /></svg>
                          </a>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

              </article>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ( $button_url ) : ?>
        <div class="team-cta mt-12 text-center">
          <a href="<?php echo esc_url( $button_url ); ?>" class="btn <?php echo esc_attr( $button_style ); ?>"<?php echo $button_target ? ' target="' . esc_attr($button_target) . '"' : ''; ?> >
            <?php echo esc_html( $button_title ); ?>
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<script>
(function() {
  function initTeamSwiper() {
    var teamEl = document.querySelector('.<?php echo 'blueprint-' . basename( __DIR__ ); ?>  .team-members');
    if (!teamEl) return;
    

    if (teamEl.swiper) {
      teamEl.swiper.destroy(true, true);
    }
    
    var container = teamEl.closest('.<?php echo 'blueprint-' . basename( __DIR__ ); ?> ');
    var slides = teamEl.querySelectorAll('.swiper-slide');
    var slideCount = slides.length;

    new Swiper(teamEl, {
      slidesPerView: '1',
      loop: true,
      spaceBetween: 20,
      navigation: {
        nextEl: container.querySelector('.team-btn-next'),
        prevEl: container.querySelector('.team-btn-prev'),
      },
      breakpoints: {
        640: {
          slidesPerView: '2',
          spaceBetween: 20,
        },
        768: {
          slidesPerView: '3',
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: '4',
          spaceBetween: 30,
        },
      }
    });
  }

  // Init on load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTeamSwiper);
  } else {
    initTeamSwiper();
  }

  // Re-init for block editor
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=medewerkers-slider', initTeamSwiper);
  }
})();
</script>
