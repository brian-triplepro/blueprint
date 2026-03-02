<?php
    
    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
        return;
    }

    $colors = get_field('colors') ?: array();
    $bg_color = $colors['bg_color'] ?? 'background';
    $color = $colors['text_color'] ?? 'dark';

    $content = get_field('content') ?: array();
    $title = $content['title'] ?? '';
    $text = $content['text'] ?? '';
    
    $image_group = get_field('image') ?: array();
    $image = $image_group['image'] ?? null;
    $image_position = $image_group['image_position'] ?? 'left';
    if ( $image_position === 'left' ) { $order = 1; } else { $order = 2; }

    $placeholder = get_template_directory_uri() . '/assets/images/placeholder.png';
    $image_url = $image ? wp_get_attachment_url( $image ) : false;
    $image_src = $image_url ? $image_url : $placeholder;
    $image_alt = $image ? get_post_meta( $image, '_wp_attachment_image_alt', true ) : 'Placeholder';
    
    $cta1 = get_field('cta_1') ?: array();
    $cta_style = $cta1['cta_style'] ?? 'primary';
    $cta_title = $cta1['cta_link']['title'] ?? '';
    $cta_url = $cta1['cta_link']['url'] ?? '';
    $cta_target = $cta1['cta_link']['target'] ?? '';

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $bg_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true )  $section_classes .= ' pb-0';
?>

<section class="<?= esc_attr( $section_classes ); ?>">
    <div class="container grid lg:grid-cols-[auto_1fr] items-center justify-center gap-[30px] lg:gap-[100px]">
        <div class="max-w-[350px] order-2 lg:order-<?php echo esc_attr( $order ); ?>">
             <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="w-full" />
        </div>
        <div class="order-1 lg:order-<?php echo esc_attr( $order === 1 ? 2 : 1 ); ?>">
            <?php if ( $title ) : ?>
                <h2 <?php echo acf_inline_text_editing_attrs( 'content.title' ); ?> class="mb-[30px]">
                    <?php echo esc_html( $title ); ?>
                </h2>
            <?php endif; ?>
            <?php if ( $text ) : ?>
                <div class="mb-[30px]">
                    <?php echo wp_kses_post( $text ); ?>
                </div>
            <?php endif; ?>
            <?php if ( $cta_title && $cta_url ) : ?>
                <div>
                    <a href="<?php echo esc_url($cta_url); ?>" class="btn <?php echo esc_attr($cta_style); ?>" target="<?php echo esc_attr($cta_target); ?>">
                        <?php echo esc_html($cta_title); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>