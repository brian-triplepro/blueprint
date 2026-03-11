<?php
  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }

    add_filter( 'acf/format_value/type=icon_picker', function( $value ) {
        if ( ! is_string( $value ) || $value === '' ) {
            return $value;
        }
        
        if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
            return array( 'type' => 'url', 'value' => $value );
        }

        if ( preg_match( '/^[0-9]+$/', $value ) ) {
            return array( 'type' => 'media_library', 'value' => $value );
        }

        return array( 'type' => 'dashicons', 'value' => $value );
    }, 5 );

    $colors = get_field('colors') ?: array();
    $bg_color = $colors['bg_color'] ?? 'background';
    $text_color = $colors['text_color'] ?? 'dark';
    $title_color = $colors['title_color'] ?? 'dark';
    $subtitle_color = $colors['subtitle_color'] ?? 'dark';

    // block heading fields
    $block_title = get_field('block_title') ?: '';
    $block_subtitle = get_field('block_subtitle') ?: '';

    $item_style = get_field('item_style') ?: array();
    $cta_style = $item_style['cta_style'] ?? 'primary';
    $item_bg_color = $item_style['bg_color'] ?? 'primary';
    $item_text_color = $item_style['text_color'] ?? 'light';
    $item_icon_bg_color = $item_style['icon_bg_color'] ?? $item_text_color;
    $item_icon_text_color = $item_style['icon_text_color'] ?? $item_bg_color;

    $items = get_field('items') ?: array();

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $bg_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true )  $section_classes .= ' pb-0';

?>

<section class="<?= esc_attr( $section_classes ); ?>">
    <div class="container text-center">
        <?php if ( $block_title || $block_subtitle ) : ?>
             <?php if ( $block_subtitle ) : ?>
                <p class="mb-2 text-<?php echo esc_attr( $subtitle_color ); ?>"><?php echo esc_html( $block_subtitle ); ?></p>
            <?php endif; ?>
            <?php if ( $block_title ) : ?>
                <h2 class="text-2xl !mb-[30px] text-<?php echo esc_attr( $title_color ); ?>"><?php echo esc_html( $block_title ); ?></h2>
            <?php endif; ?>
    <?php endif; ?>
    <?php if ( $items ) : ?>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(300px,1fr))] gap-[30px]">
                <?php foreach ( $items as $item ) :
                    $icon_field = $item['icon'] ?? '';

                    $icon_type  = '';
                    $icon_value = '';

                    if ( is_array( $icon_field ) ) {
                        $icon_type  = $icon_field['type'] ?? '';
                        $icon_value = $icon_field['value'] ?? '';

                        if ( is_array( $icon_value ) && isset( $icon_value['url'] ) ) {
                            $icon_value = $icon_value['url'];
                        }


                    } else {
                        $raw_icon = (string) $icon_field;

                        if ( filter_var( $raw_icon, FILTER_VALIDATE_URL ) ) {
                            $icon_type  = 'url';
                            $icon_value = $raw_icon;
                        } elseif ( preg_match( '/^[0-9]+$/', $raw_icon ) ) {
                            $icon_type  = 'media_library';
                            $icon_value = wp_get_attachment_url( intval( $raw_icon ) ) ?: $raw_icon;
                        } elseif ( strpos( $raw_icon, 'dashicons' ) !== false || strpos( $raw_icon, 'dashicon' ) !== false ) {
                            $icon_type  = 'dashicons';
                            $icon_value = $raw_icon;
                        } else {
                            $icon_type  = 'class';
                            $icon_value = $raw_icon;
                        }
                    }

                    // simplified fields
                    $label = $item['label'] ?? '';
                    $link = $item['link'] ?? array();
                    $url = is_array($link) ? ($link['url'] ?? '') : '';
                    $target = is_array($link) ? ($link['target'] ?: '_self') : '_self';
                ?>
                    <a href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>" class="service-card flex items-center gap-3 p-4 bg-white rounded-lg shadow-md hover:shadow-sm text-<?php echo esc_attr( $item_text_color ); ?>">
                        <?php if ( $icon_value ) : ?>
                            <span class="service-icon inline-flex items-center justify-center w-7 h-7 rounded-[5px] bg-<?php echo esc_attr( $item_icon_bg_color ); ?> text-<?php echo esc_attr( $item_icon_text_color ); ?>">
                                <?php if ( $icon_type === 'media_library' || $icon_type === 'url' || $icon_type === 'svg_file' ) : ?>
                                    <img src="<?php echo esc_url( $icon_value ); ?>" alt="" class="w-6 h-6" />
                                <?php elseif ( $icon_type === 'dashicons' ) : ?>
                                    <span class="dashicons <?php echo esc_attr( $icon_value ); ?>"></span>
                                <?php else : ?>
                                    <span class="<?php echo esc_attr( $icon_value ); ?>"></span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                        <span class="service-label font-semibold"><?php echo esc_html( $label ); ?></span>
                        <span class="service-arrow ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M13 5l7 7-7 7M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>