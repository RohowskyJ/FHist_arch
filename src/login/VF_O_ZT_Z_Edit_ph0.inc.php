<?php

/**
 * Zeitungs- Liste, Wartung, Formular
 *
 * @author J.Rohowsky
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_ZT_Z_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_ZE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<input type='hidden' name='zt_id' value='$zt_id'/>";
echo "<input type='hidden' name='zt_daten' value='" . $neu['zt_daten'] . "'/>";
# =========================================================================================================
Edit_Tabellen_Header('Zeitungsdaten');
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('zt_id');

# =========================================================================================================
Edit_Separator_Zeile('Information über die Zeitung');
# =========================================================================================================

Edit_Daten_Feld('zt_name', 100);
Edit_Daten_Feld('zt_herausg', 100);
Edit_Daten_Feld('zt_internet', 100);
Edit_Daten_Feld('zt_email', 100);

Edit_Daten_Feld('zt_daten');

Edit_Daten_Feld('zt_erstausgdat', 10, 'Datum der Erstausgabe');
Edit_Daten_Feld('zt_letztausgabe', 10, 'Datum letzten Ausgabe');

Edit_Daten_Feld('zt_uidaend');
Edit_Daten_Feld('zt_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_O_ZT_List.php'>Zurück zur Liste</a></p>";

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_Z_Edit_ph0.inc.php beendet</pre>";
}
?>