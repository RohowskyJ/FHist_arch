<?php

/**
 * Foto Liste Details (Fotos je Aufnahmedatum) als Basis für einen Bericht
 * 
 * @author Josef Rohowsky - neu 2024
 * 
 * 
 */
session_start(); # die SESSION aktivieren

$module = 'OEF';
$sub_mod = 'Beri';

$tabelle = 'dm_edien';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_FO_List_Bericht.inc.php";

$debug = True; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/common/VF_Funcs.inc';
require $path2ROOT . 'login/common/common/VF_List_Funcs.inc';
require $path2ROOT . 'login/common/common/VF_Tabellen_Spalten.inc';
require $path2ROOT . 'login/common/common/VF_Comm_Funcs.inc';
require $path2ROOT . 'login/common/common/VF_7_Tabelle.inc';

VF_initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

$LinkDB_database = '';
$db = LinkDB('Mem');

VF_chk_valid();

VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['md_eigner'])) {
    $dm_eigner = $_GET['dm_urheb'];
} else {
    $dm_urheber = "";
}

if ($dm_eigner != "") {
    $_SESSION[$module]['dm_urheber'] = $dm_urheber;
    VF_Displ_Urheber($dm_urheber);
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

$csv_DSN = "";
$NoSelects = True;
$NoSort = False;
$lowHeight = True;

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION[$module][$sub_mod]['eigner'] = "";
    }
}

if (isset($_GET['fo_aufn_d'])) {
    $_SESSION[$module]['md_aufn_d'] = $md_aufn_d = $_GET['md_aufn_d'];
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['select_string'] = $select_string;

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 0) {}

VF_HTML_header("Foto Auswahl für Bericht", '', '', 'Form');

require "VF_O_FO_List_Bericht.inc.php";

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
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
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module, $fo_mandkennz;

    $s_tab = substr($tabelle, 0, 8);
    $VF_Foto_Video = array(
        "Foto" => "Fotografie",
        "Film" => "Filme",
        "Video" => "Videos"
    );

    $Auth_Neu = array(
        "AG" => "Alois Gritsch",
        "EK" => "Erich Koller",
        "FWNDF" => "FF Wiener Neudorf",
        "JR" => "Josef Rohowsky",
        "VK" => "Viktor Kabelka",
        "WR" => "Wolfgang Riegler",
        "MH" => "Moravec Helmut",
        "BL" => "Brigitta Laager",
        "AW" => "Alois Wanzenböck"
    );

    switch ($s_tab) {
        case "fo_manda":
            $fm_id = $row['fm_id'];
            $fm_mandkennz = $row['fm_mandkennz'];
            $row['fm_id'] = "<a href='VF_O_FO_M_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            $fm_eigner = $row['fm_eigner'];
            $row['md_eigner'] = "<a href='VF_O_FO_List.php?fm_eigner=$fm_eigner'  target='Foto'>" . $fm_eigner . " Fotos </a>";
            break;

        case "dm_edien":
            $md_id = $row['md_id'];
            $row['md_id'] = "<a href='VF_O_FO_Edit.php?md_id=$md_id&md_eigner=" . $row['mdo_eigner'] . "&verz=N' >" . $md_id . "</a>";
            /*
             * if ($row['fo_dsn'] != "") {
             * $dsn = $row['fo_dsn'];
             * $d_path = $pict_path.$row['fo_aufn_datum']."/";
             *
             * $row['fo_dsn'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a>";
             * }
             */

            $row['inmdfo_media'] = $VF_Foto_Video[$row['md_media']];
            if ($row['md_dsn_1'] != "") {

                $dsn = $row['md_dsn_1'];

                if ($row['fo_typ'] == "Foto") {
                    $md_d_spl = explode("-", $dsn);
                    $cnt_f_d = count($md_d_spl);

                    $file_arr = explode("-", $dsn);

                    $pict_path = "../login/AOrd_Verz/" . $row['md_eigner'] . "/09/06/";
                    $d_path = $pict_path . $row['mdo_aufn_datum'] . "/";
              
                    # $d_path = $pict_path.$row['fo_aufn_datum']."/";
                    VF_Displ_Urheb($row['md_eigner']);

                    $row['md_dsn'] = "<a href='VF_O_FO_Detail.php?eig=" . $row['fod_eigner'] . "&id=$md_id' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' ></a><br>" . $_SESSION[$module]['URHEBER']['fm_beschreibg'] . "<br>" . $d_path . $dsn;

                    $begltext = $row['md_beschreibg'];
                    # $row['fo_begltxt'] = "<a href='$d_path$dsn' target='_blank'><img src='$d_path$dsn' alt='$dsn' height='200' >$begltext</a>";
                } else {
                    $vi_d_spl = explode("-", $dsn);
                    $cnt_f_d = count($vi_d_spl);
                    if ($cnt_f_d >= 2 and stripos($row['md_basepath'], "ARCHIV_VFHNOe")) {
                        $pict_path = "../login/AOrd_Verz/" . $row['md_eigner'] . "/09/10/";
                        $d_path = $pict_path . $row['md_aufn_datum'] . "/";
          
                    } else {
                        $pict_path = "login/AOrd_Verz/" . $row['md_eigner'] . "/09/10/";
                        $d_path = $pict_path . $row['md_aufn_datum'] . "/";
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

                    $begltext = $row['md_beschreibg'];
                    $row['md_beschreibg'] = "<a href='www.feuerwehrhistoriker.at/$pict_path$dsn' target='_blank'>$begltext</a>";
                    $row['md_dsn_1'] = "";
                    echo "L 0173 md_beschreibg" . $row['md_bechreibg'] . " <br>";
                }
            }
    
            $md_aufn_datum = $row['md_aufn_datum'];

            break;
    }

    return True;
} # Ende von Function modifyRow

?>
