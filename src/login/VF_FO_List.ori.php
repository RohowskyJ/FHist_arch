<?php
/**
 * Foto- Verwaltung
 * 
 * @author J. Rohowsky  - neu 2018 Umstellung Urheber 2025
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'fo_todaten';

/**
 * Pfad zum Root- Verzeichnis, wird abgelöst
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

var_dump($_POST);

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - select_string
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
        "LangListe" => "Aus",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Urheber Auswahl";

BA_HTML_header($title, '', 'List', '75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();


if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        #unset($_SESSION[$module]['URHEBER']);
    }
}

if (!isset($_SESSION[$module]['URHEBER'] )) {
    $_SESSION['Eigner']['eig_eigner'] = "";
    $_SESSION[$module]['URHEBER'] = array();
}

if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

if (isset($_GET['typ'])) {
    $fm_typ = $_GET['typ'];
} else {
    $fm_typ = "F";
}

if (isset($_GET['fm_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = $fm_eigner = $_GET['fm_eigner'];
    $eign_ret = VF_Displ_Eig($fm_eigner);
    # VF_Displ_Urheb_n($fm_eigner, $fm_typ);
}

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
    if ($phase  == "2")  {
        if (isset($_POST['ei_id'])) {
            $_SESSION['Eigner']['eig_eigner'] = $_POST['ei_id'];
            $eign_ret = VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);
            
            $urh_kurzz = "";
            if (isset($_POST['urh_kurz']))     {
                $urh_kurz = $_POST['urh_kurz'];
                if (VF_Sel_Eign_Urheb($_POST['ei_id'],$_POST['urh_kurz'])) {
                    var_dump($_SESSION[$module]);
                   
                    require "VF_FO_List.inc.php";
                }
            }
        }
        
        
    }
} else {
    $phase = 0;
}


/*
if (isset($_GET['kurzz'])) { # Urheber- Kurzzeichen gesetzt
    $_SESSION[$module]['U_ku'] = $_GET['kurzz'];
}
*/
if ($phase == 99) {
    unset($_SESSION[$module]['']);
    header("Location: /login/VF_C_Menu.php");
}

VF_upd();


if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;

// Parametr werden in VF_FO_List_Detail verwendet
$_SESSION[$module]['FOTO'] = True;
$_SESSION[$module]['BERI'] = False;

VF_Count_add();

if (! isset($_SESSION['Eigner']['eig_eigner']) or $_SESSION['Eigner']['eig_eigner'] == "" || !isset($SESSION[$module]['URHEBER'])) 
{
    if ($phase != 2 ) {
        $eig_header = "Eigentümer/Urheber Auswahl";
        require 'VF_Z_E_U_Sel_List.inc.php';
    }
   
}
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
 * @global array  $db         Handle Database
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $db,$path2ROOT, $T_List, $module, $fm_eigner;

    $s_tab = substr($tabelle, 0, 8);

    #var_dump($row);
    switch ($s_tab) {
        
        case "fh_urheb":
            $fm_id = $row['fm_id'];
            $fm_typ = $row['fm_typ'];
            
            $row['fm_id'] = "<a href='VF_FO_U_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            $fm_eigner = $row['fm_eigner'];
            if ($row['fm_typ'] == "F") {
                $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=F&fm_id=$fm_id'  target='Foto'>" . $fm_eigner . "  </a> Fotos";
            } elseif ($row['fm_typ'] == "V") {
                $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=V&fm_id=$fm_id'  target='Video'>" . $fm_eigner . " </a> Videos";
            } else {
               # $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=A&fm_id=$fm_id'  target='Audio'>" . $fm_eigner . " </a> Audios";
            }
            # echo "L 0161 eigner $fm_eigner <br>";
            $fm_typ = $row['fm_typ'];
            
            break;
        case "fh_eigen":
            if (!isset($row['Urh_Erw'])) {
                $row['Urh_Erw'] = "";
            }
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<input type='radio' id='$ei_id' name='ei_id' value='$ei_id'><label for id='$ei_id'> &nbsp; $ei_id</label>";
            
            $row['ei_name']  .= " ".$row['ei_vname'];
       
            if ($row['ei_org_typ']  != "Privat" ) { // urh erw auslesen
                $sql_u = "SELECT * FROM fh_eign_urh WHERE  fs_eigner=$ei_id ";
                $ret_u = SQL_QUERY($db,$sql_u);
                WHILE ( $row_u = mysqli_fetch_object($ret_u)) {
                    # var_dump($row_u);
                    console_log($row_u->fs_urh_kurzz);
                    $row['Urh_Erw'] .= "<input type='radio' id='$row_u->fs_urh_kurzz' name='urh_kurz' value='$row_u->fs_urh_kurzz'> <label for= > $row_u->fs_fotograf $row_u->fs_urh_kurzz    </label><br>";
                } 
            } else  {
                
            }
            break;
        case "fo_todat":
            $bpfad = $row['fo_basepath'];
            $zuspfad = $row['fo_zus_pfad'];
            if ($_SESSION[$module]['URHEBER']['ur_media'] == "F") {
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/06/";
            } else {
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/10/";
            }

            $fo_id = $row['fo_id'];
            $verz = "N";
            if ($row['fo_dsn'] == "") {
                $verz = "J";
            }
            $row['fo_id'] = "<a href='VF_FO_Edit.php?fo_id=$fo_id&fo_eigner=" . $row['fo_eigner'] . "&verz=$verz' >" . $fo_id . "</a>";

            $fo_aufn_d   = $row['fo_aufn_datum'];
            $fo_aufn_s   = $row['fo_aufn_suff'];
            $fo_eigner   = $row['fo_eigner'];
            $fo_basepath = $row['fo_basepath'];

            if ($fo_basepath != "") { # Pfad orientiertes Archiv
                $pfad = $fo_basepath . "/";
            } elseif ($fo_aufn_d != "") { # Datums orientertes Archiv (neue Arcive oder Fotoserien
                $pfad = $fo_aufn_d . "/";
            }

            if ($_SESSION[$module]['URHEBER']['ur_media'] == "F") {
                $row['fo_basepath'] = "<a href='VF_FO_List_Detail.php?fo_eigner=$fo_eigner&fo_aufn_d=$fo_aufn_d&fo_aufn_s=$fo_aufn_s&pf=$bpfad&zupf=$zuspfad'  target='_blanc'>" . $fo_aufn_d . " </a> Fotos "; 
            }

            if ($row['fo_dsn'] != "") {
                $dsn = $row['fo_dsn'];
                $d_path = $pict_path . $row['fo_aufn_datum'] . "/";
                if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
                    $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
                } else {
                    $row['fo_dsn'] = "<a href='$pict_path$dsn' target='_blank'>" . $row['fo_dsn'] . "</a>";
                }
            }
            
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
