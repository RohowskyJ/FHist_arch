<?php

/**
 * Auskunft über gespeicherte Date nach DSVGO , Nachrichten Aufbau uns Anzige Kurztext
 *
 * @author Josef Rohowsky - neu 2019
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_M_yellow_ph1.inc.php ist gestarted</pre>";
}

echo "<table class=\"w3-table-all\"><tbody>";
while ($row = mysqli_fetch_object($result)) {

    $einvart = $row->mi_einv_art;
    $einverkl = $row->mi_einversterkl;
    $einv_dat = $row->mi_einv_dat;
    $f_einverkl = $einverkl;

    $msg = "<table class=\"w3-table-all\" width='100%'><tbody>";
    echo "<tr><td colspan='2' style='padding:4pt;font-size:1.5em;font-weight:bold;'>Feuerwehrhistoriker in NÖ<br>";
    echo "Datenkontrolle laut DSGVO</td></tr>";

    echo "<tr><td colspan='2' style='padding:4pt;font-size:1.4em;'>$row->mi_anrede $row->mi_titel $row->mi_vname $row->mi_name $row->mi_n_titel</td></tr> ";

    echo "<tr><td style='margin-top:4pt; margin-right:3pt; margin-bottom:4pt; margin-left:3pt; padding-top:4pt; padding-right:3pt; padding-bottom:4pt; padding-left:3pt; border-top-width:2pt; border-right-width:0pt; border-bottom-width:0pt; border-left-width:0pt; border-top-color:fuchsia; border-top-style:solid; border-bottom-style:none;' > ";
    echo "Emailadresse: </td>";
    echo "<td style='margin-top:4pt; margin-right:3pt; margin-bottom:4pt; margin-left:3pt; padding-top:4pt; padding-right:3pt; padding-bottom:4pt; padding-left:3pt; border-top-width:2pt; border-right-width:0pt; border-bottom-width:0pt; border-left-width:0pt; border-top-color:fuchsia; border-top-style:solid; border-bottom-style:none;' >";
    echo "<b><font size='+1'>$row->mi_email</font></b></td></tr>";

    echo "<tr><td widht='30%'>Geburts-Datum: </td>";
    echo "<td  style='font-weight:bold'><b><font size='+1'>$row->mi_gebtag</font></b><tr>";

    echo "<tr><td >Adresse: </td>";
    echo "<td style='font-weight:bold'>$row->mi_anschr</td></tr>";

    echo "<tr><td >Plz Ort: </td>";
    echo "<td style='font-weight:bold' >$row->mi_plz $row->mi_ort</td></tr>";

    echo "<tr><td>Land:</td>";
    echo "<td style='font-weight:bold'>$row->mi_staat</td></tr>";

    echo "<p style='font-weight:bold'>Die vorhandenen Daten werden per EMail an die angegebene Adresse abgeschickt.</p>";
    echo "<input type='hidden' name='EMail' value='$row->mi_email'> ";
    echo "";
} // while Ende

echo $msg;

if ($einverkl == "Y") {
    echo "<tr><td colspan='4'  style='font-weight:bold;font-size:1.2em;'><b>Die Einverständniserklärung entsprechend Datenschutzgesetz wurde am $einv_dat gegeben.</b></td></tr>";
    echo "<p align='center'> <button type='submit' name='phase' value=2 style='background-color:white; border-color:green; color:darkgreen'>Weiter</button></p>";
    echo "<input  type='radio' name='einverkl' value='$einverkl' >";
} else {

    echo '  <tr> <td colspan="4" height="20px">    &nbsp; </td></tr> ';

    echo '  <tr>                                                                                                                                                         ';
    echo '    <td colspan="4" >                                                                                                               ';
    echo '      <p align="center" style="font-weight:bold;font-size:1.2em;">                                                                                                                                      ';
    echo '        <b>Einwilligungserklärung im Sinne der EU Datenschutzgrundverordnung: <b/><br/>';
    echo '         Sie (als AntragstellerIn) stimmen ausdrücklich zu, dass Ihre oberhalb angezeigten persönlichen Daten, nämlich Name, Adresse, e-Mail-Adresse, Telefonnummer, Geburtsdatum  ';
    echo '         zum Zweck der elektronischen Zusendung von Vereinsnachrichten, Veranstaltungseinladungen, Anmeldebestätigungen, Zahlungsaufforderungen, Erinnerungen, und postalischer Zusendung von bestellten Gegenständen im Verein gespeichert, verwaltet und verarbeitet werden.<br /> ';
    echo '         Sie können jederzeit Ihre bei uns gespeicherten Daten überprüfen und ändern lassen. Wenn Sie die Löschung Ihrer Daten bei uns beantragen, erlischt automatisch Ihre Vereinsmitgliedschaft, da die Mitgliedsverwaltung mit Datenverarbeitung durchgeführt wird. ';
    echo '          </b></p>   ';

    echo '    </td>                                                                                                                                                       ';
    echo '  </tr>                                                                                                                                                       ';

    echo '<tr>   ';
    echo '  <td style="padding-top:4pt; padding-right:2pt; padding-bot font-weight:bold;font-size:1.4em;" colspan="4" class="w3-red"  align="center">';
    echo '<font size="+1">Ich willige in die Speicherung und Verarbeitung meiner persönlichen Daten ein: <br/></font> ';
    echo " <font size='+3'>                 <input name=\"einverkl\" value=\"Y\" tabindex=\"33\" type=\"radio\" /> &nbsp; JA  &nbsp; &nbsp; &nbsp; &nbsp; </font>    ";
    echo "                  <input name='einverkl' value='N' tabindex='34' type='radio' checked='checked' />  &nbsp; Nein   <br/>  ";
    echo '   <p align="center">  ';
    echo "<button type='submit' name='phase' value=2 style='background-color:white ; border-color:green; color:darkgreen'>Weiter</button> </p>";

    echo '</td></tr> ';
}

if ($debug) {
    echo "<pre class=debug>VF_M_yellow_ph1.inc.php beendet</pre>";
}
?> 