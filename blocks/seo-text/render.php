<?php
    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/screenshot.png';
        $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/screenshot.png';
        if ( file_exists( $screenshot_file ) ) {
            echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
            return;
        }
    }

    $colors = get_field('colors') ?: array();
    $background_color = $colors['bg_color'] ?? 'background';
    $text_color       = $colors['text_color'] ?? 'dark';
    
    $content = get_field('content') ?: array();
    $columns = (int) ( $content['columns'] ?? 2 ); 
    $title   = $content['title'] ?? '';
    $text    = $content['text'] ?? '';
    
    $cta1 = get_field('cta_1') ?: array();
    $cta1_style  = $cta1['cta_style'] ?? 'primary';
    $cta1_title  = $cta1['cta_link']['title'] ?? '';
    $cta1_url    = $cta1['cta_link']['url'] ?? '';
    $cta1_target = $cta1['cta_link']['target'] ?? '';

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true ) {
        $section_classes .= ' pb-0';
    }

?>

<section class="<?= esc_attr( $section_classes ); ?>" style="--seo-columns: <?php echo esc_attr( $columns ); ?>;">
    <div class="container">
        <?php if ( $title ) : ?>
            <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('content.title'); ?>>
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( $text ) : ?>
            <div class="text" <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('content.text'); ?>>
                <?php echo wpautop( $text ); ?>
            </div>
        <?php endif; ?>
            <?php if ( $cta1_title && $cta1_url ) : ?>                
                <div class="flex items-center justify-center mt-6">
                <a href="<?php echo esc_url( $cta1_url ); ?>" class="btn <?php echo esc_attr( $cta1_style ); ?>" target="<?php echo esc_attr( $cta1_target ); ?>">
                    <?php echo esc_html( $cta1_title ); ?>
                </a>
                    </div>        
            <?php endif; ?>
            
    </div>
</section>