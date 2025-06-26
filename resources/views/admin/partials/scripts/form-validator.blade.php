{{-- Form Validator Script --}}
<script>
class FormValidator {
    constructor(formSelector = 'form', config = {}) {
        this.forms = document.querySelectorAll(formSelector);
        this.config = {
            showErrors: true,
            submitButton: 'button[type="submit"]',
            errorClass: 'is-invalid',
            successClass: 'is-valid',
            ...config
        };
        
        this.init();
    }
    
    init() {
        this.forms.forEach(form => {
            this.bindFormEvents(form);
        });
    }
    
    bindFormEvents(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                if (input.classList.contains(this.config.errorClass)) {
                    this.validateField(input);
                }
            });
        });
        
        // Form submission
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
                this.focusFirstError(form);
            }
        });
    }
    
    validateField(field) {
        const rules = this.getFieldRules(field);
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        // Required validation
        if (rules.required && !value) {
            isValid = false;
            errorMessage = 'هذا الحقل مطلوب';
        }
        
        // Email validation
        if (isValid && rules.email && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
            }
        }
        
        // Number validation
        if (isValid && rules.number && value) {
            if (isNaN(value)) {
                isValid = false;
                errorMessage = 'يرجى إدخال رقم صحيح';
            }
        }
        
        // Min length validation
        if (isValid && rules.minLength && value.length < rules.minLength) {
            isValid = false;
            errorMessage = `يجب أن يكون الحد الأدنى ${rules.minLength} أحرف`;
        }
        
        // Max length validation
        if (isValid && rules.maxLength && value.length > rules.maxLength) {
            isValid = false;
            errorMessage = `يجب أن يكون الحد الأقصى ${rules.maxLength} أحرف`;
        }
        
        // Pattern validation
        if (isValid && rules.pattern && value) {
            const regex = new RegExp(rules.pattern);
            if (!regex.test(value)) {
                isValid = false;
                errorMessage = 'تنسيق الحقل غير صحيح';
            }
        }
        
        this.updateFieldStatus(field, isValid, errorMessage);
        return isValid;
    }
    
    getFieldRules(field) {
        return {
            required: field.hasAttribute('required'),
            email: field.type === 'email',
            number: field.type === 'number',
            minLength: field.getAttribute('minlength'),
            maxLength: field.getAttribute('maxlength'),
            pattern: field.getAttribute('pattern')
        };
    }
    
    updateFieldStatus(field, isValid, errorMessage) {
        // Remove existing classes
        field.classList.remove(this.config.errorClass, this.config.successClass);
        
        // Add appropriate class
        if (isValid) {
            field.classList.add(this.config.successClass);
        } else {
            field.classList.add(this.config.errorClass);
        }
        
        // Handle error message
        if (this.config.showErrors) {
            this.updateErrorMessage(field, isValid, errorMessage);
        }
    }
    
    updateErrorMessage(field, isValid, errorMessage) {
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        
        if (!isValid && errorMessage) {
            if (existingError) {
                existingError.textContent = errorMessage;
            } else {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errorMessage;
                field.parentNode.appendChild(errorDiv);
            }
        } else if (isValid && existingError) {
            existingError.style.display = 'none';
        }
    }
    
    validateForm(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let isFormValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isFormValid = false;
            }
        });
        
        return isFormValid;
    }
    
    focusFirstError(form) {
        const firstError = form.querySelector(`.${this.config.errorClass}`);
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// File preview function
function previewFile(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById(input.id + '_preview');
    const previewImg = document.getElementById(input.id + '_preview_img');
    
    if (file && previewContainer && previewImg) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.formValidator = new FormValidator();
});
</script>