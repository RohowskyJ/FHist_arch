<?php
/**
 * Fahrzeuge, Geräteräume, Wartung
 *
 * @author Josef Rohowsky - neu 2029 
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_laderaum';

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
    $lr_id = $_GET['ID'];
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
    header('Location: VF_FA_FZ_Edit.php?fw_id=' . $_SESSION[$module]['fw_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_rp = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($lr_id == 0) {

        $neu['lr_id'] = $lr_id;
        $neu['lr_fzg'] = $_SESSION[$module]['fz_id'];
        $neu['lr_raum'] = $neu['lr_beschreibung'] = $neu['lr_foto_1'] = $neu['lr_komm_1'] = "";
        $neu['lr_foto_2'] = $neu['lr_komm_2'] = $neu['lr_foto_3'] = $neu['lr_komm_3'] = "";
        $neu['lr_foto_4'] = $neu['lr_komm_4'] = $neu['lr_uidaend'] = $neu['lr_aenddate'] = "";
    } else {
        $sql = "SELECT * FROM $table_rp ";

        if ($lr_id != '') {
            $sql .= " WHERE lr_id = '$lr_id'";
        }

        $result = SQL_QUERY($db, $sql) or die('Lesen Satz $lr_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($lr_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der lr_id Nummer $lr_id gefunden</p>";
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

    $lr_id = $neu['lr_id'];

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 118: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
    
    if (isset($_FILES['uploaddatei_1']['name'])) {
        $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";
        
        if ($_FILES['uploaddatei_1']['name'] != "" ) {
            $neu['lr_foto_1'] = VF_Upload($uploaddir, 1);
        }
        if ($_FILES['uploaddatei_2']['name'] != "" ) {
            $neu['lr_foto_2'] = VF_Upload($uploaddir, 2);
        }
        if ($_FILES['uploaddatei_3']['name'] != "" ) {
            $neu['lr_foto_3'] = VF_Upload($uploaddir, 3);
        }
        if ($_FILES['uploaddatei_4']['name'] != "" ) {
            $neu['lr_foto_4'] = VF_Upload($uploaddir, 4);
        }
    }
    
    if ($neu['lr_id'] == 0) { # neueingabe
        $sql = "INSERT INTO $table_rp (
                lr_fzg , lr_raum, lr_beschreibung, lr_foto_1, lr_komm_1,
                lr_foto_2, lr_komm_2,lr_foto_3, lr_komm_3,lr_foto_4, lr_komm_4,
                lr_uidaend,lr_aenddate
              ) VALUE (
               '$neu[lr_fzg]','$neu[lr_raum]','$neu[lr_beschreibung]','$neu[lr_foto_1]','$neu[lr_komm_1]',
               '$neu[lr_foto_2]','$neu[lr_komm_2]','$neu[lr_foto_3]','$neu[lr_komm_3]','$neu[lr_foto_4]','$neu[lr_komm_4]',
               '$p_uid',now()
               )";

        $result =SQL_QUERY($db, $sql);
        $errno = mysqli_errno($db);
        if ($errno == "1146") {
            $sql_newT = "CREATE TABLE IF NOT EXISTS $table_rp LIKE $tabelle";
            mysqli_query($db, $sql_newT);
            $result = mysqli_query($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
        }

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
            if ($name == "lr_id") {
                continue;
            } #

            if ($name == "phase") {
                continue;
            } #
            if ($name == "lr_foto_11") {
                continue;
            }
            if ($name == "lr_foto_22") {
                continue;
            }
            if ($name == "lr_foto_33") {
                continue;
            }
            if ($name == "lr_foto_44") {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

        $sql = "UPDATE $table_rp SET  $updas WHERE `lr_id`='$lr_id'";
        if ($debug) {
            echo '<pre class=debug> L 0261: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql); 
        
        $fz_id = $_SESSION[$module]['fz_id'];
        header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
    }
}
BA_HTML_header('Geräteraum', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BA_GR_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
?>