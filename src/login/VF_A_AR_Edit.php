<?php
/**
 * Wartung Archivalien
 *
 * @author josef Rohowsky - neu 2019
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'ARC';
$module = Module_Name;
$tabelle = 'ar_chivdt';

const Prefix = '';

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

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ad_id'])) {
    $ad_id = $_GET['ad_id'];
} else {
    $ad_id = "";
}
if (isset($_GET['ad_id'])) {
    $ad_id = $_GET['ad_id'];
}

if ($phase == 99) {
    header('Location: VF_A_AR_List.php');
}

if ($ad_id !== "") {
    $_SESSION[$module]['ad_id'] = $ad_id;
} else {
    $ad_id = $_SESSION[$module]['ad_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
#$lowHeight = True; // Auswahl-- und Anzeige- Tabellen mit verschiedenen Höhen - je nach Record-Anzahl
$Error_Msg = "";
# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

if ($debug) {
    echo "L 074 <br>";
    print_r($_SESSION['Eigner']['eig_eigner']);
    echo "<bR>eigner<br>";
}


$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";
Tabellen_Spalten_parms($db, $tabelle_a);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    # $Tabellen_Spalten = VF_Tabellen_Spalten_parms($db, $tabelle_a); # lesen der Tabellen Spalten Informationen

    if ($ad_id == 0) {

        $ao_arr = explode(",", $_SESSION[$module]['Ord_Ausw']);
 
        $eignr = $_SESSION['Eigner']['eig_eigner'];

        $neu = array(
            'ad_id' => 0,
            'ad_eignr' => "$eignr",
            "ad_sg" => "$ao_arr[0]",
            'ad_subsg' => "$ao_arr[1]",
            'ad_lcsg' => "$ao_arr[2]",
            'ad_lcssg' => "$ao_arr[3]",
            "ad_ao_fortlnr" => "",
            'ad_doc_date' => "",
            "ad_type" => "",
            "ad_format" => "",
            'ad_keywords' => "",
            "ad_beschreibg" => "",
            "ad_wert_orig" => "0",
            "ad_orig_waehrung" => "",
            "ad_wert_kauf" => "0",
            "ad_kauf_waehrung" => "",
            "ad_wert_besch" => "0",
            'ad_besch_waehrung' => "",
            "ad_namen" => "",
            "ad_doc_1" => "",
            "ad_doc_2" => "",
            "ad_doc_3" => "",
            "ad_doc_4" => "",
            "ad_isbn" => "",
            "ad_lagerort" => "",
            'ad_l_raum' => '',
            'ad_l_kasten' => "",
            'ad_l_fach' => "",
            'ad_l_pos_x' => "",
            'ad_l_pos_y' => "",
            'ad_neueigner' => "",
            'ad_uidaend' => ".",
            'ad_aenddat' => "."
        );
    } else {

        $sql_be = "SELECT * FROM $tabelle_a WHERE `ad_id` = '" . $_SESSION[$module]['ad_id'] . "' ORDER BY `ad_id` ASC";
        $return_be = SQL_QUERY($db, $sql_be); 

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
    }
}

if ($phase == 1) {
    # $debug = True;
    
    foreach ($_POST as $name => $value) {
        $neu[$name] = trim(mysqli_real_escape_string($db, $value));
    }
    # print_r($neu);echo "<br>L 0183 neu <br>";
    if ($debug) {
        echo "<br>\$_POST<br>";
        print_r($_POST);
        echo "<br>\$neu<br>";
        print_r($neu);
        echo "<br>";
    }

    if (isset($neu['level1']) && $neu['ad_id'] == 0) {
        $response = VF_Multi_Sel_Input();
        echo "$response <br>";
        $ao_arr = explode(" ",$response) ;
        $cres = count($ao_arr);
        
        $neu['ad_sg'] = $neu['ad_subsg'] = $neu['ad_lcsg'] = $neu['ad_lcssg'] = $neu['ad_lcssg'] = $neu['ad_lcssg_s0'] = $neu['ad_lcssg_s1'] = '00';
        $neu['ad_sammlg'] = "";
        
        foreach ($ao_arr as $key => $value) {
            switch($key) {
                case 0:
                    $ao = explode(".",$ao_arr[0]);
                    $neu['ad_sg'] = $ao[0];
                    $neu['ad_subsg'] = $ao[1];
                    break;
                case 1:
                    $neu['ad_lcsg'] = $value;
                    break;
                case 2:
                    $neu['ad_lcssg'] = $value;
                    break;
                case 3:
                    $neu['ad_lcssg_s0'] = $value;
                    break;
                case 4:
                    $neu['ad_lcssg_s1'] = $value;
                    break;
            }
            
        }
    } else {
        $neu['ad_lcssg_s0'] = $neu['ad_lcssg_s1'] = '00';
        $Err_Msg ="Archiv- Ordnung nicht ausgewählt";
    }
    
    $sql_where = "";
    if ($neu['ad_lcssg_s1'] != '00') {
        $sql_where = " WHERE al_sg='".$neu['ad_sg'].".".$neu['ad_subsg']."' AND al_lcsg='".$neu['ad_lcsg']."' AND  al_lcssg='".$neu['ad_lcssg']."' AND  al_lcssg_s0='".$neu['ad_lcssg_s0']."' AND  al_lcssg_s1='".$neu['ad_lcssg_s1']."' ";
    } elseif ($neu['ad_lcssg_s0'] != '00') {
        $sql_where = " WHERE al_sg='".$neu['ad_sg'].".".$neu['ad_subsg']."' AND al_lcsg='".$neu['ad_lcsg']."' AND  al_lcssg='".$neu['ad_lcssg']."' AND  al_lcssg_s0='".$neu['ad_lcssg_s0']."' ";
    } elseif ($neu['ad_lcssg'] != '00') {
        $sql_where = " WHERE al_sg='".$neu['ad_sg'].".".$neu['ad_subsg']."' AND al_lcsg='".$neu['ad_lcsg']."' AND  al_lcssg='".$neu['ad_lcssg']."' ";
    } 

    if ($sql_where != '') {
        $sql_aol ="SELECT * FROM ar_ord_local $sql_where ";
        echo "L 0209 $sql_aol <br>";
        $return_aol = SQL_QUERY($db,$sql_aol);
        if ($return_aol) {
            $row_aol = mysqli_fetch_object($return_aol);
            var_dump($row_aol);
            if ($row_aol->al_sammlung != '') {
                $neu['ad_sammlg'] = $row_aol->al_sammlung;
            } else {
                $neu['ad_sammlg'] = '';
            }
        }
       
    }

    If ($neu['ad_beschreibg'] == "") {
        $Error_Msg = "Beschreibung eingeben!";
    }
    if ($neu['ad_doc_date'] == "") {
        $Error_Msg = "Archivalien- Datum eingeben!";
    }
    # if ($debug) {echo "L 151 <br>";}
    if ($Error_Msg != "") {
        $phase = 0;
    } else {
        # if ($debug) {echo "L 155 v3ph1<br>";}
        require "VF_A_AR_Edit_ph1.inc.php";
    }
}

$Eigent = "Eigentümer: " . $_SESSION['Eigner']['eig_eigner'];

if (! $_SESSION['Eigner']['eig_name'] == "") {
    $Eigent .= ", " . $_SESSION['Eigner']['eig_name'];
}
$Eigent .= ", " . $_SESSION['Eigner']['eig_verant'];

$jq = $jqui = True;
$BA_AJA = true;

$header = ""; 
BA_HTML_header('Archiv- Verwaltung <br>'.  $Eigent, $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo " <form id='myform' name='myform' method='post' action='" . $_SERVER['PHP_SELF'] . "' enctype='multipart/form-data' >";

switch ($phase) {
    case 0:
        require ('VF_A_AR_Edit_ph0.inc.php');
        break;
}
 
echo "</form>";

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
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module;

    # print_r($_SESSION[$module]['all_upd']);
    if ($_SESSION[$module]['all_upd']) {
        $s_tab = substr($tabelle, 0, 8);
        # echo "<br/> L 135: \$see[$module][all_upd] \$s_tab $s_tab <br/>";
        switch ($s_tab) {
            case "ar_chivd":
                # $pict_path = "referat5/".$_SESSION[$module]['eignr']."/";
                $pict_path = "AOrd_Verz/" . $row['ad_eignr'] . "/" . $row['ad_sg'] . "/" . $row['ad_subsg'] . "/";
                $vl_id = $row['vl_id'];
                $row['vl_id'] = "<a href='VF_A_AR_Edit.php?vl_id=$vl_id' >" . $vl_id . "</a>";
                # $ei_zust_aus_bild = $row['ei_zust_aus_bild'];
                # $p1 = $pict_path . $ei_zust_aus_bild;
                # $row['ei_zust_aus_bild'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'> Groß </a>";
                break;
        }
    }

    return True;
} # Ende von Function modifyRow

?>