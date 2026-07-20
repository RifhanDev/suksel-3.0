/**
 * AJAX Loader Module
 * Replaces Angular's data-remote attribute functionality
 * Loads data from server via AJAX and populates the table
 */

class AjaxLoader {
    /**
     * Load data from a remote URL
     * @param {string} url - The URL to fetch data from
     * @returns {Promise} - Promise that resolves with the data
     */
    static async loadData(url) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error loading data:', error);
            throw error;
        }
    }

    /**
     * Initialize AJAX loading for elements with data-remote attribute
     * @param {string} selector - CSS selector for elements to initialize
     * @param {Function} callback - Callback function to handle loaded data
     */
    static init(selector, callback) {
        const elements = document.querySelectorAll(selector);

        elements.forEach(element => {
            const remoteUrl = element.getAttribute('data-remote');

            if (remoteUrl) {
                this.loadData(remoteUrl)
                    .then(data => {
                        if (callback) {
                            callback(element, data);
                        }
                    })
                    .catch(error => {
                        console.error(`Failed to load data from ${remoteUrl}:`, error);
                    });
            }
        });
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AjaxLoader;
}
