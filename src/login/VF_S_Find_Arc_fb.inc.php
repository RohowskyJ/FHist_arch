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
if ($debug) {
    echo "<pre class=debug>VF_S_Find_Arc_fb.inc.php ist gestarted</pre>";
}

$suchbegr = $s_suchtext;

$sql_in = "SELECT * FROM  `fh_findbuch`";
$return_in = SQL_QUERY($db, $sql_in);
while ($row = mysqli_fetch_object($return_in)) {
    $s_usuchname = strtoupper($suchbegr);
    $s_unaname = strtoupper($row->fi_suchbegr);
    if (strstr($s_unaname, $s_usuchname)) {
        if ($arc_liste == "") {
            $arc_liste = "$row->fi_table|$row->fi_fdid";
        } else {
            $arc_liste .= ",$row->fi_table|$row->fi_fdid";
        }
    }
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Arc_fb.inc.php beendet</pre>";
}
?>