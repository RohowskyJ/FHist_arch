<?php

/**
 * Suchen in Inventar nach Sammlung
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_Inv_sa.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Inv_sa.inc.php ist gestarted</pre>";
}

if (substr($neu['s_suchtext'], 1, 1) == "0") {
    $such = substr($neu['s_suchtext'], 0, 1) . "Z";
    $select = " WHERE in_sammlg > LIKE'".$neu['s_suchtext']."%' AND in_sammlg<= '$such' ";
} else {
    $select = " WHERE in_sammlg LIKE '".$neu['s_suchtext']."%' ";
}

foreach ($in_arr as $in_table=>$aa) {
    if ($in_table == "in_vent_n" or $in_table == "in_details" or substr($in_table, 0, 11) == "in_vent_ver") {
        continue;
    }
    $sql_in = "SELECT * FROM $in_table $select ORDER BY in_sammlg";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU Inv sa  $sql_inu </pre>";
    echo "</div>";
    
    $return_in = mysqli_query($db, $sql_in);
    # print_r($return_in);echo "<br> return_in sql_in $sql_in <br>";
    # echo "sql $sql_in <br>";
    while ($row = mysqli_fetch_object($return_in)) {
        if (empty($inv_liste)) {
            $inv_liste = "$in_table|$row->in_id|$eignr";
        } else {
            $inv_liste .= ",$in_table|$row->in_id|$eignr";
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Inv_sa.inc beendet</pre>";
}
?>
