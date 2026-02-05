<?php

/**
 * Foto Liste Details (Fotos je Aufnahmedatum)
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

$module  = 'OEF';
$sub_mod = 'Foto';

$tabelle = 'dm_edien_';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "medien des Urhebers " . $_SESSION[$module][$sub_mod]['eig_eigner'] . " - " . $_SESSION[$module][$sub_mod]['eig_verant'];

$header = "";
$jq_tabsort = $jq = true;
BA_HTML_header($title, '', 'Admin', '180em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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
    $_SESSION[$module][$sub_mod]['eigner'] = $fo_eigner;
    VF_Displ_Eig($fo_eigner,'U');
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION[$module][$sub_mod]['eigner'] = "";
    }
}

if (isset($_GET['md_aufn_d'])) {
    $_SESSION[$module]['md_aufn_d'] = $md_aufn_d = $_GET['md_aufn_d'];
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

# $reply = FO_Tab_gener(); // erstellen ergänzen der Tabellen nach Massuploads mit resize, wasserzeichen und *.jpeg - Format

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
        "Video" => "Videos"
    );
# var_dump($row);
    switch ($s_tab) {

        case "dm_edien":
            $md_id = $row['md_id'];
            #console_log("L0138 $md_id");
            $row['md_id'] = "<a href='VF_FO_Edit.php?md_id=$md_id&md_eigner=" . $row['md_eigner'] . "&verz=N' >" . $md_id . "</a>";
            if ($row['md_dsn_1'] == "") {   
                console_log("L 0141 ".$row['md_aufn_datum']);
                $_SESSION[$module]['md_aufn_d'] = $row['md_aufn_datum'];
            }           
            
            if ($row['md_dsn_1'] == '' || $row['md_dsn_1'] == '0_Verz') {
                $row['md_dsn_1'] = "Verzeichnis- Eintrag.";
            } else {
                $row['md_media'] = $VF_Foto_Video[$row['md_media']];
                if ($row['md_dsn_1'] != "") {
                    
                    $dsn = $row['md_dsn_1'];
                    
                    $bn_arr = pathinfo($dsn);
                    
                    $ext = "";
                    
                    if (in_array(strtolower($bn_arr['extension']),AudioFiles)) {
                        $ext = "Audio";
                    }
                    if (in_array(strtolower($bn_arr['extension']),GrafFiles)) {
                        $ext = "Graf";
                    }
                    if (in_array(strtolower($bn_arr['extension']),VideoFiles)) {
                        $ext = "Video";
                    }
                    
                    if ($ext == "Audio") {
                        $pict_path = "../login/AOrd_Verz/" . $row['md_eigner'] . "/09/02/";
                        
                        
                    } elseif ($ext == "Graf") {
                        $md_d_spl = explode("-", $dsn);
                        $cnt_f_d = count($md_d_spl);
                        
                        $file_arr = explode("-", $dsn);
                        
                        $pict_path = "../login/AOrd_Verz/" . $row['md_eigner'] . "/09/06/";
                        
                        $d_path = $pict_path . $row['md_aufn_datum'] ."/" ;
                        
                        
                        $Urh_anz = "Urheber: " . $row['md_Urheber'];
                        # $row['fo_dsn'] = "<a href='VF_O_FO_Detail.php?eig=" . $row['fo_eigner'] . "&id=$fo_id' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a><br>" . $_SESSION[$module]['URHEBER']['fm_beschreibg'] . "<br>" . $d_path . $dsn;
                        $row['md_dsn_1'] = "<a href='VF_FO_Detail.php?eig=" . $row['md_eigner'] . "&id=$md_id' target='_blank'><img src='$d_path$dsn' alt='$dsn' width='250' ></a><br>$Urh_anz<br>" . $d_path . $dsn;
                        
                        $begltext = $row['md_beschreibg'];
                        # $row['fo_begltxt'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' >$begltext</a>";
                        
                        $row['md_namen'] .= "<br>" . $row['md_suchbegr'];
                    } elseif ($ext == "Video") {
                        $vi_d_spl = explode("-", $dsn);
                        $cnt_f_d = count($vi_d_spl);
                        
                        $pict_path = "login/AOrd_Verz/" . $row['md_eigner'] . "/09/10/";
                        $d_path = $pict_path . $row['md_aufn_datum'] . "/";
                        
                        $begltext = $row['md_beschreibg'];
                        $row['md_beschreibg'] = "<a href='www.feuerwehrhistoriker.at/$pict_path$dsn' target='_blank'>$begltext</a>";
                        $row['md_dsn_1'] = "";
                        #echo "L 0202 fo_begltxt " . $row['fo_begltxt'] . " <br>";
                    }
                }
                
            }
            
            $md_aufn_datum = $row['md_aufn_datum'];

            break;
    }

    return True;
} # Ende von Function modifyRow

/**
 * Generiere update Tabelleneinträge nach Massupdate mit *jpeg updates.
 * @author J. Rohowsky  - neu 2025
 * 
 *
 * Tabelleneinträge einlesen, Speicherort dir einlesen, vergleichen.
 * Anzahl foto+1 gleich records-anzahl
 * dann Inhalt Speicher kein *.jpeg  - Abbruch, keine Aktion erforderlich
 * wenn Speicherplatz alle *.WebP : Vergleich alle fo_dsn entsprechend *.WebP - OK
 * wenn fo_dsn != *.jpeg fo_dsn korrigieren, alte *.Graf löschen 
 */
Function Fo_Tab_gener() {
    global $module, $sub_mod, $debug, $db;

    if ($debug) {
        echo "<pre class=debug>func FO_Tab_gener ist gestarted</pre>";
    }

    /**
     * Tabelle einlesen für Aufnahmedatum
     */
    # var_dump($_SESSION[$module]['URHEBER']);echo "<br>L 0244 sess[urhebg<br>";
    $urheb = $_SESSION[$module][$sub_mod]['eig_eigner'];
  
    $tabelle_g = "dm_edien_".$urheb;
    $sql_g = "SELECT * FROM $tabelle_g  WHERE md_aufn_datum='" . $_SESSION[$module]['md_aufn_d'] . "'  ";
    # echo "L 0249 sql_g $sql_g <br>";
    $return_g = SQL_QUERY($db,$sql_g);
    #var_dump($return_g);echo "<br>L 0251 return_g<br>";
    
    $tab_arr = array();
    $notfirst = False;
    while ($row = mysqli_fetch_object($return_g)) {
        if (!$notfirst) {
            $md_eigner     = $row->md_eigner;
            $md_Urheber    = $row->md_Urheber;
            $md_aufn_datum = $row->md_aufn_datum;
            $md_beschreibg = $row->md_beschreibg;
            $md_media      = $row->md_media;
            $md_namen      = $row->md_namen;
            $md_sammlg     = $row->md_sammlg;
            $md_aenduid    = $row->md_aenduid;
            $notfirst = True;
        }
        
        $tab_arr[$row->md_id] = $row->md_dsn_1;
    }
    
    /** einlesen Verzeichnis, suchen alle subdirs nach Aufnahmedatum, dann diese Verzeichnis einlesen */
    
    # echo "L 0267 md_media $md_media <br>";
    
    
    # var_dump($tab_arr);echo "<br>L 0275 tab_arr  <br>";
    $tab_len = count($tab_arr);
    $ao = "";
    if ($md_media == "Audio") {
        $ao = '02';
    } elseif ($md_media == "Foto") {
        $ao = '06';
    } elseif ($md_media == "Video") {
        $ao = '10';
    }
    
     
    
    $pict_pfad = "AOrd_Verz/$md_eigner/09/$ao/$md_aufn_datum/"; #.$md_aufn_datum."/";
    # echo "L 0284 pict_pfad $pict_pfad<br>";
    /**
     * Einlesen der Daten der Speicherortes
     */
    if (is_dir($pict_pfad)) {
        $verz_arr = array();
        foreach (scandir($pict_pfad) as $file) {
            if ($file === ".." OR $file === "." ) continue;
            # echo "L 0292 file $file <br>";
            $fn_ar = explode("-",$file);
            # var_dump($fn_ar);echo "<br> L 0288 fn_ar <br>";
            if ($fn_ar[0] == $md_eigner && (stripos($file,"jpg") || stripos($file,"jpeg"))) {
                $verz_arr[] = $file;
            }
        }
        
        $verz_cnt = count($verz_arr);
        # var_dump($verz_arr);
        # echo "<br>L 0302 verz_cnt $verz_cnt <br>";
        if ($verz_cnt != 0) {
            if ($tab_len <= 1 ) { // Datensätze für Fotos einfügen, so vorhanden
                
                foreach ($verz_arr as $foto_dsn ) {
                    $nfn_info = pathinfo($foto_dsn);
                    $nfn_name = $nfn_info['filename'];
                    $nfn_dsn  = $nfn_info['basename'];
                    $nfn_ext =  $nfn_info['extension'];
                    $nfn_dir =  $nfn_info['dirname'];
                    
                    $sql = "INSERT INTO $tabelle_g (
                          md_eigner,md_urheber,md_aufn_datum,md_dsn_1,md_beschreibg,md_namen,
                          md_sammlg,md_media,
                          md_aenduid
                      ) VALUE (
                         '$md_eigner','$md_Urheber','$md_aufn_datum','$nfn_dsn','$md_beschreibg','$md_namen',
                         '$md_sammlg','$md_media',
                         '$md_aenduid'
                      )";
                    
                    $result = SQL_QUERY($db, $sql);
                 
                }
            } elseif ($tab_len >= 1 ) { // Vergleiche Tabellen- Records mit Verzeichnis
                #var_dump($tab_arr);echo "<br> L 0325 <br>";
                #var_dump($verz_arr);echo "<br> L 0326 <br>";
                foreach ( $tab_arr as $o_key => $o_dsn) {
                    if ($o_dsn == "") {continue;}
                    #echo "L 0327 o_dsn n$o_dsn <br>";
                    $ofn_info = pathinfo($o_dsn);
                    #var_dump($ofn_info);
                    $o_name = $ofn_info['filename'];
                    $o_dsn  = $ofn_info['basename'];
                    $o_ext  = $ofn_info['extension'];
                    $o_dir  = $ofn_info['dirname'];
                    # echo "<br>L 0335 o_name $o_name <br>";
                    $fn_arr = explode("-",$o_name);
                    $fn_cnt= count($fn_arr);
                    if ($fn_cnt >= 3) { 
                        if ($fn_arr[0] == $md_eigner) {
                            continue;
                        }
                    }
                    
                    foreach ($verz_arr as $n_dsn) {
                        $nfn_info = pathinfo($n_dsn);
                        $n_name = $nfn_info['filename'];
                        $n_dsn  = $nfn_info['basename'];
                        $n_ext  = $nfn_info['extension'];
                        $n_dir  = $nfn_info['dirname'];
                        # echo "L 0344 nfn_name $n_name <br>";
                        
                        if ($o_name == $n_name) {
                            if ($o_ext == "jpg") {  
                                if ($o_dsn === $n_dsn) { // identisch, keine aktion erforderlich, löschen der Arr- Einträge
                                    unset($tab_arr['$o_key']);
                                    unset($verz_arr['$n_dsn']);
                                    #var_dump($tab_arr);
                                    #var_dump($verz_arr);
                                } else { // replace with new, delete old, unset arr- entry
                                    echo "L 0354 replace $o_dsn with $n_dsn <br>";
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
                                    if (is_file($pict_pfad.$o_dsn)) {
                                        unlink($pict_pfad.$o_dsn);
                                    }
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

    #echo "L 0391 end FO_tab_gener <br>";
}
return True;

/**
 * Funktion zum austauschen des Fo-dsn - Namens
 * 
 */
function tab_update($tabelle,$record,$odsn,$ndsn) {
    global $db,$module,$debug;
    
    $sql_in = "SELECT md_id,md_dsn_1 FROM $tabelle where md_id = '$record' ";
    $ret_in = SQL_QUERY($db,$sql_in);
    while ($row_in = mysqli_fetch_object($ret_in))  {
        if (mysqli_num_rows($ret_in) >=2) { 
            echo "Fehler, sollte nur ein Record sein. Abbruch.<br>";
            return FALSE;
        } else {
            if ($row_in->fo_dsn == $odsn) { // Update mit $ndsn
                $sql_o = "UPDATE $tabelle set  md_dsn_1 = '$ndsn' where md_id = '$record' ";
                $ret_o = SQL_QUERY($db,$sql_o);  
                
                return True;
            }
        }
    }
} # end funktion tab_update
 

?>
