/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * Initialisierung der Funktionen und Event- Funktionen
 * 
 * 
 */

$(function() {
    // Sichtbarkeitsstatus beim Laden setzen
    setInitialUploadVisibility();

    // Button-Event zum togglen
    $('#toggleUploadsButton').on('click', function() {
        toggleUploadBlocks();
    });
});





