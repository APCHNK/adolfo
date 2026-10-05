<?php get_header(); ?>

<main>
  <?php get_template_part('template-parts/banner'); ?>

  <?php
  // ACF structure: flex-content (flexible_content)
  //   → layout: content_block (clone display:seamless → style + content)
  //     → content (nested flexible_content) → actual layout components
  // When the page ends with an FAQ block, the Instagram block goes right before
  // it, so the FAQ sits directly above the Contact section.
  $blocks = get_field('flex-content') ?: [];
  $last_block = $blocks ? end($blocks) : null;
  $faq_last = $last_block && !empty($last_block['content']) && count($last_block['content']) === 1
    && ($last_block['content'][0]['acf_fc_layout'] ?? '') === 'faq';
  $instagram_done = false;

  if (have_rows('flex-content')) :
    while (have_rows('flex-content')) : the_row();
      if ($faq_last && !$instagram_done && get_row_index() === count($blocks)) {
        get_template_part('template-parts/instagram');
        $instagram_done = true;
      }
      $style = get_sub_field('style') ?: 'light';
      // render the block first: components return nothing when they have no
      // data (e.g. a schedule without upcoming dates), and an empty coloured
      // band must not be left on the page
      ob_start();
          if (have_rows('content')) :
            while (have_rows('content')) : the_row();
              $layout = get_row_layout();

              switch ($layout) {
                case 'headline_content':
                  get_template_part('template-parts/headline-content');
                  break;
                case 'book_request':
                  get_template_part('template-parts/booking');
                  break;
                case 'schedule':
                  get_template_part('template-parts/schedule');
                  break;
                case 'videos':
                  get_template_part('template-parts/media-videos');
                  break;
                case 'player':
                  get_template_part('template-parts/player');
                  break;
                case 'contact':
                  get_template_part('template-parts/contact-inline');
                  break;
                case 'songs':
                  get_template_part('template-parts/songs');
                  break;
                case 'faq':
                  get_template_part('template-parts/faq');
                  break;
              }
            endwhile;
          endif;
      $inner = trim(ob_get_clean());
      if ($inner === '') continue;
      ?>
      <div class="<?php echo esc_attr($style); ?>-bg">
        <div class="columnar"><?php echo $inner; ?></div>
      </div>
      <?php
    endwhile;
  endif;
  ?>

  <?php if (!$instagram_done) get_template_part('template-parts/instagram'); ?>
</main>

<?php get_footer(); ?>
