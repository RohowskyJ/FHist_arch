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
Edit_Daten_Feld(Prefix . 'bu_editor', 100);
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
    Edit_Daten_Feld(Prefix . 'bu_frei_id', 100);
    Edit_Daten_Feld('bu_frei_dat');
}
# =========================================================================================================
Edit_Separator_Zeile('Bilder und Bildbeschreibungen');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "../login/AOrd_Verz/Buch/";

echo "<input type='hidden' name='bu_bild1' value='" . $neu['bu_bild1'] . "'>";
echo "<input type='hidden' name='bu_bild2' value='" . $neu['bu_bild2'] . "'>";
echo "<input type='hidden' name='bu_bild3' value='" . $neu['bu_bild3'] . "'>";
echo "<input type='hidden' name='bu_bild4' value='" . $neu['bu_bild4'] . "'>";
echo "<input type='hidden' name='bu_bild5' value='" . $neu['bu_bild5'] . "'>";

$Feldlaenge = "100px";

$pic_arr = array(
    "1" => "||bu_text1|bu_bild1",
    "2" => "||bu_text2|bu_bild2",
    "3" => "||bu_text3|bu_bild3",
    "4" => "||bu_text4|bu_bild4",
    "5" => "||bu_text5|bu_bild5"
);
VF_Multi_Foto($pic_arr);

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

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VFH_O_Bu_Edit_ph0.inc.php beendet</pre>";
}
?>