/**
 * Form Validator Module
 * Replaces Angular's form validation functionality
 * Provides form validation with custom rules and error messages
 */

class FormValidator {
    constructor(formElement, options = {}) {
        this.form = formElement;
        this.errors = new Map();
        this.touched = new Set();

        this.config = {
            validateOnBlur: true,
            validateOnInput: false,
            showErrorMessages: true,
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorMessageClass: 'invalid-feedback',
            ...options
        };

        this.rules = new Map();
        this.customValidators = new Map();

        this.init();
    }

    /**
     * Initialize the validator
     */
    init() {
        this.setupDefaultValidators();
        this.parseFormValidation();
        this.attachEventListeners();
    }

    /**
     * Setup default validators
     */
    setupDefaultValidators() {
        // Required validator
        this.addValidator('required', (value, element) => {
            if (element.type === 'checkbox') {
                return element.checked;
            }
            return value !== null && value !== undefined && value.trim() !== '';
        }, 'Field ini wajib diisi');

        // Email validator
        this.addValidator('email', (value) => {
            if (!value) return true; // Allow empty if not required
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(value);
        }, 'Sila masukkan alamat emel yang sah');

        // Pattern validator
        this.addValidator('pattern', (value, element) => {
            if (!value) return true; // Allow empty if not required
            const pattern = element.getAttribute('pattern');
            if (!pattern) return true;
            const regex = new RegExp(pattern);
            return regex.test(value);
        }, 'Format tidak sah');

        // Min length validator
        this.addValidator('minlength', (value, element) => {
            if (!value) return true;
            const minLength = parseInt(element.getAttribute('minlength'));
            return value.length >= minLength;
        }, (element) => {
            const minLength = element.getAttribute('minlength');
            return `Minimum ${minLength} aksara diperlukan`;
        });

        // Max length validator
        this.addValidator('maxlength', (value, element) => {
            if (!value) return true;
            const maxLength = parseInt(element.getAttribute('maxlength'));
            return value.length <= maxLength;
        }, (element) => {
            const maxLength = element.getAttribute('maxlength');
            return `Maksimum ${maxLength} aksara sahaja`;
        });

        // Min value validator
        this.addValidator('min', (value, element) => {
            if (!value) return true;
            const min = parseFloat(element.getAttribute('min'));
            return parseFloat(value) >= min;
        }, (element) => {
            const min = element.getAttribute('min');
            return `Nilai minimum ialah ${min}`;
        });

        // Max value validator
        this.addValidator('max', (value, element) => {
            if (!value) return true;
            const max = parseFloat(element.getAttribute('max'));
            return parseFloat(value) <= max;
        }, (element) => {
            const max = element.getAttribute('max');
            return `Nilai maksimum ialah ${max}`;
        });

        // Number validator
        this.addValidator('number', (value) => {
            if (!value) return true;
            return !isNaN(parseFloat(value)) && isFinite(value);
        }, 'Sila masukkan nombor yang sah');

        // URL validator
        this.addValidator('url', (value) => {
            if (!value) return true;
            try {
                new URL(value);
                return true;
            } catch {
                return false;
            }
        }, 'Sila masukkan URL yang sah');

        // Date validator
        this.addValidator('date', (value) => {
            if (!value) return true;
            const date = new Date(value);
            return date instanceof Date && !isNaN(date);
        }, 'Sila masukkan tarikh yang sah');
    }

    /**
     * Add a custom validator
     */
    addValidator(name, validatorFn, errorMessage) {
        this.customValidators.set(name, {
            validate: validatorFn,
            message: errorMessage
        });
    }

    /**
     * Parse form for validation rules
     */
    parseFormValidation() {
        const inputs = this.form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            const rules = [];

            // Check for required
            if (input.hasAttribute('required')) {
                rules.push('required');
            }

            // Check for pattern
            if (input.hasAttribute('pattern')) {
                rules.push('pattern');
            }

            // Check for email
            if (input.type === 'email') {
                rules.push('email');
            }

            // Check for number
            if (input.type === 'number') {
                rules.push('number');
            }

            // Check for url
            if (input.type === 'url') {
                rules.push('url');
            }

            // Check for date
            if (input.type === 'date') {
                rules.push('date');
            }

            // Check for minlength
            if (input.hasAttribute('minlength')) {
                rules.push('minlength');
            }

            // Check for maxlength
            if (input.hasAttribute('maxlength')) {
                rules.push('maxlength');
            }

            // Check for min
            if (input.hasAttribute('min')) {
                rules.push('min');
            }

            // Check for max
            if (input.hasAttribute('max')) {
                rules.push('max');
            }

            // Check for custom validators via data attribute
            const customValidators = input.getAttribute('data-validators');
            if (customValidators) {
                rules.push(...customValidators.split(',').map(v => v.trim()));
            }

            if (rules.length > 0) {
                this.rules.set(input, rules);
            }
        });
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        // Form submit
        this.form.addEventListener('submit', (e) => {
            if (!this.validateAll()) {
                e.preventDefault();
                this.focusFirstError();
            }
        });

        // Input validation
        this.rules.forEach((rules, input) => {
            if (this.config.validateOnBlur) {
                input.addEventListener('blur', () => {
                    this.touched.add(input);
                    this.validateInput(input);
                });
            }

            if (this.config.validateOnInput) {
                input.addEventListener('input', () => {
                    if (this.touched.has(input)) {
                        this.validateInput(input);
                    }
                });
            }
        });
    }

    /**
     * Validate a single input
     */
    validateInput(input) {
        const rules = this.rules.get(input);
        if (!rules) return true;

        const value = input.value;
        let isValid = true;
        let errorMessage = '';

        for (const rule of rules) {
            const validator = this.customValidators.get(rule);
            if (!validator) continue;

            const result = validator.validate(value, input);
            if (!result) {
                isValid = false;
                errorMessage = typeof validator.message === 'function'
                    ? validator.message(input)
                    : validator.message;
                break;
            }
        }

        if (isValid) {
            this.clearError(input);
        } else {
            this.setError(input, errorMessage);
        }

        return isValid;
    }

    /**
     * Validate all inputs
     */
    validateAll() {
        let isValid = true;

        this.rules.forEach((rules, input) => {
            this.touched.add(input);
            if (!this.validateInput(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Set error for an input
     */
    setError(input, message) {
        this.errors.set(input, message);

        // Add error class
        input.classList.add(this.config.errorClass);
        input.classList.remove(this.config.validClass);

        // Show error message
        if (this.config.showErrorMessages) {
            this.showErrorMessage(input, message);
        }
    }

    /**
     * Clear error for an input
     */
    clearError(input) {
        this.errors.delete(input);

        // Remove error class
        input.classList.remove(this.config.errorClass);
        if (this.touched.has(input)) {
            input.classList.add(this.config.validClass);
        }

        // Hide error message
        if (this.config.showErrorMessages) {
            this.hideErrorMessage(input);
        }
    }

    /**
     * Show error message
     */
    showErrorMessage(input, message) {
        let errorElement = input.parentElement.querySelector(`.${this.config.errorMessageClass}`);

        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = this.config.errorMessageClass;
            input.parentElement.appendChild(errorElement);
        }

        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    /**
     * Hide error message
     */
    hideErrorMessage(input) {
        const errorElement = input.parentElement.querySelector(`.${this.config.errorMessageClass}`);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    /**
     * Focus first error
     */
    focusFirstError() {
        const firstError = Array.from(this.errors.keys())[0];
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    /**
     * Check if form is valid
     */
    isValid() {
        return this.errors.size === 0;
    }

    /**
     * Get all errors
     */
    getErrors() {
        return Array.from(this.errors.entries()).map(([input, message]) => ({
            input,
            name: input.name || input.id,
            message
        }));
    }

    /**
     * Reset validation
     */
    reset() {
        this.errors.clear();
        this.touched.clear();

        this.rules.forEach((rules, input) => {
            input.classList.remove(this.config.errorClass, this.config.validClass);
            this.hideErrorMessage(input);
        });
    }

    /**
     * Destroy validator
     */
    destroy() {
        this.errors.clear();
        this.touched.clear();
        this.rules.clear();
        this.customValidators.clear();
    }

    /**
     * Static method to validate a form
     */
    static validate(formElement, options = {}) {
        const validator = new FormValidator(formElement, options);
        return validator.validateAll();
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormValidator;
}
