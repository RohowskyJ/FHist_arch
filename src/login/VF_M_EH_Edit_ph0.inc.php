<?php

/**
 * Liste der vom Verein verliehenen Ehrungen, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_M_EH_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input name='fe_lfnr' type='hidden' value='" . $neu['fe_lfnr'] . "' > ";
echo "<input name='fe_m_id' type='hidden' value='" . $neu['fe_m_id'] . "' > ";

# =========================================================================================================
Edit_Tabellen_Header('Ehrungen');
# =========================================================================================================

Edit_Daten_Feld('fe_lfnr');
Edit_Daten_Feld('fe_m_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnung / Ehrung');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'fe_ehrung');
Edit_Daten_Feld(Prefix . 'fe_eh_datum', '', '', "type='date'");
Edit_textarea_Feld(Prefix . 'fe_begruendg', '', 'maxlength=256');

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================

echo "<input type='hidden' name='fe_bild1' value='" . $neu['fe_bild1'] . "'>";
echo "<input type='hidden' name='fe_bild2' value='" . $neu['fe_bild2'] . "'>";
echo "<input type='hidden' name='fe_bild3' value='" . $neu['fe_bild3'] . "'>";
echo "<input type='hidden' name='fe_bild4' value='" . $neu['fe_bild4'] . "'>";

$pict_path = "AOrd_Verz/1/MITGL/";

$Feldlaenge = "150px";

$pic_arr = array(
    "01" => "|||fe_bild1",
    "02" => "|||fe_bild2",
    "03" => "|||fe_bild3",
    "04" => "|||fe_bild4"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fe_uidaend');
Edit_Daten_Feld(Prefix . 'fe_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
if (isset($_SESSION[$module]['Return']) AND $_SESSION[$module]['Return']) {
    echo "<p><a href='VF_M_Ehrg_List.php' >Zurück zur Liste</a></p>";
} else {
    echo "<p><a href='VF_M_List.php' >Zurück zur Liste</a></p>";
}

echo "</div>";

if ($debug) {
    echo "<pre class=debug>VF_M_EH_Edit_ph0.inc.php beendet </pre>";
}

?>