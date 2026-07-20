@if ($modalEmbed ?? false)
    <script>
        window.MODAL_EMBED = true;

        function vendorFormClose(reloadParent) {
            if (window.parent === window) {
                return;
            }
            window.parent.postMessage({
                type: 'vendor-form-close',
                reload: !!reloadParent
            }, window.location.origin);
        }

        function vendorFormComplete(message, status) {
            if (window.parent === window) {
                return false;
            }
            window.parent.postMessage({
                type: 'vendor-form-complete',
                status: status || 'success',
                message: message || ''
            }, window.location.origin);
            return true;
        }

        function vendorFormNavigate(url) {
            if (vendorFormComplete()) {
                return;
            }
            window.location.href = url;
        }
    </script>
@endif
