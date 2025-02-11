<?php

/**
 * Auszeichnungs- Verwaltung
 * 
 * @author Josef Rohowsky - neu 2018
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'az_adetail';

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
    $ad_id = $_GET['ID'];
} else {
    $ad_id = $_SESSION[$proj]['ad_id'];
}

if (isset($_POST['ab_id'])) {
    $ab_id = $_POST['ab_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_M_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}

$proj = $_SESSION[$module]['proj'];
$_SESSION[$proj]['ad_id'] = $ad_id;
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
    if ($ad_id == 0) {

        $neu['ad_id'] = $ad_id;
        $neu['ad_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['ad_ab_id'] = $_SESSION['AUSZ']['ab_id'];
        $neu['ad_name'] = $neu['ad_detail'] = $neu['ad_extern'] = $neu['ad_bez'] = $neu['ad_abmesg'] = $neu['ad_band'] = "";
        $neu['ad_vorderseite'] = $neu['ad_rueckseite'] = $neu['ad_stiftg_date'] = $neu['ad_statut'] = $neu['ad_erklaer'] = $neu['ad_aend_uid'] = $neu['ad_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($ad_id != '') {
            $sql .= " WHERE ad_id = '$ad_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $ad_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fw_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der ad_id Nummer $ad_id gefunden</p>";
                }
            }

            VF_HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>\$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}
;
if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['ad_fw_id'] = $_SESSION[$module]['fw_id'];
    # echo '<pre class=debug>';echo '<hr>$neu: '; print_r($neu); echo '</pre>';

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    $uploaddir = $pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";

    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['ad_extern'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['ad_statut'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['ad_erklaer'] = VF_Upload($uploaddir, 3);
    }

    if ($ad_id == 0) { # neueingabe
        $sql = "INSERT INTO $tabelle (
                ad_fw_id , ad_ab_id, ad_name,ad_detail , ad_extern,ad_bez ,ad_abmesg,ad_band,ad_vorderseite,ad_rueckseite,
                ad_stiftg_date, ad_statut, ad_erklaer, ad_aend_uid,ad_aenddat
              ) VALUE (
               '$neu[ad_fw_id]','$neu[ad_ab_id]','$neu[ad_name]','$neu[ad_detail]','$neu[ad_extern]','$neu[ad_bez]','$neu[ad_abmesg]',
               '$neu[ad_band]','$neu[ad_vorderseite]','$neu[ad_rueckseite]',
               '$neu[ad_stiftg_date]','$neu[ad_statut]','$neu[ad_erklaer]','$p_uid',now()
               )";
        $result = SQL_QUERY($db, $sql);
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
            if ($name == "ad_extern1") {
                continue;
            }
            if ($name == "ad_statut2") {
                continue;
            }
            if ($name == "ad_erklaer3") {
                continue;
            }
            if ($name == "tabelle") {
                continue;
            }
            
            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorher keine Update-Strings sind

        $sql = "UPDATE $tabelle SET  $updas WHERE `ad_id`='$ad_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
        $fw_id = $_SESSION[$module]['fw_id'];
        header("Location: VF_PS_OV_M_Edit.php?ID=$fw_id");
    }
    if ($ad_id != "" && $ad_id != "NeuItem") { // NE NeuItem
    } else {}

    $ab_id = $_SESSION[$module]['ab_id'];

    header("Location: VF_PS_OV_AD_Edit.php?ID=$ab_id");
}

BA_HTML_header('Auszeichnungs - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AE_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();
?>