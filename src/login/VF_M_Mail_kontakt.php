<?php
/**
 * Mail an andere Mitglieder senden
 *
 * @author  osef Rohowsky - neu 2023
 *
 *
 */
$module = 'MVW';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
if (isset($_GET['id'])) {
    $empf_id = $_GET['id'] / 13579;
} elseif (isset($_POST['id'])) {
    $empf_id = $_POST['id'];
}
if (isset($_POST['empf_id'])) {
    $empf_id = $_POST['empf_id'];
}
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_POST['mi_email'])) {
    $EMail = $_POST['mi_email'];
} else {
    $EMail = '';
}
if (isset($_POST['text'])) {
    $text = trim($_POST['text']);
} else {
    $text = '';
}
if (isset($_SERVER['REMOTE_USER'])) {
    $REMOTE_USER = $_SERVER['REMOTE_USER'];
} else {
    $REMOTE_USER = '';
}
# $REMOTE_USER = 'richard.gaicki@aon.at';
if (strpos($REMOTE_USER, '@') !== false) {
    $EMail = $REMOTE_USER;
}

$Errors = 0;
$Err_EMail = $Err_text = $Err_total = '';

# ============================================================================================================
# ======================================= Empfänger Daten ermitteln ==========================================
# ============================================================================================================

if (empty($empf_id)) {
    die('<h1><span style="color:red">Kein Empfänger für die Nachricht angegeben.</span</h1><br>');
}

$empf_row = EMail_Adr_check($empf_id, $db);
if ($debug) {
    echo "<pre class=debug>row:";
    print_r($empf_row);
    echo '</pre>';
}
if (! empty($empf_row['message'])) {
    die('<h1><span style="color:red">Keine gültige Emailadresse für den Empfänger der Nachricht gefunden.</span></h1>');
}
$_SESSION['empf'] = $empf_row;

# ============================================================================================================
# ======================================= sender Daten ermitteln =============================================
# ============================================================================================================
if ($phase == 1 or ! empty($EMail)) {
    $sender_row = EMail_Adr_check($EMail, $db); # zu prüfende E-Mail-Adresse
    if (! empty($sender_row['message'])) {
        $Errors ++;
        $Err_EMail .= $sender_row['message'];
        $phase = 0;
    } else {
        $_SESSION['send'] = $sender_row;
    }
}

// ============================================================================================================
if ($phase == 1) {
    // ============================================================================================================
    if ($text == "") {
        $Errors ++;
        $Err_text .= "Bitte eine Nachricht im Textfeld eingeben.";
    }
}

if ($Errors > 0) {
    $phase = 0;
} # Fehler ist/sind aufgetreten - zurück zur Eingabemaske

# ===========================================================================================================
# die HTML Seite ausgeben
# ===========================================================================================================
HTML_header('Feuerwehrhistoriker Kontakt Form', '', '', '', '');
?>

<h1>Nachricht an Feuerwehrhistoriker Mitglied</h1>

<?php
echo "<input type='hidden' name='empf_id'    value='$empf_id'>";
switch ($phase) {
    case 0:
        require 'VF_M_Mail_kontakt_ph0.inc.php'; # Form 'Eingabe' zeigen & Variable für nächste Phase posten
        break;
    case 1:
        require 'VF_M_Mail_kontakt_ph1.inc.php';
        break;
    default:
        echo "<p style='color:red;font-size:xx-large;'>plskontakt: Interner phase=$phase Fehler</p>";
}
HTML_trailer();?>