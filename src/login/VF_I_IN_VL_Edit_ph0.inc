<?php

/**
 * Inventarverleihliste, Wartung, Formular 
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_I_IN_VL_Edit_ph0.inc ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='vl_id' value='" . $neu['vl_id'], "' ";
# =========================================================================================================
Edit_Tabellen_Header('Verleih');
# =========================================================================================================

Edit_Daten_Feld('vl_id');
Edit_Daten_Feld('ei_id');
Edit_Daten_Feld('ei_invnr');
echo "<input type='hidden' name='ei_id' value='" . $neu['ei_id'] . "' >";
echo "<input type='hidden' name='ei_invnr' value='" . $neu['ei_invnr'] . "' >";
echo "<input type='hidden' name='ei_id' value='" . $neu['ei_id'] . "' >";
# =========================================================================================================
Edit_Separator_Zeile('Zustandsbeschreibung');
# =========================================================================================================
echo "<input type='hidden' name='ei_zust_aus_bild' value='" . $neu['ei_zust_aus_bild'] . "' >";
echo "<input type='hidden' name='ei_zust_ret_bild' value='" . $neu['ei_zust_ret_bild'] . "' >";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";

$Feldlaenge = "100px";

$pic_arr = array(
    "1" => "||ei_zustand_aus|ei_zust_aus_bild",
    "2" => "||ei_zustand_ret|ei_zust_ret_bild"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Verleihung');
# =========================================================================================================
$cdate = date('Y-m-d');
/*
$sub_funct = 81;
$opt_eig = VF_Sel_Eigner($neu['ei_leiher'], $sub_funct);

Edit_Select_Feld('ei_leiher', $opt_eig);
*/
echo "<input type='hidden' name='ei_leiher' value='".$neu['ei_leiher']."'>";

Edit_Daten_Feld(Prefix.'ei_leiher');
$tit_eig_leih = 'Leiher';
VF_Eig_Ausw();
# Edit_Daten_Feld_auto('ei_leiher',75,'','','srch_eigent');

Edit_Daten_Feld(Prefix . 'ei_leihvertr', 50);
Edit_Daten_Feld(Prefix . 'ei_verlbeg', 10, '', "type='date' required min='$cdate'");
Edit_Daten_Feld(Prefix . 'ei_verlend', 10, '', "type='date' required min='$cdate'");
Edit_textarea_Feld(Prefix . 'ei_verlgrund', 250);
Edit_Daten_Feld(Prefix . 'ei_verlrueck', 10, '', "type='date' min='$cdate'");
Edit_Daten_Feld(Prefix . 'ei_verluebn', 10);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'ei_uidaend');
Edit_Daten_Feld(Prefix . 'ei_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_I_IN_Edit.php?in_id=" . $_SESSION[$module]['in_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_I_IN_VL_Edit_ph0.inc beendet</pre>";
}
?>