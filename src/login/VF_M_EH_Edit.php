<?php

/**
 * Liste der vom Verein verliehenen Ehrungen, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'MVW';
$module = Module_Name;
$tabelle = 'fh_m_ehrung';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";
$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

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
    $fe_lfnr = $_GET['ID'];
}

$mi_id = $_SESSION[$module]['mi_id'];
if ($phase == 99) {
    header('Location: VF_M_Edit.php?mi_id=' . $_SESSION[$module]['mi_id']);
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

                              # --------------------------------------------------------
                              # Lesen der Daten aus der sql Tabelle
                              # ------------------------------------------------------------------------------------------------------------

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    if ($fe_lfnr == 0) {

        $neu['fe_lfnr'] = $fe_lfnr;
        $neu['fe_m_id'] = $_SESSION[$module]['mi_id'];

        $neu['fe_ehrung'] = $neu['fe_eh_datum'] = $neu['fe_begruendg'] = $neu['fe_aenddat'] = "";
        $neu['fe_bild1'] = $neu['fe_bild2'] = $neu['fe_bild3'] = $neu['fe_bild4'] = "";
        $neu['fe_uidaend'] = $p_uid;
    } else {
        $sql = "SELECT * FROM $tabelle ";

        if ($fe_lfnr != '') {
            $sql .= " WHERE fe_lfnr = '$fe_lfnr'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fe_lfnr != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der ad_id Nummer $fe_lfnr gefunden</p>";
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
;
if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    # echo '<pre class=debug>';echo '<hr>$neu: '; print_r($neu); echo '</pre>';

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
    
    $uploaddir = "AOrd_Verz/1/MITGL/";
    
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir);
    }
    
    
    $target1 = "";
    if (! empty($_FILES['uploaddatei_01'])) {
        $pict1 = basename($_FILES['uploaddatei_01']['name']);
        if (! empty($pict1)) {
            $target1 = $uploaddir . basename($_FILES['uploaddatei_01']['name']);
            if (move_uploaded_file($_FILES['uploaddatei_01']['tmp_name'], $target1)) {
                echo "Datei/Bild 1 geladen!<br><br><br>";
                $neu['fe_bild1'] = $pict1;
            }
        }
    }

    $target2 = "";
    if (! empty($_FILES['uploaddatei_02'])) {
        $pict2 = basename($_FILES['uploaddatei_02']['name']);
        if (! empty($pict2)) {
            $target2 = $uploaddir . basename($_FILES['uploaddatei_02']['name']);
            if (move_uploaded_file($_FILES['uploaddatei_02']['tmp_name'], $target2)) {
                echo "Datei/Bild 2 geladen!<br><br><br>";
                $neu['fe_bild2'] = $pict2;
            }
        }
    }

    $target3 = "";
    if (! empty($_FILES['uploaddatei_03'])) {
        $pict3 = basename($_FILES['uploaddatei_03']['name']);
        if (! empty($pict3)) {
            $target3 = $uploaddir . basename($_FILES['uploaddatei_03']['name']);
            if (move_uploaded_file($_FILES['uploaddatei_03']['tmp_name'], $target3)) {
                echo "Datei/Bild 3 geladen!<br><br><br>";
                $neu['fe_bild3'] = $pict3;
            }
        }
    }

    $target4 = "";
    if (! empty($_FILES['uploaddatei_04'])) {
        $pict4 = basename($_FILES['uploaddatei_04']['name']);
        if (! empty($pict4)) {
            $target4 = $uploaddir . basename($_FILES['uploaddatei_04']['name']);
            if (move_uploaded_file($_FILES['uploaddatei_04']['tmp_name'], $target4)) {
                echo "Datei/Bild 4 geladen!<br><br><br>";
                $neu['fe_bild4'] = $pict4;
            }
        }
    }
    if ($neu['fe_lfnr'] == 0) { # neueingabe
        $sql = "INSERT INTO $tabelle (
                fe_m_id , fe_ehrung, fe_eh_datum,fe_begruendg,fe_bild1,fe_bild2,fe_bild3,fe_bild4, fe_uidaend
              ) VALUE (
               '$neu[fe_m_id]','$neu[fe_ehrung]','$neu[fe_eh_datum]','$neu[fe_begruendg]','$neu[fe_bild1]','$neu[fe_bild2]','$neu[fe_bild3]','$neu[fe_bild4]','$p_uid'
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
            if ($name == "fe_lfnr") {
                continue;
            } #
            if ($name == "fe_bild101" || $name == "fe_bild202" || $name == "fe_bild303" || $name == "fe_bild404"  ) {
                continue;
            }

            $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
        } # Ende der Schleife

        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorher keine Update-Strings sind

        $sql = "UPDATE $tabelle SET  $updas WHERE `fe_lfnr`='" . $neu['fe_lfnr'] . "'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);

        $mi_id = $_SESSION[$module]['mi_id'];
          
        if (isset($_SESSION[$module]['Return']) AND $_SESSION[$module]['Return']) {
            header("Location: VF_M_Ehrg_List.php");
        } else {
            if ($mi_id != "") {
                header("Location: VF_M_Edit.php?ID=$mi_id");
            } 
            header("Location: VF_M_List.php");
        }
    }

}

BA_HTML_header('Auszeichnungs - Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require 'VF_M_EH_Edit_ph0.inc.php';
        break;
}
BA_HTML_trailer();
?>