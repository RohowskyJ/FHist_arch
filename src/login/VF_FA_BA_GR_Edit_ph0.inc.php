<?php

/**
 * Fahrzeuge, Geräteräume, Wartung, Formular
 * 
 * @author Josef Rohowsky - neu 2029
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_BA_GR_Edit_ph0.inc.php ist gestarted</pre>"; 
}

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header('Geräteraum');
# =========================================================================================================
echo "<input type='hidden' name='lr_fzg' value='" . $neu['lr_fzg'] . "'>";
echo "<input type='hidden' name='lr_id' value='" . $neu['lr_id'] . "'>";
Edit_Daten_Feld('lr_id');
Edit_Daten_Feld('lr_fzg');

# =========================================================================================================
Edit_Separator_Zeile('Laderaum');
# =========================================================================================================

Edit_Daten_Feld('lr_raum', 60);
Edit_textarea_Feld('lr_beschreibung');

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================

echo "<input type='hidden' name='lr_foto_1' value='" . $neu['lr_foto_1'] . "'>";
echo "<input type='hidden' name='lr_foto_2' value='" . $neu['lr_foto_2'] . "'>";
echo "<input type='hidden' name='lr_foto_3' value='" . $neu['lr_foto_3'] . "'>";
echo "<input type='hidden' name='lr_foto_4' value='" . $neu['lr_foto_4'] . "'>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";

$Feldlaenge = "100px";

$pic_arr = array(
    "1" => "||lr_komm_1|lr_foto_1",
    "2" => "||lr_komm_2|lr_foto_2",
    "3" => "||lr_komm_3|lr_foto_3",
    "4" => "||lr_komm_4|lr_foto_4"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'lr_uidaend');
Edit_Daten_Feld(Prefix . 'lr_aenddate');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {

    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VFH_FA_FZ_Edit.php?fz_id=" . $_SESSION[$module]['fz_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_BA_GR_Edit_ph0.inc.php beendet</pre>";
}
?>