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
          </nav>
        </div>
      </div>
    </header>
