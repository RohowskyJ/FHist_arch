<?php

/**
 * Fahrzeuge , Reparaturen, Formular
 * 
 * @authorJosef Rohowsky - neu 2019
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_BA_RE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fz_id' value='" . $neu['fz_id'] . "'>";
echo "<input type='hidden' name='fz_rep_id' value='" . $neu['fz_rep_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('Reparaturen');
# =========================================================================================================

echo "<input type='hidden' name='fz_rep_id' value='" . $neu['fz_rep_id'] . "'>";
Edit_Daten_Feld('fz_rep_id');
Edit_Daten_Feld('fz_id');

# =========================================================================================================
Edit_Separator_Zeile('Typisierung-Änderungen');
# =========================================================================================================

Edit_Daten_Feld('fz_repdat', 10);
Edit_textarea_Feld('fz_reptext');

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fz_uidaend');
Edit_Daten_Feld(Prefix . 'fz_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FA_FZ_Edit.php?fz_id=" . $_SESSION[$module]['fz_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_RE_Edit_ph0.inc.php beendet</pre>";
}
?>