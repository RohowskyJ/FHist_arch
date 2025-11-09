<?php

/**
 * Benutzervrwaltung, Warten
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'fh_firmen';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_FI_Edit.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$LinkDB_database  = '';
$db = LinkDB('VFH'); 
// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $fi_id = $_GET['ID'];
} else {
    $fi_id = "0";
}
if (isset($_POST['fi_id'])) {
    $fi_id = $_POST['fi_id'];
}

if ($phase == 99) {
    header('Location: VF_B_List.php');
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
                              # --------------------------------------------------------
                              # Lesen der Daten aus der sql Tabelle
                              # ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fi_id == "0") {

        $neu['fi_id'] = $fi_id;
        $neu['fi_abk'] = $neu['fi_name'] = $neu['fi_ort'] = $neu['fi_vorgaenger'] = "";
        $neu['fi_funkt'] = $neu['fi_inet'] = "";
        $neu['fi_aenddat'] = "";
        $neu['fi_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    } else {
        $sql = "SELECT * FROM $tabelle  ";

        if ($fi_id != '') {
            $sql .= " WHERE fi_id = '$fi_id'";
        }
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z FI Edit $sql </pre>";
        echo "</div>";
        
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fi_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Benutzer mit der fi_id Nummer $fi_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);
     
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
}

BA_HTML_header('Hersteller und Aufbauer von Feuerwehrfahrzeugen', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_FI_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_Z_FI_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>