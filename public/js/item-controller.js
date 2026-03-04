/**
 * Item Controller Module
 * Replaces Angular's ItemController functionality
 * Handles add, edit, delete operations for table rows
 */

class ItemController {
    constructor(containerElement, options = {}) {
        this.container = containerElement;
        this.items = [];
        this.deletedItems = [];
        this.newItem = {};
        this.editingItem = null;
        this.hasEditingItem = false;
        this.hasNewItem = false;

        // Configuration
        this.config = {
            tableSelector: 'table',
            tbodySelector: 'tbody',
            tfootSelector: 'tfoot',
            addButtonSelector: '[data-action="save"]',
            editButtonSelector: '[data-action="edit"]',
            deleteButtonSelector: '[data-action="remove"]',
            clearButtonSelector: '[data-action="clear"]',
            inputSelector: 'input, select',
            hiddenInputsContainer: 'tbody',
            ...options
        };

        this.init();
    }

    /**
     * Initialize the controller
     */
    init() {
        this.table = this.container.querySelector(this.config.tableSelector);
        this.tbody = this.container.querySelector(this.config.tbodySelector);
        this.tfoot = this.container.querySelector(this.config.tfootSelector);

        // Load data from data-remote attribute
        const remoteUrl = this.container.getAttribute('data-remote');
        if (remoteUrl) {
            this.loadRemoteData(remoteUrl);
        }

        // Bind events
        this.bindEvents();
    }

    /**
     * Load data from remote URL
     */
    async loadRemoteData(url) {
        try {
            const data = await AjaxLoader.loadData(url);
            this.items = data;
            this.render();
        } catch (error) {
            console.error('Failed to load remote data:', error);
        }
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Save button
        if (this.tfoot) {
            const saveBtn = this.tfoot.querySelector(this.config.addButtonSelector);
            if (saveBtn) {
                saveBtn.addEventListener('click', () => this.save());
            }

            // Clear button
            const clearBtn = this.tfoot.querySelector(this.config.clearButtonSelector);
            if (clearBtn) {
                clearBtn.addEventListener('click', () => this.clear());
            }

            // Input change detection
            const inputs = this.tfoot.querySelectorAll(this.config.inputSelector);
            inputs.forEach(input => {
                input.addEventListener('input', () => this.setHasNewItem());
                input.addEventListener('keypress', (e) => this.handleKeypress(e));
            });
        }

        // Delegate events for edit and delete buttons
        if (this.tbody) {
            this.tbody.addEventListener('click', (e) => {
                const editBtn = e.target.closest(this.config.editButtonSelector);
                const deleteBtn = e.target.closest(this.config.deleteButtonSelector);

                if (editBtn) {
                    const row = editBtn.closest('tr');
                    const index = Array.from(this.tbody.children).indexOf(row);
                    this.edit(this.items[index]);
                }

                if (deleteBtn) {
                    const row = deleteBtn.closest('tr');
                    const index = Array.from(this.tbody.children).indexOf(row);
                    this.remove(index);
                }
            });
        }
    }

    /**
     * Handle keypress events (Enter key to save)
     */
    handleKeypress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            this.save();
        }
    }

    /**
     * Check if there's new item data
     */
    setHasNewItem() {
        const values = Object.values(this.getNewItemFromInputs()).filter(val => val && val.trim() !== '');
        this.hasNewItem = values.length > 0;
    }

    /**
     * Get new item data from input fields
     */
    getNewItemFromInputs() {
        const newItem = {};

        if (!this.tfoot) return newItem;

        const inputs = this.tfoot.querySelectorAll(this.config.inputSelector);
        inputs.forEach(input => {
            const name = input.getAttribute('data-field');
            if (name) {
                if (input.type === 'checkbox') {
                    newItem[name] = input.checked;
                } else {
                    newItem[name] = input.value;
                }
            }
        });

        return newItem;
    }

    /**
     * Set new item data to input fields
     */
    setInputsFromItem(item) {
        if (!this.tfoot) return;

        const inputs = this.tfoot.querySelectorAll(this.config.inputSelector);
        inputs.forEach(input => {
            const name = input.getAttribute('data-field');
            if (name && item.hasOwnProperty(name)) {
                if (input.type === 'checkbox') {
                    input.checked = item[name];
                } else {
                    input.value = item[name];
                }
            }
        });
    }

    /**
     * Clear input fields
     */
    clear() {
        this.newItem = { nationality: 'MALAYSIAN' };
        this.editingItem = null;
        this.hasEditingItem = false;
        this.hasNewItem = false;

        if (this.tfoot) {
            const inputs = this.tfoot.querySelectorAll(this.config.inputSelector);
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.getAttribute('data-field') === 'nationality') {
                    input.value = 'MALAYSIAN';
                } else {
                    input.value = '';
                }
            });
        }

        this.render();
    }

    /**
     * Save (add or update) an item
     */
    save() {
        const itemData = this.getNewItemFromInputs();

        // Validate that at least some fields are filled
        const hasData = Object.values(itemData).some(val =>
            val && (typeof val === 'boolean' || val.trim() !== '')
        );

        if (!hasData) {
            return;
        }

        if (this.hasEditingItem && this.editingItem) {
            // Update existing item
            const index = this.items.indexOf(this.editingItem);
            if (index !== -1) {
                this.items[index] = { ...this.items[index], ...itemData };
            }
        } else {
            // Add new item
            itemData.id = 'false'; // Mark as new item
            this.items.push(itemData);
        }

        this.clear();
        this.render();
    }

    /**
     * Edit an item
     */
    edit(item) {
        this.hasEditingItem = true;
        this.editingItem = item;
        this.newItem = { ...item };

        this.setInputsFromItem(this.newItem);
        this.render();
    }

    /**
     * Remove an item
     */
    remove(index) {
        const confirmAction = () => {
            this.deletedItems.push({ ...this.items[index] });
            this.items.splice(index, 1);
            this.render();
        };

        if (window.VendorForm && typeof window.VendorForm.confirmDelete === 'function') {
            window.VendorForm.confirmDelete('Adakah anda pasti mahu memadam item ini?', (result) => {
                if (result) confirmAction();
            });
        } else if (window.VendorForm && typeof window.VendorForm.confirm === 'function') {
            window.VendorForm.confirm('Adakah anda pasti mahu memadam item ini?', (result) => {
                if (result) confirmAction();
            });
        } else if (typeof bootbox !== 'undefined') {
            bootbox.confirm('Anda pasti?', (result) => {
                if (result) confirmAction();
            });
        } else {
            if (confirm('Anda pasti?')) {
                confirmAction();
            }
        }
    }

    /**
     * Render the table
     */
    render() {
        if (!this.tbody) return;

        // Clear existing rows
        this.tbody.innerHTML = '';

        // Check if we're in show mode
        const showMode = this.container.getAttribute('data-show-mode') === 'true';

        // Render each item
        this.items.forEach((item, index) => {
            const row = this.createRow(item, index, showMode);
            this.tbody.appendChild(row);
        });

        // Update hidden inputs for deleted items
        this.renderDeletedInputs();
    }

    /**
     * Create a table row for an item
     */
    createRow(item, index, showMode) {
        const row = document.createElement('tr');

        // Add editing class if this is the item being edited
        if (this.editingItem === item) {
            row.classList.add('editing');
        }

        // Get the template from the first row or create default
        const headerCells = this.table.querySelectorAll('thead th');
        const fields = this.getFieldsFromHeaders(headerCells);

        // Create cells for each field
        fields.forEach(field => {
            if (field === 'actions') return; // Skip actions column in data cells

            const cell = document.createElement('td');

            if (field === 'done') {
                // Checkbox field
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = item[field];
                checkbox.disabled = true;
                cell.appendChild(checkbox);
            } else {
                cell.textContent = item[field] || '';
            }

            row.appendChild(cell);
        });

        // Add hidden inputs for form submission
        this.addHiddenInputsToRow(row, item, index);

        // Add actions column if not in show mode
        if (!showMode) {
            const actionsCell = this.createActionsCell(item, index);
            row.appendChild(actionsCell);
        }

        return row;
    }

    /**
     * Get field names from table headers
     */
    getFieldsFromHeaders(headers) {
        const fields = [];
        headers.forEach(header => {
            const field = header.getAttribute('data-field') || header.textContent.toLowerCase();
            fields.push(field);
        });
        return fields;
    }

    /**
     * Add hidden inputs to a row for form submission
     */
    addHiddenInputsToRow(row, item, index) {
        const entityName = this.container.getAttribute('data-entity-name');
        if (!entityName) return;

        Object.keys(item).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${entityName}[${key}][]`;
            input.value = item[key];
            row.appendChild(input);
        });
    }

    /**
     * Create actions cell with edit and delete buttons
     */
    createActionsCell(item, index) {
        const cell = document.createElement('td');
        cell.className = 'text-center';

        if (this.editingItem === item) {
            const badge = document.createElement('span');
            badge.className = 'badge text-bg-warning';
            badge.textContent = 'Sedang Dikemaskini';
            cell.appendChild(badge);
        } else {
            const editBtn = this.createButton('edit', 'btn-warning');
            const deleteBtn = this.createButton('remove', 'btn-danger');

            cell.appendChild(editBtn);
            cell.appendChild(document.createTextNode(' '));
            cell.appendChild(deleteBtn);
        }

        return cell;
    }

    /**
     * Create action button with icon
     */
    createButton(action, btnClass) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `btn ${btnClass} btn-sm`;
        button.setAttribute('data-action', action);

        const svg = this.getIconSVG(action);
        button.innerHTML = svg;

        return button;
    }

    /**
     * Get SVG icon for action
     */
    getIconSVG(action) {
        const icons = {
            edit: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
            remove: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>'
        };
        return icons[action] || '';
    }

    /**
     * Render hidden inputs for deleted items
     */
    renderDeletedInputs() {
        const entityName = this.container.getAttribute('data-entity-name');
        if (!entityName) return;

        // Find or create container for deleted inputs
        let deletedContainer = this.container.querySelector('[data-deleted-inputs]');
        if (!deletedContainer) {
            deletedContainer = document.createElement('div');
            deletedContainer.setAttribute('data-deleted-inputs', '');
            deletedContainer.style.display = 'none';
            this.container.appendChild(deletedContainer);
        }

        // Clear existing deleted inputs
        deletedContainer.innerHTML = '';

        // Add hidden input for each deleted item
        this.deletedItems.forEach(item => {
            if (item.id && item.id !== 'false') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `deleted[${entityName}][]`;
                input.value = item.id;
                deletedContainer.appendChild(input);
            }
        });
    }

    /**
     * Get current items
     */
    getItems() {
        return this.items;
    }

    /**
     * Get deleted items
     */
    getDeletedItems() {
        return this.deletedItems;
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ItemController;
}
