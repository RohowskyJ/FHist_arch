<?php

/**
 * Wartung der Abzeichen- Beschreibungen
 *
 * @author Josef Rohowsky - neu 2019
 *
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'az_beschreibg';

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
    $ab_id = $_GET['ID'];
} else {
    $ab_id = "";
}
if (isset($_POST['ab_id'])) {
    $ab_id = $_POST['ab_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_O_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$_SESSION[$module]['ab_id'] = $ab_id;
$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($ab_id == 0) {

        $neu['ab_id'] = $ab_id;
        $neu['ab_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['ab_art'] = $neu['ab_beschreibg'] = $neu['ab_stifter'] = $neu['ab_stiftg_datum'] = $neu['ab_statut'] = $neu['ab_erklaerung'] = $neu['ab_aend_uid'] = $neu['ab_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($ab_id != '') {
            $sql .= " WHERE ab_id = '$ab_id'";
        }
        /**
         * @var array $result
         */
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($ab_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Kein Datensatz mit der ab_id Nummer $ab_id gefunden</p>";
                }
            }

            VF_HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);

        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neur);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['ab_fw_id'] = $_SESSION[$module]['fw_id'];

    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = $path2ROOT."login/AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";
        
        if ($_FILES['uploaddatei_1']['name'] != "" ) {
            $neu['ab_statut'] = VF_Upload($uploaddir, 1);
        }
        if ($_FILES['uploaddatei_2']['name'] != "" ) {
            $neu['ab_erklaerung'] = VF_Upload($uploaddir, 2);
        }
    }
  
     
    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
    if ($ab_id == 0) { # neueingabe
        $sql = "INSERT INTO $tabelle (
                ab_fw_id , ab_art ,ab_beschreibg,ab_stifter, ab_stiftg_datum , ab_statut,ab_erklaerung , ab_aend_uid,ab_aenddat
              ) VALUE (
               '$neu[ab_fw_id]','$neu[ab_art]','$neu[ab_beschreibg]','$neu[ab_stifter]','$neu[ab_stiftg_datum]','$neu[ab_statut]','$neu[ab_erklaerung]','$p_uid',now()
              )";

        $result = SQL_QUERY($db, $sql);
        if ($result) {
            $_SESSION[$module]['ab_id'] = mysqli_insert_id($db);
        }
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
            if ($name == "ab_statut1") {
                continue;
            }
            if ($name == "ab_erklaerung2") {
                continue;
            }
            if ($name == "tabelle") {
                continue;
            }
            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $tabelle SET  $updas WHERE `ab_id`='$ab_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);

        $fw_id = $_SESSION[$module]['fw_id'];
        header("Location: VF_PS_OV_AD_Edit.php?ID=$fw_id");
    }

    // inset Y into fw_auszeich
    $fw_id = $_SESSION[$module]['fw_id'];
    $sql_up = "UPDATE  aw_ort_ref SET fw_auszeich='J' WHERE fw_id='$fw_id' ";
    $sqlresult = SQL_QUERY($db, $sql_up);

    $ab_id = $_SESSION[$module]['ab_id'];
    header("Location: VF_PS_OV_AD_Edit.php?ID=$ab_id");
}

BA_HTML_header('Auszeichnungs Beschreibung - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AD_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();
?>