<?php
/**
 * Archivalien zum Fahrzeg, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_arc_fz_xref';

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
    $_SESSION[$module]['fa_id'] = $fa_id = $_GET['ID'];
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
    header('Location: VF_FA_FZ_Edit.php?fz_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
                              # --------------------------------------------------------
                              # Lesen der Daten aus der sql Tabelle
                              # ------------------------------------------------------------------------------------------------------------

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

$table_da = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
#var_dump($_SESSION[$module]);
if ($phase == 0) {

    if ($fa_id == 0) {
        $neu['fa_id'] = $fa_id;
        $neu['fa_fzgnr'] = $_SESSION[$module]['fz_id'];
        $neu['fa_eignr'] = $_SESSION['Eigner']['eig_eigner'];
        $neu['fa_uidaend'] = $neu['fa_aenddat'] = "";
        $neu['fa_sammlg'] = $_SESSION[$module]['fz_sammlg'];
    } else {
        $sql = "SELECT * FROM $table_da ";

        if ($fa_id != '') {
            $sql .= " WHERE fa_arcnr = '$fa_id'";   
        }
        $result = SQL_QUERY($db, $sql);

        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fa_fzgnr != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der fa_id Nummer $fa_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        While ($neu = mysqli_fetch_assoc($result)) {}
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    if (isset($_POST['arc_id'])){
        
    } else {
        foreach ($_POST as $name => $value) {
            $neu[$name] = mysqli_real_escape_string($db, $value);
        }
    }
    

    $neu['fa_id'] = $_SESSION[$module]['fa_id'];
    $neu['fa_fzgnr'] = $_SESSION[$module]['fz_id'];

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
    $fz_id = $_SESSION[$module]['fz_id'];

    if (isset($_POST['arc_id'])) {
        $arc_id = $_POST['arc_id'];
    } else {
        $arc_id = "";
    }
    $eignr = $_SESSION['Eigner']['eig_eigner'];
    
    var_dump($arc_id);

    if (! empty($arc_id)) {
        $table = "ma_arc_xref_$eignr";

        foreach ($arc_id as $value) {
            $arc_arr = explode(";", $value);

            $select = "WHERE `fa_fzgnr`='$arc_arr[2]' AND `fa_arcnr`= '$arc_arr[0]'";

            $sql_in = "SELECT * FROM `$table` $select ";
            $return_in = mysqli_query($db, $sql_in) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
            if (mysqli_num_rows($return_in) <= 0) {
                $sql_in = "INSERT INTO `$table` (
                   `fa_eignr`, `fa_fzgnr`, `fa_sammlg`, `fa_arcnr`,
                   `fa_uidaend`
                    ) VALUES
                    (
                    '$eignr','$arc_arr[2]',
                    '$arc_arr[3]','$arc_arr[1]',
                    '$p_uid'
                    )";
                $return_in = mysqli_query($db, $sql_in) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
            }
        }

        mysqli_close($db);
    }

    header("Location: VF_FA_FZ_Edit.php?ID=$eignr");
}

BA_HTML_header('Zuordnung Archivdaten', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
switch ($phase) {
    case 0:
        require ('VF_FA_BA_AR_Srch_ph0.inc.php');
        break;
}
BA_HTML_trailer();

?>