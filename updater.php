<?php 

add_filter('site_transient_update_themes', function ($transient) {
    if (empty($transient->checked)) return $transient;

    $theme_slug = 'blueprint';
    $theme = wp_get_theme($theme_slug);
    $current_version = $theme->get('Version');

    // Check cache first (12 hours)
    $cache_key = 'blueprint_github_update_check';
    $cached = get_transient($cache_key);
    
    if ($cached !== false && !empty($cached)) {
        if (!empty($cached['new_version']) && version_compare($current_version, $cached['new_version'], '<')) {
            $transient->response[$theme_slug] = $cached;
        }
        return $transient;
    }

    // GitHub API endpoint for latest release
    $api_url = 'https://api.github.com/repos/brian-triplepro/' . $theme_slug . '/releases/latest';
    
    $response = wp_remote_get($api_url, [
        'timeout' => 10,
        'headers' => [
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url()
        ]
    ]);

    if (is_wp_error($response)) {
        set_transient($cache_key, [], HOUR_IN_SECONDS);
        return $transient;
    }

    $release_data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (empty($release_data['tag_name'])) {
        set_transient($cache_key, [], HOUR_IN_SECONDS);
        return $transient;
    }

    // Clean version number (remove 'v' prefix if present)
    $remote_version = ltrim($release_data['tag_name'], 'v');
    
    // Look for a properly packaged zip in release assets
    $package_url = null;
    if (!empty($release_data['assets']) && is_array($release_data['assets'])) {
        foreach ($release_data['assets'] as $asset) {
            if (preg_match('/\.zip$/i', $asset['name'])) {
                $package_url = $asset['browser_download_url'];
                break;
            }
        }
    }
    
    // Fallback to zipball_url
    if (empty($package_url) && !empty($release_data['zipball_url'])) {
        $package_url = $release_data['zipball_url'];
    }
    
    // Last resort: construct archive URL
    if (empty($package_url)) {
        $package_url = 'https://github.com/brian-triplepro/' . $theme_slug . '/archive/refs/tags/' . $release_data['tag_name'] . '.zip';
    }

    $update_data = [
        'theme'       => $theme_slug,
        'new_version' => $remote_version,
        'url'         => 'https://github.com/brian-triplepro/' . $theme_slug,
        'package'     => $package_url,
    ];

    set_transient($cache_key, $update_data, 12 * HOUR_IN_SECONDS);

    if (version_compare($current_version, $remote_version, '<')) {
        $transient->response[$theme_slug] = $update_data;
    }

    return $transient;
});

// Auto-update control
add_filter('auto_update_theme', function ($should_update, $item) {
    $theme_slug = 'blueprint';
    $slug = $item->slug ?? $item->theme ?? null;

    if ($slug !== $theme_slug) {
        return $should_update;
    }

    // Check setting (defaults to disabled for safety)
    return get_option('blueprint_auto_update', false);
}, 10, 2);

// Fix GitHub archive folder structure (blueprint-0.3/ -> blueprint/)
add_filter('upgrader_source_selection', function ($source, $remote_source, $upgrader, $hook_extra) {
    if (!isset($hook_extra['theme']) || $hook_extra['theme'] !== 'blueprint') {
        return $source;
    }

    global $wp_filesystem;
    
    $desired_name = 'blueprint';
    $source_name = basename($source);
    
    if ($source_name === $desired_name) {
        return $source;
    }
    
    $new_source = trailingslashit(dirname($source)) . $desired_name . '/';
    
    if ($wp_filesystem->move($source, $new_source, true)) {
        return $new_source;
    }
    
    return $source;
}, 10, 4);

// Clear cache when checking for updates
add_action('load-update-core.php', function() {
    delete_transient('blueprint_github_update_check');
});

add_action('load-themes.php', function() {
    delete_transient('blueprint_github_update_check');
});

// Admin notice with enable/disable link
add_action('admin_notices', function() {
    $screen = get_current_screen();
    if ($screen->id !== 'themes') {
        return;
    }
    
    if (!current_user_can('update_themes')) {
        return;
    }
    
    // Handle toggle action
    if (isset($_GET['blueprint_auto_update'])) {
        check_admin_referer('blueprint-auto-update');
        $action = $_GET['blueprint_auto_update'];
        
        if ($action === 'enable') {
            update_option('blueprint_auto_update', true);
            echo '<div class="notice notice-success is-dismissible"><p><strong>Blueprint:</strong> Automatische updates ingeschakeld</p></div>';
        } elseif ($action === 'disable') {
            update_option('blueprint_auto_update', false);
            echo '<div class="notice notice-success is-dismissible"><p><strong>Blueprint:</strong> Automatische updates uitgeschakeld</p></div>';
        }
        return;
    }
    
    $auto_update_enabled = get_option('blueprint_auto_update', false);
    
    if ($auto_update_enabled) {
        $url = wp_nonce_url(add_query_arg('blueprint_auto_update', 'disable'), 'blueprint-auto-update');
        $message = 'Automatische updates zijn <strong>ingeschakeld</strong>. <a href="' . esc_url($url) . '">Uitschakelen</a>';
    } else {
        $url = wp_nonce_url(add_query_arg('blueprint_auto_update', 'enable'), 'blueprint-auto-update');
        $message = 'Automatische updates zijn <strong>uitgeschakeld</strong>. <a href="' . esc_url($url) . '">Inschakelen</a>';
    }
    
    echo '<div class="notice notice-info"><p><strong>Blueprint Theme:</strong> ' . $message . '</p></div>';
});

?>