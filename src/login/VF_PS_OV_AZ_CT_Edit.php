<?php

/**
 * Auszeichnungs- Verwaltung CTIF- Auszeichnungen
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'az_ausz_ctif';

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
// Eingabenerfassung und defauls
// ============================================================================================================

if (isset($_GET['ID'])) {
    $fw_id = $_GET['ID'];
}

$proj = $_SESSION[$module]['proj'];

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $ac_id = $_GET['ID'];
} else {
    $ac_id = $_SESSION[$proj]['ac_id'];
}
# if ( isset( $_POST['ac_id'] ) ) { $ac_id = $_POST['ac_id']; }
if (isset($_POST['ac_ad_id'])) {
    $ac_ad_id = $_POST['ac_ad_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_O_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}

$proj = $_SESSION[$module]['proj'];
$_SESSION[$proj]['ac_id'] = $ac_id;
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$fw_id = $_SESSION[$module]['fw_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle, 'ffhist');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($ac_id == 0) {

        $neu['ac_id'] = $ac_id;
        $neu['ac_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['ac_ab_id'] = $_SESSION['AUSZ']['ab_id'];
        $neu['ac_beschr'] = $neu['ac_wettbsp_v'] = $neu['ac_wettbsp_r'] = "";
        $neu['ac_wettb_dok_v'] = $neu['ac_wettb_dok_r'] = $neu['ac_gr_med_g_dok_v'] = $neu['ac_gr_med_g_dok_r'] = "";

        $neu['ac_gr_med_go_v'] = $neu['ac_gr_med_go_r'] = $neu['ac_gr_med_si_v'] = $neu['ac_gr_med_si_r'] = $neu['ac_gr_med_br_v'] = $neu['ac_gr_med_br_r'] = "";
        $neu['ac_kl_med_go_v'] = $neu['ac_kl_med_go_r'] = $neu['ac_kl_med_si_v'] = $neu['ac_kl_med_si_r'] = $neu['ac_kl_med_br_v'] = $neu['ac_kl_med_br_r'] = "";
        $neu['ac_kl_med_g_dok_v'] = $neu['ac_kl_med_g_dok_r'] = "";
        $neu['ac_so_med_go_v'] = $neu['ac_so_med_go_r'] = $neu['ac_so_med_si_v'] = $neu['ac_so_med_si_r'] = $neu['ac_so_med_br_v'] = $neu['ac_so_med_br_r'] = "";
        $neu['ac_so_med_g_dok_v'] = $neu['ac_so_med_g_dok_r'] = "";
        $neu['ac_so_beschr_1'] = $neu['ac_urkund_1'] = $neu['ac_urk_beschr_1'] = $neu['ac_urkund_2'] = $neu['ac_urk_beschr_2'] = "";
        $neu['ac_fabz_v'] = $neu['ac_fabz_r'] = $neu['ac_teiln_v'] = $neu['ac_teiln_r'] = $neu['ac_aend_uid'] = $neu['ac_aenddat'] = "";
        $neu['ac_fabz_dok_v'] = $neu['ac_fabz_dok_r'] = $neu['ac_teiln_dok_v'] = $neu['ac_teiln_dok_r'] = "";
        # $war = $neu;
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($ac_id != '') {
            $sql .= " WHERE ac_id = '$ac_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fw_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der ac_id Nummer $ac_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}
# echo "E_Edit L 099: \$phase $phase <br/>";
if ($phase == 1) {
    
}

BA_HTML_header('CTIF Auszeichnungs - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AZ_CT_Edit_ph0.inc.php';
        break;
    case 1:
        require 'VF_PS_OV_AZ_CT_Edit_ph1.inc.php';
        break;
}
BA_HTML_trailer();
?>