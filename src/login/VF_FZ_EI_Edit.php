<?php

/**
 * Fahrzeuge, Typenschein, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'ma_eigner';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = false;

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
if (isset($_GET['ID'])) {
    $fz_eign_id = $_GET['ID'];
} else {
    $fz_eign_id = "";
}

if ($phase == 99) {
    header('Location: VF_FA_FZ_Edit.php?fw_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!

$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

$table_ei = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];
Tabellen_Spalten_parms($db, $table_ei);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fz_eign_id == 0) {
        $neu['fz_eign_id'] = 0;
        $neu['fz_id'] = $fz_id;
        $neu['fz_docbez'] = $neu['fz_zul_dat'] = $neu['fz_zul_end_dat'] = $neu['fz_zuldaten'] = $neu['fz_uidaend'] = $neu['fz_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_ei ";

        if ($fz_eign_id != '') {
            $sql .= " WHERE fz_eign_id =$fz_eign_id";
        } else {
            $sql .= "  WHERE fz_id = '$fz_id' ";
        }

        $result = SQL_QUERY($db, $sql);

        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fz_eign_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fz_eign_id Nummer $fz_eign_id gefunden</p>";
                }

                HTML_trailer();
                exit();
            }
        }
        $neu = mysqli_fetch_array($result);
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($nu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 105: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($neu['fz_eign_id'] == 0) { # nueingabe

        if (Cr_n_ma_eigner($table_ei)) {

        }
        $sql = "INSERT INTO $table_ei (
               fz_id,fz_docbez,fz_zul_dat,fz_zul_end_dat,fz_zuldaten,fz_uidaend
              ) VALUE (
               '$neu[fz_id]','$neu[fz_docbez]','$neu[fz_zul_dat]','$neu[fz_zul_end_dat]','$neu[fz_zuldaten]','$p_uid'
               )";

        $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));

        $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

        foreach ($neu as $name => $value) { # für alle Felder aus der tabelle
            if (! preg_match("/[^0-9]/", $name)) {
                continue;
            } # überspringe Numerische Feldnamen
            if ($name == "MAX_FILE_SIZE") {
                continue;
            } #
            if ($name == "phase") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_ei SET  $updas WHERE `fz_eign_id`='" . $neu['fz_eign_id'] . "'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);

        $fz_id = $_SESSION[$module]['fz_id'];
    }

    header("Location: VF_FZ_MaF_Edit.php?ID=$fz_id");
}

BA_HTML_header('Zulassungs Daten', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require('VF_FZ_EI_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
