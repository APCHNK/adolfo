<?php
$title = get_sub_field('title');
$questions = get_sub_field('questions');

if (!$questions || !is_array($questions) || count($questions) === 0) return;
?>
<div class="faq">
  <?php if ($title) : ?>
    <h2 class="reveal reveal--up"><?php echo esc_html($title); ?></h2>
  <?php endif; ?>
  <div class="faq__list">
    <?php foreach ($questions as $index => $item) : ?>
      <div class="faq__item reveal reveal--up" data-reveal-delay="<?php echo ($index * 60); ?>">
        <button class="faq__question" type="button">
          <span><?php echo esc_html($item['question']); ?></span>
          <svg class="faq__arrow" width="26" height="26" viewBox="0 0 26 26" fill="none">
            <path d="M24.1904 13.1582L15.8408 21.5078L15.0361 20.7031L21.0205 14.708L21.9951 13.7314L1.57226 13.7314L1.57226 12.5869L21.9951 12.5869L21.0205 11.6094L15.0361 5.61426L15.8408 4.8086L24.1904 13.1582Z" fill="currentColor" stroke="currentColor" stroke-width="1"/>
          </svg>
        </button>
        <div class="faq__answer">
          <?php echo wp_kses_post($item['answer']); ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
