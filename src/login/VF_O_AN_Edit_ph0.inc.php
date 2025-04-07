
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
Edit_Separator_Zeile('Bilder ');
# =========================================================================================================
$pict_path = $path2ROOT."login/AOrd_Verz/Biete_Suche/";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
Edit_Show_Pict(Prefix . 'bs_bild_1', '150px');
if ($_SESSION[$module]['Act'] == 1) {
    Edit_Upload_File(Prefix . 'bs_bild_1', '1');
}
Edit_Show_Pict(Prefix . 'bs_bild_2', '150px');
if ($_SESSION[$module]['Act'] == 1) {
    Edit_Upload_File(Prefix . 'bs_bild_2', '2');
}
Edit_Show_Pict(Prefix . 'bs_bild_3', '150px');
if ($_SESSION[$module]['Act'] == 1) {
    Edit_Upload_File(Prefix . 'bs_bild_3', '3');
}

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>"; 
}

echo "<p><a href='VF_O_AN_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";

if ($debug) {
    echo "<pre class=debug>VF_O_An_Edit_ph0.php beendet</pre>";
}
?>