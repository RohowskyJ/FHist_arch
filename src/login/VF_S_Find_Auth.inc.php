<?php
/**
 * Suchen in Zeitungsregister nach Feuerwehrnamen
 *  *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_S_Find_FNam.inc.php  ist gestarted</pre>";
}

$sql_zt = "SELECT * FROM zt_zeitungen ";
$return_zt = SQL_QUERY($db, $sql_zt);
while ($row = mysqli_fetch_object($return_zt)) {
    $zt_index[$row->zt_id] = $row->zt_name;
}

foreach ($zt_arr as $file=>$v) {
    if ($file == "zt_inhalt") {
        continue;
    }
    $sql = "SELECT ih_id,ih_autor FROM $file WHERE ih_autor LIKE '%$s_suchztauth%' "; 
    $return = SQL_QUERY($db,$sql);
    while ($row = mysqli_fetch_object($return)) {
        if (empty($zeitg_liste)) {
            $auth_liste = "$file|$row->ih_id";
        } else {
            $auth_liste .= ",$file|$row->ih_id";
        }
    }
    

}


# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_FNam.inc.php beendet</pre>";
}
?>