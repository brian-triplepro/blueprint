<?php

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
$bg_color = $colors['bg_color'] ?? 'white';
$title_color = $colors['title_color'] ?? 'dark';
$step_number_border_radius = $colors['step_number_border_radius'] ?? '50';
$step_number_bg_color = $colors['step_number_bg_color'] ?? 'accent';
$line_color = $colors['line_color'] ?? 'accent';
$step_number_text_color = $colors['step_number_text_color'] ?? 'white';
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
            $is_last = $index === count( $steps ) - 1;
          ?>
            <div class="flex flex-col relative flex-1 w-full min-w-[200px] items-center<?php echo !$is_last ? ' step-item-with-line' : ''; ?>">
              <div class="w-20 h-20 md:w-[80px] md:h-[80px] flex items-center justify-center text-2xl md:text-[2.5em] font-black mb-5 flex-shrink-0 relative z-10" style="background-color: var(--step-bg-color, var(--color-accent, #007bff)); border-radius: var(--steps-border-radius, 50%); color: var(--step-text-color, #ffffff);">
                <?php echo esc_html( $step_number ); ?>
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
  @media (min-width: 1024px) {
    .step-item-with-line::after {
      content: "";
      position: absolute;
      top: 40px;
      left: 100%;
      width: calc(100% + 20px);
      height: 2px;
      background: var(--step-line-color, var(--color-accent, #007bff));
      z-index: 0;
      pointer-events: none;
      transform: translateX(-50%);
    }
  }
</style>
