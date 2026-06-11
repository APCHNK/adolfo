<?php
/**
 * Boney M feat Liz Mitchell — Theme functions
 */

if (!defined('ABSPATH')) exit;

require_once get_template_directory() . '/inc/image-helper.php';

// -----------------------------------------------------------------------------
// Theme Setup
// -----------------------------------------------------------------------------
add_action('after_setup_theme', function () {
    add_theme_support('menus');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menu('primary', 'Primary Menu');
    register_nav_menu('header', 'Header Menu');
});

// -----------------------------------------------------------------------------
// MIME Types (SVG + MP3)
// -----------------------------------------------------------------------------
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['mp3'] = 'audio/mpeg';
    return $mimes;
});

// -----------------------------------------------------------------------------
// ACF Options Page
// -----------------------------------------------------------------------------
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'menu_slug'       => 'options',
        'page_title'      => 'bnmusic',
        'position'        => 25,
        'icon_url'        => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgICAgICAgICAgPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMC42MjU5IDguMDIwNjNINy44OTIwNkM3LjgxMTg5IDcuMTYyNzkgNy4yNzc0IDYuNzMzODYgNi4yODg2MSA2LjczMzg2QzUuOTE4NCA2LjcwOTc0IDUuNTQ4ODggNi43OTAzMSA1LjIyMjMxIDYuOTY2MzZDNS4xMTQ4IDcuMDI0MTcgNS4wMjQ2NyA3LjEwOTY0IDQuOTYxMjUgNy4yMTM5NEM0Ljg5NzgyIDcuMzE4MjQgNC44NjM0IDcuNDM3NTkgNC44NjE1NCA3LjU1OTY0QzQuODYxMDQgNy42NzgxNyA0Ljg5MjQgNy43OTQ2NSA0Ljk1MjM0IDcuODk2OUM1LjAxMjI5IDcuOTk5MTYgNS4wOTg2IDguMDgzNDMgNS4yMDIyNyA4LjE0MDg5QzUuNTI4MDIgOC4zMjgxOCA1Ljg4MTAyIDguNDYzNDMgNi4yNDg1MiA4LjU0MTc2QzcuMjk3OTggOC43NzcxNiA4LjMyOTQ0IDkuMDg2NiA5LjMzNTE3IDkuNDY3NzVDOS44MTg1NiA5LjY2OTQ5IDEwLjIyODIgMTAuMDE1IDEwLjUwODYgMTAuNDU3NEMxMC43ODkgMTAuODk5OSAxMC45MjY1IDExLjQxNzggMTAuOTAyNSAxMS45NDExQzEwLjkyMzMgMTIuMzkyNSAxMC44Mzc2IDEyLjg0MjYgMTAuNjUyNCAxMy4yNTQ4QzEwLjQ2NzIgMTMuNjY3IDEwLjE4NzYgMTQuMDI5OSA5LjgzNjI1IDE0LjMxNDJDOS4xMjUzOCAxNC44OTE0IDguMDMzNyAxNS4xODAxIDYuNTYxMiAxNS4xODAxQzUuMDMzOTEgMTUuMTgwMSAzLjg0NzM1IDE0Ljg3MTQgMy4wMTM1NiAxNC4yNTQxQzIuNjExOTEgMTMuOTY5NyAyLjI4NDU4IDEzLjU5MjkgMi4wNTkyMiAxMy4xNTU1QzEuODMzODYgMTIuNzE4IDEuNzE3MDkgMTIuMjMyNyAxLjcxODc3IDExLjc0MDZINC42OTMxN0M0LjY4ODk3IDExLjkzMSA0LjczMTc2IDEyLjExOTUgNC44MTc3NiAxMi4yODk0QzQuOTAzNzYgMTIuNDU5MyA1LjAzMDMyIDEyLjYwNTQgNS4xODYyMyAxMi43MTQ3QzUuNTk5NTkgMTIuOTg3OSA2LjA5MDc3IDEzLjExODggNi41ODUyNSAxMy4wODc1QzcuNTc1MzggMTMuMDg3NSA4LjA3MjQ1IDEyLjc3ODkgOC4wNzI0NSAxMi4xNjU2QzguMDc4MjYgMTIuMDU1NSA4LjA1NDExIDExLjk0NTkgOC4wMDI1OCAxMS44NDg1QzcuOTUxMDUgMTEuNzUxMSA3Ljg3NDA2IDExLjY2OTUgNy43Nzk4MiAxMS42MTI0QzcuNDYzNzEgMTEuNDM1IDcuMTIxMzYgMTEuMzA5MiA2Ljc2NTY0IDExLjIzOTZDNS43Njg5NyAxMS4wMjU4IDQuNzkzNTkgMTAuNzIyNSAzLjg1MTM2IDEwLjMzMzZDMi42Njg4MSA5Ljc0ODM1IDIuMDc5NTQgOC44NjY0NiAyLjA3OTU0IDcuNjk5OTRDMi4wNjc1NiA3LjI4OTE0IDIuMTUzNjcgNi44ODEzOSAyLjMzMDc0IDYuNTEwNTFDMi41MDc4MSA2LjEzOTY0IDIuNzcwNzUgNS44MTYzMSAzLjA5Nzc0IDUuNTY3MzVDMy43NzExOSA1LjAzNDIgNC44NTc1MyA0Ljc2NTYyIDYuMzQ0NzMgNC43NjU2MkM3Ljc2Mzc5IDQuNzY1NjIgOC44MTgwNiA1LjAzNDIgOS40OTk1MiA1LjU2NzM1QzkuODUyNzggNS44Njg1IDEwLjEzNjQgNi4yNDI3NCAxMC4zMzA5IDYuNjY0MjNDMTAuNTI1NCA3LjA4NTczIDEwLjYyNjEgNy41NDQ0MSAxMC42MjU5IDguMDA4NjFWOC4wMjA2M1oiLz4KICAgICAgICAgICAgPHBhdGggZD0iTTE4LjI2NjYgMTMuMTgzM0gxMS43Nzg4VjE1LjE5NTRIMTguMjY2NlYxMy4xODMzWiIvPgogICAgICAgICAgICA8L3N2Zz4=',
        'show_in_graphql' => true,
    ));

    acf_add_options_page(array(
        'menu_slug'  => 'header-settings',
        'page_title' => 'Header Settings',
        'menu_title' => 'Header',
        'icon_url'   => 'dashicons-arrow-up-alt',
        'position'   => 60,
    ));

    acf_add_options_page(array(
        'menu_slug'  => 'footer-settings',
        'page_title' => 'Footer Settings',
        'menu_title' => 'Footer',
        'icon_url'   => 'dashicons-arrow-down-alt',
        'position'   => 61,
    ));

    acf_add_options_page(array(
        'menu_slug'  => 'schedule-settings',
        'page_title' => 'Schedule Settings',
        'menu_title' => 'Schedule',
        'icon_url'   => 'dashicons-calendar-alt',
        'position'   => 62,
    ));
}

// -----------------------------------------------------------------------------
// Contact Form 7 tweaks
// -----------------------------------------------------------------------------
add_filter('show_admin_bar', '__return_false');

add_filter('wpcf7_autop_or_not', '__return_false');
add_filter('wpcf7_load_css', '__return_false');
add_filter('wpcf7_form_autocomplete', function () { return 'off'; });

// -----------------------------------------------------------------------------
// Custom Page Template: Frontpage
// -----------------------------------------------------------------------------
add_filter('theme_page_templates', function ($templates) {
    $templates['frontpage-template'] = 'Frontpage';
    return $templates;
});

// -----------------------------------------------------------------------------
// Remove block theme styles and unused scripts
// -----------------------------------------------------------------------------
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('wp-img-auto-sizes-contain');
    wp_dequeue_style('wp-emoji-styles');

    // Remove jQuery on frontend (not needed)
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
}, 100);

// Remove emoji scripts and styles
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
add_action('init', function () {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
});

// -----------------------------------------------------------------------------
// Enqueue Assets
// -----------------------------------------------------------------------------
add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    // Main CSS (compiled by webpack)
    wp_enqueue_style(
        'theme-main',
        $theme_uri . '/dist/main.css',
        array(),
        filemtime($theme_dir . '/dist/main.css')
    );

    // Main JS (compiled by webpack)
    wp_enqueue_script(
        'theme-main',
        $theme_uri . '/dist/main.js',
        array(),
        filemtime($theme_dir . '/dist/main.js'),
        true
    );
});

// -----------------------------------------------------------------------------
// Cache and security headers
// -----------------------------------------------------------------------------
add_action('send_headers', function () {
    if (is_admin()) return;
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
});

// Add cache headers to theme static assets
add_filter('style_loader_tag', function ($tag, $handle) {
    return $tag;
}, 10, 2);

// Page caching for non-logged-in users
add_action('template_redirect', function () {
    if (is_user_logged_in() || is_admin()) return;
    header('Cache-Control: public, max-age=300, s-maxage=3600');
});

// -----------------------------------------------------------------------------
// Preload LCP image (banner) for faster rendering
// -----------------------------------------------------------------------------
add_action('wp_head', function () {
    if (!is_front_page()) return;
    $banner = get_field('banner');
    $image  = $banner['image'] ?? null;
    if (!$image) return;

    $webp = adolfo_webp_sibling($image['url']);
    $href = $webp ?: $image['url'];
    $type = $webp ? ' type="image/webp"' : '';

    $srcset = '';
    $sizes  = '';
    if (!empty($image['id'])) {
        $auto_srcset = wp_get_attachment_image_srcset($image['id'], 'full');
        if ($auto_srcset) {
            if ($webp) {
                $parts = array_map('trim', explode(',', $auto_srcset));
                $mapped = array();
                foreach ($parts as $part) {
                    if (preg_match('/^(\S+)(\s+.+)?$/', $part, $m)) {
                        $c = adolfo_webp_sibling($m[1]);
                        if ($c) $mapped[] = $c . ($m[2] ?? '');
                    }
                }
                if (count($mapped) === count($parts)) $srcset = implode(', ', $mapped);
            } else {
                $srcset = $auto_srcset;
            }
            $sizes = wp_get_attachment_image_sizes($image['id'], 'full') ?: '100vw';
        }
    }

    printf(
        '<link rel="preload" as="image" href="%s"%s%s%s fetchpriority="high">' . "\n",
        esc_url($href),
        $type,
        $srcset ? ' imagesrcset="' . esc_attr($srcset) . '"' : '',
        $sizes  ? ' imagesizes="'  . esc_attr($sizes)  . '"' : ''
    );
}, 1);

// -----------------------------------------------------------------------------
// Instagram Feed (via Social Feed Gallery plugin)
// -----------------------------------------------------------------------------
function theme_get_instagram_photos($count = 8) {
    $accounts = get_option('insta_gallery_accounts', []);
    if (empty($accounts)) return [];

    $account = reset($accounts);
    $token = $account['access_token'] ?? '';
    if (empty($token)) return [];

    $transient_key = 'liz_instagram_photos';
    $photos = get_transient($transient_key);
    if ($photos !== false) return array_slice($photos, 0, $count);

    $url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,permalink,thumbnail_url&access_token=' . $token;
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return [];

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (empty($data['data'])) return [];

    $photos = [];
    foreach ($data['data'] as $item) {
        if ($item['media_type'] === 'IMAGE' || $item['media_type'] === 'CAROUSEL_ALBUM') {
            $photos[] = ['url' => $item['media_url'], 'link' => $item['permalink']];
        } elseif ($item['media_type'] === 'VIDEO' && !empty($item['thumbnail_url'])) {
            $photos[] = ['url' => $item['thumbnail_url'], 'link' => $item['permalink']];
        }
    }

    set_transient($transient_key, $photos, 5 * MINUTE_IN_SECONDS);
    return array_slice($photos, 0, $count);
}

// -----------------------------------------------------------------------------
// Custom Menu Walker
// -----------------------------------------------------------------------------
class Theme_Menu_Walker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="submenu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $is_current = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes);

        $li_classes = array();
        if ($has_children) $li_classes[] = 'has-submenu';
        if ($is_current) $li_classes[] = 'current';

        $li_class_str = !empty($li_classes) ? ' class="' . esc_attr(implode(' ', $li_classes)) . '"' : '';

        $output .= '<li' . $li_class_str . '>';

        $a_classes = array();
        if ($is_current) $a_classes[] = 'current';
        $a_class_str = !empty($a_classes) ? ' class="' . esc_attr(implode(' ', $a_classes)) . '"' : '';

        $output .= '<a href="' . esc_url($item->url) . '"' . $a_class_str . '>';
        $output .= esc_html($item->title);

        if ($has_children) {
            $output .= '<span class="submenu-indicator">&#9662;</span>';
        }

        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        // Add <hr> separator inside <li> for top-level items (styled by SCSS)
        if ($depth === 0) {
            $output .= '<hr>';
        }
        $output .= '</li>';
    }
}


// -----------------------------------------------------------------------------
// RU texts for the global contact block + footer copyright (ACF options are
// not multilingual). Fill the "(RU)" fields in Theme Options; the Russian
// version swaps them in automatically on the frontend.
add_filter('acf/format_value/name=contact', function ($value, $post_id, $field) {
    if ($post_id !== 'option' && $post_id !== 'options') return $value;
    if (is_admin() && !wp_doing_ajax()) return $value;
    if (strpos(get_locale(), 'ru') !== 0) return $value;
    if (is_array($value)) {
        if (!empty($value['title_ru'])) $value['title'] = $value['title_ru'];
        if (!empty($value['text_ru']))  $value['text']  = $value['text_ru'];
        if (!empty($value['book_button_text_ru']) && is_array($value['book_button'] ?? null)) {
            $value['book_button']['title'] = $value['book_button_text_ru'];
        }
        if (!empty($value['email_button_text_ru'])) {
            $value['email_button_text'] = $value['email_button_text_ru'];
        }
    }
    return $value;
}, 10, 3);

add_filter('acf/format_value/name=footer_copyright', function ($value, $post_id, $field) {
    if ($post_id !== 'option' && $post_id !== 'options') return $value;
    if (is_admin() && !wp_doing_ajax()) return $value;
    if (strpos(get_locale(), 'ru') !== 0) return $value;
    $ru = get_field('footer_copyright_ru', 'option');
    return $ru ?: $value;
}, 10, 3);
