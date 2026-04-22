<?php
$banner = get_field('banner');
if (!$banner) return;

$image = $banner['image'] ?? null;
$title = $banner['title'] ?? '';
$subtitle = $banner['subtitle'] ?? '';
$mark_text = $banner['mark_text'] ?? '';
$button = $banner['button'] ?? null;
$use_modal = $banner['use_modal'] ?? true;

if (!$title) return;
?>
<div class="banner banner-bg">
  <div class="banner-img">
    <?php
    echo adolfo_render_image($image, array(
        'loading'       => 'eager',
        'fetchpriority' => 'high',
        'decoding'      => 'sync',
        'sizes'         => '100vw',
    ));
    ?>
  </div>
  <div class="columnar vertical">
    <div class="banner-wrap">
      <div class="heading">
        <div>
          <?php if ($mark_text) : ?>
            <span class="banner-mark reveal reveal--desktop" data-reveal-delay="200"><?php echo esc_html($mark_text); ?></span>
          <?php endif; ?>
          <?php if ($title) : ?>
            <h1 class="reveal reveal--desktop" data-reveal-delay="300"><?php echo esc_html($title); ?></h1>
          <?php endif; ?>
          <?php if ($subtitle) : ?>
            <p class="reveal reveal--desktop" data-reveal-delay="500"><?php echo esc_html($subtitle); ?></p>
          <?php endif; ?>
        </div>
        <?php if (!empty($button['link']['title'])) : ?>
          <div class="btn-wrap reveal reveal--desktop" data-reveal-delay="700">
            <?php if ($use_modal) : ?>
              <button class="button button--primary" data-open-modal>
                <?php echo esc_html($button['link']['title']); ?>
              </button>
            <?php else : ?>
              <a href="<?php echo esc_url($button['link']['url'] ?? '#'); ?>" class="button button--primary" <?php echo !empty($button['link']['target']) ? 'target="' . esc_attr($button['link']['target']) . '"' : ''; ?>>
                <?php echo esc_html($button['link']['title']); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
