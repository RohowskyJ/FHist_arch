<?php

/**
 * Fahrzeug- Liste
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'fz_beschr';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.inc';

require $path2ROOT . 'login/common/const.inc';
require $path2ROOT . 'login/common/Funcs.inc';
# require $path2ROOT . 'login/common/Edit_Funcs.inc';
require $path2ROOT . 'login/common/List_Funcs.inc';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc';
require $path2ROOT . 'login/common/M_tab_creat.inc';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

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
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

if (! isset($_SESSION[$module]['sammlung'])) {
    $_SESSION[$module]['sammlung'] = 'B0';
}

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

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
    }
}
if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}
if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['ei_id'])) {
    $ei_id = $_GET['ei_id'];
    VF_Displ_Eig($ei_id);
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}
$_SESSION['Eigner']['eig_eigner'] = $ei_id;

if (isset($_POST['sammlg'])) {
    $sammlg = $_SESSION[$module]['sammlung'] = $_POST['sammlg'];
}

if ($_SESSION['Eigner']['eig_eigner'] == "") {
    $eig_header = "Eigentümer Auswahl";
    require ('VF_Z_E_Select_List.inc');
} else {

    VF_upd();

    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================

    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextEig" => "<a href='VF_FA_FG_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NeuItem" => "<a href='VF_FA_FG_Edit.php?ID=0' > Neuen Datensatz anlegen </a>"
    );

    # ===========================================================================================================
    # Haeder ausgeben
    # ===========================================================================================================
    $title = "Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'];

    $header = "";
    $logo = 'NEIN';
    HTML_header('Fahrzeuge des Eigentümers ' . $_SESSION['Eigner']['eig_eigner'], 'Auswahl', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
    
    echo "<fieldset>";

    List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

    $tabelle .= "_" . $_SESSION['Eigner']['eig_eigner'];

    $tab_typ = "fz_fz_type_" . $_SESSION['Eigner']['eig_eigner'];
    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tab_typ);

    $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

    switch ($T_List) {
        case "Alle":
            $Tabellen_Spalten = array(
                'fz_id',
                'fz_invnr',
                'fz_sammlg',
                'fz_name',
                'fz_taktbez',
                'fz_indienstst',
                'fz_ausdienst',
                'fz_herstell_fg',
                'fz_baujahr',
                'fz_bild_1'
            );

            break;
        default:
            $Tabellen_Spalten = array(
                'fz_id',
                'fz_invnr',
                'fz_sammlg',
                'fz_name',
                'fz_taktbez',
                'fz_indienstst',
                'fz_ausdienst',
                'fz_herstell_fg',
                'fz_baujahr',
                'fz_bild_1'
            );
    }

    $Tabellen_Spalten_style['fz_eignr'] = 
    $Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_baujahr'] = 'text-align:center;';

    $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li>';
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
    List_Action_Bar($tabelle,"Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

    # ===========================================================================================================
    # Die Sammlungs- Auswahl anzeigen:
    # ===========================================================================================================
    
    $sel_sammlg = "<select name='sammlg'>";
    foreach (VF_Sammlung_B0 as $key => $value) {
        $sel_sammlg .= "<option value ='$key'>$value</option>";
    }
    $sel_sammlg .= "</select>";
    
   
    
    $zus_ausw .= "<b>Entweder die Sammlung auswählen </b> oder mit \"Motorkraft\" alle Sammlungen des Eigentümers<br/>";
    $zus_ausw .= "<fieldset><div class='w3-container w3-aqua'>";
    $zus_ausw .= '<div class="label">Referat.Sammlung - Sammlungsbezeichnung</div>';
    
    $zus_ausw .= "$sel_sammlg<br/>";
    
    $zus_ausw .= "</div></fieldset>";
     
    echo $zus_ausw;
    
    
    # ===========================================================================================================
    # Je nach ausgewähltem Radio Button das sql SELECT festlegen
    # ===========================================================================================================

    $return = Cr_n_fz_beschr($tabelle);
    if ($return != True) {
        echo "error: mysqli_errno($return)";
    }

    $sql = "SELECT * FROM $tabelle ";

    $sql_where = "";
    $orderBy = "  ";
    if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '')
        $select_string = $_SESSION[$module]['select_string'];
    if ($_SESSION[$module]['sammlung'] == "B0") {
        $sql_where = " WHERE fz_sammlg >='B0' AND fz_sammlg <='BZ' ";
    } else {
        $sql_where = " WHERE fz_sammlg = '" . $_SESSION[$module]['sammlung'] . "' ";
    }

    $sql .= $sql_where . $orderBy;

    List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
    
    echo "</fieldset>";
    
    HTML_trailer();
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
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_FA_FG_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "fz_besch":
            $fz_id = $row['fz_id'];
            $row['fz_id'] = "<a href='VF_FA_FG_Edit.php?fz_id=$fz_id' >" . $fz_id . "</a>";
            if ($row['fz_bild_1'] != "") {
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";
                # $pictpath = "referat2/" . $_SESSION['Eigner']['eig_eigner'] . "/";

                $fz_bild_1 = $row['fz_bild_1'];
                $p1 = $pict_path . $row['fz_bild_1'];

                $row['fz_bild_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='150px'>  $fz_bild_1  </a>";
            }

            break;
    }

    return True;
} # Ende von Function modifyRow

?>
