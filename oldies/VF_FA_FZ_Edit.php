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
$tabelle = 'ma_fz_beschr';

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

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$prot = True;
$header = "";

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
    header('Location: VF_FA_List.php');
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
            'fz_eignr' => "",
            "fz_invnr" => "0",
            "sa_name" => "Kraftfahrzeug",
            'fz_name' => "",
            'fz_taktbez' => "",
            'fz_indienstst' => "",
            'fz_ausdienst' => "",
            'fz_zeitraum' => "",
            "fz_komment" => "",
            'fz_sammlg' => $_SESSION[$module]['sammlung'],
            'fz_bild_1' => "",
            'fz_b_1_komm' => "",
            'fz_bild_2' => "",
            'fz_b_2_komm' => "",
            'fz_zustand' => "",
            'fz_ctifklass' => "",
            'fz_ctifdate' => "",
            "fz_beschreibg_det" => "",
            "fz_eigent_freig" => "",
            "fz_verfueg_freig" => "",
            "fz_pruefg_id" => "",
            "fz_pruefg" => "",
            "fz_aenduid" => "",
            "fz_aenddat" => "",
            "ct_darstjahr" => "",
            "ct_juroren" => "",
            "fz_herstell_fg" => "",
            "fz_baujahr" => ""
        );
    } else {

        #$sql_be = "SELECT * FROM $tabelle_a WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        
        $sql_be = "SELECT *
        FROM $tabelle_a
        LEFT JOIN fh_sammlung ON $tabelle_a.fz_sammlg = fh_sammlung.sa_sammlg 
        WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' OR fh_sammlung.sa_sammlg IS NULL ORDER BY `fz_id` ASC";
        
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
        
        $_SESSION[$module]['fz_id_a'] = $neu['fz_id'];
        if ($neu['fz_sammlg'] != "") {
            $_SESSION[$module]['fz_sammlg'] = $neu['fz_sammlg'];
        }

        $sql_in = "SELECT * FROM fz_ctif_klass WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "' AND `fz_eignr`='" . $_SESSION['Eigner']['eig_eigner'] . "'";
        $return_in = SQL_QUERY($db, $sql_in);
        $num_rows = mysqli_num_rows($return_in);
        if ($num_rows >= 1) {
            while ($row = mysqli_fetch_object($return_in)) {
                $neu['ct_juroren'] = $row->fz_juroren;
                $neu['ct_darstjahr'] = $row->fz_darstjahr;
            }
        } else {
            $neu['ct_darstjahr'] = '';
            $neu['ct_juroren'] = " ";
        }
    }
}

if ($phase == 1) {
    
}

switch ($phase) {
    case 0:
        require ('VF_FA_FZ_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FA_FZ_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();

/**
 * 
 * @param array $row
 * @param string $tabelle
 * @return boolean
 */
function modifyRow_n(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
    $tabelle)
{
    global $path2VF, $T_List, $module, $neu;
    # echo "L 86: \$tabelle $tabelle <br/>";
    $tab_abk = substr($tabelle, 0, 8);
    # echo "L 023: \$tab_abk $tab_abk <br/>";
    /*
    if ($tab_abk == "fz_fz_ty") {
        # echo "L 56: fz_fz_type \$tabelle $tabelle Sammlung ".$_SESSION[$module]['sammlung']."<br/>";
        $ft_id = $row['ft_id'];
        
        if  (substr($_SESSION[$module]['sammlung'],0,7) == 'MA_F-AH') {
            $row['ft_id'] = "<a href='VF_2_BH_TS_Edit.php?ID=$ft_id' >" . $ft_id . "</a>";
        } elseif (substr($_SESSION[$module]['sammlung'],0,4) == 'MA_F' ) {
            $row['ft_id'] = "<a href='VF_FA_BA_TS_Edit.php?ID=$ft_id' >" . $ft_id . "</a>";
        }
    } else
        */
    if ($tab_abk == "ma_eigne") {
        $fz_eign_id = $row['fz_eign_id'];
        $row['fz_eign_id'] = "<a href='VF_FZ_EI_Edit.php?ID=$fz_eign_id' >" . $fz_eign_id . "</a>";
        /*
    } elseif ($tab_abk == "fz_lader") {
        $lr_id = $row['lr_id'];
        $row['lr_id'] = "<a href='VF_FA_BA_GR_Edit.php?ID=$lr_id' >" . $lr_id . "</a>";
        $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";
        # $defjahr = date("Y"); // Beitragsjahr, .ist Gegenwärtiges Jahr
        
        $lr_foto_1 = $row['lr_foto_1'];
        
        $pict = $pict_path . $lr_foto_1;
        
        # $row['lr_foto_1'] = "<img src='$pict' alt='Foto Laderaum' width='70em' >";
        $row['lr_foto_1'] = "<a href='$pict' target='_blanc' > <img src='$pict' alter='$pict' width='70px'>  $lr_foto_1  </a>";
    } elseif ($tab_abk == "fz_repar") {
        $fz_rep_id = $row['fz_rep_id'];
        $row['fz_rep_id'] = "<a href='VF_FA_BA_RE_Edit.php?ID=$fz_rep_id' >" . $fz_rep_id . "</a>";
    } elseif ($tab_abk == "fz_typis") {
        $fz_typ_id = $row['fz_typ_id'];
        $row['fz_typ_id'] = "<a href='VF_FA_BA_UT_Edit.php?ID=$fz_typ_id' >" . $fz_typ_id . "</a>";
    } elseif ($tab_abk == "fz_fixei") {
        $fz_einb_id = $row['fz_einb_id'];
        $row['fz_einb_id'] = "<a href='VF_FA_BA_EB_Edit.php?ID=$fz_einb_id' >" . $fz_einb_id . "</a>";
        $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";
        $fz_ger_foto_1 = $row['fz_ger_foto_1'];
        
        $pict = $pict_path . $fz_ger_foto_1;
        
        # $row['lr_foto_1'] = "<img src='$pict' alt='Foto Laderaum' width='70em' >";
        $row['fz_ger_foto_1'] = "<a href='$pict' target='_blanc' > <img src='$pict' alter='$pict' width='70px'>  $fz_ger_foto_1  </a>";
    } elseif ($tab_abk == "ar_chivd") {
        $ad_id = $row['ad_id'];
        $eignr = $row['ad_eignr'];
        # $row['ad_id'] = "<a href='referat5/$eignr/".$row['ad_sg']."/".$row['ad_subsg']."/".$row['ad_doc_1']."' >".$ad_id."</a>" ;
        
        $ad_doc1 = $row['ad_doc_1'];
        $ad_doc = "<a href='AOrd_Verz/" . $row['ad_eignr'] . "/" . $row['ad_sg'] . "/" . $row['ad_subsg'] . "/" . $row['ad_doc_1'] . "' target=_Archiv>$ad_doc1</a>";
        # print_r($row);
        # echo "<br>ba_ar_list L 083: ad_doc $ad_doc </br>";
        $row['ad_id'] = "<a href='VF_FA_BA_AR_Edit.php?ID=$ad_id' >" . $ad_id . "</a>";
        $row['ad_beschreibg'] = $row['ad_beschreibg'] . "<br/>" . $ad_doc;
        */
    }
    
    return True;
} # Ende von Function modifyRow

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_Lists.inc beendet</pre>";
}
?>
