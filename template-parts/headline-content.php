<?php
$headline = get_sub_field('headline');
$slides = get_sub_field('slides');

if ((!$headline || (!$headline['title'] && !$headline['subtitle'])) && !$slides) return;

$has_slider = $slides && is_array($slides) && count($slides) >= 1;
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

  <?php if ($has_slider) : ?>
    <div class="description-slider load-fadeInUp load-delay-1">
      <div class="swiper description-swiper">
        <div class="swiper-wrapper">
          <?php foreach ($slides as $slide) : ?>
            <div class="swiper-slide">
              <?php echo wp_kses_post($slide['content']); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
