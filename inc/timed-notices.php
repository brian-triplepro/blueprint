<?php
/**
 * Timed popups
 * Reads the `timed_notices` repeater from the Blueprint options page and renders the
 * first active popup (based on startdate/enddate). Also enqueues and localizes
 * the front-end script that opens/closes the popup.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Return a stable key for a popup row (based on dates and content).
 *
 * @param array $p Popup row from ACF.
 * @return string
 */
function blueprint_timed_popup_get_key( $p ) {
    $dates = $p['dates'] ?? array();
    $start = $dates['startdate'] ?? '';
    $end   = $dates['enddate'] ?? '';

    // Support 'notices' (new), 'melding' (Dutch) and 'popup' (legacy)
    $title = $p['notices']['title'] ?? $p['melding']['title'] ?? $p['popup']['title'] ?? $p['title'] ?? '';
    $text  = $p['notices']['text']  ?? $p['melding']['text']  ?? $p['popup']['text']  ?? $p['text']  ?? '';

    $active_flag = isset( $p['active'] ) ? (int) $p['active'] : 1;

    $bg = $p['notices']['bg_color'] ?? $p['melding']['bg_color'] ?? '';
    $fg = $p['notices']['fg_color'] ?? $p['melding']['fg_color'] ?? '';

    $btn = $p['notices']['button'] ?? $p['melding']['button'] ?? array();
    $btn_text  = $btn['text']  ?? '';
    // Prefer link array url, then link string, then url field
    $btn_url   = is_array( $btn['link'] ?? '' ) ? ( $btn['link']['url'] ?? '' ) : ( $btn['link'] ?? ( $btn['url'] ?? '' ) );
    $btn_style = $btn['style'] ?? ''; 

    return md5( implode( '|', array( $start, $end, (string) $title, (string) $text, (string) $active_flag, (string) $bg, (string) $fg, (string) $btn_text, (string) $btn_url, (string) $btn_style ) ) );
}

add_action( 'wp_enqueue_scripts', 'blueprint_enqueue_timed_popup_assets' );
function blueprint_enqueue_timed_popup_assets() {
    if ( ! function_exists( 'get_field' ) ) return;

    $popups = get_field( 'timed_notices', 'option' );
    if ( empty( $popups ) || ! is_array( $popups ) ) return;

    $today = date_i18n( 'Ymd' );
    $active = null;

    foreach ( $popups as $p ) {
        // Skip if explicitly deactivated
        if ( isset( $p['active'] ) && ! $p['active'] ) continue;

        $dates = $p['dates'] ?? null;
        $start = $dates['startdate'] ?? '';
        $end = $dates['enddate'] ?? '';

        if ( empty( $start ) && empty( $end ) ) {
            $active = $p; break;
        }

        if ( ! empty( $start ) && ! empty( $end ) ) {
            if ( $start <= $today && $today <= $end ) { $active = $p; break; }
        } elseif ( ! empty( $start ) && empty( $end ) ) {
            if ( $start <= $today ) { $active = $p; break; }
        } elseif ( empty( $start ) && ! empty( $end ) ) {
            if ( $today <= $end ) { $active = $p; break; }
        }
    }

    if ( ! $active ) return;

    // register/enqueue the script that controls the popup
    if ( ! wp_script_is( 'timed-notices', 'registered' ) ) {
        wp_register_script( 'timed-notices', get_template_directory_uri() . '/assets/js/timed-notices.js', array(), '', true );
    }
    wp_enqueue_script( 'timed-notices' );

    // Provide a stable key for localStorage (so each popup can be tracked persistently)
    $key = blueprint_timed_popup_get_key( $active );
    $data = array( 'key' => $key, 'active' => isset( $active['active'] ) ? (int) $active['active'] : 1 );
    wp_add_inline_script( 'timed-notices', 'window.blueprintTimedNotices = ' . wp_json_encode( $data ) . ';', 'before' );

}

add_action( 'wp_footer', 'blueprint_render_timed_popup', 20 );
function blueprint_render_timed_popup() {
    if ( ! function_exists( 'get_field' ) ) return;

    $popups = get_field( 'timed_notices', 'option' );
    if ( empty( $popups ) || ! is_array( $popups ) ) return;

    $today = date_i18n( 'Ymd' );
    $active = null;

    foreach ( $popups as $p ) {
        // Skip if explicitly deactivated
        if ( isset( $p['active'] ) && ! $p['active'] ) continue;

        $dates = $p['dates'] ?? null;
        $start = $dates['startdate'] ?? '';
        $end = $dates['enddate'] ?? '';

        if ( empty( $start ) && empty( $end ) ) {
            $active = $p; break;
        }

        if ( ! empty( $start ) && ! empty( $end ) ) {
            if ( $start <= $today && $today <= $end ) { $active = $p; break; }
        } elseif ( ! empty( $start ) && empty( $end ) ) {
            if ( $start <= $today ) { $active = $p; break; }
        } elseif ( empty( $start ) && ! empty( $end ) ) {
            if ( $today <= $end ) { $active = $p; break; }
        }
    }

    if ( ! $active ) return;

    $title = $active['notices']['title'] ?? $active['melding']['title'] ?? $active['popup']['title'] ?? $active['title'] ?? '';
    $text  = $active['notices']['text']  ?? $active['melding']['text']  ?? $active['popup']['text']  ?? $active['text']  ?? '';
    $key   = blueprint_timed_popup_get_key( $active );

    // Render markup expected by the JS/CSS
    ?>
    <?php
    // Pull style and button values
    $bg = $active['notices']['bg_color'] ?? $active['melding']['bg_color'] ?? $active['bg_color'] ?? '';
    $fg = $active['notices']['fg_color'] ?? $active['melding']['fg_color'] ?? $active['fg_color'] ?? '';

    $btn = $active['notices']['button'] ?? $active['melding']['button'] ?? $active['button'] ?? array();
    $btn_text = $btn['text'] ?? '';
    $btn_style = $btn['style'] ?? '';

    // Resolve link field: can be an array (link field) or a simple URL string
    $btn_url = '';
    if ( isset( $btn['link'] ) && $btn['link'] ) {
        if ( is_array( $btn['link'] ) ) {
            $btn_url = $btn['link']['url'] ?? '';
        } else {
            $btn_url = $btn['link'];
        }
    }

    $inner_style = '';
    if ( $bg ) $inner_style .= 'background:' . esc_attr( $bg ) . ';';
    if ( $fg ) $inner_style .= 'color:' . esc_attr( $fg ) . ';';
    ?>

    <div id="timed-popup" class="timed-popup" aria-hidden="true" role="dialog" aria-label="<?php echo esc_attr( $title ? strip_tags( $title ) : 'Timed popup' ); ?>" data-tp-key="<?php echo esc_attr( $key ); ?>">
        <div class="timed-popup__overlay" data-tp-close></div>
        <div class="timed-popup__panel" role="document">
            <div class="timed-popup__inner" <?php echo $inner_style ? 'style="' . esc_attr( $inner_style ) . '"' : ''; ?>>
                <button class="timed-popup__close" aria-label="<?php esc_attr_e( 'Close', 'blueprint' ); ?>" data-tp-close>&times;</button>
                <?php if ( $title ) : ?>
                    <h3 class="timed-popup__title"><?php echo wp_kses_post( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $text ) : ?>
                    <div class="timed-popup__notice" <?php echo $fg ? 'style="color:' . esc_attr( $fg ) . ';"' : ''; ?>><?php echo apply_filters( 'the_content', $text ); ?></div>
                <?php endif; ?>

                <?php if ( $btn_text && $btn_url ) : ?>
                    <p class="timed-popup__cta">
                        <a class="btn <?php echo esc_attr( $btn_style ? $btn_style : 'primary' ); ?>" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_text ); ?></a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
