<?php

/**
 * Archivalien zum Fahrzeg, Wartung, Formular
 * 
 * @author Josef Rohowsky - neu 2018 
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_BA_AR_Edit_ph0.inc.php ist gestarted</pre>";
}

?>
<input type='hidden' name='fa_fzgnr' value="$neu['fa_fzgnr'">
<input type='hidden' name='fa_eignr' value="$neu['fa_eignr'">

<?php

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('fa_eignr');
Edit_Daten_Feld('fa_fzgnr');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Ärmel- Abzeichen');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fa_fz_suchbegr', 50);
Edit_Daten_Feld(Prefix . 'fa_arcnr', 25);
Edit_Daten_Feld(Prefix . 'fa_ar_suchbegr', 50);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fa_uidaend');
Edit_Daten_Feld(Prefix . 'fa_aenddatt');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FA_FZ_Edit.php?fz_id=" . $_SESSION[$module]['fa_fzgnr'] . "'>Zurück zur Liste</a></p>";

echo "</div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_AR_Edit_ph0.inc.php beendet</pre>";
}
?>