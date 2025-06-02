<?php

/**
 * Liste der Dokumentationen, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;

const Tabellen_Name = 'fh_dokumente';
$tabelle = Tabellen_Name;

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

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $dk_nr = $_GET['ID'];
} else {
    $dk_nr = "";
}

if (isset($_POST['dk_nr'])) {
    $dk_nr = $_POST['dk_nr'];
}

if ($phase == 99) {
    header('Location: VF_O_DO_List.php');
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
    if ($dk_nr == 0) {
        $neu['dk_nr'] = 0;
        $neu['dk_Thema'] = $neu['dk_Titel'] = $neu['dk_Author'] = $neu['dk_Urspr'] = "";
        $neu['dk_Dsn'] = $neu['dk_Dsn_2'] = $neu['dk_Path2Dsn'] = $neu['dk_url'] = $neu['dk_sg'] = $neu['dk_aenduid'] = $neu['dk_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($dk_nr != '') {
            $sql .= " WHERE dk_nr = '$dk_nr'";
        }

        $result = SQL_QUERY($db, $sql) or die('Lesen Satz $dk_nr nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($d_nr != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Kein Dokument mit der dk_nr Nummer $dk_nr gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>\$neu: ';
            print_r($neu);
            echo " \$phase $phase </pre>";
        }
    }
}

if ($phase == 1) {

    require "VF_O_DO_Edit_ph1.inc.php";
}

BA_HTML_header('Dokumente zum Downloaden',  '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_O_DO_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_DO_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>