<?php

/**
 * Bericht- Anzeige, nur fertige Berichte 
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;

const Tabellen_Name = 'vb_ber_detail_4';
$tabelle = Tabellen_Name;

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

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_GET['vb_flnr'])) {
    $_SESSION[$module]['vb_flnr'] = $vb_flnr = $_GET['vb_flnr'];
} else {
    $vb_flnr = 0;
}
if (isset($_POST['vb_flnr'])) {
    $vb_flnr = $_POST['vb_flnr'];
}

/**
 * Unterseiten- Steuerung
 */
if (isset($_GET['vb_unter'])) {
    $vb_unter = $_GET['vb_unter'];
} else {
    $vb_unter = 0;
}   

# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$sql = "SELECT * FROM vb_bericht_4 WHERE vb_flnr=$vb_flnr "; #
$return = SQL_QUERY($db, $sql);
$row = mysqli_fetch_assoc($return);
modifyrow($row, 'vb_bericht_4' );

$header = "<style>
      table, th, td {
      padding: 10px;
      border: 1px solid black;
      border-collapse: collapse;
      }
    </style>";

HTML_header('Veranstaltungs Anzeige', '', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
$pictpath = $path2ROOT . "login/AOrd_Verz/";

echo "<div class='white'>";
echo "<framearea>";

echo "<table class='w3-table w3-striped w3-hoverable scroll'
     style='border:1px solid black;background-color:white;margin:5px;'>";

echo "<thead>";
echo "<tr style='border-bottom:1px solid black;'>";

echo "<tr><th colspan='8'><h2>" . $row['vb_titel'] . "</h2></th></tr>";
echo "<tr><td colspan='8'>" . $row['vb_beschreibung'] . "</td><td></td></tr>";

$unter = $row['vb_unterseiten'];

if ($unter == "Unterseiten") { # # and $vb_unter == 0
    # echo "L 091 Unterseiten <br>";
    /**
     * Anzeige mit Unterseiten
     * Anzeige der Unterseiten vb_suffix = 0
     *
     * Wenn Unterseite aufgerufen wird übergabe vb_flnr und vb_unter
     */

    $sql = "SELECT * FROM vb_ber_detail_4 WHERE vb_flnr='$vb_flnr'   ORDER BY vb_flnr, vb_unter, vb_suffix ASC"; # # AND vb_unter!='0' AND vb_suffix='0'
    $return = SQL_QUERY($db, $sql);
    
    $cnt = 1;
    WHILE ($row = mysqli_fetch_assoc($return)) {
        $sql_fot = "SELECT * FROM fo_todaten_" . $row['vb_foto_Urheber'] . " WHERE fo_id='$row[vb_foto]' ";
        
        $return_fot = SQL_QUERY($db, $sql_fot);
        $recno = mysqli_num_rows($return_fot);
        
        if ($recno >= 1) {
            $row_fot = mysqli_fetch_assoc($return_fot);
            modifyRow($row_fot, "fo_todaten_" . $row['vb_foto_Urheber']);
            if ($row['vb_unter'] == $vb_unter) {
                if ($cnt % 2 == 0) { # gerade Anzahl
                    echo "<tr><td colspan='2'>" . $row_fot['fo_begltxt'] . "</td><td>" . $row_fot['fo_dsn'] . "</td></tr>";
                } else {
                    echo "<tr><td>" . $row_fot['fo_dsn'] . "</td><td colspan='2'>" . $row_fot['fo_begltxt'] . "</td></tr>";
                }
                $cnt ++;
            } else {
                if ($row['vb_suffix'] == 0) {
                    echo "<tr><td><a href='" . $_SERVER['PHP_SELF'] . "?vb_flnr=" . $row['vb_flnr'] . "&vb_unter=" . $row['vb_unter'] . "' target='_blanc'>" . $row['vb_titel'] . "</a> </td><td>" . $row_fot['fo_begltxt'] . "</td><td>" . $row_fot['fo_dsn'] . "</th></tr>";
                }
            }
        }
    }
} else {
    /**
     * Anzeige ohne Unterseiten
     */
    #  echo "L 0128 Keine Unterseiten <br>";
    $sql = "SELECT * FROM vb_ber_detail_4 WHERE vb_flnr='$vb_flnr'  ORDER BY vb_flnr, vb_unter, vb_suffix ASC";
    $return = SQL_QUERY($db, $sql);
    if ($return) {
        $cnt = 1;
        WHILE ($row = mysqli_fetch_assoc($return)) {
            $sql_fot = "SELECT * FROM fo_todaten_" . $row['vb_foto_Urheber'] . " WHERE fo_id='$row[vb_foto]' ";
            
            $return_fot = SQL_QUERY($db, $sql_fot);
            if ($return_fot) {
                $row_fot = mysqli_fetch_assoc($return_fot);
                $fo_urheber = $row['vb_foto_Urheber'];
                modifyRow($row_fot, "fo_todaten_$fo_urheber");

                if ($cnt % 2 == 0) { # gerade Anzahl
                    echo "<tr><td colspan='2'>" . $row_fot['fo_begltxt'] . "</td><td>" . $row_fot['fo_dsn'] . "</td></tr>";
                } else {
                    echo "<tr><td>" . $row_fot['fo_dsn'] . "</td><td colspan='2'>" . $row_fot['fo_begltxt'] . "</td></tr>";
                }
                $cnt ++;
            }
        }
    }
}

echo "</thead></table>";
echo "</framearea>";
echo "</div>";

HTML_trailer();

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *        
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);

    switch ($s_tab) {
        case "fo_urheb":
            $fm_id = $row['fm_id'];
            $fm_eig = $row['fm_eigner'];
            $row['fm_id'] = "<a href='VF_C_MassUpload_v4.php?fm_eig=$fm_eig' >" . $fm_id . "</a>";
            break;

        case "fo_todat":
            if ($_SESSION[$module]['URHEBER']['BE']['ei_media'] == "F") {
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/06/";
            } else {
                $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/07/";
            }

            $fo_id = $row['fo_id'];

            $row['fo_id'] = "<a href='VF_FO_Edit.php?fo_id=$fo_id&fo_eigner=" . $row['fo_eigner'] . "&verz=J' >" . $fo_id . "</a>";

            $fo_aufn_d = $row['fo_aufn_datum'];
            $fo_eigner = $row['fo_eigner'];

            $row['fo_eigner'] = "<a href='VF_FO_List_Detail.php?fo_eigner=$fo_eigner&fo_aufn_d=$fo_aufn_d'  target='_blanc'>" . $fo_aufn_d . " Fotos/Videos </a>";

            $pict_path .= $d_path = VF_set_PictPfad($row['fo_aufn_datum'], $row['fo_basepath'], $row['fo_zus_pfad'], $row['fo_aufn_suff']);
   
            if ($row['fo_dsn'] != "") {
                $dsn = $row['fo_dsn'];
               
                if ($_SESSION[$module]['URHEBER']['BE']['ei_media'] == "F") {
                    $row['fo_dsn'] = "<a href='$pict_path$dsn' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";
                } else {
                    $video = "
                        <video width='320' height='240' controls>
                        <source src='$pict_path" . $row['fo_dsn'] . " type='video/mp4'>
    
                        Your browser does not support the video tag.
                        </video>";

                    $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'>$video</a>";
                }
            }

            break;
    }
    return True;
} # Ende von Function modifyRow

?>
