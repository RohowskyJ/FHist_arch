<?php
/**
 * Wartung der Ärmelabzeichen bei der Feuerwehr, Formular
 *
 * @author Josef Rohowsky - neu 2020
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_PS_OV_AB_Edit_ph0.php-+ ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fo_id' value='" . $neu['fo_id'] . "'>";

# =========================================================================================================
Edit_Tabellen_Header('Ärmel- und Hemden- Abzeichen');
# =========================================================================================================

Edit_Daten_Feld('fo_id');
Edit_Daten_Feld('fo_fw_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Ärmel- Abzeichen');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fo_ff_a_sort', 4);

# =========================================================================================================
Edit_Separator_Zeile('Abbild des Wappens');
# =========================================================================================================
echo "<input type='hidden' name='fo_ff_abzeich' value='" . $neu['fo_ff_abzeich'] . "'>";

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/PSA/AERM/Aermel_Abz/";

$Feldlaenge = "100px";
Edit_Show_Pict(Prefix . 'fo_ff_abzeich', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
   # if (empty($neu['fo_gde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}

Edit_Select_Feld('fo_ff_a_typ_a', VF_Aermelabz_text, '');
Edit_textarea_Feld(Prefix . 'fo_ff_abz_typ');

Edit_Upload_File('fo_ff_abzeich', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
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
echo "<p><a href='VF_PS_OV_O_Edit.php?fw_id=" . $_SESSION[$module]['fw_id'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AB_Edit_ph0.inc.php beendet</pre>";
}
?>