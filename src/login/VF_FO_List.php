<?php
/**$basepfad
 * Foto- Verwaltung
 * 
 * @author J. Rohowsky  - neu 2018 Umstellung Urheber 2025
 * 
 */
session_start();

$module = 'OEF';
$sub_mod = 'Foto';

$tabelle = 'dm_edien_';

/**
 * Pfad zum Root- Verzeichnis, wird abgelöst
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_FO_List.php"; 

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

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - select_string
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "DropdownAnzeige" => "Aus",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

VF_upd();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
if (!isset($phase)) {
    $phase = 0;
}

if ($phase == 0) {
    $title = "Urheber Auswahl";
} else {
    $title = "Medien- Bearbeitung (Audio, Foto, Video) ";
}

$jq = $jqui = True;
$BA_AJA = true;
BA_HTML_header($title, '', 'List', '75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

/**
 * Löschen Parameter, wenn neue Urheber gewünscht
 */
if (isset($_GET['ID']) && $_GET['ID'] == "NextEig") { // Urhebe- Parameter und Eigner- Information löschen
    $_SESSION[$module][$sub_mod]['eig_eigner'] = "";
}

/**
 * Phase- Informationen
 * $phase = 0 einlesen
 */
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
}

$_SESSION[$module][$sub_mod] = array();

if ($phase == 0  ) {
    echo "<h3>".$_SESSION['VF_Prim']['OrgNam']."</h3>";
    echo "<h2>$title</h2>";
    VF_Auto_Eigent('U',True,1);
}

if ($phase  == "1")  {
    
    if (isset($_POST['eigentuemer_1'])) {  // ei_id
        $_SESSION[$module][$sub_mod]['eig_eigner'] = $_POST['eigentuemer_1'];
      
        $eign_ret = VF_Displ_Eig($_POST['eigentuemer_1'], 'U');
            require "VF_FO_List.inc.php";
    }
}

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
 * @global array  $db         Handle Database
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $db,$path2ROOT, $T_List, $module, $fm_eigner;

    $s_tab = substr($tabelle, 0, 8);

    #var_dump($row);
    switch ($s_tab) {

        case "fh_eigen":
            if (!isset($row['Urh_Erw'])) {
                $row['Urh_Erw'] = "";
            }
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<input type='radio' id='$ei_id' name='ei_id' value='$ei_id'> <label for id='$ei_id'> &nbsp; $ei_id</label>"; // 
            
            $row['ei_name']  .= " ".$row['ei_vname'];
       
            if (strlen($row['ei_media']) >= 2 ) {
            #if ($row['ei_org_typ']  != "Privat" ) { // urh erw auslesen
                $sql_u = "SELECT * FROM fh_eign_urh WHERE  fs_eigner=$ei_id ";
                $ret_u = SQL_QUERY($db,$sql_u);
                WHILE ( $row_u = mysqli_fetch_object($ret_u)) {
                    $row['Urh_Erw'] .= "<input type='radio' id='$row_u->fs_urh_kurzz' name='urh_kurz' value='$row_u->fs_urh_kurzz|$row_u->fs_typ'> <label for= > $row_u->fs_fotograf, $row_u->fs_typ, $row_u->fs_urh_kurzz, $row_u->fs_urh_verzeich    </label><br>";
                } 
            } else  {
                
            }
            break;
        case "dm_edien":
        
            $verz = "N"; /** Daten anzeigen, J = Nur Verzeichnisse anzeigen */
            $md_id = $row['md_id'];
            
            
            $pict_path = $pict_path = "../login/AOrd_Verz/" . $row['md_eigner'] . "/09/";
            if ($row['md_dsn_1'] != "0_Verz") {
                $verz = "J";
                $md_arr = pathinfo($pict_path);
                
                if (in_array(strtolower($md_arr['extension']), AudioFiles)) {
                    $pict_path ."02/";
                }
                if (in_array(strtolower($md_arr['extension']), GrafFiles)) {
                    $pict_path ."06/";
                }
                if (in_array(strtolower($md_arr['extension']), VideoFiles)) {
                    $pict_path ."10/";
                }
            }
          
            $row['md_id'] = "<a href='VF_FO_Edit.php?md_id=$md_id&md_eigner=" . $row['md_eigner'] . "&verz=$verz' >" . $md_id . "</a>";

            $md_aufn_d   = $row['md_aufn_datum'];
            $md_eigner   = $row['md_eigner'];
            $pfad = "";
            if ($md_aufn_d != "") { # Datums orientertes Archiv (neue Arcive oder Fotoserien
                $pfad = $md_aufn_d . "/";
            }
 
            $row['md_aufn_datum'] = "<a href='VF_FO_List_Detail.php?md_eigner=$md_eigner&md_aufn_d=$md_aufn_d'  target='_blanc'>" . $pfad . "</a> &nbsp; Fotos ";  # $md_aufn_d         

            if ($row['md_dsn_1'] != "0_Verz") {
                $dsn = $row['md_dsn_1'];
                $d_path = $pict_path . $row['md_aufn_datum'] . "/";
                $d_arr = pathinfo(strtolower($dsn));
                if ($d_arr['extension'] == 'jpg' || $d_arr['extension'] == 'jpeg' || $d_arr['extension'] == 'gif' || $d_arr['extension'] == 'tiff' || $d_arr['extension'] == 'png' || $d_arr['extension'] == 'ico' ) {
                    $row['md_dsn_1'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
                } else {
                    $row['md_dsn_1'] = "<a href='$pict_path$dsn' target='_blank'>" . $row['md_dsn_1'] . "</a>";
                }
            }
            
            break;
    }

    return True;
} # Ende von Function modifyRow

?>
