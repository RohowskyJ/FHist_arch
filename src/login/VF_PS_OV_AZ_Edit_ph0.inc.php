<?php

/**
 * Wartung der Auszeichnungen bei der Feuerwehr, Formular
 * 
 * @author Josef Rohowsky - neu 2020
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_Edit_ph0.inc ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='az_id' value='" . $neu['az_id'] . "'>";
echo "<input type='hidden' name='az_fw_id' value='" . $neu['az_fw_id'] . "'>";
echo "<input type='hidden' name='az_ad_id' value='" . $neu['az_ad_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('Auszeichungen, Details');
# =========================================================================================================

Edit_Daten_Feld('az_id');
Edit_Daten_Feld('az_fw_id');
Edit_Daten_Feld('az_ad_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnung- Abzeichen');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'az_stufe', 10);
Edit_Daten_Feld(Prefix . 'az_mat', 15);

Edit_textarea_Feld(Prefix . 'az_beschr');
# =========================================================================================================
Edit_Separator_Zeile('Abbilder der Auszeichnung');
# =========================================================================================================
echo "<input type='hidden' name='az_bild_v' value='" . $neu['az_bild_v'] . "'>";
echo "<input type='hidden' name='az_bild_r' value='" . $neu['az_bild_r'] . "'>";
echo "<input type='hidden' name='az_bild_m' value='" . $neu['az_bild_m'] . "'>";
echo "<input type='hidden' name='az_bild_m_r' value='" . $neu['az_bild_m_r'] . "'>";
echo "<input type='hidden' name='az_bild_klsp' value='" . $neu['az_bild_klsp'] . "'>";
echo "<input type='hidden' name='az_urkund_1' value='" . $neu['az_urkund_1'] . "'>";
echo "<input type='hidden' name='az_urkund_2' value='" . $neu['az_urkund_2'] . "'>";

$pict_path = $path2ROOT."login/AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";

$Feldlaenge = "300px";

$pic_arr = array(
    "1" => "|||az_bild_v",
    "2" => "|||az_bild_r",
    "3" => "|||az_bild_m",
    "4" => "|||az_bild_m_r",
    "5" => "|||az_bild_klsp"
);
VF_Multi_Foto($pic_arr);
$pic_arr = array(
    "6" => "|||az_urkund_1",
    "7" => "|||az_urkund_2"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'az_aend_uid');
Edit_Daten_Feld(Prefix . 'az_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();


if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VFH_PS_OV_AZ_Edit.php?ID=" . $_SESSION[$module]['fw_id'] . ">Zurück zur Liste</a></p>";

echo "</div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_Edit_ph0.inc beendet</pre>";
}
?>