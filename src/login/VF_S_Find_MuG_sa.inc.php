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
if ($debug) {
    echo "<pre class=debug>VF_S_Find_MuG_sa.inc.php ist gestarted</pre>";
}

$select = "WHERE mg_sammlg LIKE '".$neu['s_suchtext']."%' ";

#$table = "mg_raet_$eignr";
foreach ($mug_arr as $mg_table => $mg_nix) {
    if ($mg_table == "mu_geraet_") {
        continue;
    }

    $sql_mg = "SELECT * FROM `$mg_table` $select ORDER BY mg_sammlg";
    $return_mg = mysqli_query($db, $sql_mg);

    while ($row = mysqli_fetch_object($return_mg)) {
        if (empty($ger_liste)) {
            $mug_liste = "$mg_table|$row->mg_id|$row->mg_eignr";
        } else {
            $mug_liste .= ",$mg_table|$row->mg_id|$row->mg_eignr";
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MuG_sa.inc.php beendet.php</pre>";
}
?>