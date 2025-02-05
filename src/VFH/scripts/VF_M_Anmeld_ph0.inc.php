<?php
/**
 * Anmeldung eines neuen Mitgliedes, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_M_Anmeld_ph0.inc.php ist gestarted</pre>";
}
$ed_lcnt = 0;
Edit_Tabellen_Header('Mitgliedsanmeldung');
# =========================================================================================================

echo "<strong>Ich </strong>";
echo "<p><strong>trete dem Verein \"Feuerwehrhistoriker in Niederösterreich\" bei und akzeptiere </strong>";
echo "<strong>die Statuten </strong>(werden auf Wunsch zugesandt). <strong>Den Mitgliedsbeitrag von 25.-€ pro Jahr werde ich bezahlen (UM-Unterstützendes Mitglied und FG-Sachbearbeiter FG).</strong></p>";

# =========================================================================================================
Edit_Separator_Zeile('Persönliche Daten');
# =========================================================================================================
if ($err != "") {
    echo "<br><b style='color:red;background:white;'>$err</b><br>";
}
echo "<input type='hidden' name='mi_id' value='" . $neu['mi_id'] . "' />";

Edit_Radio_Feld(Prefix . 'mi_anrede', array(
    "Fr." => "Frau ",
    "Hr." => "Herr "
), '', 'required');
Edit_Daten_Feld(Prefix . 'mi_titel', 10, 'Akad. Titel');
Edit_Daten_Feld(Prefix . 'mi_name', 50, '', 'required');
Edit_Daten_Feld(Prefix . 'mi_vname', 50, '', 'required');

Edit_Daten_Feld(Prefix . 'mi_n_titel', 10, 'Titel, nachgestellt');
Edit_Daten_Feld(Prefix . 'mi_dgr', 10, 'FF Dienstgrad');

Edit_Daten_Feld(Prefix . 'mi_gebtag', 10, '', 'type="date" required');

$ST_Opt = VF_Sel_Staat('mi_staat', '9');
Edit_Select_Feld(Prefix . 'mi_staat', $ST_Opt, '', 'required');

Edit_Daten_Feld(Prefix . 'mi_anschr', 50, '', 'required');
Edit_Daten_Feld(Prefix . 'mi_plz', 7, '', 'required');
Edit_Daten_Feld(Prefix . 'mi_ort', 50, '', 'required');

Edit_Daten_Feld(Prefix . 'mi_tel_handy', 100, '', 'required');

Edit_Daten_Feld(Prefix . 'mi_fax', 50);
if ($mail_err != "") {
    echo "<br><b style='color:red;background:white;'>$mail_err</b><br>";
}
Edit_Daten_Feld(Prefix . 'mi_email', 50, '', 'required');

# =========================================================================================================
Edit_Separator_Zeile('Organisations- Daten');
# =========================================================================================================

Edit_Radio_Feld(Prefix . 'mi_mtyp', M_Typ);

Edit_Select_Feld(Prefix . 'mi_org_typ', M_Org);
Edit_Daten_Feld(Prefix . 'mi_org_name', 50);

# =========================================================================================================
Edit_Separator_Zeile('Wo sind meine Interessen: ');
# =========================================================================================================

echo "<input type='hidden' name='mi_ref_int_2' value='" . $neu['mi_ref_int_2'] . "' />";
echo "<input type='hidden' name='mi_ref_int_3' value='" . $neu['mi_ref_int_3'] . "' />";
echo "<input type='hidden' name='mi_ref_int_4' value='" . $neu['mi_ref_int_4'] . "' />";

Edit_CheckBox('mi_ref_int_2', VF_Referate_anmeld[2]);
Edit_CheckBox('mi_ref_int_3', VF_Referate_anmeld[3]);
Edit_CheckBox('mi_ref_int_4', VF_Referate_anmeld[4]);

# =========================================================================================================
Edit_Separator_Zeile('Datenschutz- Erklärung (DSVGO)');
# =========================================================================================================

Edit_Tabellen_Trailer();

echo "  <div class='w3-container w3-blue'   ";

echo '      <p align="center">                                                                                                                                      ';
echo '        <font size="4" color="whitew"><b>Einwilligungserklärung im Sinne der EU Datenschutzgrundverordnung: <b/><br/></font>';
echo '         <font size="2" color="white">Sie (als AntragstellerIn) stimmen ausdrücklich zu, dass Ihre soeben erhobenen persönlichen Daten, nämlich Name, Adresse, e-Mail-Adresse, Telefonnummer, Geburtsdatum  ';
echo '         zum Zweck der elektronischen Zusendung von Vereinsnachrichten, Veranstaltungseinladungen, Anmeldebestätigungen, Zahlungsaufforderungen, Erinnerungen, und postalischer Zusendung von bestelltem Material bei erfolgtem Beitritt im Verein gespeichert, verwaltet und verarbeitet werden.<br /> ';
echo '         Sie können jederzeit Ihre bei uns gespeicherten Daten überprüfen und ändern lassen. Wenn Sie die Löschung Ihrer Daten bei uns beantragen, werden die Daten gelöscht und Ihre Vereinsmitgliedschaft erlischt automatisch. ';
echo '         </b></font></p>   ';

echo '<font size="+1">Ich willige in die Speicherung und Verarbeitung meiner persönlichen Daten ein: <br/></font> ';
echo "                  <input name=\"einverkl\" value=\"Y\" tabindex=\"33\" type=\"radio\"        required           /> &nbsp; JA  &nbsp; &nbsp; &nbsp; &nbsp;     ";
echo "                  <input name=\"einverkl\" value=\"N\" tabindex=\"34\" type=\"radio\" checked='checked' />  &nbsp; Nein   <br/>  ";

echo '</div> ';

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";

echo "<p><a href='../'>Zurück zum Index (ABBRUCH der Anmeldung!)</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_M_Anmeld_ph0.inc.php beendet</pre>";
}
?>