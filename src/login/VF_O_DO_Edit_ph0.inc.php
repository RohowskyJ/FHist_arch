<?php

/**
 * Liste der Dokumentationen, Wartun, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_DO_Edit_ph0.inc.php ist gestarted</pre>";
}

# =========================================================================================================
Edit_Tabellen_Header('Dokumentationen des Vereines');
# =========================================================================================================

Edit_Daten_Feld('dk_nr');
echo "<input type='hidden' name='dk_nr' value='" . $neu['dk_nr'] . "' >";
# =========================================================================================================
Edit_Separator_Zeile('Dokument');
# =========================================================================================================
$sel = VF_Doku_Art;

Edit_Select_Feld(Prefix . 'dk_Thema', $sel);

$sel = VF_Doku_SG;

Edit_Select_Feld(Prefix . 'dk_sg', $sel);

Edit_Daten_Feld(Prefix . 'dk_Titel', 256);
Edit_Daten_Feld(Prefix . 'dk_Author', 256);
Edit_Daten_Feld(Prefix . 'dk_Urspr', 100);

echo "<input type='hidden' name='dk_Dsn' value='" . $neu['dk_Dsn'] . "' >";
echo "<input type='hidden' name='dk_Dsn_2' value='" . $neu['dk_Dsn_2'] . "' >";

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/Downloads/";
if ($neu['dk_Path2Dsn'] != "") {
    $pict_path .= $neu['dk_Path2Dsn']."";
}

$Feldlaenge = "100px";

$pic_arr = array(
    "1" => "|||dk_Dsn",
    "2" => "|||dk_Dsn_2"
);
VF_Multi_Foto($pic_arr);

Edit_Daten_Feld(Prefix . 'dk_Path2Dsn', 70);

Edit_Daten_Feld(Prefix . 'dk_url', 100);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'dk_aenduid');
Edit_Daten_Feld(Prefix . 'dk_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_O_DO_List.php'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VFH_O_DO_Edit_ph0.inc.php beendet</pre>";
}
?>