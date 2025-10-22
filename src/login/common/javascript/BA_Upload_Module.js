
// BA_Upload_Module.js
// Reusable Upload-Modul für Bilder und Dokumente (Deutsch)
// Benötigt jQuery

/*
Usage Example (place in your main JS or at the end of your HTML, after DOM is ready):

BA_Upload_Module.initOnReady(function() {
  BA_Upload_Module.setupUploadBlock({
    inputId: 'yourInputId',
    previewContainerId: 'yourPreviewDivId',
    uploadButtonId: 'yourUploadButtonId',
    requiredFields: [
      {id: 'eigentuemer', label: 'Eigentümer'},
      {id: 'aufnDat', label: 'Aufnahmedatum'}
    ],
    extraFields: function() { return {}; },
    ajaxUrl: 'common/API/VF_Upload_Media.API.php',
    onSuccess: function(resp) { // handle success },
    onError: function(xhr) { // handle error },
    filenameTargetId: 'yourHiddenInputId',
    allowMultiple: true,
    allowRotate: true,
    allowRename: true,
    allowSelect: true
  });
});

// Or with jQuery:
// $(function() { BA_Upload_Module.setupUploadBlock({ ... }); });
*/

(function(window, $) {
    function setupUploadBlock(config) {
        // config: {
        //   inputId, previewContainerId, uploadButtonId, requiredFields: [{id, label}], extraFields (fn), ajaxUrl, onSuccess, onError, filenameTargetId, allowMultiple, allowRotate, allowRename, allowSelect
        // }
        const fileInput = $('#' + config.inputId);
        fileInput.attr('multiple', config.allowMultiple ? 'multiple' : null);
        const previewDiv = $('#' + config.previewContainerId);
        const uploadBtn = $('#' + config.uploadButtonId);
        let selectedFiles = [];
        let rotations = [];
        let renames = [];
        let selectedForUpload = [];

        // Initial UI
        previewDiv.html('');

        // File input change handler
        fileInput.on('change', function(e) {
            selectedFiles = Array.from(e.target.files);
            rotations = selectedFiles.map(() => 0);
            renames = selectedFiles.map(f => f.name);
            selectedForUpload = selectedFiles.map(() => true);
            renderPreviews();
        });

        function renderPreviews() {
            previewDiv.html('');
            if (!selectedFiles.length) return;
            selectedFiles.forEach((file, idx) => {
                const fileRow = $('<div style="margin-bottom:10px; display:flex; align-items:center;"></div>');
                const selectBox = config.allowSelect ? $('<input type="checkbox" checked style="margin-right:8px;">') : null;
                const filenameInput = config.allowRename ? $('<input type="text" style="width:180px; margin-right:8px;">').val(renames[idx]) : $('<span style="width:180px; margin-right:8px;">' + file.name + '</span>');
                const rotateLeftBtn = config.allowRotate ? $('<button type="button" style="margin-right:5px;">⟲</button>') : null;
                const rotateRightBtn = config.allowRotate ? $('<button type="button" style="margin-right:5px;">⟳</button>') : null;
                const imgHolder = $('<span style="margin-right:8px;"></span>');
                const removeBtn = $('<button type="button" style="margin-right:5px;">Entfernen</button>');

                if (selectBox) {
                    selectBox.prop('checked', selectedForUpload[idx]);
                    selectBox.on('change', function() {
                        selectedForUpload[idx] = this.checked;
                    });
                    fileRow.append(selectBox);
                }
                if (filenameInput.is('input')) {
                    filenameInput.on('input', function() {
                        renames[idx] = $(this).val();
                    });
                }
                fileRow.append(filenameInput);
                if (rotateLeftBtn) {
                    rotateLeftBtn.on('click', function() {
                        rotations[idx] = (rotations[idx] - 90) % 360;
                        renderImage(file, rotations[idx], imgHolder);
                    });
                    fileRow.append(rotateLeftBtn);
                }
                if (rotateRightBtn) {
                    rotateRightBtn.on('click', function() {
                        rotations[idx] = (rotations[idx] + 90) % 360;
                        renderImage(file, rotations[idx], imgHolder);
                    });
                    fileRow.append(rotateRightBtn);
                }
                fileRow.append(imgHolder);
                fileRow.append(removeBtn);
                removeBtn.on('click', function() {
                    selectedFiles.splice(idx, 1);
                    rotations.splice(idx, 1);
                    renames.splice(idx, 1);
                    selectedForUpload.splice(idx, 1);
                    renderPreviews();
                });
                previewDiv.append(fileRow);
                if (file.type.startsWith('image/')) {
                    renderImage(file, rotations[idx], imgHolder);
                } else {
                    imgHolder.html('<span style="color:#888;">(Keine Vorschau)</span>');
                }
            });
        }

        function renderImage(file, rotation, imgHolder) {
            if (!file) { imgHolder.html(''); return; }
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = $('<img>').attr('src', e.target.result).css({
                    'max-width': '120px',
                    'transform': 'rotate(' + rotation + 'deg)'
                });
                imgHolder.html(img);
            };
            reader.readAsDataURL(file);
        }

        // Add error spans for required fields
        if (Array.isArray(config.requiredFields)) {
            config.requiredFields.forEach(function(f) {
                if ($('#' + f.id).next('.error-msg').length === 0) {
                    $('#' + f.id).after('<span class="error-msg" style="color:red;display:none;margin-left:8px;"></span>');
                }
                // Remove error on input
                $('#' + f.id).on('input', function() {
                    if ($(this).val() !== '') {
                        $(this).removeClass('input-error');
                        $(this).next('.error-msg').hide();
                    }
                });
            });
        }

        // Add CSS for error highlight
        if ($('style#input-error-style').length === 0) {
            $('head').append('<style id="input-error-style">.input-error { border: 2px solid red !important; }</style>');
        }

        uploadBtn.on('click', function() {
            let hasError = false;
            // Remove previous error styles/messages
            if (Array.isArray(config.requiredFields)) {
                config.requiredFields.forEach(function(f) {
                    $('#' + f.id).removeClass('input-error');
                    $('#' + f.id).next('.error-msg').hide();
                });
            }

            if (!selectedFiles.length) {
                alert('Bitte wählen Sie mindestens eine Datei aus.');
                return;
            }

            // Validate required fields
            if (Array.isArray(config.requiredFields)) {
                config.requiredFields.forEach(function(f) {
                    if ($('#' + f.id).val() === '') {
                        $('#' + f.id).addClass('input-error');
                        $('#' + f.id).next('.error-msg').text('Pflichtfeld!').show();
                        hasError = true;
                    }
                });
            }
            if (hasError) {
                return;
            }

            const formData = new FormData();
            let anySelected = false;
            selectedFiles.forEach((file, idx) => {
                if (!config.allowSelect || selectedForUpload[idx]) {
                    formData.append('file[]', file);
                    if (config.allowRotate) formData.append('rotation[]', rotations[idx]);
                    if (config.allowRename) formData.append('rename[]', renames[idx]);
                    anySelected = true;
                }
            });
            if (!anySelected) {
                alert('Bitte wählen Sie mindestens eine Datei zum Hochladen aus.');
                return;
            }
            // Zusätzliche Felder
            if (typeof config.extraFields === 'function') {
                const extra = config.extraFields();
                for (let key in extra) {
                    formData.append(key, extra[key]);
                }
            }
            $.ajax({
                url: config.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let respObj = response;
                    if (typeof response === 'string') {
                        try {
                            respObj = JSON.parse(response);
                        } catch(e) {
                            respObj = response;
                        }
                    }
                    console.log('Upload response: ', respObj);
                    alert('Upload erfolgreich!');
                    // Set returned filename in target input if provided
                    if (config.filenameTargetId && respObj && respObj.files) {
                        const target = $('#' + config.filenameTargetId);
                        if (target.length && respObj.files.length) {
                            target.val(respObj.files[0].filename);
                        }
                    }
                    if (typeof config.onSuccess === 'function') config.onSuccess(respObj);
                },
                error: function(xhr) {
                    alert('Fehler beim Upload: ' + xhr.statusText);
                    if (typeof config.onError === 'function') config.onError(xhr);
                }
            });
        });
    }
    // Helper: run callback when DOM is ready (jQuery or native)
    function initOnReady(cb) {
        console.info('initOnReady called');
        if (window.jQuery) {
            $(cb);
        } else if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', cb);
        } else {
            cb();
        }
    }

    // Export
    window.BA_Upload_Module = {
        setupUploadBlock: setupUploadBlock,
        initOnReady: initOnReady
    };
})(window, jQuery);

