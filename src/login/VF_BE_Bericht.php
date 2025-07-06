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

BA_HTML_header('Veranstaltungs Anzeige', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<div class='white'>";
echo "<framearea>";

echo "<table class='w3-table w3-striped w3-hoverable scroll'
     style='border:1px solid black;background-color:white;margin:5px;'>";

echo "<thead>";
echo "<tr style='border-bottom:1px solid black;'>";

echo "<tr><th colspan='8'><h2>" . $row['vb_titel'] . "</h2></th></tr>";
echo "<tr><td colspan='8'>" . $row['vb_beschreibung'] . "</td><td></td></tr>";
echo "</thead><tbody>";

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
        $sql_fot = "SELECT * FROM dm_edien_" . $row['vb_foto_Urheber'] . " WHERE md_id='$row[vb_foto]' ";
        
        $return_fot = SQL_QUERY($db, $sql_fot);
        $recno = mysqli_num_rows($return_fot);
        
        if ($recno >= 1) {
            $row_fot = mysqli_fetch_assoc($return_fot);
            modifyRow($row_fot, "dm_edien_" . $row['vb_foto_Urheber']);
            if ($row['vb_unter'] == $vb_unter) {
                if ($cnt % 2 == 0) { # gerade Anzahl
                    echo "<tr><td colspan='2'>" . $row_fot['md_beschreibg'] . "</td><td>" . $row_fot['md_dsn_1'] . "</td></tr>";
                } else {
                    echo "<tr><td>" . $row_fot['md_dsn_1'] . "</td><td colspan='2'>" . $row_fot['md_beschreibg'] . "</td></tr>";
                }
                $cnt ++;
            } else {
                if ($row['vb_suffix'] == 0) {
                    echo "<tr><td><a href='" . $_SERVER['PHP_SELF'] . "?vb_flnr=" . $row['vb_flnr'] . "&vb_unter=" . $row['vb_unter'] . "' target='_blanc'>" . $row['vb_titel'] . "</a> </td><td>" . $row_fot['md_beschreibg'] . "</td><td>" . $row_fot['md_dsn_1'] . "</th></tr>";
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
            $sql_fot = "SELECT * FROM dm_edien_" . $row['vb_foto_Urheber'] . " WHERE md_id='$row[vb_foto]' ";
            
            $return_fot = SQL_QUERY($db, $sql_fot);
            if ($return_fot) {
                $row_fot = mysqli_fetch_assoc($return_fot);
                $vb_urheber = $row['vb_foto_Urheber'];
                
                modifyRow($row_fot, "dm_edien_$vb_urheber");

                if ($cnt % 2 == 0) { # gerade Anzahl
                    echo "<tr><td colspan='2'>" . $row_fot['fo_begltxt'] . "</td><td>" . $row_fot['fo_dsn'] . "</td></tr>";
                } else {
                    echo "<tr><td>" . $row_fot['md_dsn_1'] . "</td><td colspan='2'>" . $row_fot['md_beschreibg'] . "</td></tr>";
                }
                $cnt ++;
            }
        }
    }
}

echo "</tbody></table>";
echo "</framearea>";
echo "</div>";

BA_HTML_trailer();

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
        case "md_urheb":
            $pict_base_pfad = 
            $fm_id = $row['fm_id'];
            $fm_eig = $row['fm_eigner'];
            $row['fm_id'] = "<a href='VF_C_MassUpload_v4.php?fm_eig=$fm_eig' >" . $fm_id . "</a>";
            break;

        case "dm_edien":
            $pict_basis_pfad = $path2ROOT."login/AOrd_Verz/" . $row['md_eigner'] . "/09/";
            
            if ($row['md_media'] == "Audio") {
                $pict_basis_pfad .= "02/";
            } elseif ($row['md_media'] == "Foto") {
                $pict_basis_pfad .= "06/";
            } elseif ($row['md_media'] == "Video") {
                $pict_basis_pfad .= "10/";
            }
     
            $md_id = $row['md_id'];

            $row['md_id'] = "<a href='VF_FO_Edit.php?md_id=$md_id&md_eigner=" . $row['md_eigner'] . "&verz=J' >" . $md_id . "</a>";

            $md_aufn_d = $row['md_aufn_datum'];
            $md_eigner = $row['md_eigner'];

            $row['md_eigner'] = "<a href='VF_FO_List_Detail.php?md_eigner=$md_eigner&md_aufn_d=$md_aufn_d'  target='_blanc'>" . $md_aufn_d . " Medien </a>";

            $d_path = "$pict_basis_pfad$md_aufn_d/";

            if ($row['md_dsn_1'] != "") {
                $dsn = $row['md_dsn_1'];
               
                if ($row['md_media'] == "Audio") {
                    # $row['$row['md_dsn_1'] = "<a href='$d_path$dsn' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";fo_dsn'] = "<a href='$pict_path$dsn' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";
                } elseif ($row['md_media'] == "Foto") {
                    $row['md_dsn_1'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
                } elseif ($row['md_media'] == "Video") {
                    $video = "
                        <video width='320' height='240' controls>
                        <source src='$d_path" . $row['fo_dsn'] . " type='video/mp4'>
                            
                        Your browser does not support the video tag.
                        </video>";
                    
                    $row['md_dsn_1'] = "<a href='$d_path$dsn' target='_blank'>$video</a>";
                }
            }

            break;
    }
    return True;
} # Ende von Function modifyRow

?>
