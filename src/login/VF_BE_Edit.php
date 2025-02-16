<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;

const Tabellen_Name = 'vb_bericht_4';

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
# require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

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
    $phase = 95;
}
if (isset($_GET['vb_flnr'])) {
    $_SESSION[$module]['vb_flnr'] = $vb_flnr = $_GET['vb_flnr'];
    if ($_GET['vb_flnr'] == 0) {
        $phase = 95;
    } else {
        $phase = 0;
    }
} 

# ===========================================================================================================
# die HTML Seite ausgeben
# ===========================================================================================================
$Err_Msg = $Err_Urheb = "";
$Err_Nr = 0;

if ($phase == 99) {
    header("Localtion: VF_BE_List.php?Act=" . $_SESSION[$module]['Act']);
}

# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, Tabellen_Name);

unset($_SESSION[$module]['Urheber_List']);

VF_Sel_Urheber_n();


// ==============================================================================================================
if ($phase == 95) // Einlesen Berichsts- Datum
{
    if ($_SESSION[$module]['vb_flnr'] == 0) {
        $neu = array(
            "vb_flnr" => 0,
            "vb_datum" => ""
        ); # ,"vb_titel"=>"test");
    }
}

// ============================================================================================================
if ($phase == 96) // Einlesen der vorhandenen Foto-Saätze aller Urheber/Eigentümer
{
    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
    $_SESSION[$module]['fo_aufn_d'] = $veranst_datum = $neu['vb_datum'];
}

if ($phase == 0) {
    if ($_SESSION[$module]['vb_flnr'] == 0) {
        $vb_flnr = $_SESSION[$module]['vb_flnr'] ;
        $neu = array(
            "vb_flnr" => 0,
            "vb_unterseiten" => "Keine",
            "vb_datum" => $_SESSION[$module]['fo_aufn_d'],
            "vb_titel" => "",
            'vb_beschreibung' => '',
            'vb_urheb' => '',
            "vb_uid" => "",
            "vb_aenddat" => ""
        );

        /**
         * Variable für die Fotos
         *
         * @var array $eig_foto = Urheber|Foto_nr
         */
        $eig_foto = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 2) == "fo") {
                $eig_foto[] = $value;
            }
        }
    } else {
        $_SESSION[$module]['vb_flnr'] = $vb_flnr;
        $_SESSION[$module]['Fo']['FOTO'] = False;
        $_SESSION[$module]['Fo']['BERI'] = True;

        $sql = "SELECT * FROM vb_bericht_4 WHERE vb_flnr = '$vb_flnr' ";

        # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);

        $neu = mysqli_fetch_array($result);
        
        if ($neu['vb_urheb'] != "") {
            VF_Displ_Eig($neu['vb_urheb']);
            VF_Displ_Urheb_n($neu['vb_urheb'],"F");
        }

        $vb_flnr = $neu['vb_flnr'];
        $d_arr = explode("-", $neu['vb_datum']);
        $_SESSION[$module]['fo_aufn_d'] = $d_arr[0] . $d_arr[1] . $d_arr[2];
        $sql = "SELECT * FROM vb_ber_detail_4 WHERE  vb_flnr='$vb_flnr' ";
        $return = SQL_QUERY($db, $sql);

        while ($row = mysqli_fetch_assoc($return)) {
            $eig_foto[] = $row['vb_foto_Urheber'] . "|" . $row['vb_foto'];
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

// ============================================================================================================
if ($phase == 1) // prüfe die Werte in array $neu <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                  // ============================================================================================================
{
    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
    
    $ber_fo_arr = array();
    $i = 0;
    foreach ($neu as $key => $value) {
        if (is_numeric($key)) {
            $fo_arr = explode("|", $value);
            $ber_fo_arr[$i] = $value; # array($fo_arr[0] => $fo_arr[1]); # key is Fortl. Nr, Inhalt Urheber-Nummer => Record- Nr.
            $i ++;
        }
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

# ====================================================================================================
# Anzeigen
# ====================================================================================================

$header = "
";

BA_HTML_header('Veranstaltungs Definition', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 95:
        require "VF_BE_Edit_ph95.inc.php"; // Veranstaltugs- Datum einlesen
        break;
    case 96:
        require "VF_BE_Edit_ph96.inc.php"; // Liste der zu diesem Datum verfügbaren Fotos der Urheber
        break;
    case 0:
        require 'VF_BE_Edit_ph0.inc.php';
        break;
    case 1:
        require 'VF_BE_Edit_ph1.inc.php';
        break;
    case 2:
        require 'VF_BE_Edit_ph2.inc.php';
        break;
    case 99:
        echo "<a href='VF_BE_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a>";
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
            $fo_urh = $vb_foto . "_" . $urh;
            if ($row['vb_foto'] != "") {
                VF_Displ_Urheb_n($row['vb_foto_Urheber']);
                $sql_fot = "SELECT * FROM fo_todaten_$urh WHERE fo_id='" . $row['vb_foto'] . "' ";

                $ret_foto = SQL_QUERY($db, $sql_fot);
# print_r($ret_foto);echo "<br>L 0360 $sql_fot <br>";
                if ($ret_foto) {
                    $row_foto = mysqli_fetch_assoc($ret_foto);

                    if ($_SESSION[$module]['URHEBER']['BE']['ei_media'] == "F") {
                        $pict_path .= $row_foto['fo_eigner'] . "/09/06/";
                    } else {
                        $pict_path .= $row_foto['fo_eigner'] . "/09/07/";
                    }
                    $pict_path .= $d_path = VF_set_PictPfad($row_foto['fo_aufn_datum'], $row_foto['fo_basepath'], $row_foto['fo_zus_pfad'], $row_foto['fo_aufn_suff']);

                    $fo_id = $row_foto['fo_id'];
                    
                    $row['fo_id'] = "<a href='VF_FO_Edit.php?fo_id=$fo_id&fo_eigner=" . $row_foto['fo_eigner'] . "&verz=J' >" . $fo_id . "</a>";
                    
                    $fo_aufn_d = $row_foto['fo_aufn_datum'];
                    $fo_eigner = $row_foto['fo_eigner'];
                       $DsName = "";
                    if ($row_foto['fo_dsn'] != "") {
                        $dsn = $row_foto['fo_dsn'];

                        if ($_SESSION[$module]['URHEBER']['BE']['ei_media'] == "F") {
                            $row['vb_foto'] = "<a href='$pict_path$dsn' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";
                            $DsName = "<a href='VF_FO_Detail.php?id=$vb_foto&eig=$urh' target='_blank'><img src='$pict_path$dsn' alt='$dsn' height='200' ></a>";
                        } else {
                            $video = "
                        <video width='320' height='240' controls>
                        <source src='$d_path" . $row_foto['fo_dsn'] . " type='video/mp4'>
                            
                        Your browser does not support the video tag.
                        </video>";
                            
                            $DsName = "<a href='$pict_path$dsn' target='_blank'>$video</a>";
                        }
                    }
                    
                    $row['vb_foto'] = "$DsName<br>" . $_SESSION[$module]['URHEBER']['BE']['ei_urheber'];
                    $row['fo_begltxt'] = "<textarea id='fo_begltxt_$fo_urh' name='fo_begltxt_$fo_urh' rows='4' cols='50'>" . $row_foto['fo_begltxt'] . "</textarea>"; 
                }

                $vb_unter = $row['vb_unter'];
                $vb_suffix = $row['vb_suffix'];
                $row['vb_unter'] = "Unterseite: <input type='text' name='vb_unter_$vd_flnr' id='vb_unter_$vd_flnr' value='$vb_unter' maxlength='4'>";
                                
                $row['vb_unter'] .= "<br>Sortierung: <input type='text' name='vb_suffix_$vd_flnr' id='vb_suffix_$vd_flnr' value='$vb_suffix' maxlength='4'>";

                $vb_titel = $row['vb_titel'];
                $row['vb_titel'] = "<input type='text' name='vb_titel_$vd_flnr' id='vb_titel_$vd_flnr' value='$vb_titel' maxlength='60'>";
            }
            break;

        case "fo_todat":

            if ($_SESSION[$module]['URHEBER']['BE']['fm_typ'] == "F") {
                $pict_path .= $row['fo_eigner'] . "/09/06/";
            } else {
                $pict_path .= $row['fo_eigner'] . "/09/07/";
            }
            $nodat = false;
            if ($row['fo_basepath'] != "") {
                $d_path = $pict_path . $row['fo_basepath']."/" ;
            } elseif ($row['fo_aufn_datum'] != "") {
                $d_path = $pict_path . $row['fo_aufn_datum']."/" ;
                $nodat = true;
            }
            
            if ($row['fo_zus_pfad'] != "") {
                $d_path .= $row['fo_zus_pfad'] . "/";
            } else {
                #$d_path .=  "/";
            }
            if ($row['fo_aufn_datum'] != "" AND !$nodat) {
                $d_path .= $row['fo_aufn_datum']."/" ;
            }
              
            $fo_id = $row['fo_id'];

            $row['fo_id'] = "<a href='VF_FO_Edit.php?fo_id=$fo_id&fo_eigner=" . $row['fo_eigner'] . "&verz=J' >" . $fo_id . "</a>";

            $fo_aufn_d = $row['fo_aufn_datum'];
            $fo_eigner = $row['fo_eigner'];

            if ($row['fo_dsn'] != "") {
                $dsn = $row['fo_dsn'];

                if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
                    $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
                   
                } else {
                    $video = "
                        <video width='320' height='240' controls>
                        <source src='$pict_path" . $row['fo_dsn'] . " type='video/mp4'>
      
                        Your browser does not support the video tag.
                        </video>";

                    $row['fo_dsn'] = "<a href='$pict_path$dsn' target='_blank'>$video</a>";
                }
                if ($row['fo_namen'] != "") {
                    $row['fo_dsn'] .= "<br>".$row['fo_namen'];
                }
            }
            $checked = "";
            if (in_array($fo_id,$fo_arr[$fo_eigner] )) {
                $checked = "checked";
            }
            $row['in Bericht'] = "<input type='checkbox' id='$fo_id' name='$fo_id' value='$fo_eigner|$fo_id' $checked >in den Bericht nehmen";

            break;
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>
