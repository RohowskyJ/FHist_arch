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
$tabelle = 'fo_todaten';

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

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestellt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['fo_id'])) {
    $fo_id = $_GET['fo_id'];
} else {
    $fo_id = "";
}

if (isset($_GET['verz'])) {
    $_SESSION[$module]['verzeich'] = $verz = $_GET['verz'];
} else {
    $_SESSION[$module]['verzeich'] = $verz = "J";
} # verz: J .. Verzeichnis-Satz, N .. Foto- Satz

if (isset($_GET['fo_aufn_d'])) {
    $fo_aufn_d = $_GET['fo_aufn_d'];
    $_SESSION[$module]['fo_aufn_datum'] = $_GET['fo_aufn_d'];
} else {
    $fo_aufn_d = "";
} #

if ($phase == 99) {
    header('Location: VF_FO_List.php');
}

$java_script = $java_script_ref = $java_script_such = "";

if ($fo_id !== "") {
    $_SESSION[$module]['fo_id'] = $fo_id;
} else {
    $fo_id = $_SESSION[$module]['fo_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$tabelle = $tabelle . "_" . $_SESSION[$module]['URHEBER']['ei_id'];
Tabellen_Spalten_parms($db, $tabelle);

$Tabellen_Spalten_COMMENT['fo_namen'] = "Namen der vorkommenden Personen";
$Tabellen_Spalten_COMMENT['urh_abk'] = "Urheber des Fotos";
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($_SESSION[$module]['fo_id'] == 0) {

        $fo_typ = $_SESSION[$module]['URHEBER']['ur_media'];
        $fo_eigner = $_SESSION[$module]['URHEBER']['fs_urh_nr'];
        $fo_urheber = $_SESSION[$module]['URHEBER']['fs_fotograf'];

        $pict_path = "../login/AOrd_Verz/" . $fo_eigner . "/09/"; # 06/";

        if ($fo_typ == "F") {
            $fo_media = "Foto";
            $fo_suchb3 = "06";
            $pict_path .= "06/";
        } elseif ($fo_typ == "V") {
            $fo_media = "Video";
            $fo_suchb3 = "10";
            $pict_path .= "10/";
        }

        $neu = array(
            'fo_id' => 0,
            'fo_eigner' => $fo_eigner,
            'fo_Urheber' => $fo_urheber,
            'fo_Urh_kurzz' => "",
            'fo_dsn' => "",
            'fo_aufn_datum' => "$fo_aufn_d",
            'fo_aufn_suff' => "",
            'fo_basepath' => "",
            'fo_zus_pfad' => "",
            'fo_begltxt' => "",
            'fo_namen' => "",
            'fo_sammlg' => "",
            'fo_typ' => $fo_typ,
            'fo_media' => $fo_media,
            "fo_uidaend" => $_SESSION['VF_Prim']['p_uid'],
            "fo_aenddat" => "",
            'verz' => $verz
        );
         
        if ($_SESSION[$module]['verzeich'] == "N") {
            $neu['fo_aufn_datum'] = $_SESSION[$module]['fo_aufn_d'];
            $neu['fo_basepath'] = $_SESSION[$module]['fo_base'];
            $neu['fo_zus_pfad'] = $_SESSION[$module]['fo_zus'];
        }
    } else {
        $sql_fo = "SELECT * FROM $tabelle WHERE `fo_id` = '" . $_SESSION[$module]['fo_id'] . "' ORDER BY `fo_id` ASC";
 
        $return_fo = SQL_QUERY($db, $sql_fo);

        $neu = mysqli_fetch_array($return_fo);

        $neu['urh_abk'] = "";
        
        mysqli_free_result($return_fo);
        $pict_path = "../login/AOrd_Verz/" . $neu['fo_eigner'] . "/09/";
        if ($neu['fo_typ'] == "F") {
            $pict_path .= "06/";
        } elseif ($neu['fo_typ'] == "V") {
            $pict_path .= "10/";
        }
      
    }
}
# echo "E_Edit L 099: \$phase $phase <br/>";
if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
    
    if ($neu['verz'] != "J") {
        if ($neu['fo_begltxt'] == "") {
            $Err_msg['fo_begltxt'] = "keine Bildbeschreibung eingegeben.";
        }
    }

    if (!empty($Err_msg)) {
        if ($neu['verz'] == 'J') {$verz = "J";} else {$verz = 'N';}
        $pict_path = $path2ROOT."login/AOrd_Verz/" . $neu['fo_eigner'] . "/09/";
        if ($neu['fo_typ'] == "F") {
            $pict_path .= "06/";
        } elseif ($neu['fo_typ'] == "V") {
            $pict_path .= "10/";
        }
        $phase = 0;
    }
}

$header = "";
$prot = True;
BA_HTML_header('Foto- Verwaltung',  $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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