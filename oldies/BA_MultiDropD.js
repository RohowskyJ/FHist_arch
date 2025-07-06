/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * Multi- Level Dropdpwn- List
 * 
 */

	    function loadOptions(level, parentValue) {
	        const nextLevel = level + 1;
	        const $nextSelect = $('#level' + nextLevel);
            const opVal = $("#opval").val();
	        $nextSelect.empty();
	        $nextSelect.append('<option value="Nix">Bitte wählen</option>');
console.log('level',level);
console.log("parentValue",parentValue);
console.log('opVal',opVal);
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
	        // Event-Listener für Dropdown 5
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

