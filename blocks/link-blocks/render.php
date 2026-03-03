<?php
    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
        return;
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

    $content = get_field('content') ?: array();
    $title = $content['title'] ?? '';
    $text = $content['text'] ?? '';

    $item_style = get_field('item_style') ?: array();
    $cta_style = $item_style['cta_style'] ?? 'primary';
    $item_bg_color = $item_style['bg_color'] ?? 'primary';
    $item_text_color = $item_style['text_color'] ?? 'light';

    $items = get_field('items') ?: array();

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $bg_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true )  $section_classes .= ' pb-0';

?>

<section class="<?= esc_attr( $section_classes ); ?>">
    <div class="container">
        <?php if ( $title ) : ?>
            <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('content.title'); ?> class="mb-[20px]">
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( $text ) : ?>
            <div class="text">
                <div>
                <?php echo wp_kses_post( $text ); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $items ) : ?>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(270px,1fr))] gap-[30px]">
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

                            // fallback to using raw class name if nothing else matches
                            $icon_type  = 'class';
                            $icon_value = $raw_icon;
                        }
                    }

                    $text_group = $item['text'] ?? array();
                    $item_title = $text_group['title'] ?? '';
                    $item_excerpt = $text_group['excerpt'] ?? '';

                    $cta_link = $item['cta_link'] ?? array();
                    $cta_url = is_array($cta_link) ? ($cta_link['url'] ?? '') : '';
                    $cta_text = is_array($cta_link) ? ($cta_link['title'] ?? '') : '';
                    $cta_target = is_array($cta_link) ? ($cta_link['target'] ?: '_self') : '_self';
                ?>
                    <div class="item bg-<?php echo esc_attr( $item_bg_color ); ?> text-<?php echo esc_attr( $item_text_color ); ?> p-[30px] rounded-[var(--border-radius-block)]">
                        <?php if ( $icon_value ) : ?>
                            <div class="item-icon mb-[20px]">
                                <?php if ( $icon_type === 'media_library' || $icon_type === 'url' || $icon_type === 'svg_file' ) : ?>
                                    <img src="<?php echo esc_url( $icon_value ); ?>" alt="" height="24" width="24" style="border-radius: 0;" />
                                <?php elseif ( $icon_type === 'dashicons' ) : ?>
                                    <span class="dashicons <?php echo esc_attr( $icon_value ); ?>"></span>
                                <?php else : ?>
                                    <span class="<?php echo esc_attr( $icon_value ); ?>"></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="item-content">
                            <?php if ( $item_title ) : ?>
                                <h3 class="mb-[20px]"><?php echo esc_html( $item_title ); ?></h3>
                            <?php endif; ?>

                            <?php if ( $item_excerpt ) : ?>
                                <div class="item-excerpt mb-[20px]">
                                    <?php echo wp_kses_post( $item_excerpt ); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $cta_text && $cta_url ) : ?>
                                <a href="<?php echo esc_url( $cta_url ); ?>" class="btn <?php echo esc_attr( $cta_style ); ?>" target="<?php echo esc_attr( $cta_target ); ?>">
                                    <?php echo esc_html( $cta_text ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>