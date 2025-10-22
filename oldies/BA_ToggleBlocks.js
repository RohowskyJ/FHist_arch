/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * togggle Blocks show/hide, for Eingabe der File-Uploads
 * 
 * 
 */


/**
 * Sichtbarkeit beim Laden anhand des PHP-Status setzen
 */
function setInitialUploadVisibility() {
    var status = $('#hide_area').val(); // '0' oder '1'
    var blocks = $('.file-upload-block');
  console.log('Status ',status);
    if (status === '0') {
        blocks.show();
    } else {
        blocks.hide();
    }
}

/**
 * Blöcke toggeln (anzeigen/verstecken)
 */
function toggleUploadBlocks() {
    var blocks = $('.file-upload-block');

    blocks.each(function() {
        var currentDisplay = $(this).css('display');
        if (currentDisplay === 'none' || currentDisplay === '') {
            $(this).fadeIn();
        } else {
            $(this).fadeOut();
        }
    });

    // Status im Hidden-Input aktualisieren
    var areAnyVisible = false;
    blocks.each(function() {
        if ($(this).css('display') !== 'none') {
            areAnyVisible = true;
            return false; // Schleife abbrechen
        }
    });
    $('#hide_area').val(areAnyVisible ? '0' : '1');
}

