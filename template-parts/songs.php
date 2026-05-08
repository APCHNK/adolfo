<?php
$songs_title = get_sub_field('title');
$cards = get_sub_field('cards');
$vinyl_image = get_sub_field('vinyl_image');

if (!$cards || !is_array($cards) || count($cards) === 0) return;
?>
<div class="songs-section">
  <?php if ($songs_title) : ?>
    <h2 class="reveal reveal--up"><?php echo esc_html($songs_title); ?></h2>
  <?php endif; ?>
  <div class="songs-grid">
    <?php foreach ($cards as $index => $card) :
      $image = $card['image'] ?? null;
      $title = $card['title'] ?? '';
      $desc = $card['description'] ?? '';
      $url = $card['url'] ?? '#';
    ?>
      <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="songs-card reveal reveal--up" data-reveal-delay="<?php echo ($index * 80); ?>">
        <?php if ($image) : ?>
          <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
        <?php endif; ?>
        <div class="songs-card__overlay">
          <?php if ($title) : ?>
            <h3><?php echo esc_html($title); ?></h3>
          <?php endif; ?>
          <?php if ($desc) : ?>
            <p><?php echo wp_kses_post($desc); ?></p>
          <?php endif; ?>
        </div>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="songs-vinyl reveal reveal--up" aria-hidden="true">
    <div class="songs-vinyl__disc">
      <?php if (!empty($vinyl_image['url'])) : ?>
        <div class="songs-vinyl__photo">
          <img src="<?php echo esc_url($vinyl_image['url']); ?>" alt="<?php echo esc_attr($vinyl_image['alt'] ?? ''); ?>" loading="lazy">
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
