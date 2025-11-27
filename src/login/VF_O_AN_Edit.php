<?php
/**
 * Liste der Anbote / Nachfragen, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start(); # die SESSION am leben halten

$module = 'OEF';
$sub_mod ='AN';

$tabelle = 'bs_biete_suche';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_AN_Edit.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$tinymce_header = "
     <script src='" . $path2ROOT . "login/common/javascript/tinymce/tinymce.min.js' referrerpolicy='origin'></script>
    <script>
      tinymce.init({
        selector: 'textarea#bs_text',
        menubar: 'edit format'
         });
    </script>
   ";

$jq = $jqui = true;
$BA_AJA = true;

$jq_fotoUp = true; // Foto upload oder Auswahl aus FotoLibs

BA_HTML_header('Biete / Suche - Marktplatz', $tinymce_header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = False; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl
                    // ============================================================================================================
                    // Eingabenerfassung und defauls
                    // ============================================================================================================

if (isset($_GET['ID'])) {
    $bs_id = $_GET['ID'];
} else {
    $bs_id = "";
}

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_POST['bs_id'])) {
    $bs_id = $_POST['bs_id'];
}

if ($phase == 99) {
    header('Location: VF_O_AN_List.php');
}

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'bs_biete_suche');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($bs_id == 0) {

        $neu['bs_id'] = 0;
        $neu['bs_startdatum'] = $neu['bs_enddatum'] = $neu['bs_kurztext'] = $neu['bs_text'] = $neu['bs_typ'] = "";
        $neu['bs_email_1'] = $neu['bs_email_2'] = $neu['bs_bild_1'] = $neu['bs_bild_2'] = $neu['bs_bild_3'] = $neu['bs_bild_4'] = "";
        $neu['bs_aenduid'] = $neu['bs_aenddate'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle";

        if ($bs_id != '') {
            $sql .= " WHERE bs_id = '$bs_id'";
        }
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AN Edit $sql </pre>";
        echo "</div>";
        
        $result = SQL_QuERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($now_bs_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Bericht mit der bs_id Nummer $now_bs_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }

        $neu = mysqli_fetch_array($result);
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>\$neu: ';
            var_dump($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {
   
}



echo "<form id='myform' name='myform' method='post' action='" . $_SERVER['PHP_SELF'] . "' enctype='multipart/form-data'>";

switch ($phase) {
    case 0:
        require ('VF_O_AN_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_AN_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();
?>