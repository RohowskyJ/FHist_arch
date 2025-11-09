<?php
/**
 * Archivordnung, Erweiterungen für Eigentümer, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018, reorg Tabelle 2024
 *
 * Hinzufügen zweier zusätzlicher Ebenen zur Archivordnung vom ÖBFV
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_A_AOR_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_A_AOR_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<input type='hidden' name='al_id' value='" . $neu['al_id'] . "'/>";
echo "<input type='hidden' name='al_sg' value='" . $neu['al_sg'] . "'/>";

# =========================================================================================================
Edit_Tabellen_Header('Archivordnung- Erweiterung');
# =========================================================================================================

Edit_Daten_Feld('al_id');
Edit_Daten_Feld('al_sg');
echo "Sachgebiet " . $_SESSION[$module]['ar_grp'] . " &nbsp; " . $_SESSION[$module]['ar_name'] . " <br>";

# =========================================================================================================
Edit_Separator_Zeile('Archivalien- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('al_lcsg', 2);
Edit_Daten_Feld('al_lcssg', 2);
Edit_Daten_Feld('al_lcssg_s0', 2);
Edit_Daten_Feld('al_lcssg_s1', 2);
Edit_textarea_Feld('al_bezeich');

Edit_Daten_Feld('al_sammlung',20);

# =========================================================================================================
Edit_Tabellen_Trailer();


if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie  ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_A_AOR_List.php'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_A_AOR_Edit_ph0.inc.php beendet</pre>";
}
?>