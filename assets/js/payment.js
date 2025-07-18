document.addEventListener('DOMContentLoaded', () => {
  // Tab switching
  document.querySelectorAll('.method-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.method-tab, .method-content').forEach(el => el.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById(tab.dataset.tab).classList.add('active');
    });
  });

  // Input formatters
  const formatInput = (selector, maxLength, pattern, formatter = val => val) => {
    document.querySelectorAll(selector).forEach(input => {
      input.addEventListener('input', e => {
        let value = e.target.value.replace(pattern, '');
        e.target.value = formatter(value.substring(0, maxLength));
      });
    });
  };

  formatInput('#card-number', 19, /\D/g, val => val.match(/.{1,4}/g)?.join(' ') || val);
  formatInput('#expiry-date', 4, /\D/g, val => val.length > 2 ? val.slice(0, 2) + '/' + val.slice(2) : val);
  formatInput('input[type="tel"]', 11, /\D/g);
  formatInput('input[placeholder*="OTP"]', 6, /\D/g);
  formatInput('#gcash-pin', 4, /\D/g);

  // Validators
  const isValid = {
    cardNumber: val => /^\d{16}$/.test(val.replace(/\s+/g, '')),
    expiryDate: val => /^\d{2}\/\d{2}$/.test(val),
    cvv: val => /^\d{3,4}$/.test(val),
    name: val => val.trim().length > 0,
    mobile: val => /^\d{11}$/.test(val),
    otp: val => /^\d{6}$/.test(val),
    mpin: val => /^\d{4}$/.test(val),
    accountNumber: val => /^\d{10}$/.test(val)
  };

  const validateForm = (fields, messages, e) => {
    for (let i = 0; i < fields.length; i++) {
      if (!fields[i].test(messages[i].value)) {
        alert(messages[i].error);
        e.preventDefault();
        return false;
      }
    }
    return true;
  };

  document.getElementById('payment-form')?.addEventListener('submit', e => {
    validateForm(
      [isValid.cardNumber, isValid.expiryDate, isValid.cvv, isValid.name],
      [
        { value: document.getElementById('card-number').value, error: 'Please enter a valid card number' },
        { value: document.getElementById('expiry-date').value, error: 'Enter expiry date in MM/YY format' },
        { value: document.getElementById('cvv').value, error: 'Enter a valid CVV' },
        { value: document.getElementById('card-name').value, error: 'Enter the name on your card' }
      ],
      e
    );
  });

  document.getElementById('seabank-form')?.addEventListener('submit', e => {
    validateForm(
      [isValid.accountNumber, isValid.mobile, isValid.otp],
      [
        { value: document.getElementById('seabank-account').value, error: 'Enter a valid 10-digit account number' },
        { value: document.getElementById('seabank-mobile').value, error: 'Enter an 11-digit mobile number' },
        { value: document.getElementById('seabank-otp').value, error: 'Enter a 6-digit OTP' }
      ],
      e
    );
  });

  document.getElementById('gcash-form')?.addEventListener('submit', e => {
    validateForm(
      [isValid.mobile, isValid.otp, isValid.mpin],
      [
        { value: document.getElementById('gcash-mobile').value, error: 'Enter an 11-digit mobile number' },
        { value: document.getElementById('gcash-otp').value, error: 'Enter a 6-digit OTP' },
        { value: document.getElementById('gcash-pin').value, error: 'Enter a 4-digit MPIN' }
      ],
      e
    );
  });
});