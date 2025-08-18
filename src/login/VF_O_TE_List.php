<?php

/**
 * Liste der Veranstaltungstermine
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'va_daten';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

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

if (isset($_GET['Act'])) {
    IF ( $_GET['Act'] == 1) {
        $_SESSION[$module]['Act'] = $Act = $_GET['Act'];
        VF_chk_valid();
        VF_set_module_p();
    } else {
        if (!isset($_SESSION[$module]['Act'])){
            $_SESSION[$module]['Act'] = $Act = 0;
            $_SESSION['VF_Prim']['p_uid'] = 999999999;
            $_SESSION[$module]['all_upd'] = False;
        }
    }
}

$_SESSION[$module]['TList'] = "Aktuell";

VF_Count_add();

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $T_list_texte = array(
        "Aktuell" => "Aktuelle/zukünftige Veranstaltungen ",
        "Alle" => "Alle Veranstaltungen <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Details" => "Alle mit Details <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Alles" => "Alle Daten aller Veranstaltungen"
    );
} else {
    $T_list_texte = array(
        "Aktuell" => "Aktuelle/zukünftige Veranstaltungen ",
        "Alle" => "Alle Veranstaltungen <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Details" => "Alle mit Details <span style='font:90% normal;'> (Historie der Veranstaltungen mit Jahres Auswahl)</span>",
        "Alles" => "Alle Daten aller Veranstaltungen",
        "Neuer Datensatz" => "<a href='VF_O_TE_Edit.php?va_id=0' >Neue Veranstaltung definieren</a>"
    );
}

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header("Veranstaltungs- Kalender ",  '', 'Adm', '60em');

echo "<fieldset>";

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
            'va_bild'
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

switch ($_SESSION[$module]['scol']) {
    case '':
        $_SESSION[$module]['scol'] = 'va_data_id';
        $_SESSION[$module]['sord'] = 'ASC';
    case 'va_id':
        $sql .= "\n ORDER BY va_id " . $_SESSION[$module]['sord'] . ",va_datum ASC,va_begzt ASC,va_id ASC";
        break;
    case 'va_datum':
        $sql .= "\n ORDER BY va_datum " . $_SESSION[$module]['sord'] . ",va_begzt " . $VF_List_parm['sort_richtung'];
        break;
}

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================
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
    $va_bild = $row['va_bild'];
    $va_prosp_1 = $row['va_prosp_1'];
    $va_prosp_2 = $row['va_prosp_2'];

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
    if ($row['va_bild'] != "") {

        $p1 = $pict_path . $row['va_bild'];

        # $row['fz_bild_1'] = "<img src='$p1' alter='$p1' width='70px'> $fz_bild_1";
        $row['va_bild'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'>  Groß  </a><br>";
    }
    if ($row['va_prosp_1'] != "") {
        $row['va_bild'] .= "<br/><a href='$pict_path$va_prosp_1' target='Prospekt 1'>$va_prosp_1</a><br>";
    }
    if ($row['va_prosp_2'] != "") {
        $row['va_bild'] .= "<br/><a href='$pict_path$va_prosp_2' target='Prospekt 2'>$va_prosp_2</a><br>";
    }
    $va_internet = $row['va_internet'];
    if ($row['va_internet'] != "") {
        $row['va_bild'] .= "<a href='$va_internet' target='Internet' >$va_internet</a>";
    }
    $va_datum = $row['va_datum'];
    $row['va_datum'] = "$va_datum <br>".$row['va_begzt'];
    $va_adresse = $row['va_adresse'];
    $row['va_adresse'] .= "<br>".$row['va_plz']." ".$row['va_ort'];

    return True;
} # Ende von Function modifyRow

?>