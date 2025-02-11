<?php

/**
 * Auszeichnungs- Listen, CTIF AUszeichnungn
 *
 * @author Josef Rohowsky - neu 2019
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_CTIF.inc ist gestarted</pre>";
}

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_CTIF.inc ist gestarted<br> L 011 </pre>";
}

$proj = $_SESSION[$module]['proj'];
$pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";

$stat_path = $pict_path . "Stat/";

while ($row = mysqli_fetch_array($return)) {
    if ($debug) {
        echo "<pre class=debug>VF_PS_OV_AD_Z_List_CTIF.inc.php ist gestarted<br> L 016 \$tab_a_ausz $tab_a_ausz </pre>";
    }

    $ab_id = $row['ab_id'];
    echo "<div class=' w3-table-all border:1;'>";
    echo "<table><tbody>";
    echo "<tr><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td></td></tr>";
    echo "<tr><th colspan='11'>$l_tit</th></tr>";

    echo "<tr><th colspan='8'>" . $row['ab_beschreibg'] . "</th><th></th><th colspan='2'>Gestiftet: " . $row['ab_stiftg_datum'] . "</th></tr>";
    if ($row['ab_statut'] != "") {
        echo "<tr><th colspan='5'><a href='$stat_path" . $row['ab_statut'] . "' target='statut'>Statut</a></th>";
    }
    if ($row['ab_erklaerung'] != "") {
        echo "<th colspan='2'><a href='$stat_path" . $row['ab_erklaerung'] . "' target='statut'>Erklärung</a></th>";
    }
    echo "</tr>";

    $sql_ausz = "SELECT * FROM $tab_a_ausz WHERE ac_ab_id='$ab_id' ORDER BY ac_stufe ASC";

    $return_ausz = mysqli_query($db, $sql_ausz);

    while ($row_ausz = mysqli_fetch_assoc($return_ausz)) {
        if ($row_ausz['ac_mat'] != "" || $row_ausz['ac_beschr'] != "") {
            echo "<tr><td colspan='3'>" . $row_ausz['ac_mat'] . "</td><td colspan='8'> <b>" . $row_ausz['ac_beschr'] . "<b/></td></tr>";
        }

        $a_bld_1 = $a_bld_2 = $a_bld_3 = $a_bld_4 = $a_bld_5 = $a_bld_6 = "&nbsp;";
        if ($row_ausz['ac_wettbsp_v'] != "") {
            $a_bld_1 = "<img src='$pict_path" . $row_ausz['ac_wettbsp_v'] . "' align='left'>";
        }

        if ($row_ausz['ac_gr_med_go_v'] != "") {
            $a_bld_2 = "<img src='$pict_path" . $row_ausz['ac_gr_med_go_v'] . "' align='left'>";
        }
        echo "<tr><td colspan='5'>$a_bld_1 " . strtr($row_ausz['ac_wettbsp_v'], '_', ' ') . "</td><td>&nbsp;</td><td colspan='5'>$a_bld_2 " . strtr($row_ausz['ac_gr_med_go_v'], '_', ' ') . "</td></tr>";

        if ($row_ausz['ac_kl_med_go_v'] != "") {
            $a_bld_3 = "<img src='$pict_path" . $row_ausz['ac_kl_med_go_v'] . "' align='left'>";
        }
        if ($row_ausz['ac_so_med_go_v'] != "") {
            $a_bld_4 = "<img src='$pict_path" . $row_ausz['ac_so_med_go_v'] . "' align='left'>";
        }

        echo "<tr><td colspan='5'>$a_bld_3 " . strtr($row_ausz['ac_kl_med_go_v'], '_', ' ') . "</td><td>&nbsp;</td><td colspan='5'>$a_bld_4 " . strtr($row_ausz['ac_so_med_go_v'], '_', ' ') . "</td></tr>";

        if ($row_ausz['ac_teiln_v'] != "") {
            $a_bld_5 = "<img src='$pict_path" . $row_ausz['ac_teiln_v'] . "' align='left'>";
        }
        if ($row_ausz['ac_fabz_v'] != "") {
            $a_bld_6 = "<img src='$pict_path" . $row_ausz['ac_so_med_go_v'] . "' align='left'>";
        }
        echo "<tr><td colspan='5'>$a_bld_5 " . strtr($row_ausz['ac_teiln_v'], '_', ' ') . "</td><td>&nbsp;</td><td colspan='5'>$a_bld_6 " . strtr($row_ausz['ac_fabz_v'], '_', ' ') . "</td></tr>";
    }

    echo "</tbody></table>";
    echo "</div>";
}
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_CTIF.inc.php ist beendet</pre>";
}
?>