<?php

/**
 * Mitgliederverwwaltung Zahlungseingabgsvermerk, schreiben des Bezahldatums
 *
 * @author Josef Rohowsky - neu 2020
 * 
 * Parameter beim Aufruf:
 *         mi_id - Mitgliedsnummer
 *         b     - Beitragsart
 *         BM    - Mitgliedsbeitrag
 *         BA  - Abo- Beitrag
 *         BMA - Mitglieds- und Abo- Beitrag
 *         
 *  Schreiben Historie Record
 *  Schreiben der jeweiligen Änderung
 *  Mail  an Admin und Mitglied schicken
 *          
 */
session_start();

$module = 'VF_MB';
$sub_mod = 'alle';

$tabelle = 'fh_mitglieder';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_MB_Buchung.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH'); 

initial_debug();
// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_GET['mi_id'])) {
    $mi_id = $_GET['mi_id'];
}
if (isset($_GET['b'])) {
    $Bez_was = $_GET['b'];
}
if (isset($_GET['p_uid'])) {
    $p_uid = $_GET['p_uid'];
}

$mi_m_beitr_bez = "";
if (isset($_GET['mi_m_beitr_bez'])) {
    $mi_m_beitr_bez_bis = $_GET['mi_m_beitr_bez'];
}
$mi_m_abo_bez = "";
if (isset($_GET['mi_m_abo_bez'])) {
    $mi_m_abo_bez_bis = $_GET['mi_m_abo_bez'];
}

# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus Tabelle TNTab
# ------------------------------------------------------------------------------------------------------------
$sql = "SELECT * FROM $tabelle WHERE mi_id = '$mi_id'";
if ($debug) {
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
}

$result = SQL_QUERY($db, $sql);
$num_rows = mysqli_num_rows($result);
if ($num_rows !== 1) {
    echo "<p style='color:red;font-size:150%;font-weight:bold;' >$num_rows Eintragungen mit der mi_id Nummer $mi_id gefunden</p>";
    BA_HTML_trailer();
    exit();
}

$row = mysqli_fetch_array($result);
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$row: ';
    print_r($row);
    echo '</pre>';
}

# ------------------------------------------------------------------------------------------------------------
# Ändern der Daten in TNTab
# ------------------------------------------------------------------------------------------------------------

$buch_dat = date('Y-m-d');
$date_arr = explode("-", $buch_dat);
$buch_dat_1 = $date_arr[0] + 1 . "-$date_arr[1]-$date_arr[2]";
$mi_m_abo_bez_bis = "";
$mi_m_beitr_bez_bis = "";
switch ($Bez_was) {
    // BM_m2 BM_m1 
    case "BM_m2":
        $mi_m_beitr_bez = date("Y-m-d");
        $mi_mb_bez = " Mitgliedsbeitrag für  $buch_dat bezahlt ";
        $mi_m_beitr_bez_bis = $date_arr[0] - 2;
        #$fuerjahr = $date_arr[0] - 2;
        $mi_mb_bez = " Mitglieds- Beitrag für das Beitragsjahr $mi_m_beitr_bez_bis, am $buch_dat ";
        break;
    case "BM_m1":
        $mi_m_beitr_bez = date("Y-m-d");
        $mi_mb_bez = " Mitgliedsbeitrag für  $buch_dat bezahlt ";
        $mi_m_beitr_bez_bis = $date_arr[0] - 1;
        #$fuerjahr  = $date_arr[0] - 1;
        $mi_mb_bez = " Mitglieds- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat  ";
        break;
    case "BM":
        $mi_m_beitr_bez = date("Y-m-d");
        $mi_mb_bez = " Mitgliedsbeitrag für  $buch_dat bezahlt ";
        $mi_m_beitr_bez_bis = $date_arr[0];
        #$fuerjahr  = $date_arr[0] - 1;
        $mi_mb_bez = " Mitglieds- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat ";
        break;
    case "BA":
        $mi_m_abo_bez = date("Y-m-d");
        $mi_mb_bez = " Abo- Beitrag für  $buch_dat bezahlt ";
        $mi_m_abo_bez_bis = $date_arr[0];
        $mi_mb_bez = "  Abo- Beitrag für das Beitragsjahr  $mi_m_abo_bez_bis, am $buch_dat  ";
        break;
    case "BMA":
        $mi_m_beitr_bez = date("Y-m-d");
        $mi_m_abo_bez = date("Y-m-d");
        $mi_m_beitr_bez_bis = $date_arr[0];
        $mi_m_abo_bez_bis = $date_arr[0];
        $mi_mb_bez = " Mitglieds- und Abo- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat ";
        break;
    Case "BM_1":
        $mi_m_beitr_bez = $buch_dat;
        $mi_m_beitr_bez_bis = $date_arr[0] + 1;
        $mi_mb_bez = " Mitglieds- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat  ";
        break;
    case "BA_1":
        $mi_m_abo_bez = $buch_dat;
        $mi_m_abo_bez_bis = $date_arr[0] + 1;
        $mi_mb_bez = " Abo- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat ";
        break;
    case "BMA_1":
        $mi_m_beitr_bez = $buch_dat;
        $mi_m_abo_bez = $buch_dat;
        $mi_m_beitr_bez_bis = $date_arr[0] + 1;
        $mi_m_abo_bez_bis = $date_arr[0] + 1;
        $mi_mb_bez = " Mitglieds- und Abo- Beitrag für das Beitragsjahr  $mi_m_beitr_bez_bis, am $buch_dat  ";
        break;
    case "korr":
        $mi_mb_bez = " Storno der Einzahlung Mitglieds- und Abo- Beitrag  ";
        # echo "beitr_bez_bis $mi_m_beitr_bez_bis abo_bez_bis $mi_m_abo_bez_bis <br>";
        break;
    default:
        header('Location: VF_MB_List_v3.php');
}

$updas = "";
if (isset($mi_m_beitr_bez)) {
    if ($mi_m_beitr_bez != "") {
        $updas = "mi_m_beitr_bez='$mi_m_beitr_bez'";
    }
}

if ($mi_m_abo_bez != "") {
    if ($updas == "") {
        $updas = "mi_m_abo_bez = '$mi_m_abo_bez'";
    } else {
        $updas .= ",mi_m_abo_bez = '$mi_m_abo_bez'";
    }
}
if ($mi_m_beitr_bez_bis != "") {
    if ($updas == "") {
        $updas = "mi_m_beitr_bez_bis = '$mi_m_beitr_bez_bis'";
    } else {
        $updas .= ",mi_m_beitr_bez_bis = '$mi_m_beitr_bez_bis'";
    }
}
if ($mi_m_abo_bez_bis != "") {
    if ($updas == "") {
        $updas = "mi_m_abo_bez_bis = '$mi_m_abo_bez_bis'";
    } else {
        $updas .= ",mi_m_abo_bez_bis = '$mi_m_abo_bez_bis'";
    }
}
$logtext = "Änderungen in " . Tabellen_Name . " für " . $row['mi_name'] . "  " . $row['mi_vname'] . " [mi_id]=$mi_id" . "\n[mi_m_beitr_bez] von '" . $row['mi_m_beitr_bez'] . "' auf '$mi_m_beitr_bez'" . "\n[mi_m_abo_bez] von '" . $row['mi_m_abo_bez'] . "' auf '$mi_m_abo_bez'";

$sql = "UPDATE $tabelle SET  $updas ,mi_uidaend = '$p_uid'";

$sql .= " WHERE mi_id='$mi_id'";
if ($debug) {
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
}
$result = SQL_QUERY($db, $sql);

$logtext .= print_r($result);
$logtext .= "<br>L 0209 $sql <br>";

writelog($path2ROOT . "login/logs/MitglBeitrLog/MB_buchung_log", $logtext);

$msg = "<b>" . $row['mi_anrede'] . ' ' . $row['mi_titel'] . ' ' . $row['mi_vname'] . ' ' . $row['mi_name'] . ' ' . $row['mi_n_titel'] . "</b>";

if (isset($bez_was) and $bez_was == "korr") {
    $msg .= "<p>Die Bezahlung wude STORIERT.</p>";
} else {
    $msg .= "<p>Wir bestätigen die Einzahlung von $mi_mb_bez beim Verein der Feuerwehrhistoriker in NÖ verbucht." ; ##, gebucht am $mi_mb_bez 
}

$msg .= "<p>Der Vorstand der Feuerwehrhistoriker.</p>" . "Bitte bewahren Sie diese Bestaetigung auf," . " sie soll Ihnen spaetere Rueckfragen ersparen!";

$RemUsr = '';
if (isset($_SERVER['REMOTE_USER'])) {
    $RemUsr = $_SERVER['REMOTE_USER'];
    $status .= " (Admin='$RemUsr')";
}

$adr_list = VF_Mail_Set('Bezah');
$admin = $adr_list . ' ,josef@kexi.at';
$adm_mail_grp = "Bezah";

if ($bez_was = "korr") {
    sendEmail($row['mi_email'].", josef@kexi.at", "Einzahlungs- Bestätigung ", $msg); // Email zum Antragsteller
}
sendEmail($admin, "Klubmitgliedschaft " . $row['mi_name'] . ". Bestätigung ", $msg);

header("Location: VF_MB_List.php?mi_id=$mi_id"); # zurück zum Aufrufenden Script

?>