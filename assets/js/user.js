document.addEventListener("DOMContentLoaded", function () {
    const editProfileBtn = document.getElementById("editProfileBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const saveProfileBtn = document.getElementById("saveProfileBtn");
    const profileForm = document.getElementById("profileForm");
    
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
            
            if (field === 'email') {
                // Email field - show readonly message in edit mode
                if (isEditing) {
                    if (display) display.style.display = 'none';
                    if (readonly) readonly.style.display = 'block';
                } else {
                    if (display) display.style.display = 'block';
                    if (readonly) readonly.style.display = 'none';
                }
            } else if (input) {
                // Editable fields
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
        const inputs = document.querySelectorAll('.detail-input');
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
        
        const formData = new FormData(profileForm);
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
        const inputs = document.querySelectorAll('.detail-input');
        inputs.forEach(input => {
            const field = input.getAttribute('name');
            if (data[field] !== undefined) {
                input.setAttribute('data-original-value', data[field]);
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
