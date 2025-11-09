<?php

/**
 * Mail an andere Mitglieder senden, senden
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_M_Mail_kontekt_ph1.php"; 

if ($debug) {
    echo "<pre class=debug>VF_M_Mail_kontakt_ph1.inc.php für $module ist gestarted</pre>";
}

# ================================================================================================================
# Senden der E-Mail an den Empfänger der Nachricht und einen Kopie an den Sender
# ================================================================================================================
$rtext = nl2br($text); // Rückmeldetext mit Zeilen definieren

$empf = $_SESSION['empf']['mi_email'];
$empf_anr = $_SESSION['empf']['mi_anrede'] . " " . $_SESSION['empf']['mi_titel'] . " " . $_SESSION['empf']['mi_vname'] . " " . $_SESSION['empf']['mi_name'] . " " . $_SESSION['empf']['mi_n_titel'];
$send = $_SESSION['send']['mi_email'];
$send_anr = $_SESSION['send']['mi_anrede'] . " " . $_SESSION['send']['mi_titel'] . " " . $_SESSION['send']['mi_vname'] . " " . $_SESSION['send']['mi_name'] . " " . $_SESSION['send']['mi_n_titel'];

$message = "<h1>$empf_anr </h1>" . "<pHistoriker Mitglied <b>$send_anr</b> ";

$message .= " hat folgende Nachricht an Sie:</p>" . "<div style='background-color:LightCyan;padding:10px;margin:20px;'>" . # border:1px solid blue;
"<cite style='color:darkblue;'><b>$text</b></cite></div>" . "Senden Sie Ihre Antwort an die E-Mail-Adresse <a href='mailto:$send'>$send</a>";
$message = stripslashes($message);

sendEmail($empf, "VFH Kontaktmailer: Nachricht von $send_anr", $message, $send); # , $EMail
sendEmail($send, "Historiker Kontaktmailer: Kopie Ihrer Nachricht an $empf_anr", $message);

# ================================================================================================================
# Schreiben des logs
# ================================================================================================================

$log_rec = "       Absender: " . $_SESSION['send']['mi_anrede'] . " \n" . "Absender E-Mail: " . $_SESSION['send']['mi_email'] . " \n" . " Empfänger Name: " . $_SESSION['empf']['mi_anrede'] . "  \n" . "        Mail To: " . $_SESSION['empf']['mi_email'] . " \n" . "           Text: $text <<<<< Ende Text  -----\n";

$logDateiname = writelog($path2ROOT . "login/logs/plskontakt_log", $log_rec);
?>

<!-- =========================================================================================================== -->
<!--                                  Begin der angzuzeigenden Form                                              -->
<!-- =========================================================================================================== -->
<div class=white>
<?php
echo $send_anr;
echo "<p>Eine E-Mail mit Ihrer Nachricht wurde an <b>$empf_anr </b> gesendet.
          Sie erhalten eine Kopie dieser E-Mail an Ihre E-Mail-Adresse <q>$send</q> gesendet.
        </p>";
echo "<p>Ihre Nachricht lautete: <q><cite style='color:darkblue;'><b>$text</b></cite></q></p>";
echo VF_Spam_Text . VF_Gruesse;
?>
</div>