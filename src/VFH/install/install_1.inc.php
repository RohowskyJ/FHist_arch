<?php

/**
 * Home-Page setup. Mode, Kenndaten, Funktionen
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>install_1.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";
?>
<h1>Datenbank- Parameter</h1>
<h2>für <b>localhost</b> (zumBeispiel mit XAMPP)</h2>

<label for='l_dbh'>Hostname  &nbsp; &nbsp; </label><input type='text' id='l_dbh' name='l_dbh' value='<?php echo "$l_dbh" ?>'><br>
<label for='l_dbn'>Datenbank- Name </label><input type='text' id='l_dbn' name='l_dbn' value='<?php echo "$l_dbn" ?>'><br>
<label for='l_dbu'>User-ID &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label><input type='text' id='l_dbu' name='l_dbu' value='<?php echo "$l_dbu" ?>'><br>
<label for='l_dbp'>Passwort &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label><input type='text' id='l_dbp' name='l_dbp' value='<?php echo "$l_dbp" ?>'><br>

<h2>für <b>HOST</b> (Provider- Datenbanken)</h2>

<label for='h_dbh'>Hostname : Port &nbsp; &nbsp; </label><input type='text' id='h_dbh' name='h_dbh' value='<?php echo "$h_dbh" ?>'><br>
<label for='h_dbn'>Datenbank-Name &nbsp;  </label><input type='text' id='h_dbn' name='h_dbn' value='<?php echo "$h_dbn" ?>'><br>
<label for='h_dbu'>User-ID &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label><input type='text' id='h_dbu' name='h_dbu' value='<?php echo "$h_dbu" ?>'><br>
<label for='h_dbh'>Passwort &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label><input type='text' id='h_dbp' name='h_dbp' value='<?php echo "$h_dbp" ?>'><br>
<?php 

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='2' class='green'>Daten abspeichern</button></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>install_1.inc.php beendet</pre>";
}
?>