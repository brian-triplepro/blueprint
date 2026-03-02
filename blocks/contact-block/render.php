<?php

    // Check if this is a preview in the block inserter
    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
        return;
    }

    // Load ACF field-values
    $title = get_field('title') ?: 'Contact';
    $form_shortcode = get_field('form_shortcode') ?: '';
    $background_color = get_field('bg_color') ?: 'background';
    $text_color = get_field('text_color') ?: 'dark';
    $form_background_color = get_field('form_bg_color') ?: 'white';
    $form_text_color = get_field('form_text_color') ?: 'dark';
    $info_title = get_field('info_title') ?: 'Contactgegevens';
    $hours_title = get_field('hours_title') ?: 'Openingstijden';
    
    // Get data from options page
    $address_details = get_field('address_details', 'option') ?: array();
    $company_name = $address_details['companyname'] ?? '';
    $street = trim( ( $address_details['street'] ?? '' ) . ' ' . ( $address_details['house_number'] ?? '' ) );
    $zipcode = $address_details['zipcode'] ?? '';
    $city = $address_details['city'] ?? '';
    
    $contact_details = get_field('contact_details', 'option') ?: array();
    $phone = $contact_details['phone_number'] ?? '';
    $whatsapp = $contact_details['whatsapp'] ?? '';
    $email = $contact_details['emailadres'] ?? '';
    
    $opening_hours = get_field('opening_hours', 'option') ?: array();
?>

<?php
    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
?>

<section class="<?= esc_attr( $section_classes ); ?>">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-16">
        <div>
            <?php if ( $form_shortcode ) : ?>
                <div class="bg-<?php echo esc_attr( $form_background_color ); ?> text-<?php echo esc_attr( $form_text_color ); ?> p-8 rounded-lg shadow">
                    <?php if ( $title ) : ?>
                        <h2 class="mb-6 font-semibold"><?php echo esc_html( $title ); ?></h2>
                    <?php endif; ?>
                    <?php echo do_shortcode( $form_shortcode ); ?>
                </div>
            <?php else : ?>
                <div class="p-8 bg-gray-100 rounded-lg text-center">
                    <p>Voeg een formulier shortcode toe in de blok instellingen.</p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <?php if ( $company_name || $street || $city || $phone || $email ) : ?>
                <div  class="mb-[50px]">
                    <?php if ( $info_title ) : ?>
                        <h2 class="font-semibold mb-4"><?php echo esc_html( $info_title ); ?></h2>
                    <?php endif; ?>
                    <div class="space-y-1">
                        <?php if ( $company_name ) : ?>
                            <p><?php echo esc_html( $company_name ); ?><br>
                        <?php endif; ?>
                        <?php if ( $street ) : ?>
                            <?php echo esc_html( $street ); ?><br>
                        <?php endif; ?>
                        <?php if ( $zipcode || $city ) : ?>
                            <?php echo esc_html( trim( $zipcode . ' ' . $city ) ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ( $phone || $whatsapp || $email ) : ?>
                        <p>
                            <?php if ( $phone ) : ?>
                                Bel ons <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $phone ) ); ?>" class="underline"><?php echo esc_html( $phone ); ?></a><br>
                            <?php endif; ?>
                            <?php if ( $whatsapp ) : ?>
                                App ons <a href="https://wa.me/<?php echo esc_attr( str_replace( array( ' ', '+', '-' ), '', $whatsapp ) ); ?>" class="underline"><?php echo esc_html( $whatsapp ); ?></a><br>
                            <?php endif; ?>
                            <?php if ( $email ) : ?>
                                Mail ons <a href="mailto:<?php echo esc_attr( $email ); ?>" class="underline"><?php echo esc_html( $email ); ?></a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $opening_hours ) ) : ?>
                <div>
                    <?php if ( $hours_title ) : ?>
                        <h2 class="text-xl font-semibold mb-4"><?php echo esc_html( $hours_title ); ?></h2>
                    <?php endif; ?>
                    <div class="space-y-1">
                        <?php foreach ( $opening_hours as $hours_item ) : 
                            $day = $hours_item['day'] ?? '';
                            $hours = $hours_item['hours'] ?? '';
                            if ( $day || $hours ) :
                        ?>
                            <p><?php echo esc_html( $day . ' ' . $hours ); ?></p>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
