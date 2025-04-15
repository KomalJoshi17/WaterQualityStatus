document.addEventListener('DOMContentLoaded', function() {
    // Check for saved credentials
    if(getCookie('email') && getCookie('password')) {
        document.querySelector('input[name="email"]').value = getCookie('email');
        document.querySelector('input[name="password"]').value = getCookie('password');
        document.querySelector('input[name="remember"]').checked = true;
    }
});

// Helper function to get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

const loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = this.email.value;
    const password = this.password.value;
    
    // Basic validation
    if (!email || !password) {
        alert('Please fill in all fields');
        return;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        return;
    }
    
    // If validation passes, submit the form
    this.submit();
});

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Password input focus effect
const passwordInput = document.getElementById('password');

passwordInput.addEventListener('focus', function() {
    this.parentElement.classList.add('focused');
});

passwordInput.addEventListener('blur', function() {
    this.parentElement.classList.remove('focused');
});