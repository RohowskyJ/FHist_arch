<?php 

/**
 * Daten aus csv- Dateien in Tabellen einlesen, Abfrage  source
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "Z_DS_2_ph0.inc.php";

if ($debug) {echo "<pre class=debug>VF_Z_DS_2_ph0.inc.php ist gestarted</pre>";}

echo "<div>";
echo "Dateiformat:<br>";
echo "1. Zeile: Tabellen- Name, z.B.: Test_tab<br>";
echo "2. Zeile: fld_nam1|fld-nam2| ....<br>";
echo "ab der 3. Zeile: Inhalte, z.B.: inh1|inh2| ....<br>";
echo "<\div>";

echo '<div class="w3-content">';
echo 'Dateiname: ';
echo "<input name=\"indata\" value='$indata'  type='text' > ";
echo "Dateiname der Eingabe-Datei, voll mit Laufwerk und Pfad eingeben<br>";

echo "<button type='submit' name='phase' value='1' class='green'>Weiter</button></p>";
echo "</div>";
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_DS_2_ph0.inc.php beendet</pre>";}
?>