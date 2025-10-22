/**
 * JS funktionen mit JQUERY / JQ-UI
 * 
 * Autocomplete
 * 
 */

    // Alle Input-Felder mit Klasse 'autocomplete' initialisieren
    $(".autocomplete").each(function() {
        var $input = $(this);
        var proc = $input.data('proc');
        var targetId = $input.data('target'); // z.B. 'suggestTaktisch'
        var feedId = $input.data('feed'); // z.B. 'taktisch'
        console.log('trgetid ',targetId);
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

