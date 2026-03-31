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
  $general = get_field( 'general', 'option' ) ?: array();
  $border_radius_img = isset( $general['border_radius_img'] ) ? intval( $general['border_radius_img'] ) : 30;
  $img_shape = get_field( 'img_shape' ) ?: 'default';
  $img_radius_style = $img_shape === 'circle' ? 'border-radius: 50%' : 'border-radius: ' . $border_radius_img . 'px';

  // fetch medewerkers from options repeater and group by department
  $department_filter = get_field( 'department' );
  $repeater = get_field( 'medewerkers', 'option' ) ?: array();

  $grouped = array();
  foreach ( $repeater as $row ) {
      $dept = $row['employee_department'] ?? '';
      if ( $department_filter && $dept !== $department_filter ) {
          continue;
      }
      $grouped[ $dept ][] = array(
          'image'    => $row['employee_thumbnail']['id'] ?? null,
          'name'     => $row['employee_name'] ?? '',
          'role'     => $row['employee_function'] ?? '',
      );
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
          <h2 class="text-2xl leading-tight !mb-[50px] text-center text-<?php echo esc_attr( $title_color ); ?>"><?php echo wp_kses_post( $title ); ?></h2>
      <?php endif; ?>
    <?php if ( ! empty( $grouped ) ) : ?>

      <?php foreach ( $grouped as $dept => $members ) :
            $heading = $dept !== '' ? $dept : __( 'Overige', 'blueprint' );
      ?>
        <h3 class="text-xl font-semibold text-center !mt-[50px] !mb-[30px]"><?php echo esc_html( $heading ); ?></h3>
        <div class="employee-grid flex flex-wrap justify-center gap-8">
          <?php foreach ( $members as $member ) :
            $image_id = $member['image'] ?? null;
            $name = $member['name'] ?? '';
            $role = $member['role'] ?? '';
          ?>
            <div class="employee-card text-center">
              <?php $bg_slug = $card_background_color ? $card_background_color : 'background'; ?>
            <div class="employee-photo w-[140px] h-[140px] overflow-hidden mx-auto bg-<?php echo esc_attr( $bg_slug ); ?> flex items-center justify-center" style="<?php echo esc_attr( $img_radius_style ); ?>">
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

