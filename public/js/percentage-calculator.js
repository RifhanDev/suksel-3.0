/**
 * Percentage Calculator Module
 * Handles real-time percentage calculations and updates
 * Useful for shareholder percentage calculations and similar use cases
 */

class PercentageCalculator {
    constructor(options = {}) {
        this.inputs = [];
        this.totalElement = null;
        this.percentages = {};

        this.config = {
            minValue: 0,
            maxValue: 100,
            expectedTotal: 100,
            showWarning: true,
            warningClass: 'text-danger',
            successClass: 'text-success',
            allowExceed: false,
            ...options
        };

        this.init();
    }

    /**
     * Initialize the calculator
     */
    init() {
        // Will be set up by specific implementation
    }

    /**
     * Add an input field to track
     */
    addInput(element, name) {
        const input = {
            element,
            name,
            value: 0
        };

        this.inputs.push(input);
        this.attachInputListener(input);

        // Initialize value
        this.updateValue(input);
    }

    /**
     * Attach event listener to an input
     */
    attachInputListener(input) {
        input.element.addEventListener('input', () => {
            this.updateValue(input);
            this.calculate();
        });

        input.element.addEventListener('change', () => {
            this.updateValue(input);
            this.calculate();
        });
    }

    /**
     * Update value from input
     */
    updateValue(input) {
        const value = parseFloat(input.element.value) || 0;
        input.value = Math.max(this.config.minValue, value);

        // Enforce max value if not allowing exceed
        if (!this.config.allowExceed && input.value > this.config.maxValue) {
            input.value = this.config.maxValue;
            input.element.value = this.config.maxValue;
        }

        this.percentages[input.name] = input.value;
    }

    /**
     * Calculate total
     */
    calculate() {
        const total = this.inputs.reduce((sum, input) => sum + input.value, 0);

        // Update total element if exists
        if (this.totalElement) {
            this.totalElement.value = total;
            this.updateTotalDisplay(total);
        }

        // Trigger callback
        if (this.config.onChange) {
            this.config.onChange(this.percentages, total);
        }

        return total;
    }

    /**
     * Update total display with styling
     */
    updateTotalDisplay(total) {
        if (!this.totalElement) return;

        const parent = this.totalElement.parentElement;

        // Remove existing classes
        parent.classList.remove(this.config.warningClass, this.config.successClass);

        if (this.config.showWarning) {
            if (total === this.config.expectedTotal) {
                parent.classList.add(this.config.successClass);
            } else {
                parent.classList.add(this.config.warningClass);
            }
        }
    }

    /**
     * Set total element
     */
    setTotalElement(element) {
        this.totalElement = element;
        this.calculate();
    }

    /**
     * Get current total
     */
    getTotal() {
        return this.inputs.reduce((sum, input) => sum + input.value, 0);
    }

    /**
     * Get percentages object
     */
    getPercentages() {
        return { ...this.percentages };
    }

    /**
     * Check if total is valid
     */
    isValid() {
        const total = this.getTotal();
        return total === this.config.expectedTotal;
    }

    /**
     * Reset all values
     */
    reset() {
        this.inputs.forEach(input => {
            input.element.value = 0;
            input.value = 0;
        });
        this.percentages = {};
        this.calculate();
    }

    /**
     * Initialize from DOM
     * Looks for elements with data-percentage attribute
     */
    static initFromDOM(containerElement, options = {}) {
        const calculator = new PercentageCalculator(options);
        const inputs = containerElement.querySelectorAll('[data-percentage]');

        inputs.forEach(input => {
            const name = input.getAttribute('data-percentage');
            calculator.addInput(input, name);
        });

        // Find total element
        const totalElement = containerElement.querySelector('[data-percentage-total]');
        if (totalElement) {
            calculator.setTotalElement(totalElement);
        }

        return calculator;
    }
}

/**
 * Shareholder Percentage Calculator
 * Specialized calculator for shareholder percentages
 */
class ShareholderPercentageCalculator extends PercentageCalculator {
    constructor(options = {}) {
        super({
            expectedTotal: 100,
            showWarning: true,
            ...options
        });
    }

    /**
     * Initialize shareholder calculator from specific elements
     */
    static init(bumiInput, nonBumiInput, foreignerInput, totalInput, options = {}) {
        const calculator = new ShareholderPercentageCalculator(options);

        calculator.addInput(bumiInput, 'bumi');
        calculator.addInput(nonBumiInput, 'nonbumi');
        calculator.addInput(foreignerInput, 'foreigner');

        if (totalInput) {
            calculator.setTotalElement(totalInput);
        }

        return calculator;
    }

    /**
     * Get breakdown by type
     */
    getBreakdown() {
        return {
            bumiputera: this.percentages.bumi || 0,
            nonBumiputera: this.percentages.nonbumi || 0,
            foreigner: this.percentages.foreigner || 0,
            total: this.getTotal()
        };
    }

    /**
     * Check if company is bumiputera
     */
    isBumiputera(threshold = 51) {
        return (this.percentages.bumi || 0) >= threshold;
    }
}

/**
 * Live Calculator for any numeric inputs
 * Calculates sum of inputs in real-time
 */
class LiveCalculator {
    constructor(inputs, totalElement, options = {}) {
        this.inputs = Array.isArray(inputs) ? inputs : [inputs];
        this.totalElement = totalElement;
        this.config = {
            operation: 'sum', // sum, average, multiply
            decimals: 2,
            format: true,
            prefix: '',
            suffix: '',
            ...options
        };

        this.init();
    }

    /**
     * Initialize calculator
     */
    init() {
        this.inputs.forEach(input => {
            input.addEventListener('input', () => this.calculate());
            input.addEventListener('change', () => this.calculate());
        });

        this.calculate();
    }

    /**
     * Calculate based on operation
     */
    calculate() {
        const values = this.inputs.map(input => parseFloat(input.value) || 0);
        let result = 0;

        switch (this.config.operation) {
            case 'sum':
                result = values.reduce((sum, val) => sum + val, 0);
                break;
            case 'average':
                result = values.reduce((sum, val) => sum + val, 0) / values.length;
                break;
            case 'multiply':
                result = values.reduce((product, val) => product * val, 1);
                break;
            default:
                result = values.reduce((sum, val) => sum + val, 0);
        }

        // Round to decimals
        result = parseFloat(result.toFixed(this.config.decimals));

        // Update total element
        if (this.totalElement) {
            let displayValue = result;

            if (this.config.format) {
                displayValue = this.formatNumber(result);
            }

            displayValue = this.config.prefix + displayValue + this.config.suffix;

            if (this.totalElement.tagName === 'INPUT') {
                this.totalElement.value = displayValue;
            } else {
                this.totalElement.textContent = displayValue;
            }
        }

        // Trigger callback
        if (this.config.onChange) {
            this.config.onChange(result, values);
        }

        return result;
    }

    /**
     * Format number with thousand separators
     */
    formatNumber(number) {
        return number.toLocaleString('en-US', {
            minimumFractionDigits: this.config.decimals,
            maximumFractionDigits: this.config.decimals
        });
    }

    /**
     * Get current result
     */
    getResult() {
        return this.calculate();
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        PercentageCalculator,
        ShareholderPercentageCalculator,
        LiveCalculator
    };
}
