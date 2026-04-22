<?php
$photos = theme_get_instagram_photos(8);
if (empty($photos)) return;

$insta = get_field('instagram', 'option');
$title = $insta['title'] ?? 'Social Media';
$btn_text = $insta['btn_text'] ?? 'View more on Instagram';
$url = $insta['url'] ?? '#';
?>
<div class="white-bg spacing-lg">
  <div class="columnar">
    <div class="instagram-section">
      <h2 class="reveal reveal--up"><?php echo esc_html($title); ?></h2>
      <div class="instagram-grid">
        <?php foreach ($photos as $index => $photo) : ?>
          <a href="<?php echo esc_url($photo['link']); ?>" target="_blank" rel="noopener noreferrer" class="instagram-item reveal reveal--up" data-reveal-delay="<?php echo ($index * 80); ?>">
            <img src="<?php echo esc_url($photo['url']); ?>" alt="Instagram" loading="lazy" decoding="async" width="400" height="400">
          </a>
        <?php endforeach; ?>
      </div>
      <?php if ($url && $btn_text) : ?>
        <div class="instagram-cta">
          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="button button--outline">
            <?php echo esc_html($btn_text); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
