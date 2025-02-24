<?php

/**
 * Foto- Massupload
 *
 * @author J. Rohowsky  - neu 2024
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

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();


$prot = True;
$header = "
<style>
        #preview {
            display: flex;
            flex-direction: column;
        }
        .preview-image {
            margin: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .preview-image img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
";

BA_HTML_header('Archivalien- Mass- Upload',  $header, 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

VF_chk_valid();

VF_set_module_p();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
# var_dump($_POST);

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
# echo "L 077 phase $phase <br>";
if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if ($phase == 99) {
    # header('Location: VF_7_FO_M_SelectList_v4.php');
}

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - select_string
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "EIN",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige" => "Aus",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

if (!isset($Err_Msg)) {
    $Err_Msg = "";
}

if ($phase == 1) {
    if (isset($_POST['auto']) ) {
        VF_Displ_Eig($_POST['auto']);
    }

    require "VF_A_AR_MassUp_ph1.inc.php";

}

if ($phase == 0) {

        echo "<h2>Eigentümer auswählen zum Archivalien hochladen</h2>";
        VF_Eig_Ausw();
      
        echo "<br> <button type='submit' name='phase' value='1' class=green>Auswahl bestätigen</button></p>";
    
}


BA_HTML_trailer();
?>

                           