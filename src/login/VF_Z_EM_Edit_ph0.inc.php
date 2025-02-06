<?php

/**
 * Automatische Benachrichtigung für ADMINS bei Änderungen, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='em_flnr' value='" . $neu['em_flnr'] . "' />";
echo "<input type='hidden' name='em_mitgl_nr' value='" . $neu['em_mitgl_nr'] . "' />";

# =========================================================================================================
Edit_Tabellen_Header('Administrator E-Mails');
# =========================================================================================================

Edit_Daten_Feld('em_flnr');
Edit_Daten_Feld('em_mitgl_nr');

# =========================================================================================================
Edit_Separator_Zeile('Benutzer');
# =========================================================================================================

Edit_Daten_Feld('mi_name', 35);

Edit_Select_feld('em_mail_grp', VF_Mail_Grup);
$a_arr = array(
    "a" => 'Aktiv',
    "i" => 'Inaktiv'
);
Edit_Radio_feld('em_active', $a_arr);
# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld('em_uidaend');
Edit_Daten_Feld('em_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_Z_EM_List.php'>Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph0.inc.php beendet</pre>";
}
?>