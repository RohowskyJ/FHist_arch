<?php

/**
 * Auszeichnungs- Listen, Vereins- Auszeichnungen
 *
 * @author Josef Rohowsky - neu 2019
 *
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_VEREINE.inc.php ist gestarted<br> L 011 </pre>";
}

$proj = $_SESSION[$module]['proj'];
$pict_path = $pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";

$stat_path = $pict_path . "Stat/";

while ($row = mysqli_fetch_array($return)) {
    if ($debug) {
        echo "<pre class=debug>VF_4_OV_AD_Z_List_VEREINE.inc ist gestarted<br> L 016 \$tab_a_ausz $tab_a_ausz </pre>";
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

    $sql_ausz = "SELECT * FROM $tab_a_ausz WHERE av_ab_id='$ab_id' ORDER BY av_sort ASC";

    $return_ausz = SQL_QUERY($db, $sql_ausz);

    # print_r($return_ausz);echo "<br>L 0039 \$sql_ausz $sql_ausz <br>";

    while ($row_ausz = mysqli_fetch_assoc($return_ausz)) {
        if ($row_ausz['av_mat'] != "" || $row_ausz['av_beschr'] != "") {
            echo "<tr><td colspan='3'>" . $row_ausz['av_mat'] . "</td><td colspan='8'> <b>" . $row_ausz['av_beschr'] . "<b/></td></tr>";
        }

        $a_bld_1 = $a_bld_2 = $a_bld_3 = $a_bld_4 = $a_bld_5 = $a_bld_6 = "&nbsp;";
        if ($row_ausz['av_bild_v'] != "") {
            $a_bld_1 = "<img src='$pict_path" . $row_ausz['av_bild_v'] . "' align='left'>";
        }

        if ($row_ausz['av_bild_r'] != "") {
            $a_bld_2 = "<img src='$pict_path" . $row_ausz['av_bild_r'] . "' align='left'>";
        }
        echo "<tr><td colspan='5'>$a_bld_1 " . strtr($row_ausz['av_bild_v'], '_', ' ') . "</td><td>&nbsp;</td><td colspan='5'>$a_bld_2 " . strtr($row_ausz['av_bild_r'], '_', ' ') . "</td></tr>";

    }

    echo "</tbody></table>";
    echo "</div>";
}
if ($debug) {
    echo "<pre class=debug>VF_4_OV_AD_Z_List_VEREINE.inc it beendet</pre>";
}
?>