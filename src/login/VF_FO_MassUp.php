<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers 
 * 
 * @author  Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Foto_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');


$prot = True;
$header = "
<style>
        #preview {
            display: flex;
            flex-direction: column;
        }
        .preview-image {
            margin: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .preview-image img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
";
BA_HTML_header('Einlesen von Dokumenten in Tabelle', '', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

VF_chk_valid();

VF_set_module_p();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (!isset($_SESSION[$module]['URHEBER'] )) {
    $_SESSION['Eigner']['eig_eigner'] = "";
    $_SESSION[$module]['Fo']['URHEBER'] = array();
}

if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}


if (isset($_GET['fm_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = $eigner = $fm_eig = $_GET['fm_eigner'];
    VF_Displ_Urheb_n($eigner);
} else {
    if (!isset($_SESSION['Eigner']['eig_eigner']))  {
        $_SESSION['Eigner']['eig_eigner'] = "";
    } 
    $eigner = $_SESSION['Eigner']['eig_eigner'];
    VF_Displ_Urheb_n($eigner);
}

if ($phase == 99) {
    # header('Location: VF_7_FO_M_SelectList_v4.php');
}

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

# VF_Tabellen_Spalten_parms($db, $tabelle);

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}
 #var_dump($_GET);
 #var_dump($_SESSION[$module]);
$_SESSION[$module]['$select_string'] = $select_string;  



if ($phase == 0 AND $_SESSION['Eigner']['eig_eigner'] != "" AND $_SESSION[$module]['Fo']['URHEBER']['fm_typ'] != "") {
    $phase = 1;
}

if ($phase == 1) {
    VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);
    VF_Displ_Urheb_n($_SESSION['Eigner']['eig_eigner']);
}

if ($phase == 2) {
    foreach ($_POST as $key => $value) {
        $_SESSION[$module]['Up_Parm'][$key] = $value;
    }
    
 
    if (isset($_GET['urh_abk'])) {
        $urh_abk = $_GET['urh_abk'];
    } else {
        if (isset($_SESSION[$module]['Fo']['URHEBER']['urh_abk']))  { // Org als Urheber
            $u_cnt= count($_SESSION[$module]['Fo']['URHEBER']['urh_abk']);
            foreach($_SESSION[$module]['Fo']['URHEBER']['urh_abk'] as $key => $value ) {
                if ($u_cnt == 1) {
                    $_SESSION[$module]['Up_Parm']['urh_abk'] = $key;
                    break;
                }
                $urh_abk = $key;
            }
        } else { // Priv als Urheber
            $urh_abk = $_SESSION[$module]['Fo']['URHEBER']['fm_urh_kurzz'];
            $_SESSION[$module]['Up_Parm']['urh_abk']= $_SESSION[$module]['Fo']['URHEBER']['fm_urh_kurzz'];
        }
        
    } 

   #print_r($_SESSION[$module]['Upload']);echo "<br>L 0136: upload <br>";
}

switch ($phase) {
    case 0:
        $eig_header = "Eigentümer- Auswahl zum Hochladen";
        require ('!VF_FO_U_Sel_List.inc.php');
        break;
    case 1:
        require 'VF_FO_MassUp_ph1.inc.php'; // Ziel nach Archiv-Ordnung feststellen, Pfad der Source- Bilder abfragen
        break;
    case 2:
        require 'VF_FO_MassUp_ph2.inc.php'; // Pfade und Tabellen feststellen und die Tabeleninhalte erstellen (lassen)
        break;
}

# echo "<script type='text/javascript' src='VF_C_AOrd_Funcs.js'></script>";

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
/*
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List, $module;

    # echo "L 0173: tabelle $tabelle<br>";

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fo_urheb":
            $fm_id = $row['fm_id'];
            $fm_typ = $row['fm_typ'];
            $fm_eig = $row['fm_eigner'];
            $row['fm_id'] = "<a href='VF_O_FO_MassUp2_Foto_Tabs.php?fm_eig=$fm_eig&fm_typ=$fm_typ' >" . $fm_id . "</a>";
            break;
      
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow
*/
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
    
    # print_r($row);echo "<br>L 0149 row <br>";
    switch ($s_tab) {
        case "fh_urheb":
            $fm_id = $row['fm_id'];
            $fm_typ = $row['fm_typ'];
            
            $fm_eigner = $row['fm_eigner'];
            $row['fm_id'] = "<a href='VF_FO_MassUp2_Tabs.php?fm_eigner=$fm_eigner' >" . $fm_id . "</a>";
            
            if ($row['fm_typ'] == "F") {
                $row['fm_eigner'] = "<a href='VF_FO_MassUp.php?fm_eigner=$fm_eigner&typ=F&fm_id=$fm_id'  target='Foto'>" . $fm_eigner . "  </a> Fotos";
            } elseif ($row['fm_typ'] == "V") {
                $row['fm_eigner'] = "<a href='VF_FO_MassUp.php?fm_eigner=$fm_eigner&typ=V&fm_id=$fm_id'  target='Video'>" . $fm_eigner . " </a> Videos";
            } else {
                # $row['fm_eigner'] = "<a href='VF_FO_List.php?fm_eigner=$fm_eigner&typ=A&fm_id=$fm_id'  target='Audio'>" . $fm_eigner . " </a> Audios";
            }
            # echo "L 0161 eigner $fm_eigner <br>";
            $fm_typ = $row['fm_typ'];
            
            break;
        case "fo_todat":
            $bpfad = $row['fo_basepath'];
            $zuspfad = $row['fo_zus_pfad'];
            if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
                # $pict_path ="../login/AOrd_Verz/".$row['fo_eigner']."/";
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/06/";
            } else {
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/10/";
            }
            
            $fo_id = $row['fo_id'];
            $verz = "N";
            if ($row['fo_dsn'] == "") {
                $verz = "J";
            }
            $row['fo_id'] = "<a href='VF_FO_MassUp.php?fo_id=$fo_id&fo_eigner=" . $row['fo_eigner'] . "&verz=$verz' >" . $fo_id . "</a>";
            
            $fo_aufn_d = $row['fo_aufn_datum'];
            $fo_eigner = $row['fo_eigner'];
            $fo_basepath = $row['fo_basepath'];
            
            if ($fo_basepath != "") { # Pfad orientiertes Archiv
                $pfad = $fo_basepath . "/";
            } elseif ($fo_aufn_d != "") { # Datums orientertes Archiv (neue Arcive oder Fotoserien
                $pfad = $fo_aufn_d . "/";
            }
            
            if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
                $row['fo_basepath'] = "<a href='VF_FO_List_Detail.php?fo_eigner=$fo_eigner&fo_aufn_d=$fo_aufn_d&pf=$bpfad&zupf=$zuspfad'  target='_blanc'>" . $fo_aufn_d . " </a> Fotos ";
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