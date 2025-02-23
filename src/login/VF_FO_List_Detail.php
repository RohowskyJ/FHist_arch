<?php

/**
 * Foto Liste Details (Fotos je Aufnahmedatum)
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'fo_todaten';

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
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

VF_chk_valid();

VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['fo_eigner'])) {
    $fo_eigner = $_GET['fo_eigner'];
} else {
    $fo_eigner = "";
}

if ($fo_eigner != "") {
    $_SESSION[$module]['eigner'] = $fo_eigner;
    VF_Displ_Eig($fo_eigner);
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION[$module]['eigner'] = "";
    }
}

if (isset($_GET['fo_aufn_d'])) {
    $_SESSION[$module]['fo_aufn_d'] = $fo_aufn_d = $_GET['fo_aufn_d'];
}

if (isset($_GET['fo_aufn_s'])) {
    $_SESSION[$module]['fo_aufn_s'] = $fo_aufn_s = $_GET['fo_aufn_s'];
}

if (isset($_GET['pf'])) {
    $basepath = $_GET['pf'];
}
if (isset($_GET['zupf'])) {
    $zus_pfad = $_GET['zupf'];
}
    

if (isset($_GET['pfad'])) {
    $_SESSION[$module]['pfad'] = $pfad = $_GET['pfad'];
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;

$reply = FO_Tab_gener(); // erstellen ergänzen der Tabellen nach Massuploads mit resize, wasserzeichen und *.WebP - Format

require "VF_FO_List_Detail.inc.php";

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
    global $path2ROOT, $T_List, $module, $fo_mandkennz, $pfad;

    $s_tab = substr($tabelle, 0, 8);
    $VF_Foto_Video = array(
        "Audio" => "Tonmaterial",
        "Foto" => "Fotografie",
        "Film" => "Filme",
        "Video" => "Videos"
    );

    switch ($s_tab) {
        case "fo_manda":
            $fm_id = $row['fm_id'];
            $fm_mandkennz = $row['fm_mandkennz'];
            $row['fm_id'] = "<a href='VF_O_FO_M_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            $fm_eigner = $row['fm_eigner'];
            $row['fm_eigner'] = "<a href='VF_O_FO_List.php?fm_eigner=$fm_eigner'  target='Foto'>" . $fm_eigner . " Fotos </a>";
            break;

        case "fo_todat":
            $fo_id = $row['fo_id'];
            console_log("L0145 $fo_id");
            $row['fo_id'] = "<a href='VF_FO_Edit.php?fo_id=$fo_id&fo_eigner=" . $row['fo_eigner'] . "&verz=N' >" . $fo_id . "</a>";
            if ($row['fo_dsn'] == "") {
                console_log("L 0148 ".$row['fo_aufn_suff']);
                $_SESSION[$module]['fo_aufn_d'] = $row['fo_aufn_datum'];
                $_SESSION[$module]['fo_aufn_s'] = $row['fo_aufn_suff'];
                $_SESSION[$module]['fo_base'] = $row['fo_basepath'];
                $_SESSION[$module]['fo_zus'] = $row['fo_zus_pfad'];
                
            }           
            /*
             * if ($row['fo_dsn'] != "") {
             * $dsn = $row['fo_dsn'];
             * $d_path = $pict_path.$row['fo_aufn_datum']."/";
             *
             * $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
             * }
             */

            $row['fo_media'] = $VF_Foto_Video[$row['fo_media']];
            if ($row['fo_dsn'] != "") {

                $dsn = $row['fo_dsn'];

                if ($row['fo_typ'] == "F") {
                    $fo_d_spl = explode("-", $dsn);
                    $cnt_f_d = count($fo_d_spl);

                    $file_arr = explode("-", $dsn);

                    $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/06/";

                    $f_path = VF_set_PictPfad($row['fo_aufn_datum'],$row['fo_basepath'],$row['fo_zus_pfad'],$row['fo_aufn_suff']);
                    $d_path = $pict_path . $f_path ;
                    

                    $Urh_anz = "Urheber: " . $row['fo_Urheber'];
                    # $row['fo_dsn'] = "<a href='VF_O_FO_Detail.php?eig=" . $row['fo_eigner'] . "&id=$fo_id' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a><br>" . $_SESSION[$module]['URHEBER']['fm_beschreibg'] . "<br>" . $d_path . $dsn;
                    $row['fo_dsn'] = "<a href='VF_FO_Detail.php?eig=" . $row['fo_eigner'] . "&id=$fo_id' target='_blank'><img src='$d_path$dsn' alt='$dsn' width='250' ></a><br>$Urh_anz<br>" . $d_path . $dsn;

                    $begltext = $row['fo_begltxt'];
                    # $row['fo_begltxt'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' >$begltext</a>";
                } else {
                    $vi_d_spl = explode("-", $dsn);
                    $cnt_f_d = count($vi_d_spl);
                    if ($cnt_f_d >= 2 and stripos($row['fo_basepath'], "ARCHIV_VFHNOe")) {
                        $pict_path = "../login/AOrd_Verz/" . $row['fo_eigner'] . "/09/10/";
                        $d_path = $pict_path . $row['fo_basepath'] . "/";
                    } else {
                        $pict_path = "login/AOrd_Verz/" . $row['fo_eigner'] . "/09/10/";
                        $d_path = $pict_path . $row['fo_aufn_datum'] . "/";
                    }

                    /*
                     * $video = "
                     * <video width='320' height='240' controls>
                     * <source src='$d_path".$row['fo_dsn']." type='video/mp4'>
                     *
                     * Your browser does not support the video tag.
                     * </video>";
                     *
                     * $row['fo_dsn'] = $video; # "<a href='$d_path$dsn' target='_blank'>Video ansehen</a>";
                     */
                    # $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";

                    $begltext = $row['fo_begltxt'];
                    $row['fo_begltxt'] = "<a href='www.feuerwehrhistoriker.at/$pict_path$dsn' target='_blank'>$begltext</a>";
                    $row['fo_dsn'] = "";
                    #echo "L 0202 fo_begltxt " . $row['fo_begltxt'] . " <br>";
                }
            }

            $fo_aufn_datum = $row['fo_aufn_datum'];

            break;
    }

    return True;
} # Ende von Function modifyRow

/**
 * Generiere update Tabelleneinträge nach Massupdate mit *WebP updates.
 * @author J. Rohowsky  - neu 2025
 * 
 *
 * Tabelleneinträge einlesen, Speicherort dir einlesen, vergleichen.
 * Anzahl foto+1 gleich records-anzahl
 * dann Inhalt Speicher kein *.WebP  - Abbruch, keine Aktion erforderlich
 * wenn Speicherplatz alle *.WebP : Vergleich alle fo_dsn entsprechend *.WebP - OK
 * wenn fo__dsn != *.WebP fo_dsn korrigieren, alte *.Graf löschen 
 */
Function Fo_Tab_gener() {
    global $module, $debug, $db;
    

    if ($debug) {
        echo "<pre class=debug>func FO_Tab_gener ist gestarted</pre>";
    }

    /**
     * Tabelle einlesen für Aufnahmedatum
     */
    #var_dump($_SESSION[$module]['URHEBER']);
    $eignr = $_SESSION['Eigner']['eig_eigner'];
    $fo_typ = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ'];
    $tabelle_g = "fo_todaten_".$_SESSION[$module]['URHEBER']['ei_id'];
    $sql_g = "SELECT * FROM $tabelle_g  WHERE fo_aufn_datum='" . $_SESSION[$module]['fo_aufn_d'] . "' AND fo_aufn_suff ='" . $_SESSION[$module]['fo_aufn_s'] . "' ";
    # echo "L 0244 sql_g $sql_g <br>";
    $return_g = SQL_QUERY($db,$sql_g);
    #var_dump($return_g);
    
    $tab_arr = array();
    $notfirst = False;
    while ($row = mysqli_fetch_object($return_g)) {
        if (!$notfirst) {
            $fo_eigner     = $row->fo_eigner;
            $fo_Urheber    = $row->fo_Urheber;
            $fo_Urh_kurzz  = $row->fo_Urh_kurzz;
            $fo_aufn_datum = $row->fo_aufn_datum;
            $fo_aufn_suff  = $row->fo_aufn_suff;
            $fo_basepath   = $row->fo_basepath;
            $fo_zus_pfad   = $row->fo_zus_pfad;
            $fo_begltxt    = $row->fo_begltxt;
            $fo_typ        = $row->fo_typ;
            $fo_media      = $row->fo_media;
            $fo_namen      = $row->fo_namen;
            $fo_sammlg     = $row->fo_sammlg;
            $fo_uidaend    = $row->fo_uidaend;
            $notfirst = True;
        }
        
        $tab_arr[$row->fo_id] = $row->fo_dsn;
    }
    #var_dump($tab_arr);
    $tab_len = count($tab_arr);
    
    if ($fo_typ == "F") {
        $ao = '06';
    } elseif ($fo_typ == "V") {
        $ao = '09';
    }
    
    $pict_pfad = "AOrd_Verz/$fo_eigner/09/$ao/".VF_set_PictPfad($fo_aufn_datum, $fo_basepath, $fo_zus_pfad, $fo_aufn_suff);
   # echo "L 277 pict_pfad $pict_pfad<br>";
    /**
     * Einlesen der Daten der Speicherortes
     */
    if (is_dir($pict_pfad)) {
        $verz_arr = array();
        foreach (scandir($pict_pfad) as $file) {
            if ($file === ".." OR $file === "." ) continue;
            if (stripos($file,"WebP")) {
                $verz_arr[] = $file;
            }
        }
        
        $verz_cnt = $verz_arr;
        #var_dump($verz_arr);
        if ($verz_cnt != 0) {
            if ($tab_len <= 1 ) { // Datensätze für Fotos einfügen, so vorhanden
                
                foreach ($verz_arr as $foto_dsn ) {
                    $nfn_info = pathinfo($foto_dsn);
                    $nfn_name = $nfn_info['filename'];
                    $nfn_dsn  = $nfn_info['basename'];
                    $nfn_ext =  $nfn_info['extension'];
                    $nfn_dir =  $nfn_info['dirname'];
                    
                    $sql = "INSERT INTO $tabelle_g (
                          fo_eigner,fo_urheber,fo_Urh_kurzz,fo_aufn_datum,fo_aufn_suff,fo_dsn,fo_begltxt,fo_namen,
                          fo_sammlg,fo_typ,fo_media,fo_basepath,fo_zus_pfad,
                          fo_uidaend
                      ) VALUE (
                         '$fo_eigner','$fo_Urheber','$fo_Urh_kurzz','$fo_aufn_datum','$fo_aufn_suff','$nfn_dsn','$fo_begltxt','$fo_namen',
                         '$fo_sammlg','$fo_typ','$fo_media','$fo_basepath','$fo_zus_pfad',
                         '$fo_uidaend'
                      )";
                    
                    $result = SQL_QUERY($db, $sql);
                 
                }
            } elseif ($tab_len >= 1 ) { // Vergleiche Tabellen- Records mit Verzeichnis
                #var_dump($tab_arr);
                #var_dump($verz_arr);
                foreach ( $tab_arr as $o_key => $o_dsn) {
                    if ($o_dsn == "") {continue;}
                    #echo "L 0327 o_dsn n$o_dsn <br>";
                    $ofn_info = pathinfo($o_dsn);
                    #var_dump($ofn_info);
                    $o_name = $ofn_info['filename'];
                    $o_dsn  = $ofn_info['basename'];
                    $o_ext  = $ofn_info['extension'];
                    $o_dir  = $ofn_info['dirname'];
                    #echo "L 0335 o_name $o_name <br>";
                    
                    
                    foreach ($verz_arr as $n_dsn) {
                        $nfn_info = pathinfo($n_dsn);
                        $n_name = $nfn_info['filename'];
                        $n_dsn  = $nfn_info['basename'];
                        $n_ext  = $nfn_info['extension'];
                        $n_dir  = $nfn_info['dirname'];
                        #echo "L 0344 nfn_name $n_name <br>";
                        
                        if ($o_name == $n_name) {
                            if ($o_ext == "WebP") {  
                                if ($o_dsn == $n_dsn) { // identisch, keine aktion erforderlich, löschen der Arr- Einträge
                                    unset($tab_arr['$o_key']);
                                    unset($verz_arr['n_dsn']);
                                    #var_dump($tab_arr);
                                    #var_dump($verz_arr);
                                } else { // replace with new, delete old, unset arr- entry
                                    #echo "L 0354 replace $o_dsn with $n_dsn <br>";
                                    $ret = tab_update($tabelle_g,$o_key,$o_dsn,$n_dsn) ;
                                    if ($ret) {
                                        unset($tab_arr[$o_key]);
                                        unset($verz_arr[$n_dsn]);
                                        unlink($pict_pfad.$o_dsn);
                                    }
                                    
                                }
                            } else { // replace o_dsn with n_dsn, del o_dsn, del arr entries
                                #echo "L 0364 replace $o_dsn with $n_dsn <br>";
                                $ret = tab_update($tabelle_g,$o_key,$o_dsn,$n_dsn) ;
                                if ($ret) {
                                    unset($tab_arr[$o_key]);
                                    unset($verz_arr[$n_dsn]);
                                    unlink($pict_pfad.$o_dsn);
                                }
                            }
                        }
                    }

                }
                
                // add all remaining from verz_arr
              
                #echo "L 0378 add $n_dsn <br>";
                #var_dump($tab_arr);
                #var_dump($verz_arr);
                

                
            }
        }
    }

    
}
return True;

/**
 * Funktion zum austauschen des Fo-dsn - Namens
 * 
 */
function tab_update($tabelle,$record,$odsn,$ndsn) {
    global $db,$module,$debug;
    
    $sql_in = "SELECT fo_id,fo_dsn FROM $tabelle where fo_id = '$record' ";
    $ret_in = SQL_QUERY($db,$sql_in);
    while ($row_in = mysqli_fetch_object($ret_in))  {
        if (mysqli_num_rows($ret_in) >=2) { 
            echo "Fehler, sollte nur ein Record sein. Abbruch.<br>";
            return FALSE;
        } else {
            if ($row_in->fo_dsn == $odsn) { // Update mit $ndsn
                $sql_o = "UPDATE $tabelle set  fo_dsn = '$ndsn' where fo_id = '$record' ";
                $ret_o = SQL_QUERY($db,$sql_o);  
                
                return True;
            }
        }
    }
} # end funktion tab_update
 

?>
