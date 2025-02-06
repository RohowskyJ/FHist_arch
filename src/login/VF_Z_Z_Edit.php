<?php

/**
 * Wartung der Zugriffsberechtgungen der Benutzer
 * 
 * @author Josef Rohowsky -  neu 2018
 * 
 * 
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
$tabelle = 'fh_zugriffe_n';

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
$LinkDB_database = "";
$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['be_id'])) {
    $zu_id = $_GET['be_id'];
}
if (isset($_POST['be_id'])) {
    $zu_id = $_POST['be_id'];
}
if (isset($_POST['zu_id'])) {
    $zu_id = $_POST['zu_id'];
}

if ($phase == 99) {
    header('Location: VF_Z_B_List.php');
}
$Edit_Funcs_FeldName = true; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_zugriffe_n');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($zu_id == "0") {

        $neu['zu_id'] = $zu_id;
        $neu['zu_pw_enc'] = $neu['zu_ref_leiter'];
        $neu['zu_eignr_1'] = $neu['zu_eignr_2'] = $neu['zu_eignr_3'] = $neu['zu_eignr_4'] = $neu['zu_eignr_5'] = "0"; # $neu['zu_uid'] =;
        $neu['zu_F_G'] = $neu['zu_F_M'] = $neu['zu_S_G'] = $neu['zu_PSA'] = $neu['zu_ARC'] = "A";
        $neu['zu_INV'] = $neu['zu_OEF'] = $neu['zu_MVW'] = "A";
        $neu['zu_ADM'] = $neu['zu_SUC'] = "N";
        $neu['zu_valid_until'] = $neu['zu_uidaend'] = $neu['zu_aenddat'] = "";
        $neu['passwd'] = $neu['passwd_K'] = "banane1a";
    } else {
        $sql = "SELECT * FROM $tabelle 
             INNER JOIN fh_benutzer 
                ON $tabelle.zu_id = fh_benutzer.be_id 
            ";

        if ($zu_id != '') {
            $sql .= " WHERE zu_id = '$zu_id' ";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($zu_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Berechtigung  der zu_id Nummer $zu_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        $neu['passwd'] = $neu['passwd_K'] = "";

        if ($neu['zu_F_G'] == "") {
            if ($neu['zu_ref_1'] != "") {
                $neu['zu_F_G'] = $neu['zu_ref_1'];
            }
            if ($neu['zu_ref_2'] != "") {
                $neu['zu_F_M'] = $neu['zu_ref_2'];
            }
            if ($neu['zu_ref_3'] != "") {
                $neu['zu_S_G'] = $neu['zu_ref_3'];
            }
            if ($neu['zu_ref_4'] != "") {
                $neu['zu_PSA'] = $neu['zu_ref_4'];
            }
            if ($neu['zu_ref_5'] != "") {
                $neu['zu_ARC'] = $neu['zu_ref_5'];
            }
            if ($neu['zu_ref_6'] != "") {
                $neu['zu_INV'] = $neu['zu_ref_6'];
            }
            if ($neu['zu_ref_7'] != "") {
                $neu['zu_OEF'] = $neu['zu_ref_7'];
            }
            if ($neu['zu_ref_1'] != "V") {
                $neu['zu_ADM'] = "V";
            }
            if ($neu['zu_ADM'] != "") {
                $neu['zu_MVW'] = $neu['zu_ADM'];
            }
        }

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

BA_HTML_header('Berechtigungen', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_Z_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_Z_Z_Edit_ph1.inc.php";
}
BA_HTML_trailer();
?>