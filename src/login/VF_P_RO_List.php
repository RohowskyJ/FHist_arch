<?php
/**
 * Protokolle des Vereines Liste anzeigen, Hochladen
 *
 * @author  Josef Rohowsky - neu 2021
 *
 *
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
$tabelle = 'fo_todaten';

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
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

initial_debug();

$LinkDB_database = '';
$db = LinkDB('VFH');

$_SESSION[$module]['referat'] = '5';

VF_chk_valid();

VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "Alle" => "Alle Protokolle. ",
    "Vorst" => "Vorstandssitzungen",
    "EVorst" => "erweiterte Vorstandssitzungen",
    "I4" => "Stammtisch Industrieviertel",
    "M4" => "Stammtisch Mostviertel",
    "W4" => "Stammtisch Weinviertel",
    "R1" => "Sitzung mit Muskelkraft bewegte Geräte (Referat 1)",
    "R2" => "Sitzung mit Motorkraft bewegte Geräte (Referat 2)",
    "R3" => "Sitzung Sonstige Geräte (Referat 3)",
    "R4" => "Sitzung Persönliche Ausrüstung (Referat 4)",
    "R4-I4" => "Sitzung Persönliche Ausrüstung (Referat 4) und Stammtisch Industrie-Viertel",
    "R5" => "Sitzung Archivalien (Protokolle, Fotos, ..) (Referat 5)",
    "R6" => "Sitzung Museales (Inventar) (Referat 6)",
    "R7" => "Sitzung Div Dokumentationen (Referat 7)",
    "GV" => "Generalversammlung",
    "Notiz" => "Gesprächs- Notiz",
    
);
if ($_SESSION[$module]['p_zug'] == "V") {
    $T_list_texte["NeuItem"] = "<a href='VF_P_RO_Edit.php?fo_id=0' > Neues Protokoll hochladen </a>";
      
}

# ===========================================================================================================
# Haeder ausgeben

$header = "";
BA_HTML_header('Sitzungs-Protokolle ', '', 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Foto - Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';

switch ($T_List) {
    case "Alle":

        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     */
}
$List_Hinweise .= '</ul></li>';

$zus_ausw = "";

List_Action_Bar("","Protokolle ", $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
$f_arr = array();
$dir = 'AOrd_Verz/1/01/01';
// Open a directory, and read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file == "." || $file == ".." || stripos($file, "Namenskonventionen") >= 1) {
                continue;
            }
            $f_arr[] = $file;
        }
        closedir($dh);
    }
}
$cnt_file = count($f_arr);

if ($cnt_file >=1 ){
    asort($f_arr);
    
    echo "<table class='w3-table w3-striped w3-hoverable scroll'
               style='border:1px solid black;background-color:white;margin:0px;'>";
    /*
     * <!-- =================================================================================================== -->
     * <!-- Tabellen Kopfzeile Ausgeben -->
     * <!-- =================================================================================================== -->
     */
    echo "<thead>
               <tr style='border-bottom:1px solid black;'>";
    echo "<th>Protokoll-Datei</th><th>Sitzungs- Datum</th><th>Sitzg. Nr.</th><th>Gruppe</th></tr>";
    
    foreach ($f_arr as $file) {
        if (stristr($file, $T_List) || $T_List == "Alle") {
            $f_pos = explode('_', $file);
            $Text = $T_list_texte[$f_pos[2]];
            echo "<tr><th ><a href='$dir/$file' target='_blank'>$file</a></th><td>$f_pos[0]</td><td>$f_pos[1]</td><td>$Text</td></tr>";
        }
    }
    
    echo "</table>";
} else {
    echo "Keine Protokolle vorhaden.<br><br>";
}




BA_HTML_trailer();
?>
