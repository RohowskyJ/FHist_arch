<?php

/**
 * EMail an ander Mitglieder schicken
 *
 * @author Josef Rohowsky - neu 2022
 *
 */
session_start();

const Module_Name = '0_EM';
$module = Module_Name;
$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php'; // Diverse Unterprogramme
require $path2ROOT . 'login/common/BA_Funcs.lib.php'; // Diverse Unterprogramme
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';

$header = "";
BA_HTML_header('Meine Nachricht an die Feuerwehrhistoriker in NÖ',  $header, 'Form', '75em');

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

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

if ($phase == 99) {
    header('Location: /index.php');
}
$Edit_Funcs_FeldName = true; // Feldname der Tabelle wird nicht angezeigt !!

                             # --------------------------------------------------------
                             # Lesen der Daten aus der sql Tabelle
                             # ------------------------------------------------------------------------------------------------------------


# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {}


$ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini',True,INI_SCANNER_NORMAL);
$adm_mail_arr = parse_ini_file($path2ROOT.'login/common/admin_email.ini',True,INI_SCANNER_NORMAL);
$adm_mail = "";
foreach ($adm_mail_arr['Allg'] as $key => $value) {
    $adm_mail = "$value,";
}
if ($adm_mail != "") {
    $adm_mail = substr($adm_mail,0,-1);
}

$adrs = array(
    "Allgemein" => $ini_arr['Config']['vema'].", $adm_mail",
    "Referat1"  => $ini_arr['Config']['vema'].", $adm_mail",
    "Referat2"  => $ini_arr['Config']['vema'].", $adm_mail",
    "Referat3"  => $ini_arr['Config']['vema'].", $adm_mail",
    "Referat4"  => $ini_arr['Config']['vema'].", $adm_mail"
);
# print_r($adrs);

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = $value;
    }

    $adresse = ($adrs[$neu['adresse']]);
    if ($neu['adresse'] != "Allgemein") {
        if ($adresse == "") {
            $adresse = $adrs["Allgemein"];
        } else {
            $adresse .= ", " . $adrs["Allgemein"];
        }
    }

    $dsn = "../../login/logs/emaillog";
    $log_rec = "Mail- Log: Orig.Requ: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $log_rec .= "$adresse \n" . $neu['betreff'] . " \n " . $neu['msgtext'] . " \n" . $neu['email'] . "\n\n";
    $fname = writelog($dsn, $log_rec);

    sendEmail($adresse, $neu['betreff'], $neu['msgtext'] . " " . $neu['absender'], $neu['email']);

    header("Location: $path2ROOT");
}

switch ($phase) {
    case 0:
        require 'VF_EM_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();
?>
