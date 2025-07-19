document.addEventListener("DOMContentLoaded", function () {
    const editProfileBtn = document.getElementById("editProfileBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const saveProfileBtn = document.getElementById("saveProfileBtn");
    const profileForm = document.getElementById("profileForm");
    
    // Parse address on page load
    parseAddressOnLoad();
    
    // Parse existing address into separate fields
    function parseAddressOnLoad() {
        const addressDisplay = document.querySelector('[data-field="address"] .detail-display');
        if (!addressDisplay) return;
        
        const currentAddress = addressDisplay.textContent.trim();
        if (currentAddress && currentAddress !== 'Not provided') {
            const addressParts = currentAddress.split(',').map(part => part.trim());
            
            // Try to parse the address parts
            const streetInput = document.getElementById('street');
            const cityInput = document.getElementById('city');
            const stateInput = document.getElementById('state');
            const zipInput = document.getElementById('zipCode');
            const countrySelect = document.getElementById('country');
            
            if (addressParts.length >= 5) {
                if (streetInput) {
                    streetInput.value = addressParts[0] || '';
                    streetInput.setAttribute('data-original-value', addressParts[0] || '');
                }
                if (cityInput) {
                    cityInput.value = addressParts[1] || '';
                    cityInput.setAttribute('data-original-value', addressParts[1] || '');
                }
                if (stateInput) {
                    stateInput.value = addressParts[2] || '';
                    stateInput.setAttribute('data-original-value', addressParts[2] || '');
                }
                if (zipInput) {
                    zipInput.value = addressParts[3] || '';
                    zipInput.setAttribute('data-original-value', addressParts[3] || '');
                }
                if (countrySelect) {
                    countrySelect.value = addressParts[4] || '';
                    countrySelect.setAttribute('data-original-value', addressParts[4] || '');
                }
            } else {
                // If parsing fails, put the whole address in street field
                if (streetInput) {
                    streetInput.value = currentAddress;
                    streetInput.setAttribute('data-original-value', currentAddress);
                }
            }
        }
    }
    
    // Toggle between view and edit mode
    function toggleEditMode(isEditing) {
        const detailItems = document.querySelectorAll('.detail-item');
        const profileActions = document.querySelector('.profile-actions');
        
        detailItems.forEach(item => {
            const field = item.getAttribute('data-field');
            const label = item.querySelector('label');
            const display = item.querySelector('.detail-display');
            const input = item.querySelector('.detail-input');
            const readonly = item.querySelector('.detail-readonly');
            const addressFields = item.querySelector('.address-fields');
            
            if (field === 'email') {
                // Email field - show readonly message in edit mode
                if (isEditing) {
                    if (display) display.style.display = 'none';
                    if (readonly) readonly.style.display = 'block';
                } else {
                    if (display) display.style.display = 'block';
                    if (readonly) readonly.style.display = 'none';
                }
            } else if (field === 'address') {
                // Address field - show individual fields in edit mode
                if (isEditing) {
                    if (display) display.style.display = 'none';
                    if (addressFields) addressFields.style.display = 'block';
                } else {
                    if (display) display.style.display = 'block';
                    if (addressFields) addressFields.style.display = 'none';
                }
            } else if (input) {
                // Other editable fields
                if (isEditing) {
                    display.style.display = 'none';
                    input.style.display = 'block';
                    if (field === 'first_name') {
                        input.focus();
                    }
                } else {
                    display.style.display = 'block';
                    input.style.display = 'none';
                }
            }
        });
        
        // Toggle action buttons
        if (isEditing) {
            profileActions.classList.add('editing');
        } else {
            profileActions.classList.remove('editing');
        }
    }
    
    // Enter edit mode
    editProfileBtn?.addEventListener("click", function(e) {
        e.preventDefault();
        toggleEditMode(true);
    });
    
    // Cancel edit mode
    cancelEditBtn?.addEventListener("click", function(e) {
        e.preventDefault();
        
        // Reset form values to original
        const inputs = document.querySelectorAll('.detail-input, .address-input');
        inputs.forEach(input => {
            const originalValue = input.getAttribute('data-original-value');
            if (originalValue !== null) {
                input.value = originalValue;
            }
        });
        
        toggleEditMode(false);
    });
    
    // Save profile changes
    profileForm?.addEventListener("submit", function(e) {
        e.preventDefault();
        
        // Validate address fields
        const street = document.getElementById('street')?.value.trim() || '';
        const city = document.getElementById('city')?.value.trim() || '';
        const state = document.getElementById('state')?.value.trim() || '';
        const zipCode = document.getElementById('zipCode')?.value.trim() || '';
        const country = document.getElementById('country')?.value || '';
        
        // Client-side validation
        const errors = [];
        
        const firstName = document.querySelector('[name="first_name"]')?.value.trim();
        const lastName = document.querySelector('[name="last_name"]')?.value.trim();
        const phoneNumber = document.querySelector('[name="phone_number"]')?.value.trim();
        
        // Validate required fields
        if (!firstName) errors.push('First name is required');
        if (!lastName) errors.push('Last name is required');
        
        // Validate name formats
        if (firstName && !/^[A-Za-z\s'-]+$/.test(firstName)) {
            errors.push('First name should only contain letters and spaces');
        }
        if (lastName && !/^[A-Za-z\s'-]+$/.test(lastName)) {
            errors.push('Last name should only contain letters and spaces');
        }
        
        // Validate phone number
        if (phoneNumber) {
            const digitsOnly = phoneNumber.replace(/[^0-9]/g, '');
            if (digitsOnly.length !== 11) {
                errors.push('Phone number must be exactly 11 digits');
            } else if (!/^[0-9+\-\s()]+$/.test(phoneNumber)) {
                errors.push('Phone number contains invalid characters');
            }
        }
        
        // Validate address fields if any are filled
        if (street || city || state || zipCode || country) {
            if (!street || street.length < 5) errors.push('Street address must be at least 5 characters long');
            if (!city || city.length < 2) errors.push('City must be at least 2 characters long');
            if (!state || state.length < 2) errors.push('State/Province must be at least 2 characters long');
            if (!zipCode || !/^[0-9]{4,10}$/.test(zipCode)) errors.push('ZIP code must be 4-10 digits');
            if (!country) errors.push('Please select a country');
        }
        
        if (errors.length > 0) {
            showMessage(errors.join(', '), 'error');
            return;
        }
        
        const formData = new FormData(profileForm);
        
        // Add address fields to form data
        formData.set('street', street);
        formData.set('city', city);
        formData.set('state', state);
        formData.set('zipCode', zipCode);
        formData.set('country', country);
        
        const saveBtn = document.getElementById("saveProfileBtn");
        const originalText = saveBtn.textContent;
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";
        
        fetch("actions/update_profile.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update display values with new data
                updateDisplayValues(data.data);
                
                // Show success message
                showMessage("Profile updated successfully!", "success");
                
                // Exit edit mode
                toggleEditMode(false);
            } else {
                showMessage(data.message || "Failed to update profile", "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage("An error occurred while updating profile", "error");
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
        });
    });
    
    // Update display values after successful save
    function updateDisplayValues(data) {
        const firstNameDisplay = document.querySelector('[data-field="first_name"] .detail-display');
        const lastNameDisplay = document.querySelector('[data-field="last_name"] .detail-display');
        const phoneDisplay = document.querySelector('[data-field="phone_number"] .detail-display');
        const addressDisplay = document.querySelector('[data-field="address"] .detail-display');
        
        if (firstNameDisplay) firstNameDisplay.textContent = data.first_name;
        if (lastNameDisplay) lastNameDisplay.textContent = data.last_name;
        if (phoneDisplay) phoneDisplay.textContent = data.phone_number || 'Not provided';
        if (addressDisplay) addressDisplay.textContent = data.address || 'Not provided';
        
        // Also update the profile header name
        const profileHeaderName = document.querySelector('.profile-info h3');
        if (profileHeaderName) {
            profileHeaderName.textContent = data.first_name + ' ' + data.last_name;
        }
        
        // Update original values for inputs
        const regularInputs = document.querySelectorAll('.detail-input');
        regularInputs.forEach(input => {
            const field = input.getAttribute('name');
            if (data[field] !== undefined) {
                input.setAttribute('data-original-value', data[field]);
            }
        });
        
        // Update original values for address inputs
        const addressParts = (data.address || '').split(',').map(part => part.trim());
        const addressInputs = [
            { input: document.getElementById('street'), value: addressParts[0] || '' },
            { input: document.getElementById('city'), value: addressParts[1] || '' },
            { input: document.getElementById('state'), value: addressParts[2] || '' },
            { input: document.getElementById('zipCode'), value: addressParts[3] || '' },
            { input: document.getElementById('country'), value: addressParts[4] || '' }
        ];
        
        addressInputs.forEach(item => {
            if (item.input) {
                item.input.setAttribute('data-original-value', item.value);
                item.input.value = item.value;
            }
        });
    }
    
    // Show success/error messages
    function showMessage(message, type) {
        // Remove any existing messages
        const existingMessage = document.querySelector('.profile-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `profile-message ${type}`;
        messageDiv.textContent = message;
        
        // Insert message at the top of profile content
        const profileContent = document.querySelector('.profile-content');
        profileContent.insertBefore(messageDiv, profileContent.firstChild);
        
        // Auto-remove message after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
});
