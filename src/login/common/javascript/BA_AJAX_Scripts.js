/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * Autocomplete
 * Multi- Level Dropdpwn- List
 * 
 * fehlt noch
 * Foto -Upload mit Resize/rename/Watermark
 * 
 */
$(function() {
	//console.log('initFunctions aktiv');
    // Init-Funktionen
    initAutocomplete();
    initMultiDropDown();

	// initRadio();
	//sucheBibliothek();
});

function initAutocomplete() {
    // Alle Input-Felder mit Klasse 'autocomplete' initialisieren
	console.log('initAutocomplete aktiv');
	
    $(".autocomplete").each(function() {
        var $input = $(this);
        var proc = $input.data('proc');
        var targetId = $input.data('target'); // z.B. 'suggestTaktisch'
        var feedId = $input.data('feed'); // z.B. 'taktisch'
        // console.log('trgetid ',targetId);
        $input.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'common/API/VF_AutoCompl_jq.API.php',
                    method: 'POST',
                    data: {
                        query: request.term,
                        proc: proc
                    },
                    success: function(data) {
                        if (!Array.isArray(data)) {
                            data = [data];
                        }
                        response(data);
                    },
                    error: function() {
                        response([]);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                var $this = $(this);
                var targetId = $this.data('target');
                var feedId = $this.data('feed');

                // Versteckte Felder befüllen
                if (feedId) {
                    $('#' + feedId).val(ui.item.value);
                } else if (targetId) {
                    $('#' + targetId).val(ui.item.value);
                }

                // Eingabefeld
                $this.val(ui.item.label);

                // Ausgabe (wenn vorhanden)
                $('#ausgabebox_' + targetId).html('<strong>Auswahl: </strong>' + ui.item.label);
                return false;
            }
        });
    });
}

function initMultiDropDown() {

	    function loadOptions(level, parentValue) {
			console.log('loadOptions aktiv');
	        const nextLevel = level + 1;
	        const $nextSelect = $('#level' + nextLevel);
            const opVal = $("#opval").val();
	        $nextSelect.empty();
	        $nextSelect.append('<option value="Nix">Bitte wählen</option>');
// console.log('level',level);
// console.log("parentValue",parentValue);
// console.log('opVal',opVal);
	        if (parentValue === 'Nix') return;

	        $.ajax({
	            url: 'common/API/VF_MultiSel_Opt_jq.API.php', // Stelle sicher, dass der Pfad stimmt
	            method: 'GET',
	            data: {
	                level: level,
	                parent: parentValue,
	                opval: opVal // oder 2, je nach Bedarf
	            },
	            success: function(response) {
	                // Prüfen, ob response die erwartete Struktur hat
	                if (response.status === 'ok' && response.data) {
	                    $nextSelect.empty();
	                    response.data.forEach(function(item) {
	                        $nextSelect.append(
	                            $('<option></option>').val(item.value).text(item.text)
	                        );
	                    });
	                } else {
	                    console.log('Keine Daten für Level ' + level);
	                }
	            },
	            error: function() {
	                alert('Fehler beim Laden der Optionen.');
	            }
	        });
	    }

	    // Event-Listener für Dropdown 1
	    $('#level1').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(1, selectedVal);
	        $('#level2').val('Nix'); // Zurücksetzen
	        $('#level3').val('Nix');
	        $('#level4').val('Nix');
	        $('#level5').val('Nix');
	        $('#level6').val('Nix');
	    });

	    // Event-Listener für Dropdown 2
	    $('#level2').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(2, selectedVal);
	        $('#level3').val('Nix');
	        $('#level4').val('Nix');
	        $('#level5').val('Nix');
	        $('#level6').val('Nix');
	    });
	        // Event-Listener für Dropdown 3
	    $('#level3').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(3, selectedVal);
	        $('#level4').val('Nix');
	        $('#level5').val('Nix');
	        $('#level6').val('Nix');
	    });
	        // Event-Listener für Dropdown 4
	    $('#level4').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(4, selectedVal);
	        $('#level5').val('Nix');
	        $('#level6').val('Nix');
	    });
	        // Event-Listener für Dropdown 5c
	    $('#level5').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(5, selectedVal);
	        $('#level6').val('Nix');
	    });
	        // Event-Listener für Dropdown 6
	    $('#level6').on('change', function() {
	        const selectedVal = $(this).val();
	        loadOptions(6, selectedVal);
	        
	    });
}

 function toggleVisibility(id) {
	console.log('toggleVisibility aktiv');
             var el = document.getElementById(id);
 			console.log('Element ',el);
             if (el.style.display === 'none') {
                 el.style.display = 'block';
             } else {
                 el.style.display = 'none';
             }
}

/**
 * Toggle alle 'foto-upd'-Elemente
 */
function toggleAll() {
    var containers = document.querySelectorAll('.foto-upd-container');
    var allHidden = true;
    console.log('toggleAll aktiv');
    // Prüfen, ob alle versteckt sind
    containers.forEach(function(cont) {
        if (window.getComputedStyle(cont).display !== 'none') {
            allHidden = false;
        }
    });

    // Alle sichtbar machen oder verstecken
    containers.forEach(function(cont) {
        cont.style.display = allHidden ? 'block' : 'none';
    });
}

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

