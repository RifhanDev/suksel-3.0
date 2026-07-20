/**
 * Two-Way Data Binding Module
 * Replaces Angular's ng-model functionality
 * Provides two-way data binding between form inputs and data objects
 */

class TwoWayBinding {
    constructor() {
        this.bindings = new Map();
        this.observers = new Map();
    }

    /**
     * Bind an input element to a data model
     * @param {HTMLElement} element - The input element
     * @param {Object} model - The data model object
     * @param {string} property - The property name in the model
     * @param {Object} options - Additional options
     */
    bind(element, model, property, options = {}) {
        const binding = {
            element,
            model,
            property,
            options
        };

        // Store the binding
        const bindingKey = this.getBindingKey(element);
        this.bindings.set(bindingKey, binding);

        // Set initial value
        this.updateElementFromModel(binding);

        // Listen to element changes
        this.attachElementListeners(binding);

        // Create observer for model changes
        this.observeModel(binding);
    }

    /**
     * Get a unique key for an element
     */
    getBindingKey(element) {
        if (!element.dataset.bindingId) {
            element.dataset.bindingId = 'binding_' + Date.now() + '_' + Math.random();
        }
        return element.dataset.bindingId;
    }

    /**
     * Attach event listeners to element
     */
    attachElementListeners(binding) {
        const { element, model, property, options } = binding;

        const updateModel = () => {
            let value;

            if (element.type === 'checkbox') {
                value = element.checked;
            } else if (element.type === 'number') {
                value = parseFloat(element.value) || 0;
            } else {
                value = element.value;
            }

            // Apply transformations
            if (options.transform) {
                value = options.transform(value);
            }

            // Apply uppercase if specified
            if (options.uppercase && typeof value === 'string') {
                value = value.toUpperCase();
                element.value = value; // Update the input to show uppercase
            }

            // Update the model
            this.setNestedProperty(model, property, value);

            // Trigger change callback
            if (options.onChange) {
                options.onChange(value, model);
            }
        };

        // Listen to input events
        element.addEventListener('input', updateModel);
        element.addEventListener('change', updateModel);

        // Handle keyup for uppercase transformation
        if (options.uppercase) {
            element.addEventListener('keyup', updateModel);
        }

        // Store listener for cleanup
        element.dataset.bindingListener = 'attached';
    }

    /**
     * Update element value from model
     */
    updateElementFromModel(binding) {
        const { element, model, property } = binding;
        const value = this.getNestedProperty(model, property);

        if (element.type === 'checkbox') {
            element.checked = !!value;
        } else if (element.type === 'number') {
            element.value = value || 0;
        } else {
            element.value = value || '';
        }
    }

    /**
     * Observe model changes using Proxy
     */
    observeModel(binding) {
        const { model, property } = binding;

        // If model is not already a proxy, create one
        if (!this.observers.has(model)) {
            // This is a simplified implementation
            // In production, you might want to use a more sophisticated observer
            this.observers.set(model, true);
        }
    }

    /**
     * Get nested property value
     */
    getNestedProperty(obj, path) {
        const keys = path.split('.');
        let value = obj;

        for (const key of keys) {
            if (value && typeof value === 'object' && key in value) {
                value = value[key];
            } else {
                return undefined;
            }
        }

        return value;
    }

    /**
     * Set nested property value
     */
    setNestedProperty(obj, path, value) {
        const keys = path.split('.');
        const lastKey = keys.pop();
        let target = obj;

        for (const key of keys) {
            if (!(key in target)) {
                target[key] = {};
            }
            target = target[key];
        }

        target[lastKey] = value;
    }

    /**
     * Unbind an element
     */
    unbind(element) {
        const bindingKey = this.getBindingKey(element);
        this.bindings.delete(bindingKey);
    }

    /**
     * Unbind all elements
     */
    unbindAll() {
        this.bindings.clear();
        this.observers.clear();
    }

    /**
     * Initialize bindings from data attributes
     * Elements should have data-bind="propertyName" attribute
     */
    static initFromDOM(containerElement, model, options = {}) {
        const binder = new TwoWayBinding();
        const elements = containerElement.querySelectorAll('[data-bind]');

        elements.forEach(element => {
            const property = element.getAttribute('data-bind');
            const uppercase = element.hasAttribute('data-uppercase');
            const onChange = element.getAttribute('data-on-change');

            const bindingOptions = {
                uppercase,
                ...options
            };

            // Add onChange callback if specified
            if (onChange && typeof window[onChange] === 'function') {
                bindingOptions.onChange = window[onChange];
            }

            binder.bind(element, model, property, bindingOptions);
        });

        return binder;
    }
}

/**
 * Simple utility for declarative two-way binding
 * Usage: <input data-model="user.name" />
 */
class DataModelBinder {
    constructor() {
        this.models = new Map();
        this.binders = new Map();
    }

    /**
     * Register a model with a name
     */
    registerModel(name, model) {
        this.models.set(name, model);
    }

    /**
     * Get a registered model
     */
    getModel(name) {
        return this.models.get(name);
    }

    /**
     * Initialize bindings in a container
     */
    init(containerElement) {
        const elements = containerElement.querySelectorAll('[data-model]');

        elements.forEach(element => {
            const modelPath = element.getAttribute('data-model');
            const [modelName, ...propertyPath] = modelPath.split('.');
            const property = propertyPath.join('.');

            const model = this.getModel(modelName);
            if (!model) {
                console.warn(`Model "${modelName}" not found for element:`, element);
                return;
            }

            // Get or create binder for this model
            let binder = this.binders.get(modelName);
            if (!binder) {
                binder = new TwoWayBinding();
                this.binders.set(modelName, binder);
            }

            // Bind the element
            const options = {
                uppercase: element.hasAttribute('data-uppercase'),
                onChange: (value) => {
                    // Trigger custom event
                    element.dispatchEvent(new CustomEvent('model-change', {
                        detail: { value, model, property }
                    }));
                }
            };

            binder.bind(element, model, property, options);
        });
    }

    /**
     * Cleanup all bindings
     */
    destroy() {
        this.binders.forEach(binder => binder.unbindAll());
        this.binders.clear();
        this.models.clear();
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { TwoWayBinding, DataModelBinder };
}
