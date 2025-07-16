document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.method-tab');
            const contents = document.querySelectorAll('.method-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    tab.classList.add('active');
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Format credit card number input
            const cardNumberInput = document.getElementById('card-number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s+/g, '');
                    if (value.length > 0) {
                        value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
                    }
                    e.target.value = value;
                });
            }
            
            // Format expiry date input
            const expiryDateInput = document.getElementById('expiry-date');
            if (expiryDateInput) {
                expiryDateInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }

            // Format mobile number inputs
            const mobileInputs = document.querySelectorAll('input[type="tel"]');
            mobileInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        value = value.substring(0, 11);
                    }
                    e.target.value = value;
                });
            });

            // Format OTP inputs
            const otpInputs = document.querySelectorAll('input[placeholder*="OTP"]');
            otpInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        value = value.substring(0, 6);
                    }
                    e.target.value = value;
                });
            });

            // Format GCash MPIN
            const gcashPinInput = document.getElementById('gcash-pin');
            if (gcashPinInput) {
                gcashPinInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        value = value.substring(0, 4);
                    }
                    e.target.value = value;
                });
            }

            // Form validation for credit card
            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    const cardNumber = document.getElementById('card-number').value.replace(/\s+/g, '');
                    const expiryDate = document.getElementById('expiry-date').value;
                    const cvv = document.getElementById('cvv').value;
                    const cardName = document.getElementById('card-name').value;

                    if (cardNumber.length < 16 || !/^\d+$/.test(cardNumber)) {
                        alert('Please enter a valid card number');
                        e.preventDefault();
                        return;
                    }

                    if (!expiryDate.match(/^\d{2}\/\d{2}$/)) {
                        alert('Please enter a valid expiry date in MM/YY format');
                        e.preventDefault();
                        return;
                    }

                    if (cvv.length < 3 || !/^\d+$/.test(cvv)) {
                        alert('Please enter a valid CVV');
                        e.preventDefault();
                        return;
                    }

                    if (cardName.trim() === '') {
                        alert('Please enter the name on your card');
                        e.preventDefault();
                        return;
                    }
                });
            }

            // Form validation for SeaBank
            const seabankForm = document.getElementById('seabank-form');
            if (seabankForm) {
                seabankForm.addEventListener('submit', function(e) {
                    const accountNumber = document.getElementById('seabank-account').value;
                    const mobileNumber = document.getElementById('seabank-mobile').value;
                    const otp = document.getElementById('seabank-otp').value;

                    if (accountNumber.length !== 10 || !/^\d+$/.test(accountNumber)) {
                        alert('Please enter a valid 10-digit SeaBank account number');
                        e.preventDefault();
                        return;
                    }

                    if (mobileNumber.length !== 11 || !/^\d+$/.test(mobileNumber)) {
                        alert('Please enter a valid 11-digit mobile number');
                        e.preventDefault();
                        return;
                    }

                    if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                        alert('Please enter a valid 6-digit OTP');
                        e.preventDefault();
                        return;
                    }
                });
            }

            // Form validation for GCash
            const gcashForm = document.getElementById('gcash-form');
            if (gcashForm) {
                gcashForm.addEventListener('submit', function(e) {
                    const mobileNumber = document.getElementById('gcash-mobile').value;
                    const otp = document.getElementById('gcash-otp').value;
                    const pin = document.getElementById('gcash-pin').value;

                    if (mobileNumber.length !== 11 || !/^\d+$/.test(mobileNumber)) {
                        alert('Please enter a valid 11-digit mobile number');
                        e.preventDefault();
                        return;
                    }

                    if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                        alert('Please enter a valid 6-digit OTP');
                        e.preventDefault();
                        return;
                    }

                    if (pin.length !== 4 || !/^\d+$/.test(pin)) {
                        alert('Please enter a valid 4-digit GCash MPIN');
                        e.preventDefault();
                        return;
                    }
                });
            }
        });