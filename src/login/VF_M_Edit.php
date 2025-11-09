<?php

/**
 * Mitgliederverwaltung, Wartung
 * 
 * @author Josef Rohowsky - neu 2020
 */
session_start();

$module = 'MVW';
$sub_mod = 'all';

$tabelle = 'fh_mitglieder';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_M_Edit.php"; 


$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$form_start = True;
BA_HTML_header('Mitglieder- Verwaltung','', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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
if (isset($_GET['ID'])) {
    $mi_id = $_GET['ID'];
} else {
    $mi_id = "";
}
if (isset($_POST['mi_id'])) {
    $mi_id = $_POST['mi_id'];
}

if ($phase == 99) {
    header('Location: VF_M_List.php');
}
$Edit_Funcs_FeldName = true; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_mitglieder');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($mi_id == 0) {
        $neu['mi_id'] = $mi_id;
        $neu['mi_anrede'] = "Hr.";
        $neu['mi_mtyp'] = $neu['mi_org_typ'] = $neu['mi_org_name'] = $neu['mi_name'] = $neu['mi_vname'] = $neu['mi_titel'] = "";
        $neu['mi_n_titel'] = $neu['mi_dgr'] = $neu['mi_gebtag'] = $neu['mi_staat'] = $neu['mi_plz'] = $neu['mi_ort'] = $neu['mi_anschr'] = "";
        $neu['mi_tel_handy'] = $neu['mi_handy'] = $neu['mi_fax'] = $neu['mi_email'] = $neu['mi_email_status'] = $neu['mi_vorst_funct'] = $neu['mi_ref_leit'] = "";
        $neu['mi_ref_int_2'] = $neu['mi_ref_int_3'] = $neu['mi_ref_int_4'] = "";
        $neu['mi_sterbdat'] = $neu['mi_beitritt'] = $neu['mi_austrdat'] = $neu['mi_m_beitr'] = $neu['mi_m_abo'] = $neu['mi_m_beitr_bez'] = $neu['mi_m_abo_bez'] = $neu['mi_abo_ausg'] = "";
        $neu['mi_einv_art'] = $neu['mi_einversterkl'] = $neu['mi_einv_dat'] = $neu['mi_uidaend'] = $neu['mi_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM fh_mitglieder";

        if ($mi_id != '') {
            $sql .= " WHERE mi_id = '$mi_id'";
        }
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>M Edit $sql </pre>";
        echo "</div>";
        
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($mi_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Mitglied mit der mi_id Nummer $mi_id gefunden</p>";
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
        require ('VF_M_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_M_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>