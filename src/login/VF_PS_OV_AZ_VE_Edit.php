<?php

/**
 * Auszeichnungs- Veraltung Vereins- Auszeichnungen
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'az_ausz_ve';

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
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

if (isset($_GET['ID'])) {
    $fw_id = $_GET['ID'];
}

$proj = $_SESSION[$module]['proj'];

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $av_id = $_GET['ID'];
} else {
    $av_id = $_SESSION[$proj]['av_id'];
}

if (isset($_POST['av_ad_id'])) {
    $av_ad_id = $_POST['av_ad_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_O_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}

$proj = $_SESSION[$module]['proj'];
$_SESSION[$proj]['av_id'] = $av_id;
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
                              # --------------------------------------------------------
                              # Lesen der Daten aus der sql Tabelle
                              # ------------------------------------------------------------------------------------------------------------
$fw_id = $_SESSION[$module]['fw_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle, 'ffhist');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($av_id == 0) {

        $neu['av_id'] = $av_id;
        $neu['av_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['av_ab_id'] = $_SESSION['AUSZ']['ab_id'];
        $neu['av_beschr'] = $neu['av_mat'] = "";
        $neu['av_bild_v'] = $neu['av_bild_r'] = $neu['av_beschr_v'] = $neu['av_beschr_r'] = "";

        $neu['av_urkund_1'] = $neu['av_urkund_2'] = "";
        $neu['av_aend_uid'] = $neu['av_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($av_id != '') {
            $sql .= " WHERE av_id = '$av_id'";
        }

        $result = SQL_QUERY($db, $sql) or die('Lesen Satz $av_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fw_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der av_id Nummer $av_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {
    
}

BA_HTML_header('Vereins- und Zivil-  Auszeichnungs - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AZ_VE_Edit_ph0.inc.php';
        break;
    case 1:
        require 'VF_PS_OV_AZ_VE_Edit_ph1.inc.php';
        break;
}
BA_HTML_trailer();
?>