<?php

/**
 * Auszeichnungs- Listen, Standard Auszeichnungen
 *
 * @author Josef Rohowsky - neu 2019
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_Standard.inc.php ist gestarted</pre>";
}

$proj = $_SESSION[$module]['proj'];

$pict_path = $pict_path = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
$stat_path = $pict_path . "Stat/";
while ($row = mysqli_fetch_array($return)) {

    $ab_id = $row['ab_id'];
    echo "<div class=' w3-table-all border:1;'>";
    echo "<table><tbody>";
    echo "<tr><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td width='9%'></td><td></td></tr>";
    echo "<tr><th colspan='11'>$l_tit</th></tr>";

    echo "<tr><th colspan='8'>" . $row['ab_beschreibg'] . "<br>" . $row['ab_stifter'] . "</th><th></th><th colspan='2'>Gestiftet: " . $row['ab_stiftg_datum'] . "</th></tr>";

    if ($row['ab_statut'] != "") {
        echo "<tr><th colspan='5'><a href='$stat_path" . $row['ab_statut'] . "' target='statut'>Statut</a></th>";
        # $tab_data .= ",5||<a href='$stat_path".$row['ab_statut']."' target='statut'>Statut</a>|h";
    }
    if ($row['ab_erklaerung'] != "") {
        echo "<th colspan='2'><a href='$stat_path" . $row['ab_erklaerung'] . "' target='statut'>Erklärung</a></th>";
    }
    echo "</tr>";
    $sql_desc = "SELECT * FROM $tab_a_desc WHERE ad_ab_id='$ab_id' ";

    $return_desc = mysqli_query($db, $sql_desc);
    if ($return_desc) {
        WHILE ($row_desc = mysqli_fetch_assoc($return_desc)) {
            $ad_id = $row_desc['ad_id'];
            echo "<tr><td colspan='3' style='font-weight:bold;font-style:italic;'>" . $row_desc['ad_name'] . "</td><td colspan='8'>" . $row_desc['ad_detail'] . "</td></tr>";
            echo "<tr><td colspan='8'><b>Band: </b> " . $row_desc['ad_band'] . "</td><td colspan='3'>Abmesssung: " . $row_desc['ad_abmesg'] . "</td></tr>";
            echo "<tr><td colspan='5'>" . $row_desc['ad_vorderseite'] . "</td><td></td><td colspan='5'>" . $row_desc['ad_rueckseite'] . "</td></tr>";

            $sql_ausz = "SELECT * FROM $tab_a_ausz WHERE az_ad_id='$ad_id' ORDER BY az_stufe ASC";

            $return_ausz = mysqli_query($db, $sql_ausz);
            while ($row_ausz = mysqli_fetch_assoc($return_ausz)) {
                $pic1 = $pic2 = $pic3 = $pic4 = $pic5 = $pic6 = "";
                if ($row_ausz['az_mat'] != "" || $row_ausz['az_beschr'] != "") {
                    echo "<tr><td colspan='3'>" . $row_ausz['az_mat'] . "</td><td colspan='8'>" . $row_ausz['az_beschr'] . "</td></tr>";
                }

                if ($row_ausz['az_bild_v'] != "") {
                    $pic1 = $pict_path . $row_ausz['az_bild_v'];
                    $Lpic = "<img src='$pic1' align='left'>" . strtr($row_ausz['az_bild_v'], '_', ' ') . "";
                } else {
                    $Lpic = "";
                }
                if ($row_ausz['az_bild_r'] != "") {
                    $pic2 = $pict_path . $row_ausz['az_bild_r'];
                    $Rpic = "<img src='$pic2' align='left'>" . strtr($row_ausz['az_bild_r'], '_', ' ') . "";
                } else {
                    $Rpic = "";
                }
                echo "<tr><td colspan='5'>$Lpic</td><td></td><td colspan='5'>$Rpic</td></tr>";

                if ($row_ausz['az_bild_m'] != "") {
                    $pic3 = $pict_path . $row_ausz['az_bild_m'];
                    $Lpic = "<img src='$pic3' align='left'>" . strtr($row_ausz['az_bild_m'], '_', ' ') . "";
                } else {
                    $Lpic = "";
                }
                if ($row_ausz['az_bild_m_r'] != "") {
                    $pic4 = $pict_path . $row_ausz['az_bild_m_r'];
                    $Rpic = "<img src='$pic4' align='left'>" . strtr($row_ausz['az_bild_m_r'], '_', ' ') . "";
                } else {
                    $Rpic = "";
                }
                echo "<tr><td colspan='5'>$Lpic</td><td></td><td colspan='5'>$Rpic</td></tr>";

                if ($row_ausz['az_bild_klsp'] != "") {
                    $pic5 = $pict_path . $row_ausz['az_bild_klsp'];
                    $Lpic = "<img src='$pic5' align='left'>" . strtr($row_ausz['az_bild_klsp'], '_', ' ') . "";
                } else {
                    $Lpic = "";
                }
                echo "<tr><td colspan='5'>$Lpic</td><td></td><td colspan='5'></td></tr>";
            }
        }
    }

    echo "</tbody></table>";
    echo "</div>";
}

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AD_Z_List_Standard.inc.php ist beendet</pre>";
}
?>