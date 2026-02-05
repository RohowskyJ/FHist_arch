<?php
/**
 * Liste der Buchbesprechungen
 * 
 * @author j. Rohowsky - neu 2019
 * 
 */
session_start();

$module = 'OEF';
$sub_mod = 'BU';

$tabelle = 'bu_echer';

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
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_O_BU_List.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$jq_tabsort = $jq = true;
BA_HTML_header('Buch- Beschreibungs- Verwaltung', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<frameset>";

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
/*
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "DropdownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}
*/
// Wennn Aufruf von Offener Seite kommt - NUR LESE-Berechtigung!
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
} else {
    $referer = "";
}
$refer_arr = explode("/", $referer);

$r_cnt = count($refer_arr);
$caller = $refer_arr[$r_cnt - 1];

if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

if (isset($_GET['Act']) and $_GET['Act'] == 1) {
    $_SESSION[$module]['Act'] = $Act = $_GET['Act'];
    VF_chk_valid();
    VF_set_module_p();
    $_SESSION['VF_LISTE']['LangListe'] = "Aus";
} else {
    $_SESSION[$module]['Act'] = $Act = 0;
    $_SESSION['VF_Prim']['p_uid'] = 999999999;
    $_SESSION[$module]['all_upd'] = False;
}

if (strpos($caller, "?") > 0) {
    $lstr = explode("?", $caller);
    $caller = $lstr[0];
}

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    if ($_SESSION[$module]['Act'] == 0) {
        header("Location: ../");
    } else {
        header("Location: /login/VF_C_Menu.php");
    }
}

VF_Count_add();

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Rezensionen "
    );
} else {
    if ($_SESSION[$module]['p_zug'] == "Q") {
        $_SESSION[$module]['all_upd'] = True;
    }
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Rezensionen "
    );
}

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_BU_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

$Tabellen_Spalten = array(
    'bu_id',
    'bu_titel',
    'bu_utitel',
    'bu_teaser',
    'bu_author',
    'bu_verlag',
    'bu_preis',
    'bu_seiten',
    'bu_bilder_anz',
    'bu_bilder_art',
    'bu_format',
    'bu_bild_1',
    'bu_bew_ges',
    'bu_editor'
);
if ($_SESSION[$module]['all_upd'] == '1') {
    array_push($Tabellen_Spalten,"bu_frei_stat");
}

$Tabellen_Spalten_style['bu_id'] = 
$Tabellen_Spalten_style['bu_fbw_st_abk'] = $Tabellen_Spalten_style['ei_staat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Buch - Daten ändern: Auf die Zahl in Spalte <q>bu_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":

        break;

    default:
}

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle," Buch- Besprechungen ", $T_list_texte, $T_List, $List_Hinweise, ''); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$sql = "SELECT * FROM $tabelle";
$where = '';

if ($_SESSION[$module]['all_upd'] == '0') {
    $where = " where bu_frei_stat= 'F' " ;
}
    
$sql .= $where;
echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O BU List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

echo "</frameset>";

BA_HTML_trailer();

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *        
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List;

    $pict_path = "../scripts/updata/Buch/";

    $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr

    $bu_id = $row['bu_id'];
    $row['bu_id'] = "<a href='VF_O_BU_Edit.php?ID=" . $bu_id . "' >" . $bu_id . "</a>";

    if ($row['bu_isbn'] != "") { 
        $row['bu_verlag'] .= "<br>".$row['bu_isbn'];
    }

    if ($row['bu_bild_1'] != "") {

        $bu_bild_1 = $row['bu_bild_1'];
        
        $dn_a = pathinfo(strtolower($bu_bild_1));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$bu_bild_1' > $bu_bild_1 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    break;
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Buch/";
                }
            }
            $fo_arr = explode("-", $bu_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image1 = $pict_path.$urh."09/".$aord_sp.$verz.$bu_bild_1;
                
                if (!is_file($image1)) {
                    $image1 = $pict_path . $bu_bild_1;
                }
            } else {
                $image1 = $pict_path . "Buch/". $bu_bild_1;
            }
            $image2 = "<img src='$image1' alt='Bild 1' width='150px'/> ";
        }
        $row['bu_bild_1'] = "<a href='".$image1."'  target='_blanc'> $image2 </a>";
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow
?>
