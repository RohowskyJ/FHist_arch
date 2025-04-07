<?php

/**
 * Zeitungs- Index Liste, Wartung, Formular
 *
 * @author J.Rohowsky
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_ZT_I_Edit_ph0.inc ist gestarted</pre>";
}

echo "<input type='hidden' name='ih_id' value='" . $neu['ih_id'] . "'/>";
echo "<input type='hidden' name='ih_zt_id' value='" . $neu['ih_zt_id'] . "'/>";
# =========================================================================================================
Edit_Tabellen_Header('Inhalt');
# =========================================================================================================

Edit_Daten_Feld('ih_id');
Edit_Daten_Feld('ih_zt_id');
# Edit_Daten_Feld('ge_invnr');
# =========================================================================================================
Edit_Separator_Zeile('Inhalts- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('ih_jahrgang', 4);
Edit_Daten_Feld('ih_jahr', 4);
Edit_Daten_Feld('ih_nr', 4);

Edit_Select_Feld(Prefix . 'ih_kateg', VF_ZT_Kategorie, '');

Edit_Select_Feld(Prefix . 'ih_sg', VF_ZT_Sachgeb, '');
Edit_Select_Feld(Prefix . 'ih_ssg', VF_ZT_Sub_Sachg, '');

Edit_Daten_Feld('ih_gruppe', 30);
Edit_textarea_Feld('ih_titel');
Edit_textarea_Feld('ih_titelerw');
Edit_textarea_Feld('ih_autor');

Edit_Daten_Feld('ih_tel', 60);
Edit_Daten_Feld('ih_fax', 60);
Edit_Daten_Feld('ih_seite', 4);
Edit_Daten_Feld('ih_spalte', 4);

Edit_textarea_Feld('ih_fwehr');
# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('ih_uidaend');
Edit_Daten_Feld('ih_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_O_ZT_List.php'>Zurück zur Liste</a></p>";

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_I_Edit_ph0.inc beendet</pre>";
}
?>