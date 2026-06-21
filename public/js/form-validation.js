function initFormValidation(form) {
  if (!form) return;
  form.querySelectorAll('input, select, textarea').forEach(function(field) {
    field.addEventListener('blur', function() { validateField(field); });
    field.addEventListener('input', function() { validateField(field); });
  });
  form.addEventListener('submit', function(e) {
    var valid = true;
    form.querySelectorAll('input, select, textarea').forEach(function(field) {
      if (!validateField(field)) valid = false;
    });
    if (!valid) e.preventDefault();
  });
}

function validateField(field) {
  var row = field.closest('.form-row');
  if (!row) return true;
  var errorEl = row.querySelector('.field-error');
  if (!errorEl) {
    errorEl = document.createElement('div');
    errorEl.className = 'field-error';
    row.appendChild(errorEl);
  }

  var valid = true;
  var msg = '';

  if (field.hasAttribute('required') && !field.value.trim()) {
    valid = false;
    msg = field.getAttribute('data-required-msg') || 'This field is required.';
  } else if (field.type === 'email' && field.value.trim()) {
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
      valid = false;
      msg = 'Enter a valid email address.';
    }
  } else if (field.name === 'password' && field.value.length > 0 && field.value.length < 8) {
    valid = false;
    msg = 'Password must be at least 8 characters.';
  } else if (field.name === 'password_confirmation') {
    var pw = (field.closest('form') || document).querySelector('[name="password"]');
    if (pw && field.value !== pw.value) {
      valid = false;
      msg = 'Passwords do not match.';
    }
  } else if (field.name === 'reg_number' && field.value.trim()) {
    if (!/^[A-Z]+\/\d{2}\/(SC|IT|CS|EE|ME|CE|BE|AR|LA|BM|PM)\/\d{3,4}$/i.test(field.value.trim())) {
      valid = false;
      msg = 'Format: BIT/22/SC/0001';
    }
  }

  row.classList.toggle('has-error', !valid);
  errorEl.textContent = msg;
  return valid;
}

document.querySelectorAll('form').forEach(initFormValidation);
