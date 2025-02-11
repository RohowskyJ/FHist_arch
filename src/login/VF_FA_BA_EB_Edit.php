<?php

/**
 * Fahrzeuge, fixe Enbauten, Liste
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_fixeinb';

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

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $fz_einb_id = $_GET['ID'];
} else {
    $fz_einb_id = "";
}

if ($phase == 99) {
    header('Location: VF_FA_FZ_Edit.php?fz_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_eb = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fz_einb_id == 0) {

        $neu['fz_einb_id'] = $fz_einb_id;
        $neu['fz_id'] = $_SESSION[$module]['fz_id'];
        $neu['fz_gername'] = $neu['fz_ger_herst'] = $neu['fz_ger_sernr'] = $neu['fz_ger_baujahr'] = $neu['fz_ger_typ'] = $neu['fz_ger_leistg'] = $neu['fz_ger_l_einh'] = "";
        $neu['fz_ger_foto_1'] = $neu['fz_ger_f1_beschr'] = $neu['fz_ger_foto_2'] = $neu['fz_ger_f2_beschr'] = $neu['fz_ger_foto_3'] = $neu['fz_ger_f3_beschr'] = "";
        $neu['fz_ger_foto_4'] = $neu['fz_ger_f4_beschr'] = $neu['fz_einb_komm'] = $neu['fz_uidaend'] = $neu['fz_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_eb ";

        if ($fz_einb_id != '') {
            $sql .= " WHERE fz_einb_id = '$fz_einb_id'";
        }

        $result = SQL_QUERY($db, $sql) or die('Lesen Satz $fz_ebnr_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fz_einb_id Nummer $fz_einb_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);
   
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$eu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['fz_id'] = $_SESSION[$module]['fz_id'];

    $neu['fz_gername'] = mb_convert_case($neu['fz_gername'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
    $neu['fz_ger_herst'] = mb_convert_case($neu['fz_ger_herst'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";
        
        if (! file_exists($uploaddir)) {
            mkdir($uploaddir, 0770, true);
        }
        
        if ($_FILES['uploaddatei_1']['name'] != "" ) {
            $neu['fz_ger_foto_1'] = VF_Upload($uploaddir, 1);
        }
        if ($_FILES['uploaddatei_2']['name'] != "" ) {
            $neu['fz_ger_foto_2'] = VF_Upload($uploaddir, 2);
        }
        if ($_FILES['uploaddatei_3']['name'] != "" ) {
            $neu['fz_ger_foto_3'] = VF_Upload($uploaddir, 3);
        }
        if ($_FILES['uploaddatei_4']['name'] != "" ) {
            $neu['fz_ger_foto_4'] = VF_Upload($uploaddir, 4);
        }
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 148: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
    $fz_einb_id = $neu['fz_einb_id'];

    if ($fz_einb_id == 0) { # neuingabe
        $sql = "INSERT INTO $table_eb (
                fz_id , fz_gername, fz_ger_herst , fz_ger_sernr,fz_ger_baujahr , fz_ger_typ,fz_ger_leistg,
                fz_ger_l_einh,fz_ger_foto_1,fz_ger_f1_beschr,fz_ger_foto_2,fz_ger_f2_beschr,
                fz_ger_foto_3,fz_ger_f3_beschr,fz_ger_foto_4,fz_ger_f4_beschr,
                fz_einb_komm,fz_uidaend
              ) VALUE (
                '$neu[fz_id]','$neu[fz_gername]','$neu[fz_ger_herst]','$neu[fz_ger_sernr]','$neu[fz_ger_baujahr]','$neu[fz_ger_typ]','$neu[fz_ger_leistg]',
                '$neu[fz_ger_l_einh]','$neu[fz_ger_foto_1]','$neu[fz_ger_f1_beschr]','$neu[fz_ger_foto_2]','$neu[fz_ger_f2_beschr]',
                '$neu[fz_ger_foto_3]','$neu[fz_ger_f1_beschr]','$neu[fz_ger_foto_4]','$neu[fz_ger_f4_beschr]','$neu[fz_einb_komm]','$p_uid'
               )";
        # echo " L 0165: \$sql $sql <br/>";
        $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));

        $fz_id = $_SESSION[$module]['fz_id'];
        header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
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
            if ($name == "fz_ger_foto_11") {
                continue;
            }
            if ($name == "fz_ger_foto_22") {
                continue;
            }
            if ($name == "fz_ger_foto_33") {
                continue;
            }
            if ($name == "fz_ger_foto_44") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_eb SET  $updas WHERE `fz_einb_id`='$fz_einb_id'";
        if ($debug) {
            echo '<pre class=debug> L 0265: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = mysqli_query($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));

        $fz_id = $_SESSION[$module]['fz_id'];
        header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
    }
}

BA_HTML_header('Fixer Einbau  im Fahrzeug', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BA_EB_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
?>