/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * Multi- File- Upload
 * 
 */

	// Funktion zum Hochladen eines Bildes via AJAX
	function uploadImage(fileInputId, index) {
	    var fileInput = document.getElementById(fileInputId);
	    var file = fileInput.files[0];
console.log('funct uploadImage geladen');
	    if (!file) {
	        alert('Bitte wählen Sie eine Datei aus.');
	        return;
	    }
console.log("Urheber ",urheber);
	    var formData = new FormData();
	    formData.append('file', file);
	    formData.append('urheber', '<?php echo json_encode($urheber); ?>');  // vorschlag von chatgpt : als korrektuur :  formData.append('urheber', <?php echo json_encode($urheber); ?>);
	    formData.append('targPfad', '<?php echo $verzeichnis; ?>');
	    formData.append('urhEinfg', '<?php echo $urh_einfueg; ?>'); // Wasserzeichen einfügen, wenn urheber und aufnDat  > '' und = J
	    formData.append('aufnDat', '<?php echo $aufn_datum; ?>'); // Teil des Bild-Dateinamens wenn rename - oder Blank
		
		console.log("FormData ",formData);
	    $.ajax({
	        url: 'common/API/VF_Upload_FO_API.php', // Server-Skript
	        type: 'POST',
	        data: formData,
	        contentType: false,
	        processData: false,
	        success: function(response) {
	            // Antwort des Servers interpretieren
	            var res = JSON.parse(response);
	            if (res.success) {
	                alert('Upload erfolgreich: ' + res.dateiname);
	                // Setze Dateinamen in das Input-Feld
	                $('#fz_bild_' + index).val(res.dateiname);
	            } else {
	                alert('Fehler: ' + res.message);
	            }
	        },
	        error: function() {
	            alert('Fehler beim Upload.');
	        }
	    });
	}

