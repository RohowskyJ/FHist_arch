<?php

/**
 * Liste der Eigentümer, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
$tabelle = 'fh_eigentuemer';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True;
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
if (isset($_GET['ID'])) {
    $now_ei_id = $_GET['ID'];
} else {
    $now_ei_id = "";
}
if (isset($_POST['ei_id'])) {
    $now_ei_id = $_POST['ei_id'];
}

if ($phase == 99) {
    header('Location: VF_Z_E_List.php');
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_eigentuemer');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($now_ei_id == "0") {

        $neu['ei_id'] = $now_ei_id;
        $neu['ei_org_typ'] = "Privat";
        $neu['ei_mitglnr'] = $neu['ei_staat'] = $neu['ei_bdld'] = $neu['ei_bezirk'] =$neu['ei_org_name'] = "";
        $neu['kont_name'] = $neu['ei_fwkz'] = "";
        $neu['ei_grdgj'] = $neu['ei_titel'] = $neu['ei_vname'] = $neu['ei_name'] = $neu['ei_dgr'] = $neu['ei_adresse'] = "";
        $neu['ei_plz'] = $neu['ei_ort'] = $neu['ei_tel'] = $neu['ei_fax'] = $neu['ei_handy'] = $neu['ei_email'] = "";
        $neu['ei_internet'] = $neu['ei_sterbdat'] = $neu['ei_abgdat'] = $neu['ei_neueigner'] = $neu['ei_wlpriv'] = $neu['ei_vopriv'] = "";
        $neu['ei_wlmus'] = $neu['ei_vomus'] = $neu['ei_wlinv'] = $neu['ei_voinv'] = $neu['ei_voinf'] = $neu['ei_vofo'] = "";
        $neu['ei_voar'] = $neu['ei_drwvs'] = $neu['ei_drneu'] = $neu['ei_uidaend'] = $neu['ei_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM fh_eigentuemer ";

        if ($now_ei_id != '') {
            $sql .= " WHERE ei_id = '$now_ei_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $now_ei_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($now_ei_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Bericht mit der ei_id Nummer $now_ei_id gefunden</p>";
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

BA_HTML_header('Eigentümer- Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_Z_E_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_Z_E_Edit_ph1.inc.php";
        header("Location: VF_Z_E_List.php");
        break;
}
BA_HTML_trailer();?>