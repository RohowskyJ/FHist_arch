<?php

/**
 * Fahrzeuge, Typenschein, Formular
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */
if ($debug) {
    echo "<pre class=debug>VFH_2_BA_TE_Edit_ph0.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fz_eign_id' value='$neu[fz_eign_id]'>";
echo "<input type='hidden' name='fz_id' value='$neu[fz_id]'>";
# =========================================================================================================
Edit_Tabellen_Header('Eigentums- Verlauf');
# =========================================================================================================

Edit_Daten_Feld('fz_eign_id');
Edit_Daten_Feld('fz_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten Zulassung(en)');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fz_docbez', 100);
Edit_Daten_Feld(Prefix . 'fz_zul_dat', 10);
Edit_Daten_Feld(Prefix . 'fz_zul_end_dat', 10);
Edit_Daten_Feld(Prefix . 'fz_zuldaten', 100);

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

echo "<p><a href='VF_FA_FZ_Edit.php?fz_id=" . $_SESSION[$module]['fz_id'] . "'>Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_TE_Edit_ph0.inc.php beendet</pre>"; 
}
?>