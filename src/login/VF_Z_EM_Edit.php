<?php

/**
 * Automatische Benachrichtigung für ADMINS bei Änderungen, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
$tabelle = 'fh_m_mail';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$LinkDB_database = '';
$db = LinkDB('VFH');

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 9;
}
if (isset($_GET['phase'])) {
    $phase = $_GET['phase'];
}
if (isset($_GET['ID'])) {
    $em_flnr = $_GET['ID'];
} else {
    $em_flnr = "";
}
if (isset($_POST['em_flnr'])) {
    $be_id = $_POST['em_flnr'];
}

if ($phase == 99) {
    header('Location: VF_Z_EM_List.php');
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

Tabellen_Spalten_parms($db, $tabelle);

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 9) {
    if ($em_flnr == "0") {
        $_SESSION[$module]['em_flnr'] = 0;
    } else {
        # $sql = "SELECT * FROM $tabelle ";
        $sql = "SELECT * FROM $tabelle LEFT JOIN fh_mitglieder ON $tabelle.em_mitgl_nr = fh_mitglieder.mi_id ";
        if ($em_flnr != '') {
            $sql .= " WHERE em_flnr = '$em_flnr'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($be_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Benutzer mit der mi_id Nummer $mi_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }

        $neu = mysqli_fetch_array($result);

        $phase = 0;
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 0) {
    foreach ($_GET as $name => $value) {
        $neu[$name] = $value;
    }

    if (isset($_GET['mi_id'])) {
        $neu['em_flnr'] = 0;
        $neu['em_mitgl_nr'] = $_GET['mi_id'];
        $neu['em_mail_grp'] = "";
        $neu['em_active'] = "Aktiv";
        $neu['em_aenddat'] = "";
        $neu['em_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    } else {}

    $sql = "SELECT * FROM fh_mitglieder WHERE mi_id = '$neu[em_mitgl_nr]' ";

    $return = SQL_QUERY($db, $sql);

    $row = mysqli_fetch_object($return);
    $neu['mi_name'] = $row->mi_name . " " . $row->mi_vname;
}

# echo "E_Edit L 099: \$ phase $phase <br/>";

BA_HTML_header('Automat. Email- Verwaltung', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 9:
        require ('VF_Z_EM_Edit_ph9.inc.php');
        break;
    case 0:
        require 'VF_Z_EM_Edit_ph0.inc.php';
        break;
    case 1:
        require 'VF_Z_EM_Edit_ph1.inc.php';
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
    global $module, $path2ROOT, $T_List;

    # $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr
    $em_flnr = $_SESSION[$module]['em_flnr'];
    $mi_id = $row['mi_id'];
    $row['mi_id'] = "<a href='VF_Z_EM_Edit.php?ID=$em_flnr&mi_id=$mi_id&phase=0'>$mi_id</a>";

    return True;
} # Ende von Function modifyRow

?>