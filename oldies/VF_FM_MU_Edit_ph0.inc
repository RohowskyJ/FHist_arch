<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Formular
 *
 * @author Josef Rohowsky  neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph0.inc ist gestarted</pre>";
}

echo "<input type='hidden' name='fm_id' value='$fm_id'/>";
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('fm_id');
Edit_Daten_Feld('fm_eignr');
Edit_Daten_Feld('fm_invnr');
# =========================================================================================================
Edit_Separator_Zeile('Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fm_bezeich', 100);
Edit_Daten_Feld('fm_type', 60);
Edit_Daten_Feld('fm_indienst', 10, 'Datum oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fm_ausdienst', 10, 'Datum oder zumindest Jahr der Ausserdienst- Stellung');
Edit_textarea_Feld('fm_komment');

Edit_Daten_Feld('fm_leistung', 10);
Edit_Daten_Feld('fm_lei_bed', 50);

Edit_Daten_Feld('fm_herst', 60);
Edit_Daten_Feld('fm_baujahr', 10);
Edit_Select_Feld(Prefix . 'fm_zustand', VF_Zustand, '');
Edit_Daten_Feld('fm_fgstnr', 60);

Edit_Daten_Feld('fm_gew', 10);

Edit_Daten_Feld('fm_zug', 10);

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='fm_foto_1' value='" . $neu['fm_foto_1'] . "'>";
echo "<input type='hidden' name='fm_foto_2' value='" . $neu['fm_foto_2'] . "'>";
echo "<input type='hidden' name='fm_foto_3' value='" . $neu['fm_foto_3'] . "'>";
echo "<input type='hidden' name='fm_foto_4' value='" . $neu['fm_foto_4'] . "'>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/GER/";

$Feldlaenge = "100px";

$pic_arr = array(
    "01" => "||fm_komm_1|fm_foto_1",
    "02" => "||fm_komm_2|fm_foto_2",
    "03" => "||fm_komm_3|fm_foto_3",
    "04" => "||fm_komm_4|fm_foto_4"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================

echo "<tr><td colspan='3'><div class='w3-container w3-aqua'>  <div class='label'>Sammlung - Sammlungsbezeichnung</div></div></td><td>";
echo "<div class='w3-container w3-aqua'>";

Edit_Select_Feld('fm_sammlg', VF_Sammlung_A0_F, '');
 
echo "</div>";

echo "</td></tr>";
# =========================================================================================================
Edit_Separator_Zeile('Standardiesierte Suchbegriffe');
# =========================================================================================================

$l1sb = $neu['fm_suchbegr_1'];
$l2sb = $neu['fm_suchbegr_2'];
$l3sb = $neu['fm_suchbegr_3'];
$l4sb = $neu['fm_suchbegr_4'];
$l5sb = $neu['fm_suchbegr_5'];
$l6sb = $neu['fm_suchbegr_6'];

$sb_s1 = $sb_s2 = $sb_s3 = $sb_s4 = $sb_s5 = $sb_s6 = "";

VF_Suchbegr_Ausw_js($sb_s1, $sb_s2, $sb_s3, $sb_s4, $sb_s5, $sb_s6);

VF_Suchbegr_Edit();

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fm_uidaend');
Edit_Daten_Feld('fm_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FM_GE_List.php?ID='.$_SESSION[$module]['fm_sammlung']>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph0.inc beendet</pre>";
}
?>