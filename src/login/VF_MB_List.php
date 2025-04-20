<?php

/**
 * Mitgliederverwwaltung Zahlungseingabgsvermerk
 * 
 * @author Josef Rohowsky - neu 2020
 */
session_start();

const Module_Name = 'MVW';
$module = Module_Name;

const Tabellen_Name = 'fh_mitglieder';
$tabelle = Tabellen_Name;

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

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php?p_uid=$p_uid");
}

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige" => "Ein",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Ein"
    );
}

$mitgl_nrs = "";
$mitgl_einv_n = 0;

$csv_DSN = 'Bezahlt.csv';

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION['VF']['$select_string'] = $select_string;

# ===========================================================================================
#
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
#
# ===========================================================================================
$T_list_texte = array(
    "AlleM" => "Alle aktiven Mitglieder <span style='font:90% normal;'>(mit Namens Auswahl)</span>",
    "offenAlle" => "aktive Mitglieder - für 20" . date('y') . " nicht bezahlt",
    "bezahlt" => "für 20" . date('y') . " bezahlt",
    "EM" => "Nicht zahlende Mitglieder (EM oder OE) <span style='font:90% normal;'>(mit Namens Auswahl)</span>",
    "Alle" => "Alle <span style='font:90% normal;'>incl. BU und RI (mit Namens Auswahl)</span>"
);
 # "offen" => "aktive Mitglieder - für 20" . date('y') . " nicht bezahlt  <span style='font:90% normal;'>(mit Namens Auswahl)</span>",

if (isset($_GET['mod_t_id'])) {
    $mod_t_id = $_GET['mod_t_id'];
}

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header("Mitglieds Beitrag", '', '', 'Adm', '200em');

echo "<fieldset>";

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

$curjahr = date("Y");
$ljahr = date("Y") - 1;
$ljahr = $ljahr . "-12-31";
$BM_m2 = "MitglB " . ($curjahr - 2);
$BA_m2 = "Abo " . ($curjahr - 2);
$BM_m1 = "MitglB " . ($curjahr - 1);
$BA_m1 = "Abo " . ($curjahr - 1);
$BM = "MitglB " . ($curjahr);
$BA = "Abo " . ($curjahr);
$BMA = "MitglB + Abo " . ($curjahr);
$BM_1 = "MitglB " . ($curjahr + 1);
$BA_1 = "Abo " . ($curjahr + 1);
$BMA_1 = "MitglB + Abo " . ($curjahr + 1);

$Tabellen_Spalten = array(
    'mi_name',
    'mi_vname',
    'mi_titel',
    'mi_id',
    'mi_mtyp',
    $BM_m2,
    $BM_m1,
    $BM,
    $BA,
    $BMA,
    $BM_1,
    $BA_1,
    $BMA_1,
    'mi_m_beitr_bez_bis',
    'mi_m_abo_bez_bis',
    'mi_m_beitr_bez',
    'mi_m_abo_bez',
    'Korrektur'
);

$Tabellen_Spalten_style['mi_id'] = $Tabellen_Spalten_style['Korrektur'] = 'text-align:center;';

$Tabellen_Spalten_style['mi_m_beitr_bez_bis'] = 'font-weight:bold;';

$text = "<ul style='margin:0 1em 0em 1em;padding:0;'>" . "<li><b style='color:green;'>&checkmark;</b> : gebucht</li>" . "<li><b style='color:red;'  >&cross;</b> : nicht möglich</li>" . "<li><b style='color:blue;' >&euro;</b> : nicht gebucht. Zum buchen anklicken.</li>" . "</ul>";

$Tabellen_Spalten_COMMENT[$BM_m2] = 'Mitglieds- Beitrag bis Ende ' . ($curjahr - 2) . $text;
$Tabellen_Spalten_COMMENT[$BM_m1] = 'Mitglieds- Beitrag bis Ende ' . ($curjahr - 1) . $text;

$Tabellen_Spalten_COMMENT[$BM] = 'Mitglieds- Beitrag bis Ende ' . ($curjahr) . $text;
$Tabellen_Spalten_COMMENT[$BA] = 'Abo bis Ende ' . ($curjahr) . $text;
$Tabellen_Spalten_COMMENT[$BMA] = 'Abo- und Mitglieds- Beitrag  bis Ende ' . ($curjahr) . $text;
$Tabellen_Spalten_COMMENT[$BM_1] = 'Mitglieds- Beitrag bis Ende ' . ($curjahr + 1) . $text;
$Tabellen_Spalten_COMMENT[$BA_1] = 'Abo bis Ende ' . ($curjahr + 1) . $text;
$Tabellen_Spalten_COMMENT[$BMA_1] = 'Abo- und Mitglieds- Beitrag  bis Ende ' . ($curjahr + 1) . $text;
$Tabellen_Spalten_COMMENT['Korrektur'] = 'Korrektur nach Fehlbuchung ' . ($curjahr) . " <br>noch nicht verfügbar";
# $Tabellen_Spalten_COMMENT['Hist'] = 'Historie der Buchungen '.($curjahr)." <br>noch nicht verfügbar";

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . 
# . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>t_id</q> Klicken.</li>'
# . '<li>Änderungs Geschichte ansehen: Auf <q>Hist</q> in Spalte <q>Hist</q> Klicken.</li>'
'<li><b>Beitragszahlung ändern</b>: ' . "auf das Euro Symbol (<q>&euro;</q>) in einer der Spalten <q>2<i>x yyyy</i></q> Klicken</li>" . '<li>E-Mail senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>' . '</ul></li>';

List_Action_Bar(Tabellen_Name, "Mitglieds-Beitrag: Buchungs-Status", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

$sql = "SELECT * FROM $tabelle ";
switch ($T_List) {
    case "Alle":
    # break;
    case "AlleM":
        $sql .= " WHERE  mi_mtyp<>'OE' && ((mi_austrdat IS NULL OR mi_austrdat = '') AND (mi_sterbdat IS NULL OR mi_sterbdat = ''))    "; 
        break;

    case "offen":
    case "offenAlle":
        $sql .= " WHERE mi_mtyp<>'OE' && (mi_m_beitr_bez_bis<'$ljahr' ) && ((mi_austrdat IS NULL OR mi_austrdat = '') AND (mi_sterbdat IS NULL OR mi_sterbdat = ''))  ";
        break;
    // OR (mi_austrdat = NULL AND mi_sterbdat = NULL
    case "EM":
        $sql .= " WHERE  mi_mtyp='OE' || mi_mtyp='EM' && ((mi_austrdat IS NULL OR mi_austrdat = '') AND (mi_sterbdat IS NULL OR mi_sterbdat = ''))     "; 
        break;
    case "bezahlt":
        $sql .= " WHERE mi_mtyp<>'OE' && (mi_m_beitr_bez_bis>= '$ljahr' ) && ((mi_austrdat IS NULL OR mi_austrdat = '') AND (mi_sterbdat IS NULL OR mi_sterbdat = ''))  ";
        break;
        // OR (mi_austrdat = NULL AND mi_sterbdat = NULL
    case "EM":
        $sql .= " WHERE  mi_mtyp='OE' || mi_mtyp='EM' && ((mi_austrdat IS NULL OR mi_austrdat = '') AND (mi_sterbdat IS NULL OR mi_sterbdat = ''))     ";
        break;
    default:
        HTML_trailer();
        exit(); # wenn noch nix gewählt wurde >> beenden
}

$select_string = $List_parm['select_string'];
if ($select_string != '') {
    switch ($T_List) {
        case "bezahlt":
        case "bezahltn":
        case "bezahltnn":
            break;
        case "Alle":
            $sql .= " AND mi_name LIKE '$select_string%'";
            break;
        default:
            $sql .= " AND mi_name LIKE '$select_string%'";
    }
}
$sql .= " ORDER BY mi_name, mi_vname ASC  ";
# echo "L 0218 sql $sql <br>";
# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

List_Create($db, $sql, '', Tabellen_Name, '');

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
 * @global string $curjahr Laufedes Jahr
 * @global string $ljahr vergangenes Jahr
 * @global string $BM_m2 Verbuchung Mitgliedsbeitrag für vor 2 Jahren
 * @global string $BM_m1 Verbuchung Mitgliedsbeitrag für vor 1 Jahren
 * @global string $BM Verbuchung Mitgliedsbeitrag für heuer
 * @global string $BA Verbuchung ABO- Betrag für heuer
 * @global string $BMA Verbuchung Mitgl-Beitrag + ABO- Betrag für heuer
 * @global string $BM_1 Verbuchung Mitgliedsbeitrag für nächstes Jahr
 * @global string $BA Verbuchung ABO- Betrag für näachstes Jahr
 * @global string $BMA Verbuchung Mitgl-Beitrag + ABO- Betrag für nächstes Jahr
 * @global string $p_uid Benutzer- ID
 */
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $curjahr, $ljahr, $BM_m2, $BM_m1, $BM, $BA, $BMA, $BM_1, $BA_1, $BMA_1, $p_uid;

    $mi_id = $row['mi_id'];

    $Bezahlt = "<b style='color:green;font-size:130%;'>&checkmark;</b>";
    $NA = "<span style='color:red;'>&cross;</span>";

    if ($row['mi_name'] == "" and $row['mi_org_name'] != "") {
        $row['mi_name'] = $row['mi_org_name'];
    }

    $mi_beitr_jahr = substr($row['mi_beitritt'], 0, 4);

    $a = "<a href='VF_MB_buchung.php?mi_id=$mi_id&p_uid=$p_uid";
    /*
     * if (is_null($row['mi_m_beitr_bez']) || $row['mi_m_beitr_bez'] < $curjahr . '-01-01') {
     * #if ($row['mi_m_beitr_bez'] < $curjahr . '-01-01') {
     * $row[$BM] = "$a&b=BM' title='Mitgliedsbeitrag heuer bezahlen'>" . "&euro;</a>"; # 2B für heuer
     * $row[$BA] = "$a&b=BA' title='Abo für heuer bezahlen'>" . "&euro;</a>";
     * $row[$BMA] = "$a&b=BMA' title='Mitgliedsbeitrag + Abo heuer bezahlen'>&euro;</a>"; # für heuer
     * $row[$BM_1] = "$a&b=BM_1' title='Mitgliedsbeitrag nächstes Jahr bezahlen'>" . "&euro;</a>"; # MB für nächstes Jahr
     * $row[$BA_1] = "$a&b=BA_1' title='Abo für nächstes Jahr bezahlen'>" . "&euro;</a>";
     * $row[$BMA_1] = "$a&b=BMA_1' title='Mitgliedsbeitrag + Abo nächstes Jahr bezahlen'>&euro;</a>";
     * }
     */
    $curjahr_m2 = $curjahr - 2;
    $curjahr_m1 = $curjahr - 1;
    $curjahr_1 = $curjahr + 1;

    if ($mi_beitr_jahr <= $curjahr_m2 && $row['mi_m_beitr_bez_bis'] >= $curjahr_m2) {
        $row[$BM_m2] = $Bezahlt;
    }

    if ($mi_beitr_jahr <= $curjahr_m1 && $row['mi_m_beitr_bez_bis'] >= $curjahr_m1) {
        $row[$BM_m1] = $Bezahlt;
    }

    if ($row['mi_m_beitr_bez_bis'] >= $curjahr) {
        $row[$BM] = $Bezahlt;
        # $row[$BMA] = "$NA";
    } else {
        $row[$BM] = "$a&b=BM' title='MitglBeitr. für heuer bezahlen'>" . "&euro;</a>";
        $row[$BMA] = "$a&b=BMA' title='Mitgliedsbeitrag + Abo heuer bezahlen'>&euro;</a>";
    }

    if ($row['mi_m_abo_bez_bis'] >= $curjahr) {
        $row[$BA] = $Bezahlt;
        $row[$BMA] = "";
    } else {
        $row[$BA] = "$a&b=BA' title='Abo für heuer bezahlen'>" . "&euro;</a>";
    }

    if ($row['mi_m_beitr_bez_bis'] >= $curjahr_1) {
        $row[$BM_1] = $Bezahlt;
        # $row[$BMA] = "$NA";
    } else {
        $row[$BM_1] = "$a&b=BM_1' title='MitglBeitr. für nächstes Jahr bezahlen'>" . "&euro;</a>";
        $row[$BMA_1] = "$a&b=BMA_1' title='Mitgliedsbeitrag + Abo für nächstes Jahr bezahlen'>&euro;</a>";
    }

    if ($row['mi_m_abo_bez_bis'] >= $curjahr_1) {
        $row[$BA_1] = $Bezahlt;
        $row[$BMA_1] = "";
    } else {
        $row[$BA_1] = "$a&b=BA_1' title='Abo für nächstes Jahr bezahlen'>" . "&euro;</a>";
    }

    # Überschreibe die Spalten mit den Symbolen für bereits eingezahlt/verbucht oder Einzahlung nicht möglich

    $curdate = date('Y') . '-01-01';

    # $row['Korrektur'] = '';
    if ($row['mi_m_beitr_bez'] >= $curjahr || $row['mi_m_abo_bez'] >= $curjahr) # Wenn für dieses Jahr eine Zahlung gebucht ist - zeige den Korrektur link
    {
        $st_str = "&b=korr";
        if ($row['mi_m_beitr_bez'] >= $curjahr) {
            $st_str .= "&mi_m_beitr_bez=" . ($curjahr - 1);
        }
        if ($row['mi_m_abo_bez'] >= $curjahr) {
            $st_str .= "&mi_m_abo_bez=" . ($curjahr - 1);
        }
        $row['Korrektur'] = "$a" . $st_str . "' title='Einzahlungs Status korrigieren - durch zurücksetzen von KB_bis'>Korr</a>";
    }

    if ($row['mi_m_beitr_bez_bis'] >= $curjahr) { # Mtgliedsbeitrag bezahlt
        $row[$BM] = $Bezahlt;
        $row['mi_m_beitr_bez'] = "<span style='color:green;'>" . $row['mi_m_beitr_bez'] . "</span>";
    } else {
        $row['mi_m_beitr_bez'] = "<span style='color:red;'>" . $row['mi_m_beitr_bez'] . "</span>";
    }
    if ($row['mi_m_abo_bez_bis'] >= $curjahr) {
        $row[$BA] = $Bezahlt; # Abo- Beitrag bezahlt
        $row['mi_m_abo_bez'] = "<span style='color:green;'>" . $row['mi_m_abo_bez'] . "</span>";
    } else {
        $row['mi_m_abo_bez'] = "<span style='color:red;'>" . $row['mi_m_abo_bez'] . "</span>";
    }

    return True;
} # Ende von Function modifyRow

?>