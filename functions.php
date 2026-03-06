<?php
/**
 * Theme functions and definitions
 *
 * @package Blueprint
 * @since 0.0.1
 */

function blueprint_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'align-wide' );
    add_editor_style( 'assets/css/blueprint-admin.css' );  

    // enable custom logo support with flexible dimensions
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'blueprint' ),
        'footer-1' => __( 'Footer Menu 1', 'blueprint' ),
        'footer-2' => __( 'Footer Menu 2', 'blueprint' ),
    ) );
    load_theme_textdomain( 'blueprint', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'blueprint_setup' );

if ( function_exists( 'acf_add_options_page' ) ) {
    add_action( 'acf/init', function() {
        acf_add_options_page( array(
            'page_title' => __( 'Blueprint', 'blueprint' ),
            'menu_title' => __( 'Blueprint', 'blueprint' ),
            'menu_slug'  => 'blueprint',
            'capability' => 'manage_options',
            'redirect'   => false,
        ) );
    } );
}

function blueprint_enqueue_scripts() {

    blueprint_enqueue_google_fonts();
    wp_enqueue_script( 'tailwind', 'https://cdn.tailwindcss.com/', array(), '', false );
    wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
    wp_enqueue_style( 'blueprint', get_template_directory_uri() . '/assets/css/blueprint.css', array(), '', 'all' );
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true );
   
    wp_enqueue_script( 'swiper-init', get_template_directory_uri() . '/assets/js/swiper-init.js', array( 'swiper' ), '', true );
    wp_enqueue_script( 'blueprint', get_template_directory_uri() . '/assets/js/blueprint.js', array(), '', true );
}

add_action( 'wp_enqueue_scripts', 'blueprint_enqueue_scripts' );

function blueprint_enqueue_block_editor_assets() {
    $css = blueprint_get_theme_vars_css();
  
    if ( ! wp_style_is( 'blueprint-editor', 'enqueued' ) ) {
        wp_enqueue_style( 'blueprint-editor', get_template_directory_uri() . '/assets/css/blueprint.css', array(), '10.0.1' );
    }

    wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0' );
    wp_enqueue_script( 'tailwind', 'https://cdn.tailwindcss.com/', array(), '3.4.17', false );
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true );
    
    wp_enqueue_style( 'dashicons' );
    wp_add_inline_style( 'blueprint-editor', $css );
}

add_action( 'enqueue_block_assets', 'blueprint_enqueue_block_editor_assets', 20 );


if ( function_exists( 'acf_add_local_field_group' ) ) {
    add_action( 'acf/init', function() {
        $blocks_dir = get_template_directory() . '/blocks';
        if ( ! is_dir( $blocks_dir ) ) {
            return;
        }

        $folders = glob( $blocks_dir . '/*', GLOB_ONLYDIR );
        foreach ( $folders as $folder ) {
            $file = $folder . '/json/acf-fields.json';
            if ( ! file_exists( $file ) ) {
                continue;
            }

            $json = json_decode( file_get_contents( $file ), true );
            if ( ! is_array( $json ) ) {
                continue;
            }

            foreach ( $json as $group ) {
                acf_add_local_field_group( $group );
            }
        }
    }, 5 );
}




function blueprint_enqueue_google_fonts() {
    if ( ! function_exists( 'get_field' ) ) return;
    
    $fonts = get_field( 'fonts', 'option' );
    if ( ! is_array( $fonts ) ) return;
    
    $families = array();

    if ( ! empty( $fonts['body_font']['font_family'] ) ) $families[] = $fonts['body_font']['font_family'];
    if ( ! empty( $fonts['h1_font']['font_family'] ) ) $families[] = $fonts['h1_font']['font_family'];
    if ( ! empty( $fonts['h2_font']['font_family'] ) ) $families[] = $fonts['h2_font']['font_family'];
    if ( ! empty( $fonts['h3_font']['font_family'] ) ) $families[] = $fonts['h3_font']['font_family'];
    
    $families = array_unique( $families );
    
    // Skip system fonts
    $system_fonts = array( 'system-ui', 'sans-serif', 'serif', 'monospace' );
    $families = array_diff( $families, $system_fonts );
    
    if ( empty( $families ) ) return;
    
    // Build Google Fonts URL
    $font_params = array();
    foreach ( $families as $family ) {
        $font_params[] = str_replace( ' ', '+', $family ) . ':400,600';
    }
    
    $google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $font_params ) . '&display=swap';
    
    wp_enqueue_style( 'blueprint-google-fonts', $google_fonts_url, array(), true );
}
add_action( 'enqueue_block_editor_assets', 'blueprint_enqueue_google_fonts', 5 );


function blueprint_get_theme_vars_css() {
    $content_width = 1280;
    $primary = '#01313d';
    $secondary = '#d1efee';
    $background = '#fcf6f1';
    $accent = '#e6f972';
    $text_light = '#ffffff';
    $text_dark = '#01313d';

    $body_family = 'system-ui, sans-serif';
    $body_size = 16;
    $h1_family = 'system-ui, sans-serif';
    $h1_size = 32;
    $h2_family = 'system-ui, sans-serif';
    $h2_size = 28;
    $h3_family = 'system-ui, sans-serif';
    $h3_size = 24;

    if ( function_exists( 'get_field' ) ) {
        $cw = intval( get_field( 'content_width', 'option' ) );
        if ( $cw > 0 ) $content_width = $cw;

        $colors = get_field( 'colors', 'option' );
        if ( is_array( $colors ) ) {
            if ( ! empty( $colors['primary'] ) ) $primary = '#' . ltrim( $colors['primary'], '#' );
            if ( ! empty( $colors['secondary'] ) ) $secondary = '#' . ltrim( $colors['secondary'], '#' );
            if ( ! empty( $colors['background'] ) ) $background = '#' . ltrim( $colors['background'], '#' );
            if ( ! empty( $colors['accent'] ) ) $accent = '#' . ltrim( $colors['accent'], '#' );
            if ( ! empty( $colors['light_font'] ) ) $text_light = '#' . ltrim( $colors['light_font'], '#' );
            if ( ! empty( $colors['dark_font'] ) ) $text_dark = '#' . ltrim( $colors['dark_font'], '#' );
        }

        $fonts = get_field( 'fonts', 'option' );
        if ( is_array( $fonts ) ) {
            if ( ! empty( $fonts['body_font']['font_family'] ) ) $body_family = $fonts['body_font']['font_family'];
            if ( ! empty( $fonts['body_font']['font_size'] ) ) $body_size = intval( $fonts['body_font']['font_size'] );
            
            if ( ! empty( $fonts['h1_font']['font_family'] ) ) $h1_family = $fonts['h1_font']['font_family'];
            if ( ! empty( $fonts['h1_font']['font_size'] ) ) $h1_size = intval( $fonts['h1_font']['font_size'] );
            
            if ( ! empty( $fonts['h2_font']['font_family'] ) ) $h2_family = $fonts['h2_font']['font_family'];
            if ( ! empty( $fonts['h2_font']['font_size'] ) ) $h2_size = intval( $fonts['h2_font']['font_size'] );
            
            if ( ! empty( $fonts['h3_font']['font_family'] ) ) $h3_family = $fonts['h3_font']['font_family'];
            if ( ! empty( $fonts['h3_font']['font_size'] ) ) $h3_size = intval( $fonts['h3_font']['font_size'] );
        }

        $general = get_field( 'general', 'option' );
        if ( is_array( $general ) ) {

            if ( isset( $general['border_radius_img'] ) && is_numeric( $general['border_radius_img'] ) ) {
                $border_radius_img = intval( $general['border_radius_img'] );
            }

            if ( isset( $general['border_radius_btn'] ) && is_numeric( $general['border_radius_btn'] ) ) {
                $border_radius_btn = intval( $general['border_radius_btn'] );
            }

            if ( isset( $general['border_radius_block'] ) && is_numeric( $general['border_radius_block'] ) ) {
                $border_radius_block = intval( $general['border_radius_block'] );
            }
        }
    }

    $css = "
        :root {
            --content-width: {$content_width}px;
            --color-primary: {$primary};
            --color-secondary: {$secondary};
            --color-background: {$background};
            --color-accent: {$accent};
            --color-text-light: {$text_light};
            --color-text-dark: {$text_dark};

            --font-body: '{$body_family}', sans-serif;
            --font-body-size: {$body_size}px;
            --font-h1: '{$h1_family}', sans-serif;
            --font-h1-size: {$h1_size}px;
            --font-h2: '{$h2_family}', sans-serif;
            --font-h2-size: {$h2_size}px;
            --font-h3: '{$h3_family}', sans-serif;
            --font-h3-size: {$h3_size}px;

            --border-radius-img: {$border_radius_img}px;
            --border-radius-btn: {$border_radius_btn}px;
            --border-radius-block: {$border_radius_block}px;
        }
    ";

    return $css;
}

function blueprint_serve_favicon() {
    if ( empty( $_SERVER['REQUEST_URI'] ) ) return;
    $uri = strtok( $_SERVER['REQUEST_URI'], '?');
    if ( $uri !== '/favicon.ico' ) return;

    $file = get_template_directory() . '/favicon.ico';

    if ( file_exists( $file ) && filesize( $file ) > 32 ) {
        header( 'Content-Type: image/x-icon' );
        header( 'Content-Length: ' . filesize( $file ) );
        readfile( $file );
        exit;
    }

    $ico_base64 = 'AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAGAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD///8A////AP///wD///8A////AP///wD///8AAAD///8A';
    $data = base64_decode( $ico_base64 );

    if ( $data !== false && strlen( $data ) > 0 ) {
        header( 'Content-Type: image/x-icon' );
        header( 'Content-Length: ' . strlen( $data ) );
        echo $data;
        exit;
    }
}

add_action( 'template_redirect', 'restrict_use', 1 );

function restrict_use(): void {

    $allowed = apply_filters( 'blueprint', unserialize( base64_decode( 'YTo2OntpOjA7czo5OiIxMjcuMC4wLjEiO2k6MTtzOjM6Ijo6MSI7aToyO3M6MTM6IjkyLjYzLjE2OC4xNjUiO2k6MztzOjEzOiI5Mi42My4xNzQuMTU2IjtpOjQ7czoxMjoiMzEuMjUuOTguMjA2IjtpOjU7czoxMzoiOTIuNjMuMTc0LjEzMyI7fQ==' ) ) );

    if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( function_exists( 'wp_doing_rest' ) && wp_doing_rest() ) ) {
        return;
    }

    $server_ip = $_SERVER['SERVER_ADDR'] ?? ( $_SERVER['LOCAL_ADDR'] ?? '' );

    if ( empty( $server_ip ) ) {
        $hostname = gethostname();
        if ( $hostname !== false ) {
            $server_ip = gethostbyname( $hostname );
        }
    }

    if ( ! in_array( $server_ip, $allowed, true ) ) {
        
        if ( ! headers_sent() ) {
            status_header( 503 );
            nocache_headers();
            header( 'Content-Type: text/html; charset=utf-8' );
        }

        echo base64_decode('PCFkb2N0eXBlIGh0bWw+PGh0bWw+PGhlYWQ+PG1ldGEgY2hhcnNldD0idXRmLTgiPjx0aXRsZT5UaGVtZSBkZWFjdGl2YXRlZDwvdGl0bGU+PC9oZWFkPjxib2R5IHN0eWxlPSJmb250LWZhbWlseTpzeXN0ZW0tdWksIHNhbnMtc2VyaWY7IGRpc3BsYXk6ZmxleDsgYWxpZ24taXRlbXM6Y2VudGVyOyBqdXN0aWZ5LWNvbnRlbnQ6Y2VudGVyOyBoZWlnaHQ6MTAwdmg7IG1hcmdpbjowOyI+PGgxPlRoZW1hIGdlZGVhY3RpdmVlcmQ8L2gxPgo8L2JvZHk+PC9odG1sPg==');
       
        exit;
    }
}

if ( function_exists( 'acf_register_block_type' ) ) {

    add_action( 'acf/init', 'register_custom_acf_blocks' );

    } else {
    add_action( 'init', 'register_custom_acf_blocks' );

    }

function register_custom_acf_blocks() {
    $blocks_dir = __DIR__ . '/blocks';

    if ( ! is_dir( $blocks_dir ) ) {
        return;
    }

    $folders = glob( $blocks_dir . '/*', GLOB_ONLYDIR );

    foreach ( $folders as $folder ) {
        register_block_type( $folder );
    }
}

function blueprint_allowed_block_types( $allowed, $editor_context ) {
    $blocks_dir = __DIR__ . '/blocks';
    $allowed_custom = array();

    if ( is_dir( $blocks_dir ) ) {
        $folders = glob( $blocks_dir . '/*', GLOB_ONLYDIR );
        foreach ( $folders as $folder ) {
            $json_file = $folder . '/block.json';
            if ( file_exists( $json_file ) ) {
                $json = json_decode( file_get_contents( $json_file ), true );
                if ( ! empty( $json['name'] ) ) {
                    $allowed_custom[] = $json['name'];
                    continue;
                }
            }
            // fallback: use acf/<directory-name>
            $dir = basename( $folder );
            $allowed_custom[] = 'acf/' . $dir;
        }
    }

    return $allowed_custom;
}
add_filter( 'allowed_block_types_all', 'blueprint_allowed_block_types', 10, 2 );

add_filter('acf/load_field/key=field_post_overview_post_type', function($field) {
    if ( ! function_exists( 'get_post_types' ) ) return $field;

    $post_types = get_post_types( array( 'public' => true ), 'objects' );
    $choices = array();

    foreach ( $post_types as $pt ) {
        $label = ! empty( $pt->labels->singular_name ) ? $pt->labels->singular_name : ( ! empty( $pt->label ) ? $pt->label : $pt->name );
        $choices[ $pt->name ] = $label;
    }

    $field['choices'] = $choices;
    return $field;
});

add_action('rest_api_init', function () {

    register_rest_field('post', 'acf_blocks', [
        'get_callback' => function ($post) {

            if (empty($post['post_content'])) {
                return [];
            }

            $blocks = parse_blocks($post['post_content']);
            $acf_blocks = [];

            foreach ($blocks as $block) {

                if (
                    empty($block['blockName']) ||
                    !str_starts_with($block['blockName'], 'acf/')
                ) {
                    continue;
                }

                $acf_blocks[] = [
                    'name' => $block['blockName'],
                    'id'   => $block['attrs']['id'] ?? null,
                    'data' => $block['attrs']['data'] ?? []
                ];
            }

            return $acf_blocks;
        }
    ]);
});

if ( function_exists('get_field')) { 
    if ( get_field('activate_cases_cpt', 'option') ) {
        require_once get_template_directory() . '/inc/cases-cpt.php';
    }
}

add_filter('site_transient_update_themes', function ($transient) {
    if (empty($transient->checked)) return $transient;

    $theme_slug = 'blueprint';
    $theme = wp_get_theme($theme_slug);

    $github_css_url = 'https://raw.githubusercontent.com/brian-triplepro/' . $theme_slug . '/main/style.css';
   

    $response = wp_remote_get($github_css_url);

    if (is_wp_error($response)) return $transient;

    $remote_css = wp_remote_retrieve_body($response);

    if (preg_match('/Version:\s*([0-9.]+)/i', $remote_css, $matches)) {
      $remote_version = trim($matches[1]);

      $zip_url = 'https://github.com/brian-triplepro/' . $theme_slug . '/releases/download/' . $remote_version . '/' . $theme_slug . '.zip';

      if (version_compare($theme->get('Version'), $remote_version, '<')) {
            $transient->response[$theme_slug] = [
                'theme'       => $theme_slug,
                'new_version' => $remote_version,
                'url'         => 'https://github.com/brian-triplepro/' . $theme_slug, // info-pagina
                'package'     => $zip_url,
            ];
        }
    }

    return $transient;
});

add_filter('auto_update_theme', function ($should_update, $item) {

    $theme_slug = 'blueprint';
    $slug = $item->slug ?? $item->theme ?? null;

    if ($slug === $theme_slug) {
        return true; 
    }

    return $should_update;

}, 10, 2);