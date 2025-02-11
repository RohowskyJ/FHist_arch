<?php

/**
 * Fahrzge, Typisierungsänderug, Formular 
 * 
 * @author Josef Rohowsky - neu 2019
 * 
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_BA_UT_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header('Umtypisierungen');
# =========================================================================================================
echo "<input type='hidden' name='fz_id' value='" . $neu['fz_id'] . "'>";
echo "<input type='hidden' name='fz_typ_id' value='" . $neu['fz_typ_id'] . "'>";
Edit_Daten_Feld('fz_typ_id');
Edit_Daten_Feld('fz_id');

# =========================================================================================================
Edit_Separator_Zeile('Typisierung-Änderungen');
# =========================================================================================================

Edit_Daten_Feld('fz_t_aenddat', 10);
Edit_textarea_Feld('fz_infotext');

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

echo "<p><a href='VF_FA_FF_Edit.php?fz_id=" . $_SESSION[$module]['fz_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_UT_Edit_ph0.inc.php beendet</pre>";
}
?>