document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetForm');
    
    resetForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = this.email.value;
        const submitBtn = this.querySelector('.auth-btn');
        const originalBtnText = submitBtn.innerHTML;
        
        // Basic validation
        if (!email) {
            showError('Please enter your email address');
            return;
        }
        
        if (!isValidEmail(email)) {
            showError('Please enter a valid email address');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        
        try {
            const response = await fetch('reset-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}`
            });
            
            // Check if response is ok before trying to parse JSON
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            const responseText = await response.text();
            
            // Try to parse JSON, but handle case where response isn't valid JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Invalid JSON response:', responseText);
                throw new Error('Server returned invalid response format');
            }
            
            if (result.success) {
                // Show success message with the green background
                const successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success';
                successDiv.style.backgroundColor = '#dff0d8';
                successDiv.style.padding = '15px';
                successDiv.style.marginTop = '20px';
                successDiv.style.borderRadius = '4px';
                successDiv.textContent = result.message || 'Reset instructions have been sent to your email';
                
                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());
                
                resetForm.appendChild(successDiv);
                resetForm.reset();
            } else {
                showError(result.message || 'Failed to send reset email');
            }
        } catch (error) {
            console.error('Error:', error);
            showError(`Error: ${error.message || 'An error occurred. Please try again.'}`);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
});

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.style.backgroundColor = '#f2dede';
    errorDiv.style.padding = '15px';
    errorDiv.style.marginTop = '20px';
    errorDiv.style.borderRadius = '4px';
    errorDiv.textContent = message;
    
    const existingError = document.querySelector('.alert');
    if (existingError) {
        existingError.remove();
    }
    
    document.getElementById('resetForm').appendChild(errorDiv);
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}