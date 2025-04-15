<?php

/**
 * Foto- Verwaltung, Wartung. Formular
 *
 * @author J. Rohowsky  - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<input type='hidden' name='fo_id' value='" . $neu['fo_id'] . "'/>";
echo "<input type='hidden' name='fo_eigner' value='" . $neu['fo_eigner'] . "'/>";
echo "<input type='hidden' name='fo_typ' value='" . $neu['fo_typ'] . "'/>";
echo "<input type='hidden' name='fo_media' value='" . $neu['fo_media'] . "'/>";
echo "<input type='hidden' name='fo_Urheber' value='" . $neu['fo_Urheber'] . "'/>";
echo "<input type='hidden' name='fo_uidaend' value='" . $neu['fo_uidaend'] . "'/>";
echo "<input type='hidden' name='fo_aenddat' value='" . $neu['fo_aenddat'] . "'/>";

if (!empty($Err_msg) ) {echo "<span class='error'>Eingabe Fehler,bitte korrigieren.</span>";}


# =========================================================================================================
Edit_Tabellen_Header('Foto- / Video Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fo_id');
Edit_Daten_Feld('fo_eigner');
# Edit_Daten_Feld('fo_Urheber', 50);

# =========================================================================================================
Edit_Separator_Zeile('Daten');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'fo_namen', 'Namen der Personen am Bild', "rows='2' cols='50'");

Edit_textarea_Feld(Prefix . 'fo_suchbegr', 'Suchbegriffe', "rows='2' cols='50'");

echo "<input type='hidden' name='fo_aufn_datum' value='" . $neu['fo_aufn_datum'] . "'/>";
echo "<input type='hidden' name='fo_aufn_suff' value='" . $neu['fo_aufn_suff'] . "'/>";
echo "<input type='hidden' name='fo_dsn' value='" . $neu['fo_dsn'] . "'/>";
echo "<input type='hidden' name='fo_typ' value='" . $neu['fo_typ'] . "'/>";
echo "<input type='hidden' name='verz' value='" . $verz . "'/>";

Edit_Daten_Feld('fo_Urheber', 60, 'Verfüger');
/*
if (isset($_SESSION[$module]['Fo']['URHEBER']['urh_abk']) && is_array($_SESSION[$module]['Fo']['URHEBER']['urh_abk'])) {
    Edit_Radio_Feld('fo_Urh_kurzz', $_SESSION[$module]['Fo']['URHEBER']['urh_abk']);
} else {
    Edit_Daten_Feld('fo_Urh_kurzz');
}
*/
echo "<input type='hidden' name='MAX_FILE_SIZE' value='800000' />";
if ($neu['fo_typ'] == 'F') { # Foto
    if ($verz == "J" ) { ## erst  Verzeichnis definierten - dann erst die Fotos ins Verzeichnis
        Edit_textarea_Feld('fo_begltxt');
        Edit_Separator_Zeile('Foto und Speicherort: wenn das Feld Pfad einen Wert beinhaltet, wir dieser Wert benutzt, egal ob es ein Aufnahmedatum gibt oder nicht');
        Edit_Daten_Feld('fo_aufn_datum', 15,'YYYYmmDD Format oder Jahreszahl'); # ,'YYYYmmDD Format oder Jahreszahl'    
        Edit_Daten_Feld('fo_aufn_suff', 2,'am gleichem Datum mehrere Ereignisse => Suffix eingeben!'); 
    } else {
        $d_path = VF_set_PictPath($neu['fo_aufn_datum'],$neu['fo_aufn_suff']);
        $pict_path = $neu['Bildpfad'] = $pict_path . $d_path;
        $Tabellen_Spalten_COMMENT['Bildpfad'] = "Pfad zur Datei";
        Edit_Daten_Feld('Bildpfad');
        
        $Feldlaenge = "100px";
        
        $pic_arr = array(
            "1" => "||fo_begltxt|fo_dsn"
        );
        VF_Multi_Foto($pic_arr);
        
    }
} else  { # Video sofort ohne Zusatzverzeichnis Video Record anlegen
    Edit_Daten_Feld('fo_aufn_datum', 15,'YYYYmmDD Format oder Jahreszahl'); # ,'YYYYmmDD Format oder Jahreszahl'
    $neu['Bildpfad'] = $pict_path;
    $Tabellen_Spalten_COMMENT['Bildpfad'] = "Pfad zur Datei";
    $pic_arr = array(
        "01" => "||fo_begltxt|fo_dsn"
    );
    VF_Multi_Foto($pic_arr);
}

# =========================================================================================================
Edit_Separator_Zeile('Für Teile einer Sammlung: Sammlung auswählen');
# =========================================================================================================

Edit_Daten_Feld('fo_sammlg','');

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
 *
 *
 * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
 * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
 * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
 *
 * @Input-Parm $_POST['Level1...6']
 */

$MS_Lvl   = 4; # 1 ... 6
$MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung

$MS_Txt = array(
    'Auswahl der Sammlungs- Type (1. Ebene)',
    'Auswahl der Sammlungs- Gruppe (2. Ebene)',
    'Auswahl der Untergrupppe (3. Ebene)',
    'Auswahl des Spezifikation (4. Ebene)'
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val,$titel);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fo_uidaend');
Edit_Daten_Feld('fo_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FO_List_Detail.php?fo_aufn_d=" . $neu['fo_aufn_datum'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph0.inc.php beendet</pre>";
}
?>