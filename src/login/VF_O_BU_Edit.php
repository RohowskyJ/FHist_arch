<?php

/**
 * Liste der Buchbesprechungen, Wartung
 *
 * @author j. Rohowsky - neu 2019
 *
 */
session_start(); # die SESSION am leben halten

$module = 'OEF';
$sub_mod = 'BU';


const Tabellen_Name = 'bu_echer';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_BU_Edit.php";

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

$tinymce_header = "
     <script src='" . $path2ROOT . "login/common/javascript/tinymce/tinymce.min.js' referrerpolicy='origin'></script>
    <script>
      tinymce.init({
        selector: 'textarea#bu_text',
        menubar: 'edit format'
         });
    </script>
";

$jq = $jqui = true;
$BA_AJA = true;

BA_HTML_header('Buch- Beschreibungs- Verwaltung',  $tinymce_header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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
    $bu_id = $_GET['ID'];
} else {
    $bu_id = "";
}

if (isset($_POST['bu_id'])) {
    $bu_id = $_POST['bu_id'];
}

if ($phase == 99) {
    header('Location: VF_O_BU_List_v3.php');
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = False; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'bu_echer');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($bu_id == "0") {
        $neu['bu_id'] = "0";
        $neu['bu_titel'] = $neu['bu_utitel'] = $neu['bu_author'] = $neu['bu_verlag'] = $neu['bu_isbn'] = "";
        $neu['bu_seiten'] = $neu['bu_preis'] = $neu['bu_bilder_anz'] = $neu['bu_bilder_art'] = $neu['bu_format'] = "";
        $neu['bu_eignr'] = $neu['bu_invnr'] = $neu['bu_teaser'] = $neu['bu_text'] = $neu['bu_bild_1'] = "";
        $neu['bu_bild_2'] = $neu['bu_bild_3'] = $neu['bu_bild_4'] = $neu['bu_bild_5'] = $neu['bu_bild_6'] = $neu['bu_text_1'] = "";
        $neu['bu_text_2'] = $neu['bu_text_3'] = $neu['bu_text_4'] = $neu['bu_text_5'] = $neu['bu_text_6'] = $neu['bu_bew_ges'] = "";
        $neu['bu_bew_bild'] = $neu['bu_bew_txt'] = $neu['bu_editor'] = $neu['bu_ed_id'] = $neu['bu_edit_dat'] = "";
        $neu['bu_frei_id'] = $neu['bu_frei_dat'] = "";
        $neu['bu_frei_stat'] = "U";
    } else {
        $sql = "SELECT * FROM bu_echer";

        if ($bu_id != '') {
            $sql .= " WHERE bu_id = '$bu_id'";
        }
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O BU Edit $sql </pre>";
        echo "</div>";
        
        $result = mysqli_query($db, $sql) or die('Lesen Satz $now_bu_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($bu_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Kein Buch mit der bu_id Nummer $bu_id gefunden</p>";
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
            echo " \$phase $phase </pre>";
        }
    }
}

if ($phase == 1) {
    foreach ($_POST as $name => $value) {
        $neu[$name] = trim(mysqli_real_escape_string($db, $value));
    }
}

switch ($phase) {
    case 0:
        require ('VF_O_BU_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_BU_Edit_ph1.inc.php";
        break;
}
BA_HTML_trailer();
?>