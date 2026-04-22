<?php
$contact = get_field('contact', 'option');
if (!$contact) return;

$image = $contact['image'] ?? null;
$title = $contact['title'] ?? '';
$text = $contact['text'] ?? '';
$book_button = $contact['book_button'] ?? null;
$use_modal = $contact['use_modal'] ?? true;
$email = $contact['email'] ?? 'book.boneym@gmail.com';

if (!$title && !$text) return;
?>
<div class="light-bg spacing-lg">
  <div class="columnar">
    <div class="contact">
      <?php if (!empty($image['url'])) : ?>
        <?php echo adolfo_render_image($image, array(
          'class'   => 'reveal reveal--left',
          'loading' => 'lazy',
          'sizes'   => '(max-width: 1023px) 100vw, 50vw',
        )); ?>
      <?php endif; ?>
      <div class="action reveal reveal--right" data-reveal-delay="200">
        <?php if ($title) : ?>
          <h2><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if ($text) : ?>
          <div><?php echo wp_kses_post($text); ?></div>
        <?php endif; ?>
        <div class="btns">
          <?php if (!empty($book_button['title'])) : ?>
            <?php if ($use_modal) : ?>
              <button class="button button--primary" data-open-modal>
                <?php echo esc_html($book_button['title']); ?>
              </button>
            <?php else : ?>
              <a href="<?php echo esc_url($book_button['url'] ?? '#'); ?>" class="button button--primary" <?php echo !empty($book_button['target']) ? 'target="' . esc_attr($book_button['target']) . '"' : ''; ?>>
                <?php echo esc_html($book_button['title']); ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>
          <a href="mailto:<?php echo esc_attr($email); ?>" class="button button--outline">Email</a>
        </div>
      </div>
    </div>
  </div>
</div>
