// Error page JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if resend form exists on the page
    const resendForm = document.getElementById('resendForm');
    if (!resendForm) return;

    // Form submission handling
    resendForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('emailInput').value;
        const resendBtn = document.getElementById('resendBtn');
        const feedbackMessage = document.getElementById('feedbackMessage');
        
        // Disable button during request
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending...';
        
        // Send AJAX request
        fetch('actions/resend_verification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email=' + encodeURIComponent(email)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success feedback message
                feedbackMessage.style.display = 'block';
                feedbackMessage.textContent = data.message;
                feedbackMessage.style.backgroundColor = '#d4edda';
                feedbackMessage.style.color = '#155724';
                feedbackMessage.style.border = '1px solid #c3e6cb';
                
                // Start cooldown if provided
                if (data.cooldown && data.cooldown > 0) {
                    startCooldown(data.cooldown);
                }
            } else {
                // Check if it's a cooldown error
                if (data.cooldown && data.cooldown > 0) {
                    // For cooldown, just start the timer without showing error message
                    startCooldown(data.cooldown);
                } else {
                    // Show error message for non-cooldown errors
                    feedbackMessage.style.display = 'block';
                    feedbackMessage.textContent = data.message;
                    feedbackMessage.style.backgroundColor = '#f8d7da';
                    feedbackMessage.style.color = '#721c24';
                    feedbackMessage.style.border = '1px solid #f5c6cb';
                    
                    // Re-enable button for other errors
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Link';
                }
            }
        })
        .catch(error => {
            // Network error handling
            feedbackMessage.style.display = 'block';
            feedbackMessage.style.backgroundColor = '#f8d7da';
            feedbackMessage.style.color = '#721c24';
            feedbackMessage.style.border = '1px solid #f5c6cb';
            feedbackMessage.textContent = 'Network error. Please try again.';
            
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend Link';
        });
    });
    
    // Check for URL-based cooldown on page load
    const urlParams = new URLSearchParams(window.location.search);
    const cooldownParam = urlParams.get('cooldown');
    if (cooldownParam && parseInt(cooldownParam) > 0) {
        startCooldown(parseInt(cooldownParam));
    }
});

function startCooldown(cooldownTime) {
    const resendBtn = document.getElementById('resendBtn');
    const feedbackMessage = document.getElementById('feedbackMessage');
    let countdown = document.getElementById('countdown');
    let cooldownMessage = document.getElementById('cooldownMessage');
    
    // Create cooldown elements if they don't exist
    if (!cooldownMessage) {
        cooldownMessage = document.createElement('div');
        cooldownMessage.id = 'cooldownMessage';
        cooldownMessage.style.cssText = 'margin-top: 10px; color: #666;';
        cooldownMessage.innerHTML = 'Please wait <span id="countdown"></span> before requesting again.';
        document.querySelector('.container').appendChild(cooldownMessage);
        countdown = document.getElementById('countdown');
    }
    
    // Show cooldown message
    cooldownMessage.style.display = 'block';
    
    // Disable button and style it
    resendBtn.disabled = true;
    resendBtn.style.opacity = '0.5';
    resendBtn.style.cursor = 'not-allowed';
    resendBtn.textContent = 'Resend Link';
    
    const timer = setInterval(() => {
        cooldownTime--;
        
        // Format time as minutes:seconds
        const minutes = Math.floor(cooldownTime / 60);
        const seconds = cooldownTime % 60;
        const timeDisplay = minutes > 0 ?
            `${minutes}:${seconds.toString().padStart(2, '0')}` :
            `${seconds} seconds`;
        
        countdown.textContent = timeDisplay;
        
        if (cooldownTime <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendBtn.style.opacity = '1';
            resendBtn.style.cursor = 'pointer';
            cooldownMessage.style.display = 'none';
            
            // Hide success feedback message when cooldown ends
            if (feedbackMessage.style.backgroundColor === 'rgb(212, 237, 218)') {
                feedbackMessage.style.display = 'none';
            }
        }
    }, 1000);
}
