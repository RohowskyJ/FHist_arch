<?php

/**
 * Formular für Wappen und Auszeichnungen
 * 
 * @author Josef Rohowsky - neu 2019
 */

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_M_Edit_ph0.inc ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fw_id' value='$fw_id'/>";
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('fw_id');

# =========================================================================================================
Edit_Separator_Zeile('Orts- Daten');
# =========================================================================================================
$ST_Opt = VF_Sel_Staat('fw_st_abk', '7');
Edit_Select_Feld(Prefix . 'fw_st_abk', $ST_Opt);

$st_abk = ""; // $neu['fw_st_abk'];
$BD_Opt = VF_Sel_Bdld($st_abk, '8');
Edit_Select_Feld(Prefix . 'fw_bd_abk', $BD_Opt);

Edit_Daten_Feld(Prefix . 'fw_bz_abk', 4);
Edit_Daten_Feld(Prefix . 'fw_bz_name', 50);

Edit_Daten_Feld(Prefix . 'fw_ab_nr', 4);
Edit_Daten_Feld(Prefix . 'fw_ab_name', 50);

Edit_Daten_Feld(Prefix . 'fw_gd_nr', 4);
Edit_Daten_Feld(Prefix . 'fw_gd_name', 50);

$gd_art = array(
    '  ' => 'keine Definition',
    'Ss' => 'Statutarstadt',
    'St' => 'Stadtgemeinde',
    'Ma' => 'Marktgemeinde',
    'Ge' => 'Gemeinde',
    'Zt' => 'Gemeinde- Teil',
    'Or' => 'Organisation'
);
Edit_Select_Feld(Prefix . 'fw_gd_art', $gd_art, '');

Edit_textarea_Feld(Prefix . 'fw_ort_komm');

# =========================================================================================================
Edit_Separator_Zeile('Feuerwehr ( - Ortsteil)');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fw_fw_nr', 4);
Edit_Daten_Feld(Prefix . 'fw_fw_name', 50);

Edit_Daten_Feld(Prefix . 'fw_fw_typ', 5);

Edit_Daten_Feld(Prefix . 'fw_grdg_dat', 15);

Edit_Daten_Feld(Prefix . 'fw_end_dat', 15);

Edit_textarea_Feld(Prefix . 'fw_kommentar');

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fw_uid_aend');
Edit_Daten_Feld(Prefix . 'fw_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_PS_OV_M_List.php'>Zurück zur Liste</a></p>";

if (isset($_SESSION[$module]['proj'])) {
    if ($_SESSION[$module]['proj'] == 'AERM') {
        $g = $f = $a = 0;
        /*
        if ($_SESSION[$module]['all_upd']) {
           # $for_update = 1;
        } else {
           # $for_update = 0;
        }
*/
        echo "<div class='w3-container'><fieldset> <label> Gemeinde-Wappen: </label><br/>";
        require 'VF_PS_OV_OW_List.inc';
        # echo "</fieldset></div>";

        echo "<div class='w3-container'><fieldset> <label> Wappen bei der Feuerwehr: </label><br/>";
        require 'VF_PS_OV_FW_List.inc';

        echo "</fieldset></div>";

        echo "<div class='w3-container'><fieldset> <label> Ärmel- und T-Shirt- Abzeichen:</label><br/>";
        require ('VF_PS_OV_AB_List.inc');
        echo "</fieldset></div>";
    } elseif ($_SESSION[$module]['proj'] == 'AUSZ') {
        // Beschreibung der Auszeichhung/des Abzeichens
        echo "<div class='w3-container'><fieldset> <label> Auszeichnungs- Beschreibung: </label><br/>";
        require 'VF_PS_OV_AD_List.inc';
        echo "</fieldset></div>";
    }
} else {
    echo 'Kein Projekt definiert <br/>';
}

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array    $row
 * @param string   $tabelle
 * @return boolean immer true
 *
 * @global string $path2VF   String zur root-Angleichung für relative Adressierung
 * @global string $T_List    Auswahl der Listen- Art
 * @global string $module    Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2VF, $T_List, $module;
    # echo "L 86: \$tabelle $tabelle <br/>";
    # echo "L136 Sess(mod(proj ".$_SESSION[$module]['proj']." <br/>";
    if ($_SESSION[$module]['proj'] == "AUSZ") {
        if ($tabelle == "fh_ort_wappen") {
            $fo_id = $row['fo_id'];
            $row['fo_id'] = "<a href='VF_PS_OV_M_Edit.php?ID=$fo_id' >" . $fo_id . "</a>";
        } elseif ($tabelle == "az_beschreibg") {
            $proj = $_SESSION[$module]['proj'];
            
            #$pict_path = "referat4/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";
            $pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";
            
            $ab_id = $row['ab_id'];
            $row['ab_id'] = "<a href='VF_PS_OV_AD_Edit.php?ID=$ab_id' >" . $ab_id . "</a>";
            $ab_statut = $row['ab_statut'];
            $row['ab_statut'] = "<a href='$pict_path$ab_statut' target='Statut' >" . $ab_statut . "</a>";
            $ab_erklaerung = $row['ab_erklaerung'];
            $row['ab_erklaerung'] = "<a href='$pict_path$ab_erklaerung' target='Erklaerung' >" . $ab_erklaerung . "</a>";
        }
    } elseif ($_SESSION[$module]['proj'] == "AERM") {
        if ($tabelle == "fh_ort_wappen") {
            # echo "L 92: fh_ort_wappen \$tabelle $tabelle <br/>";
            $fo_id = $row['fo_id'];
            $row['fo_id'] = "<a href='VF_PS_OV_OW_Edit.php?ID=$fo_id' >" . $fo_id . "</a>";

            # $pict_path = "referat4/AERM/Wappen_Ort/";
            $pict_path = "Beschreibungen/PSA/AERM/Wappen_Ort/";

            if ($row['fo_gde_wappen'] != "") {

                $fo_gde_wappen = $row['fo_gde_wappen'];
                $p1 = $pict_path . $row['fo_gde_wappen'];
                $row['fo_gde_wappen'] = "<a href='$p1' target='Ortswappen' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
            }
        } elseif ($tabelle == "fh_ff_wappen") {
            # echo "L 107: fh_ff_wappen \$tabelle $tabelle <br/>";
            $fo_id = $row['fo_id'];
            $row['fo_id'] = "<a href='VF_PS_OV_FW_Edit.php?ID=$fo_id' >" . $fo_id . "</a>";

            # $pict_path = "referat4/AERM/Wappen_FW/";
            $pict_path = "Beschreibungen/PSA/AERM/Wappen_FW/";

            if ($row['fo_ff_wappen'] != "") {

                $fo_ff_wappen = $row['fo_ff_wappen'];
                $p1 = $pict_path . $row['fo_ff_wappen'];
                $row['fo_ff_wappen'] = "<a href='$p1' target='Wappen Feuerwehr' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
            }
        } elseif ($tabelle == "fh_ff_abz") {
            # echo "L 121: fh_ff_abz \$tabelle $tabelle <br/>";
            $fo_id = $row['fo_id'];
            $row['fo_id'] = "<a href='VF_PS_OV_AB_Edit.php?ID=$fo_id' >" . $fo_id . "</a>";

            # $pict_path = "referat4/AERM/Aermel_Abz/";
            $pict_path = "Beschreibungen/PSA/AERM/Aermel_Abz/";

            if ($row['fo_ff_abzeich'] != "") {

                $fo_ff_abzeich = $row['fo_ff_abzeich'];
                $p1 = $pict_path . $row['fo_ff_abzeich'];
                $row['fo_ff_abzeich'] = "<a href='$p1' target='Ärmelabzeichen' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
            }

            $a_typ = $row['fo_ff_a_typ_a'];

            $row['fo_ff_a_typ_a'] = VF_Aermelabz_text[$a_typ];
        } elseif ($tabelle == "-fh_ff_abz") {}
        return True;
    }
} # Ende von Function modifyRow

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_M_Edit_ph0.inc beendet</pre>";
}
?>