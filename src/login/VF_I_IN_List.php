<?php

/**
 * Fahrzeug- Liste
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();
echo "<!DOCTYPE html>";
const Module_Name = 'INV';
$module = Module_Name;
$tabelle = ''; # mu_geraet

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

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Spaltennamen
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
        "DropdownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

/**
 * Variablen für Eigentümer und Sammlung initialisieren
 */
if (!isset($_SESSION['Eigner'])) {
    $_SESSION['Eigner'] = array('eig_eigner' => "");
}

/**
 * Haeder ausgeben, body und form
 */

$jq = $jqui = True;
$BA_AJA = True;
$header = "";
BA_HTML_header('Inventar des Eigentümers ' . $_SESSION['Eigner']['eig_eigner'],  $header, 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

VF_chk_valid(); // Test auf gültigen Login- String

VF_set_module_p(); // Setzen Variable für Update, Berechtigung

if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
} else {
    $_SESSION['Eigner']['eig_eigner'] =$_SESSION['VF_Prim']['eignr'];
}

if (! isset($_SESSION[$module]['sammlung'])) {
    $_SESSION[$module]['sammlung'] = 'MU';
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
    header("Location: /login/VF_C_Menu.php");
}

/**
 * neue Sammlung auswählen
 */
if (isset($_GET['ID']) && $_GET['ID'] == "NextSam") {
    $_SESSION[$module]['sammlung'] = "MA";
}

/**
 * neuen Eigentümer auswählen
 */
if (isset($_GET['ID']) && $_GET['ID'] == "NextEig" && $_SESSION['VF_Prim']['mode'] == 'Mandanten') {
    $_SESSION['Eigner']['eig_eigner'] = "";
    $_SESSION[$module]['sammlung'] = '';
} else {
    VF_Displ_Eig($_SESSION['VF_Prim']['eignr']);
}


if (isset($post['select_string'])) {
    $select_string = $postT['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

/**
 * Eigentümer- Auswahl (Autocomplete)
*/
if (isset($_POST['eigentuemer'])) {
    $ei_id = $_POST['eigentuemer'];
    VF_Displ_Eig($ei_id);
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}
#var_dump($_POST);
/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $sammlg = $_SESSION[$module]['sammlung'] = VF_Multi_Sel_Input();
    #echo "L 0150 sammlg $sammlg <br>";
}

/**
 * Eigentümerdaten  oder Sammlung neu einlesen
 */
if ($_SESSION['Eigner']['eig_eigner'] == "" || $_SESSION[$module]['sammlung'] == "") { 
    
    if ($_SESSION['Eigner']['eig_eigner'] == "") {
        VF_Auto_Eigent('E','');
    }
    
    if ($_SESSION[$module]['sammlung'] == ""){
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
            'Auswahl der Sammlungs- Type (1. Ebene)  ',
            'Auswahl der Sammlungs- Gruppe (2. Ebene)  ',
            'Auswahl der Untergrupppe (3. Ebene) ',
            'Auswahl des Spezifikation (4. Ebene)  '
        );
        
        switch ($MS_Opt) {
            case 1:
                $in_val = '';
                $MS_Init = VF_Sel_SA_Such ; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
        }
        
        $titel  = 'Suche nach der Sammlungs- Beschreibung (- oder Änderung der  angezeigten)';
        VF_Multi_Dropdown($in_val,$titel);
        
    }

   echo "<button type='submit' name='phase' value='1' class=green>Auswahl abspeichern</button></p>";
    
} else {
    
    /**
     * Hier erfolgt die Aufteilung nach Fahrzeug oder Gerät
     */
    VF_upd();

    $sql = $sql_where = $orderBy = "";
    
    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================
    if ($_SESSION['VF_Prim']['mode'] == 'Mandanten') {
        $T_list_texte = array(
            "Alle" => "Alle Inventarisierten Gegenstände. ",
            "NextEig" => "<a href='VF_I_IN_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
            "NeuItem" => "<a href='VF_I_IN_Edit.php?in_id=0' target='neu' > Neuen Datensatz anlegen </a>"
        );
    } else {
        $T_list_texte = array(
            "Alle" => "Alle Inventarisierten Gegenstände. ",
            "NeuItem" => "<a href='VF_I_IN_Edit.php?in_id=0' target='neu' > Neuen Datensatz anlegen </a>"
        );
    }

    /*
    # ===========================================================================================================
    # Haeder ausgeben
    # ===========================================================================================================
    $title = "Inventar des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . ", " . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'] . ", " . $_SESSION['Eigner']['eig_adresse'] . ", " . $_SESSION['Eigner']['eig_ort'];
    ;
    
    $header = "";

    $prot = True;
    BA_HTML_header('Inventar des Eigentümers ' . $_SESSION['Eigner']['eig_eigner'], $header, '', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
    */
    $tabelle_m = $_SESSION[$module]['tabelle_m'] = "in_ventar";
    $tabelle = $tabelle_m . "_" . $_SESSION['Eigner']['eig_eigner'];
    List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen
    
    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>in_id</q> Klicken.</li>';
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
    // Text vor der Tabelle:
    // vollst. Inventar-Nummern- Prefix, ev. incl Sammlg
    // Stücke in Referat:Sammlung/alle Stücke
    $eignr = $_SESSION['Eigner']['eig_eigner'];
    $pref_eignr = substr("00000", 1, 5 - strlen($eignr)) . $eignr;
    /*
    if ($_SESSION[$module]['suchausw'] != "") {
        $text = $_SESSION[$module]['sammlung'];
        
        $zus_ausw .= "<div class='w3-container w3-sand'>  Stücke in Sammlung &nbsp; $text ";
        $zus_ausw .= "<br/>Inventarnummer Prefix (Verein  Eigentümernummer Sammlung) <font size='+1'>V$pref_eignr" . $_SESSION[$module]['sammlung'] . "</font> und der Inventar- Nummer in der Spalte <i>Inv.Nr.</i> (Keine Leerzeichen)</div>";
    } else {
        
    */
        $zus_ausw .= "<div class='w3-container w3-sand'> Alle Stücke des Eigentümers ";
        $zus_ausw .= "<br/>Inventarnummer Prefix (Verein Eigentümernummer) <font size=\"+2\">V$pref_eignr</font> und der Inhalt der Spalte <i>in_sammlung</i> und <i>Inv.Nr.</i> (Keine Leerzeichen, InvNr. 6 Stellig, führende 0)</div> ";
    #}
    
    
    List_Action_Bar($tabelle, "Inventar des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

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
        'Auswahl der Sammlungs- Type (1. Ebene) &nbsp;  ',
        'Auswahl der Sammlungs- Gruppe (2. Ebene) &nbsp; ',
        'Auswahl der Untergrupppe (3. Ebene) &nbsp; ',
        'Auswahl der Spezifikation (4. Ebene) &nbsp; '
    );
    
    switch ($MS_Opt) {
        case 1:
            $in_val = '';
            $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
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
    
    $sql = "SELECT * FROM $tabelle ";
    
    $Tabellen_Spalten = array(
        'in_id',
        'in_sammlg',
        'in_bezeichnung',
        'in_hersteller',
        'in_linkerkl',
        'in_foto_1'
    );
    #$Tabellen_Spalten_COMMENT['in_sammlg'] ." ". $Tabellen_Spalten_COMMENT['in_invnr'];
    
    $Tabellen_Spalten_style['in_id'] = 'text-align:center;';
    
    $sql_where = "";
    
    $orderBy = ""; # " ORDER BY in_sammlg ASC ";
    
    if ($_SESSION[$module]['sammlung'] != "" && $_SESSION[$module]['sammlung'] != "Nix") {
        $sql_where = " WHERE in_sammlg LIKE '%".$_SESSION[$module]['sammlung']."%' ";
    }
    
    Cr_n_in_ventar($tabelle);

    # echo "L 0348 sql $sql <br>";
    
    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
    
    echo "</fieldset>";
    
    BA_HTML_trailer();
}

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
    global $path2ROOT, $T_List, $module, $neu;

    $s_tab = substr($tabelle, 0, 8);
    # echo "<br/> L 135: \$s_tab $s_tab <br/>";
    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_I_IN_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "in_venta":
            $in_id = $row['in_id'];
            $row['in_id'] = "<a href='VF_I_IN_Edit.php?in_id=$in_id' >" . $in_id . "</a>";
            if ($row['in_foto_1'] != "") {
                $pictpath = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";

                $in_foto_1 = $row['in_foto_1'];
                $p1 = $pictpath . $row['in_foto_1'];

                $row['in_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$in_foto_1' width='150px'> $in_foto_1 </a>";
                
            }
          
            if ($row['in_neueigner'] > 0) { # OR !is_null($row['in_neueigner'])
                $row['in_bezeichnung'] .= "<br><span class='error'>Abgegeben</span>";
            }
            
            $row['in_sammlg'] .=  " - ".$row['in_invnr'];
            
            break;
    }
 
    return True;
}
# Ende von Function modifyRow

?>
