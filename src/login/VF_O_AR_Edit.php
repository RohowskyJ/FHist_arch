<?php

/**
 * Liste der öffentlichen Archive und Bibliotheken, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'fh_falinks';

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
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

if (isset($_GET['ID'])) {
    $fa_id = $_GET['ID'];
} else {
    $fa_id = "";
}

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_POST['fa_id'])) {
    $fa_id = $_POST['fa_id'];
}

if ($phase == 99) {
    header('Location: VF_O_Verw.php');
}

BA_HTML_header('Bibliothek- und Archiv- Links  Verwaltung', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_falinks');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fa_id == "0") {
        $neu['fa_id'] = $fa_id;
        $neu['fa_link'] = $neu['fa_text'] = $neu['fa_aenduid'] = $neu['fa_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM fh_falinks";

        if ($fa_id != '') {
            $sql .= " WHERE fa_id = '$fa_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $now_fa_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($now_fa_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Bericht mit der fa_id Nummer $now_fa_id gefunden</p>";
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
    
}


switch ($phase) {
    case 0:
        require ('VF_O_AR_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_AR_Edit_ph1.inc.php";
        break;
}
HTML_trailer();
?>