<?php

/**
 * Fahrzeuge, fixe Enbauten, Wartung, Formular
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */

if ($debug) {
    echo "<pre class=debug>VFH_FA_BA_EB_Edit_ph0.inc.php ist gestarted</pre>"; 
}

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header('Fix- Einbauten');
# =========================================================================================================
echo "<input type='hidden' name='fz_einb_id' value='" . $neu['fz_einb_id'] . "'>";
Edit_Daten_Feld('fz_einb_id');
Edit_Daten_Feld('fz_id');

Edit_Daten_Feld('fz_gername', 50);
Edit_Daten_Feld('fz_ger_herst', 50);
Edit_Daten_Feld('fz_ger_sernr', 50);
Edit_Daten_Feld('fz_ger_baujahr', 5);
Edit_Daten_Feld('fz_ger_typ', 30);
Edit_Daten_Feld('fz_ger_leistg', 10);
Edit_Daten_Feld('fz_ger_l_einh', 50);

Edit_textarea_Feld(Prefix . 'fz_einb_komm');

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================

echo "<input type='hidden' name='fz_ger_foto_1' value='" . $neu['fz_ger_foto_1'] . "'>";
echo "<input type='hidden' name='fz_ger_foto_2' value='" . $neu['fz_ger_foto_2'] . "'>";
echo "<input type='hidden' name='fz_ger_foto_3' value='" . $neu['fz_ger_foto_3'] . "'>";
echo "<input type='hidden' name='fz_ger_foto_4' value='" . $neu['fz_ger_foto_4'] . "'>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";

$Feldlaenge = "100px";

$pic_arr = array(
    "1" => "||fz_ger_f1_beschr|fz_ger_foto_1",
    "2" => "||fz_ger_f2_beschr|fz_ger_foto_2",
    "3" => "||fz_ger_f3_beschr|fz_ger_foto_3",
    "4" => "||fz_ger_f4_beschr|fz_ger_foto_4"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld('fz_uidaend');
Edit_Daten_Feld('fz_aenddat');

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
    echo "<pre class=debug>VF_FA_BA_EB_Edit_ph0.inc.php beendet</pre>";
}
?>