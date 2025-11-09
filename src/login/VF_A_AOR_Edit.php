<?php

/**
 * Archivordnung, Erweiterungen für Eigentümer, Wartung
 *
 * @author Josef Rohowsky -  - neu 2018, reorg Tabelle 2024
 *
 * Hinzufügen zweier zusätzlicher Ebenen zur Archivordnung vom ÖBFV
 */
session_start();

$module  = 'ARC';
$sub_mod = 'all';

$tabelle = 'ar_ord_local';

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
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_A_AOR_Edit.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['al_id'])) {
    $al_id = $_GET['al_id'];
} else {
    $al_id = "";
}
if (isset($_GET['al_id'])) {
    $al_id = $_GET['al_id'];
}

if ($phase == 99) {
    header('Location: VF_A_AOR_List.php');
}

if ($al_id !== "") {
    $_SESSION[$module]['al_id'] = $al_id;
} else {
    $al_id = $_SESSION[$module]['al_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle;

Tabellen_Spalten_parms($db, $tabelle_a);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['al_id'] == 0) {
        $eignr = $_SESSION['Eigner']['eig_eigner'];
        $al_grp = $_SESSION[$module]['ar_grp'];
        $neu = array(
            'al_id' => 0,
            "al_sg" => "$al_grp",
            'al_lcsg' => "0",
            'al_lcssg' => "0",
            'al_lcssg_s0' => '0',
            'al_lcssg_s1' => '0',
            "al_bezeich" => '',
            'al_sammlung' => '',
            'al_sb_grp' => "",
            'al_sb_1' => "0",
            'al_sb_2' => "0",
            'al_sb_3' => "0",
            'al_sb_4' => "0",
            'al_sb_fl' => "0"
        );
    } else {
        $al_id = $_SESSION[$module]['al_id'];
        $sql_be = "SELECT * FROM $tabelle_a WHERE `al_id` = '$al_id' ORDER BY `al_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be); ;
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>A_AOR Edit $sql </pre>";
        echo "</div>";
        
        $errno = mysqli_errno($db);
    
        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);

    }
}

if ($phase == 1) {
    
}

$header = ""; // "<script type='text/javascript' src='common/javascript/prototype.js' async></script>";
BA_HTML_header('Archiv- Verwaltung', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>
<!--  
<form id="myform" name="myform" method="post"
	action="<?php echo $_SERVER['PHP_SELF'] ?>"
	enctype="multipart/form-data">
      -->
<?php

switch ($phase) {
    case 0:
        require 'VF_A_AOR_Edit_ph0.inc.php';
        break;
    case 1:
        require "VF_A_AOR_Edit_ph1.inc.php";
        break;
}

?>
  </form>
<?php

BA_HTML_trailer();

?>