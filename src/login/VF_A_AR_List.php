<?php

/**
 * Liste der Archivalien 
 *
 * @author josef Rohowsky - neu 2019
 *
 */
session_start();

# die SESSION aktivieren

const Module_Name = 'ARC';
$module = Module_Name;
if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

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
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

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

$ErrMsg = "";

if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

$sk = $_SESSION['VF_Prim']['SK'];
#var_dump($_POST);
# ==========================================Arc_List=================================================================
# Haeder ausgeben
# ===========================================================================================================

$jq = $jqui = True; // JQ-UI laden
$BA_AJA = True; // AJAX- Scripts laden

$header = "";
$eigner = $_SESSION['Eigner']['eig_eigner'];
BA_HTML_header("Archivalien des Eigentümers $eigner", $header, 'List', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

$_SESSION[$module]['ArOrd_Suchb'] = "";

if (! isset($_SESSION[$module]['Get_Next_Ord'])) {
    $_SESSION[$module]['Get_Next_Ord'] = True;
}
if (! isset($_SESSION[$module]['ArOrd_Name'])) {
    $_SESSION[$module]['ArOrd_Name'] = "Anzeiger der ausgewählten Daten";
}
if (! isset($_SESSION[$module]['Ord_Ausw'])) {
    $_SESSION[$module]['Ord_Ausw'] = "0,0,0,0";
}
if (! isset($_SESSION[$module]['List_Sel'])) {
    $_SESSION[$module]['List_Sel'] = "";
}

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig"  && $_SESSION['VF_Prim']['mode'] == 'Mandanten') {
        $_SESSION['Eigner']['eig_eigner'] = "";
        $_SESSION['Eigner']['eig_name'] = "";
        $_SESSION['Eigner']['eig_verant'] = "";
        $_SESSION['Eigner']['eig_staat'] = "";
        $_SESSION['Eigner']['eig_adr'] = "";
        $_SESSION['Eigner']['eig_ort'] = "";
    }
    if ($_GET['ID'] == "NextArch") {
        $_SESSION[$module]['Get_Next_Ord'] = True;
        $_SESSION[$module]['List_Sel'] = "";
        # $_SESSION[$module]['Ord_Ausw'] = ""; 
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

# ==========================================Arc_List=================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "";

/**
 * Eigentümer- Auswahl (Autocomplete)
*/
if (isset($_POST['eigentuemer_1'])) {
    $ei_id = $_POST['eigentuemer_1'];
    VF_Displ_Eig($ei_id);
    # $_SESSION[$module]['eigname'] = $_POST['auto'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

if ($_SESSION['Eigner']['eig_eigner'] == "") 
{
    if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
        if ($_SESSION['Eigner']['eig_eigner'] == "") {
            VF_Auto_Eigent('E','');
        }
    } else {
        $_SESSION['Eigner']['eig_eigner'] =$_SESSION['VF_Prim']['eignr'];
    }

    echo "<button type='submit' name='phase' value='1' class=green>Auswahl abspeichern</button></p>";
} else {

    VF_upd();
    
    $AO_Sel = VF_Multi_Sel_Input(); 
    # echo "L 0167  AO_Sel $AO_Sel <br>";
    if (isset($AO_Sel) && $AO_Sel != "") {
        $AO_Arr = explode(" ",$AO_Sel);
        $AO_cnt = count($AO_Arr);
        
        if ($AO_Arr[0] != "" && $AO_Arr[0] !='00'){
            $AO_A = explode(".",$AO_Arr[0]);
            $_SESSION[$module]['ad_sg'] = $AO_A[0];
            if (isset($AO_A[1])) {
                $_SESSION[$module]['ad_subsg'] = $AO_A[1];
            }
            
        }
        if ($AO_cnt >= 2) {
            if ($AO_Arr[1] != "" && $AO_Arr[1] !='00'){
                $_SESSION[$module]['ad_lcsg'] = $AO_Arr[1];
            }
        }
        if ($AO_cnt >= 3) {
            if ($AO_Arr[2] != "" && $AO_Arr[2] !='00'){
                $_SESSION[$module]['ad_lcssg'] = $AO_Arr[2];
            }
        }
    }
    
    # ==========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================

    $T_list_texte = array(
        "Anzeige" => $_SESSION[$module]['ArOrd_Name']."(Auswahl)",
        "NextOrd" => "<a href='VF_A_AR_List.php?ID=NextArch' > anderen Archivbereich auswählen </a>",
        "NextEig" => "<a href='VF_A_AR_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NeuItem" => "<a href='VF_A_AR_Edit.php?ad_id=0' target='neu' > Neuen Datensatz anlegen </a>"
    );
    

    List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Daten ändern: Auf die Zahl in Spalte <q>ad_id</q> Klicken.</li>';
    switch ($T_List) {
        case "Alle":

            break;

        default:
    }
    $List_Hinweise .= '</ul></li>';

    # ===========================================================================================================
    # Die Sammlungs- Auswahl anzeigen: Referat ist immer 5
    # ===========================================================================================================
  
    $tabelle_m = $_SESSION[$module]['tabelle_m'] = "ar_chivdt";
    $tabelle = $tabelle_m . "_" . $_SESSION['Eigner']['eig_eigner'];
   
    $_SESSION[$module]['tabelle'] = $tabelle;
    
    $zus_ausw = "";
    List_Action_Bar($tabelle,"Archivalien des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " <br>" . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
    
    if ($_SESSION[$module]['Get_Next_Ord']) {
        
        /**
         * Parameter für den Aufruf von Multi-Dropdown
         *
         * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
         *
         *
         * @var array $MS_Init  Kostante mit den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
         * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
         * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
         *
         * @Input-Parm $_POST['Level1...6']
         */

        $MS_Txt = array(
            'Auswahl der Obersten (ÖBFV)- Ebene  ',
            'Auswahl der 2. ÖBFV- Ebene ',
            'Auswahl der Lokalen Erweiterung (3. Ebene) ',
            'Auswahl des 2. Erweiterung (4. Ebene)  ',
            'Auswahl des 3. Erweiterung (5. Ebene)  ',
            'Auswahl des 4. Erweiterung (6. Ebene)  '
        );
              
        $MS_Lvl   = 4; # 1 ... 6
        $MS_Opt   = 2; # 1: SA für Sammlung, 2: AO für Archivordnung
        
        switch ($MS_Opt) {
            case 1:
                $in_val = 'PA_R';
                $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
            case 2:
                $in_val = '07';
                $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
        }
       
        $titel  = 'Auswahl aus der Archivordnung';
        VF_Multi_Dropdown($in_val,$titel);

    }
    
    $ar_arr = $fo_arr = $fz_arr = $fm_arr = $ge_arr = $in_arr = $zt_arr = array();
    $tables_act = VF_tableExist(); // Array der existierenden Tabellen
                                   # print_r($in_arr);echo "<br>in_arr <br";
    if (! $tables_act) {
        echo "keine Tabellen gefunden - ABBRUCH <br>";
        exit();
    }
    if (array_key_exists($tabelle, $ar_arr)) {  // Tabelle nciht vorhanden, anlegen ar_chivdt_yy und ar_chivord_loc_
    } else {
        $ret_cr = Cr_n_ar_chivdt($tabelle);
    }

    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen
    
    $Tabellen_Spalten_COMMENT['ad_doc_date'] .= " und Nummer";
 
    $Tabellen_Spalten = array(
        'ad_id',
        'ad_doc_date',
        'ad_beschreibg',
        'ad_type',
        'ad_doc_1',
        'ad_keywords'
    );
    $Tabellen_Spalten_style['ad_id'] = $Tabellen_Spalten_style['ad_sg'] = 'text-align:center;';

    $sql = "SELECT * FROM $tabelle ";
    $sql_where = "";
    if ($_SESSION[$module]['List_Sel'] != "") {
        $sql_where = $_SESSION[$module]['List_Sel'];
    }

    if (isset($_SESSION[$module]['ad_sg']) && $_SESSION[$module]['ad_sg'] != "") {
        if ($_SESSION[$module]['ad_sg'] != "" && $_SESSION[$module]['ad_sg'] !='00'){
            $sql_where = " WHERE ad_sg='".$_SESSION[$module]['ad_sg']."' ";
            if (isset($_SESSION[$module]['ad_subsg']) && $_SESSION[$module]['ad_subsg'] != "")  {
                $sql_where .= " AND ad_subsg='".$_SESSION[$module]['ad_subsg']."' ";
            }

        }
        if (isset($_SESSION[$module]['ad_lcsg']) && $_SESSION[$module]['ad_lcsg'] != "" && $_SESSION[$module]['ad_lcsg'] !='00'){
            $sql_where .= " AND ad_lcsg='".$_SESSION[$module]['ad_lcsg']."' ";
        }
        
        if (isset($_SESSION[$module]['ad_lcssg']) && $_SESSION[$module]['ad_lcssg'] != "" && $_SESSION[$module]['ad_lcssg'] !='00'){
            $sql_where .= " AND ad_lcssg='".$_SESSION[$module]['ad_lcssg']."' ";
        }
       
    }
    
    if (! isset($sql_orderBy)) {
        $sql_orderBy = " ";
    }

    if (isset($_GET['col'])) {
        $sql_orderBy = " ORDER BY " . $_GET['col'] . " " . $_GET['sord'];
        $_SESSION[$module]['orderBy'] = $sql_orderBy;
    }
    $_SESSION[$module]['sql_dr'] = $sql . $sql_where;
    $sql .= $sql_where . $sql_orderBy;

    $zus_text = "";
    // Text vor der Tabelle:
    // vollst. Inventar-Nummern- Prefix, ev. incl Sammlg
    // Stücke in Referat:Sammlung/alle Stücke
    $eignr = $_SESSION['Eigner']['eig_eigner'];
    ;
    $pref_eignr = substr("00000", 1, 5 - strlen($eignr)) . $eignr;

    $zus_text = "<div class='w3-container w3-sand'>  ";
    $zus_text .= "<br/>Archivaliennummer Prefix (Verein  Eigentümernummer Archivordnung) <font size='+1'>V:" . $pref_eignr . "</font> und der Nummern der Archivordnung und der Fortlaufenden Nummer <i>Inv.Nr.</i> (Keine Leerzeichen)</div>";
    List_Create($db, $sql,'', $tabelle,'', $zus_text); # die liste ausgeben

    BA_HTML_trailer();
}


/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktionen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
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
    global $path2ROOT, $T_List, $module, $neu;

    $pictPath = "";
    $s_tab = substr($tabelle, 0, 8);
    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_A_AR_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "ar_chivd":
            $ad_id = $row['ad_id'];
            $row['ad_id'] = "<a href='VF_A_AR_Edit.php?ad_id=$ad_id' >" . $ad_id . "</a> <br/>";
            # $row['ad_id'] .= "V:" . $row['ad_eignr'] . "-" . $row['ad_sg'] . "." . $row['ad_subsg'] . "." . $row['ad_lcsg'] . "." . $row['ad_lcssg'] . "-" . $row['ad_ao_fortlnr'];

            if ($row['ad_type'] != "") {
                $ad_type = $row['ad_type'];
                $row['ad_type'] = VF_Arc_Type[$ad_type];
            }
            if ($row['ad_format'] != "") {
                $ad_format = $row['ad_format'];
                $row['ad_type'] = VF_Arc_Format[$ad_format] . "<br/>" . $row['ad_type'];
            }

            $row['ad_doc_date'] .= "<br>V:" . $row['ad_eignr'] . "- " . $row['ad_sg'] . "." . $row['ad_subsg'] . "." . $row['ad_lcsg'] . "." . $row['ad_lcssg'] . "-" . $row['ad_ao_fortlnr'];;
            
            $pictPath = "AOrd_Verz/" . $row['ad_eignr'] . "/" . $row['ad_sg'] . "/" . $row['ad_subsg'] . "/";
            if ($row['ad_doc_1'] != "") {
                $ad_doc_1 = $row['ad_doc_1'];
                $pict = $pictPath . $row['ad_doc_1'];
                $row['ad_doc_1'] = "<a href= '$pict' target='Document' >$ad_doc_1</a>";
            }
            if ($row['ad_doc_2'] != "") {
                $ad_doc_2 = $row['ad_doc_2'];
                $pict = $pictPath . $row['ad_doc_2'];
                $row['ad_doc_1'] .= "<br><a href= '$pict' target='Document' >$ad_doc_2</a>";
            }
            if ($row['ad_doc_3'] != "") {
                $ad_doc_3 = $row['ad_doc_3'];
                $pict = $pictPath . $row['ad_doc_3'];
                $row['ad_doc_1'] .= "<br><a href= '$pict' target='Document' >$ad_doc_3</a>";
            }
            if ($row['ad_doc_4'] != "") {
                $ad_doc_4 = $row['ad_doc_4'];
                $pict = $pictPath . $row['ad_doc_4'];
                $row['ad_doc_1'] .= "<br><a href= '$pict' target='Document' >$ad_doc_4</a>";
            }
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
