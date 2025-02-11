<?php

/**
 * Liste der Automobilen Fahrzeuge, Zusatz listen
 * 
 * @author Josef Rohowsky - neu 2019
 * 
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_BA_Lists.inc ist gestarted</pre>";
}
echo "<div class='w3-container'><fieldset>";

echo "<div class='w3-container'><fieldset> <label> Typenschein- Daten: </label><br/>";
require 'VF_FA_BA_TS_List.inc'; // Typenschein
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Fahrzeug Eigentümer: </label><br/>";
require 'VF_FA_BA_TE_List.inc';
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Umtypisierungen: </label><br/>";
require 'VF_FA_BA_UT_List.inc';
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Reparaturen, Umbauten: </label><br/>";
require 'VF_FA_BA_RE_List.inc';
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Geräteräume: </label><br/>";
require 'VF_FA_BA_GR_List.inc';
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Dokumente (Archivalien): </label><br/>";
require 'VF_FA_BA_AR_List.inc';
echo "</fieldset></div>";

echo "<div class='w3-container'><fieldset> <label> Fixe Einbauten: </label><br/>";
require 'VF_FA_BA_EB_List.inc';
echo "</fieldset></div>";

echo "</fieldset></div>";

# echo "wurst";
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2VF, $T_List, $module, $neu;
    # echo "L 86: \$tabelle $tabelle <br/>";
    $tab_abk = substr($tabelle, 0, 8);
    # echo "L 023: \$tab_abk $tab_abk <br/>";
    if ($tab_abk == "fz_fz_ty") {
        # echo "L 56: fz_fz_type \$tabelle $tabelle Sammlung ".$_SESSION[$module]['sammlung']."<br/>";
        $ft_id = $row['ft_id'];

        if  (substr($_SESSION[$module]['sammlung'],0,7) == 'MA_F-AH') {
            $row['ft_id'] = "<a href='VF_2_BH_TS_Edit.php?ID=$ft_id' >" . $ft_id . "</a>";
        } elseif (substr($_SESSION[$module]['sammlung'],0,4) == 'MA_F' ) {
            $row['ft_id'] = "<a href='VF_FA_BA_TS_Edit.php?ID=$ft_id' >" . $ft_id . "</a>";
        }
    } elseif ($tab_abk == "fz_eigne") {
        $fz_eign_id = $row['fz_eign_id'];
        $row['fz_eign_id'] = "<a href='VF_FA_BA_TE_Edit.php?ID=$fz_eign_id' >" . $fz_eign_id . "</a>";
    } elseif ($tab_abk == "fz_lader") {
        $lr_id = $row['lr_id'];
        $row['lr_id'] = "<a href='VF_FA_BA_GR_Edit.php?ID=$lr_id' >" . $lr_id . "</a>";
        $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";
        # $defjahr = date("Y"); // Beitragsjahr, .ist Gegenwärtiges Jahr

        $lr_foto_1 = $row['lr_foto_1'];

        $pict = $pict_path . $lr_foto_1;

        # $row['lr_foto_1'] = "<img src='$pict' alt='Foto Laderaum' width='70em' >";
        $row['lr_foto_1'] = "<a href='$pict' target='_blanc' > <img src='$pict' alter='$pict' width='70px'>  $lr_foto_1  </a>";
    } elseif ($tab_abk == "fz_repar") {
        $fz_rep_id = $row['fz_rep_id'];
        $row['fz_rep_id'] = "<a href='VF_FA_BA_RE_Edit.php?ID=$fz_rep_id' >" . $fz_rep_id . "</a>";
    } elseif ($tab_abk == "fz_typis") {
        $fz_typ_id = $row['fz_typ_id'];
        $row['fz_typ_id'] = "<a href='VF_FA_BA_UT_Edit.php?ID=$fz_typ_id' >" . $fz_typ_id . "</a>";
    } elseif ($tab_abk == "fz_fixei") {
        $fz_einb_id = $row['fz_einb_id'];
        $row['fz_einb_id'] = "<a href='VF_FA_BA_EB_Edit.php?ID=$fz_einb_id' >" . $fz_einb_id . "</a>";
        $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";
        $fz_ger_foto_1 = $row['fz_ger_foto_1'];
        
        $pict = $pict_path . $fz_ger_foto_1;
        
        # $row['lr_foto_1'] = "<img src='$pict' alt='Foto Laderaum' width='70em' >";
        $row['fz_ger_foto_1'] = "<a href='$pict' target='_blanc' > <img src='$pict' alter='$pict' width='70px'>  $fz_ger_foto_1  </a>";
    } elseif ($tab_abk == "ar_chivd") {
        $ad_id = $row['ad_id'];
        $eignr = $row['ad_eignr'];
        # $row['ad_id'] = "<a href='referat5/$eignr/".$row['ad_sg']."/".$row['ad_subsg']."/".$row['ad_doc_1']."' >".$ad_id."</a>" ;

        $ad_doc1 = $row['ad_doc_1'];
        $ad_doc = "<a href='AOrd_Verz/" . $row['ad_eignr'] . "/" . $row['ad_sg'] . "/" . $row['ad_subsg'] . "/" . $row['ad_doc_1'] . "' target=_Archiv>$ad_doc1</a>";
        # print_r($row);
        # echo "<br>ba_ar_list L 083: ad_doc $ad_doc </br>";
        $row['ad_id'] = "<a href='VF_FA_BA_AR_Edit.php?ID=$ad_id' >" . $ad_id . "</a>";
        $row['ad_beschreibg'] = $row['ad_beschreibg'] . "<br/>" . $ad_doc;
    }
    return True;
} # Ende von Function modifyRow

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_Lists.inc beendet</pre>";
}
?>