<?php

/**
 * Anhänger, Typenschein, Wartung,  Anhänger
 * 
 * @author Josef Rohowsky - neu 2019
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_BA_TS_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='ft_id' value='" . $neu['ft_id'] . "' >";
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

Edit_Daten_Feld('ft_id');
Edit_Daten_Feld('fz_t_id');
Edit_Daten_Feld('fz_eignr');

# =========================================================================================================
Edit_Separator_Zeile('Daten im Typenschein');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fz_herstell_fg', 100);
Edit_Daten_Feld(Prefix . 'fz_fgtyp', 100);
Edit_Daten_Feld(Prefix . 'fz_idnummer', 50);
Edit_Daten_Feld(Prefix . 'fz_fgnr', 50);
Edit_Daten_Feld(Prefix . 'fz_baujahr', 10);
Edit_Daten_Feld(Prefix . 'fz_eig_gew', 10, 'in kg');
Edit_Daten_Feld(Prefix . 'fz_zul_g_gew', 10, 'in kg');
Edit_Daten_Feld(Prefix . 'fz_achsl_1', 10, 'in kg');
Edit_Daten_Feld(Prefix . 'fz_achsl_2', 10, 'in kg');
Edit_Daten_Feld(Prefix . 'fz_achsl_3', 10, 'in kg');

Edit_Daten_Feld(Prefix . 'fz_radstand', 20, 'in mm');
Edit_Daten_Feld(Prefix . 'fz_spurweite', 20, 'in mm');

Edit_Daten_Feld(Prefix . 'fz_lenkachsen', 10);

Edit_Daten_Feld(Prefix . 'fz_bereifung_1', 100);
Edit_Daten_Feld(Prefix . 'fz_bereifung_2', 100);
Edit_Daten_Feld(Prefix . 'fz_bereifung_3', 100);

Edit_Daten_Feld(Prefix . 'fz_bremsanl', 100);

Edit_Daten_Feld(Prefix . 'fz_feststellbr', 100);
Edit_Daten_Feld(Prefix . 'fz_verzoegerg', 100);

# =========================================================================================================
Edit_Separator_Zeile('Aufbau');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'fz_herst_aufb', 100);
Edit_Daten_Feld(Prefix . 'fz_aufb_bauart', 100);

Edit_Daten_Feld(Prefix . 'fz_aufbau', 100);
Edit_Daten_Feld(Prefix . 'fz_anh_kuppl', 100);
Edit_Daten_Feld(Prefix . 'fz_geschwind', 10);

Edit_Daten_Feld(Prefix . 'fz_abmessg_mm', 50);
Edit_Daten_Feld(Prefix . 'fz_heizung', 100);
Edit_Daten_Feld(Prefix . 'fz_farbe', 100);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'fz_aenduid');
Edit_Daten_Feld(Prefix . 'fz_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FA_FG_Edit.php?fz_id=" . $_SESSION[$module]['fz_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BH_TS_Edit_v3ph0.inc.php beendet</pre>";
}
?>