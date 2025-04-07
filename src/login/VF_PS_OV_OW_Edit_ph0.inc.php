<?php

/**
 * Ortswappen- Wartung: Formular
 *
 * @author josef Rohowsky - neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_PS_OW_OW_Edit_ph0.inc ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fo_id' value='" . $neu['fo_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('Orts- Wappen');
# =========================================================================================================

Edit_Daten_Feld('fo_id');
Edit_Daten_Feld('fo_fw_id');

# =========================================================================================================
Edit_Separator_Zeile('Ortswappen- Daten');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fo_gde_w_sort', 4);

# =========================================================================================================
Edit_Separator_Zeile('Abbild des Wappens');
# =========================================================================================================
echo "<input type='hidden' name='fo_gde_wappen' value='" . $neu['fo_gde_wappen'] . "'>"; 

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";;
$pict_path = "AOrd_Verz/PSA/AERM/Wappen_Ort/";

$Feldlaenge = "100px";
Edit_Show_Pict(Prefix . 'fo_gde_wappen', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
   # if (empty($neu['fo_gde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}

Edit_textarea_Feld(Prefix . 'fo_gde_w_komment');

Edit_Upload_File('fo_gde_wappen', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '1' # Identifier bei mehreren uploads
);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fo_aenduid');
Edit_Daten_Feld(Prefix . 'fo_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_PS_OV_OW_Edit.php?fw_id=" . $_SESSION[$module]['fw_id'] . "'>Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_OW_Edit_v3ph0.inc beendet</pre>";
}
?>