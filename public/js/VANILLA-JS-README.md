# Vanilla JS Modules - Angular.js Replacement

This directory contains vanilla JavaScript modules that replace Angular.js functionality in the vendor form.

## Files Overview

### 1. `ajax-loader.js`
**Purpose:** Load data via AJAX (replaces Angular's `data-remote` and `$http.get()`)

**Features:**
- Fetch data from remote URLs
- Handle AJAX errors gracefully
- Initialize elements with `data-remote` attribute

**Usage:**
```javascript
// Load data from a URL
AjaxLoader.loadData('/api/shareholders')
    .then(data => console.log(data))
    .catch(error => console.error(error));

// Auto-initialize elements with data-remote attribute
AjaxLoader.init('[data-remote]', (element, data) => {
    console.log('Data loaded for', element, data);
});
```

---

### 2. `item-controller.js`
**Purpose:** Manage table rows with add/edit/delete operations (replaces Angular's `ItemController`)

**Features:**
- Add new rows dynamically
- Edit existing rows
- Delete rows with confirmation
- Load data from AJAX
- Generate hidden form inputs for submission
- Handle Enter key to save

**Usage:**
```javascript
// Initialize controller
const controller = new ItemController(containerElement, {
    entityName: 'shareholder' // Used for form input names
});

// Programmatic operations
controller.save();          // Save current item
controller.edit(item);      // Edit an item
controller.remove(index);   // Remove an item
controller.clear();         // Clear form inputs
controller.getItems();      // Get all items
controller.getDeletedItems(); // Get deleted items
```

**HTML Requirements:**
- Container element with `data-remote` attribute (optional, for loading data)
- Container element with `data-entity-name` attribute (for form submission)
- Container element with `data-show-mode="true"` attribute (optional, for read-only mode)
- Table with `thead`, `tbody`, and `tfoot`
- Input fields in `tfoot` with `data-field` attributes
- Buttons with `data-action="save"`, `data-action="edit"`, `data-action="remove"`, `data-action="clear"`

---

### 3. `two-way-binding.js`
**Purpose:** Two-way data binding between inputs and data models (replaces Angular's `ng-model`)

**Features:**
- Bind input elements to data objects
- Automatic updates when input changes
- Support for uppercase transformation
- Support for nested properties
- Custom transformations

**Usage:**
```javascript
// Method 1: Programmatic binding
const binder = new TwoWayBinding();
const model = { name: '', email: '' };

binder.bind(inputElement, model, 'name', {
    uppercase: true,
    onChange: (value) => console.log('Name changed:', value)
});

// Method 2: Declarative binding with data attributes
const model = { user: { name: '', email: '' } };
const binder = TwoWayBinding.initFromDOM(containerElement, model);

// HTML: <input data-bind="user.name" data-uppercase />

// Method 3: Using DataModelBinder
const binder = new DataModelBinder();
binder.registerModel('user', { name: '', email: '' });
binder.init(containerElement);

// HTML: <input data-model="user.name" />
```

---

### 4. `form-validator.js`
**Purpose:** Form validation with custom rules (replaces Angular's form validation)

**Features:**
- Built-in validators (required, email, pattern, min, max, etc.)
- Custom validators
- Real-time validation
- Error messages in Bahasa Malaysia
- Visual feedback with CSS classes

**Usage:**
```javascript
// Initialize validator
const validator = new FormValidator(formElement, {
    validateOnBlur: true,
    validateOnInput: false,
    showErrorMessages: true
});

// Add custom validator
validator.addValidator('phoneNumber', (value) => {
    return /^[+0-9]{9,}$/.test(value);
}, 'Nombor telefon tidak sah');

// Check if valid
if (validator.isValid()) {
    // Form is valid
}

// Get errors
const errors = validator.getErrors();

// Reset validation
validator.reset();
```

**HTML Requirements:**
- Standard HTML5 validation attributes (`required`, `pattern`, `minlength`, etc.)
- Custom validators via `data-validators="validator1,validator2"`

---

### 5. `percentage-calculator.js`
**Purpose:** Calculate percentages and totals in real-time

**Features:**
- Real-time calculation
- Visual feedback for valid/invalid totals
- Shareholder percentage calculator
- Generic live calculator for any numeric inputs

**Usage:**
```javascript
// Shareholder percentage calculator
const calculator = ShareholderPercentageCalculator.init(
    bumiInput,
    nonBumiInput,
    foreignerInput,
    totalInput,
    {
        onChange: (percentages, total) => {
            console.log('Total:', total);
        }
    }
);

// Check if valid (total = 100)
calculator.isValid();

// Get breakdown
calculator.getBreakdown();
// Returns: { bumiputera: 60, nonBumiputera: 30, foreigner: 10, total: 100 }

// Check if bumiputera
calculator.isBumiputera(); // true if >= 51%

// Generic calculator
const calculator = new LiveCalculator(
    [input1, input2, input3],
    totalElement,
    {
        operation: 'sum', // or 'average', 'multiply'
        decimals: 2,
        format: true
    }
);
```

---

### 6. `vendor-form-init.js`
**Purpose:** Main initialization file that sets up all modules

**Features:**
- Initialize all item controllers
- Setup field mappings
- Initialize percentage calculator
- Initialize form validator
- Setup uppercase inputs
- Auto-configure table headers

**Usage:**
Just include this file after all other modules. It will automatically initialize everything when the DOM is ready.

```html
<script src="/js/ajax-loader.js"></script>
<script src="/js/item-controller.js"></script>
<script src="/js/two-way-binding.js"></script>
<script src="/js/form-validator.js"></script>
<script src="/js/percentage-calculator.js"></script>
<script src="/js/vendor-form-init.js"></script>
```

The global `VendorForm` object will be available:

```javascript
// Access controllers
VendorForm.controllers.shareholders.getItems();
VendorForm.controllers.directors.getItems();

// Access calculators
VendorForm.calculators.shareholder.isValid();

// Access validators
VendorForm.validators.main.validateAll();
```

---

## Migration from Angular.js

### Before (Angular.js):
```html
<div ng-app="vendor" ng-controller="ItemController" data-remote="/api/shareholders">
    <table>
        <tbody>
            <tr ng-repeat="item in items">
                <td ng-bind="item.name"></td>
                <td>
                    <button ng-click="edit(item)">Edit</button>
                    <button ng-click="remove($index)">Delete</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><input ng-model="newItem.name" /></td>
                <td><button ng-click="save()">Save</button></td>
            </tr>
        </tfoot>
    </table>
</div>
```

### After (Vanilla JS):
```html
<div id="shareholders-container" data-remote="/api/shareholders" data-entity-name="shareholder">
    <table>
        <thead>
            <tr>
                <th data-field="name">Name</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be generated automatically -->
        </tbody>
        <tfoot>
            <tr>
                <td><input type="text" data-field="name" /></td>
                <td>
                    <button type="button" data-action="save">Save</button>
                    <button type="button" data-action="clear">Clear</button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
// Automatically initialized by vendor-form-init.js
// Or manually:
const controller = new ItemController(
    document.getElementById('shareholders-container'),
    { entityName: 'shareholder' }
);
</script>
```

---

## HTML Attribute Reference

### Container Attributes:
- `data-remote="/url"` - Load data from this URL
- `data-entity-name="name"` - Entity name for form inputs (e.g., shareholder, director)
- `data-show-mode="true"` - Read-only mode (no edit/delete buttons)

### Table Header Attributes:
- `data-field="fieldName"` - Map header to field name

### Input Attributes:
- `data-field="fieldName"` - Map input to field name
- `data-bind="propertyName"` - Two-way binding to property
- `data-model="model.property"` - Two-way binding with model name
- `data-uppercase` - Auto-convert to uppercase
- `data-validators="validator1,validator2"` - Custom validators

### Button Attributes:
- `data-action="save"` - Save button
- `data-action="edit"` - Edit button
- `data-action="remove"` - Delete button
- `data-action="clear"` - Clear button

### Percentage Calculator Attributes:
- `data-percentage="fieldName"` - Mark input for percentage calculation
- `data-percentage-total` - Mark element to display total

---

## Browser Support

All modules use modern JavaScript (ES6+) and support:
- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

For older browsers, you may need to include polyfills or transpile with Babel.

---

## Dependencies

No external dependencies required! All modules are pure vanilla JavaScript.

Optional:
- `bootbox.js` - For better confirmation dialogs (fallback to native `confirm()`)

---

## Debugging

All modules expose their instances to the global scope when in development:

```javascript
// Check if controllers are initialized
console.log(VendorForm.controllers);

// Get current items
console.log(VendorForm.controllers.shareholders.getItems());

// Check validation errors
console.log(VendorForm.validators.main.getErrors());

// Check percentage total
console.log(VendorForm.calculators.shareholder.getTotal());
```

---

## License

Internal use only.
