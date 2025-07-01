<?php

/**
 * Katalog der Automobile im Feuerwehrdienst
 * 
 * @author Josef Rohowsky - neu 2019 
 * 
 * change Avtivity:
 *   2019       J. Rohowsky nach B.R.Gaicki  - neu
 * 
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_katalog';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";
$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require ('common/VF_Comm_Funcs.inc.php');
require ('common/const.inc.php');

require $path2ROOT . 'login/common/Funcs.inc.php'; 

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

$lowHeight = True;
/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

$Kat_Arr = array();
if (isset($_POST['aera'])) {
    $sel_aera = $_POST['aera'];
} else {
    $sel_aera = "";
}

$_SESSION[$module]['sel_aera'] = $sel_aera;

$ar_arr = $fo_arr = $fz_arr = $fm_arr = $ge_arr = $in_arr = $zt_arr = array();
$maf_arr = $mag_arr = array();
$tables_act = VF_tableExist(); # verfügbare Mandanten- Tabellen
if (! $tables_act) {
    echo "keine Tabellen gefunden - ABBRUCH <br>";
    exit();
}

#
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Automobil Katalog";

$logo = 'JA';
HTML_header('Automobil- Katalog', 'Historische Feuerwehrautos', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# ===========================================================================================================
# Die Aera auswählen
# ===========================================================================================================
$Opt_Aera = VF_FZG_Aera; # VF_Sel_Aera("", "9");
$zus_ausw = "<select name='aera' >";
foreach ($Opt_Aera as $key => $value) {
    $zus_ausw .= "<option value='$key'> $value</option>";
}
$zus_ausw .= "</select>";

echo "<fieldset>";
echo "<div class='resize' style='height:1.5cm; >";
echo "<table class='w3-table ' style='border:1px solid black;background-color:lightgrey;margin:0px;'>";

echo "<tr><th width='30%'>";

echo "Auswahl der Fahrzeuggruppe: </th>";
echo "<th> $zus_ausw </th></tr>";
echo "</table><br/>";
echo "</div>";

echo "";
echo "";

echo "<p><button type='submit' name='phase' value=1 class=green>Durchführen</button>";
echo "<button type='submit' name='phase' value=99 class=blue>Beenden</button></p>";

echo "</fieldset>";

echo "<fieldset>";

$sql = "SELECT * FROM $tabelle ";
$orderBy = " ORDER BY fk_aera,fk_indienst ASC";

$sql_where = "";

switch ($_SESSION[$module]['sel_aera']) {
    case "999":
        $sql_where = "  ";
        break;

    default:
        $sql_where = " WHERE fk_aera = '" . $_SESSION[$module]['sel_aera'] . "' ";
}

$sql .= $sql_where . $orderBy;

$return = mysqli_query($db, $sql) or die(mysqli_error($db) . " <br/>");

# $Kat_Arr = array();

WHILE ($row = mysqli_fetch_assoc($return)) {

    $ei = $row['fk_eigner'];
    $fz = $row['fk_fzgid'];

    $Kat_Arr[$ei] = $fz;
}

# für die dynam Höhe der Tabelle
$zeilen = 20;
if ($lowHeight) {
    if ($zeilen >= 15) {
        $T_Style = "style='height:15cm;'";
    } elseif ($zeilen >= 8) {
        $T_Style = "style='height:10cm;";
    } else {
        $T_Style = "style='height:4cm;";
    }
} else {
    $T_Style = "style='height:15cm;'";
}
# echo "</div>";
echo "<div class='resize' $T_Style  >";
echo "<table class='w3-table w3-striped w3-hoverable scroll' style='border:1px solid black;background-color:white;margin:0px;'>";
echo "<tr><th colspan='4'>Fahrzeugbeschreibungen</th></tr>    ";
echo "<tr><th>Eigent.<hr/>Fzg.Nr.</th><th>Fahrzeug<br/>Indienstst.<br/>Zustand<br/>Hersteller</th><th>Beschreibung</th><th>Foto</th></tr> ";

foreach ($Kat_Arr as $eignr => $fzgid) {
    $table_fzg = "ma_fz_beschr_$eignr";

    if (array_key_exists($table_fzg,$maf_arr)) {
    } else {
        continue;
    }

    $sql = "SELECT * FROM $table_fzg WHERE fz_id = '$fzgid' ";

    $return = mysqli_query($db, $sql);
    if (! $return) {
        continue;
    }
    WHILE ($row = mysqli_fetch_assoc($return)) {
        $pk = "$fzgid|$eignr";
        $link = "<a href='VF_FA_BA_Show.php?ID=$pk' > $fzgid</a>"; //
        $pict_path = "AOrd_Verz/$eignr/MaF/";
       
        $bild = "";
        if ($row['fz_bild_1'] != "") {
            $fz_bild_1 = $row['fz_bild_1'];
            $p1 = $pict_path . $row['fz_bild_1'];

            # $row['fz_bild_1'] = "<img src='$p1' alter='$p1' width='70px'> $fz_bild_1";
            $bild = "<a href='$p1' target='Fahrzeug-Bild' > <img src='$p1' alter='$p1' width='150px'> <br/>$fz_bild_1   </a>";
        }
        $zust = "";
        $zustand = $row['fz_zustand'];

        $zust = VF_Zustand[$zustand];

        echo "<tr><td>" . $row['fz_eignr'] . "<hr/>" . $link . "</td>";
        echo "<td>" . $row['fz_name'] . "<br/>" . $row['fz_indienstst'] . "<br/>" . $zust . "<br/>" . $row['fz_herstell_fg'] . "</td>";
        echo "<td>" . $row['fz_komment'] . "</td>";
        echo "<td>" . $bild . "</td></tr>";
    }
}

echo "</table>";
echo "";

echo "</form></fieldset>";

HTML_trailer();

?>
