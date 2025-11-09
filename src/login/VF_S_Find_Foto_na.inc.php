<?php

/**
 * Suchen in Fotos nach Namen
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_Foto_sa.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Foto_na.inc ist gestarted</pre>";
}

$sql_na = "SELECT * FROM `fh_find_namen` WHERE na_table LIKE 'dm_edi%' ";

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU Foto na  $sql_mu </pre>";
echo "</div>";

if ($_SESSION[$module]['su_Eig'] == "A") {
    $eignr = "0";
} else {
    $eignr = $_SESSION[$module]['su_Eig'];
}
$return_na = SQL_QUERY($db, $sql_na) or die("Datenbankabfrage gescheitert. \$sql_na $sql_na " . mysqli_error($db));
while ($row = mysqli_fetch_object($return_na)) {
    $found = FALSE;

    if ($eignr != "0") {
        $dat_arr = explode("_", $row->na_table);
        if ($dat_arr[2] == $eignr) {} else {
            continue;
        }
    }
  
    if (substr($row->na_table, 0, 7) == "fo_toda") {
        $s_usuchname = strtoupper($s_suchname);
        $s_unaname = strtoupper($row->na_name);
        if (strstr($s_unaname, $s_usuchname)) {
            if (empty($foto_liste)) {            
                $foto_liste = "$row->na_table|$row->na_fdid";
            } else {
                $foto_liste .= ",$row->na_table|$row->na_fdid";
            }
        }
    }
}
mysqli_free_result($return_na);

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Foto_na.inc beendet</pre>";
}
?>