document.addEventListener('DOMContentLoaded', function() {
    // Get token from URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    
    // Set token in hidden field
    if (token) {
        document.getElementById('token').value = token;
    } else {
        // If no token, show error and redirect
        showError('Invalid password reset link. Please request a new one.');
        setTimeout(() => {
            window.location.href = 'forgot-password.html';
        }, 3000);
        return;
    }
    
    const updatePasswordForm = document.getElementById('updatePasswordForm');
    
    updatePasswordForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const submitBtn = this.querySelector('.auth-btn');
        const originalBtnText = submitBtn.innerHTML;
        
        // Validate passwords
        if (password.length < 6) {
            showError('Password must be at least 6 characters long');
            return;
        }
        
        if (password !== confirmPassword) {
            showError('Passwords do not match');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        try {
            const response = await fetch('update-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `token=${encodeURIComponent(token)}&password=${encodeURIComponent(password)}`
            });
            
            // Get the response text first
            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            // Try to parse as JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse server response as JSON:', responseText);
                
                // Check for specific database error messages
                if (responseText.includes("Table") && responseText.includes("doesn't exist")) {
                    throw new Error('Database setup incomplete. Please contact the administrator.');
                } else {
                    throw new Error('Server returned invalid response format');
                }
            }
            
            if (result.success) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success';
                successDiv.style.backgroundColor = '#dff0d8';
                successDiv.style.color = '#3c763d';
                successDiv.style.padding = '15px';
                successDiv.style.marginTop = '20px';
                successDiv.style.borderRadius = '4px';
                successDiv.textContent = result.message || 'Password updated successfully!';
                
                // Remove any existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());
                
                updatePasswordForm.appendChild(successDiv);
                updatePasswordForm.reset();
                
                // Redirect to login page after 3 seconds
                setTimeout(() => {
                    window.location.href = 'login.html';
                }, 3000);
            } else {
                // Check for specific database error in the result message
                if (result.message && result.message.includes("Table") && result.message.includes("doesn't exist")) {
                    showError('Database setup incomplete. Please contact the administrator.');
                } else {
                    showError(result.message || 'Failed to update password');
                }
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
    errorDiv.style.color = '#a94442';
    errorDiv.style.padding = '15px';
    errorDiv.style.marginTop = '20px';
    errorDiv.style.borderRadius = '4px';
    errorDiv.textContent = message;
    
    const existingError = document.querySelector('.alert');
    if (existingError) {
        existingError.remove();
    }
    
    document.getElementById('updatePasswordForm').appendChild(errorDiv);
}