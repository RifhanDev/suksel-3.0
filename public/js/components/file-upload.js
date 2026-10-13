/**
 * file-upload.js
 * Reusable upload-zone (drag-and-drop) + file-chip component.
 *
 * Usage:
 *   FileUpload.init({
 *     zoneId     : 'upload-zone-kdt',       // id of the <label class="upload-zone">
 *     inputId    : 'input-dokumen-kdt',      // id of the hidden <input type="file">
 *     chipListId : 'file-chip-list-kdt',     // id of the <div class="file-chip-list">
 *   });
 *
 * Files are accumulated in a DataTransfer object and synced back to the
 * <input type="file"> so they are submitted with the form.
 */

var FileUpload = (function ($) {
    'use strict';

    // ── Helpers ──────────────────────────────────────────────────────────────

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function extClass(filename) {
        return 'ext-' + filename.split('.').pop().toLowerCase();
    }

    // ── Build a single file chip DOM element ─────────────────────────────────

    function buildFileChip(file, url, onRemove) {
        var ext      = file.name.split('.').pop().toLowerCase();
        var safeName = $('<span>').text(file.name).html();

        var $chip = $(
            '<div class="file-chip">' +
                '<span class="file-chip-ext ' + extClass(file.name) + '">' + ext + '</span>' +
                '<div class="file-chip-body">' +
                    '<a href="' + url + '" target="_blank" class="file-chip-name" title="' + safeName + '">' + safeName + '</a>' +
                    '<span class="file-chip-size">' + formatBytes(file.size) + '</span>' +
                '</div>' +
                '<button type="button" class="file-chip-remove" title="Buang fail">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                        '<line x1="18" y1="6" x2="6" y2="18"></line>' +
                        '<line x1="6" y1="6" x2="18" y2="18"></line>' +
                    '</svg>' +
                '</button>' +
            '</div>'
        );

        $chip.find('.file-chip-remove').on('click', function () {
            URL.revokeObjectURL(url);
            $chip.remove();
            if (typeof onRemove === 'function') onRemove(file);
        });

        return $chip;
    }

    var instances = {};

    function reset(inputId) {
        if (instances[inputId] && typeof instances[inputId].clear === 'function') {
            instances[inputId].clear();
        } else {
            var $input = $('#' + inputId);
            if ($input.length && $input[0]) {
                $input.val('');
            }
        }
    }

    // ── Public: init ─────────────────────────────────────────────────────────

    function init(opts) {
        var $zone     = $('#' + opts.zoneId);
        var $input    = $('#' + opts.inputId);
        var $chipList = $('#' + opts.chipListId);

        if (!$zone.length || !$input.length || !$chipList.length) {
            console.warn('[FileUpload] One or more elements not found:', opts);
            return;
        }

        // Accumulated files — keeps the input in sync for form submission
        var dt = new DataTransfer();

        function syncInput() {
            if ($input[0]) {
                $input[0].files = dt.files;
            }
        }

        function clear() {
            dt = new DataTransfer();
            syncInput();
            $chipList.empty();
        }

        instances[opts.inputId] = { clear: clear };

        function addFiles(files) {
            $.each(files, function (i, file) {
                dt.items.add(file);
                var url = URL.createObjectURL(file);
                $chipList.append(buildFileChip(file, url, function (removedFile) {
                    // Rebuild DataTransfer without the removed file
                    var newDt = new DataTransfer();
                    for (var j = 0; j < dt.files.length; j++) {
                        if (dt.files[j] !== removedFile) {
                            newDt.items.add(dt.files[j]);
                        }
                    }
                    dt = newDt;
                    syncInput();
                }));
            });
            syncInput();
        }

        // File input change (click-to-upload path)
        $input.on('change', function () {
            var files = this.files;
            if (!files || files.length === 0) return;
            // Copy files before resetting — FileList becomes empty after reset
            var fileArr = [];
            for (var i = 0; i < files.length; i++) fileArr.push(files[i]);
            // Reset input so the same file can be re-selected
            $input.val('');
            addFiles(fileArr);
        });

        // Drag-and-drop
        var zoneEl = $zone[0];

        zoneEl.addEventListener('dragover', function (e) {
            e.preventDefault();
            $zone.addClass('dragover');
        });

        zoneEl.addEventListener('dragleave', function () {
            $zone.removeClass('dragover');
        });

        zoneEl.addEventListener('drop', function (e) {
            e.preventDefault();
            $zone.removeClass('dragover');
            var files = e.dataTransfer.files;
            if (!files || files.length === 0) return;
            var fileArr = [];
            for (var i = 0; i < files.length; i++) fileArr.push(files[i]);
            addFiles(fileArr);
        });
    }

    return {
        init: init,
        reset: reset
    };

}(jQuery));
