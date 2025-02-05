<?php

/**
 * EMail an andere Mitglieder schicken, Formular
 * 
 * @author Josef Rohowsky - neu 2022
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_EM_Edit_ph0.inc.php ist gestarted</pre>";
}

?>
<br>
<div class='white'>

	<table>
		<tbody>

			<tr>
				<td>An: Feuerwehrhistoriker</td>
				<td><select name="adresse">
						<option value="Allgemein" selected="selected">Allgemein</option>
						<option value="Referat1">Referat 1: Wolfgang Riegler</option>
						<option value="Referat3">Referat 2: Willibald Schermann</option>
						<option value="Referat5">Referat 3: Franz Blüml</option>
						<option value="Referat4">Referat 4: Karl Zehetner</option>
						<!--  
						<option value="Webmaster">Webmaster und EDV: Josef Rohowsky</option>
						-->
				</select></td>
			</tr>
			<tr>
				<td>Betreff:</td>
				<td><input type="text" name="betreff" size="60" maxlength="60" /></td>
			</tr>
			<tr>
				<td>Meine Nachricht:</td>
				<td><textarea name="msgtext" id="msgtext" rows="12" cols="60"></textarea></td>
			</tr>
			<tr>
				<td>Absender:</td>
				<td><input type="text" name="absender" size="60" maxlength="60" /></td>
			</tr>
			<tr>
				<td>Meine E-Mail-Adresse:</td>
				<td><input type="text" name="email" size="60" maxlength="60" /></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>


		</tbody>
	</table>

</div>

<?php

echo "<p>Nach Eingabe aller Daten drücken Sie ";
echo "<button type='submit' name='phase' value='1' class=green>Senden</button></p>";

echo "<p><a href='../../index.php'>Zurück zum Hauptseite</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_EM_Edit_ph0.inc.php beendet</pre>";
}
?>