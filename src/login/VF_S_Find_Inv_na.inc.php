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
if ($debug) {
    echo "<pre class=debug>VF_S_Find_Inv_na.inc.php ist gestarted</pre>";
}

$sql_na = "SELECT * FROM `fh_find_namen` WHERE na_table LIKE 'in_Ven%' ";
$return_na = mysqli_query($db, $sql_na) or die("Datenbankabfrage gescheitert. \$sql_na $sql_na " . mysqli_error($db));
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