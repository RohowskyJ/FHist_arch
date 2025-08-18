<?php

/**
 * Auskunft über gespeicherte Daten nach DSVGO 
 *
 * @author Josef Rohowsky - neu 2019
 *
 * 
 */
session_start(); // wird erst mir v2 (php > 7.0.x) aktiviert

$module = 'MVW';

$debug = False; // Debug output Ein/Aus Schalter

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

require $path2ROOT . 'login/common/PHP_Mail_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_POST['EMail'])) {
    $EMail = trim($_POST['EMail']);
} else {
    $EMail = '';
}
if (isset($_POST['einverkl'])) {
    $einverkl = trim($_POST['einverkl']);
} else {
    $einverkl = '';
}

$ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini',True,INI_SCANNER_NORMAL);

$LinkDB_database = '';
$db = LinkDB('VFH');

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

if ($phase == 90) {
    header("Location: /");
}

// ********************************* Aufbereitung der Eingaben ***************************************************
setlocale(LC_CTYPE, "de_AT"); // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper()
$EMail = mb_strtolower($EMail, 'UTF-8'); // Adresse in Kleinbuchstaben umwandeln

// ************************************ Fehlermeldungen initialisieren ****************************************
$Errors = 0; // Fehlerzähler: 0 ... alles ok , >0 ... Fehler sind aufgetreten
             # $Err_EMail = $Err_bild = '';

$err3 = $errM = $Noerr = $emerr = $err1 = $err2 = "";


if ($phase == 1) 
{

    # echo "m_yellow_v1 L 088: \$EMail $EMail <br/>";
    if ($EMail == "") {
        # $err3 = "222";
        $phase = 0;
    } else {

        $sql = "SELECT * FROM  fh_mitglieder WHERE mi_email='$EMail' ";
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        $ds1 = $num_rows;
        # echo "<br>EMCHECK...es gibt $ds1 Datensatz mit der Email $EMail<br>";
        if ($ds1 == 0) {
            $errM = "333";
            $phase = 0;
        }
    }
} // Ende Phase 1 

// ============================================================================================================
if ($phase == 2) 
                 
{
    if (isset($_POST['einverkl'])) {
        $einverkl = $_POST['einverkl'];
    } else {
        $einverkl = "N";
    }

    if ($einverkl == "Y") {

        $sql = "UPDATE fh_mitglieder SET mi_einv_art='Onl', mi_einversterkl='$einverkl', mi_einv_dat=now() WHERE mi_email='$EMail' ";
        $result = SQL_QUERY($db, $sql);

        echo "Save Phase ohne Fehlermeldung abgeschlossen, Zeile 130<br>";

        echo "<br>";

        $curjahr = date("y");
    }
    $sql = "SELECT * FROM  fh_mitglieder WHERE mi_email='$EMail' ";
    $result = SQL_QUERY($db, $sql);

    $num_rows = mysqli_num_rows($result);

    while ($row = mysqli_fetch_object($result)) {
        $t_ID = $row->mi_id;
        $mtyp = $row->mi_mtyp;
        $m_o_typ = $row->mi_org_typ;
        $m_o_name = $row->mi_org_name;
        $t_H_F = $row->mi_anrede;
        $t_titel = $row->mi_titel;
        $t_dgr = $row->mi_dgr;
        $t_name = $row->mi_name;
        $t_vname = $row->mi_vname;
        $t_gebtag = $row->mi_gebtag;
        $t_email = $row->mi_email;
        $t_em_stat = $row->mi_email_status;
        $t_staat = $row->mi_staat;
        $t_plz = $row->mi_plz;
        $t_Ort = $row->mi_ort;
        $t_Adr = $row->mi_anschr;
        $t_Fax = $row->mi_fax;
        $t_TelNo = $row->mi_tel_handy;
        $t_vorst = $row->mi_vorst_funct;
        $t_ref_leit = $row->mi_ref_leit;

        $t_ref_in2 = $row->mi_ref_int_2;
        $t_ref_in3 = $row->mi_ref_int_3;
        $t_ref_in4 = $row->mi_ref_int_4;
        $t_sterbd = $row->mi_sterbdat;
        $t_beitritt = $row->mi_beitritt;
        $t_austrdat = $row->mi_austrdat;
        $einvart = $row->mi_einv_art;
        $einverkl = $row->mi_einversterkl;
        $einv_dat = $row->mi_einv_dat;
        $f_einverkl = $einverkl;

        if ($t_Adr == "") {
            $t_Adr = "kein Eintrag";
        }
        if ($t_vorst != "") {
            $vorst = "";
        }
        $RPmsg = "Adresse:<b> " . $row->mi_anschr . "</b><br>";

        if ($row->mi_staat == "") {
            $Land = "kein Eintrag";
        }
        $ortstring = $row->mi_staat;
        $RPmsg .= "Land:<b> " . $row->mi_staat . "</b><br>";

        if ($t_plz == "") {
            $t_plz = "kein Eintrag";
        }
        $ortstring = $ortstring . "-" . $row->mi_plz;
        $RPmsg .= "PLZ:<b> " . $row->mi_plz . "</b><br>";

        if ($row->mi_ort == "") {
            $row->mi_ort = "kein Eintrag";
        }
        $text = "PLZ Ort";
        $ortstring = $ortstring . " " . $row->mi_plz;
        // if ($ortstring == "-") { echo $lstring . $key . " = " . $text . $mstring . "kein Eintrag" . $rstring; }
        // else { echo $lstring . $key . " = " . $text . $mstring . $ortstring . $rstring; }
        $RPmsg .= "Ort:<b> " . $row->mi_ort . "</b><br>";

        if ($t_TelNo == "") {
            $t_TelNo = "kein Eintrag";
        }
        $RPmsg .= "Telefonnummer:<b> " . $row->mi_tel_handy . "</b><br>";



        if ($t_gebtag == "") {
            $t_gebtag = "kein Datum eingegeben";
        }
        $RPmsg .= "Geburtstag: <b>$row->mi_gebtag</b><br/> ";

        $RPmsg .= "Eintrittsdatum: <b>$row->mi_beitritt</b><br>";
        $RPmsg .= "Mitglieds Beitr.: <b>$row->mi_m_beitr_bez</b><br>";
        $RPmsg .= "Bezahlt bis :  <b>$row->mi_m_beitr_bez_bis</b><br>";
        $RPmsg .= "Abo Beitr.: <b>$row->mi_m_abo_bez</b><br>";
        $RPmsg .= "Abo Bez. bis: <b>$row->mi_m_abo_bez_bis</b><br>";

        $RPmsg .= "";

        if ($t_H_F == "Hr.") {
            $R_msg = "Lieber Herr ";
        } else if ($t_H_F == "Fr.") {
            $R_msg .= "Liebe Frau ";
        } else {
            $R_msg .= "Liebes Mitglied ";
        }
        if (! empty($t_titel)) {
            $R_msg .= $t_titel . " ";
        }
        $R_msg .= $t_name . " " . $t_vname . "!<br>\n";
        $R_msg .= "Mit Bezug auf Anfrage von " . $t_name . " " . $t_vname . " finden Sie nachfolgend die Daten,<br> die in der Mitglieder-Datei des Vereines der Feuerwehrhistoriker in Niederösterreich gespeichert sind.<br>\n";
        $R_msg .= "Sie haben Zugang zu den geschützten Seiten unserer IT-Plattform per Login mit Ihrem Benutzer-ID und Passwort,<br/> die Sie vom Administarator bekommen können (E-Mail: service @ feuerwehrhistoriker.at).<br><br>\n\n";
        $R_msg .= "Ihre gespeicherte Mitgliedsnummer ist <b>" . $t_ID . "</b>, bitte verwenden Sie diese Nummer<br><b>immer als Referenz</b> bei Einzahlungen und Änderungsmeldungen.<br>\n";
        $R_msg .= "Ihre gespeicherte Emailadresse ist <b>\"" . $t_email . "\"</b>.<br>\n<br>\n";

        # } //Ende ist Club-Mitglied ****************

        $R_msg .= "Nachfolgend Ihre persönlichen Daten, die in der Mitglieds-Datei abgespeichert sind:<br>";
        $R_msg .= $RPmsg;

        // Schlussformel an ALLE *************************
        $R_msg .= "<p>Sie haben Ihre Zustimmung zur Datenspeicherung ";
        if ($einverkl == "Y") {
            $R_msg .= "bereits am $einv_dat ";
            if ($einvart == "ONL") {
                $R_msg .= " Online ";
            } else {
                $R_msg .= " auf Papier ";
            }
        } else {
            $R_msg .= " noch NICHT ";
        }
        $R_msg .= " gegeben.</p>";
        $R_msg .= "<p>Falls die Aufzählung fehlerhaft erscheint oder Sie Änderungen wünschen, <br>schicken Sie bitte eine E-Mail an den Admin " . $ini_arr['Config']['vema'] . "
.</p>
<p>Mit freundlichen Grüßen<br>Verein Feuerwehrhistoriker in Nö.</p>";
    } // Ende 2.Teil nur an Members

    $R_msg .= "Dies ist eine systemgenerierte Nachricht!<br>";
    $diezeit = date("d. M Y \u\m  H:i:s");
    $R_msg .= "Diese Information ist zusammengestellt und verschickt am $diezeit<br>";
    // Ende Messagetext **********

    $J_msg = "Yellow Abfrage von Form_Name: $t_name $t_vname<br>";
    $J_msg .= "Name: $t_name $t_vname<br>";
    $J_msg .= "Mailversandzeit $diezeit</b><br>";

    sendEmail($t_email, // Empänger(Liste)
    "Gespeicherte Daten von " . $t_email, // Subject Text der EMail
    $R_msg, // Inhalt der Email in HTML format
        $reply_to = $ini_arr['Config']['vema']); # service@feuerwehrhistoriker.at

    $datum = date("d.m.Y:");
    $zeit = date("H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];
    $site = $_SERVER['REQUEST_URI'];
    $monate = array(
        1 => "Januar",
        2 => "Feber",
        3 => "März",
        4 => "April",
        5 => "Mai",
        6 => "Juni",
        7 => "Juli",
        8 => "August",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Dezember"
    );

    $monat = date("n");
    $jahr = date("y");
    $dateiname = "logs/yellow/yel_log";

    /* Get a the current date and time, formated */
    $shortdate = date("Y-m-d H:i:s");

    /* Build log string for writing */
    // $log_rec = "\nReferer: " . $HTTP_REFERER . "\n";
    # $log_rec = "\nIP: " . $_SERVER['REMOTE_ADDR'] . ", Hostname: " . $_SERVER['REMOTE_HOST'] . "\n";
    $log_rec = "With: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    # $log_rec .= "requested von REMOTE_USER:>>>>" . $_SERVER['REMOTE_USER'] . "<<<<<\n";
    $log_rec .= "Page: " . $_SERVER['REQUEST_URI'] . "\n";
    $log_rec .= "Requester-Name: " . $t_name . "\n";
    $log_rec .= "Requester-Email: " . $t_email . "\n";
    $log_rec .= "Error Kodes: " . "Noerr = " . $Noerr . " | .. errM = " . $errM . " | ..  emerr = " . $emerr . " | .. err3 = " . $err3 . "| ..err2 = " . $err2 . "| ..err1 = " . $err1 . " | Ende Errkode\n";
    $log_rec .= "MITGLIEDSNUMMER ( Datensatz-Nr.): " . $t_ID . "\n";
    $log_rec .= "Anrede: " . $t_H_F . "\n";
    $log_rec .= "Titel: " . $t_titel . "\n";
    $log_rec .= "Name: " . $t_name . $t_vname . "\n";
    $log_rec .= "Email: " . $t_email . "\n";

    $log_rec .= "Einverstaendnis Datenschutz: $einvart $einverkl  $einv_dat \n";

    $eintragen = "Datum - - [$datum$zeit] $log_rec ";
    $adminmail = $eintragen . "****** Adminmail Ende ******";

    writelog($dateiname, // DSN des MonatsLogFiles (wird mit Jahr Monat und .txt ergänzt)
    $log_rec // der Text welcher Einzutragen ist (wird mit Systemdaten ergänzt)
    );

    sendEmail("service@feuerwehrhistoriker.at, josef@kexi.at", // Empänger(Liste)
    "Yellow.log-Adminkopie von " . $t_email, // Subject Text der EMail
    $R_msg . $J_msg, // Inhalt der Email in HTML format
    $reply_to = "service@feuerwehrhistoriker.at");
} // Ende Phase 2


BA_HTML_header('Daten Auskunft', '', 'Form', '80em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

if ($debug) {
    echo "<pre class=debug>VF_M_yellow.php phase=$phase</pre>";
}
if ($phase > 1) {
    echo '<div class=white>';
}
switch ($phase) {
    case 0:
        require ('VF_M_yellow_ph0.inc.php'); # Form 'Eingabe' zeigen & Variable für nächste Phase posten
        break;
    case 1:
        require ('VF_M_yellow_ph1.inc.php'); # Form 'Eingabe' zeigen & Variable für nächste Phase posten>
        break;
    case 2:
        echo "";
        echo "Save Phase ohne Fehlermeldung abgeschlossen, Zeile 336<br>";
        echo "bitte diesen Tabulator schliessen, Index (Menu) wird sichtbar.";

        echo "<br>";

        break;

    default:
        echo " <p style='color:red;font-size:xx-large;'>yellow_v3: Interner phase=$phase Fehler</p>";
}
if ($phase > 1) {
    echo '</div>';
}
BA_HTML_trailer();
?>