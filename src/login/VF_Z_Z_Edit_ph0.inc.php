<?php

/**
 * Wartung der Zugriffsberechtgungen der Benutzer, Formular
 *
 * @author Josef Rohowsky -  neu 2018
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_Z_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_Z_Z_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

# echo "L 021 neu <br>";var_dump($neu);
echo "<input type='hidden' name='zu_id' value='" . $neu['zu_id'] . "' />";

$Edit_Funcs_FeldName = true; // Feldname der Tabelle wird nicht angezeigt !!

# =========================================================================================================
Edit_Tabellen_Header('Berechtigungen für '.$neu['be_vname'].' '. $neu['be_name']);
# =========================================================================================================

Edit_Daten_Feld('zu_id');
# Edit_Daten_Feld('zu_uid');

# =========================================================================================================
Edit_Separator_Zeile('Berechtigungen je Sachbereich - neues System');
# =========================================================================================================

Edit_Select_Feld('zu_F_G', VF_Berechtig);
Edit_Select_Feld('zu_F_M', VF_Berechtig);
Edit_Select_Feld('zu_S_G', VF_Berechtig);
Edit_Select_Feld('zu_PSA', VF_Berechtig);
Edit_Select_Feld('zu_ARC', VF_Berechtig);
Edit_Select_Feld('zu_INV', VF_Berechtig);
Edit_Select_Feld('zu_OEF', VF_Berechtig);
Edit_Select_Feld('zu_MVW', VF_Berechtig);
Edit_Select_Feld('zu_ADM', VF_Berechtig);
Edit_Select_Feld('zu_SUC', VF_Berechtig);

# =========================================================================================================
Edit_Separator_Zeile('Für die Eigentümer');
# =========================================================================================================

$Ei_opt1 = VF_Sel_Eigner('zu_eignr_1', '8');
Edit_Select_Feld('zu_eignr_1', $Ei_opt1);
$Ei_opt2 = VF_Sel_Eigner('zu_eignr_2', '8');
Edit_Select_Feld('zu_eignr_2', $Ei_opt2);
$Ei_opt3 = VF_Sel_Eigner('zu_eignr_3', '8');
Edit_Select_Feld('zu_eignr_3', $Ei_opt3);
$Ei_opt4 = VF_Sel_Eigner('zu_eignr_4', '8');
Edit_Select_Feld('zu_eignr_4', $Ei_opt4);
$Ei_opt5 = VF_Sel_Eigner('zu_eignr_5', '8');
Edit_Select_Feld('zu_eignr_5', $Ei_opt5);

# =========================================================================================================
Edit_Separator_Zeile('Passwort- Definieren / Ändern, Gültigkeit ');
# =========================================================================================================
Edit_Daten_Feld('passwd', 50, 'und zur Kontrolle nochmals: ');
Edit_Daten_Feld('passwd_K', 50);

Edit_Daten_Feld('zu_valid_until', 20, '', 'type=date');
# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld('zu_uidaend');
Edit_Daten_Feld('zu_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_Z_B_List.php'>Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_Z_Z_Edit_ph0.inc.php beendet</pre>";
}
?>