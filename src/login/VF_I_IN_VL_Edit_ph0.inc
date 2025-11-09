<?php

/**
 * Inventarverleihliste, Wartung, Formular 
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_I_IN_VL_Edit_ph0.inc.php";


if ($debug) {
    echo "<pre class=debug>VF_I_IN_VL_Edit_ph0.inc ist gestarted</pre>";
}

if ($neu['vl_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
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
# ==========================================================================================================
$button_f = "";
if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
}
Edit_Separator_Zeile('Zustandsbeschreibung'.$button_f);
# =========================================================================================================
echo "<div>";
echo "<input type='hidden' id='urhNr' value='' >";
echo "<input type='hidden' name='ei_bild_1' value='" . $neu['ei_bild_1'] . "' >";
echo "<input type='hidden' name='ei_bild_2' value='" . $neu['ei_bild_2'] . "' >";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";

$Feldlaenge = "150px";
/*
$pic_arr = array(
    "1" => "||ei_zustand_aus|ei_zust_aus_bild",
    "2" => "||ei_zustand_ret|ei_zust_ret_bild"
);
VF_Multi_Foto($pic_arr);

$Feldlaenge = "100px";
*/
$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 2;

$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'ei_komm_'.$i, 'bi' => 'ei_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    $i++;
}

VF_Upload_Form_M();


echo "</div>";
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

# Edit_Daten_Feld(Prefix.'ei_leiher');
Edit_textarea_Feld('ei_leiher');
/*
$tit_eig_leih = 'Leiher';

Edit_Daten_Feld('ei_leiher',75);
# VF_Auto_Eigent('E', $cl = false,$j="1");
*/
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