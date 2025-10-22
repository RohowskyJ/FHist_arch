<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_BE_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_BE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo $Err_Msg;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='" . $neu['vb_flnr'] . "' />";
echo "<input name='vb_datum' id='aufnDat' type='hidden' value='" . $neu['vb_datum'] . "' />";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Daten der Veranstaltung');
# =========================================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $Edit_Funcs_Protect = True;
}

Edit_Daten_Feld('vb_flnr');
# =========================================================================================================
Edit_Separator_Zeile('Datum');
# =========================================================================================================

# Edit_Daten_Feld('vb_datum', 10, '', "type='date' ");
Edit_Daten_Feld('vb_datum');

# =========================================================================================================
Edit_Separator_Zeile('Titel, Beschreibung, Foto');
# =========================================================================================================

Edit_Daten_feld('vb_titel', 80);
Edit_textarea_Feld('vb_beschreibung', '', 'cols=70 rows=4');

if ($neu['vb_flnr'] == 0) {
    $us_arr = array(
        "Unterseiten" => "mit Unterseiten",
        "Keine" => "Keine Unterseiten"
    );
    Edit_Radio_Feld('vb_unterseiten', $us_arr);
    
    Edit_CheckBox('vb_auto','Daten aus der Fahrzeugbeschreibung benutzen?');
} else {
    $Edit_Funcs_protect = True;
    
    $us_arr = array(
        "Unterseiten" => "mit Unterseiten",
        "Keine" => "Keine Unterseiten"
    );
    Edit_Radio_Feld('vb_unterseiten', $us_arr);
    $Edit_Funcs_protect = False;
}

Edit_Separator_Zeile('Änderungen');

Edit_Daten_Feld('vb_uid');
Edit_Daten_Feld('vb_aenddat');

Edit_Tabellen_Trailer();

/**
 * Auswahl der Fotos bei Neueingabe, Anzeige der ausgewählten Fotos mit Einteilung bei Bestand
 */
if ($_SESSION[$module]['vb_flnr'] == 0) {  // Neu- Eingabe
    $j = 1;
    ?>
    
    <!-- Radio Buttons für die Auswahl  -->
    <label>
    <input type="radio" name="sel_libs_<?php echo $j; ?>" id="sel_libs_ja<?php echo $j; ?>" value="ja"> aus Bibliothek auswählen
    </label>
 
    <!-- Bereich für die diversen Ausgaben von js  -->
    <input type="hidden" id="bild-datei-auswahl_<?php echo $j ?>" name="bild_datei_<?php echo $j ?>" value="" />
    
    <!-- Bereich, um die ausgewählten Bildinfos anzuzeigen (immer im DOM) -->
    <div id="auswahl-bild" style="display:none;">
    <h3>Neu gewähltes Bild:</h3>
    <div id="bild-vorschau-auswahl"></div>
    <p>Dateiname: <span id="dateiname-auswahl"></span></p>
    </div>
    
    <!-- Galerie-Container für die Bildauswahl -->
    <div id="bild-galerie" style="display:none; border:1px solid #ccc; padding:10px;"></div>
    
    <!-- Dialog für die Bilder-Auswahl (separater Dialog, eigene IDs) -->
    <div id="dialog-bilder" style="display:none;">
    <div id="bild-vorschau-dialog"></div>
    <div id="dateiname-dialog"></div>
    <input type="hidden" id="bild-datei-dialog">
    </div>
    <hr>
    
    <script>
var bilder = {}; // globale Variable für die Bilderliste

// Funktion zum Umschalten zwischen Bibliothek und Upload --- sollte nicht mehr nätig sein
function toggleGruppen(biNr) {
    console.log('toggle Gruppen');
    const selLibsYes = $('#sel_libs_ja' + biNr);
    const selLibsNo = $('#sel_libs_nein' + biNr);
    const groupSearch = $('#sel_lib_suche' + biNr);
    const groupUpload = $('#sel_lib_upload' + biNr);
    console.log('grupl ',groupUpload);
     
    const auswahlBild = $('#auswahl-bild_' + biNr);

    if (selLibsYes.is(':checked')) {
        groupSearch.show();
        groupUpload.hide();
        auswahlBild.show();
        startAjax(biNr);
    } else if (selLibsNo.is(':checked')) {
        groupSearch.hide();
        groupUpload.show();
        auswahlBild.hide();
    }
}

// Funktion, um Bilder basierend auf der Auswahl zu laden
function startAjax(biNr) {
    // Beispiel: Daten sammeln
    var sammlg = $('#sammlung').val() || ''; //.trim();
    var eigner = $('#eigner') || ''; // .val();
    var aufnDat = $('#aufnDat') || '';
    console.log('Sammlg ',sammlg);
    console.log('Eigner ',eigner);
    console.log('aufnDat ',aufnDat);

    // Level-Filter (hast du ggf. in deiner Seite)
    var level1 = $('#level1').val() || '';
    var level2 = $('#level2').val() || '';
    var level3 = $('#level3').val() || '';
    var level4 = $('#level4').val() || '';
    var level5 = $('#level5').val() || '';
    var level6 = $('#level6').val() || '';

    // Auswahl anhand der Level
    if (level6 && level6.toLowerCase() !== 'nix' && fz_sammlg.length < level6.length) {
        fz_sammlg = level6;
    } else if (level5 && level5.toLowerCase() !== 'nix' && fz_sammlg.length < level5.length) {
        fz_sammlg = level5;
    } else if (level4 && level4.toLowerCase() !== 'nix' && fz_sammlg.length < level4.length) {
        fz_sammlg = level4;
    } else if (level3 && level3.toLowerCase() !== 'nix' && fz_sammlg.length < level3.length) {
        fz_sammlg = level3;    
    } else if (level2 && level2.toLowerCase() !== 'nix' && fz_sammlg.length < level2.length) {
        fz_sammlg = level2;
    }    

    // AJAX-Anfrage
    $.ajax({
        url: 'common/API/VF_SelPictLib.API.php',
        method: 'POST',
        data: {
            'sammlg': sammlg,
            'eigner': eigner,
            'aufnDat' : aufnDat
        },
        dataType: 'json',
        success: function(daten) {
            console.log('Success ',daten);
            bilder[biNr] = daten;
            // Galerie im Dialog füllen
            var dialog = $('#dialog-bilder_' + biNr);
            var galerieHtml = '<div style="display:flex; flex-wrap:wrap; gap:10px;">';
            for (let i=0; i<daten.length; i++) {
                let b = daten[i];
                galerieHtml += `<div class="bild-item" data-index="${i}" style="cursor:pointer; border:1px solid #ccc; padding:5px;">
                        <img src="${b.pfad}" alt="${b.dateiname}" width="200"><br>${b.dateiname}
                    </div>`;
            }
            galerieHtml += '</div>';
            dialog.find('#bild-vorschau-dialog_' + biNr).html(galerieHtml);
            dialog.find('#dateiname-dialog_' + biNr).text('');
            dialog.find('#bild-datei-dialog_' + biNr).val('');
            dialog.find('#bild-nummer-dialog_' + biNr).val(1);
            // Dialog öffnen
            dialog.dialog({ width: 800, modal: true });
        }
    });
}


// Klick auf Bild in Galerie
$(document).on('click', '[id^=bild-galerie_] .bild-item', function() {
    // This handler is now obsolete, as gallery is only in dialog. No action needed.
});

// Click handler for image selection in dialog gallery
$(document).on('click', '[id^=dialog-bilder_] .bild-item', function() {
    const biNr = $(this).closest('[id^=dialog-bilder_]').attr('id').split('_')[1];
    const index = $(this).data('index');
    if (bilder[biNr]) {
        const bild = bilder[biNr][index];
        // Vorschau im Dialog setzen (optional, or just highlight selection)
        // $('#bild-vorschau-dialog_' + biNr).html(`<img src="${bild.pfad}" width="250">`);
        $('#dateiname-dialog_' + biNr).text(bild.dateiname);
        $('#bild-datei-dialog_' + biNr).val(bild.dateiname);

        // Auch in der Auswahl-Box anzeigen
        $('#bild-vorschau-auswahl_' + biNr).html(`<img src="${bild.pfad}" width="250">`);
        $('#dateiname-auswahl_' + biNr).text(bild.dateiname);
        $('#bild-datei-auswahl_' + biNr).val(bild.dateiname);

        // Zeige den Auswahl-Bereich explizit an (falls versteckt)
        $('#auswahl-bild_' + biNr).show();
        // Optional: Scrolle zum Auswahl-Bereich
        document.getElementById('auswahl-bild_' + biNr).scrollIntoView({behavior: 'smooth', block: 'center'});

        // Dialog schließen
        $('#dialog-bilder_' + biNr).dialog('close');
    }
});
/*
$(document).ready(function() {
    <?php for ($j = 1; $j <= $pic_cnt; $j++): ?>
        // Initiales Umschalten
        toggleGruppen(<?php echo $j; ?>);
        // Event für Radio-Buttons
        $('#sel_libs_ja<?php echo $j; ?>, #sel_libs_nein<?php echo $j; ?>').change(function() {
            toggleGruppen(<?php echo $j; ?>);
        });
    <?php endfor; ?>
});
*/    

</script>
    <?php 
    
} else {  // Bestand bearbeiten
    echo "<p><a href='VF_O_BE_List.php?Act=?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";
    
    if ($_SESSION[$module]['Fo']['FOTO']) {
        foreach ($eig_foto as $key) {
            $farr = explode("|", $key);
            
            if (!isset($farr[1])) {
                $farr[1] = "";
            }
            
            $fo_arr[$farr[0]] [] = $farr[1];
            VF_Displ_Urheb_n($farr[0]);
            VF_Displ_Eig($farr[0]);
            $tabelle = "dm_edien_";
        }
        
        require "VF_FO_List_Bericht.inc.php";
    } else {
        $tabelle = "vb_ber_detail_4";
        /**
         * Phase, in der die EIngabe in der Tabelle landen soll
         *
         * @var string $TabButton 0: phase, 1: Farbe, 2: Text, 3: Rücksprung-Link
         */
        $TabButton = "2|green|Bilder für den Bericht speichern.|"; #
        require "VF_FO_List_Ber_Det.inc.php";
    }
    
}
if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}


echo "<p><a href='VF_BE_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================
if ($debug) {
    echo "<pre class=debug>VF_BE_edit.ph0.php beendet</pre>";
}

?>