<?php

/**
 * Inventarverleihliste, Wartung 
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
session_start();

const Module_Name = 'INV';
$module = Module_Name;
$tabelle = 'in_vent_verleih';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.inc.php';

require $path2ROOT . 'login/common/VF_Const.inc.php';
require $path2ROOT . 'login/common/Funcs.inc.php';
require $path2ROOT . 'login/common/Edit_Funcs.inc.php';
require $path2ROOT . 'login/common/List_Funcs.inc.php';
require $path2ROOT . 'login/common/Tabellen_Spalten.inc.php';

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
    $_SESSION[$module]['vl_id'] = $vl_id = $_GET['ID'];
} else {
    $_SESSION[$module]['vl_id'] = $vl_id = "";
}
if (isset($_GET['vl_id'])) {
    $_SESSION[$module]['vl_id'] = $vl_id = $_GET['vl_id'];
}

if (isset($_POST['vl_id'])) {
    $_SESSION[$module]['vl_id'] = $vl_id = $_POST['vl_id'];
}

if ($phase == 99) {
    header('Location: VF_I_IN_Edit.php?in_id=' . $_SESSION[$module]['in_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$table_ty = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

Tabellen_Spalten_parms($db, $table_ty);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($vl_id == 0) {

        $neu['vl_id'] = $vl_id;
        $neu['ei_id'] = $_SESSION['Eigner']['eig_eigner'];
        $neu['ei_invnr'] = $_SESSION[$module]['in_id'];
        $neu['ei_zustand_aus'] = $neu['ei_zustand_ret'] = $neu['ei_zust_aus_bild'] = $neu['ei_zust_ret_bild'] = $neu['ei_leiher'] = $neu['ei_leihvertr'] = "";
        $neu['ei_verlbeg'] = $neu['ei_verlend'] = $neu['ei_verlgrund'] = $neu['ei_verlrueck'] = $neu['ei_verluebn'] = "";
        $neu['ei_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
        $neu['ei_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_ty ";

        if ($vl_id != '') {
            $sql .= " WHERE vl_id = '$vl_id'";
        }

        $result = SQL_QUERY($db, $sql);

        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($vl_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der vl_id Nummer $vl_id gefunden</p>";
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
var_dump($neu);
    $neu['ei_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    
    if (stripos($neu['auto'],"-") >=1 ) {
        $n_leiher = explode("-",$neu['auto']);
        $neu['ei_leiher'] = $n_leiher[0];
    }
    echo "L 0138 leiher ".$neu['ei_leiher']." <br>";
    
    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";
        
        if ($_FILES['uploaddatei_1']['name'] != "" ) {
            $neu['ei_zust_aus_bild'] = VF_Upload($uploaddir, 1);
        }
        if ($_FILES['uploaddatei_2']['name'] != "" ) {
            $neu['ei_zust_ret_bild'] = VF_Upload($uploaddir, 2);
        }
    } 
    
    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($neu['vl_id'] == 0) { # neueingabe
        $sql = "INSERT INTO $table_ty (
              ei_id,ei_invnr,ei_zustand_aus,ei_zustand_ret,
              ei_zust_aus_bild,ei_zust_ret_bild,ei_leiher,ei_leihvertr,ei_verlbeg,ei_verlend,
              ei_verlgrund,ei_verlrueck,ei_verluebn,ei_uidaend
           ) VALUE (
              '$neu[ei_id]','$neu[ei_invnr]','$neu[ei_zustand_aus]','$neu[ei_zustand_ret]',
              '$neu[ei_zust_aus_bild]','$neu[ei_zust_ret_bild]','$neu[ei_leiher]','$neu[ei_leihvertr]','$neu[ei_verlbeg]','$neu[ei_verlend]',
              '$neu[ei_verlgrund]','$neu[ei_verlrueck]','$neu[ei_verluebn]','$neu[ei_uidaend]'
               )";

        $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));

        $neu['vl_id'] = mysqli_insert_id($db);
       
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
            if ($name == "ei_zust_aus_bild1") {
                continue;
            }
            if ($name == "ei_zust_ret_bild2") {
                continue;
            }
            if ($name == "eigentmr") {
                continue;
            } #
            if ($name == "auto") {
                continue;
            } 

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_ty SET  $updas WHERE `vl_id`='$vl_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERYy($db, $sql) ;
    }

    $in_id = $_SESSION[$module]['in_id'];
    header("Location: VF_I_IN_Edit.php?ID=$in_id");
}

$jq = $jq_ui = False;
$prot = True; # prototype.js laden
$header = "";

HTML_header('Inventar- Verleih','', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_I_IN_VL_Edit_ph0.inc');
        break;
}
HTML_trailer();
?>