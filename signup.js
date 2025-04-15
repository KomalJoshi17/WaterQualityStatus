// Password strength checker
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthMeter = document.querySelector('.strength-meter');
    let strength = 0;

    // Calculate password strength
    if(password.length >= 8) strength += 25;
    if(password.match(/[0-9]/)) strength += 25;
    if(password.match(/[a-z]/)) strength += 25;
    if(password.match(/[A-Z]/)) strength += 25;

    // Update strength meter
    strengthMeter.style.width = strength + '%';
    
    // Update color based on strength
    if(strength <= 25) strengthMeter.style.backgroundColor = '#dc3545';
    else if(strength <= 50) strengthMeter.style.backgroundColor = '#ffc107';
    else if(strength <= 75) strengthMeter.style.backgroundColor = '#17a2b8';
    else strengthMeter.style.backgroundColor = '#28a745';
});

// Generate Captcha
function generateCaptcha() {
    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let captcha = '';
    for(let i = 0; i < 6; i++) {
        captcha += chars[Math.floor(Math.random() * chars.length)];
    }
    document.getElementById('captcha').textContent = captcha;
}

// Initial captcha generation
generateCaptcha();

// Refresh captcha
document.querySelector('.refresh-captcha').addEventListener('click', generateCaptcha);

// Form submission handler
document.getElementById('signupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Verify captcha and terms (existing code remains)
    const userCaptcha = this.captcha.value;
    const displayedCaptcha = document.getElementById('captcha').textContent;
    
    if (userCaptcha !== displayedCaptcha) {
        alert('Invalid captcha! Please try again.');
        generateCaptcha();
        this.captcha.value = '';
        return;
    }

    // Verify terms acceptance
    if (!this.terms.checked) {
        alert('Please accept the Terms and Conditions');
        return;
    }

    try {
        const formData = new FormData(this);
        const response = await fetch('signup.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                title: 'Success!',
                text: 'Registration completed successfully!',
                icon: 'success',
                confirmButtonText: 'Continue'
            }).then((result) => {
                window.location.href = 'water_quality.html';
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: result.message || 'Registration failed',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred during registration',
            icon: 'error',
            confirmButtonText: 'Try Again'
        });
    }
});

// Add ripple effect to buttons
document.querySelectorAll('.auth-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});