<?php

/**
 * Mitglieder Verwaltung Liste
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
session_start();

const Module_Name = 'MVW';
$module = Module_Name;
$tabelle = 'fh_mitglieder';

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
$_SESSION[$module]['Return'] = False;

$LinkDB_database  = '';
$db = LinkDB('VFH'); 

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

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
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

$mitgl_nrs = "";
$mitgl_einv_n = 0;

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION['VF']['$select_string'] = $select_string;

initial_debug();
$bez_cnt = $abo_cnt = 0;
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle jemals gemeldeten Mitglieder ( Auswahl ) ",
    "Mitgl" => "Aktive Mitglieder    ( Auswahl ) ",
    "MitglA4" => "Aktive Mitglieder, A4- Liste ( Auswahl ), requ. Dachauer", 
    "nMitgl" => "Nicht aktive Mitglieder      <span style='font-weight:normal;'>[Abgemeldet, Verstorben]</span>",
    "BezL" => "Bezahl- und Abo- Daten Der Aktive Mitglieder ( Auswahl )    ",
    "BezL_W" => "Bezahl- und Abo- Daten Der Aktive Mitglieder ( Auswahl )  W-Spec  ",
    "AdrList" => "Adress-Liste der aktiven Mitglieder, Versand     "

); 
#     "NeuItem" => "<a href='VF_M_Edit.php?ID=0' >Neues Mitglied eingeben</a>"

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Mitglieder Daten";
# $heading = "<h1>Mitglieder Daten aus Tabelle <q>".$tabelle."</q></h1>";

$logo = 'NEIN';
BA_HTML_header('Mitglieder- Verwaltung', '', 'Admin', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

#echo "<fieldset>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

switch ($T_List) {
    case "Alle":
    case "Mitgl":
    case "nMitgl":
        $Tabellen_Spalten = array(
            'mi_id',
            'mi_org_typ',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort',
            'mi_gebtag',
            'mi_tel_handy',
            'mi_fax',
            'mi_email',
            'mi_vorst_funct',
            'mi_ref_leit',
            'mi_ref_int_2',
            'mi_ref_int_3',
            'mi_ref_int_4',
            'mi_einv_art',
            'mi_einversterkl',
            'mi_einv_dat',
            'mi_uidaend',
            'mi_aenddat'
        );
        break;
    case "MitglA4":
        $Tabellen_Spalten = array(
        'mi_id',
        'mi_name',
        'mi_anschr', 
        'mi_gebtag',
        'mi_email',
        'mi_tel_handy',
        'mi_ref_int_2'
            );
        $Tabellen_Spalten_COMMENT['mi_id'] = 'Nr. Typ';
        $Tabellen_Spalten_COMMENT['mi_name'] = 'Name Vorname';
        $Tabellen_Spalten_COMMENT['mi_anschr'] = 'Anschrift';
        $Tabellen_Spalten_COMMENT['mi_gebtag'] = 'Geburtstag Eintrittsdatum';
        $Tabellen_Spalten_COMMENT['Ausg'] = 'Ausg';
        $Tabellen_Spalten_COMMENT['mi_ref_int_2'] = "Interesse Ref2, Ref3, Ref4";
        
        $CSV_Spalten = array(
            'mi_id',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort',
            'mi_gebtag',
            'mi_eintrdat',
            'mi_tel_handy',
            'mi_fax',
            'mi_email',
            'mi_ref_int_2',
            'mi_ref_int_3',
            'mi_ref_int_4',
        );
        
        break;
    case "AdrList":
        $Tabellen_Spalten = array(
            'mi_id',
            'mi_org_typ',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort'
        );
        break;
    case "BezL":
        $Tabellen_Spalten = array(
            'mi_id',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort',
            'mi_m_beitr_bez',
            'mi_m_abo_bez',
            'mi_m_abo_ausg'
        );
        break;
    case "BezL_W":
        $Tabellen_Spalten = array(
            'mi_id',
            'mi_mtyp',
            'aktiv',
            'MB',
            'ABO',
            'ausg',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_tel_handy',
            'mi_email',
            'mi_gebtag',
            'mi_beitritt',
            'mi_austrdat',
            'mi_sterbdat'
        );
        $Tabellen_Spalten_COMMENT['aktiv'] = 'Aktiv';
        $Tabellen_Spalten_COMMENT['MB'] = 'MB';
        $Tabellen_Spalten_COMMENT['ABO'] = 'ABO';
        $Tabellen_Spalten_COMMENT['Ausg'] = 'Ausg';
        break;
    default:
        $Tabellen_Spalten = array(
            'mi_id',
            'mi_org_typ',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name' . 'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort'
        );
}

$Tabellen_Spalten_style['mi_id'] = $Tabellen_Spalten_style['va_datum'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>';
switch ($T_List) {
    case "Mitgl":
    case "nMitgl":
    case "MitglA4":
    case "BezL":

    case "Name":
    case "EMail":

    case "AdrList":
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
/*
 * if ($debug) {echo "<pre class=debug>Tabellen_Spalten "; print_r($Tabellen_Spalten); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_COMMENT "; print_r($Tabellen_Spalten_COMMENT); echo "</pre>";
 * echo "<pre class=debug>Tabellen_Spalten_style "; print_r($Tabellen_Spalten_style); echo "</pre>";
 * }
 */
List_Action_Bar("fh_mitglieder","Mitglieder- Verwaltung - Administrator ", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = $order_By = "";
$sql = "SELECT * FROM fh_mitglieder ";
switch ($T_List) {
    case "Alle":
        $sql_where = " WHERE mi_name!='' ";
        $orderBy = ' ORDER BY mi_id ';
        break;
    case "MitglA4":
    case "Mitgl":
        $sql_where = " WHERE ((mi_austrdat <='0000-00-00' AND mi_sterbdat <='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL)) ";
        $orderBy = ' ORDER BY mi_name';
        break;
    case "BezL_W":
    case "BezL":
        $sql_where = " WHERE ((mi_austrdat<='0000-00-00' AND mi_sterbdat<='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL)) ";
        $orderBy = ' ORDER BY mi_name';
        break;
    case "AdrList":
        $sql_where = " WHERE ((mi_austrdat<='0000-00-00' AND mi_sterbdat<='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL)) ";
        $orderBy = ' ORDER BY mi_name';
        break;
    case "nMitgl":
        $sql_where = " WHERE (mi_austrdat >'0000-00-00' OR mi_sterbdat  >'0000-00-00') ";
        $orderBy = ' ORDER BY mi_name';
        break;

}

if ($select_string != '') {
    switch ($T_List) {

        default:
            $sql_where .= " AND mi_name LIKE '%$select_string%'";
    }
}
$sql .= $sql_where . $orderBy;

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================
if ($T_List == 'AdrList') {
   $for = array("AK","EM") ;
   
   foreach ($for as $grp) {
       $sql = "SELECT * FROM fh_mitglieder ";
       $orderBy = ' ORDER BY mi_name';
       if ($grp == 'AK') {
           $csv_DSN = $path2ROOT . "login/Downloads/Beitragszahler.csv";
           $Kateg_Name = 'Aktive Mitglieder, Beitragszahler';
           $sql_where = " WHERE (((mi_austrdat<='0000-00-00' AND mi_sterbdat<='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL)) AND (mi_mtyp<>'OE' OR mi_mtyp<>'EM'))";
       } else {
           $csv_DSN = $path2ROOT . "login/Downloads/Beitragsfreie.csv";
           $Kateg_Name = 'Aktive Mitglieder, Beitragsbefreit';
           $sql_where = " WHERE (((mi_austrdat<='0000-00-00' AND mi_sterbdat<='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL))  AND (mi_mtyp='OE' OR mi_mtyp='EM')) ";
       }
       $sql .= $sql_where . $orderBy;
       List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
   }

} else {
    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
}


if ($T_List == "BezL" || $T_List == "BezL_W") {
    echo "<div class='w3-content' style='font-size:2em; color:blue;' >";
    echo "$bez_cnt mal Mitgliedsbeiträge bezahlt<br>";
    echo "$abo_cnt mal Abonnementsbeiträge bezahlt<br>";
    echo "</div>";
}

#echo "</fieldset>";

HTML_trailer();

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
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $mitgl_einv_n, $bez_cnt, $abo_cnt;

    $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr
    
    $row_csv = $row;

    $mi_id = $row['mi_id'];
    $row['mi_id'] = "<a href='VF_M_Edit.php?ID=$mi_id'>$mi_id</a>";

    # echo "M_List L 083: \$row['mi_einversterkl'] ".$row['mi_einversterkl']." \$row['mi_id'] ".$row['mi_id']." <br/>";
    $m_einverkl = $row['mi_einversterkl'];
    $color_einv = "";
    if ($m_einverkl == "" || $m_einverkl == "N") {
        # echo "M_List L 087: \$row['mi_einversterkl'] ".$row['mi_einversterkl']."<br/>";
        $color_einv = "bgcolor='#f7ee6a'";
        $row['mi_einversterkl'] = "<a href='VF_M_dsvgo_e.php?mitgl_nr=" . $mi_id . "&mitgl_name=" . $row['mi_name'] . "&mitgl_vname=" . $row['mi_vname'] . "&mitgl_email=" . $row['mi_email'] . "'> Einverst. Ändern</a>";
        $mitgl_einv_n = $mitgl_einv_n + 1;
        # echo "M_List L 091: \$row['mi_einversterkl'] ".$row['mi_einversterkl']."<br/>";
    }

    if ($T_List == "BezL") {
        $Bezahlt = "<b style='color:green;font-size:130%;'>&checkmark;</b>";
        $NA = "<span style='color:red;'>&cross;</span>";

        $curdate = date('Y') . '-01-01';
        if ($row['mi_m_beitr_bez'] > $curdate) { # Mtgliedsbeitrag bezahlt
            $row['mi_m_beitr_bez'] = "<span style='color:green;'>$Bezahlt </span>";
            $bez_cnt ++;
        } else {
            $row['mi_m_beitr_bez'] = "<span style='color:red;'>$NA </span>";
        }
        if ($row['mi_m_abo_bez'] > $curdate) { # Abo- Beitrag bezahlt
            $row['mi_m_abo_bez'] = "<span style='color:green;'> $Bezahlt </span>";
            $abo_cnt ++;
        } else {
            $row['mi_m_abo_bez'] = "<span style='color:red;'>$NA </span>";
        }
      
    }

    if ($T_List == "BezL_W") {
        $Bezahlt = "<b style='color:green;font-size:130%;'>&checkmark;</b>";
        $NA = "<span style='color:red;'>&cross;</span>";

        $curdate = date('Y') . '-01-01';
        if ($row['mi_m_beitr_bez'] > $curdate) { # Mtgliedsbeitrag bezahlt
            $row['MB'] = 1;
            # $row['mi_m_beitr_bez'] = "<span style='color:green;'>$Bezahlt </span>";
            $bez_cnt ++;
        } else {
            $row['MB'] = 0;
            # $row['mi_m_beitr_bez'] = "<span style='color:red;'>$NA </span>";
        }
        if ($row['mi_m_abo_bez'] > $curdate) { # Abo- Beitrag bezahlt
            $row['ABO'] = 1;
            # $row['mi_m_abo_bez'] = "<span style='color:green;'> $Bezahlt </span>";
            $abo_cnt ++;
        } else {
            $row['ABO'] = 0;
            # $row['mi_m_abo_bez'] = "<span style='color:red;'>$NA </span>";
        }
        if ($row['mi_sterbdat'] == '0000-00-00' && $row['mi_austrdat'] == '0000-00-00') {
            $row['aktiv'] = 1;
        }
        if ($row['mi_anschr']) {
            $anschr = $row['mi_plz'] . " " . $row['mi_ort'] . ", " . $row['mi_anschr'];
            $row['mi_anschr'] = $anschr;
        }
        if ($row['mi_name']) {
            $row['mi_name'] .= " " . $row['mi_titel'] . " " . $row['mi_n_titel'];
        }
        # $row['mi_tel_handy'] .= "\n" . $row['mi_handy'];
    }

    if ($T_List == "MitglA4" ) {       
        $mi_id = $row['mi_id'];
        $row['mi_id'] = "$mi_id<hr>".$row['mi_mtyp'];
        $mi_name = $row['mi_name'];
        $row['mi_name'] = "<b>$mi_name</b><br>".$row['mi_vname'];
        $row_csv['mi_name'] = "$mi_name ".$row_csv['mi_vname'];
        $mi_anschr = $row['mi_anschr'];
        $row['mi_anschr'] = $row['mi_plz']." ".$row['mi_ort']."<br>$mi_anschr";
        $row_csv['mi_anschr'] = $row['mi_plz']." ".$row['mi_ort']." $mi_anschr";
        $gebtag = $row['mi_gebtag'];
        $row['mi_gebtag'] = "$gebtag <hr>".$row['mi_beitritt'];
        $row_csv['mi_gebtag'] = "$gebtag  ".$row['mi_beitritt'];
        $refint = $row['mi_ref_int_2'];
        $row['mi_ref_int_2'] = "$refint, ".$row['mi_ref_int_3']." ".$row['mi_ref_int_4'];
        $row_csv['mi_ref_int_2'] = "$refint, ".$row['mi_ref_int_3']." ".$row['mi_ref_int_4'];
    }
    
    if ($T_List == 'Alle' || $T_List == 'nMitgl') {
        if ($row['mi_austrdat'] != "0000-00-00")  {
            $row['mi_name'] .= "<br><b>Ausgetreten: ".$row['mi_austrdat']."</b>";
        }
        if ($row['mi_sterbdat'] != "0000-00-00")  {
            $row['mi_name'] .= "<br><b>Verstorben: ".$row['mi_sterbdat']."</b>";
        }
    }

    return True;
} # Ende von Function modifyRow

?>