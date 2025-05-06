<?php

/**
 * Fahrzeug- Foto- Umstellung auf Foto-Libs (AOrd_Verz/eigner/MaF 
 *     auf AOrdd_Verz/Urheber/09/06/datum
 *      muss für alle Tabellen, die Fotos beschreiben gemacht werden.
 *
 * @author Josef Rohowsky - neu 2025
 *
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_G_U';
$module = Module_Name;
$tabelle = 'ma_fz_beschr';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = true; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';
# require $path2ROOT . 'login/common/BA_XRF_Comm.lib.php';

$flow_list = True;

$LinkDB_database  = '';
$db = LinkDB('VFH');

$prot = True;
$header = "";

BA_HTML_header('Neue Foto- Zuordnung aus FO_Lib', $header, 'Form', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();


/** einlesen aller Foto- Libs mit  .WebP */

$sql_fo = "SELECT * FROM fo_index ";
$ret_fo = SQL_QUERY($db,$sql_fo);

if ($ret_fo) {
    $num_fo = mysqli_num_rows($ret_fo);
    if ($num_fo === 0) {
        #echo "L 061 <br>";
        #global $file_info; // All the file paths will be pushed here
        $file_info = array();
       
        $verz = scandir("AOrd_Verz");
        # var_dump($verz);echo "<br>L 066 <br>";
        foreach($verz as $sver ) {
            #echo "L 068 sver $sver <br>";
            if ($sver != "." || $sver != ".." ) {
                #echo "L 071 sver $sver <br>";
                $verz_sub = scandir("AOrd_Verz/$sver/09/06/");
                #var_dump($verz_sub);echo "<br>L 073 <br>";
                if (is_array($verz_sub)) {
                    foreach($verz_sub as $targ) {
                        #echo "L 075 targ $targ <br>";
                        if ($targ != "." || $targ != "..") {
                            $tar_sub = scandir("AOrd_Verz/$sver/09/06/$targ");
                            #var_dump($tar_sub);echo "<br>L 079 <br>";
                            if (is_array($tar_sub)) {
                                foreach ($tar_sub as $tfile) {
                                    #echo "L 081 tfile $tfile <br>";
                                    if ($tfile != "." || $tfile != "..") {
                                        if (is_dir($tfile)) {
                                            $suff_sub = scandir("AOrd_Verz/$sver/09/06/$targ/$tfile");
                                            if (is_array($suff_sub)) {
                                                foreach ($suff_sub as $sufile)
                                                if (stripos($tfile,".WebP")) {
                                                    $f_a = explode("-",$sufile);
                                                    #var_dump($f_a);echo "<br>L 089<br>";
                                                    $f_cnt = count($f_a) -1;
                                                    #echo "L 087 f_cnt $f_cnt <bR>";
                                                    $fn_a = explode(".",$f_a[$f_cnt]);
                                                    #var_dump($fn_a);echo "<br>L 093 fn_a <br>";
                                                    $file_info[] = array($fn_a[0],"AOrd_Verz/$sver/09/06/$targ/$tfile/$sufile");
                                                }
                                                
                                            }
                                        } else {
                                            if (stripos($tfile,".WebP")) {
                                                $f_a = explode("-",$tfile);
                                                #var_dump($f_a);echo "<br>L 086<br>";
                                                $f_cnt = count($f_a) -1;
                                                #echo "L 087 f_cnt $f_cnt <bR>";
                                                $fn_a = explode(".",$f_a[$f_cnt]);
                                                #var_dump($fn_a);echo "<br>L 089 fn_a <br>";
                                                $file_info[] = array($fn_a[0],"AOrd_Verz/$sver/09/06/$targ/$tfile");
                                            }
                                        }
                                       
                                        
                                    }
                                    
                                }
                            }
                            
                            
                            
                            
                        }
                    }
                }
               
 
              
            }
        }
        
        var_dump($file_info);echo "<br>L 0128 <br>";
        
        /** abspeichern des Foto-Index
         * 
         */

        foreach ($file_info as $kurz => $path) {
            
            
            $sql_fo_o = "INSERT INTO fo_index (
                o_name, o_pfad 
              ) VALUES (
                '$path[0]','$path[1]'
               )";
            
            $ret_fo_o = SQL_QUERY($db, $sql_fo_o);
            
        }
        exit;
        
        
    }
}

$ar_arr = $fo_arr = $fz_arr = $maf_arr = $fm_arr = $muf_arr = $mug_arr = $ge_arr = $mag_arr = $in_arr  =  $zt_arr = $arcxr_arr = $mar_arr = array();
$ret = VF_tableExist();

if ($ret) {
    var_dump($maf_arr);echo "<br>L 0155 <br>";
    foreach ( $maf_arr as $table_nm) {
        $sql_tabs = "SELECT * FROM ma_fz_besch_tabs WHERE f_tabname like '%$table_nm%' ";
        $ret_tabs = SQL_QUERY($dn,$sql_tabs);
        $num_tabs = mysqli_num_rows($ret_tabs);
        if ($num_tabs != 0 ) {
            $row_tabs = mysqli_fetch_object($ret_tabs);
            if ($row_tabs->f_done == 'J') {
                continue;
            }
            $sql_ma = "SELECT * FROM $table_nm ";
            $ret_ma = SQL_QUERY($db.$sql_ma);
            
            
            
            
            
            
        }
        
        
        
        
        
    }
    
}

$sql_tabs = "SELECT * FROM ma_fz_besch_tabs WHERE f_tabname like '%$table_nm%' ";
$ret_tabs = SQL_QUERY($dn,$sql_tabs);

if ($ret_tabs) {
    $num_tabs = mysqli_num_rows($ret_tabs);
    if ($num_tabs === 0) {
        /** einlesen der möglichen Tabellen ma_fz_beschr_ (Welche gibt es?) , wenn ma_fz_besch_tabs rows = 0 */
        
        
        /** schreiben ma_fz_besch_tabs */
     #   `fo_lib` set('J','N','') DEFAULT NULL COMMENT 'J - Foto lib nutzen', 
        
        
    } else {
        
    }
}

$sql_r = "SELECT * FROM ma_fz_besch_tabs WHERE f_done = '' ";
$ret_r = SQL_QUERY($db,$sql_r);

if ($ret_r) {
    WHILE ($row_r = mysqli_fetch_object($ret_r)) {
        
        
        
    }
}

/** abarbeiten der tabs, je Eigner, max 30 items, dann Anzeige zur Auswahl eventueller Fotos */


/** Auswahl der Fotos abspeichern, in f_tabs und fo_todaten_xy */









exit;
##### alter code 
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
    $fz_id = $_GET['ID'];
} else {
    $fz_id = "";
}
if (isset($_GET['fz_id'])) {
    $fz_id = $_GET['fz_id'];
}

if ($phase == 99) {
    header('Location: VF_FA_List.php');
}

if ($fz_id != "") {
    $_SESSION[$module]['fz_id'] = $fz_id;
} else {
    $fz_id = $_SESSION[$module]['fz_id'];
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!
$Tabellen_Spalten[] = 'sa_name';
$Tabellen_Spalten_COMMENT['sa_name'] = 'Ausgewählte Sammlung';

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------


$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_a = $tabelle . "_$eignr";

Tabellen_Spalten_parms($db, $tabelle_a);

if ($_SESSION[$module]['all_upd']) {

    $edit_allow = 1;
    $read_only = "";
} else {
    $edit_allow = 0;
    $read_only = "readonly";
}

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    $Tabellen_Spalten_COMMENT['ct_juroren'] = 'Juroren';
    $Tabellen_Spalten_COMMENT['ct_darstjahr'] = 'Darstellungs- Jahr';
    if ($_SESSION[$module]['fz_id'] == 0) {

        $neu = array(
            'fz_id' => 0,
            'fz_eignr' => "",
            "fz_invnr" => "0",
            "sa_name" => "Kraftfahrzeug",
            'fz_name' => "",
            'fz_taktbez' => "",
            'fz_indienstst' => "",
            'fz_ausdienst' => "",
            'fz_zeitraum' => "",
            "fz_komment" => "",
            'fz_sammlg' => $_SESSION[$module]['sammlung'],
            'fz_bild_1' => "",
            'fz_b_1_komm' => "",
            'fz_bild_2' => "",
            'fz_b_2_komm' => "",
            'fz_zustand' => "",
            'fz_ctifklass' => "",
            'fz_ctifdate' => "",
            "fz_beschreibg_det" => "",
            "fz_eigent_freig" => "",
            "fz_verfueg_freig" => "",
            "fz_pruefg_id" => "",
            "fz_pruefg" => "",
            "fz_aenduid" => "",
            "fz_aenddat" => "",
            "ct_darstjahr" => "",
            "ct_juroren" => "",
            "fz_herstell_fg" => "",
            "fz_baujahr" => ""
        );
    } else {

        #$sql_be = "SELECT * FROM $tabelle_a WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' ORDER BY `fz_id` ASC";
        
        $sql_be = "SELECT *
        FROM $tabelle_a
        LEFT JOIN fh_sammlung ON $tabelle_a.fz_sammlg = fh_sammlung.sa_sammlg 
        WHERE `fz_id` = '" . $_SESSION[$module]['fz_id'] . "' OR fh_sammlung.sa_sammlg IS NULL ORDER BY `fz_id` ASC";
        
        $return_be = SQL_QUERY($db, $sql_be);

        $neu = mysqli_fetch_array($return_be);
        mysqli_free_result($return_be);
        
        $_SESSION[$module]['fz_id_a'] = $neu['fz_id'];
        if ($neu['fz_sammlg'] != "") {
            $_SESSION[$module]['fz_sammlg'] = $neu['fz_sammlg'];
        }

        $sql_in = "SELECT * FROM fz_ctif_klass WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "' AND `fz_eignr`='" . $_SESSION['Eigner']['eig_eigner'] . "'";
        $return_in = SQL_QUERY($db, $sql_in);
        $num_rows = mysqli_num_rows($return_in);
        if ($num_rows >= 1) {
            while ($row = mysqli_fetch_object($return_in)) {
                $neu['ct_juroren'] = $row->fz_juroren;
                $neu['ct_darstjahr'] = $row->fz_darstjahr;
            }
        } else {
            $neu['ct_darstjahr'] = '';
            $neu['ct_juroren'] = " ";
        }
    }
}

if ($phase == 1) {
    
}

switch ($phase) {
    case 0:
        require ('VF_FA_FZ_Edit_ph0.inc.php');
        break;
    case 1:
        require "VF_FA_FZ_Edit_ph1.inc.php";
        break;
}

BA_HTML_trailer();?>