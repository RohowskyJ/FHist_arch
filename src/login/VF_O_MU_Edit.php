<?php

/**
 * Museums- Daten- Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'mu_basis';

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



$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

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
    $mu_id = $_GET['ID'];
} else {
    $mu_id = "";
}
if (isset($_POST['mu_id'])) {
    $mu_id = $_POST['mu_id'];
}

$_SESSION[$module]['mu_id'] = $mu_id;

if ($phase == 99) {
    header('Location: VF_O_MU_List.php?Act=' . $_SESSION[$module]['Act']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$mu_id = $_SESSION[$module]['mu_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($mu_id == 0) {

        $neu['mu_id'] = $mu_id;
        $neu['mu_staat'] = "AT";
        $neu['mu_bdland'] = "NOE";
        $neu['mu_bez'] = $neu['mu_name'] = $neu['mu_bezeichng'] = $neu['mu_adresse_a'] = $neu['mu_plz_a'] = $neu['mu_ort_a'] = "";
        $neu['mu_adresse_p'] = $neu['mu_plz_p'] = $neu['mu_ort_p'] = $neu['mu_eigner'] = "";
        $neu['mu_kustos_titel'] = $neu['mu_kustos_vname'] = $neu['mu_kustos_name'] = $neu['mu_kustos_dgr'] = $neu['mu_kustos_tel'] = "";
        $neu['mu_kustos_fax'] = $neu['mu_kustos_handy'] = $neu['mu_kustos_intern'] = $neu['mu_kustos_email'] = $neu['mu_sammlbeg'] = "";
        $neu['mu_bildnam_1'] = $neu['mu_bildnam_2'] = $neu['mu_mustyp'] = $neu['mu_museigtyp'] = $neu['mu_sammlgschw'] = "";
        $neu['mu_besobj_1'] = $neu['mu_besobj_2'] = $neu['mu_besobj_3'] = $neu['mu_anz_obj'] = $neu['mu_archiv'] = "";
        $neu['mu_protbuch'] = $neu['mu_abzeich'] = $neu['mu_ausruest'] = $neu['mu_kleinger'] = $neu['mu_grossger'] = "";
        $neu['mu_toilett'] = $neu['mu_garderobe'] = $neu['mu_cafe'] = $neu['mu_sonst_anb'] = $neu['mu_rollst'] = "";
        $neu['mu_beheinr'] = $neu['mu_oeffnung'] = $neu['mu_saison'] = $neu['mu_oez_mo'] = $neu['mu_oez_di'] = "";
        $neu['mu_oez_mi'] = $neu['mu_oez_do'] = $neu['mu_oez_fr'] = $neu['mu_oez_sa'] = $neu['mu_oez_so'] = "";
        $neu['mu_oez_fei'] = $neu['mu_f1_titel'] = $neu['mu_f1_vname'] = $neu['mu_f1_name'] = $neu['mu_f1_tel'] = $neu['mu_f1_dgr'] = "";
        $neu['mu_f1_handy'] = $neu['mu_f1_email'] = $neu['mu_f2_titel'] = $neu['mu_f2_vname'] = $neu['mu_f2_name'] = "";
        $neu['mu_f2_dgr'] = $neu['mu_f2_tel'] = $neu['mu_f2_handy'] = $neu['mu_f2_email'] = $neu['mu_uidaend'] = "";
        $neu['mu_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($mu_id != '') {
            $sql .= " WHERE mu_id = '$mu_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $mu_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der mu_id Nummer $mu_id gefunden</p>";
                }
            }
            goto ende;
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

BA_HTML_header('Museums- Daten', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_O_MU_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_O_MU_Edit_ph1.inc.php";
        break;
}
ende:
BA_HTML_trailer();
?>