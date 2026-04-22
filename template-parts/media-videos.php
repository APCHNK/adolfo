<?php
$headline = get_sub_field('headline');
$videos = get_sub_field('videos');

if (!$headline || !$videos) return;
?>
<div class="media">
  <?php if ($headline) : ?>
    <h2 class="reveal reveal--up"><?php echo esc_html($headline); ?></h2>
  <?php endif; ?>
  <div class="half">
    <?php foreach ($videos as $index => $video) : ?>
      <?php if (!empty($video['url'])) : ?>
        <div class="media-wrap reveal reveal--up" data-reveal-delay="<?php echo ($index * 150); ?>">
          <iframe
            title="iframe <?php echo $index; ?>"
            data-src="<?php echo esc_url($video['url']); ?>"
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen
          ></iframe>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>
