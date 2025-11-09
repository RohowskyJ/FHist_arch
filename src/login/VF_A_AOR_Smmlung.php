<?php

/**
 * Archivordnung, Test für Sammlungs- Auswahl
 *
 * @author Josef Rohowsky -  - neu 2024 
 *
 * Hinzufügen zweier zusätzlicher Ebenen zur Archivordnung vom ÖBFV
 */
session_start();

const Module_Name = 'ARC';
$module = Module_Name;
$tabelle = 'ar_ord_local';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/Funcs.inc.php';
require $path2ROOT . 'login/common/Edit_Funcs.inc.php';
require $path2ROOT . 'login/common/List_Funcs.inc.php';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.inc.php';
require $path2ROOT . 'login/common/VF_Const.inc.php'; 
require $path2ROOT . 'login/common/Ajax_Funcs.inc';

$flow_list = False;

$header = "<script type='text/javascript' src='common/javascript/prototype.js' async></script>";
HTML_header('Test Sammlungs-Auswahl', 'Änderungsdienst: <br/>', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width


initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================



$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$eignr = ''; # $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "$eignr";

Tabellen_Spalten_parms($db, $tabelle_a);

$sql_be = "SELECT * FROM $tabelle_a WHERE `al_sg` = '07.01' ORDER BY `al_sg`,'al_lcsg','al_lcssg','al_lcssg_s0','al_lcssg_s1' ASC";
$return_be = SQL_QUERY($db, $sql_be); ;

$errno = mysqli_errno($db);

$l1_arr_A = $l2_arr_A = $l3_arr_A = array();
$l1_arr_B = $l2_arr_B = $l3_arr_B = array();

while($row = mysqli_fetch_object($return_be)) {
    #print_r($row);echo "<br> L 077 row <br>";
    #echo $row->al_bezeich."<br>";
    if (substr($row->al_bezeich,0,2) ==  '- ' ){
        if ($row->al_lcsg == '01') {
            if ($row->al_lcssg == "00") {
                $l1_arr_A[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            } elseif ($row->al_lcssg_s0 == "00") {
                $l2_arr_A[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            } elseif ($row->al_lcssg_s1 == "00") {
                $l3_arr_A[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            }
            
        }
    }

    if ($row->al_lcsg == '02') {
        if (substr($row->al_bezeich,0,2) ==  '- ' ){
            if ($row->al_lcssg == "00") {
                $l1_arr_B[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            } elseif ($row->al_lcssg_s0 == "00") {
                $l2_arr_B[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            } elseif ($row->al_lcssg_s1 == "00") {
                $l3_arr_B[$row->al_id] = $row->al_sg."|".$row->al_lcsg."|".$row->al_lcssg."|".$row->al_lcssg_s0."|".$row->al_lcssg_s1."|".$row->al_bezeich."|".$row->al_sammlung;
            }
        }
    }
}

var_dump($l1_arr_A);
var_dump($l2_arr_A);
var_dump($l3_arr_A);

var_dump($l1_arr_B);
var_dump($l2_arr_B);
var_dump($l3_arr_B);

$eigner = $_SESSION['Eigner']['eig_eigner'] = '21';
$al_sg = '07.01';
$al_lcsg = $al_lcssg = $al_lcssg_s0 = $al_lcssg_s1 = "";
$sa_s1 = $sa_s2 = $sa_s3 = $sa_s4 = $sa_s5 = "";

#VF_Sammlung_Ausw_js($sa_s1,$sa_s2,$sa_s3,$sa_s4,$sa_s5);

#VF_Sammlung_Edit();

echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";


echo "<script type='text/javascript' src='VF_C_Sammlung_Funcs.js'></script>";
HTML_trailer();

?>