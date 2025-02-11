<?php

/**
 * Auszeichnungs- Verwaltung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input name='ad_id' type='hidden' value='" . $neu['ad_id'] . "' > ";
echo "<input name='ad_fw_id' type='hidden' value='" . $neu['ad_fw_id'] . "' > ";
echo "<input name='ad_ab_id' type='hidden' value='" . $neu['ad_ab_id'] . "' > ";

# =========================================================================================================
Edit_Tabellen_Header('Auszeichnung Änderung, Neueingabe');
# =========================================================================================================

Edit_Daten_Feld('ad_id');
Edit_Daten_Feld('ad_fw_id');
Edit_Daten_Feld('ad_ab_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnungen- Abzeichen');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'ad_name');
Edit_textarea_Feld(Prefix . 'ad_detail');

echo "<input type='hidden' name='ad_extern' value='" . $neu['ad_extern'] . "'>";
$pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";

$Feldlaenge = "100px";
Edit_Show_Pict(Prefix . 'ad_extern', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
   # if (empty($neu['fo_gde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}

Edit_Upload_File('ad_extern', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '1' # Identifier bei mehreren uploads
);

Edit_Daten_Feld(Prefix . 'ad_bez', 50);

Edit_Daten_Feld(Prefix . 'ad_abmesg', 30);
Edit_textarea_Feld(Prefix . 'ad_band');
Edit_textarea_Feld(Prefix . 'ad_vorderseite');
Edit_textarea_Feld(Prefix . 'ad_rueckseite');
# Edit_Daten_Feld(Prefix.'ad_name',50);

# =========================================================================================================
Edit_Separator_Zeile('Wenn Mehrere Zeitlche Versionen');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'ad_stiftg_date', '', '', "type='date'");
echo "<input type='hidden' name='ad_statut' value='" . $neu['ad_statut'] . "'>";
echo "<input type='hidden' name='ad_erklaer' value='" . $neu['ad_erklaer'] . "'>";
$pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";
$Feldlaenge = "100px";
Edit_Show_Pict(Prefix . 'ad_statut', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
   # if (empty($neu['fo_gde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}

Edit_Upload_File('ad_statut', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '2' # Identifier bei mehreren uploads
);
Edit_Show_Pict(Prefix . 'ad_erklaer', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
Edit_Upload_File('ad_erklaer', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '3' # Identifier bei mehreren uploads
);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'ad_aend_uid');
Edit_Daten_Feld(Prefix . 'ad_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

# print_r($_SESSION[$module]);
if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
# echo "<p><button type='submit' name='phase' value='99' class=green>Zurück zur Liste</button></p>";
echo "<p><a href='VF_PS_OV_AD_Edit.php?ID=" . $neu['ad_ab_id'] . "'>Zurück zur Liste</a></p>";

echo "<div class='w3-container'><fieldset> <label> Auszeichnungen: </label><br/>";
require 'VF_PS_OV_AZ_List.inc';
echo "</fieldset></div>";

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *
 * @global string $path2VF String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2VF, $T_List, $module, $pict_path, $proj;
    # echo "L 86: \$tabelle $tabelle <br/>";

    if ($tabelle == "az_auszeich") {
        $pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
        $az_id = $row['az_id'];
        $row['az_id'] = "<a href='VF_PS_OV_AZ_Edit.php?ID=$az_id' >" . $az_id . "</a>";
        if ($row['az_bild_v'] != "") {
            $az_bild_v = $row['az_bild_v'];
            $p1 = $pict_path . $row['az_bild_v'];
            $row['az_bild_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['az_bild_r'] != "") {
            $az_bild_r = $row['az_bild_r'];
            $p1 = $pict_path . $row['az_bild_r'];
            $row['az_bild_r'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['az_bild_m'] != "") {
            $az_bild_m = $row['az_bild_m'];
            $p1 = $pict_path . $row['az_bild_m'];
            $row['az_bild_m'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['az_bild_m_r'] != "") {
            $az_bild_m_r = $row['az_bild_m_r'];
            $p1 = $pict_path . $row['az_bild_m_r'];
            $row['az_bild_m_r'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['az_bild_klsp'] != "") {
            $az_bild_klsp = $row['az_bild_klsp'];
            $p1 = $pict_path . $row['az_bild_klsp'];
            $row['az_bild_klsp'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
    }

    if ($tabelle == "az_adetail") {
        $stat_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/stat/";
        if ($row['ad_statut'] != "") {
            $ad_statut = $row['ad_statut'];
            $row['a_statut'] = "<a href='$stat_path.$ad_statut' target='_new' >$ad_statut</a>";
        }
        if ($row['ad_erklaer'] != "") {}
    }

    return True;
} # Ende von Function modifyRow

if ($debug) { 
    echo "<pre class=debug>VF_PS_OV_AE_Edit_ph0.inc.php beendet</pre>";
}

?>