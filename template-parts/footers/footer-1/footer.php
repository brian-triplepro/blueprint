<?php
if ( function_exists( 'get_field' ) ) {
    $address = get_field( 'address_details', 'option' ) ?: array();
    $contact = get_field( 'contact_details', 'option' ) ?: array();
    $footer_opts = get_field( 'footer', 'option' ) ?: array();
} else {
    $address = array();
    $contact = array();
    $footer_opts = array();
}

$company = ! empty( $address['companyname'] ) ? $address['companyname'] : '';

$street = ! empty( $address['street'] ) ? $address['street'] : '';
$house_number = ! empty( $address['house_number'] ) ? $address['house_number'] : '';
$zipcode = ! empty( $address['zipcode'] ) ? $address['zipcode'] : '';
$city = ! empty( $address['city'] ) ? $address['city'] : '';

$phone = ! empty( $contact['phone_number'] ) ? $contact['phone_number'] : '';
$whatsapp = ! empty( $contact['whatsapp'] ) ? $contact['whatsapp'] : '';
$email = ! empty( $contact['emailadres'] ) ? $contact['emailadres'] : '';

$footer_logo = ! empty( $footer_opts['footer_logo'] ) ? $footer_opts['footer_logo'] : null;
?>
    </main>
        <footer class="site-footer">
            <div class="container">
                <div class="footer-top">
                    <div class="footer-brand">
                        <?php if ( ! empty( $footer_logo['url'] ) ) : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
                                <img src="<?php echo esc_url( $footer_logo['url'] ); ?>" 
                                     alt="<?php echo esc_attr( $footer_logo['alt'] ?: get_bloginfo( 'name' ) ); ?>" 
                                     width="<?php echo esc_attr( $footer_logo['width'] ); ?>" 
                                     height="<?php echo esc_attr( $footer_logo['height'] ); ?>" />
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
                                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <?php
                        $social = function_exists( 'get_field' ) ? ( get_field( 'social_media_links', 'option' ) ?: array() ) : array();
                        $social_platforms = array(
                            'instagram' => array( 'label' => 'Instagram',  'icon' => 'fa-instagram.svg' ),
                            'facebook'  => array( 'label' => 'Facebook',   'icon' => 'fa-facebook.svg' ),
                            'linkedin'  => array( 'label' => 'LinkedIn',   'icon' => 'fa-linkedin-in.svg' ),
                            'x_twitter' => array( 'label' => 'X / Twitter','icon' => 'fa-twitter.svg' ),
                            'tiktok'    => array( 'label' => 'TikTok',     'icon' => 'fa-tiktok.svg' ),
                        );
                        $has_social = false;
                        foreach ( $social_platforms as $key => $platform ) {
                            if ( ! empty( $social[ $key ] ) ) { $has_social = true; break; }
                        }
                        if ( $has_social ) : ?>
                        <p class="footer-follow"><?php esc_html_e( 'Volg ons', 'blueprint' ); ?></p>
                        <div class="footer-social">
                            <?php foreach ( $social_platforms as $key => $platform ) :
                                if ( empty( $social[ $key ] ) ) continue; ?>
                                <a href="<?php echo esc_url( $social[ $key ] ); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $platform['label'] ); ?>">
                                    <span class="social-icon"><?php
                                    $svg_path = get_template_directory() . '/assets/icons/brands/' . $platform['icon'];
                                    if ( file_exists( $svg_path ) ) echo file_get_contents( $svg_path ); // phpcs:ignore WordPress.Security.EscapeOutput
                                    ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer-column">
                        <?php
                        $menu_name_1 = '';
                        $menu_location_1 = 'footer-1';
                        if ( has_nav_menu( $menu_location_1 ) ) {
                            $menu_object_1 = wp_get_nav_menu_object( get_nav_menu_locations()[$menu_location_1] );
                            if ( $menu_object_1 ) {
                                $menu_name_1 = $menu_object_1->name;
                            }
                        }
                        ?>
                        <?php if ( $menu_name_1 ) : ?>
                            <h4 class="footer-heading text-accent"><?php echo esc_html( $menu_name_1 ); ?></h4>
                        <?php endif; ?>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-1',
                            'container' => false,
                            'menu_class' => 'footer-menu',
                            'fallback_cb' => false,
                            'depth' => 1,
                        ) );
                        ?>
                    </div>

                    <div class="footer-column">
                        <?php
                        $menu_name_2 = '';
                        $menu_location_2 = 'footer-2';
                        if ( has_nav_menu( $menu_location_2 ) ) {
                            $menu_object_2 = wp_get_nav_menu_object( get_nav_menu_locations()[$menu_location_2] );
                            if ( $menu_object_2 ) {
                                $menu_name_2 = $menu_object_2->name;
                            }
                        }
                        ?>
                        <?php if ( $menu_name_2 ) : ?>
                            <h4 class="footer-heading text-accent"><?php echo esc_html( $menu_name_2 ); ?></h4>
                        <?php endif; ?>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-2',
                            'container' => false,
                            'menu_class' => 'footer-menu',
                            'fallback_cb' => false,
                            'depth' => 1,
                        ) );
                        ?>
                    </div>

                    <div class="footer-column">
                        <h4 class="footer-heading text-accent"><?php esc_html_e( 'Contact', 'blueprint' ); ?></h4>
                        <address class="footer-contact"> 
                            <?php if ( $company ) : ?><div class="company"><?php echo esc_html( $company ); ?></div><?php endif; ?>
                            <?php if ( $street || $house_number ) : ?><div class="street"><?php echo esc_html( trim( $street . ' ' . $house_number ) ); ?></div><?php endif; ?>
                            <?php if ( $zipcode || $city ) : ?><div class="city"><?php echo esc_html( trim( $zipcode . ' ' . $city ) ); ?></div><?php endif; ?>
                            <?php if ( $phone ) : ?><div class="phone"><a href="tel:<?php echo esc_attr( preg_replace( '/\D+/', '', $phone ) ); ?>" class="footer-<?php echo esc_attr( $footer_text_color ); ?>"><?php echo esc_html( $phone ); ?></a></div><?php endif; ?>
                            <?php if ( $whatsapp ) : ?><div class="whatsapp"><a target="_blank" rel="noopener" href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D+/', '', $whatsapp ) ); ?>" class="footer-<?php echo esc_attr( $footer_text_color ); ?>"><?php esc_html_e( 'WhatsApp', 'blueprint' ); ?></a></div><?php endif; ?>
                            <?php if ( $email ) : ?><div class="email"><a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer-<?php echo esc_attr( $footer_text_color ); ?>"><?php echo esc_html( $email ); ?></a></div><?php endif; ?>
                        </address>
                    </div>
                </div>
                    

                <div class="footer-bottom">
                    <div class="footer-logos">
                        <div id="tpom-regeltante-logo" class="flex gap-[15px] items-center">
                            <a target="_blank" data-rel="noopener" href="https://www.onlinemarketing.triplepro.nl" class="logo_triplepro" aria-label="link naar de website van triplepro online marketing"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="135" height="25" viewBox="0 0 135 25">
                                <g transform="matrix(0.99999718,0,0,1,2,0)"> 
                                    <path data-rel="logo_square" d="M 15.063,34.6 H 2.564 V 22.1 H 7.531 V 19.539 H 0 v 17.626 h 17.626 v -7.532 h -2.563 z" transform="translate(0,-15.737)"></path> 
                                    <path data-rel="logo_arrow" d="M 42.771,19.538 V 22.1 H 45.9 l -5.33,5.332 1.813,1.812 5.355,-5.354 v 3.178 H 50.3 v -7.53 z" transform="translate(-32.677,-15.737)"></path> 
                                    <path data-rel="t" d="M 111.154,30.919 V 29.31 h 1.562 V 26.8 l 1.892,-1.843 v 4.353 h 2.081 v 1.609 h -2.081 v 3.311 c 0,0.758 0.219,1.23 1.056,1.23 a 1.962,1.962 0 0 0 1.025,-0.237 v 1.593 a 3.993,3.993 0 0 1 -1.5,0.252 c -2,0 -2.475,-0.9 -2.475,-2.681 v -3.468 z" transform="translate(-89.526,-20.102)"></path> 
                                    <path data-rel="r" d="m 147.132,46.541 h 1.893 v 1.2 h 0.031 a 2.411,2.411 0 0 1 2.239,-1.388 2.44,2.44 0 0 1 0.678,0.111 v 1.829 a 3.824,3.824 0 0 0 -0.914,-0.142 1.9,1.9 0 0 0 -2.034,1.908 v 4.051 h -1.893 z" transform="translate(-118.503,-37.333)"></path> <rect data-rel="i_dot" width="1.892" height="1.931" transform="translate(34.65,5.673)" x="0" y="0"></rect> <rect data-rel="i_line" width="1.892" height="7.5689998" transform="translate(34.651,9.208)" x="0" y="0"></rect> 
                                    <path data-rel="p" d="m 202.405,48.055 a 2.271,2.271 0 1 0 2.224,2.27 2.19,2.19 0 0 0 -2.224,-2.27 m -4.052,-1.514 h 1.8 v 1.136 h 0.033 a 2.916,2.916 0 0 1 2.6,-1.325 3.674,3.674 0 0 1 3.737,3.974 3.712,3.712 0 0 1 -3.66,3.974 3.111,3.111 0 0 1 -2.568,-1.152 h -0.048 v 4.557 h -1.893 z" transform="translate(-159.757,-37.333)"></path> <rect data-rel="l" width="1.893" height="11.921" transform="translate(48.467,4.857)" x="0" y="0"></rect> 
                                    <path data-rel="e" d="m 273.087,49.568 a 1.743,1.743 0 0 0 -1.924,-1.8 1.883,1.883 0 0 0 -1.956,1.8 z m -3.88,1.419 a 1.962,1.962 0 0 0 2.034,1.8 2.488,2.488 0 0 0 2.034,-1.025 l 1.356,1.025 a 4.048,4.048 0 1 1 -3.2,-6.434 c 2.113,0 3.547,1.482 3.547,4.116 v 0.52 z" transform="translate(-215.3,-37.332)"></path> 
                                    <path data-rel="p" d="m 319.433,48.055 a 2.271,2.271 0 1 0 2.224,2.27 2.19,2.19 0 0 0 -2.224,-2.27 m -4.052,-1.514 h 1.8 v 1.136 h 0.031 a 2.918,2.918 0 0 1 2.6,-1.325 3.674,3.674 0 0 1 3.737,3.974 3.713,3.713 0 0 1 -3.659,3.974 3.117,3.117 0 0 1 -2.571,-1.152 h -0.046 v 4.557 h -1.893 z" transform="translate(-254.014,-37.333)"></path> 
                                    <path data-rel="r" d="m 365.952,46.541 h 1.892 v 1.2 h 0.032 a 2.423,2.423 0 0 1 2.917,-1.277 v 1.829 a 3.81,3.81 0 0 0 -0.914,-0.142 1.9,1.9 0 0 0 -2.034,1.908 v 4.051 h -1.892 z" transform="translate(-294.745,-37.333)"></path> 
                                    <path data-rel="o" d="m 398.865,52.6 a 2.271,2.271 0 1 0 -2.223,-2.271 2.191,2.191 0 0 0 2.223,2.271 m 0,-6.244 a 3.976,3.976 0 1 1 -4.115,3.974 3.924,3.924 0 0 1 4.115,-3.974" transform="translate(-317.939,-37.333)"></path> <rect data-rel="seperator" width="0.713" height="17.629999" transform="translate(89.044,3.8)" x="0" y="0"></rect> 
                                    <path data-rel="o" d="m 483.827,36.393 a 1.094,1.094 0 0 0 0.879,-0.433 2.1,2.1 0 0 0 0.355,-1.347 1.96,1.96 0 0 0 -0.373,-1.333 1.135,1.135 0 0 0 -0.869,-0.413 1.092,1.092 0 0 0 -0.866,0.418 2.024,2.024 0 0 0 -0.355,1.323 2.107,2.107 0 0 0 0.355,1.372 1.107,1.107 0 0 0 0.874,0.413 m -0.052,0.568 a 1.677,1.677 0 0 1 -1.419,-0.674 2.692,2.692 0 0 1 -0.51,-1.672 2.413,2.413 0 0 1 0.573,-1.686 1.848,1.848 0 0 1 1.426,-0.631 1.741,1.741 0 0 1 1.442,0.664 2.579,2.579 0 0 1 0.527,1.656 2.482,2.482 0 0 1 -0.569,1.688 1.855,1.855 0 0 1 -1.469,0.655" transform="translate(-388.088,-26.013)"></path> 
                                    <path data-rel="n" d="m 510.452,36.839 h -0.643 V 34 a 1.145,1.145 0 0 0 -0.267,-0.862 0.913,0.913 0 0 0 -0.648,-0.249 1.112,1.112 0 0 0 -0.881,0.42 1.654,1.654 0 0 0 -0.356,1.107 v 2.423 h -0.687 v -4.418 h 0.639 v 0.814 a 1.59,1.59 0 0 1 1.4,-0.936 1.377,1.377 0 0 1 0.841,0.255 1.355,1.355 0 0 1 0.477,0.575 3.47,3.47 0 0 1 0.129,1.155 z" transform="translate(-408.323,-26.014)"></path> <rect data-rel="l" width="0.68800002" height="5.9699998" transform="translate(103.316,4.854)" x="0" y="0"></rect> 
                                    <path data-rel="i" d="M 541.207,30.919 H 540.52 V 26.5 h 0.687 z m 0.023,-5.2 h -0.74 v -0.765 h 0.74 z" transform="translate(-435.321,-20.094)"></path> 
                                    <path data-rel="n" d="m 553.8,36.839 h -0.642 V 34 a 1.144,1.144 0 0 0 -0.268,-0.862 0.911,0.911 0 0 0 -0.647,-0.249 1.111,1.111 0 0 0 -0.881,0.42 1.653,1.653 0 0 0 -0.362,1.107 v 2.423 h -0.687 v -4.418 h 0.639 v 0.814 a 1.589,1.589 0 0 1 1.4,-0.936 1.382,1.382 0 0 1 0.842,0.255 1.357,1.357 0 0 1 0.477,0.575 3.516,3.516 0 0 1 0.128,1.155 z" transform="translate(-443.234,-26.014)"></path> 
                                    <path data-rel="e" d="m 576.088,34.178 a 1.946,1.946 0 0 0 -0.123,-0.66 1.129,1.129 0 0 0 -0.385,-0.492 1.061,1.061 0 0 0 -0.651,-0.2 1.164,1.164 0 0 0 -0.869,0.368 1.52,1.52 0 0 0 -0.4,0.989 z m 0.039,1.343 0.639,0.132 a 1.8,1.8 0 0 1 -0.682,0.937 1.964,1.964 0 0 1 -1.194,0.371 1.859,1.859 0 0 1 -1.466,-0.637 2.778,2.778 0 0 1 0.023,-3.375 1.877,1.877 0 0 1 1.46,-0.65 1.765,1.765 0 0 1 1.393,0.6 2.689,2.689 0 0 1 0.54,1.793 h -3.21 a 1.712,1.712 0 0 0 0.42,1.352 1.364,1.364 0 0 0 0.887,0.35 1.214,1.214 0 0 0 1.19,-0.87" transform="translate(-461.395,-26.013)"></path> 
                                    <path data-rel="m" d="m 489.544,77.352 h -0.67 v -2.747 a 2.548,2.548 0 0 0 -0.059,-0.637 0.758,0.758 0 0 0 -0.286,-0.389 0.812,0.812 0 0 0 -0.512,-0.177 1.08,1.08 0 0 0 -0.759,0.361 1.491,1.491 0 0 0 -0.365,1.1 v 2.489 h -0.664 v -2.908 a 1.04,1.04 0 0 0 -0.278,-0.822 0.881,0.881 0 0 0 -0.584,-0.241 0.977,0.977 0 0 0 -0.762,0.376 1.411,1.411 0 0 0 -0.323,0.954 v 2.642 h -0.656 v -4.418 h 0.59 v 0.848 a 1.507,1.507 0 0 1 1.361,-0.971 1.139,1.139 0 0 1 0.809,0.311 1.425,1.425 0 0 1 0.43,0.748 1.485,1.485 0 0 1 1.4,-1.059 1.267,1.267 0 0 1 0.789,0.258 1.188,1.188 0 0 1 0.442,0.592 3.336,3.336 0 0 1 0.1,0.943 z" transform="translate(-389.522,-58.644)"></path> 
                                    <path data-rel="a" d="m 521.6,75.143 c -0.28,-0.017 -0.492,-0.027 -0.638,-0.027 a 2.486,2.486 0 0 0 -1.264,0.268 0.856,0.856 0 0 0 -0.451,0.78 0.728,0.728 0 0 0 0.243,0.553 0.987,0.987 0 0 0 0.7,0.228 1.3,1.3 0 0 0 1.021,-0.452 1.55,1.55 0 0 0 0.4,-1.061 c 0,-0.082 0,-0.178 -0.013,-0.289 m 0.758,2.209 h -0.679 c -0.026,-0.14 -0.054,-0.405 -0.079,-0.8 a 1.591,1.591 0 0 1 -1.548,0.918 1.547,1.547 0 0 1 -1.14,-0.394 1.279,1.279 0 0 1 -0.4,-0.946 1.329,1.329 0 0 1 0.569,-1.1 3.286,3.286 0 0 1 1.934,-0.431 c 0.122,0 0.317,0.005 0.586,0.017 a 2.56,2.56 0 0 0 -0.08,-0.759 0.68,0.68 0 0 0 -0.344,-0.374 1.466,1.466 0 0 0 -0.691,-0.14 q -1.045,0 -1.216,0.8 l -0.634,-0.109 q 0.233,-1.22 1.951,-1.22 a 1.729,1.729 0 0 1 1.3,0.4 2.018,2.018 0 0 1 0.384,1.4 v 1.876 a 3.661,3.661 0 0 0 0.093,0.858" transform="translate(-417.616,-58.644)"></path> 
                                    <path data-rel="r" d="m 544.577,77.313 h -0.686 V 72.9 h 0.6 v 1.032 a 2.219,2.219 0 0 1 0.64,-0.947 1.163,1.163 0 0 1 0.658,-0.217 c 0.07,0 0.16,0.007 0.272,0.018 v 0.656 h -0.14 a 1.21,1.21 0 0 0 -0.945,0.424 1.723,1.723 0 0 0 -0.4,1.115 z" transform="translate(-438.06,-58.605)"></path> 
                                    <path data-rel="k" d="M 562.317,71.433 H 561.6 l -1.292,-2.467 -0.957,1.1 v 1.365 h -0.688 v -5.97 h 0.688 v 3.829 l 2.052,-2.274 h 0.734 l -1.381,1.551 z" transform="translate(-449.962,-52.725)"></path> 
                                    <path data-rel="e" d="m 581.823,74.691 a 1.911,1.911 0 0 0 -0.123,-0.661 1.126,1.126 0 0 0 -0.385,-0.492 1.054,1.054 0 0 0 -0.651,-0.2 1.167,1.167 0 0 0 -0.869,0.367 1.524,1.524 0 0 0 -0.4,0.988 z m 0.039,1.343 0.639,0.131 a 1.8,1.8 0 0 1 -0.682,0.937 1.968,1.968 0 0 1 -1.195,0.371 1.857,1.857 0 0 1 -1.465,-0.637 2.781,2.781 0 0 1 0.023,-3.375 1.876,1.876 0 0 1 1.46,-0.651 1.767,1.767 0 0 1 1.393,0.6 2.7,2.7 0 0 1 0.541,1.793 h -3.211 a 1.711,1.711 0 0 0 0.42,1.352 1.364,1.364 0 0 0 0.887,0.35 1.212,1.212 0 0 0 1.19,-0.87" transform="translate(-466.015,-58.643)"></path> 
                                    <path data-rel="t" d="m 603.156,72.537 v 0.542 a 4.836,4.836 0 0 1 -0.629,0.048 q -1.284,0 -1.282,-1.339 v -2.662 h -0.784 v -0.538 h 0.784 l 0.03,-1.111 0.639,-0.061 v 1.173 h 0.988 v 0.538 h -0.988 V 71.9 c 0,0.456 0.235,0.684 0.708,0.684 a 3.36,3.36 0 0 0 0.534,-0.049" transform="translate(-483.622,-54.297)"></path> 
                                    <path data-rel="i" d="m 618.417,71.432 h -0.686 v -4.417 h 0.686 z m 0.023,-5.205 h -0.74 v -0.765 h 0.739 z" transform="translate(-497.508,-52.724)"></path> 
                                    <path data-rel="n" d="m 631.006,77.352 h -0.643 v -2.838 a 1.145,1.145 0 0 0 -0.266,-0.862 0.915,0.915 0 0 0 -0.648,-0.249 1.111,1.111 0 0 0 -0.881,0.42 1.653,1.653 0 0 0 -0.356,1.107 v 2.424 h -0.687 v -4.419 h 0.639 v 0.813 a 1.59,1.59 0 0 1 1.4,-0.936 1.381,1.381 0 0 1 0.842,0.255 1.365,1.365 0 0 1 0.477,0.575 3.483,3.483 0 0 1 0.129,1.155 z" transform="translate(-505.42,-58.644)"></path> 
                                    <path data-rel="g" d="m 651.232,72.915 a 0.979,0.979 0 0 0 0.713,-0.269 0.93,0.93 0 0 0 0.275,-0.7 0.964,0.964 0 0 0 -0.978,-0.994 1.032,1.032 0 0 0 -0.692,0.261 1.041,1.041 0 0 0 -0.03,1.435 0.976,0.976 0 0 0 0.713,0.264 m -0.822,1.868 a 0.665,0.665 0 0 0 -0.482,0.608 0.576,0.576 0 0 0 0.4,0.523 2.379,2.379 0 0 0 1.008,0.186 3.227,3.227 0 0 0 1.041,-0.16 q 0.472,-0.16 0.472,-0.54 a 0.442,0.442 0 0 0 -0.14,-0.337 0.692,0.692 0 0 0 -0.382,-0.166 c -0.163,-0.023 -0.445,-0.041 -0.847,-0.053 q -0.722,-0.017 -1.067,-0.061 m 2.922,-4.93 v 0.55 a 1.242,1.242 0 0 0 -0.632,0.109 0.578,0.578 0 0 0 -0.212,0.394 1.6,1.6 0 0 1 0.425,1.067 1.372,1.372 0 0 1 -0.462,1.037 1.738,1.738 0 0 1 -1.24,0.433 1.461,1.461 0 0 1 -0.3,-0.026 2.224,2.224 0 0 0 -0.3,-0.047 0.53,0.53 0 0 0 -0.284,0.116 0.352,0.352 0 0 0 -0.166,0.3 0.325,0.325 0 0 0 0.24,0.341 5.163,5.163 0 0 0 1.133,0.1 6.4,6.4 0 0 1 1.118,0.1 1.129,1.129 0 0 1 0.609,0.359 0.98,0.98 0 0 1 0.249,0.68 q 0,1.264 -2.222,1.264 a 2.881,2.881 0 0 1 -1.538,-0.317 0.971,0.971 0 0 1 -0.482,-0.847 0.888,0.888 0 0 1 0.674,-0.885 0.832,0.832 0 0 1 -0.434,-0.713 0.855,0.855 0 0 1 0.613,-0.783 1.441,1.441 0 0 1 -0.574,-1.167 1.349,1.349 0 0 1 0.491,-1.076 1.77,1.77 0 0 1 1.193,-0.42 2.13,2.13 0 0 1 0.905,0.188 0.852,0.852 0 0 1 0.3,-0.6 1.28,1.28 0 0 1 0.726,-0.166 c 0.031,0 0.088,0 0.17,0.009" transform="translate(-522.93,-56.254)"></path>
                                </g>
                            </svg>
                            </a>
                            <a target="_blank" data-rel="noopener" href="https://regeltante2punt0.nl" class="logo_regeltante" aria-label="link naar de website van regeltante 2 punt 0"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="180" height="25" viewBox="0 0 180 25">
                                <g data-rel="regeltante_2_0" transform="matrix(1.0000023,0,0,0.99999997,-149.29242,0.04960002)"> 
                                    <path data-rel="R" d="m 1016.506,44.573 h -2.6 a 0.247,0.247 0 0 1 -0.166,-0.1 l -1.91,-3.44 h -1.858 v 3.387 a 0.171,0.171 0 0 1 -0.184,0.154 h -2.1 a 0.165,0.165 0 0 1 -0.165,-0.154 V 32.245 a 0.166,0.166 0 0 1 0.165,-0.154 h 4.641 a 4.337,4.337 0 0 1 4.366,4.463 c 0,2.061 -0.859,3.676 -2.149,4.157 l 2.024,3.7 c 0.11,0.19 -0.068,0.161 -0.068,0.161 m -6.529,-6 h 2.356 a 2.046,2.046 0 0 0 1.915,-2.018 2.153,2.153 0 0 0 -1.915,-2.017 h -2.356 z" transform="translate(-811.48,-25.847)"></path> 
                                    <path data-rel="E" d="m 1067.017,35.757 v 2.75 h 4.787 a 0.154,0.154 0 0 1 0.156,0.14 v 1.932 a 0.154,0.154 0 0 1 -0.156,0.14 h -4.787 v 2.75 h 5.779 a 0.16,0.16 0 0 1 0.174,0.14 v 1.985 a 0.16,0.16 0 0 1 -0.174,0.139 h -7.937 a 0.16,0.16 0 0 1 -0.174,-0.139 V 33.686 a 0.16,0.16 0 0 1 0.174,-0.14 h 7.781 a 0.16,0.16 0 0 1 0.174,0.14 v 1.932 a 0.159,0.159 0 0 1 -0.174,0.139 z" transform="translate(-857.517,-27.019)"></path> 
                                    <path data-rel="G" d="m 1129.279,37.962 a 0.153,0.153 0 0 1 0.156,0.139 v 6.824 a 0.153,0.153 0 0 1 -0.156,0.138 h -1.741 c -0.087,0 -0.174,-0.052 -0.174,-0.122 l -0.07,-1.131 a 4.038,4.038 0 0 1 -3.255,1.376 c -3.289,0 -5.867,-2.281 -5.867,-6.267 a 6.1,6.1 0 0 1 6.128,-6.267 5.972,5.972 0 0 1 4.317,1.793 0.131,0.131 0 0 1 0,0.21 l -1.392,1.392 a 0.184,0.184 0 0 1 -0.226,0 3.818,3.818 0 0 0 -2.7,-1.132 3.723,3.723 0 0 0 -3.8,4 c 0,2.8 1.707,4 3.8,4 a 3.758,3.758 0 0 0 2.8,-1.271 v -1.577 h -1.967 c -0.1,0 -0.175,-0.052 -0.175,-0.122 V 38.1 a 0.159,0.159 0 0 1 0.175,-0.139 z" transform="translate(-900.597,-26.299)"></path> 
                                    <path data-rel="E" d="m 1194.124,35.757 v 2.75 h 4.787 a 0.154,0.154 0 0 1 0.156,0.14 v 1.932 a 0.154,0.154 0 0 1 -0.156,0.14 h -4.787 v 2.75 h 5.779 a 0.159,0.159 0 0 1 0.174,0.14 v 1.985 a 0.159,0.159 0 0 1 -0.174,0.139 h -7.938 a 0.159,0.159 0 0 1 -0.174,-0.139 V 33.686 a 0.16,0.16 0 0 1 0.174,-0.14 h 7.781 a 0.159,0.159 0 0 1 0.173,0.14 v 1.932 a 0.158,0.158 0 0 1 -0.173,0.139 z" transform="translate(-959.891,-27.019)"></path> 
                                    <path data-rel="L" d="m 1253.557,43.468 a 0.159,0.159 0 0 1 0.174,0.14 v 1.985 a 0.159,0.159 0 0 1 -0.174,0.139 h -6.406 a 0.16,0.16 0 0 1 -0.175,-0.139 V 33.686 a 0.16,0.16 0 0 1 0.175,-0.14 h 2 a 0.154,0.154 0 0 1 0.156,0.14 v 9.783 z" transform="translate(-1004.338,-27.019)"></path> 
                                    <path data-rel="T" d="m 1294.5,35.5 a 0.159,0.159 0 0 1 -0.174,0.139 h -2.332 v 9.958 a 0.16,0.16 0 0 1 -0.174,0.139 h -1.967 a 0.159,0.159 0 0 1 -0.174,-0.139 v -9.962 h -2.843 a 0.159,0.159 0 0 1 -0.174,-0.139 v -1.81 a 0.16,0.16 0 0 1 0.174,-0.14 h 7.491 a 0.159,0.159 0 0 1 0.174,0.14 z" transform="translate(-1036.3,-27.019)"></path> 
                                    <path data-rel="A" d="m 1346.6,37.654 v 7.939 a 0.159,0.159 0 0 1 -0.173,0.139 h -1.985 a 0.159,0.159 0 0 1 -0.174,-0.139 v -2.02 h -4.613 v 2.02 a 0.16,0.16 0 0 1 -0.174,0.139 h -1.985 a 0.16,0.16 0 0 1 -0.173,-0.139 v -7.939 a 4.463,4.463 0 0 1 0.592,-2.072 5.04,5.04 0 0 1 8.095,0 4.416,4.416 0 0 1 0.592,2.072 m -2.332,3.847 v -3.83 a 2.177,2.177 0 0 0 -0.279,-0.992 2.229,2.229 0 0 0 -2.036,-0.871 2.148,2.148 0 0 0 -2.019,0.923 2.216,2.216 0 0 0 -0.278,0.922 V 41.5 Z" transform="translate(-1077.103,-27.019)"></path> 
                                    <path data-rel="N" d="m 1411.436,45.593 a 0.159,0.159 0 0 1 -0.173,0.139 h -2 c -0.035,0 -0.123,-0.017 -0.14,-0.052 l -5.605,-8.026 v 7.939 a 0.16,0.16 0 0 1 -0.175,0.139 h -2 a 0.154,0.154 0 0 1 -0.157,-0.139 V 33.686 a 0.154,0.154 0 0 1 0.157,-0.14 h 2.019 c 0.035,0 0.122,0.018 0.14,0.053 l 5.622,8.025 v -7.938 a 0.16,0.16 0 0 1 0.174,-0.14 h 1.968 a 0.159,0.159 0 0 1 0.173,0.14 z" transform="translate(-1128.539,-27.019)"></path> 
                                    <path data-rel="T" d="m 1472.2,35.5 a 0.159,0.159 0 0 1 -0.174,0.139 h -2.333 v 9.958 a 0.16,0.16 0 0 1 -0.175,0.139 h -1.967 a 0.159,0.159 0 0 1 -0.173,-0.139 v -9.962 h -2.333 a 0.16,0.16 0 0 1 -0.175,-0.139 v -1.81 a 0.16,0.16 0 0 1 0.175,-0.14 h 6.98 a 0.159,0.159 0 0 1 0.174,0.14 z" transform="translate(-1179.832,-27.019)"></path> 
                                    <path data-rel="E" d="m 1515.235,35.757 v 2.75 h 4.787 a 0.153,0.153 0 0 1 0.156,0.14 v 1.932 a 0.153,0.153 0 0 1 -0.156,0.14 h -4.787 v 2.75 h 5.779 a 0.16,0.16 0 0 1 0.175,0.14 v 1.985 a 0.16,0.16 0 0 1 -0.175,0.139 h -7.937 a 0.16,0.16 0 0 1 -0.174,-0.139 V 33.686 a 0.16,0.16 0 0 1 0.174,-0.14 h 7.781 a 0.16,0.16 0 0 1 0.174,0.14 v 1.932 a 0.159,0.159 0 0 1 -0.174,0.139 z" transform="translate(-1218.52,-27.019)"></path> 
                                    <path data-rel="2" d="m 1573.416,44.945 a 0.158,0.158 0 0 1 -0.173,0.139 h -6.943 a 0.159,0.159 0 0 1 -0.173,-0.139 v -1.828 l 4.3,-5.936 a 1.851,1.851 0 0 0 0.418,-1.1 1.052,1.052 0 0 0 -1.079,-1.079 1.238,1.238 0 0 0 -1.306,1.306 0.161,0.161 0 0 1 -0.173,0.122 h -1.863 a 0.162,0.162 0 0 1 -0.174,-0.122 3.265,3.265 0 0 1 3.516,-3.569 3.378,3.378 0 0 1 3.5,3.221 4.107,4.107 0 0 1 -0.87,2.367 l -3.4,4.491 h 4.248 a 0.158,0.158 0 0 1 0.173,0.14 z" transform="translate(-1261.385,-26.371)"></path> 
                                    <path data-rel="." d="m 1614.124,85.491 a 1.236,1.236 0 0 1 -2.471,0 1.236,1.236 0 0 1 2.471,0" transform="translate(-1298.055,-67.874)"></path> 
                                    <path data-rel="0" d="m 1633.294,37.213 a 4.6,4.6 0 1 1 4.6,4.561 4.607,4.607 0 0 1 -4.6,-4.561 m 8.652,7.66 a 0.16,0.16 0 0 1 -0.175,0.139 h -7.85 a 0.16,0.16 0 0 1 -0.175,-0.139 V 42.9 a 0.159,0.159 0 0 1 0.175,-0.138 h 7.85 a 0.159,0.159 0 0 1 0.175,0.138 z m -6.336,-7.66 a 2.28,2.28 0 1 0 2.28,-2.315 2.284,2.284 0 0 0 -2.28,2.315" transform="translate(-1315.486,-26.299)"></path> 
                                    <path data-rel="x_stroke" d="m 847.484,1.414 4.416,4.416 -7.875,7.87 7.875,7.88 -4.416,4.42 -7.875,-7.875 V 9.289 Z" transform="translate(-676.237,-1.139)" fill="none" stroke-miterlimit="10" stroke-width="0.5"></path> 
                                    <path data-rel="x_fill" d="m 780.3,26.245 -4.416,-4.416 7.875,-7.875 -7.873,-7.874 4.414,-4.416 7.875,7.875 v 8.831 z" transform="translate(-624.913,-1.34)"></path>
                                </g> 
                            </svg>
                            </a>
                        </div>
                    </div>
                    <p>&copy; <?php echo date( 'Y' ); ?> <?php echo esc_html( $company ); ?> — <?php esc_html_e( 'All rights reserved.', 'blueprint' ); ?></p>
                </div>
            </div>
        </footer>
        </div>
    <?php wp_footer(); ?>
    </body>
</html>