<?php

/**
 * Anmeldung eines neuen Mitgliedes
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
session_start();

$module = 'ADM';
$sub_mod = 'NeuAnmeld';

$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

$header = "";
HTML_header('Mitglieder- Verwaltung', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES
setlocale(LC_CTYPE, "de_AT"); // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper
                              # require($path2ROOT.'login/common/trans_2utf8.php'); # übersetungstabellen

if (! isset($_SESSION[$module])) {
    $_SESSION[$module][$sub_mod] = array();
}
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

if ($phase == 99) {
    header('Location: ../');
}

$_SESSION['VF_Prim']['p_uid'] = 999999999;

$err = $mail_err = "";
if (! isset($Err_msg)) {
    $Err_msg = array();
}

$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, 'fh_mitglieder');

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {
    $neu = array(
        'mi_id' => '0',
        'mi_mtyp' => 'UM',
        'mi_org_typ' => '',
        'mi_org_name' => '',
        'mi_name' => '',
        'mi_vname' => '',
        'mi_titel' => '',
        'mi_dgr' => '',
        'mi_n_titel' => '',
        'mi_anrede' => 'Hr.',
        'mi_gebtag' => '',
        'mi_staat' => '',
        'mi_plz' => '',
        'mi_ort' => '',
        'mi_anschr' => '',
        'mi_tel_handy' => '',
        'mi_fax' => '',
        'mi_email' => '',
        'mi_ref_int_2' => '',
        'mi_ref_int_3' => '',
        'mi_ref_int_4' => ''
    );

    # ## ,'mi_ref_ma'=>'',
}

if ($phase == 1) {
    $mail_err = $err = "";
    $err_anz = 0;

    $Err_msg = array();

    foreach ($_POST as $name => $value) {
        $neu[$name] = trim(mysqli_real_escape_string($db, $value));
    }
var_dump($neu);
    $neu['mi_beitritt'] = date('Y-m-d');

    if ($neu['mi_mtyp'] == "UM" && $neu['mi_org_name'] != "") {
        $neu['mi_mtyp'] = "FG";
    }

    if ($neu['mi_org_typ'] !=  "Privat") {
        if ($neu['mi_org_name'] =="") {
            $Err_msg['mi_org_typ'] = 'Oranisationsamen eingeben';
        }
    }

    if (! isset($neu['mi_sterbdat'])) {
        $refl = $sterb = $austr = "";

        $neu['mi_sterbdat'] = "";
        $neu['mi_austrdat'] = "";
        $neu['mi_ref_leit'] = "";
        $now_mi_id = "";
        $neu['mi_email_status'] = "";
        $neu['mi_m_beitr'] = "";
        $neu['mi_m_abo'] = "";
        $neu['mi_m_beitr_bez'] = "";
        $neu['mi_m_abo_bez'] = "";
        $neu['mi_abo_ausg'] = "";
    }

    if ($neu['mi_gebtag'] == "")  {
        $Err_msg = "Bitte den Geburtstag eingeben";
    }

    if ($neu['mi_email'] != "") {
        if (! filter_var($neu['mi_email'], FILTER_VALIDATE_EMAIL)) {
            $mail_err = "Invalid email format<br>";
            $Err_msg['mi_mail'] = "Invalid email format<br>";
            $err_anz ++;
        }

        $sql = "SELECT * FROM fh_mitglieder WHERE mi_email = '$neu[mi_email]' ";
        $return = SQL_QUERY($db, $sql);
        $num_rec = mysqli_num_rows($return);

        if ($num_rec > 0) {
            $mail_err = "E-Mail Adresse bereits vorhanden<br>";
            $Err_msg['mi_email'] = "E-Mail Adresse bereits vorhanden<br>";
            $err_anz ++;
        }
    }

    if (isset($neu['einverkl'])) {
        $neu['mi_einversterkl'] = $neu['einverkl'];
    } else {
        $neu['mi_einversterkl'] = "N";
    }

    if ($neu['mi_einversterkl'] == "Y") {
        $neu['mi_einv_art'] = "ONL";
        $neu['mi_einv_dat'] = date('Y-m-d');
    } else {
        $err .= "Einverständniserklärung wird laut DSGVO zwingend benötigt <br>";
        $Err_msg['mi_einversterkl'] = "Einverständniserklärung wird laut DSGVO zwingend benötigt";
        $err_anz ++;
    }

    if ($err_anz != "0") {
        $phase = 0;
    }
    if (count($Err_msg) >= 1) {
        $phase = 0;
    }
}

switch ($phase) {
    case 0:
        require 'VF_M_Anmeld_ph0.inc.php';
        break;
    case 1:
        require "VF_M_Anmeld_ph1.inc.php";
        require $path2ROOT . "login/VF_M_EBZ.inc.php";
        break;
}
BA_HTML_trailer();
?>
