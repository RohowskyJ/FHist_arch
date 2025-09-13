<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

$module = 'OEF';
$sub_mod = 'Beri';

const Tabellen_Name = 'vb_bericht_4';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$Inc_Arr = array();
$Inc_Arr[] = "VF_FO_Ber_Edit.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

# ====================================================================================================
# Anzeigen
# ====================================================================================================

$jq = $jqui = true;
$BA_AJA = true;

$header = "
";

BA_HTML_header('Veranstaltungs Definition', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

var_dump($_POST);
/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropDownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "Aus",
        "SpaltenNamenAnzeige" => "Ein",
        "DropDownAnzeige" => "Aus",
        "LangListe" => "Aus",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['vb_flnr'])) {
    $_SESSION[$module]['vb_flnr'] = $vb_flnr = $_GET['vb_flnr'];
    if ($_GET['vb_flnr'] == 0) {
        $phase = 0;
    }
} 

# ===========================================================================================================
# die HTML Seite ausgeben
# ===========================================================================================================
$Err_Msg = $Err_Urheb = "";
$Err_Nr = 0;

if ($phase == 99) {
    header("Localtion: VF_FO_Ber_List.php?Act=" . $_SESSION[$module]['BERI']['Act']);
}

# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, Tabellen_Name);

if ($phase == 0) {
    if ($_SESSION[$module]['vb_flnr'] == 0) {
        $vb_flnr = $_SESSION[$module]['vb_flnr'] ;
        $neu = array(
            "vb_flnr" => 0,
            "vb_unterseiten" => "J",
            "vb_datum" => '20250504',
            "vb_titel" => "120 j",
            'vb_beschreibung' => 'OLDIES',
            'vb_urheb' => '',
            'vb_fzg_beschr' => 'J',
            "vb_uid" => "",
            "vb_aenddat" => ""
        );

        /**
         * Variable für die Fotos
         *
         * @var array $eig_foto = Urheber|Foto_nr
         */
        /*
        $eig_foto = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 2) == "dm") {
                $urh_arr[] = $value;
            }
        }
        var_dump($urh_arr);
        */
    } else {
        
        $_SESSION[$module]['BERI']['vb_flnr'] = $vb_flnr;
        

        $sql = "SELECT * FROM vb_bericht_4 WHERE vb_flnr = '$vb_flnr' ";

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);

        $neu = mysqli_fetch_array($result);

        $vb_flnr = $neu['vb_flnr'];
       
      //  $d_arr = explode("-", $neu['vb_datum']);
      //  $_SESSION[$module]['dm_aufn_d'] = $d_arr[0] . $d_arr[1] . $d_arr[2];
        $sql = "SELECT * FROM vb_ber_detail_4 WHERE  vb_flnr='$vb_flnr' ORDER BY vd_unter,vd_suffix,vd_foto ASC ";
        $return = SQL_QUERY($db, $sql);
        if ($return) {
            $num_rows = mysqli_num_rows($return);
            if ($num_rows >= 1) {
                while ($row = mysqli_fetch_assoc($return)) {
                    $ber_arr[$row['vd_flnr']] = $row;
                }
            }
        } else { // Fotos aus Libs suchen
            
        }

      
    }
  
    if ($debug) {
        echo '<pre class=debug>';
        echo '<hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }
}
 
# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu mit den Werten aus der vorhergehende Phase
# -------------------------------------------------------------------------------------------------------

if ($phase == 1) // prüfe die Werte in array $neu 
{
    foreach ($_POST as $name => $value) {
        $neu[$name] = trim(mysqli_real_escape_string($db, $value));
    }

    if ($Err_Nr > 0) {
        $phase = 0;
    }
}

if ($phase == 2) { # Werte von FO_List_Ber_det.php
    
    
    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    /**
     * Array für den Update von vb_bericht_4
     *
     * @var array $arr_ber
     */
    $arr_ber = array();

    /**
     * Array für den update von vb_ber_detail_4
     *
     * @var array $arr_det
     */
    $arr_det = array();

    /**
     * Array für den update von fo_todaten_Urh
     *
     * @var array $arr_fot
     */
    $arr_fot = array();

    foreach ($neu as $key => $value) {

        if ($key == "vb_flnr") {
            $arr_ber['vb_flnr'] = $value;
            $arr_ber_det[] = $value;
            $vb_flnr = $value;
            continue;
        }
        if ($key == "vb_titel") {
            $arr_ber['vb_titel'] = $value;
            continue;
        }
        if ($key == "vb_beschreibung") {
            $arr_ber['vb_beschreibung'] = $value;
            continue;
        }
        if ($key == "vb_flnr") {
            $arr_ber['vb_unterseiten'] = $value;
            continue;
        }

        $key_k = substr($key, 0, 5);
        if ($key_k == "vb_un") {
            $key_arr = explode("_", $key);
            if (count($key_arr) < 3) {
                continue;
            }
            $arr_ber_det[$key_arr[2]] = $key_arr[1] . "|" . $value . ";"; # key: RecordNr in ber_det begriff|Wert
            continue;
        }
        if ($key_k == "vb_su") {
            $key_arr = explode("_", $key);
            $arr_ber_det[$key_arr[2]] .= $key_arr[1] . "|" . $value . ";";
            continue;
        }
        if ($key_k == "vb_ti") {
            $key_arr = explode("_", $key);
            $arr_ber_det[$key_arr[2]] .= $key_arr[1] . "|" . $value . ";";
            if ($value != "") {
                $arr_ber_det[$key_arr[2]] .= "suffix|0;";
            }
            continue;
        }

        if ($key_k == "fo_be") {
            $key_arr = explode("_", $key); # fo_begltxt_foto-NR
            $arr_fot[$key_arr[2]] = $key_arr[3] . "|" . $key_arr[1] . "|" . $value; # foto.RecNr|Be
            continue;
        }
    }
}


switch ($phase) {
    case 0:
        require 'VF_FO_Ber_Edit_ph0.inc.php'; // erheben titlel und Form
        break;
    case 1:
        require 'VF_FO_Ber_Edit_ph1.inc.php'; // abspeichern Daten
        break;
    case 2:
        require 'VF_FO_Ber_Edit_ph2.inc.php'; // Bilder aussuchen/ editieren 
        break;
    case 99:
        echo "<a href='VF_FO_Ber_List.php?Act=" . $_SESSION[$module]['BERI']['Act'] . "'>Zurück zur Liste</a>";
        break;
}
ende:

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
    global $path2ROOT, $db, $T_List, $module, $fo_arr;
 #echo "L 0338 $tabelle ";print_r($row);echo "<br>row<br>";

    $pict_path = $path2ROOT . "login/AOrd_Verz/";

    $Tab_kurz = substr($tabelle, 0, 8);
    switch ($Tab_kurz) {

        case "vb_berich":
            break;
        case "vb_ber_d":
            $vd_flnr = $row['vd_flnr'];
            $vb_flnr = $row['vb_flnr'];
            $row['vd_flnr'] = "<a href='VF_BE_DE_Edit.php?ID=$vd_flnr&vb_flnr=$vb_flnr'  >" . $vd_flnr . "</a>";

            $vb_foto = $row['vb_foto'];
            $urh = $row['vb_foto_Urheber'];
            $md_urh = $vb_foto . "_" . $urh;
            if ($row['vb_foto'] != "") {
                VF_Displ_Urheb_n($row['vb_foto_Urheber']);
                $sql_fot = "SELECT * FROM dm_edien_$urh WHERE md_id='" . $row['vb_foto'] . "' ";

                $ret_foto = SQL_QUERY($db, $sql_fot);
# print_r($ret_foto);echo "<br>L 0360 $sql_fot <br>";
                if ($ret_foto) {
                    $row_foto = mysqli_fetch_assoc($ret_foto);
                    
                    if ($row_foto['md_media'] == "Audio") {
                        $pict_path .= $row_foto['md_eigner'] . "/09/02/";
                    } elseif ($row_foto['md_media'] == "Foto") {
                        $pict_path .= $row_foto['md_eigner'] . "/09/06/";
                    } else {
                        $pict_path .= $row_foto['md_eigner'] . "/09/10/";
                    }
                    # $pict_path .= $d_path = VF_set_PictPfad($row_foto['fo_aufn_datum'], $row_foto['fo_basepath'], $row_foto['fo_zus_pfad'], $row_foto['fo_aufn_suff']);

                    $md_id = $row_foto['md_id'];
                    
                    $row['md_id'] = "<a href='VF_FO_Edit.php?fo_id=$md_id&fo_eigner=" . $row_foto['md_eigner'] . "&verz=J' >" . $md_id . "</a>";
                    
                    $md_aufn_d = $row_foto['md_aufn_datum'];
                    if ($md_aufn_d != "") {
                        $md_aufn_d_s = $md_aufn_d."/";
                    }
                    $md_eigner = $row_foto['md_eigner'];
                    
                    $DsName = "";
                    if ($row_foto['md_dsn_1'] != "") {
                        $dsn = $row_foto['md_dsn_1'];

                        if ($row_foto['md_media'] == "Foto") {
                            $row['vb_foto'] = "<a href='$pict_path$md_aufn_d_s$dsn' target='_blank'><img src='$pict_path$md_aufn_d_s$dsn' alt='$dsn' height='200' ></a>";
                            $DsName = "<a href='VF_FO_Detail.php?id=$vb_foto&eig=$urh' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";
                        } else {
                            $d_path = $pict_path.$md_aufn_d_s;
                            $video = "
                            <video width='320' height='240' controls>
                            <source src='$d_path" . $row_foto['md_dsn_1'] . " type='video/mp4'>
                                
                             Your browser does not support the video tag.
                             </video>";
                            
                            $DsName = "<a href='$pict_path$dsn' target='_blank'>$video</a>";
                            $row['vb_foto'] = "$DsName<br>" . $_SESSION[$module][$sub_mod]['eig_urhname'];
                        }
                    }
                    
                    $row['md_beschreibg'] = "<textarea id='md_beschreibg_$md_urh' name='fo_beschreibg_$md_urh' rows='4' cols='50'>" . $row_foto['md_beschreibg'] . "</textarea>"; 
                }

                $vb_unter = $row['vb_unter'];
                $vb_suffix = $row['vb_suffix'];
                $row['vb_unter'] = "Unterseite: <input type='text' name='vb_unter_$vd_flnr' id='vb_unter_$vd_flnr' value='$vb_unter' maxlength='4'>";
                                
                $row['vb_unter'] .= "<br>Sortierung: <input type='text' name='vb_suffix_$vd_flnr' id='vb_suffix_$vd_flnr' value='$vb_suffix' maxlength='4'>";

                $vb_titel = $row['vb_titel'];
                $row['vb_titel'] = "<input type='text' name='vb_titel_$vd_flnr' id='vb_titel_$vd_flnr' value='$vb_titel' maxlength='60'>";
            }
            break;
              
        case "dm_edien":
            if ($row['md_media'] == "Audio") {
                $pict_path .= $row['md_eigner'] . "/09/02/";
            } elseif ($row['md_media'] == "Foto") {
                $pict_path .= $row['md_eigner'] . "/09/06/";
            } elseif ($row['md_media'] == "Video") {
                $pict_path .= $row['mf_eigner'] . "/09/10/";
            }
            $nodat = false;
           if ($row['md_aufn_datum'] != "") {
                $d_path = $pict_path . $row['md_aufn_datum']."/" ;
                $nodat = true;
            }
       
            if ($row['md_aufn_datum'] != "" AND !$nodat) {
                $d_path .= $row['md_aufn_datum']."/" ;
            }
              
            $md_id = $row['md_id'];

            $row['md_id'] = "<a href='VF_FO_Edit.php?fo_id=$md_id&md_eigner=" . $row['fo_eigner'] . "&verz=J' >" . $md_id . "</a>";

            $md_aufn_d = $row['md_aufn_datum'];
            $md_eigner = $row['md_eigner'];

            if ($row['md_dsn_1'] != "") {
                $dsn = $row['md_dsn_1'];

                if ($row_foto['md_media'] == "Audio") {
                    #$pict_path .= $row['md_eigner'] . "/09/02/";
                } elseif ($row_foto['md_media'] == "Foto") {
                    $row['md_dsn_1'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
                } elseif ($row_foto['md_media'] == "Video") {
                    $video = "
                        <video width='320' height='240' controls>
                        <source src='$pict_path" . $row['fo_dsn'] . " type='video/mp4'>
                            
                        Your browser does not support the video tag.
                        </video>";
                    
                    $row['fo_dsn'] = "<a href='$pict_path$dsn' target='_blank'>$video</a>";
                }
      
                if ($row['md_namen'] != "") {
                    $row['md_dsn_1'] .= "<br>".$row['md_namen'];
                }
            }
            $checked = "";
            if (in_array($md_id,$fo_arr[$md_eigner] )) {
                $checked = "checked";
            }
            $row['in Bericht'] = "<input type='checkbox' id='$md_id' name='$md_id' value='$md_eigner|$md_id' $checked >in den Bericht nehmen";

            break;
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>
