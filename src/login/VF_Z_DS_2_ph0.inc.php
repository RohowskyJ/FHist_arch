<?php 

/**
 * Daten aus csv- Dateien in Tabellen einlesen, Abfrage  source
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_Z_DS_2_ph0.inc.php ist gestarted</pre>";}

echo '<div class="w3-content">';
echo 'Dateiname: ';
echo "<input name=\"indata\" value='$indata'  type='text' > ";
echo "Dateiname der Eingabe-Datei, voll mit Laufwerk und Pfad eingeben<br>";

echo "<button type='submit' name='phase' value='1' class='green'>Weiter</button></p>";
echo "</div>";
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_DS_2_ph0.inc.php beendet</pre>";}
?>