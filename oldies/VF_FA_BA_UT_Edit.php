<?php

/**
 * Fahrzge, Typisierungsänderug, Formular
 *
 * @author Josef Rohowsky - neu 2019 
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_typis_aend';

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

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');


initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

if (isset($_GET['ID'])) {
    $fz_typ_id = $_GET['ID'];
}

$LinkDB_database = '';
$db = LinkDB('VFH');

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header('Location: VF_FA_FZ_Edit.php?fz_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_ut = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fz_typ_id == 0) {

        $neu['fz_typ_id'] = $fz_typ_id;
        $neu['fz_id'] = $_SESSION[$module]['fz_id'];
        $neu['fz_t_aenddat'] = $neu['fz_infotext'] = $neu['fz_uidaend'] = $neu['fz_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_ut ";

        if ($fz_typ_id != '') {
            $sql .= " WHERE fz_typ_id = '$fz_typ_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fz_typ_id Nummer $fz_typ_id gefunden</p>";
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

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $fz_typ_id = $neu['fz_typ_id'];

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 107: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($fz_typ_id == 0) { # neueingabe
        $sql = "INSERT INTO $table_ut (
                fz_id , fz_t_aenddat, fz_infotext , fz_uidaend
              ) VALUE (
               '$neu[fz_id]','$neu[fz_t_aenddat]','$neu[fz_infotext]','$p_uid'
               )";
        echo " L 0146: \$sql $sql <br/>";
        $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));
        $errno = mysqli_errno($db);
        if ($errno == "1146") {
            $sql_newT = "CREATE TABLE IF NOT EXISTS $table_ut LIKE $tabelle";
            mysqli_query($db, $sql_newT);
            $result = mysqli_query($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
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
            if ($name == "fo_ff_abzeich1") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_ut SET  $updas WHERE `fz_typ_id`='$fz_typ_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
    }

    $fz_id = $_SESSION[$module]['fz_id'];
    header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
}

BA_HTML_header('Umtypisierungen', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BA_UT_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
?>