<?php

/**
 * Fahrzeug- Liste, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'ma_fahrzeug';

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
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';
require $path2ROOT . 'login/common/BA_AJAX_Funcs.lib.php';

$flow_list = True;
if ($flow_list) {
    flow_add($module,"BA_MA_Edit.php Funct: BA_Auto_Compl" );
}
$LinkDB_database  = '';
$db = LinkDB('VFH');

$prot = True;
$header = "<style>.button-sm {font-size:14px;font-weight:bold;color:black ;padding:0px 6px 0px 4px;margin:1px;
             background-color:#FFF0F5;border:2px solid blue;border-radius:2px}</style>";

$A_Off = True;  # set autocomplete=off in Header

BA_HTML_header('Fahrzeug- und Geräte- Verwaltung', $header, 'Form', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    $fz_id = $_GET['ID'];
} else {
    $fz_id = "";
}
if (isset($_GET['fz_id'])) {
    $fz_id = $_GET['fz_id'];
}

if ($phase == 99) {
    header('Location: VF_FZ_List.php');
}

if ($fz_id != "") {
    $_SESSION[$module]['fz_id'] = $fz_id;
} else {
    $fz_id = $_SESSION[$module]['fz_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------


$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

Tabellen_Spalten_parms($db, $tabelle_a);

if ($_SESSION[$module]['all_upd']) {
    $edit_allow = 1;
    $read_only = "";
} else {
    $edit_allow = 0;
    $read_only = "readonly";
}

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    $Tabellen_Spalten_COMMENT['ct_juroren'] = 'Juroren';
    $Tabellen_Spalten_COMMENT['ct_darstjahr'] = 'Darstellungs- Jahr';
    if ($_SESSION[$module]['fz_id'] == 0) {

        $neu = array(
            'fz_id' => 0,
            'fz_eignr' => $eignr,
            "fz_invnr" => "0",
            'fz_sammlg' => $_SESSION[$module]['sammlung'],
            "sa_name" => "Kraftfahrzeug",
            'fz_taktbez' => "",
            'fz_hist_bezeichng' => '',
            "fz_baujahr" => "",
            'fz_indienstst' => "",
            'fz_ausdienst' => "",
            'fz_zeitraum' => "",
            'fz_allg_beschr' => '',
            'fz_herstell_fg' => '',
            'fz_motor' => '',
            'fz_antrieb' => '',
            'fz_typ' =>'',
            'fz_modell' => '',
            'fz_geschwindigkeit' => '',
            'fz_aufbauer' => '',
            'fz_aufb_typ' => '',
            'fz_besatzung' => '',
            'fz_bild_1' => "",
            'fz_b_1_komm' => "",
            'fz_bild_2' => "",
            'fz_b_2_komm' => "",
            'fz_bild_3' => "",
            'fz_b_3_komm' => "",
            'fz_bild_4' => "",
            'fz_b_4_komm' => "",
            'fz_zustand' => "",
            'fz_ctif_klass' => "",
            'fz_ctif_date' => "",
            'fz_ctif_juroren' => '',
            'fz_ctif_darst_jahr' => '',
            "fz_beschreibg_det" => "",
            "fz_eigent_freig" => "",
            "fz_verfueg_freig" => "",
            "fz_pruefg_id" => "",
            "fz_pruefg" => "",
            'fz_l_tank' => '',
            'fz_l_monitor' => '',
            'fz_l_pumpe' =>   '',
            'fz_t_kran' => '',
            'fz_t_winde' => '', 
            'fz_t_leiter' => '',
            'fz_t_leiter' => '',
            'fz_t_abschlepp' => '',
            'fz_t_beleuchtg' => '',
            'fz_t_strom' => '',
            'fz_g_atemsch' => '',
            "fz_aenduid" => "",
            "fz_aenddat" => "",
            "ab_bezeichn" => ""
            
        );
    } else {

        #$sql_be = "SELECT * FROM $tabelle_a WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        
        $sql_be = "SELECT *
        FROM $tabelle_a                 \n
        LEFT JOIN fh_sammlung ON $tabelle_a.fz_sammlg = fh_sammlung.sa_sammlg \n
        LEFT JOIN fh_abk ON $tabelle_a.fz_taktbez = fh_abk.ab_abk    \n
        WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        
        # echo "L 0174 sql_be $sql_be <br>";
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);

        mysqli_free_result($return_be);
        
        $_SESSION[$module]['fz_id_a'] = $neu['fz_id'];
        if ($neu['fz_sammlg'] != "") {
            $_SESSION[$module]['fz_sammlg'] = $neu['fz_sammlg'];
        }

    }
    $neu['fo_org'] = 'L';
    $Tabellen_Spalten['fo_org'] = 'fo_ausw';
    $Tabellen_Spalten_COMMENT['fo_org'] = 'Foto Ursprung ';
}

if ($phase == 1) {
    
}

switch ($phase) {
    case 0:
        require ('VF_FZ_MA_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FZ_MA_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();

/**
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean
 */
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
    $tabelle)
{
    global $path2VF, $T_List, $module, $neu;
    # echo "L 86: \$tabelle $tabelle <br/>";
    $tab_abk = substr($tabelle, 0, 8);
    # echo "L 023: \$tab_abk $tab_abk <br/>";
     if ($tab_abk == "ma_eigne") {
         $fz_eign_id = $row['fz_eign_id'];
         $row['fz_eign_id'] = "<a href='VF_FZ_EI_Edit.php?ID=$fz_eign_id' >" . $fz_eign_id . "</a>";
     }
     
     return True;
} # Ende von Function modifyRow

?>