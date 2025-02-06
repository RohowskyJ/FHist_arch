<?php

/**
 * Mitgliederverwaltung, Formular
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_M_Unt_Edit_ph0.inc.php ist gestarted </pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='fu_id' value='" . $neu['fu_id'] . "'>";
echo "<input type='hidden' name='p_uid' value='" . $_SESSION['VF_Prim']['p_uid'] . "'>";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Mitglieder- Daten');
# =========================================================================================================

Edit_Daten_Feld('fu_id');

# =========================================================================================================
Edit_Separator_Zeile('unterstützer-Daten');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'fu_orgname', 50);

Edit_Radio_Feld(Prefix . 'fu_anrede', VF_Anrede);
Edit_Daten_Feld(Prefix . 'fu_dgr', 10, 'FF Dienstgrad');
Edit_Daten_Feld(Prefix . 'fu_tit_vor', 50);
Edit_Daten_Feld(Prefix . 'fu_name', 50);
Edit_Daten_Feld(Prefix . 'fu_vname', 50);
Edit_Daten_Feld(Prefix . 'fu_tit_nach', 50);

Edit_Daten_Feld(Prefix . 'fu_adresse', 50);
Edit_Daten_Feld(Prefix . 'fu_plz', 20);
Edit_Daten_Feld(Prefix . 'fu_ort', 50);

Edit_Daten_Feld(Prefix . 'fu_tel', 50);

Edit_Daten_Feld(Prefix . 'fu_email', 50);

# =========================================================================================================
Edit_Separator_Zeile('Organisatorisches');
# =========================================================================================================
Edit_Radio_Feld(Prefix . 'fu_kateg', VF_Unterst);

Edit_Radio_Feld(Prefix . 'fu_weihn_post', VF_JN);

Edit_Radio_Feld(Prefix . 'fu_zugr', VF_JN);

Edit_Radio_Feld(Prefix . 'fu_aktiv', VF_JN);


# ================================================================================================
Edit_Separator_Zeile('Letzte Änderungen');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'fu_uidaend');
Edit_Daten_Feld(Prefix . 'fu_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd'] || $_SESSION['VF_Prim']['p_uid'] == $neu['fu_id']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_M_Unterst_List.php>Zurück zur Liste</a></p>";

echo "</fieldset></div>";

if ($debug) {
    echo "<pre class=debug>VF_M_Unt_Edit_ph0.inc.php beendet</pre>";
}
?>