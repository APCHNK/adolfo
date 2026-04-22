<?php
$form_id = get_field('form_id', 'option');
$success_img = get_template_directory_uri() . '/assets/images/success.jpg';
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
    <h2 class="modal-title">Request</h2>
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
        <img src="<?php echo esc_url($success_img); ?>" alt="success" width="220" height="220" loading="lazy" decoding="async">
        <p>Request Sent Successfully!</p>
        <p>Thank you for your interest in Boney M.</p>
        <p>We will be delighted to perform for you.</p>
        <p>You got email, check your inbox !!! spam folder too !!!</p>
        <p>Looking forward to hearing from you soon</p>
        <p>Maya <a href="tel:+447869422699">+447869422699</a></p>
        <div class="btn">
          <button class="button button--primary">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
