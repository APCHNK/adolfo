<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Playfair+Display:wght@400..900&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Playfair+Display:wght@400..900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Playfair+Display:wght@400..900&display=swap" rel="stylesheet"></noscript>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> style="font-family: 'Manrope', sans-serif;">
  <?php wp_body_open(); ?>
  <div class="app-wrapper">
    <header class="spacing-xs header">
      <div class="columnar">
        <div class="header-wrap">
          <?php
          $logo = get_field('header_logo', 'option') ?: get_field('logo', 'option');
          if ($logo) : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
              <?php echo adolfo_render_image($logo, array(
                'loading'       => 'eager',
                'fetchpriority' => 'high',
                'decoding'      => 'sync',
                'sizes'         => '(max-width: 1023px) 175px, 220px',
              )); ?>
            </a>
          <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/logo.svg" alt="<?php bloginfo('name'); ?>" width="175" height="48">
            </a>
          <?php endif; ?>

          <?php
          $insta_opts = get_field('instagram', 'option');
          $header_insta_url = is_array($insta_opts) && !empty($insta_opts['url']) ? $insta_opts['url'] : '';

          $lang_ru_url = trim((string) get_field('lang_ru_url', 'option'));
          $lang_en_url = trim((string) get_field('lang_en_url', 'option'));

          $current_host = strtolower(preg_replace('/^www\./i', '', (string) ($_SERVER['HTTP_HOST'] ?? '')));
          $host_of = static function ($u) {
            $h = parse_url($u, PHP_URL_HOST);
            return $h ? strtolower(preg_replace('/^www\./i', '', $h)) : '';
          };
          $is_ru_active = $lang_ru_url && $host_of($lang_ru_url) === $current_host;
          $is_en_active = $lang_en_url && $host_of($lang_en_url) === $current_host;

          $has_lang_links = $lang_ru_url || $lang_en_url;

          ob_start(); ?>
            <?php if ($header_insta_url) : ?>
              <a class="header-social" href="<?php echo esc_url($header_insta_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <g opacity="0.8">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 0H22C27.522 0 32 4.478 32 10V22C32 27.522 27.522 32 22 32H10C4.478 32 0 27.522 0 22V10C0 4.478 4.478 0 10 0ZM22 29C25.86 29 29 25.86 29 22V10C29 6.14 25.86 3 22 3H10C6.14 3 3 6.14 3 10V22C3 25.86 6.14 29 10 29H22Z" fill="currentColor"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 16C8 11.582 11.582 8 16 8C20.418 8 24 11.582 24 16C24 20.418 20.418 24 16 24C11.582 24 8 20.418 8 16ZM11 16C11 18.756 13.244 21 16 21C18.756 21 21 18.756 21 16C21 13.242 18.756 11 16 11C13.244 11 11 13.242 11 16Z" fill="currentColor"/>
                    <path d="M24.5996 8.46614C25.1884 8.46614 25.6656 7.98888 25.6656 7.40014C25.6656 6.81141 25.1884 6.33414 24.5996 6.33414C24.0109 6.33414 23.5336 6.81141 23.5336 7.40014C23.5336 7.98888 24.0109 8.46614 24.5996 8.46614Z" fill="currentColor"/>
                  </g>
                </svg>
              </a>
              <?php if ($has_lang_links) : ?>
                <span class="header-divider" aria-hidden="true"></span>
              <?php endif; ?>
            <?php endif; ?>
            <?php if ($has_lang_links) : ?>
              <div class="lang-switcher">
                <?php if ($lang_ru_url) : ?>
                  <a href="<?php echo esc_url($lang_ru_url); ?>"<?php echo $is_ru_active ? ' class="is-active"' : ''; ?>>Ru</a>
                <?php endif; ?>
                <?php if ($lang_en_url) : ?>
                  <a href="<?php echo esc_url($lang_en_url); ?>"<?php echo $is_en_active ? ' class="is-active"' : ''; ?>>En</a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php $extras_content = ob_get_clean(); ?>

          <div class="header-extras"><?php echo $extras_content; ?></div>

          <div class="toggle-menu">
            

            <svg xmlns="http://www.w3.org/2000/svg"class="burger-icon" width="32" height="32" viewBox="0 0 32 32" fill="none">
              <path d="M5.3335 9.33333H26.6668M5.3335 16H26.6668M5.3335 22.6667H26.6668" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="close-icon" width="32" height="32" viewBox="0 0 32 32" fill="none">
              <path d="M8 8L24 24M24 8L8 24" stroke="white" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>

          <nav class="menu">
            <?php
            // Try 'primary' location first, fallback to 'header' (from old theme)
            $menu_args = array(
              'container'      => false,
              'items_wrap'     => '<ul>%3$s</ul>',
              'walker'         => new Theme_Menu_Walker(),
              'fallback_cb'    => false,
            );

            $locations = get_nav_menu_locations();
            if (!empty($locations['primary'])) {
              $menu_args['theme_location'] = 'primary';
            } elseif (!empty($locations['header'])) {
              $menu_args['theme_location'] = 'header';
            } else {
              // Fallback: use first available menu
              $menus = wp_get_nav_menus();
              if (!empty($menus)) {
                $menu_args['menu'] = $menus[0]->term_id;
              }
            }

            wp_nav_menu($menu_args);
            ?>
            <div class="menu-extras"><?php echo $extras_content; ?></div>
          </nav>
        </div>
      </div>
    </header>
