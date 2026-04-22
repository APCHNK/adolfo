<?php
/**
 * Image rendering helper with WebP fallback.
 *
 * Outputs <picture> with a <source type="image/webp"> if a .webp file exists
 * next to the original (e.g. image.png => image.webp). Otherwise falls back
 * to a plain <img>. Always sets width/height, decoding, and loading attributes.
 */

if (!defined('ABSPATH')) exit;

/**
 * Build a WebP URL next to the given original URL if the file exists on disk.
 * Returns null if no sibling .webp file is found.
 */
function adolfo_webp_sibling($url) {
    if (!$url) return null;
    $upload = wp_upload_dir();

    // Try to map URL to a local path.
    $path = null;
    if (strpos($url, $upload['baseurl']) === 0) {
        $path = $upload['basedir'] . substr($url, strlen($upload['baseurl']));
    } elseif (strpos($url, get_template_directory_uri()) === 0) {
        $path = get_template_directory() . substr($url, strlen(get_template_directory_uri()));
    }
    if (!$path) return null;

    $webp_path = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);
    if ($webp_path === $path || !file_exists($webp_path)) return null;

    return preg_replace('/\.(png|jpe?g)$/i', '.webp', $url);
}

/**
 * Render an <img> (wrapped in <picture> when a WebP sibling exists).
 *
 * @param array $image   ACF image array (expects url, width, height, alt, id).
 * @param array $attrs   Extra attributes: class, loading, fetchpriority, sizes, srcset.
 */
function adolfo_render_image($image, $attrs = array()) {
    if (empty($image) || empty($image['url'])) return '';

    $defaults = array(
        'class'         => '',
        'loading'       => 'lazy',
        'decoding'      => 'async',
        'fetchpriority' => '',
        'sizes'         => '',
        'srcset'        => '',
        'alt'           => $image['alt'] ?? '',
        'width'         => $image['width'] ?? '',
        'height'        => $image['height'] ?? '',
    );
    $a = array_merge($defaults, $attrs);

    // Auto-generate srcset from WP sizes when we have an attachment ID.
    if (empty($a['srcset']) && !empty($image['id'])) {
        $auto_srcset = wp_get_attachment_image_srcset($image['id'], 'full');
        if ($auto_srcset) {
            $a['srcset'] = $auto_srcset;
            if (empty($a['sizes'])) {
                $a['sizes'] = wp_get_attachment_image_sizes($image['id'], 'full') ?: '100vw';
            }
        }
    }

    $webp_url = adolfo_webp_sibling($image['url']);

    // Build attribute string for the <img>.
    $img_attrs = array(
        'src="' . esc_url($image['url']) . '"',
        'alt="' . esc_attr($a['alt']) . '"',
    );
    if ($a['width'])         $img_attrs[] = 'width="'  . esc_attr($a['width'])  . '"';
    if ($a['height'])        $img_attrs[] = 'height="' . esc_attr($a['height']) . '"';
    if ($a['class'])         $img_attrs[] = 'class="'  . esc_attr($a['class'])  . '"';
    if ($a['loading'])       $img_attrs[] = 'loading="' . esc_attr($a['loading']) . '"';
    if ($a['decoding'])      $img_attrs[] = 'decoding="' . esc_attr($a['decoding']) . '"';
    if ($a['fetchpriority']) $img_attrs[] = 'fetchpriority="' . esc_attr($a['fetchpriority']) . '"';
    if ($a['srcset'])        $img_attrs[] = 'srcset="' . esc_attr($a['srcset']) . '"';
    if ($a['sizes'])         $img_attrs[] = 'sizes="'  . esc_attr($a['sizes'])  . '"';

    $img_tag = '<img ' . implode(' ', $img_attrs) . '>';

    if (!$webp_url) return $img_tag;

    // Build a matching WebP srcset by swapping extensions when siblings exist.
    $webp_srcset = '';
    if ($a['srcset']) {
        $parts = array_map('trim', explode(',', $a['srcset']));
        $mapped = array();
        foreach ($parts as $part) {
            if (preg_match('/^(\S+)(\s+.+)?$/', $part, $m)) {
                $candidate = adolfo_webp_sibling($m[1]);
                if ($candidate) $mapped[] = $candidate . ($m[2] ?? '');
            }
        }
        if (count($mapped) === count($parts)) $webp_srcset = implode(', ', $mapped);
    }

    $source_attrs = array('type="image/webp"');
    $source_attrs[] = 'srcset="' . esc_attr($webp_srcset ?: $webp_url) . '"';
    if ($a['sizes']) $source_attrs[] = 'sizes="' . esc_attr($a['sizes']) . '"';

    return '<picture><source ' . implode(' ', $source_attrs) . '>' . $img_tag . '</picture>';
}
