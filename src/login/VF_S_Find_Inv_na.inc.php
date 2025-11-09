<?php

/**
 * Suchen in Inventar nach Namen
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_Inv_na.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Inv_na.inc.php ist gestarted</pre>";
}

$sql_na = "SELECT * FROM `fh_find_namen` WHERE na_table LIKE 'in_Ven%' ";

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU Inv na  $sql_mu </pre>";
echo "</div>";

$return_na = SQL_QUERY($db, $sql_na) ;
while ($row = mysqli_fetch_object($return_na)) {
    $found = FALSE;
    foreach ($eig_arr as $eignr) {
        if ($row->na_eigner == $eignr) {
            $found = TRUE;
            break;
        }
    }
    if (substr($row->na_table, 0, 7) == "in_vent") {
        $s_usuchname = strtoupper($s_suchname);
        $s_unaname = strtoupper($row->na_name);
        if (strstr($s_unaname, $s_usuchname)) {
            if (empty($inv_liste)) {
                $inv_liste = "$row->na_table|$row->na_fdid";
            } else {
                $inv_liste .= ",$row->na_table|$row->na_fdid";
            }
        }
    }
}
mysqli_free_result($return_na);

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>>VF_S_Find_Inv_na.inc.php beendet</pre>";
}
?>