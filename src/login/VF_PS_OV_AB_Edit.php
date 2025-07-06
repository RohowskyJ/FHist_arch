<?php
/**
 * Wartung der Ärmelabzeichen bei der Feuerwehr
 *
 * @author Josef Rohowsky - neu 2020
 *
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'aw_aermel_abz';

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
    $fo_id = $_GET['ID'];
}
# if (isset($_GET['pgm_id'])) {$pgm_id = $_GET['pgm_id'];} else {$pgm_id = $_SESSION['VF']['pgm_id'];}

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $fo_id = $_GET['ID'];
} else {
    $fo_id = "";
}
if (isset($_POST['fo_id'])) {
    $fo_id = $_POST['fo_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_O_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}
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
    if ($fo_id == 0) {
        $neu['fo_id'] = $fo_id;
        $neu['fo_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['fo_ff_abzeich'] = $neu['fo_ff_a_sort'] = $neu['fo_ff_a_typ_a'] = $neu['fo_ff_abz_typ'] = $neu['fo_aenduid'] = $neu['fo_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($fo_id != '') {
            $sql .= " WHERE fo_id = '$fo_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fo_id Nummer $fo_id gefunden</p>";
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

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['fo_fw_id'] = $_SESSION[$module]['fw_id'];

    $uploaddir = $Path2ROOT."login/AOrd_Verz/PSA/AERM/Aermel_Abz/";

    if (isset($_FILES['uploaddatei_1']) && $_FILES['uploaddatei_1']['name'] != "") {
        $neu['fo_ff_abzeich'] =  VF_Upload_Pic('fo_ff_abzeich', $uploaddir, "", "");
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($fo_id == 0) { # Neueingabe
        $sql = "INSERT INTO $tabelle (
                fo_fw_id , fo_ff_abzeich, fo_ff_a_sort , fo_ff_a_typ_a,fo_ff_abz_typ , fo_aenduid,fo_aenddat
              ) VALUE (
               '$neu[fo_fw_id]','$neu[fo_ff_abzeich]','$neu[fo_ff_a_sort]','$neu[fo_ff_a_typ_a]','$neu[fo_ff_abz_typ]','$p_uid',now()
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
            if ($name == "fo_ff_abzeich1") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        if ($_SESSION[$module]['all_upd']) {
            $sql = "UPDATE $tabelle SET  $updas WHERE `fo_id`='$fo_id'";
            if ($debug) {
                echo '<pre class=debug> L 0127: \$sql $sql </pre>';
            }

            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
            $result = SQL_QUERY($db, $sql);
        }
        $fw_id = $_SESSION[$module]['fw_id'];
        header("Location: VF_PS_OV_M_Edit.php?ID=$fw_id");
    }

    $fw_id = $_SESSION[$module]['fw_id'];

    $sql_up = "UPDATE  aw_ort_ref SET fw_aermelw='J' WHERE fw_id='$fw_id' ";
    $sqlresult = SQL_QUERY($db, $sql_up);

    header("Location: VF_PS_OV_O_Edit.php?ID=$fw_id");
}

BA_HTML_header('Orts- Wappen - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_PS_OV_AB_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();
?>