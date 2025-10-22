<?php

/**
 * Abkürzungen der Feuerwehr
 * 
 * @author Josef Rohowsky - neu 2025
 * 
 * 
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'fh_abk';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

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
    $ab_id = $_GET['ID'];
} else {
    $ab_id = "0";
}
if (isset($_POST['ab_id'])) {
    $ab_id = $_POST['ab_id'];
}

if ($phase == 99) {
    header('Location: VF_Z_AB_List.php');
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
    if ($ab_id == "0") {

        $neu['ab_id'] = $ab_id;
        $neu['ab_grp'] = "";
        $neu['ab_abk'] = $neu['ab_bezeichn'] = $neu['ab_gruppe'] = "";
        $neu['ab_aenddat'] = "";
        $neu['ab_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    } else {
        $sql = "SELECT * FROM $tabelle  ";

        if ($ab_id != '') {
            $sql .= " WHERE ab_id = '$ab_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($ab_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Benutzer mit der ab_id Nummer $ab_id gefunden</p>";
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
        $neu[$name] = $value;
    }
}

BA_HTML_header('Abkürzungen', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_AB_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_Z_AB_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>