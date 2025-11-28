<?php

/**
 * Lste der Presse
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

$module = 'OEF';
$sub_mod = 'PR';

$tabelle = 'pr_esse';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_PR_List.php";

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
BA_HTML_header('Presse- Berichte- Verwaltung', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
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
        "DropdownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

// Wennn Aufruf von Offener Seite kommt - NUR LESE-Berechtigung!

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

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
        header("Location: ../");
    } else {
        header("Location: /login/VF_C_Menu.php");
    }
}

VF_Count_add();

if (isset($_POST['select_string'])) {
    $_SESSION[$module]['select_string'] = $_POST['select_string'];
} else {
    $_SESSION[$module]['select_string'] = "";
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Presseberichte "
    );


$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_PR_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

$Tabellen_Spalten = array(
    'pr_id',
    'pr_datum',
    'pr_name',
    'pr_ausg',
    'pr_medium',
    'pr_seite',
    'pr_teaser',
    'pr_text',
    'pr_web_seite',
    'pr_web_text',
    'pr_bild_1',
    'pr_inet'
);

$Tabellen_Spalten_style['bu_id'] =
$Tabellen_Spalten_style['bu_fbw_st_abk'] = $Tabellen_Spalten_style['ei_staat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Buch - Daten ändern: Auf die Zahl in Spalte <q>pr_id</q> Klicken.</li>';
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

List_Action_Bar($tabelle," Presse- Berichte ", $T_list_texte, $T_List, $List_Hinweise, ''); # Action Bar ausgeben

# ===========================================================================================================
#
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
#
# ===========================================================================================================
$sql = "SELECT * FROM $tabelle ";
switch ($T_List) {
    case "Alle":
        $sql_where = " ";
        $orderBy = ' ORDER BY pr_id';
        break;
    # case "Thema" : $sql_where="WHERE dk_Thema = '$doc_art'"; $orderBy = ' ORDER BY dk_nr'; break;

    default:
        BA_HTML_trailer();
        exit(); # wenn noch nix gewählt wurde >> beenden
}

$select_string = $_SESSION[$module]['select_string'];
if ($select_string != '') {
    switch ($T_List) {
        case "Alle":
            $sql_where = " ";
            break;
        # case "Thema" : $sql_where = " WHERE dk_Thema ='$doc_art'"; break;
        # default : $sql_where .= " AND mi_name LIKE '$select_string%'";
    }
}
$sql .= $sql_where . $orderBy;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O PR List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

echo "</fieldset>";

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

    $pict_path = "../login/AOrd_Verz/Presse/";

    $pr_id = $row['pr_id'];
    $row['pr_id'] = "<a href='VF_O_PR_Edit.php?ID=" . $pr_id . "'  >" . $pr_id . "</a>";

    if ($row['pr_teaser'] == "") {
        if ($row['pr_text'] == "") {} else {
            if (strlen($row['pr_text']) > 30) {
                $pr_text = substr($row['pr_text'], 0, 20);
                $row['pr_text'] = "$pr_text </b></i></p></font></span></div> <a href='VF_O_PR_Edit.php?ID=" . $pr_id . "' >weiter ...</a>";
            }
        }
    } else {
        if (strlen($row['pr_teaser']) > 30) {
            $pr_teaser = substr($row['pr_teaser'], 0, 20);
            $row['bu_teaser'] = "$pr_teaser </b></i></p></font></span></div>  <a href='VF_O_PR_Edit.php?ID=" . $pr_id . "' >weiter ...</a>";
        }
        if ($row['pr_text'] == "") {} else {
            if (strlen($row['pr_text']) > 30) {
                $pr_text = substr($row['pr_text'], 0, 20);
                $row['pr_text'] = "$pr_text </b></i></p></font></span></div>  <a href='VF_O_PR_Edit.php?ID=" . $pr_id . " ' >weiter ...</a>";
            }
        }
    }
    $image1 = $image2 = "";
    if ($row['pr_bild_1'] != "") {
        /*
        $pr_bild_1 = $row['pr_bild_1'];
        $DsName = $pict_path . $row['pr_bild_1'];
        $image1 = "<img src='$DsName' alt='Bild 1' width='100px'/> ";
        $row['pr_bild_1'] = "<a href=\"$DsName\" target='Bild 1'> $image1 </a>";
        */
        $pr_bild_1 = $row['pr_bild_1'];
        
        $dn_a = pathinfo(strtolower($pr_bild_1));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Presse/$pr_bild_1' > $pr_bild_1 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Biete_Suche/";
                }
            }
            $fo_arr = explode("-", $pr_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image1 = $pict_path.$urh."09/".$aord_sp.$verz.$pr_bild_1;
                
                if (!is_file($image1)) {
                    $image1 = $pict_path . $pr_bild_1;
                }
            } else {
                $image1 = $pict_path . "Presse/". $pr_bild_1;
            }
            $image2 = "<img src='$image1' alt='Bild 1' width='100px'/> ";
        }
        $row['pr_bild_1'] = "<a href='".$image1."'  target='_blanc'> $image2 </a>";
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

