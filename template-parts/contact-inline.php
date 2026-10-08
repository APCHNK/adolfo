<?php
$image = get_sub_field('image');
$title = get_sub_field('title');
$text = get_sub_field('text');
$link = get_sub_field('link');

if (!$title && !$text) return;
?>
<div class="contact">
  <?php if (!empty($image['url'])) : ?>
    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>">
  <?php endif; ?>
  <div class="action">
    <?php if ($title) : ?>
      <h2><?php echo esc_html($title); ?></h2>
    <?php endif; ?>
    <?php if ($text) : ?>
      <div><?php echo wp_kses_post($text); ?></div>
    <?php endif; ?>
    <div class="btns">
      <?php if (!empty($link['title'])) : ?>
        <button class="button button--primary" data-open-modal>
          <?php echo esc_html($link['title']); ?>
        </button>
      <?php endif; ?>
      <?php if ($booking_email = adolfo_booking_email()) : ?>
        <a class="button button--outline" href="mailto:<?php echo esc_attr($booking_email); ?>">Email</a>
      <?php endif; ?>
    </div>
  </div>
</div>
