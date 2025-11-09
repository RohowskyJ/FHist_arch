<?php

/**
 * Suchen in Geräten nach Sammlung
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_MuF_sa.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MuF_sa.inc.php ist gestarted</pre>";
}

$select = "";

$select = "WHERE fm_sammlg LIKE '".$neu['s_suchtext']."%' ";

foreach ($muf_arr as $fm_table => $aa) {
    if ($fm_table == "mu_fahrzeug_") {
        continue;
    }

    $sql_mu = "SELECT * FROM $fm_table $select ORDER BY fm_sammlg";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU MuF sa  $sql_mu </pre>";
    echo "</div>";
    
    $return_mu = mysqli_query($db, $sql_mu);
    
    while ($row = mysqli_fetch_object($return_mu)) {
        if (empty($fzg_1_liste)) {
            $fzg_1_liste = "$fm_table|$row->fm_id|$row->fm_eignr";
        } else {
            $fzg_1_liste .= ",$fm_table|$row->fm_id|$row->fm_eignr";
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_VF_S_Find_MuF_sa.inc.php beendet</pre>";
}
?>  
 