<?php

wp_enqueue_style( 'dashicons' );

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }
  
$rm_bp = get_field( 'rm_bp' );
$title = get_field( 'title' ) ?: '';
$colors = get_field( 'colors' ) ?: array();
$step_styling = get_field( 'step_styling' ) ?: array();

$bg_color = $colors['bg_color'] ?? 'white';
$title_color = $colors['title_color'] ?? 'dark';

$use_icons = $step_styling['use_icons'] ?? false;
$step_number_border_radius = $step_styling['step_number_border_radius'] ?? '50';
$step_number_bg_color = $step_styling['step_number_bg_color'] ?? 'accent';
$line_color = $step_styling['line_color'] ?? 'accent';
$step_number_text_color = $step_styling['step_number_text_color'] ?? 'white';

$steps = get_field( 'steps' ) ?: array();

// Convert border-radius value
$border_radius_value = $step_number_border_radius === '50' ? '50%' : $step_number_border_radius . 'px';

$section_classes = 'blueprint-' . basename( __DIR__ ) . ' ' . 'block__' . basename( __DIR__ );
$section_classes .= ' bg-' . $bg_color;
$section_classes .= ' text-' . $title_color;
if ( $rm_bp === true ) $section_classes .= ' pb-0';

// Inline style for border-radius and colors
$bg_color_value = match( $step_number_bg_color ) {
    'primary' => 'var(--color-primary, #333)',
    'secondary' => 'var(--color-secondary, #666)',
    'accent' => 'var(--color-accent, #007bff)',
    default => 'var(--color-accent, #007bff)'
};

$line_color_value = match( $line_color ) {
    'primary' => 'var(--color-primary, #333)',
    'secondary' => 'var(--color-secondary, #666)',
    'accent' => 'var(--color-accent, #007bff)',
    default => 'var(--color-accent, #007bff)'
};

$step_number_text_color_value = match( $step_number_text_color ) {
    'light' => 'var(--color-text-light, #f0f0f0)',
    'dark' => 'var(--color-text-dark, #333)',
    'white' => '#ffffff',
    default => '#ffffff'
};

$section_style = '--steps-border-radius: ' . esc_attr( $border_radius_value ) . '; --step-bg-color: ' . esc_attr( $bg_color_value ) . '; --step-line-color: ' . esc_attr( $line_color_value ) . '; --step-text-color: ' . esc_attr( $step_number_text_color_value ) . ';';
?>

<section class="blueprint-stappenplan block__stappenplan px-5 py-[100px] bg-<?php echo esc_attr( $bg_color ); ?> text-<?php echo esc_attr( $title_color ); ?><?php echo $rm_bp === true ? ' pb-0' : ''; ?>" style="<?= $section_style; ?>">
  <div class="container">
    <?php if ( ! empty( $steps ) ) : ?>
      <div class="w-full">
        <?php if ( $title ) : ?>
          <h2 <?php if ( function_exists('acf_inline_text_editing_attrs') ) echo acf_inline_text_editing_attrs('title'); ?> class="!mb-[40px] font-bold leading-tight text-center text-<?php echo esc_attr( $title_color ); ?>">
            <?php echo esc_html( $title ); ?>
          </h2>
        <?php endif; ?>

        <div class="flex flex-wrap gap-5 relative items-start justify-between">
          <?php foreach ( $steps as $index => $step ) : 
            $step_number = $index + 1;
            $step_title = $step['title'] ?? '';
            $step_description = $step['description'] ?? '';
            $step_icon = $step['icon'] ?? '';
            $is_last = $index === count( $steps ) - 1;
          ?>
            <div class="flex flex-col relative flex-1 w-full min-w-[200px] items-center<?php echo !$is_last ? ' step-item-with-line' : ''; ?>">
              <div class="w-20 h-20 md:w-[80px] md:h-[80px] flex items-center justify-center text-2xl md:text-[2.5em] font-black mb-5 flex-shrink-0 relative z-10" style="background-color: var(--step-bg-color, var(--color-accent, #007bff)); border-radius: var(--steps-border-radius, 50%); color: var(--step-text-color, #ffffff);">
                <?php if ( $use_icons && $step_icon ) : ?>
                  <?php 
                    $icon_type = is_array( $step_icon ) ? ( $step_icon['type'] ?? null ) : null;
                    $icon_value = is_array( $step_icon ) ? ( $step_icon['value'] ?? $step_icon ) : $step_icon;
                    
                    // Handle dashicons
                    if ( $icon_type === 'dashicons' || ( is_string( $icon_value ) && strpos( $icon_value, 'dashicons-' ) === 0 ) ) {
                      echo '<span class="dashicon ' . esc_attr( $icon_value ) . '"></span>';
                    } 
                    // Handle media library or URL (image)
                    elseif ( $icon_type === 'media_library' || $icon_type === 'url' || is_numeric( $icon_value ) ) {
                      if ( is_numeric( $icon_value ) ) {
                        $image_url = wp_get_attachment_image_url( $icon_value, 'thumbnail' );
                      } else {
                        $image_url = $icon_value;
                      }
                      if ( ! empty( $image_url ) ) {
                        echo '<img src="' . esc_url( $image_url ) . '" alt="Step icon" style="width: 100%; height: 100%; object-fit: contain;">';
                      } else {
                        echo esc_html( $step_number );
                      }
                    }
                    else {
                      echo esc_html( $step_number );
                    }
                  ?>
                <?php else : ?>
                  <?php echo esc_html( $step_number ); ?>
                <?php endif; ?>
              </div>
              <div class="flex-1 text-center">
                <?php if ( $step_title ) : ?>
                  <h3 class="m-0 mb-3 text-lg md:text-[1.1em] font-bold leading-tight"><?php echo esc_html( $step_title ); ?></h3>
                <?php endif; ?>
                <?php if ( $step_description ) : ?>
                  <p class="m-0 text-base md:text-[0.95em] leading-relaxed opacity-80"><?php echo wp_kses_post( nl2br( $step_description ) ); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<style>
  .dashicon {
    font-family: dashicons !important;
    display: inline-block;
    line-height: 1;
    font-weight: 400;
    font-style: normal;
    speak: none;
    text-decoration: inherit;
    text-transform: none;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    font-size: 1em;
    width: auto;
    height: auto;
  }

  @media (min-width: 1024px) {
    .step-item-with-line {
      position: relative;
    }

    .step-item-with-line::after {
      content: "";
      position: absolute;
      top: 40px;
      left: 50%;
      width: 100%;
      height: 2px;
      background: var(--step-line-color, var(--color-accent, #007bff));
      z-index: 0;
      pointer-events: none;
    }
  }
</style>
