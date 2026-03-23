<?php $header_opts = function_exists( 'get_field' ) ? get_field( 'header', 'option' ) : null; if ( ! empty( $header_opts['usps'] ) && is_array( $header_opts['usps'] ) ) : ?>
<div class="usp-bar" style="background-color: var(--color-primary); color: var(--color-text-light);">
    <div class="container">
        <div class="swiper usps" role="region" aria-label="<?php esc_attr_e( 'Unique selling points', 'blueprint' ); ?>">
            <div class="swiper-wrapper">
                <?php foreach ( $header_opts['usps'] as $row ) :
                    $text = isset( $row['usp'] ) ? trim( $row['usp'] ) : '';
                    if ( $text === '' ) {
                        continue;
                    }
                ?>
                <div class="swiper-slide">
                    <div class="usp-item">
                        <div class="usp-icon" aria-hidden="true" role="img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10.076" height="7.459" viewBox="0 0 10.076 7.459" aria-hidden="true" focusable="false">
                                <path d="M136.562,325.459,133,321.9l.96-.96,2.6,2.617L142.116,318l.96.945Z" transform="translate(-133 -318)" fill="var(--color-primary  )"></path>
                            </svg>
                        </div>
                        <span style="color: var(--color-text-light)"><?php echo esc_html( $text ); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<header class="site-header">
  <div class="container inner-header">
      <div class="site-branding">
        <?php if ( has_custom_logo() ) :
          the_custom_logo();
        else : ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color: var(--color-text-light)"><?php bloginfo( 'name' ); ?></a>
        <?php endif; ?>
      </div>

      <nav class="main-navigation" role="navigation">
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu' ) ); ?>
      </nav>

      <div class="header-actions">
        <?php 
        $cta_button = ! empty( $header_opts['menu_cta_button_1'] ) ? $header_opts['menu_cta_button_1'] : null;
        if ( ! empty( $cta_button['url'] ) && ! empty( $cta_button['title'] ) ) : 
          $target = ! empty( $cta_button['target'] ) ? $cta_button['target'] : '_self';
        ?>
          <a href="<?php echo esc_url( $cta_button['url'] ); ?>" 
             target="<?php echo esc_attr( $target ); ?>"
             class="btn accent">
            <?php echo esc_html( $cta_button['title'] ); ?>
          </a>
        <?php endif; ?>

          <button class="menu-toggle" aria-expanded="false" aria-controls="mobile-mega-menu" aria-label="<?php esc_attr_e( 'Open menu', 'blueprint' ); ?>">
            <span class="sr-only"><?php esc_html_e( 'Toggle menu', 'blueprint' ); ?></span>
            <svg width="26" height="16" viewBox="0 0 26 16" fill="none" aria-hidden="true"><rect width="26" height="2" rx="1" fill="currentColor"></rect><rect y="7" width="26" height="2" rx="1" fill="currentColor"></rect><rect y="14" width="26" height="2" rx="1" fill="currentColor"></rect></svg>
          </button>
      </div>
    </div>

  <div id="mobile-mega-menu" class="mobile-mega" role="dialog" aria-label="<?php esc_attr_e( 'Mobile menu', 'blueprint' ); ?>">
    <div class="container">

      <button class="mobile-close" aria-label="<?php esc_attr_e( 'Close menu', 'blueprint' ); ?>">&times;</button>

      <?php
        // fallback values
        $company      = '';
        $phone        = '';
        $email        = '';

        // header options (if set)
        if ( ! empty( $header_opts ) && is_array( $header_opts ) ) {
          $company = $header_opts['address_details']['companyname'] ?? '';
          $phone   = $header_opts['contact_details']['phone_number'] ?? '';
          $email   = $header_opts['contact_details']['emailadres'] ?? '';
        }

        // fallback to shared options (same approach as footer)
        if ( empty( $company ) ) {
            $address = function_exists( 'get_field' ) ? get_field( 'address_details', 'option' ) : array();
            $company = ! empty( $address['companyname'] ) ? $address['companyname'] : ( function_exists( 'get_field' ) ? get_field( 'companyname', 'option' ) : '' );
            if ( empty( $company ) ) {
                $company = get_bloginfo( 'name' );
            }
        }

        if ( empty( $phone ) ) {
            $contact = function_exists( 'get_field' ) ? get_field( 'contact_details', 'option' ) : array();
            $phone = ! empty( $contact['phone_number'] ) ? $contact['phone_number'] : ( function_exists( 'get_field' ) ? get_field( 'phone_number', 'option' ) : '' );
        }

        if ( empty( $email ) ) {
            if ( empty( $contact ) ) { $contact = function_exists( 'get_field' ) ? get_field( 'contact_details', 'option' ) : array(); }
            $email = ! empty( $contact['emailadres'] ) ? $contact['emailadres'] : ( function_exists( 'get_field' ) ? get_field( 'emailadres', 'option' ) : '' );
        }
      ?>

      <div class="company-block">
        <div class="company-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
      </div>

      <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mobile-menu' ) ); ?>

      <div class="contact">
        <ul class="phone-and-email">
          <?php if ( $phone ) : ?>
            <li><a class="phone" href="tel:<?php echo esc_attr( preg_replace('/\D+/', '', $phone) ); ?>"><i class="fa-regular fa-phone" aria-hidden="true"></i><span><?php echo esc_html( $phone ); ?></span></a></li>
          <?php endif; ?>

          <?php if ( $email ) : ?>
            <li><a class="email" href="mailto:<?php echo esc_attr( $email ); ?>"><i class="fa-sharp fa-solid fa-envelope" aria-hidden="true"></i><span class="reg-text"><?php echo esc_html( $email ); ?></span></a></li>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </div>
</header>


