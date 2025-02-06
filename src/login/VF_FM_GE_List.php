<?php

/**
 * Liste der Geräte eines Eigentümers
 * 
 * @author Josef Rohowsky  neu 2019
 * 
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 * 
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;

$tabelle_m = "mu_geraet_";

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter
$debug = True;  
require $path2ROOT . 'login/common/VF_Comm_Funcs.inc.php';

require $path2ROOT . 'login/common/const.inc';
require $path2ROOT . 'login/common/Funcs.inc.php';
require $path2ROOT . 'login/common/Edit_Funcs.inc.php';
require $path2ROOT . 'login/common/List_Funcs.inc';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc';
require $path2ROOT . 'login/common/M_tab_creat.inc.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Muskelbewegte Fahrzeuge ud Geräte des Eigentümers " . $_SESSION['Eigner']['eig_eigner'];

$header = "";
$logo = 'NEIN';
$prot = True;
HTML_header('Geräte des Eigentümers ' . $_SESSION['Eigner']['eig_eigner'], 'Auswahl', '$header', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Spaltennamen
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

$ErrMsg = "";

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

$sk = $_SESSION['VF_Prim']['SK'];

if (! isset($_SESSION[$module]['sel_sammlung'])) {
    $_SESSION[$module]['sel_sammlung'] = '';
}
if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

foreach ($_POST as $name => $value) {
    $post[$name] = $value;
}


if (isset($post['phase'])) {
    $phase = $post['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: $path2ROOT/login/VF_C_Menu.php");
}

if (! isset($_SESSION[$module]['sammlung'])) {
    $_SESSION[$module]['sammlung'] = 'MU';
}

$csv_DSN = "";

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";        
        $_SESSION[$module]['sammlung'] = 'MU';
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;


if (isset($_POST['auto']) ) {
    $ei_arr = explode("-",$_POST['auto']);
    $ei_id = $ei_arr[0];
    VF_Displ_Eig($ei_id);
    $_SESSION[$module]['eigname'] = $_POST['auto'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

if (isset($_GET['ei_id'])) {
    $ei_id = $_GET['ei_id'];
    $_SESSION['Eigner']['eig_eignr'] = $ei_id;
    $ei_id = VF_Displ_Eig($ei_id);
    $_SESSION[$module]['sammlung'] = 'MU';
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}
$_SESSION['Eigner']['eig_eigner'] = $ei_id;

/**
 * Sammlung auswählen
 */
if (isset($post['level1']) && ($post['level1'] != "" ) ) {
    $post['sammlg'] = $post['level1'];
} else {
    $post['level2'] = $post['level3'] = $post['level4'] = "";
}

if (isset($post['level2']) && ($post['level2'] != "" ) ) {
    if ($post['level2'] == "Nix") {
        $post['sammlg'] = $post['level1'];
    } else {
        $post['sammlg'] = $post['level2'];
    }
} else {
    $post['level3'] = $post['level4'] = "";
}

if (isset($post['level3']) && ($post['level3'] != "" ) ) {
    if ($post['level3'] == "Nix") {
        $post['sammlg'] = $post['level2'];
    } else {
        $post['sammlg'] = $post['level3'];
    }
    
} else {
    $post['level4'] = "";
}

if (isset($post['level4']) && ($post['level4'] != "") ) {
    $post['sammlg'] = $post['level4'];
}


if (isset($post['sammlg'])) {
    $_SESSION[$module]['sel_sammlung'] = $post['sammlg'];
}

if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if ($_SESSION['Eigner']['eig_eigner'] == "" || $_SESSION[$module]['sammlung'] == "MU") {
    
    if ($_SESSION['Eigner']['eig_eigner'] == "") {
        VF_Eig_Ausw();
    }
    
    if ($_SESSION[$module]['sammlung'] == "MU"){
        /**
         * Parameter für den Aufruf von Multi-Dropdown
         *
         * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
         *
         *
         * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
         * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
         * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
         *
         * @Input-Parm $_POST['Level1...6']
         */
        
        $MS_Lvl   = 1; # 1 ... 6
        $MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung
        
        $MS_Txt = array(
            'Auswahl der Sammlungs- Type (1. Ebene) &nbsp;  ',
            'Auswahl der Sammlungs- Gruppe (2. Ebene) &nbsp; ',
            'Auswahl der Untergrupppe (3. Ebene) &nbsp; ',
            'Auswahl des Spezifikation (4. Ebene) &nbsp; '
        );
        
        switch ($MS_Opt) {
            case 1:
                $in_val = '';
                $MS_Init = VF_Sel_SA_MU_G ; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
                /*
                 case 2:
                 $in_val = '07';
                 $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
                 break;
                 */
        }
        
        $titel  = 'Suche nach der Sammlungs- Beschreibung (- oder Änderung der  angezeigten)';
        VF_Multi_Dropdown($in_val,$titel);
        
    }
    
    echo "<button type='submit' name='phase' value='1' class=green>Auswahl abspeichern</button></p>";
    
} else {
    $tabelle = $tabelle_m.$_SESSION['Eigner']['eig_eigner'];
    echo "L 0173 tabelle $tabelle <br>";
    VF_upd();

    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================
  
    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextEig" => "<a href='VF_FM_GE_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NeuItem" => "<a href='VF_FM_MU_Edit.php?fm_id=0' target='neu' > Neuen Datensatz anlegen </a>"
    );

    echo "<fieldset>";
    
    List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>*_id</q> Klicken.</li>';
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

    $eig_data = VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);

    List_Action_Bar($tabelle,"Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_org'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben
    
    # ===========================================================================================================
    # Die Sammlungs- Auswahl anzeigen:
    # ===========================================================================================================
    
    /**
     * Parameter für den Aufruf von Multi-Dropdown
     *
     * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
     *
     *
     * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
     * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
     * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
     *
     * @Input-Parm $_POST['Level1...6']
     */
    
    $MS_Lvl   = 4; # 1 ... 6
    $MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung
    
    $MS_Txt = array(
        'Auswahl der Sammlungs- Type (1. Ebene)  ',
        'Auswahl der Sammlungs- Gruppe (2. Ebene)  ',
        'Auswahl der Untergrupppe (3. Ebene) ',
        'Auswahl der Spezifikation (4. Ebene)  '
    );
    
    switch ($MS_Opt) {
        case 1:
            $in_val = 'MU_G';
            $MS_Init = VF_Sel_SA_MU_G; # VF_Sel_SA_Such|VF_Sel_AOrd
            break;
            /*
             case 2:
             $in_val = '07';
             $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
             break;
             */
    }
    
    
    $titel  = 'Welche Sammlung soll angezeigt werden: ';
    VF_Multi_Dropdown($in_val,$titel);
    
 
        $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen


            $return = Cr_n_mu_fahrzeug($tabelle);
            if ($return != True) {
                echo "$tabelle error: mysqli_errno($return)";
            }


        $sql = "SELECT * FROM $tabelle ";
        $sql_where = "";
        $sql_orderBy = "";
    
                $Tabellen_Spalten = array(
                    'fm_id',
                    'fm_bezeich',
                    'fm_type',
                    'fm_indienst',
                    'fm_ausdienst',
                    'fm_herst',
                    'fm_baujahr',
                    'fm_sammlg',
                    'fm_foto_1'
                );
                $Tabellen_Spalten_style['fm_eignr'] = $Tabellen_Spalten_style['fm_id'] = $Tabellen_Spalten_style['fm_baujahr'] = 'text-align:center;';
                # dzt nicht $sql_where=" WHERE fm_sammlg = '".$_SESSION[$module]['sammlung']."' " ;
     
                $sql_where = " WHERE fm_sammlg LIKE '%".$_SESSION[$module]['sel_sammlung']."%' ";

                $orderBy = "  ";
   
        
        $sql .= $sql_where . $sql_orderBy;

        List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
        
        echo "</fieldset>";
        
        HTML_trailer();
    }


/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
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
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_FM_GE_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "fz_muske":
            $fm_id = $row['fm_id'];
            $row['fm_id'] = "<a href='VF_FM_MU_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            if ($row['fm_foto_1'] != "") {
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaG/";
                $fm_foto_1 = $row['fm_foto_1'];
                $p1 = $pict_path . $row['fm_foto_1'];

                $row['fm_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'>  $fm_foto_1  </a>";
            }
            break;
 
    }

    return True;
} # Ende von Function modifyRow

?>
