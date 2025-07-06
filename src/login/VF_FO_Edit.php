<?php

/**
 * Foto- Verwaltung
 *
 * @author J. Rohowsky  - neu 2018
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'dm_edien_';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$Inc_Arr[] = "VF_FO_Edit.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = false;

$LinkDB_database = '';
$db = LinkDB('VFH');

$jq = $jqui = True;
$BA_AJA = true;

$header = "";

BA_HTML_header('Foto- Verwaltung',  $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestellt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['md_id'])) {
    $md_id = $_GET['md_id'];
} else {
    $md_id = "";
}

if (isset($_GET['verz'])) {
    $_SESSION[$module]['verzeich'] = $verz = $_GET['verz'];
} else {
    $_SESSION[$module]['verzeich'] = $verz = "J";
} # verz: J .. Verzeichnis-Satz, N .. Foto- Satz

if (isset($_GET['md_aufn_d'])) {
    $md_aufn_d = $_GET['md_aufn_d'];
    $_SESSION[$module]['md_aufn_datum'] = $_GET['md_aufn_d'];
} else {
    $md_aufn_d = "";
} #

if ($phase == 99) {
    header('Location: VF_FO_List.php');
}

if ($md_id !== "") {
    $_SESSION[$module]['md_id'] = $md_id;
} else {
    $md_id = $_SESSION[$module]['md_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$tabelle = $tabelle . $_SESSION['Eigner']['eig_eigner'];
Tabellen_Spalten_parms($db, $tabelle);

$Tabellen_Spalten_COMMENT['md_namen'] = "Namen der vorkommenden Personen";

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['md_id'] == 0) {

        $md_eigner = $_SESSION['Eigner']['eig_eigner'];
        $md_urheber = $_SESSION['Eigner']['eig_urhname'];

        $pict_path = "../login/AOrd_Verz/" . $md_eigner . "/09/"; # 06/";

        $neu = array(
            'md_id' => 0,
            'md_eigner' => $md_eigner,
            'md_Urheber' => $md_urheber,
            'md_dsn_1' => "",
            'md_aufn_datum' => "$md_aufn_d",
            'md_suchbegr' => "",
            'md_beschreibg' => "",
            'md_namen' => "",
            'md_sammlg' => "",
            'md_feuerwehr' => '',
            'md_media' => '',
            "md_aenduid" => $_SESSION['VF_Prim']['p_uid'],
            "md_aenddat" => "",
            'verz' => $verz,
            'sa_name' => ''
        );
         
        if ($_SESSION[$module]['verzeich'] == "N") {
            $neu['md_aufn_datum'] = $_SESSION[$module]['md_aufn_d'];
        }
    } else {
        $sql_fo = "SELECT * FROM $tabelle 
                           LEFT JOIN fh_sammlung ON $tabelle.md_sammlg LIKE fh_sammlung.sa_sammlg
                           WHERE `md_id` = '" . $_SESSION[$module]['md_id'] . "' ORDER BY `md_id` ASC";
 
        $return_fo = SQL_QUERY($db, $sql_fo);

        $neu = mysqli_fetch_array($return_fo);

        mysqli_free_result($return_fo);
        $pict_path = "../login/AOrd_Verz/" . $neu['md_eigner'] . "/09/";

    }
}
# echo "E_Edit L 099: \$phase $phase <br/>";
if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
    
    if ($neu['verz'] != "J") {
        if ($neu['md_beschreibg'] == "") {
            $Err_msg['md_beschreibg'] = "keine Bildbeschreibung eingegeben.";
        }
    }

    if (!empty($Err_msg)) {
        if ($neu['verz'] == 'J') {$verz = "J";} else {$verz = 'N';}
        $pict_path = $path2ROOT."login/AOrd_Verz/" . $neu['md_eigner'] . "/09/";
     
        $phase = 0;
    }
}

switch ($phase) {
    case 0:
        require 'VF_FO_Edit_ph0.inc.php';
        break;
    case 1 :
        require "VF_FO_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();
?>