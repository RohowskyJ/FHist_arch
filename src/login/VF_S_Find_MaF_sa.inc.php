<?php

/**
 * Suchen in Automobilen nach Sammlung
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_MaF_sa.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MaF_sa.inc.php ist gestarted</pre>";
}

$select = "";

$select = "WHERE fz_sammlg LIKE '".$neu['s_suchtext']."%' ";
#var_dump($maf_arr);
foreach ($maf_arr as  $fz_table => $mist) {
    if ($fz_table == "ma_fz_beschr_" || $fz_table == "ma_fz_besch_tabs") {
        continue;
    }
#echo "L 025 fz_table $fz_table <br>";
    $sql_fz = "SELECT * FROM `$fz_table` $select ORDER BY fz_sammlg";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU MaF sa  $sql_fz </pre>";
    echo "</div>";
    
    $return_fz = mysqli_query($db, $sql_fz);

    while ($row = mysqli_fetch_object($return_fz)) {

        if (empty($fzg_2_liste)) {
            $fzg_2_liste = "$fz_table|$row->fz_id|$row->fz_eignr";
        } else {
            $fzg_2_liste .= ",$fz_table|$row->fz_id|$row->fz_eignr";
           
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_VF_S_Find_MaF_sa.inc.php beendet</pre>";
}
?>