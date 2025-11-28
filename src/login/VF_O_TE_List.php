<?php

/**
 * Liste der Veranstaltungstermine
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */
session_start();
 
$module = 'OEF';
$sub_mod = 'TE';

$tabelle = 'va_daten';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_TE_List.php"; 

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

BA_HTML_header("Veranstaltungs- Kalender ",  '', 'Adm', '60em');

echo "<fieldset>";

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
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

$_SESSION[$module]['TList'] = "Aktuell";

VF_Count_add();

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $T_list_texte = array(
        "Aktuell" => "Aktuelle/zukünftige Veranstaltungen "
    );
} else {
    $T_list_texte = array(
        "Aktuell" => "Aktuelle/zukünftige Veranstaltungen ",
        "Alle" => "Alle Veranstaltungen <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Details" => "Alle mit Details <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Alles" => "Alle Daten aller Veranstaltungen"
    );
}

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_TE_Edit.php?va_id=0' >Neue Veranstaltung definieren</a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

if (isset($_GET['va_id'])) {
    $va_id = $_GET['va_id'];
} else {
    $va_id = '';
}

$Tabellen_Spalten = Tabellen_Spalten_parms($db, 'va_daten');

switch ($T_List) {
    case "Aktuell":
    case "Aktiv":
    case "Alle":
        $Tabellen_Spalten = array(
            'va_id',
            'va_datum',
            'va_titel',
            'va_inst',
            'va_adresse',
            'va_staat',
            'va_bdld',
            'va_bild_1'
        );

        break;
    case "Details":
        $Tabellen_Spalten = array(
            'va_id',
            'va_datum',
            'va_titel',
            'va_kateg',
            'va_inst',
            'va_ort',
            'va_kontakt',
            'va_anm_erf'
        );
        break;
}

$Tabellen_Spalten_style['va_id'] = $Tabellen_Spalten_style['va_data_id'] = $Tabellen_Spalten_style['va_datum'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Veranstaltungs Daten ändern: Auf die Zahl in Spalte <q>va_id</q> Klicken.</li>' . '<li>Zur Anmelderliste gehen: auf den Link in Spalte <q>va_anmeld_liste</q> Klicken.</li>' . '<li>Zum Löschen der Anmelder- Listen auf den Link in der Spalte <q>va_id</q> Klicken.</li>' . '</ul></li>';
$zus_text = "";
List_Action_Bar($tabelle,"Veranstaltungs Kalender - $zus_text", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");

switch ($T_List) {
    case "Details":
    case "Aktiv":
    case "Alles":
        $sql = "SELECT * FROM va_daten";
        break;
    default:
        $sql = "SELECT * FROM va_daten";
}

$select_string = $_SESSION[$module]['select_string'];
switch ($T_List) {
    case "Aktuell":
        $sql .= "\n WHERE va_datum>='$act_datum'  ";
        break; // AND va_tag >='$acttag'
    case "Aktiv":
        $sql .= "\n WHERE va_abschluss IS NULL ";
        break; // AND va_tag >='$acttag'
    case "Alle":
    case "Details":
        if ($select_string != '') {
            $sql .= "\n WHERE YEAR(va_datum) = '$select_string'";
            break;
        }
        break;
    case "Alles":
        break;
    default:
        BA_HTML_trailer();
        exit(); # wenn noch nix gewählt wurde >> beenden
}
#============================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O TE List vor list_create $sql </pre>";
echo "</div>";

$zus_text = "";
List_Create($db, $sql,'', $tabelle,'', $zus_text);

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
    global $path2ROOT, $T_List, $VF_List_parm;

    $datum = Date('Y-m-d');

    $va_datum = $row['va_datum'];
    $va_id = $row['va_id'];

    $ev_datum = $row['va_datum'];
    $va_umfang = $row['va_umfang'];
    $va_bild_1 = $row['va_bild_1'];
    $va_bild_2 = $row['va_bild_2'];
    $va_bild_3 = $row['va_bild_3'];

    # ----------------------------------------------------------------------------------------------------------------------------------
    # id Spalte
    # ----------------------------------------------------------------------------------------------------------------------------------
    # $row['va_id'] = "<a href='".$path2ROOT."login/platzres/waverwa/VA_Edit_v5.php?va_id=$va_id'>$va_id</a>";
    $link = "<a href='VF_O_TE_Edit.php?va_id=$va_id'";
    $row['va_id'] = "$link title='Details des Termins anzeigen'>$va_id</a>";

    # ----------------------------------------------------------------------------------------------------------------------------------
    # Datum Spalte
    # ----------------------------------------------------------------------------------------------------------------------------------
    if ($ev_datum < $datum) {
        $row['va_datum'] = "<span style ='color:red;'>$ev_datum</span>";
    }

    # ----------------------------------------------------------------------------------------------------------------------------------
    # Befülle Spalte 'va_umfang'
    # ----------------------------------------------------------------------------------------------------------------------------------
    # echo "L 0249: va_umfang ".$row['va_umfang']." <br>";
    if ($row['va_umfang'] == "") {
        $va_umfang = "9";
    }
    $row['va_umfang'] = VF_Term_Umfang[$va_umfang];

    # ----------------------------------------------------------------------------------------------------------------------------------
    # Teilnehmer Liste
    # ----------------------------------------------------------------------------------------------------------------------------------
    $row['Teilnehmer_Liste'] = '';
    if (empty($row['va_abschluss'])) { # if ( $row['va_multi']<>'T' )
                                       # {
                                       # $row['Teilnehmer_Liste'] = "<a href='VF_7_TT_List_v3.phpList_v5.php?va_id=$va_id&data_id=$va_data_id&multi="
                                       # . $row['va_multi']."' target='_blank' title='Teilnehmer Liste anzeigen/bearbeiten'>Bearbeiten</a> ";
                                       # }
    } else {
        $row['Teilnehmer_Liste'] = "<span style='color:red;'>gelöscht</span>";
    }

    # ----------------------------------------------------------------------------------------------------------------------------------
    # plätze
    # ----------------------------------------------------------------------------------------------------------------------------------
    if ($row['va_akt_pl'] == 0) {
        $row['va_akt_pl'] = " <span style='color:red;'>Keine Plätze vergeben</span>";
    }
    if ($row['va_akt_pl'] > $row['va_plaetze']) {
        $row['va_akt_pl'] .= " <span style='color:red;'>überbucht</span>";
    }
    if ($row['va_anz_anmeld'] > $row['va_plaetze']) {
        $row['va_anz_anmeld'] .= " <span style='color:red;'>überbucht</span>";
    }

    # ----------------------------------------------------------------------------------------------------------------------------------
    # Bild und Prospekte
    # ----------------------------------------------------------------------------------------------------------------------------------

    $cjahr = substr($va_datum, 0, 4);

    $pict_path = $path2ROOT . "/login/AOrd_Verz/Termine/" . $cjahr . "/";
    
    if ($row['va_bild_1'] != "") {

        $image1 = $image2 = "";

        $va_bild_1 = $row['va_bild_1'];
        
        $dn_a = pathinfo(strtolower($va_bild_1));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$va_bild_1' > $ </a>";
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
                    $aord_sp = "Termine/" . $cjahr . "/";
                }
            }
            $fo_arr = explode("-", $va_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image1 = $pict_path.$urh."09/".$aord_sp.$verz.$va_bild_1;
                
                if (!is_file($image1)) {
                    $image1 = $pict_path . $va_bild_1;
                }
            } else {
                $image1 = $pict_path . "Biete_Suche/". $va_bild_1;
            }
            $image2 = "<img src='$image1' alt='Bild 1' width='150px'/> ";
        }
        $row['va_bild_1'] = "<a href='".$image1."'  target='_blanc'> $image2 </a>";
        #$row['va_bild_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'>  Groß  </a><br>";
    }
    /*
    if ($row['va_bild_2'] != "") {
        $row['va_bild_2'] .= "<br/><a href='$pict_path$va_bild_2' target='Prospekt 1'>$va_bild_2</a><br>";
    }
    if ($row['va_bild_3'] != "") {
        $row['va_bild_3'] .= "<br/><a href='$pict_path$va_bild_3' target='Prospekt 2'>$va_bild_3</a><br>";
    }
    $va_internet = $row['va_internet'];
    if ($row['va_internet'] != "") {
        $row['va_bild_1'] .= "<a href='$va_internet' target='Internet' >$va_internet</a>";
    }
    */
    $va_datum = $row['va_datum'];
    $row['va_datum'] = "$va_datum <br>".$row['va_begzt'];
    $va_adresse = $row['va_adresse'];
    $row['va_adresse'] .= "<br>".$row['va_plz']." ".$row['va_ort'];

    return True;
} # Ende von Function modifyRow

?>