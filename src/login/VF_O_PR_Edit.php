<?php

/**
 * Lste der Presse, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;

const Tabellen_Name = 'pr_esse';

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
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$jq = $jqui = true;
$BA_AJA = true;

BA_HTML_header('Pressespiegel- Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $pr_id = $_GET['ID'];
} else {
    $pr_id = "";
}

$tabelle = Tabellen_Name;
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
#  $lowHeight = False; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'pr_esse');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($pr_id == 0) {
        $neu['pr_id'] = "0";
        $neu['pr_datum'] = $neu['pr_name'] = $neu['pr_ausg'] = $neu['pr_medium'] = $neu['pr_seite'] = "";
        $neu['pr_teaser'] = $neu['pr_text'] = $neu['pr_bild_1'] = $neu['pr_bild_2'] = $neu['pr_bild_3'] = "";
        $neu['pr_bild_4'] = $neu['pr_bild_5'] = $neu['pr_bild_6'] = $neu['pr_web_site'] = $neu['pr_web_text'] = $neu['pr_inet'] = "";
        $neu['pr_uidaend'] = $neu['pr_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM pr_esse";

        if ($pr_id != '') {
            $sql .= " WHERE pr_id = '$pr_id'";
        }
        # $result = mysqli_query($db,$sql) or die('Lesen Satz $now_pr_id nicht möglich: ' . mysqli_error($db));
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($pr_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Bericht mit der pr_id Nummer $now_pr_id gefunden</p>";
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
            echo '</pre>';
        }
    }
}

if ($phase == 1) {
    
}

switch ($phase) {
    case 0:
        require ('VF_O_PR_Edit_ph0.inc.php');
        break;
    case 1: 
        require "VF_O_PR_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>