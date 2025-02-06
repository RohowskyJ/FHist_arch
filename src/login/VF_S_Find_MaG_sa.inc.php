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
    echo "<pre class=debug>VF_S_Find_MaG_sa.inc.php ist gestarted</pre>";
}

$select = "WHERE ge_sammlg LIKE '".$neu['s_suchtext']."%' ";

#$table = "ge_raet_$eignr";
foreach ($mag_arr as $ge_table => $ge_nix) {
    if ($ge_table == "ma_geraet_") {
        continue;
    }

    $sql_ge = "SELECT * FROM `$ge_table` $select ORDER BY ge_sammlg";
    $return_ge = mysqli_query($db, $sql_ge);

    while ($row = mysqli_fetch_object($return_ge)) {
        if (empty($ger_liste)) {
            $ger_liste = "$ge_table|$row->ge_id|$row->ge_eignr";
        } else {
            $ger_liste .= ",$ge_table|$row->ge_id|$row->ge_eignr";
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MaG_sa.inc beendet.php</pre>";
}
?>