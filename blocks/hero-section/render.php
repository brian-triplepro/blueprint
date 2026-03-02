<?php
/**
 * Block: Header Homepage
 */

// load hero type early so we can use it in preview
$hero_type = get_field('hero_type');
// preview placeholder
if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    if ( $hero_type ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
    } else {
        // depth hero: show simple colour block
        echo '<div style="width:100%;height:400px;background:#eee;">&nbsp;</div>';
    }
    return;
}

// Get block fields
$hero_type = (int) get_field('hero_type') === 1;
$content = get_field('content');
$background_overlay = get_field('background_overlay');

$text_color = $content['text_color'] ?? 'dark';
$title = $content['title'] ?? '';
$text = $content['text'] ?? '';

$image_id = $background_overlay['image'] ?? null;
$background_color = $background_overlay['background_color'] ?? 'white';
$overlay_color = $background_overlay['image_overlay_color'] ?? 'primary';
$overlay_opacity = $background_overlay['overlay_opacity'] ?? 0.5;

$cta1 = get_field('cta_1') ?: array();
$cta1_style = $cta1['cta_style'] ?? 'primary';
$cta1_title = $cta1['cta_link']['title'] ?? '';
$cta1_url = $cta1['cta_link']['url'] ?? '';
$cta1_target = $cta1['cta_link']['target'] ?? '';

$cta2 = get_field('cta_2') ?: array();
$cta2_style = $cta2['cta_style'] ?? 'primary';
$cta2_title = $cta2['cta_link']['title'] ?? '';
$cta2_url = $cta2['cta_link']['url'] ?? '';
$cta2_target = $cta2['cta_link']['target'] ?? '';

$image_url = '';
if ($image_id) {
    $image_url = wp_get_attachment_image_url($image_id, 'full');
}

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= $hero_type ? ' hero-home' : ' hero-depth';
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true )  $section_classes .= ' pb-0';

?>

<section class="<?= esc_attr( $section_classes ); ?> relative overflow-hidden flex items-center">
    <?php if ( $hero_type && $image_url ) : ?>
        <img class="absolute inset-0 w-full h-full" src="<?php echo esc_url($image_url); ?>" alt="" style="object-fit: cover; object-position: center;" fetchpriority="high" /> 
    <?php endif; ?>
    <?php if ( $hero_type && $overlay_color ) : ?>
        <div class="absolute inset-0 bg-<?php echo esc_attr($overlay_color); ?>" style="opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
    <?php endif; ?>

    <div class="container mt-[100px] relative z-2">
      <?php if ($title) : ?>
                <h1 class="mb-[30px]"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>

        <?php if ( $text ) : ?>
            <div class="intro mb-[30px]" <?php if ( function_exists( 'acf_inline_text_editing_attrs' ) ) echo acf_inline_text_editing_attrs( 'intro_tekst' ); ?>>
                <?php echo wp_kses_post( $text ); ?>
            </div>
        <?php endif; ?>
        

        <?php if ( $cta1_title || $cta2_title ) : ?>
            <div class="flex flex-wrap gap-[20px]">
               <?php if ($cta1_title && $cta1_url) : ?>
                    <a href="<?php echo esc_url($cta1_url); ?>" class="btn <?php echo esc_attr($cta1_style); ?>"<?php echo $cta1_target ? ' target="' . esc_attr( $cta1_target ) . '"' : ''; ?>>
                        <?php echo esc_html($cta1_title); ?>
                    </a>
                <?php endif; ?>

                <?php if ($cta2_title && $cta2_url) : ?>
                        <a href="<?php echo esc_url($cta2_url); ?>" class="btn <?php echo esc_attr($cta2_style); ?>"<?php echo $cta2_target ? ' target="' . esc_attr( $cta2_target ) . '"' : ''; ?>>
                            <?php echo esc_html($cta2_title); ?>
                        </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
