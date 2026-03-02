<?php

    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
        return;
    }


    $use_options_address = get_field('use_options_address');
    

    $map_settings = get_field('map_settings') ?: array();
    $height = $map_settings['height'] ?? 450;
    $zoom = $map_settings['zoom'] ?? 14;
    $language = $map_settings['language'] ?? 'nl';
    

    if ( $use_options_address === true ) {

        $address_options = get_field('address_details', 'option') ?: array();
        $street = trim( ( $address_options['street'] ?? '' ) . ' ' . ( $address_options['house_number'] ?? '' ) );
        $city = $address_options['city'] ?? '';
        $zipcode = $address_options['zipcode'] ?? '';
        $country = 'Nederland'; 
    } else {
        $address_details = get_field('address_details') ?: array();
        $street = $address_details['street'] ?? '';
        $city = $address_details['city'] ?? '';
        $zipcode = $address_details['zipcode'] ?? '';
        $country = $address_details['country'] ?? '';
    }
    

    $address_parts = array_filter( array( $street, $zipcode, $city, $country ) );
    $address_query = urlencode( implode( ' ', $address_parts ) );

    $map_url = sprintf(
        'https://maps.google.com/maps?width=100%%&height=100%%&hl=%s&q=%s&t=&z=%d&ie=UTF8&iwloc=B&output=embed',
        esc_attr( $language ),
        $address_query,
        intval( $zoom )
    );

    $section_classes = 'blueprint-' . basename( __DIR__ );

?>

<section class="<?= esc_attr( $section_classes ); ?> p-0" >
    <div class="google-maps-wrapper">
        <?php if ( ! empty( $address_query ) ) : ?>
            <iframe 
                id="map-canvas" 
                class="map_part" 
                width="100%" 
                height="<?php echo intval( $height ); ?>" 
                frameborder="0" 
                scrolling="no" 
                marginheight="0" 
                marginwidth="0" 
                src="<?php echo esc_url( $map_url ); ?>"
                loading="lazy"
                title="Google Maps - <?php echo esc_attr( implode( ' ', $address_parts ) ); ?>">
            </iframe>
        <?php else : ?>
            <div class="google-maps-notice">
                <p>Geen adres gegevens gevonden. Voeg adres gegevens toe in de blok instellingen of op de opties pagina.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
