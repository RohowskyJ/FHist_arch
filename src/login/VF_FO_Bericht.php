<?php

/**
 * 
 * Bericht- Anzeige, nur fertige Berichte 
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 */
session_start();

$module = 'OEF';
$sub_mod = 'Beri';

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

$header = "<style>
      table, th, td {
      padding: 10px;
      border: 1px solid black;
      border-collapse: collapse;
      }
    </style>";

BA_HTML_header('Veranstaltungs Foto- Bericht', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<div class='white'>";
echo "<framearea>";


# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$sql_b = "SELECT * FROM vb_bericht_4 WHERE vb_flnr=$vb_flnr "; #
# echo "L 081 sql $sql_b <br>";
$return_b = SQL_QUERY($db, $sql_b);
$row_b = mysqli_fetch_assoc($return_b);
# var_dump($row_b);
modifyrow($row_b, 'vb_bericht_4' );

$unter = $row_b['vb_unterseiten'];
# echo "L 088  vb_unter $vb_unter <br>";

/**
 * einlesen der Bilddaten
 */

$sql_d = "SELECT * FROM vb_ber_detail_4 WHERE vb_flnr='$vb_flnr'
        ORDER BY vb_flnr, vd_unter, vd_suffix ASC"; # # AND vb_unter!='0' AND vb_suffix='0'
$return_d = SQL_QUERY($db, $sql_d);

$l_cnt = 0;
WHILE ($row_d = mysqli_fetch_assoc($return_d)) {
    # var_dump($row);
    $fo_dsarr = explode('-',$row_d['vd_foto']);
    $urh = $fo_dsarr[0];
    $sql_fot = "SELECT * FROM dm_edien_$urh WHERE md_dsn_1='$row_d[vd_foto]' ";
    $sql_b = "SELECT * FROM vb_bericht_4 WHERE vb_flnr=$vb_flnr "; #
    # echo "L 0104 sql $sql_fot <br>";
    $return_fot = SQL_QUERY($db, $sql_fot);
    $recno = mysqli_num_rows($return_fot);
    $row_fot = mysqli_fetch_assoc($return_fot);
    modifyRow($row_fot, "dm_edien_$urh");
    
    if ($row_d ['vd_unter'] == 1) {
        if ($row_d['vd_suffix'] == 0) {
            echo "<div class='w3-row w3-container' >";
            echo "<span class='w3-left'> ".$row_b['vb_beschreibung']."</span><span class='w3-right' ".$row_fot['md_dsn_1']."'></span>";
            echo "</div>";
        } else {
            $line_pic = $row_fot['md_dsn_1'];
            $line_txt = $row_d['vd_beschreibung'];
            
            echo "<div class='w3-row w3-container' >";
            if ($l_cnt % 2 == 0) { # gerade Anzahl
                echo "<span class='w3-left'>$line_txt</span><span class='w3-right'>$line_pic</span>";
            } else {
                echo "<span class='w3-left'>$line_pic</span><span class='w3-right'>$line_txt</span>";
            }
            echo "</div>";
            $l_cnt++;
        }
        
    } else {
        $line_pic = $row_fot['md_dsn_1'];
        $line_txt = $row_d['vd_beschreibung'];
        
        echo "<div class='w3-row w3-container' >";
        if ($l_cnt % 2 == 0) { # gerade Anzahl
            echo "<span class='w3-left'>$line_txt</span><span class='w3-right'>$line_pic</span>";
        } else {
            echo "<span class='w3-left'>$line_pic</span><span class='w3-right'>$line_txt</span>";
        }
        echo "</div>";
        $l_cnt++;
    }
    
  
}

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
