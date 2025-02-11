<?php

/**
 * Liste der Reparaturen, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 * 
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_reparat';

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
    $fz_rep_id = $_GET['ID'];
}

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header('Location: VF_FA_FG_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
                              # --------------------------------------------------------
                              # Lesen der Daten aus der sql Tabelle
                              # ------------------------------------------------------------------------------------------------------------
$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_re = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fz_rep_id == 0) {

        $neu['fz_rep_id'] = $fz_rep_id;
        $neu['fz_id'] = $_SESSION[$module]['fz_id'];
        $neu['fz_repdat'] = $neu['fz_reptext'] = $neu['fz_uidaend'] = $neu['fz_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_re ";

        if ($fz_rep_id != '') {
            $sql .= " WHERE fz_rep_id = '$fz_rep_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $fz_rep_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fz_typ_id Nummer $fz_typ_id gefunden</p>";
                }
            }

            VF_HTML_trailer();
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

    # echo '<pre class=debug>';echo '<hr>$neu: '; print_r($neu); echo '</pre>';
    $fz_rep_id = $neu['fz_rep_id'];

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 107: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($fz_rep_id == 0) { # neueigabe
        $sql = "INSERT INTO $table_re (
                fz_id , fz_repdat, fz_reptext , fz_uidaend
              ) VALUE (
               '$neu[fz_id]','$neu[fz_repdat]','$neu[fz_reptext]','$p_uid'
               )";
        # echo " L 0167: \$sql $sql <br/>";
        $result = SQL_QUERY($db, $sql);

        $fz_id = $_SESSION[$module]['fz_id'];
    } else { # update
        $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

        foreach ($neu as $name => $value) # für alle Felder aus der tabelle
        {
            if (! preg_match("/[^0-9]/", $name)) {
                continue;
            } # überspringe Numerische Feldnamen

            if ($name == "phase") {
                continue;
            } #

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_re SET  $updas WHERE `fz_rep_id`='$fz_rep_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);

        $fz_id = $neu['fz_id'];
    }

    header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
}

BA_HTML_header('Reparaturen', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BA_RE_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
?>