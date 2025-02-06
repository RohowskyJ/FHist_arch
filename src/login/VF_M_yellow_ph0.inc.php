<?php 

/**
 * Auskunft über gespeicherte Date nach DSVGO , E-Mail Adresss Abfrage
 *
 * @author Josef Rohowsky - neu 2019
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_M_yellow_ph0.inc.php ist gestarted</pre>";} ?>
<!-- ====================================================================================================== -->


	<!-- ====================================================================================================== -->
	<div class='white' style="text-align: center">
		<!-- ====================================================================================================== -->
		<p>
			Durch diese Abfrage werden Ihnen Ihre beim Verein Feuerwehrhistoriker
			in NÖ gespeicherten<br /> persönlichen Daten per E-Mail an die
			eingegebene Adresse geschickt.
		</p>

		<p style="margin-top: 0; margin-bottom: 0;" align="center">
			<span class="white"><b><font color="red">Bitte füllen Sie das Feld
						des Formulars aus:</font></b></span>
		</p>
		<p>
			<b><font size="3">Geben Sie Ihre Emailadresse ein </font><font
				size="3" color="blue">mit der Sie bei uns eingetragen sind</font><font
				size="3"> und an welche die Information gesendet werden soll:</font>
				<font size="2">&nbsp;&nbsp;</font></b> <br />

		<div class="w3-centered">
			<small><input type="text" name="EMail" value="<?php echo $EMail ?>"
				size="44"
				style="border-width: 2pt; border-color: red; border-style: groove;"
				maxlength="44"> </small>
		</div>
              
   <?php
# echo "m_yellow_ph0 L 028: \$err3 $err3 \$errM $errM <br/>";
if (! empty($err3)) {
    echo "<p align='center'><b><font color='red'>Sie haben keine Email-Adresse eingetragen.</font></b><br/>";
} 
else if (! empty($errM)) {
    echo "<p align='center'><b><font color='red'>Die eingegebene Email ist in der Mitgliederdatenbank nicht registriert.</font></b><br/>";
}
?>           

<br>
		<button type='submit' name='phase' value=1
			style='background-color: white; border-color: green; color: darkgreen'>Abfrage
			absenden</button>
		&nbsp; &nbsp; &nbsp; &nbsp;
		<!--  
 <button type='submit' name='phase' value=99 style='background-color:white; border-color:light-blue; color:blue'>Zum Auswahlmenu</button> &nbsp; &nbsp; &nbsp; &nbsp; 
 -->
		<button type='submit' name='phase' value=90
			style='background-color: white; border-color: orange; color: red'>Beenden
			(Logoff/HomePage)</button>
	</div>
	<br>

	<p style="margin-top: 0pt; margin-bottom: 0%;" align="center">
		<b><span style="font-size: 10pt;">Nach dem Absenden dieses Formulars
				werden an Hand der Emailadresse<br>die o.a. Stati vom System
				gepr&uuml;ft und die Information an Sie &uuml;bermittelt.
		</span></b>
	</p>
	<p style="margin-top: 0pt; margin-bottom: 0%;" align="center">&nbsp;</p>
	<p align="center" style="margin-top: 0; margin-bottom: 0;">
		<b><span style="font-size: 10pt;">Wird die eingegebene Emailadresse in
				der Mitgliederdatenbank</span></b><font color="red"><b><span
				style="font-size: 10pt;"> nicht gefunden</span></b></font><font
			color="blue"><b><span style="font-size: 10pt;"><br>&nbsp;(nicht
					verifiziert) - erhalten Sie sofort eine negative Rückmeldung!</span></b></font>
	</p>
	<p style="margin-top: 0pt; margin-bottom: 0%;" align="center">
		<b><span style="font-size: 10pt;"><font color="red">Sie sind dann
					entweder noch nicht oder mit einer anderen als der oben angegebenen
					E-Mail-Adresse bei unserem Verein registriert.</font></span></b>
	</p>

<?php if ($debug) {echo "<pre class=debug>VF_M_yellow_ph0.inc.php beendet </pre>";} ?> 

