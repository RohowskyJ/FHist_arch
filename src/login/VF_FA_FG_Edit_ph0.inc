<?php

/**
 * Fahrzeug- Liste, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
echo "<input type='hidden' name='fz_id' value='$fz_id'/>";
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('fz_id');
Edit_Daten_Feld('fz_eignr');

# =========================================================================================================
Edit_Separator_Zeile('Fahrzeug/Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fz_name', 30, 'Rufname');
Edit_Daten_Feld('fz_taktbez', 100);
Edit_Daten_Feld('fz_indienstst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fz_ausdienst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Ausserdienst- Stellung');

$opt_aera = VF_FZG_Aera; 
Edit_Select_Feld(Prefix . 'fz_zeitraum', $opt_aera, '');

Edit_textarea_Feld(Prefix . 'fz_komment');

Edit_textarea_Feld(Prefix . 'fz_beschreibg_det');

Edit_Select_Feld(Prefix . 'fz_zustand', VF_Zustand, '');

Edit_Daten_Feld(Prefix . 'fz_herstell_fg', 100);
Edit_Daten_Feld(Prefix . 'fz_baujahr', 4);

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";

$Feldlaenge = "100px";

echo "<input type='hidden' name='fz_bild_1' value='" . $neu['fz_bild_1'] . "'>";
echo "<input type='hidden' name='fz_bild_2' value='" . $neu['fz_bild_2'] . "'>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";
# $pict_path = "referat" . $_SESSION[$module]['referat'] . "/" . $_SESSION['Eigner']['eig_eigner'] . "/";

$Feldlaenge = "150px";

$pic_arr = array(
    "01" => "||fz_b_1_komm|fz_bild_1",
    "02" => "||fz_b_2_komm|fz_bild_2"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('CTIF Zertifizierung');
# =========================================================================================================
Edit_Daten_Feld('fz_ctifklass', 5);
Edit_Daten_Feld('fz_ctifdate', 10);
Edit_Daten_Feld('ct_darstjahr', 4);
Edit_Daten_Feld('ct_juroren', 100);

Edit_Daten_Feld('fz_pruefg_id', 10);
Edit_Daten_Feld('fz_pruefg', 10);
# =========================================================================================================
Edit_Separator_Zeile('Datenfreigabe');
# =========================================================================================================

Edit_Select_Feld(Prefix . 'fz_eigent_freig', VF_JN, '');
Edit_Select_Feld(Prefix . 'fz_verfueg_freig', VF_JN, '');

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================
# Edit_Daten_Feld('fz_invnr',50);

echo "<tr><td><div class='w3-container w3-aqua'>  <div class='label'>Sammlung - Sammlungsbezeichnung</div></div></td><td>";
echo "<div class='w3-container w3-aqua'>";

echo '';

Edit_Select_Feld('fz_sammlg', VF_Sammlung_B0, '');
echo "</div>";

echo "</td></tr>";

# =========================================================================================================
Edit_Separator_Zeile('Suchbegriff, Standardisiert');
# =========================================================================================================

$l1sb = $neu['fz_suchbegr1'];
$l2sb = $neu['fz_suchbegr2'];
$l3sb = $neu['fz_suchbegr3'];
$l4sb = $neu['fz_suchbegr4'];
$l5sb = $neu['fz_suchbegr5'];
$l6sb = $neu['fz_suchbegr6'];

$sb_s1 = $sb_s2 = $sb_s3 = $sb_s4 = $sb_s5 = $sb_s6 = "";

VF_Suchbegr_Ausw_js($sb_s1, $sb_s2, $sb_s3, $sb_s4, $sb_s5, $sb_s6);

VF_Suchbegr_Edit();

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fz_aenduid');
Edit_Daten_Feld('fz_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FA_FG_List.php'>Zurück zur Liste</a></p>";

if ($neu['fz_id'] != 0) {

    switch ($neu['fz_sammlg']) {
        case "B0":
        case "BA":
        case "BH":
            require 'VF_FA_BA_Lists.inc';
            break;
    }
} else {}
$Tab_Nam = array();
# echo "<div><fieldset>";
echo "<p><a href='VF_FA_FG_List.php'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_FG_Edit_ph0.inc beendet</pre>";
}
?>