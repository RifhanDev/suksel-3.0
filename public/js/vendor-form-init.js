/**
 * Vendor Form Initialization
 * Main initialization file that sets up all vanilla JS modules
 * Replaces Angular.js vendor app
 */

(function() {
    'use strict';

    // Global configuration
    const VendorForm = {
        controllers: {},
        validators: {},
        calculators: {},
        shareHolderTypes: ['Bumiputera', 'Bukan Bumiputera', 'Warga Asing'],
        nationalities: window.nationalities || {},
        isShowMode: false
    };

    /**
     * Initialize all item controllers (tables with add/edit/delete)
     */
    function initItemControllers() {
        // Check if we're in show mode
        VendorForm.isShowMode = document.querySelector('[data-show-mode="true"]') !== null;

        // Initialize Shareholders table
        const shareholdersContainer = document.getElementById('vf-shareholders');
        if (shareholdersContainer) {
            VendorForm.controllers.shareholders = new ItemController(shareholdersContainer, {
                entityName: 'shareholder'
            });

            // Setup field mappings for shareholders
            setupShareholderInputs(shareholdersContainer);
        }

        // Initialize Directors table
        const directorsContainer = document.getElementById('vf-directors');
        if (directorsContainer) {
            VendorForm.controllers.directors = new ItemController(directorsContainer, {
                entityName: 'director'
            });

            // Setup field mappings for directors
            setupDirectorInputs(directorsContainer);
        }

        // Initialize Contacts table (if vendor is approved)
        const contactsContainer = document.getElementById('vf-contacts');
        if (contactsContainer) {
            VendorForm.controllers.contacts = new ItemController(contactsContainer, {
                entityName: 'contact'
            });

            // Setup field mappings for contacts
            setupContactInputs(contactsContainer);
        }

        // Initialize Awards table
        const awardsContainer = document.getElementById('vf-awards');
        if (awardsContainer) {
            VendorForm.controllers.awards = new ItemController(awardsContainer, {
                entityName: 'award'
            });

            // Setup field mappings for awards
            setupAwardInputs(awardsContainer);
        }

        // Initialize Assets table
        const assetsContainer = document.getElementById('vf-assets');
        if (assetsContainer) {
            VendorForm.controllers.assets = new ItemController(assetsContainer, {
                entityName: 'asset'
            });

            // Setup field mappings for assets
            setupAssetInputs(assetsContainer);
        }

        // Initialize Projects table
        const projectsContainer = document.getElementById('vf-projects');
        if (projectsContainer) {
            VendorForm.controllers.projects = new ItemController(projectsContainer, {
                entityName: 'project'
            });

            // Setup field mappings for projects
            setupProjectInputs(projectsContainer);
        }

        // Initialize Products table
        const productsContainer = document.getElementById('vf-products');
        if (productsContainer) {
            VendorForm.controllers.products = new ItemController(productsContainer, {
                entityName: 'product'
            });

            // Setup field mappings for products
            setupProductInputs(productsContainer);
        }
    }

    /**
     * Setup shareholder input field mappings
     */
    function setupShareholderInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        // Map data-field attributes to inputs
        const nameInput = tfoot.querySelector('input[type="text"]:nth-of-type(1)');
        const identityInput = tfoot.querySelector('input[type="text"]:nth-of-type(2)');
        const nationalitySelect = tfoot.querySelector('select[name="nat"]');
        const statusSelect = tfoot.querySelector('select:not([name="nat"])');

        if (nameInput) {
            nameInput.setAttribute('data-field', 'name');
            nameInput.addEventListener('keyup', function() {
                this.value = this.value.toUpperCase();
            });
        }
        if (identityInput) identityInput.setAttribute('data-field', 'identity');
        if (nationalitySelect) nationalitySelect.setAttribute('data-field', 'nationality');
        if (statusSelect) statusSelect.setAttribute('data-field', 'bumiputera_status');

        // Populate status select with shareholder types
        if (statusSelect && statusSelect.options.length === 0) {
            VendorForm.shareHolderTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                statusSelect.appendChild(option);
            });
        }
    }

    /**
     * Setup director input field mappings
     */
    function setupDirectorInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const nameInput = tfoot.querySelector('input[type="text"]:nth-of-type(1)');
        const identityInput = tfoot.querySelector('input[type="text"]:nth-of-type(2)');
        const nationalitySelect = tfoot.querySelector('select[name="nat"]:nth-of-type(1)');
        const designationSelect = tfoot.querySelector('select[name="nat"]:nth-of-type(2)');

        if (nameInput) {
            nameInput.setAttribute('data-field', 'name');
            nameInput.addEventListener('keyup', function() {
                this.value = this.value.toUpperCase();
            });
        }
        if (identityInput) identityInput.setAttribute('data-field', 'identity');
        if (nationalitySelect) nationalitySelect.setAttribute('data-field', 'nationality');
        if (designationSelect) designationSelect.setAttribute('data-field', 'designation');
    }

    /**
     * Setup contact input field mappings
     */
    function setupContactInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const nameInput = tfoot.querySelector('input[type="text"]:nth-of-type(1)');
        const designationInput = tfoot.querySelector('input[type="text"]:nth-of-type(2)');
        const nationalitySelect = tfoot.querySelector('select[name="nat"]');
        const statusSelect = tfoot.querySelector('select:not([name="nat"])');

        if (nameInput) {
            nameInput.setAttribute('data-field', 'name');
            nameInput.addEventListener('keyup', function() {
                this.value = this.value.toUpperCase();
            });
        }
        if (designationInput) {
            designationInput.setAttribute('data-field', 'designation');
            designationInput.addEventListener('keyup', function() {
                this.value = this.value.toUpperCase();
            });
        }
        if (nationalitySelect) nationalitySelect.setAttribute('data-field', 'nationality');
        if (statusSelect) statusSelect.setAttribute('data-field', 'status');

        // Populate status select
        if (statusSelect && statusSelect.options.length === 0) {
            VendorForm.shareHolderTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                statusSelect.appendChild(option);
            });
        }
    }

    /**
     * Setup award input field mappings
     */
    function setupAwardInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const inputs = tfoot.querySelectorAll('input');
        if (inputs[0]) inputs[0].setAttribute('data-field', 'name');
        if (inputs[1]) inputs[1].setAttribute('data-field', 'description');
        if (inputs[2]) inputs[2].setAttribute('data-field', 'by');
    }

    /**
     * Setup asset input field mappings
     */
    function setupAssetInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const nameInput = tfoot.querySelector('input:nth-of-type(1)');
        const valueInput = tfoot.querySelector('input:nth-of-type(2)');

        if (nameInput) nameInput.setAttribute('data-field', 'name');
        if (valueInput) valueInput.setAttribute('data-field', 'value');
    }

    /**
     * Setup project input field mappings
     */
    function setupProjectInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const inputs = tfoot.querySelectorAll('input[type="text"]');
        const checkbox = tfoot.querySelector('input[type="checkbox"]');

        if (inputs[0]) inputs[0].setAttribute('data-field', 'name');
        if (inputs[1]) inputs[1].setAttribute('data-field', 'customer');
        if (inputs[2]) inputs[2].setAttribute('data-field', 'period');
        if (inputs[3]) inputs[3].setAttribute('data-field', 'value');
        if (checkbox) checkbox.setAttribute('data-field', 'done');
    }

    /**
     * Setup product input field mappings
     */
    function setupProductInputs(container) {
        const tfoot = container.querySelector('tfoot');
        if (!tfoot) return;

        const inputs = tfoot.querySelectorAll('input');
        if (inputs[0]) inputs[0].setAttribute('data-field', 'name');
        if (inputs[1]) inputs[1].setAttribute('data-field', 'description');
        if (inputs[2]) inputs[2].setAttribute('data-field', 'implementations');
    }

    /**
     * Initialize percentage calculator for shareholders
     */
    function initPercentageCalculator() {
        const shareholdersTab = document.getElementById('vf-shareholders');
        if (!shareholdersTab) return;

        const bumiInput = shareholdersTab.querySelector('input[name="bumi_percentage"]');
        const nonBumiInput = shareholdersTab.querySelector('input[name="nonbumi_percentage"]');
        const foreignerInput = shareholdersTab.querySelector('input[name="foreigner_percentage"]');
        const totalInput = shareholdersTab.querySelector('input[disabled]');

        if (bumiInput && nonBumiInput && foreignerInput && totalInput) {
            VendorForm.calculators.shareholder = ShareholderPercentageCalculator.init(
                bumiInput,
                nonBumiInput,
                foreignerInput,
                totalInput,
                {
                    onChange: function(percentages, total) {
                        // Update total display
                        totalInput.value = total;

                        // Add visual feedback
                        const parent = totalInput.closest('.modern-input-group') || totalInput.parentElement;
                        parent.classList.remove('text-success', 'text-danger');

                        if (total === 100) {
                            parent.classList.add('text-success');
                        } else {
                            parent.classList.add('text-danger');
                        }
                    }
                }
            );
        }
    }

    /**
     * Initialize form validator
     */
    function initFormValidator() {
        const form = document.querySelector('.jq-validate');
        if (form) {
            VendorForm.validators.main = new FormValidator(form, {
                validateOnBlur: true,
                validateOnInput: false,
                showErrorMessages: true
            });

            // Add custom validator for percentage total
            VendorForm.validators.main.addValidator('percentageTotal', function(value, element) {
                if (VendorForm.calculators.shareholder) {
                    return VendorForm.calculators.shareholder.isValid();
                }
                return true;
            }, 'Jumlah peratusan mesti sama dengan 100%');
        }
    }

    /**
     * Initialize uppercase transformation for specific inputs
     */
    function initUppercaseInputs() {
        const uppercaseInputs = document.querySelectorAll('.x-uppercase');
        uppercaseInputs.forEach(input => {
            input.addEventListener('input', function() {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(start, end);
            });
        });
    }

    /**
     * Initialize table headers with data-field attributes
     */
    function initTableHeaders() {
        // Shareholders
        const shareholdersTable = document.querySelector('#vf-shareholders table');
        if (shareholdersTable) {
            const headers = shareholdersTable.querySelectorAll('thead th');
            const fields = ['name', 'identity', 'nationality', 'bumiputera_status', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Directors
        const directorsTable = document.querySelector('#vf-directors table');
        if (directorsTable) {
            const headers = directorsTable.querySelectorAll('thead th');
            const fields = ['name', 'identity', 'nationality', 'designation', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Contacts
        const contactsTable = document.querySelector('#vf-contacts table');
        if (contactsTable) {
            const headers = contactsTable.querySelectorAll('thead th');
            const fields = ['name', 'designation', 'nationality', 'status', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Awards
        const awardsTable = document.querySelector('#vf-awards table');
        if (awardsTable) {
            const headers = awardsTable.querySelectorAll('thead th');
            const fields = ['name', 'description', 'by', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Assets
        const assetsTable = document.querySelector('#vf-assets table');
        if (assetsTable) {
            const headers = assetsTable.querySelectorAll('thead th');
            const fields = ['name', 'value', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Projects
        const projectsTable = document.querySelector('#vf-projects table');
        if (projectsTable) {
            const headers = projectsTable.querySelectorAll('thead th');
            const fields = ['name', 'customer', 'period', 'value', 'done', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }

        // Products
        const productsTable = document.querySelector('#vf-products table');
        if (productsTable) {
            const headers = productsTable.querySelectorAll('thead th');
            const fields = ['name', 'description', 'implementations', 'actions'];
            headers.forEach((header, index) => {
                if (fields[index]) {
                    header.setAttribute('data-field', fields[index]);
                }
            });
        }
    }

    /**
     * Main initialization function
     */
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        console.log('Initializing Vendor Form (Vanilla JS)...');

        // Initialize all components
        initTableHeaders();
        initItemControllers();
        initPercentageCalculator();
        initUppercaseInputs();
        initFormValidator();
        initDatepickers();
        initSelectize();

        console.log('Vendor Form initialized successfully!');

        // Expose to window for debugging
        window.VendorForm = VendorForm;
    }

    /**
     * Initialize datepickers
     */
    function initDatepickers() {
        if (typeof $ === 'undefined' || typeof $.fn.datepicker === 'undefined') {
            console.warn('jQuery datepicker not available');
            return;
        }

        // Initialize incorporation_date
        $('#incorporation_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        // Initialize ssm_expiry
        $('#ssm_expiry').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        });

        // Initialize MOF dates
        $('#mof_start_date, #mof_end_date').datepicker({
            format: 'd M yyyy',
            autoclose: true,
            todayHighlight: true
        });

        // Initialize CIDB dates
        $('#cidb_start_date, #cidb_end_date').datepicker({
            format: 'd M yyyy',
            autoclose: true,
            todayHighlight: true
        });

        console.log('Datepickers initialized');
    }

    /**
     * Initialize selectize dropdowns
     */
    function initSelectize() {
        if (typeof $ === 'undefined' || typeof $.fn.selectize === 'undefined') {
            console.warn('jQuery selectize not available');
            return;
        }

        // Initialize MOF codes
        if ($('#mof_codes').length && !$('#mof_codes')[0].selectize) {
            $('#mof_codes').selectize();
        }

        // Initialize CIDB codes  
        if ($('#cidb_codes').length && !$('#cidb_codes')[0].selectize) {
            $('#cidb_codes').selectize();
        }

        // Initialize CIDB grade selects with empty option allowed
        if ($('#cidb_grade_b_id').length && !$('#cidb_grade_b_id')[0].selectize) {
            $('#cidb_grade_b_id').selectize({ allowEmptyOption: true });
        }

        if ($('#cidb_grade_ce_id').length && !$('#cidb_grade_ce_id')[0].selectize) {
            $('#cidb_grade_ce_id').selectize({ allowEmptyOption: true });
        }

        if ($('#cidb_grade_me_id').length && !$('#cidb_grade_me_id')[0].selectize) {
            $('#cidb_grade_me_id').selectize({ allowEmptyOption: true });
        }

        // Initialize all CIDB group selectize fields (for dynamically added items)
        $('.cidb_group-code_id.selectize, .cidb_group-codes.selectize').each(function() {
            if (!this.selectize) {
                $(this).selectize({
                    allowEmptyOption: true
                });
            }
        });

        console.log('Selectize initialized');
    }

    /**
     * Re-initialize selectize for dynamically added elements
     */
    function reinitSelectize(container) {
        if (typeof $ === 'undefined' || typeof $.fn.selectize === 'undefined') {
            return;
        }

        $(container).find('.selectize').each(function() {
            if (!this.selectize) {
                $(this).selectize({
                    allowEmptyOption: true
                });
            }
        });
    }

    // Expose reinitSelectize to window for use by sheepIt or other dynamic form handlers
    window.VendorForm = window.VendorForm || {};
    window.VendorForm.reinitSelectize = reinitSelectize;

    // Start initialization
    init();

})();
