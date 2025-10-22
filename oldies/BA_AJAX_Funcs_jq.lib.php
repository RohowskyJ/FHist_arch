<?php
/**
 * AJAX Funktionen für
 *
 * BA_AUto_Funktion       Autocomplete
 * BA_Multi_Drop_Down     Multiple Dropdown Select
 *
 *
 */

/**
 * Auto_Complete-Funktion für die Aufrufe von
 * VF_Auto_Aufbau
 * VF_Auto_Eigent
 * VF_Auto_Herstell
 * VF_Auto_Taktisch
 * VF_Auto_Urheber
 *
 *
 */
function BA_Auto_Funktion_old()
{

    ?>
<script>    
    $(document).ready(function() {
        console.log('DOM geladen');
        
        // Alle input-Felder mit Klasse 'autocomplete' initialisieren
        $('.autocomplete').each(function() {
            var $input = $(this);
            var proc = $input.data('proc');
            var targetId = $input.data('target');
            var $suggestDiv = $('#' + targetId);
            
            // Anpassen des Aussehens der Vorschlags-Div
            $suggestDiv.css({
                'position': 'absolute',
                'z-index': 9999,
                'display': 'none',
                'background': '#fff',
                'border': '1px solid #ccc',
                'max-height': '200px',
                'overflow-y': 'auto',
                'width': $input.outerWidth()
            });
                
                // Hidden input für Wert (ID)
                var hiddenInputId = 'hidden_' + targetId;
                var $hiddenInput = $('<input>', {
                    type: 'hidden',
                    name: targetId,
                    id: hiddenInputId
                });
                    $input.after($hiddenInput);
                    
                    // Ausgabe-Box (sichtbar für User)
                    var ausgabeDivId = 'ausgabebox_' + targetId;
                    var $ausgabeDiv = $('<div>', {
                        id: ausgabeDivId,
                        css: {
                            'background-color': '#f0f0f0',
                            'padding': '8px',
                            'margin-top': '10px',
                            'border': '1px solid #ccc'
                        }
                    });
                        $input.wrap('<div style="position: relative; display: inline-block; width: 100%;"></div>');
                        $input.parent().append($ausgabeDiv);
                        
                        // Autocomplete-Implementierung
                        $input.on('keyup', function() {
                            var query = $(this).val().trim();
                            if (query.length > 1) {
                                $.ajax({
                                    url: 'common/API/VF_AutoCompl_jq.API.php',
                                    method: 'POST',
                                    data: {
                                        query: query,
                                        proc: proc
                                    },
                                    success: function(response) {
                                        console.log("Response", response);
                                        var data = JSON.parse(response);
                                        $suggestDiv.empty();
                                        console.log("Data",data);
                                        if (data.length > 0) {
                                            // Positionierung der Vorschläge
                                            var offset = $input.offset();
                                            var height = $input.outerHeight();
                                            
                                            $suggestDiv.css({
                                                top: offset.top + height - $suggestDiv.outerHeight(),
                                                left: offset.left,
                                                width: $input.outerWidth(),
                                                display: 'block'
                                            });
                                                
                                                // Vorschläge anzeigen
                                                $.each(data, function(index, item) {
                                                    var $div = $('<div>', {
                                                        class: 'suggestion-item',
                                                        'data-value': item.value,
                                                        'data-label': item.label,
                                                        css: {
                                                            'padding': '5px',
                                                            'cursor': 'pointer'
                                                        }
                                                    }).text(item.label);
                                                    console-log("text", item.label);
                                                    $div.on('click', function() {
                                                        $input.val(item.label);
                                                        $('#' + hiddenInputId).val(item.value);
                                                        $('#' + 'ausgabebox_' + targetId).html('<strong>Auswahl:</strong> ' + item.label);
                                                        $suggestDiv.hide();
                                                    });
                                                        
                                                        $suggestDiv.append($div);
                                                });
                                                    $suggestDiv.show();
                                        } else {
                                            $suggestDiv.hide();
                                            $suggestDiv.empty();
                                        }
                                    },
                                    error: function() {
                                        $suggestDiv.hide();
                                        $suggestDiv.empty();
                                    }
                                });
                            } else {
                                $suggestDiv.hide();
                                $suggestDiv.empty();
                            }
                        });
                            
                            // Optional: Hide suggestions bei Klick außerhalb
                            $(document).on('click', function(e) {
                                if (!$(e.target).closest($input).length && !$(e.target).closest($suggestDiv).length) {
                                    $suggestDiv.hide();
                                }
                            });
        });
    });
 </script>   
 <?php
} // Ende BA_Auto_Funktion


function BA_Auto_Funktion()
{

    ?>
<!-- JavaScript-Code für Autocomplete gradually-->
<script>
$(document).ready(function() {
    console.log('DOM geladen');
    
    // Autocomplete initialisieren für alle Eingabefelder mit Klasse 'autocomplete'
    $(".autocomplete").each(function() {
        var $input = $(this);
        var proc = $input.data('proc');
        var targetId = $input.data('target'); // z.B. 'suggestTaktisch'
        var feedId = $input.data('feed'); // z.B. 'taktisch'
        
        console.log('Input ',$input);
        
        $input.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'common/API/VF_AutoCompl_jq.API.php', // Dein API-Endpunkt
                    method: 'POST',
                    data: {
                        query: request.term,
                        proc: proc
                    },
                    // Optional: response handling
                    success: function(data) {
                            console.log("API-Daten", data);
                            // Falls dein data kein Array ist, oder unerwartet:
                            if (!Array.isArray(data)) {
                                data = [data];
                            }
                            response(data);
                        // Falls die API bereits JSON ist (Content-Type: application/json):
                        // response(data);
                        // Falls API noch Text ist, parse:
                        // var parsedData = JSON.parse(data);
                        // response(parsedData);
                    },
                    error: function() {
                        response([]);
                    }
                });

            },
            minLength: 2,
            select: function(event, ui) {
                console.log('ui item ',ui.item);
                var targetId = $(this).data('target');
                // console.log('targetId ',targetId);
                $('#'+targetId).val(ui.item.value); // verstecktes Feld, z.B. ID
                // $('#taktisch').val(ui.item.value); // anderes verstecktes Feld
                // $('#' + $(this).data('target')).val(ui.item.value);
               
                
                // Wert in das Ziel versteckte Input setzen
                if (feedId) {
                    $('#' + feedId).val(ui.item.value);
                } else if (targetId) {
                    $('#' + targetId).val(ui.item.value);
                }
                
               /* sollte jetzt ohne diese Zeilen gehen
                    // Optional: falls spezifisches Feld wie 'taktisch' aktualisieren
                    // Das macht nur Sinn, wenn du ein separates Feld (z.B. #taktisch) hast und willst es explizit setzen
                    
                    if (targetId === 'suggestAufbauer') {
                         $('#aufbauer').val(ui.item.value);
                    }
                    if (targetId === 'suggestTaktisch') {
                         $('#taktisch').val(ui.item.value);
                    }
                    if (targetId === 'suggestEigener') {
                         $('#eigentuemer').val(ui.item.value);
                    }
                    if (targetId === 'suggestHersteller') {
                         $('#hersteller').val(ui.item.value);
                    }
                    if (targetId === 'suggestTaktisch') {
                         $('#taktisch').val(ui.item.value);
                    }
                    if (targetId === 'suggestUrheber') {
                         $('#urheber').val(ui.item.value);
                    }
                */    
              
                // Das sichtbare Eingabefeld zeigt jetzt das Label 
                $(this).val(ui.item.label);
                $('#ausgabebox_'+targetId).html('<strong>Auswahl: </strong>' + ui.item.label);
                return false; // damit das Standard-verhalten nicht nochmal überschreibt
            }
        });
    });
});
    </script>
    <?php
} // Ende BA_Auto_Funktion



function BA_Multi_Drop_Down()
{
    ?>
<script>
$(document).ready(function() {
    // Funktion zum Laden der Optionen
    function loadOptions(level, parentValue) {
        const nextLevel = level + 1;
        const $nextSelect = $('#level' + nextLevel);
        $nextSelect.empty();
        $nextSelect.append('<option value="Nix">Bitte wählen</option>');

        if (parentValue === 'Nix') return;

        $.ajax({
            url: 'common/API/VF_MultiSel_Opt_json_2.API.php', // Stelle sicher, dass der Pfad stimmt
            method: 'GET',
            data: {
                level: level,
                parent: parentValue,
                opval: '1' // oder 2, je nach Bedarf
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
    $('#level1').change(function() {
        const selectedVal = $(this).val();
        loadOptions(1, selectedVal);
        $('#level2').val('Nix'); // Zurücksetzen
        $('#level3').val('Nix');
        $('#level4').val('Nix');
        $('#level5').val('Nix');
        $('#level6').val('Nix');
    });

    // Event-Listener für Dropdown 2
    $('#level2').change(function() {
        const selectedVal = $(this).val();
        loadOptions(2, selectedVal);
        $('#level3').val('Nix');
        $('#level4').val('Nix');
        $('#level5').val('Nix');
        $('#level6').val('Nix');
    });
        // Event-Listener für Dropdown 3
    $('#leve3').change(function() {
        const selectedVal = $(this).val();
        loadOptions(3, selectedVal);
        $('#level4').val('Nix');
        $('#level5').val('Nix');
        $('#level6').val('Nix');
    });
        // Event-Listener für Dropdown 4
    $('#level4').change(function() {
        const selectedVal = $(this).val();
        loadOptions(4, selectedVal);
        $('#level5').val('Nix');
        $('#level6').val('Nix');
    });
        // Event-Listener für Dropdown 5
    $('#level5').change(function() {
        const selectedVal = $(this).val();
        loadOptions(5, selectedVal);
        $('#level6').val('Nix');
    });
        // Event-Listener für Dropdown 6
    $('#level6').change(function() {
        const selectedVal = $(this).val();
        loadOptions(6, selectedVal);
        
    });
});



function sucheBibliothek(index) {
     var suchbegriff = $('#suche_' + index).val();
    
     console.log('Suchbegriff ',suchbegriff);
     // Hier sollte die AJAX-Anfrage an die API erfolgen
                // Für dieses Beispiel simulieren wir einige Bilder
                var simulations = [
                   'bild1.jpg',
                   'stadt_urlaub.png',
                   'natur_gruen.gif',
                   'architektur_building.jpg'
                   ];
                var ergebnisDiv = $('#suchergebnis_' + index);
                ergebnisDiv.empty();
                simulations.forEach(function(datei) {
                  if (datei.includes(suchbegriff) || suchbegriff.trim() == '') {
                     // Einfach alle Bilder anzeigen, die den Suchbegriff enthalten oder alle, wenn leer
                     var btn = $('<button type="button">Auswählen</button>');
                     btn.click(function() {
                     $('#foto_' + index).val(datei);
                      });
                     ergebnisDiv.append($('<div></div>').append('Datei: ' + datei + ' ').append(btn));
                  }
                });
 }
         
 $('input[type=radio].sel_libs').change(function() {
        var name = $(this).attr('name');
        var value = $(this).val();
        var id = name.split('_')[2]; // Annahme: name='sel_libs_1' etc.
        console.log('name ', name);
        console.log('value ',value);
        console.log('id ',id);
        if (value == 'Ja') {
            $('#upl_libs_' + id).show();
            $('#upl_new_' + id).hide();
        } else {
            $('#upl_libs_' + id).hide();
            $('#upl_new_' + id).show();
        }
    });         
    
</script>   
<?php
}
