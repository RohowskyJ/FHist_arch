<?php

/**
 * Wichtge Nicht- Mitglieder- Verwaltung, Wartung
 * 
 * @author Josef Rohowsky - neu 2025
 */
session_start();

const Module_Name = 'MVW';
$module = Module_Name;
$tabelle = 'fh_unterst';

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

BA_HTML_header('Unterstützer, Sponsoren- Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$LinkDB_database = '';
$db = LinkDB('VFH');

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['fu_id'])) {
    $fu_id = $_GET['fu_id'];
} else {
    $fu_id = "";
}
if (isset($_POST['fu_id'])) {
    $fu_id = $_POST['fu_id'];
}

if ($phase == 99) {
    header('Location: VF_M_List.php');
}
$Edit_Funcs_FeldName = true; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_unterst');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fu_id == 0) {
        $neu['fu_id'] = $fu_id;
        $neu['fu_aktiv'] = "J";
        $neu['fu_kateg'] = "FF";
        $neu['fu_weihn_post'] = "J";
        $neu['fu_zugr'] = "N";
        $neu['fu_tit_vor'] = "";
        $neu['fu_tit_nach'] = ""; 
        $neu['fu_anrede'] = "Hr.";
        $neu['fu_name'] = $neu['fu_vname'] = "";
        $neu['fu_dgr'] = $neu['fu_plz'] = $neu['fu_ort'] = $neu['fu_adresse'] = "";
        $neu['fu_tel'] = $neu['fu_email'] = $neu['fu_orgname'] = "";
        $neu['fu_uidaend'] = $neu['fu_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM fh_unterst";

        if ($fu_id != '') {
            $sql .= " WHERE fu_id = '$fu_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fu_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Mitglied mit der fu_id Nummer $fu_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);
 
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            var_dump($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
}

switch ($phase) {
    case 0:
        require 'VF_M_Unt_Edit_ph0.inc.php';
        break;
    case 1:
        require "VF_M_Unt_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>