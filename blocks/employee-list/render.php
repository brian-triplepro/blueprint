<?php

  if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
    $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
    if ( file_exists( $screenshot_file ) ) {
          echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
          return;
    }
  }
    
  // Load ACF field-values and assign defaults.
  $title = get_field( 'title' );

  // fetch medewerkers posts and group by afdeling taxonomy
  $department = get_field( 'department' );
  $query_args = array(
      'post_type'      => 'medewerkers',
      'posts_per_page' => -1,
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
  );
  if ( $department ) {
      $query_args['tax_query'] = array(
          array(
              'taxonomy' => 'afdeling',
              'field'    => 'term_id',
              'terms'    => $department,
          ),
      );
  }
  $employees = get_posts( $query_args );

  $grouped = array();
  if ( ! empty( $employees ) ) {
      foreach ( $employees as $emp ) {
          $image_id = get_post_thumbnail_id( $emp->ID );

          $custom_name = get_field( 'employee_name', $emp->ID );
          $title_text = $custom_name ? $custom_name : get_the_title( $emp );
          $role = '';
          if ( strpos( $title_text, '-' ) !== false ) {
              list( $name_part, $role_part ) = array_map( 'trim', explode( '-', $title_text, 2 ) );
              $name = $name_part;
              $role = $role_part;
          } else {
              $name = $title_text;
          }

          $terms = get_the_terms( $emp->ID, 'afdeling' );
          $dept_name = '';
          $dept_slug = '';
          if ( $terms && ! is_wp_error( $terms ) ) {
              $dept_slug = $terms[0]->slug;
              $dept_name = $terms[0]->name;
          }

          $grouped[ $dept_name ][] = array(
              'image'    => $image_id,
              'name'     => $name,
              'role'     => $role,
              'dept_slug'=> $dept_slug,
              'url'      => get_permalink( $emp->ID ),
          );
      }
  }

  $colors = get_field( 'colors' ) ?: array();
  $background_color = $colors['bg_color'] ?? 'background';
  $title_color = $colors['title_color'] ?? 'dark';
  $text_color = $title_color;

  $card_style = get_field( 'card_style' ) ?: array();
  $card_background_color = $card_style['card_bg_color'] ?? 'primary';
  $card_text_color = $card_style['card_text_color'] ?? 'light';

  // CTA removed for employee-list block; no button output

  $rm_bp = get_field( 'rm_pb' );
?>

<?php
    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $background_color;
    $section_classes .= ' text-' . $text_color;
    if ( $rm_bp === true ) $section_classes .= ' pb-0';
?>

<section class="<?= esc_attr( $section_classes ); ?>">
  <div class="container">
     <?php if ( $title ) : ?>
          <h2 class="text-2xl leading-tight !mb-[50px] text-center  text-<?php echo esc_attr( $title_color ); ?>"><?php echo wp_kses_post( $title ); ?></h2>
      <?php endif; ?>
    <?php if ( ! empty( $grouped ) ) : ?>

      <?php foreach ( $grouped as $dept => $members ) :
            $heading = $dept !== '' ? $dept : __( 'Overige', 'blueprint' );
      ?>
        <h3 class="text-xl font-semibold text-center !mb-[40px]"><?php echo esc_html( $heading ); ?></h3>
        <div class="employee-grid flex flex-wrap justify-center gap-8">
          <?php foreach ( $members as $member ) :
            $image_id = $member['image'] ?? null;
            $name = $member['name'] ?? '';
            $role = $member['role'] ?? '';
          ?>
            <div class="employee-card text-center">
              <?php $bg_slug = $card_background_color ? $card_background_color : 'background'; ?>
            <div class="employee-photo w-[140px] h-[140px] rounded-full overflow-hidden mx-auto bg-<?php echo esc_attr( $bg_slug ); ?> flex items-center justify-center">
                <?php if ( $image_id ) : ?>
                  <?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'loading' => 'lazy', 'class'=>'w-full h-full object-cover' ) ); ?>
                <?php endif; ?>
              </div>
              <?php if ( $name ) : ?>
                <div class="employee-info mt-2">
                  <div class="name font-semibold text-sm uppercase"><?php echo esc_html( $name ); ?></div>
                  <?php if ( $role ) : ?>
                    <div class="role text-xs text-gray-600 uppercase"><?php echo esc_html( $role ); ?></div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
  <?php endif; ?>

 
  </div>
</section>

