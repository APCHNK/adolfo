<?php
$form_id = get_field('form_id', 'option');
// success picture: the artist photo from Theme Options → Contact (no donor stock image)
$contact_opts = get_field('contact', 'option');
$success_img = !empty($contact_opts['image']['ID']) ? wp_get_attachment_image_url($contact_opts['image']['ID'], 'medium_large') : '';
$close_icon = get_template_directory_uri() . '/assets/icons/close.svg';

// Try to get CF7 form fields
$form_action = '';
if ($form_id) {
  $form_action = esc_url(home_url("/wp-json/contact-form-7/v1/contact-forms/{$form_id}/feedback"));
}
?>
<div class="modal-wrap">
  <div class="mask"></div>
  <div class="container">
    <h2 class="modal-title" data-success-title="<?php echo esc_attr(adolfo_brand_name()); ?>">Request</h2>
    <img class="close" src="<?php echo esc_url($close_icon); ?>" alt="close">
    <div class="content">
      <div class="form-content">
        <form action="<?php echo $form_action; ?>" method="post">
          <p class="form-error" style="display:none;color:#cf1f14;">Something went wrong... Please, try again</p>

          <div class="field-wrap">
            <input type="text" name="city" placeholder="City">
          </div>
          <div class="field-wrap">
            <input type="text" name="person-name" placeholder="Name">
          </div>
          <div class="field-wrap">
            <input type="text" name="person-private" placeholder="Private person / Event agency">
          </div>
          <div class="field-wrap">
            <input type="email" name="email" placeholder="Email">
          </div>
          <div class="field-wrap">
            <input type="tel" name="phone" placeholder="Phone number">
          </div>
          <div class="field-wrap">
            <input type="date" name="date" placeholder="Event date">
          </div>
          <div class="submit-container">
            <input type="submit" value="Send Request">
            <span class="spinner" style="display:none;">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="2" x2="12" y2="6"></line>
                <line x1="12" y1="18" x2="12" y2="22"></line>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                <line x1="2" y1="12" x2="6" y2="12"></line>
                <line x1="18" y1="12" x2="22" y2="12"></line>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
              </svg>
            </span>
            <?php if ($form_id) : ?>
              <input type="hidden" name="_wpcf7" value="<?php echo esc_attr($form_id); ?>">
              <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f<?php echo esc_attr($form_id); ?>-o1">
              <input type="hidden" name="formId" value="<?php echo esc_attr($form_id); ?>">
            <?php endif; ?>
          </div>
        </form>
      </div>

      <div class="success-message" style="display:none;">
        <?php if ($success_img) : ?>
          <img src="<?php echo esc_url($success_img); ?>" alt="<?php echo esc_attr(adolfo_brand_name()); ?>" loading="lazy" decoding="async">
        <?php endif; ?>
        <?php
        $is_ru_modal = function_exists('pll_current_language') && pll_current_language() === 'ru';
        $modal_email = adolfo_booking_email();
        ?>
        <?php if ($is_ru_modal) : ?>
          <p>Заявка успешно отправлена!</p>
          <p>Спасибо за интерес к трибьют-шоу Адриано Челентано с Адольфо Себастиани.</p>
          <p>Наш букинг-менеджер свяжется с вами в ближайшее время.</p>
          <?php if ($modal_email) : ?>
            <p>Email: <a href="mailto:<?php echo esc_attr($modal_email); ?>"><?php echo esc_html($modal_email); ?></a></p>
          <?php endif; ?>
        <?php else : ?>
          <p>Request sent successfully!</p>
          <p>Thank you for your interest in the Adriano Celentano Tribute Show by Adolfo Sebastiani.</p>
          <p>Our booking manager will get back to you shortly.</p>
          <?php if ($modal_email) : ?>
            <p>Email: <a href="mailto:<?php echo esc_attr($modal_email); ?>"><?php echo esc_html($modal_email); ?></a></p>
          <?php endif; ?>
        <?php endif; ?>
        <div class="btn">
          <button class="button button--primary">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
