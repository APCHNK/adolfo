<?php
$title = get_sub_field('title');
$items = get_sub_field('items');

if (!$items || !is_array($items)) return;

// Filter future events and sort
$now = time();
$filtered = array_filter($items, function ($item) use ($now) {
  if (empty($item['date'])) return false;
  $parts = explode('/', $item['date']);
  if (count($parts) !== 3) return false;
  $ts = strtotime("{$parts[2]}-{$parts[1]}-{$parts[0]}");
  return $ts >= $now;
});

usort($filtered, function ($a, $b) {
  $pa = explode('/', $a['date']);
  $pb = explode('/', $b['date']);
  $ta = strtotime("{$pa[2]}-{$pa[1]}-{$pa[0]}");
  $tb = strtotime("{$pb[2]}-{$pb[1]}-{$pb[0]}");
  return $ta - $tb;
});

if (empty($filtered)) return;

if (!function_exists('format_schedule_date')) :
function format_schedule_date($dateStr) {
  $parts = explode('/', $dateStr);
  if (count($parts) !== 3) return $dateStr;
  $ts = strtotime("{$parts[2]}-{$parts[1]}-{$parts[0]}");
  return date('j F Y', $ts);
}
endif;
?>
<div class="schedule">
  <?php if ($title) : ?>
    <h2 class="reveal reveal--up"><?php echo esc_html($title); ?></h2>
  <?php endif; ?>
  <ul>
    <?php foreach ($filtered as $index => $item) : ?>
      <li class="reveal reveal--up" data-reveal-delay="<?php echo ($index * 100); ?>">
        <?php if (!empty($item['image'])) : ?>
          <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['image']['alt'] ?? ''); ?>" width="140" height="140" loading="lazy">
        <?php endif; ?>
        <div class="info">
          <div>
            <?php if (!empty($item['text'])) : ?>
              <h3><?php echo esc_html($item['text']); ?></h3>
            <?php endif; ?>
            <?php if (!empty($item['date'])) : ?>
              <span><?php echo esc_html(format_schedule_date($item['date'])); ?></span>
            <?php endif; ?>
          </div>
          <?php if (!empty($item['link'])) : ?>
            <a href="<?php echo esc_url($item['link']); ?>" target="_blank" rel="noopener noreferrer">
              <button class="button button--outline">Buy tickets</button>
            </a>
          <?php endif; ?>
        </div>
      </li>
      <?php if ($index < count($filtered) - 1) : ?>
        <hr class="reveal" data-reveal-delay="<?php echo (count($filtered) * 100 + 500); ?>">
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>
