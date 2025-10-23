<?php
/**
 * Liste der Buchbesprechungen, Wartung, Formular
 *
 * @author j. Rohowsky - neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_Bu_Edit_ph0.inc.php ist gestarted</pre>";
}

if ($neu['bu_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

echo "<input type='hidden' name='bu_id' value='" . $neu['bu_id'] . "' > ";
# =========================================================================================================
Edit_Tabellen_Header('Rezension');
# =========================================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $Edit_Funcs_Protect = True;
}
Edit_Daten_Feld('bu_id');

# =========================================================================================================
Edit_Separator_Zeile('Buch');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'bu_titel', 100);
Edit_Daten_Feld(Prefix . 'bu_utitel', 100);
Edit_Daten_Feld(Prefix . 'bu_author', 100);
Edit_Daten_Feld(Prefix . 'bu_verlag', 100);
Edit_Daten_Feld(Prefix . 'bu_isbn', 20);
Edit_Daten_Feld(Prefix . 'bu_preis', 10);
Edit_Daten_Feld(Prefix . 'bu_seiten', 5);
Edit_Daten_Feld(Prefix . 'bu_bilder_anz', 5);
Edit_Daten_Feld(Prefix . 'bu_bilder_art', 50);
Edit_Daten_Feld(Prefix . 'bu_format', 50);

# =========================================================================================================
Edit_Separator_Zeile('Beschreibung');
# =========================================================================================================
Edit_textarea_Feld(Prefix . 'bu_teaser');
Edit_textarea_Feld(Prefix . 'bu_text');

# =========================================================================================================
Edit_Separator_Zeile('Bewertung');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'bu_bew_ges', 10, 'Bewertung 1.. Eher Grottenschlecht, , 5.. Sehr Gut');
Edit_Daten_Feld(Prefix . 'bu_bew_bild', 10);
Edit_Daten_Feld(Prefix . 'bu_bew_txt', 10);

# =========================================================================================================
Edit_Separator_Zeile('Beschreiber');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'bu_editor', 70);
Edit_Daten_Feld(Prefix . 'bu_ed_id', 10);
Edit_Daten_Feld('bu_edit_dat');

if ($_SESSION[$module]['all_upd']) {
    # =========================================================================================================
    Edit_Separator_Zeile('Freigabe (für alle Benutzer sichtbar)');
    # =========================================================================================================

    Edit_Radio_Feld(Prefix . 'bu_frei_stat', array(
        "U" => "U",
        "F" => "F"
    ));
    Edit_Daten_Feld(Prefix . 'bu_frei_id', 70);
    Edit_Daten_Feld('bu_frei_dat');
}
# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
// Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
$button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Bilder und Bildbeschreibungen ',$button_f);
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";

$pict_path = "../login/AOrd_Verz/Buch/";

echo "<input type='hidden' name='bu_bild_1' value='" . $neu['bu_bild_1'] . "'>";
echo "<input type='hidden' name='bu_bild_2' value='" . $neu['bu_bild_2'] . "'>";
echo "<input type='hidden' name='bu_bild_3' value='" . $neu['bu_bild_3'] . "'>";
echo "<input type='hidden' name='bu_bild_4' value='" . $neu['bu_bild_4'] . "'>";
echo "<input type='hidden' name='bu_bild_5' value='" . $neu['bu_bild_5'] . "'>";
echo "<input type='hidden' name='bu_bild_6' value='" . $neu['bu_bild_6'] . "'>";

echo "<input type='hidden' id='urhNr' value=''>";
echo "<input type='hidden' id='aOrd' value=''>";

echo "<input type='hidden' id='reSize' value='1754'>";

$Feldlaenge = "100px";
$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 6;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'bu_text_'.$i, 'bi' => 'bu_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    
    echo "<input type='hidden' id='aOrd_$i' value='Buch/'>";
    $i++;
}

VF_Upload_Form_M();

# =========================================================================================================
Edit_Separator_Zeile('Eigentümer');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'bu_eignr', 10);
Edit_Daten_Feld(Prefix . 'bu_invnr', 10);

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
}
# echo "<p><button type='submit' name='phase' value='99' class=green>Zurück zur Liste</button></p>";
echo "<p><a href='VF_O_BU_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_toggle.js' ></script>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VFH_O_Bu_Edit_ph0.inc.php beendet</pre>";
}
?>