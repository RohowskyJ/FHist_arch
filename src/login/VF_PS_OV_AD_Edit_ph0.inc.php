<?php

/**
 * Wartung der Abzeichen- Beschreibungen, Formular
 *
 * @author Josef Rohowsky - neu 2020
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Edit_ph0.php ist gestarted</pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='ab_id' value='" . $neu['ab_id'] . "'>";
echo "<input type='hidden' name='ab_fw_id' value='" . $neu['ab_fw_id'] . "'>";
# =========================================================================================================
Edit_Tabellen_Header('Auszeichnung, Ehrung');
# =========================================================================================================

Edit_Daten_Feld('ab_id');
Edit_Daten_Feld('ab_fw_id');
$_SESSION[$proj]['ab_id'] = $neu['ab_id'];
# =========================================================================================================
Edit_Separator_Zeile('Daten der Auszeichnung');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'ab_beschreibg');
Edit_Select_Feld(Prefix . 'ab_art', VF_Ausz);
Edit_Daten_Feld(Prefix . 'ab_stiftg_datum', '', '', "type='date'");

Edit_Select_Feld(Prefix . 'ab_stifter', VF_Stifter, '');

# =========================================================================================================
Edit_Separator_Zeile('Statut (Gesetz), Deschreibung');
# =========================================================================================================
echo "<input type='hidden' name='ab_statut' value='" . $neu['ab_statut'] . "'>";
echo "<input type='hidden' name='ab_erklaerung' value='" . $neu['ab_erklaerung'] . "'>";

# $pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";
$pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/Stat/";

$Feldlaenge = "100px";
Edit_Show_Pict(Prefix . 'ab_statut', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
   # if (empty($neu['fo_gde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}

Edit_Upload_File('ab_statut', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '1' # Identifier bei mehreren uploads
);
Edit_Show_Pict(Prefix . 'ab_erklaerung', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Feldlaenge = '300px' # Feld Länge für Bildbreite
); # Attribut/Parameter
Edit_Upload_File('ab_erklaerung', # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
$Ident = '2' # Identifier bei mehreren uploads
);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'ab_aend_uid');
Edit_Daten_Feld(Prefix . 'ab_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

# print_r($_SESSION[$module]);
if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
echo "<a href='VF_PS_OV_O_Edit.php' >zurück zur Liste</a>";

echo "<div class='w3-container'><fieldset> <label> Auszeichnungs- Details: </label><br/>";
require 'VF_PS_OV_AE_List.inc';
echo "</fieldset></div>";

// Auszeichnungs - Details

echo "<p><a href='VFH_PS_OV_O_Edit.php?fw_id=" . $_SESSION[$module]['fw_id'] . ">Zurück zur Liste</a></p>";

# =========================================================================================================
# =========================================================================================================
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2VF, $T_List, $module, $pict_path, $proj;
    # echo "L 86: \$tabelle $tabelle <br/>";

    if ($tabelle == "az_auszeich") {} elseif ($tabelle == "az_ausz_ctif") {
        $pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
        $ac_id = $row['ac_id'];
        $row['ac_id'] = "<a href='VF_PS_OV_AZ_CT_Edit.php?ID=$ac_id' >" . $ac_id . "</a>";

        if ($row['ac_wettbsp_v'] != "") {
            # $az_wettbsp_v = $row['ac_wettbsp_v'];
            $p1 = $pict_path . $row['ac_wettbsp_v'];
            $row['ac_wettbsp_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }

        if ($row['ac_gr_med_go_v'] != "") {
            # $az_wettbsp_r = $row['ac_wettbsp_r'];
            $p1 = $pict_path . $row['ac_gr_med_go_v'];
            $row['ac_gr_med_go_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['ac_kl_med_go_v'] != "") {
            # $az_wettbsp_r = $row['ac_wettbsp_r'];
            $p1 = $pict_path . $row['ac_kl_med_go_v'];
            $row['ac_kl_med_go_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['ac_so_med_go_v'] != "") {
            # $az_wettbsp_r = $row['ac_wettbsp_r'];
            $p1 = $pict_path . $row['ac_so_med_go_v'];
            $row['ac_so_med_go_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['ac_fabz_v'] != "") {
            # $az_wettbsp_r = $row['ac_wettbsp_r'];
            $p1 = $pict_path . $row['ac_fabz_v'];
            $row['ac_fabz_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['ac_teiln_v'] != "") {
            # $az_wettbsp_r = $row['ac_wettbsp_r'];
            $p1 = $pict_path . $row['ac_teiln_v'];
            $row['ac_teiln_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
    } elseif ($tabelle == "az_ausz_ve") {
        $pict_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
        $av_id = $row['av_id'];
        $row['av_id'] = "<a href='VF_PS_OV_AZ_VE_Edit.php?ID=$av_id' >" . $av_id . "</a>";
        if ($row['av_bild_v'] != "") {
            $p1 = $pict_path . $row['av_bild_v'];
            $row['av_bild_v'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['av_bild_r'] != "") {
            $p1 = $pict_path . $row['av_bild_r'];
            $row['av_bild_r'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['av_urkund_1'] != "") {
            $p1 = $pict_path . $row['av_urkund_1'];
            $row['av_urkund_1'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
        if ($row['av_urkund_2'] != "") {
            $p1 = $pict_path . $row['av_urkund_2'];
            $row['av_urkund_2'] = "<a href='$p1' target='Auszeichng' > <img src='$p1' alter='$p1' width='70px'>  Groß </a>";
        }
    } elseif ($tabelle == "az_adetail") {
        $ad_id = $row['ad_id'];
        $row['ad_id'] = "<a href='VF_PS_OV_AE_Edit.php?ID=$ad_id' >" . $ad_id . "</a>";

        $stat_path = "Beschreibungen/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/stat/";
        if ($row['ad_statut'] != "") {
            $ad_statut = $row['ad_statut'];
            $row['ad_statut'] = "<a href='$stat_path$ad_statut' target='_new' >$ad_statut</a>";
        }
        if ($row['ad_erklaer'] != "") {
            $ad_erklaer = $row['ad_erklaer'];
            $row['ad_erklaer'] = "<a href='$stat_path$ad_erklaer' target='_new' >$ad_erklaer</a>";
        }
    }

    return True;  
} # Ende von Function modifyRow

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Edit_ph0.php beendet</pre>";
}
?>