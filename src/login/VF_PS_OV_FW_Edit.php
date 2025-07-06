<?php

/**
 * Feuerwehr- Wappen- Wartung
 *
 * @author josef Rohowsky - neu 2019
 *
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
$tabelle = 'aw_ff_wappen';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Const.inc.php';
require $path2ROOT . 'login/common/Funcs.inc.php';
require $path2ROOT . 'login/common/Edit_Funcs.inc.php';
require $path2ROOT . 'login/common/List_Funcs.inc.php';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.inc.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES
setlocale(LC_CTYPE, "de_AT"); // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper

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
if (isset($_GET['fo_id'])) {
    $fo_id = $_GET['fo_id'];
}

if ($phase == 99) {
    header('Location: VF_PS_OV_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, $tabelle);

echo "phase $phase fo_id $fo_id <br>";

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fo_id == 0) {

        $neu['fo_id'] = $fo_id;
        $neu['fo_fw_id'] = $_SESSION[$module]['fw_id'];
        $neu['fo_ff_wappen'] = $neu['fo_ff_w_sort'] = $neu['fo_ff_w_komm'] = $neu['fo_aenduid'] = $neu['fo_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($fo_id != '') {
            $sql .= " WHERE fo_id = '$fo_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);

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
    $p_uid = $_SESSION['VF_Prim']['p_uid'];

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['fo_ff_w_komm'] = mb_convert_case($neu['fo_ff_w_komm'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = $path2ROOT."login/AOrd_Verz/AOrd_Verz//PSA/AERM/Wappen_FW/";
        
        if (! file_exists($uploaddir)) {
            mkdir($uploaddir, 0777, true);
        }
        
        if (isset($_FILES['uploaddatei_1']) && $_FILES['uploaddatei_1']['name'] != "") {
            $neu['fo_ff_wappen'] =  VF_Upload_Pic('fo_ff_wappen', $uploaddir, $urh_abk="", $fo_aufn_datum="");
        }
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo '<hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($neu['fo_id'] == 0) { # Neueingabe
        $sql = "INSERT INTO $tabelle (
                fo_fw_id , fo_ff_wappen, fo_ff_w_sort , fo_ff_w_komm , fo_aenduid,fo_aenddat
              ) VALUE (
               '$neu[fo_fw_id]','$neu[fo_ff_wappen]','$neu[fo_ff_w_sort]','$neu[fo_ff_w_komm]','$p_uid',now()
               )";

        $result = SQL_QUERY($db, $sql);

        header("Location: VF_PS_OV_M_Edit.php?fw_id=" . $neu['fo_fw_id']);
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
            if ($name == "fo_ff_wappen1") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $tabelle SET  $updas WHERE `fo_id`='".$neu['fo_id']."' ";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);

        $fw_id = $_SESSION[$module]['fw_id'];
        header("Location: VF_PS_OV_O_Edit.php?ID=$fw_id");
    }
}

HTML_header('Orts- Wappen - Verwaltung', 'Änderungsdienst', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_PS_OV_FW_Edit_ph0.inc.php');
        break;
}
HTML_trailer();
?>