export function initModal() {
  const modalWrap = document.querySelector('.modal-wrap');
  if (!modalWrap) return;

  const mask = modalWrap.querySelector('.mask');
  const closeBtn = modalWrap.querySelector('.close');
  const form = modalWrap.querySelector('form');
  const successMessage = modalWrap.querySelector('.success-message');
  const formContent = modalWrap.querySelector('.form-content');
  const modalTitle = modalWrap.querySelector('.modal-title');

  function openModal() {
    modalWrap.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modalWrap.classList.remove('active');
    document.body.style.overflow = 'auto';
    // Reset success state
    if (successMessage) successMessage.style.display = 'none';
    if (formContent) formContent.style.display = 'block';
    if (modalTitle) modalTitle.textContent = 'Request';
  }

  // Open triggers
  document.querySelectorAll('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal();
    });
  });

  // Close triggers
  if (mask) mask.addEventListener('click', closeModal);
  if (closeBtn) closeBtn.addEventListener('click', closeModal);

  // Success close button
  const successCloseBtn = modalWrap.querySelector('.success-message .btn button');
  if (successCloseBtn) {
    successCloseBtn.addEventListener('click', closeModal);
  }

  // Form submission
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Validation
      let isValid = true;
      const fields = {
        city: form.querySelector('[name="city"]'),
        name: form.querySelector('[name="person-name"]'),
        person: form.querySelector('[name="person-private"]'),
        email: form.querySelector('[name="email"]'),
        phone: form.querySelector('[name="phone"]'),
      };

      // Clear previous errors
      form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');

      if (fields.city && !fields.city.value.trim()) {
        showError(fields.city, '*Enter your city');
        isValid = false;
      }
      if (fields.name && !fields.name.value.trim()) {
        showError(fields.name, '*Enter your name');
        isValid = false;
      }
      if (fields.person && !fields.person.value.trim()) {
        showError(fields.person, '*Enter your role');
        isValid = false;
      }
      if (fields.email) {
        if (!fields.email.value.trim()) {
          showError(fields.email, '*Enter your email');
          isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value)) {
          showError(fields.email, '*Enter a valid email');
          isValid = false;
        }
      }
      if (fields.phone && !fields.phone.value.trim()) {
        showError(fields.phone, '*Enter your number');
        isValid = false;
      }

      if (!isValid) return;

      const submitBtn = form.querySelector('[type="submit"]');
      const spinner = form.querySelector('.spinner');
      if (submitBtn) submitBtn.disabled = true;
      if (submitBtn) submitBtn.value = 'Sending...';
      if (spinner) spinner.style.display = 'flex';

      const body = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body,
      })
        .then(res => res.json())
        .then(data => {
          if (submitBtn) submitBtn.disabled = false;
          if (submitBtn) submitBtn.value = 'Send Request';
          if (spinner) spinner.style.display = 'none';

          if (data.status === 'mail_sent') {
            if (formContent) formContent.style.display = 'none';
            if (successMessage) successMessage.style.display = 'flex';
            if (modalTitle && modalTitle.dataset.successTitle) modalTitle.textContent = modalTitle.dataset.successTitle;
            form.reset();
          } else {
            const errEl = form.querySelector('.form-error');
            if (errEl) errEl.style.display = 'block';
          }
        })
        .catch(() => {
          if (submitBtn) submitBtn.disabled = false;
          if (submitBtn) submitBtn.value = 'Send Request';
          if (spinner) spinner.style.display = 'none';
          const errEl = form.querySelector('.form-error');
          if (errEl) errEl.style.display = 'block';
        });
    });
  }

  function showError(field, message) {
    const wrap = field.closest('.field-wrap');
    if (wrap) {
      let errEl = wrap.querySelector('.error-msg');
      if (!errEl) {
        errEl = document.createElement('span');
        errEl.className = 'error-msg';
        wrap.appendChild(errEl);
      }
      errEl.textContent = message;
    }
  }
}
