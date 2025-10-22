<?php

/**
 * Wartung der Auszeichnungen bei der Feuerwehr
 *
 * @author Josef Rohowsky - neu 2020
 *
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'az_auszeich';

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
    $az_id = $_GET['ID'];
} else {
    $az_id = $_SESSION[$proj]['az_id'];
}
if (isset($_POST['az_ab_id'])) {
    $az_ab_id_id = $_POST['az_ab_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_O_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}

$proj = $_SESSION[$module]['proj'];
$_SESSION[$proj]['az_id'] = $az_id;
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$fw_id = $_SESSION[$module]['fw_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($az_id == 0) {

        $neu['az_id'] = $az_id;
        $neu['az_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['az_ad_id'] = $_SESSION['AUSZ']['ad_id'];
        $neu['az_stufe'] = $neu['az_mat'] = $neu['az_beschr'] = $neu['az_bild_v'] = $neu['az_bild_r'] = $neu['az_bild_m'] = $neu['az_bild_m_r'] = "";
        $neu['az_bild_klsp'] = $neu['az_urkund_1'] = $neu['az_urkund_2'] = $neu['az_aend_uid'] = $neu['az_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($az_id != '') {
            $sql .= " WHERE az_id = '$az_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fw_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der az_id Nummer $az_id gefunden</p>";
                }
            }

            BA_HTML_trailer();
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

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
var_dump($_FILES);
    $neu['az_fw_id'] = $_SESSION[$module]['fw_id'];

    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = $path2ROOT."login/AOrd_Verz/AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
        
        if ($_FILES['uploaddatei_1']['name'] != "" ) {
            $neu['az_bild_v'] = VF_Upload($uploaddir, 'uploaddatei_1');
        }
        if ($_FILES['uploaddatei_2']['name'] != "" ) {
            $neu['az_bild_r'] = VF_Upload($uploaddir, 2);
        }
        if ($_FILES['uploaddatei_3']['name'] != "" ) {
            $neu['az_bild_m'] = VF_Upload($uploaddir, 3);
        }
        if ($_FILES['uploaddatei_4']['name'] != "" ) {
            $neu['az_bild_m_r'] = VF_Upload($uploaddir, 4);
        }
        if ($_FILES['uploaddatei_5']['name'] != "" ) {
            $neu['az_bild_klsp'] = VF_Upload($uploaddir, 5);
        }
        if ($_FILES['uploaddatei_6']['name'] != "" ) {
            $neu['az_urkund_1'] = VF_Upload($uploaddir, 6);
        }
        if ($_FILES['uploaddatei_7']['name'] != "" ) {
            $neu['az_urkund_2'] = VF_Upload($uploaddir, 7);
        }
        
    }
    
    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($az_id == 0) { # Neueingabe
        $sql = "INSERT INTO $tabelle (
                az_fw_id,az_ad_id, az_stufe,az_mat,az_beschr,az_bild_v, az_bild_r,az_bild_m,az_bild_m_r,az_bild_klsp,az_urkund_1,az_urkund_2, az_aend_uid,az_aenddat
              ) VALUE (
               '$neu[az_fw_id]','$neu[az_ad_id]','$neu[az_stufe]','$neu[az_mat]','$neu[az_beschr]','$neu[az_bild_v]','$neu[az_bild_r]','$neu[az_bild_m]','$neu[az_bild_m_r]','$neu[az_bild_klsp]','$neu[az_urkund_1]','$neu[az_urkund_2]','$p_uid',now()
               )";

        $result = SQL_QUERY($db, $sql);

        $ab_id = $_SESSION['AUSZ']['ab_id'];
        header("Location: VF_PS_OV_AD_Edit.php?ID=$ab_id");
    } else { # update
        $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

        foreach ($neu as $name => $value) # für alle Felder aus der tabelle
        {
            if (! preg_match("/[^0-9]/", $name)) {
                continue;
            } # überspringe Numerische Feldnamen
            if ($name == "MAX_FILE_SIZE") {
                continue;
            } #
            if ($name == "phase") {
                continue;
            } #
            if ($name == "az_bild_v1") {
                continue;
            }
            if ($name == "az_bild_r2") {
                continue;
            }
            if ($name == "az_bild_m3") {
                continue;
            }
            if ($name == "az_bild_m_r4") {
                continue;
            }
            if ($name == "az_bild_klsp5") {
                continue;
            }
            if ($name == "az_urkund_16") {
                continue;
            }
            if ($name == "az_urkund_27") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        if ($_SESSION[$module]['all_upd']) {
            $sql = "UPDATE $tabelle SET  $updas WHERE `az_id`='$az_id'";
            if ($debug) {
                echo '<pre class=debug> L 0127: \$sql $sql </pre>';
            }

            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
            $result = SQL_QUERY($db, $sql);
        }
        $fw_id = $_SESSION[$module]['fw_id'];
        header("Location: VF_PS_OV_O_Edit.php?ID=$fw_id");
    }
}

BA_HTML_header('Auszeichnungs - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AZ_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();?>