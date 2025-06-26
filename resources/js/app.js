// Combined Authentication Scripts
// Works for both login and forgot password pages

(function () {
    'use strict';

    // ==============================================
    // UTILITY FUNCTIONS
    // ==============================================
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function addInputValidationStyles(input, isValid) {
        if (isValid) {
            input.style.borderColor = 'var(--success-color, #10b981)';
            input.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
        } else {
            input.style.borderColor = 'var(--danger-color, #ef4444)';
            input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        }
    }

    function resetInputStyles(input) {
        input.style.borderColor = '#e5e7eb';
        input.style.boxShadow = 'none';
    }

    // ==============================================
    // PASSWORD TOGGLE FUNCTIONALITY
    // ==============================================
    function initPasswordToggle() {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');

                // Add visual feedback
                this.style.transform = 'translateY(-50%) scale(0.9)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 150);
            });
        }
    }

    // ==============================================
    // FORM SUBMISSION HANDLING
    // ==============================================
    function initFormSubmission() {
        // Handle login form
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        if (loginForm && loginBtn) {
            const spinner = loginBtn.querySelector('#spinner') || document.getElementById('spinner');

            loginForm.addEventListener('submit', function (e) {
                loginBtn.disabled = true;
                if (spinner) spinner.style.display = 'inline-block';
                const btnText = loginBtn.querySelector('span');
                if (btnText) btnText.textContent = 'جاري تسجيل الدخول...';
            });
        }

        // Handle forgot password form
        const forgotForm = document.getElementById('forgotForm');
        const resetBtn = document.getElementById('resetBtn');

        if (forgotForm && resetBtn) {
            const spinner = resetBtn.querySelector('#spinner') || document.getElementById('spinner');

            forgotForm.addEventListener('submit', function (e) {
                resetBtn.disabled = true;
                if (spinner) spinner.style.display = 'inline-block';
                const btnText = resetBtn.querySelector('span');
                if (btnText) btnText.textContent = 'جاري الإرسال...';
            });
        }
    }

    // ==============================================
    // INPUT FOCUS ANIMATIONS
    // ==============================================
    function initInputAnimations() {
        const inputs = document.querySelectorAll('.form-input');

        inputs.forEach(input => {
            const container = input.closest('.input-container') || input.parentElement;
            const formGroup = input.closest('.form-group');
            const label = formGroup ? formGroup.querySelector('.form-label') : null;

            input.addEventListener('focus', function () {
                if (container) container.style.transform = 'scale(1.02)';
                if (label) label.style.color = 'var(--primary-color, #3b82f6)';
            });

            input.addEventListener('blur', function () {
                if (container) container.style.transform = 'scale(1)';
                if (label) label.style.color = '#374151';
            });
        });
    }

    // ==============================================
    // FORM VALIDATION
    // ==============================================
    function initFormValidation() {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        // Email validation
        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                if (this.value && !validateEmail(this.value)) {
                    addInputValidationStyles(this, false);
                } else if (this.value) {
                    addInputValidationStyles(this, true);
                } else {
                    resetInputStyles(this);
                }
            });

            emailInput.addEventListener('input', function () {
                if (this.value && validateEmail(this.value)) {
                    addInputValidationStyles(this, true);
                } else if (this.value) {
                    addInputValidationStyles(this, false);
                } else {
                    resetInputStyles(this);
                }
            });
        }

        // Password validation (for login page)
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                if (this.value.length > 0 && this.value.length < 6) {
                    addInputValidationStyles(this, false);
                } else if (this.value.length >= 6) {
                    addInputValidationStyles(this, true);
                } else {
                    resetInputStyles(this);
                }
            });
        }
    }

    // ==============================================
    // MESSAGE AUTO-HIDE
    // ==============================================
    function initMessageAutoHide() {
        const messages = document.querySelectorAll('.success-message, .error-message, .info-message');

        messages.forEach(message => {
            // Use different timeout for different pages
            const timeout = document.getElementById('loginForm') ? 5000 : 7000;

            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 300);
            }, timeout);
        });
    }

    // ==============================================
    // KEYBOARD SHORTCUTS
    // ==============================================
    function initKeyboardShortcuts() {
        document.addEventListener('keydown', function (e) {
            // Alt + E to focus email field (forgot password page)
            // Alt + L to focus email field (login page)
            if ((e.altKey && e.key === 'e') || (e.altKey && e.key === 'l')) {
                e.preventDefault();
                const emailField = document.getElementById('email');
                if (emailField) emailField.focus();
            }

            // Alt + P to focus password field
            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                const passwordField = document.getElementById('password');
                if (passwordField) passwordField.focus();
            }
        });
    }

    // ==============================================
    // ENHANCED NAVIGATION
    // ==============================================
    function initEnhancedNavigation() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Enhanced Enter key navigation
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    e.preventDefault();
                    // Move to next input or submit form
                    const allInputs = Array.from(document.querySelectorAll('input:not([type="hidden"]), button[type="submit"]'));
                    const currentIndex = allInputs.indexOf(e.target);
                    const nextInput = allInputs[currentIndex + 1];

                    if (nextInput) {
                        nextInput.focus();
                        // If it's a submit button, trigger click
                        if (nextInput.type === 'submit') {
                            nextInput.click();
                        }
                    }
                }
            });
        });
    }

    // ==============================================
    // INITIALIZATION
    // ==============================================
    function init() {
        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        // Initialize all features
        initPasswordToggle();
        initFormSubmission();
        initInputAnimations();
        initFormValidation();
        initMessageAutoHide();
        initKeyboardShortcuts();
        initEnhancedNavigation();

        // Add some basic CSS variables if they don't exist
        if (!getComputedStyle(document.documentElement).getPropertyValue('--primary-color')) {
            document.documentElement.style.setProperty('--primary-color', '#3b82f6');
            document.documentElement.style.setProperty('--success-color', '#10b981');
            document.documentElement.style.setProperty('--danger-color', '#ef4444');
        }
    }

    // Start initialization
    init();

})();