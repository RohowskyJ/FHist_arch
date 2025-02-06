<?php 

/**
 * Suchen in Archivalien nach Findbuch
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_S_Find_Arc_na.inc.php ist gestarted</pre>";}

$sql_na = "SELECT * FROM `fh_find_namen` WHERE na_table LIKE 'ar_ch%' ";
$return_na = SQL_QUERY($db,$sql_na);
while ($row   = mysqli_fetch_object($return_na)) {
    $found = FALSE;
    foreach ($eig_arr as $eignr) {
        if ($row->na_eigner == $eignr) {
            $found = TRUE;
            break;
        }
    }

    if (substr($row->na_table,0,7) == "ar_chiv") {
        $s_usuchname = strtoupper($s_suchname);
        $s_unaname   = strtoupper($row->na_name);
        if (strstr( $s_unaname, $s_usuchname) ) {
            if (empty($arc_liste)) {
                $arc_liste = "$row->na_table|$row->na_fdid";
            } else {
                $arc_liste .= ",$row->na_table|$row->na_fdid";
            }
        }
    }
}
mysqli_free_result($return_na);


# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_S_Find_Arc_na.inc.php beendet</pre>";}
?>