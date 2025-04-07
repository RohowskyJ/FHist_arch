<?php

/**
 * Auszeichnungs- Veraltung Vereins- Auszeichnungen, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_VE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='av_id' value='" . $neu['av_id'] . "'>";
echo "<input type='hidden' name='av_fw_id' value='" . $neu['av_fw_id'] . "'>";
echo "<input type='hidden' name='av_ab_id' value='" . $neu['av_ab_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('Auszeichnungen, von Vereinen gestiftet');
# =========================================================================================================

Edit_Daten_Feld('av_id');
Edit_Daten_Feld('av_fw_id');
Edit_Daten_Feld('av_ab_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnung- Abzeichen');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'av_beschr');
# =========================================================================================================
Edit_Separator_Zeile('Abbilder der Auszeichnung');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='av_bild_v' value='" . $neu['av_bild_v'] . "'>";
echo "<input type='hidden' name='av_bild_r' value='" . $neu['av_bild_r'] . "'>";

echo "<input type='hidden' name='av_urkund_1' value='" . $neu['av_urkund_1'] . "'>";
echo "<input type='hidden' name='av_urkund_2' value='" . $neu['av_urkund_2'] . "'>";

$pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";

$Feldlaenge = "300px";

# =========================================================================================================
Edit_Separator_Zeile('Große Medaille');
# =========================================================================================================

$pic_arr = array(
    "1" => "||av_beschr_v|av_bild_v",
    "2" => "||av_beschr_r|av_bild_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Urkunden ');
# =========================================================================================================

$pic_arr = array(
    "3" => "|||av_urkund_1",
    "4" => "|||av_urkund_2"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'av_aend_uid');
Edit_Daten_Feld(Prefix . 'av_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_PS_OV_AD_Edit.php?ID=" . $_SESSION[$module]['fw_id'] . "'>Zurück zur Liste</a></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_VE_Edit_ph0.inc beendet</pre>";
}
?>