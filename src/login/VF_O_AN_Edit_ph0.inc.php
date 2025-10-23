
<?php
/**
 * Lister der Anbote / Nachfragen, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_O_An_Edit_ph0.php ist gestarted</pre>";
}

if ($neu['bs_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

$today = date("Y-m_d");
Edit_Tabellen_Header('Angebot / Nachfrage');
# =========================================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $Edit_Funcs_Protect = True;
}
Edit_Daten_Feld('bs_id');
echo "<input type='hidden'name='bs_id' value='" . $neu['bs_id'] . "' >";
# =========================================================================================================
Edit_Separator_Zeile('Aussendung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'bs_startdatum', 10, '', "type='date' min='$today'");
Edit_Daten_Feld(Prefix . 'bs_enddatum', 10, '', "type='date' min='$today'");

echo "<input type='hidden'name='bs_typ' value='" . $neu['bs_typ'] . "' >";
Edit_Radio_Feld(Prefix . 'bs_typ', array(
    "B" => "Biete",
    "S" => "Suche"
));
Edit_textarea_Feld(Prefix . 'bs_kurztext');
Edit_textarea_Feld(Prefix . 'bs_text');

Edit_Daten_Feld(Prefix . 'bs_email_1', 50);

Edit_Daten_Feld(Prefix . 'bs_email_2', 50);

# =========================================================================================================
Edit_Separator_Zeile('Beschreiber');
# =========================================================================================================

Edit_Daten_Feld('bs_aenduid', 5);
Edit_Daten_Feld('bs_aenddate');

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
// Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
$button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Bilder ',$button_f);
# =========================================================================================================
echo "<input type='hidden' name='bs_bild_1' value='" . $neu['bs_bild_1'] . "'>";
echo "<input type='hidden' name='bs_bild_2' value='" . $neu['bs_bild_2'] . "'>";
echo "<input type='hidden' name='bs_bild_3' value='" . $neu['bs_bild_3'] . "'>";
echo "<input type='hidden' name='bs_bild_4' value='" . $neu['bs_bild_4'] . "'>";

$pict_path = $path2ROOT."login/AOrd_Verz/Biete_Suche/";

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";


echo "<input type='hidden' id='urhNr' value=''>";
echo "<input type='hidden' id='aOrd' value=''>";

echo "<input type='hidden' id='reSize' value='1754'>";

$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => '', 'bi' => 'bs_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    
    echo "<input type='hidden' id='aOrd_$i' value='Biete_Suche/'>";
    $i++;
}

VF_Upload_Form_M();

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>"; 
}

echo "<p><a href='VF_O_AN_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_toggle.js' ></script>";

if ($debug) {
    echo "<pre class=debug>VF_O_An_Edit_ph0.php beendet</pre>";
}
?>