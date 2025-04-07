<?php

/**
 * Auszeichnungs- Verwaltung CTIF- Auszeichnungen, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_CT_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='ac_id' value='" . $neu['ac_id'] . "'>";
echo "<input type='hidden' name='ac_fw_id' value='" . $neu['ac_fw_id'] . "'>";
echo "<input type='hidden' name='ac_ab_id' value='" . $neu['ac_ab_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('CTIF Auszeichnungen');
# =========================================================================================================

Edit_Daten_Feld('ac_id');
Edit_Daten_Feld('ac_fw_id');
Edit_Daten_Feld('ac_ab_id');

# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnung- Abzeichen');
# =========================================================================================================

# Edit_Daten_Feld(Prefix.'ac_stufe',10);
# Edit_Daten_Feld(Prefix.'ac_mat',15);

Edit_textarea_Feld(Prefix . 'ac_beschr');
# =========================================================================================================
Edit_Separator_Zeile('Abbilder der Auszeichnung');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='ac_wettbsp_v' value='" . $neu['ac_wettbsp_v'] . "'>";
echo "<input type='hidden' name='ac_wettbsp_r' value='" . $neu['ac_wettbsp_r'] . "'>";
echo "<input type='hidden' name='ac_gr_med_go_v' value='" . $neu['ac_gr_med_go_v'] . "'>";
echo "<input type='hidden' name='ac_gr_med_go_r' value='" . $neu['ac_gr_med_go_r'] . "'>";
echo "<input type='hidden' name='ac_gr_med_si_v' value='" . $neu['ac_gr_med_si_v'] . "'>";
echo "<input type='hidden' name='ac_gr_med_si_r' value='" . $neu['ac_gr_med_si_r'] . "'>";
echo "<input type='hidden' name='ac_gr_med_br_v' value='" . $neu['ac_gr_med_br_v'] . "'>";
echo "<input type='hidden' name='ac_gr_med_br_r' value='" . $neu['ac_gr_med_br_r'] . "'>";

echo "<input type='hidden' name='ac_kl_med_go_v' value='" . $neu['ac_kl_med_go_v'] . "'>";
echo "<input type='hidden' name='ac_kl_med_go_r' value='" . $neu['ac_kl_med_go_r'] . "'>";
echo "<input type='hidden' name='ac_kl_med_si_v' value='" . $neu['ac_kl_med_si_v'] . "'>";
echo "<input type='hidden' name='ac_kl_med_si_r' value='" . $neu['ac_kl_med_si_r'] . "'>";
echo "<input type='hidden' name='ac_kl_med_br_v' value='" . $neu['ac_kl_med_br_v'] . "'>";
echo "<input type='hidden' name='ac_kl_med_br_r' value='" . $neu['ac_kl_med_br_r'] . "'>";

echo "<input type='hidden' name='ac_so_med_go_v' value='" . $neu['ac_so_med_go_v'] . "'>";
echo "<input type='hidden' name='ac_so_med_go_r' value='" . $neu['ac_so_med_go_r'] . "'>";
echo "<input type='hidden' name='ac_so_med_si_v' value='" . $neu['ac_so_med_si_v'] . "'>";
echo "<input type='hidden' name='ac_so_med_si_r' value='" . $neu['ac_so_med_si_r'] . "'>";
echo "<input type='hidden' name='ac_so_med_br_v' value='" . $neu['ac_so_med_br_v'] . "'>";
echo "<input type='hidden' name='ac_so_med_br_r' value='" . $neu['ac_so_med_br_r'] . "'>";

echo "<input type='hidden' name='ac_fabz_v' value='" . $neu['ac_fabz_v'] . "'>";
echo "<input type='hidden' name='ac_fabz_r' value='" . $neu['ac_fabz_r'] . "'>";

echo "<input type='hidden' name='ac_teiln_v' value='" . $neu['ac_teiln_v'] . "'>";
echo "<input type='hidden' name='ac_teiln_r' value='" . $neu['ac_teiln_r'] . "'>";

echo "<input type='hidden' name='ac_urkund_1' value='" . $neu['ac_urkund_1'] . "'>";
echo "<input type='hidden' name='ac_urkund_2' value='" . $neu['ac_urkund_2'] . "'>";

$pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";

$Feldlaenge = "300px";

# =========================================================================================================
Edit_Separator_Zeile('Wettbewerbsspange');
# =========================================================================================================

$pic_arr = array(
    "1" => "||ac_wettb_dok_v|ac_wettbsp_v",
    "2" => "||ac_wettb_dok_r|ac_wettbsp_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Große Medaille');
# =========================================================================================================

$pic_arr = array(
    "3" => "||ac_gr_med_g_dok_v|ac_gr_med_go_v",
    "4" => "||ac_gr_med_g_dok_r|ac_gr_med_go_r",
    "5" => "|||ac_gr_med_si_v",
    "6" => "|||ac_gr_med_si_r",
    "7" => "|||ac_gr_med_br_v",
    "8" => "|||ac_gr_med_br_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Kleine Medaille');
# =========================================================================================================

$pic_arr = array(
    "9" => "||ac_kl_med_g_dok_v|ac_kl_med_go_v",
    "10" => "||ac_kl_med_g_dok_r|ac_kl_med_go_r",
    "11" => "|||ac_kl_med_si_v",
    "12" => "|||ac_kl_med_si_r",
    "13" => "|||ac_kl_med_br_v",
    "14" => "|||ac_kl_med_br_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Sonder Ausgabe');
# =========================================================================================================
Edit_textarea_Feld(Prefix . 'ac_so_beschr_1');
$pic_arr = array(
    "15" => "||ac_so_med_g_dok_v|ac_so_med_go_v",
    "16" => "||ac_so_med_g_dok_r|ac_so_med_go_r",
    "17" => "|||ac_so_med_si_v",
    "18" => "|||ac_so_med_si_r",
    "19" => "|||ac_so_med_br_v",
    "20" => "|||ac_so_med_br_r"
);

VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Urkunden ');
# =========================================================================================================

$pic_arr = array(
    "21" => "||ac_urk_beschr_1|ac_urkund_1",
    "22" => "||ac_urk_beschr_2|ac_urkund_2"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Fest Abzeichen');
# =========================================================================================================

$pic_arr = array(
    "23" => "||ac_fabz_dok_v|ac_fabz_v",
    "24" => "||ac_fabz_dok_r|ac_fabz_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Teilnehmer- Abzeichen');
# =========================================================================================================

$pic_arr = array(
    "25" => "||ac_teiln_dok_v|ac_teiln_v",
    "26" => "||ac_teiln_dok_r|ac_teiln_r"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'ac_aend_uid');
Edit_Daten_Feld(Prefix . 'ac_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
echo "<p><a href='VFH_PS_OV_AD_Edit.php?ID=" . $_SESSION[$module]['fw_id'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_CT_Edit_ph0.inc.php beendet</pre>";
}
?>