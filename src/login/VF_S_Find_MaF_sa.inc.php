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
if ($debug) {
    echo "<pre class=debug>VF_S_Find_MaF_sa.inc.php ist gestarted</pre>";
}

$select = "";

$select = "WHERE fz_sammlg LIKE '".$neu['s_suchtext']."%' ";

foreach ($maf_arr as  $fz_table => $mist) {
    if ($fz_table == "ma_fz_beschr_") {
        continue;
    }

    $sql_fz = "SELECT * FROM `$fz_table` $select ORDER BY fz_sammlg";
    
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