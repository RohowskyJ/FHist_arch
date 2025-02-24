    <?php

/**
 * Laden der Daten in die Archiv-Tabellen des gewählten Eigentümers mit der gewählten Archivordnung und Suchbegriffen
 * 
 * @author  Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'ARC';
$module = Module_Name;
$tabelle = 'ar_chivdt_';

const Prefix = '';
?>
<!DOCTYPE html>
<?php 
/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$select_String = "";

$prot = True;
$header = "";
BA_HTML_header('Einlesen von Dokumenten in Tabelle', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('Mem'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (!isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if (isset($_GET['ei_id'])) {
    $_SESSION['Eigner']['eig_eigner'] = $eigner = $ei_id = $_GET['ei_id'];
} else {
    $eigner = $_SESSION['Eigner']['eig_eigner'];
}

if (isset($_POST['auto']) ) {
    $ei_arr = explode("-",$_POST['auto']);
    $ei_id = $ei_arr[0];
    VF_Displ_Eig($ei_id);
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

if (!isset($_SESSION[$module]['archord'] )) {
    $_SESSION[$module]['archord'] = array();
}
/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix" ) {
        
    } else {
        $response = $_SESSION[$module]['archord'] = $response;
    }
}


if ($phase == 99) {
    # header('Location: VF_A_Archiv_Verw.php');
}
console_log('L 0107');
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

if ($phase == 0) {
    VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);
    # VF_Displ_Urheb($_SESSION['Eigner']['eig_eigner']);
    $tabelle = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];
   
}

if ($phase == 1) {
    foreach ($_POST as $key => $value) {
        $_SESSION[$module]['Upload'][$key] = $value;
    }

}


echo "<form id='myform' name='myform' method='post'
     action=".$_SERVER['PHP_SELF']."
     enctype='multipart/form-data'>";
  
switch ($phase) {
    case 0:
        require 'VF_A_AR_MassUp2_Arch_Tabs_ph0.inc.php'; // Ziel nach Archiv-Ordnung feststellen
        break; 
    case 1:
        console_log("vor aufr Ph1 ");
        require 'VF_A_AR_MassUp2_Arch_Tabs_ph1.inc.php'; // Pfade und Tabellen feststellen und die Tabeleninhalte erstellen (lassen)
        break;
}

# echo "<script type='text/javascript' src='VF_C_AOrd_Funcs.js'></script>";

echo "</form>";

BA_HTML_trailer();

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
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List, $module;

    # echo "L 0182: tabelle $tabelle<br>";

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            # $ei_eig = $row['fm_eigner'];
            $row['ei_id'] = "<a href='VF_A_AR_MassUp2_Arch_Tabs.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>