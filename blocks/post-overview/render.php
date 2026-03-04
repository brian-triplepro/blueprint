<?php

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }

  $remove_bottom_padding = get_field( 'remove_bottom_padding' );

  $title = get_field( 'title' );
  $intro_text = get_field( 'intro_text' );

  $colors = get_field( 'colors' ) ?: array();
  $background_color = $colors['bg_color'] ?? 'white';
  $title_color = $colors['title_color'] ?? 'dark';
  $text_color = $colors['text_color'] ?? 'dark';

  $card_colors = get_field( 'card_colors' ) ?: array();
  $card_background_color = $card_colors['card_background_color'] ?? 'secondary';
  $card_text_color = $card_colors['card_text_color'] ?? 'dark';

  $settings = get_field( 'settings' ) ?: array();
  $posts_per_page = $settings['posts_per_page'] ?? 3;
  $layout = $settings['layout'] ?? 'grid';
  $post_type = $settings['post_type'] ?? 'post';

  $cta = get_field( 'cta_button' ) ?: array();
  $cta_text_custom = $cta['text'] ?? '';
  $cta_link = $cta['link'] ?? array();
  $cta_text = !empty($cta_text_custom) ? $cta_text_custom : (!empty($cta_link['title']) ? $cta_link['title'] : '');
  $cta_url = !empty($cta_link['url']) ? $cta_link['url'] : '';
  $cta_target = !empty($cta_link['target']) ? $cta_link['target'] : '_self';
  $cta_style = $cta['style'] ?? 'primary-inv';
  
  // Query posts
  $args = array(
    'post_type' => $post_type,
    'posts_per_page' => $posts_per_page,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
  );
  
  $query = new WP_Query( $args );
?>

<?php
    $section_classes = 'blueprint-' . basename( __DIR__ );
    if ( $remove_bottom_padding === true ) {
        $section_classes .= ' pb-0';
    }
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
?>

<section class="<?= esc_attr( $section_classes ); ?>">
  <div class="container">
    <div class="blog-overview-header flex flex-col md:flex-row md:justify-between md:items-center mb-[20px]">
      <div class="space-y-2">
        <?php if ( $title ) : ?>
          <h2 class="text-2xl font-semibold text-<?php echo esc_attr( $title_color ); ?>"><?php echo esc_html( $title ); ?></h2>
        <?php endif; ?>
        <?php if ( $intro_text ) : ?>
          <p class="text-<?php echo esc_attr( $text_color ); ?>"><?php echo nl2br( esc_html( $intro_text ) ); ?></p>
        <?php endif; ?>
      </div>
      <?php if ( $cta_text && $cta_url ) : ?>
        <div>
          <a href="<?php echo esc_url( $cta_url ); ?>" class="btn <?php echo esc_attr( $cta_style ); ?>" target="<?php echo esc_attr( $cta_target ); ?>">
            <?php echo esc_html( $cta_text ); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
    
    <?php if ( $query->have_posts() ) : ?>
      <div class="blog-posts grid <?php echo $layout === 'list' ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'; ?> gap-8">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
          <article class="blog-post bg-<?php echo esc_attr( $card_background_color ); ?> text-<?php echo esc_attr( $card_text_color ); ?> rounded-[30px] overflow-hidden transition-transform transition-shadow duration-300 h-full <?php echo $layout==='list' ? 'flex flex-col md:flex-row' : 'flex flex-col'; ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="blog-post-image relative overflow-hidden min-h-[300px] aspect-video <?php echo $layout==='list' ? 'md:w-1/3' : ''; ?>">
                <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                  <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'class'=>'w-full h-full object-cover' ) ); ?>
                </a>
              </div>
            <?php endif; ?>
            
            <div class="blog-post-content p-6 flex flex-col flex-grow <?php echo $layout==='list' ? 'md:w-2/3' : ''; ?>">
              <h3 class="blog-post-title m-0 text-xl font-bold leading-tight">
                <a href="<?php the_permalink(); ?>" class="text-<?php echo esc_attr( $card_text_color ); ?> no-underline transition-opacity duration-200 hover:opacity-80">
                  <?php the_title(); ?>
                </a>
              </h3>
              
              <div class="blog-post-excerpt flex-grow leading-relaxed mb-[20px]">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '…' ); ?>
              </div>
              
              <a href="<?php the_permalink(); ?>" class="blog-post-link font-semibold underline transition-opacity duration-200 hover:opacity-70 text-<?php echo esc_attr( $card_text_color ); ?>">
                <?php esc_html_e( 'Lees meer', 'blueprint' ); ?>
              </a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p><?php esc_html_e( 'Geen posts gevonden.', 'blueprint' ); ?></p>
    <?php endif; ?>
    
    <?php wp_reset_postdata(); ?>
  </div>
</section>