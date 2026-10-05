<?php
$headline = get_sub_field('headline');
$text_area = get_sub_field('text_area');

if ((!$headline || (!$headline['title'] && !$headline['subtitle'])) && !$text_area) return;
?>
<div class="text-block">
  <div class="headline load-fadeInUp">
    <?php if (!empty($headline['title'])) : ?>
      <h2><?php echo wp_kses($headline['title'], ['br' => []]); ?></h2>
    <?php endif; ?>
    <?php if (!empty($headline['subtitle'])) : ?>
      <span><?php echo wp_kses($headline['subtitle'], ['br' => []]); ?></span>
    <?php endif; ?>
  </div>

  <?php if ($text_area) : ?>
    <div class="description load-fadeInUp load-delay-1"><?php echo wp_kses_post($text_area); ?></div>
  <?php endif; ?>
</div>
