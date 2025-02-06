<?php

/**
 * E-Mail Admin- Verständigen Zuordnung
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

const Module_Name = 'Proj_Conf';
$module = Module_Name;
$tabelle = 'proj_config';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Int_Conf_Const.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';

initial_debug();

if (isset($_GET['inst'])) {
    if ($_GET['inst'] === "J") {
        $_SESSION[$module]['inst'] =  $path2ROOT."VFH/install/install_cleanup.php";
    }
}

// =====================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
$LinkDB_Database = "";
$db = LinkDB('VFH');

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

Tabellen_Spalten_parms($db, 'proj_config');
$Tabellen_spalten = array('c_Institution','c_Vereinsreg','c_Verantwortl','c_email','c_data','c_mode',
    'c_Wartung','c_Wart_Grund','c_logo','c_Eignr','c_Homepage','c_Module_1','c_Module_2','c_Module_3','c_Module_4','c_Module_5',
    'c_Module_6','c_Module_7','c_Module_8','c_Module_9','c_Module_10','c_Module_11','c_Module_12',
    'c_Module_13','c_Module_14','c_Module_15'
);

$Tabellen_Spalten_COMMENT['c_Institution'] = 'Name des Betreibers';
$Tabellen_Spalten_COMMENT['c_Vereinsreg'] = 'Vereinregister- Nummer';
$Tabellen_Spalten_COMMENT['c_Verantwort'] =  'Name der verantw. Person';
$Tabellen_Spalten_COMMENT['c_email'] = 'E-Mail der verantw. Person';
$Tabellen_Spalten_COMMENT['c_Ver_Tel'] = 'Telefon der verantw. Person';
$Tabellen_Spalten_COMMENT['c_mode'] = 'Betriebsmodus';
$Tabellen_Spalten_COMMENT['c_Wartung'] = 'Wartungsmodus';
$Tabellen_Spalten_COMMENT['c_Wart_Grund'] = 'Wartungsgrund, Information';
$Tabellen_Spalten_COMMENT['c_logo'] = 'Logo (Signatur)';
$Tabellen_Spalten_COMMENT['c_1page'] = 'Bild der 1. Seite';
$Tabellen_Spalten_COMMENT['c_Eignr'] = 'Eigentümer- Nummer';
$Tabellen_Spalten_COMMENT['c_Homepage'] = 'Homepage (URL)';

$Tabellen_Spalten_COMMENT['c_Module_1'] = Modules['Mod_1'];
$Tabellen_Spalten_COMMENT['c_Module_2'] = Modules['Mod_2'];
$Tabellen_Spalten_COMMENT['c_Module_3'] = Modules['Mod_3'];
$Tabellen_Spalten_COMMENT['c_Module_4'] = Modules['Mod_4'];
$Tabellen_Spalten_COMMENT['c_Module_5'] = Modules['Mod_5'];
$Tabellen_Spalten_COMMENT['c_Module_6'] = Modules['Mod_6'];
$Tabellen_Spalten_COMMENT['c_Module_7'] = Modules['Mod_7'];
$Tabellen_Spalten_COMMENT['c_Module_8'] = Modules['Mod_8'];
$Tabellen_Spalten_COMMENT['c_Module_9'] = Modules['Mod_9'];
$Tabellen_Spalten_COMMENT['c_Module_10'] = Modules['Mod_10'];
$Tabellen_Spalten_COMMENT['c_Module_11'] = Modules['Mod_11'];
$Tabellen_Spalten_COMMENT['c_Module_12'] = Modules['Mod_12'];
$Tabellen_Spalten_COMMENT['c_Module_13'] = Modules['Mod_13'];
$Tabellen_Spalten_COMMENT['c_Module_14'] = Modules['Mod_14'];
$Tabellen_Spalten_COMMENT['c_Module_15'] = Modules['Mod_15'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    
    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini',True,INI_SCANNER_NORMAL);
        
        $neu['c_Institution'] = $ini_arr["Config"]['inst'];
        $neu['c_Vereinsreg'] = $ini_arr["Config"]['vreg'];
        $neu['c_Eignr'] = $ini_arr["Config"]['eignr'];
        $neu['c_Verantwort'] = $ini_arr["Config"]['vant'];
        $neu['c_email'] = $ini_arr["Config"]['vema'];
        $neu['c_Ver_Tel'] = $ini_arr["Config"]['vtel'];
        $neu['c_mode'] = $ini_arr["Config"]['mode'];
        $neu['c_Wartung'] = $ini_arr["Config"]['wart'];
        $neu['c_Wart_Grund'] = $ini_arr["Config"]['warg'];
        $neu['c_logo'] = $ini_arr["Config"]['sign'];
        $neu['c_1page'] = $ini_arr["Config"]['fpage'];
        $neu['c_Homepage'] = $ini_arr["Config"]['homp'];
    } else {
        $neu['c_Institution'] = "Organisations- Bezeichnung";
        $neu['c_Vereinsreg'] = 'Vereinsreg- Nummer';
        $neu['c_Eignr'] = 'Eigentümer- Nummer';
        $neu['c_Verantwort'] = 'Name des Verantwortlichen';
        $neu['c_email'] = 'email@verantwortl.cc';
        $neu['c_Ver_Tel'] = '+43 - Tel-Nr des Verantwortlichen';
        $neu['c_mode'] = 'Single';
        $neu['c_Wartung'] = 'N';
        $neu['c_Wart_Grund'] = "";
        $neu['c_logo'] = 'Signet.jpg';
        $neu['c_1page'] = 'Bild_1_Seite.png';
        $neu['c_Homepage'] = 'https://www.homepage-Name.at';
    }
    if (is_file($path2ROOT . 'login/common/config_m.ini')) {
        $ini_arr = parse_ini_file($path2ROOT.'login/common/config_m.ini',True,INI_SCANNER_NORMAL);
        
        $neu['c_Module_1'] = $ini_arr["Modules"]['m_1'];
        $neu['c_Module_2'] = $ini_arr["Modules"]['m_2'];
        $neu['c_Module_3'] = $ini_arr["Modules"]['m_3'];
        $neu['c_Module_4'] = $ini_arr["Modules"]['m_4'];
        $neu['c_Module_5'] = $ini_arr["Modules"]['m_5'];
        $neu['c_Module_6'] = $ini_arr["Modules"]['m_6'];
        $neu['c_Module_8'] = $ini_arr["Modules"]['m_8'];
        $neu['c_Module_9'] = $ini_arr["Modules"]['m_9'];
        $neu['c_Module_10'] = $ini_arr["Modules"]['m_10'];
        $neu['c_Module_11'] = $ini_arr["Modules"]['m_11'];
        $neu['c_Module_12'] = $ini_arr["Modules"]['m_12'];
        $neu['c_Module_13'] = $ini_arr["Modules"]['m_13'];
        $neu['c_Module_14'] = $ini_arr["Modules"]['m_14'];
        $neu['c_Module_15'] = $ini_arr["Modules"]['m_15'];
    } else {
        $neu['c_Module_1'] = 'J';
        $neu['c_Module_2'] = 'J';
        $neu['c_Module_3'] = 'J';
        $neu['c_Module_4'] = 'J';
        $neu['c_Module_5'] = 'J';
        $neu['c_Module_6'] = 'J';
        $neu['c_Module_7']= 'J';
        $neu['c_Module_8'] = 'J';
        $neu['c_Module_9'] = 'J';
        $neu['c_Module_10'] = 'J';
        $neu['c_Module_11'] = 'J';
        $neu['c_Module_12'] = 'J';
        $neu['c_Module_13'] = 'J';
        $neu['c_Module_14'] = 'J';
        $neu['c_Module_15'] = 'J';
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo '<hr>\$neu: ';
        print_r($neu);
        echo '</pre>';
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
}

HTML_header('Konfigurations- Verwaltung', '', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('Proj_Conf_Edit_ph0.inc.php');
        break;
    case 1:
        require "Proj_Conf_Edit_ph1.inc.php";
        break;
}
HTML_trailer();
?>