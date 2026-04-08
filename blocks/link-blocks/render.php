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

    $content = get_field('content') ?: array();
    $title = $content['title'] ?? '';
    $text = $content['text'] ?? '';

    $item_style = get_field('item_style') ?: array();
    $layout = $item_style['layout'] ?? 'grid';
    $columns = $item_style['columns'] ?? '4';
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
    <div class="container">
        <?php if ( $title ) : ?>
            <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('content.title'); ?> class="!mb-[20px]">
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( $text ) : ?>
            <div class="text mb-[40px]" <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('content.text'); ?>>
                <div>
                <?php echo wp_kses_post( $text ); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $items ) : ?>
            <?php
              $grid_classes = 'grid gap-[30px] ';
              if ( $layout === 'list' ) {
                  $grid_classes .= 'grid-cols-1';
              } else {
                  $col_map = array( '2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4' );
                  $grid_classes .= 'grid-cols-1 ' . ( $col_map[ $columns ] ?? 'md:grid-cols-2 lg:grid-cols-3' );
              }
            ?>
            <div class="<?php echo esc_attr( $grid_classes ); ?>">
                <?php foreach ( $items as $item ) :
                    $image_id = $item['image'] ?? null;
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

                    $text_group = $item['text'] ?? array();
                    $item_title = $text_group['title'] ?? '';
                    $item_excerpt = $text_group['excerpt'] ?? '';

                    $cta_link = $item['cta_link'] ?? array();
                    $cta_url = is_array($cta_link) ? ($cta_link['url'] ?? '') : '';
                    $cta_text = is_array($cta_link) ? ($cta_link['title'] ?? '') : '';
                    $cta_target = is_array($cta_link) ? ($cta_link['target'] ?: '_self') : '_self';

                    $is_list = $layout === 'list';
                ?>
                    <div class="item bg-<?php echo esc_attr( $item_bg_color ); ?> text-<?php echo esc_attr( $item_text_color ); ?> overflow-hidden rounded-[var(--border-radius-block)] <?php echo $is_list && $image_id ? 'flex flex-col md:flex-row' : ''; ?> <?php echo ! $image_id ? 'p-[30px]' : ''; ?>">
                        <?php if ( $image_id ) : ?>
                            <div class="item-image <?php echo $is_list ? 'md:w-1/3 md:flex-shrink-0 relative' : ''; ?>">
                                <?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'class' => 'w-full object-cover ' . ( $is_list ? 'md:absolute md:inset-0 md:h-full' : 'aspect-[16/9]' ), 'loading' => 'lazy' ) ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="<?php echo $image_id ? 'p-[30px]' : ''; ?> <?php echo $is_list ? 'flex flex-col justify-center' : ''; ?>">
                        <?php if ( $icon_value ) : ?>
                            <div class="item-icon mb-[20px] inline-flex items-center rounded-[10px] justify-center w-[50px] h-[50px] bg-<?php echo esc_attr( $item_icon_bg_color ); ?> text-<?php echo esc_attr( $item_icon_text_color ); ?>">
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
                                <div class="item-excerpt mb-[20px] [&_ul]:list-disc [&_ul]:py-5 [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5">
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>