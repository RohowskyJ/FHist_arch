<?php

/**
 * Benutzervrwaltung, Warten
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

$module = 'ADM';
$sub_mod = 'alle';

$tabelle = 'fh_benutzer';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_B_Edit.php";

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
    $be_id = $_GET['ID'];
} else {
    $be_id = "0";
}
if (isset($_POST['be_id'])) {
    $be_id = $_POST['be_id'];
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
    if ($be_id == "0") {

        $neu['be_id'] = $be_id;
        $neu['be_org_typ'] = "Privat";
        $neu['be_org_name'] = $neu['be_mitglnr'] = $neu['be_anrede'] = $neu['be_titel'] = $neu['be_n_titel'] = "";
        $neu['be_vname'] = $neu['be_name'] = $neu['be_adresse'] = $neu['be_staat'] = $neu['be_plz'] = $neu['be_ort'] = "";
        $neu['be_telefon'] = $neu['be_handy'] = $neu['be_fax'] = $neu['be_email'] = $neu['eig_id'] = "";
        $neu['be_aenddat'] = "";
        $neu['be_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    } else {
        $sql = "SELECT * FROM $tabelle  ";

        if ($be_id != '') {
            $sql .= " WHERE be_id = '$be_id'";
        }
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z B List vor list_create $sql </pre>";
        echo "</div>";
        
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($be_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Benutzer mit der be_id Nummer $be_id gefunden</p>";
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

BA_HTML_header('Benutzer- Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_B_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_Z_B_Edit_ph1.inc.php";
        header("Location: VF_Z_B_List.php");
        break;
}
BA_HTML_trailer();
?>