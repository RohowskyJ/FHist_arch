<?php

/**
 * Ausgabe der anzahl der Dokumente je Archivordnungs- Möglichkeit
 * 
 * @author Josef Rohowsky - neu 2024
 * 
 */
session_start();

$module = 'ADM';
$sub_mod = 'all';

if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

VF_chk_valid();

BA_HTML_header("Anzahl der Dokumente je Archivordnungs Gruppe",'','Form','70em');

initial_debug();

$LinkDB_database = '';
$db = LinkDB('Mem');

VF_chk_valid();
VF_set_module_p();

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
        $_SESSION['Eigner']['eig_name'] = "";
        $_SESSION['Eigner']['eig_verant'] = "";
        $_SESSION['Eigner']['eig_staat'] = "";
        $_SESSION['Eigner']['eig_adr'] = "";
        $_SESSION['Eigner']['eig_ort'] = "";
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['ei_id'])) {
    $_SESSION['Eigner']['eig_eigner'] = $ei_id = $_GET['ei_id'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

VF_Displ_Eig($ei_id);

$tabelle_m = $_SESSION[$module]['tabelle_m'] = "ar_chivdt";
$tabelle = $tabelle_m . "_" . $_SESSION['Eigner']['eig_eigner'];
$_SESSION[$module]['tabelle'] = $tabelle;

$sql = "SELECT ad_sg,ad_subsg,ad_lcsg,ad_lcssg,SUM(ad_eignr) AS Anzahl FROM $tabelle GROUP BY ad_sg,ad_subsg,ad_lcsg,ad_lcssg ORDER BY ad_sg,ad_subsg,ad_lcsg,ad_lcssg "; # ,SUM(ad_eignr) AS Anzahl

$return = SQL_QUERY($db, $sql);

$s_cnt = 0;
echo "<table>";
echo "<theader";
echo "<tr></td><td>Sachgeb</td><td>Sub-Sachgebiet</td><td>Untergruppe</td><td>SubGr.</td><td>Archiv- Ordnung; Suchbegriff </td><td>Anzahl Einträge</td></tr>";
echo "</theader>";
echo "<tbody>";
while ($row = mysqli_fetch_object($return)) {

    $cnt = $row->Anzahl/21;
    $s_cnt += $cnt;
    $arl_str = "";
    if ($row->ad_lcsg != "0" || $row->ad_lcssg != "0") {
        $arl_str = VF_Displ_Arl($row->ad_sg,$row->ad_subsg,$row->ad_lcsg,$row->ad_lcssg);
    } elseif ($row->ad_sg != "" ) { ## || $row->ad_subsg != "0"
        $arl_str = VF_Displ_Aro($row->ad_sg,$row->ad_subsg);
    } else {
        $arl_str = "Falsche Archiv- Ordnungs- Bezeichnung";
    }
   
    echo "<tr><td>$row->ad_sg</td><td> $row->ad_subsg</td><td> $row->ad_lcsg</td><td> $row->ad_lcssg</td><td>$arl_str</td><td>$cnt </td></tr>";
      
}
echo "<tr><td></td><td> </td><td> </td><td> </td><td> </td><td>$s_cnt </td></tr>";
echo "</tbody>";
echo "</table>";

BA_HTML_trailer();


?>
