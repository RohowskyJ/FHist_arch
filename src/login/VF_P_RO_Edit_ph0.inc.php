<?php
/**
 * Protokolle des Vereines, fürs Hochladen abfragen
 *
 * @author  Josef Rohowsky - neu 2021
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_P_RO_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<input type='hidden' name='fo_id' value='" . $neu['fo_id'] . "'/>";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('fo_id');

# =========================================================================================================
Edit_Separator_Zeile('Protokoll ins Netz laden');
# =========================================================================================================

Edit_Upload_File('pr_name', '1');

# =========================================================================================================
Edit_Separator_Zeile('Namenskonvention (muss eingehalten werden - sonst passt die Anzeige nicht):');
# =========================================================================================================

echo "<tr><td colspan='5'>yyyymmdd_pr-ID_Gremium_Protok*.pdf</td></tr> ";
echo "<tr><td>Sitzungs-Datum</td><td>Identifizierung</td><td>Gremium</td><td>Stichwort 'Protok'</td></tr>";
echo "<tr><th>JahrMoTa_</th><th>Fortl.Nr_</th><th>Vorst-, EVorst-,R4_</th><th>Protok</th></tr>";

Edit_Tabellen_Trailer();

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";

# echo "<p><button type='submit' name='phase' value='99' class=green>Zurück zur Liste</button></p>";
echo "<p><a href='VF_P_RO_List.php'>Zurück zur Liste</a></p>";

if ($debug) {
    echo "<pre class=debug>VF_P_RO_Edit_ph0.inc.php beendet</pre>";
}
?>