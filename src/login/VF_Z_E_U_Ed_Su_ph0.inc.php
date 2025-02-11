<?php

/**
 * Foto Urheber, Details, Formular
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FO_M_Ed_su_ph0.inc.php ist gestarted</pre>";
}

echo $Err_Msg;
echo "<input name='fs_flnr' id='fs_flnr' type='hidden' value='" . $neu['fs_flnr'] . "' />";
echo "<input name='fs_fm_id' id='fs_fm_id' type='hidden' value='" . $neu['fs_fm_id'] . "' />";
echo "<input name='fs_eigner' id='fs_eigner' type='hidden' value='" . $neu['fs_eigner'] . "' />";
echo "<input name='fs_typ' id='fs_typ' type='hidden' value='" . $neu['fs_typ'] . "' />";
$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Urheber Kurzzeichen');
# =========================================================================================================

Edit_Daten_Feld('fs_flnr');
Edit_Daten_Feld('fs_fm_id');
Edit_Daten_Feld('fs_eigner');
Edit_Daten_Feld('fs_typ');

# =========================================================================================================
Edit_Separator_Zeile('Urheber');
# =========================================================================================================

Edit_Daten_feld('fs_fotograf', 80);

Edit_Daten_Feld('fs_urh_kurzz', 15);

# =========================================================================================================
Edit_Separator_Zeile('Änderungen');
# =========================================================================================================
Edit_Daten_Feld('fs_uidaend');
Edit_Daten_Feld('fs_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FO_U_Edit.php?fm_id=".$neu['fs_fm_id']."'>Zurück zur Liste</a></p>";

if ($debug) {
    echo "<pre class=debug>VF_FO_U_Ed_su_ph0.inc.php beendet</pre>";
}
?>