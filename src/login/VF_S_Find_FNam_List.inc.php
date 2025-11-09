<?php

/**
 * Liste der Feuerwehrnamen in Zeitungen
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_FNam_List.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_S_Find_FNam_List.inc.php ist gestarted</pre>";
}

echo "<div class='w3-content'><fieldset>";
echo "<h2>Zeitungen</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo "<table width=\"100%\" border=\"1\" summary=\"Feuerwehrnamensliste\"><br/>";
echo "<th colspan=\"7\">Verein der Feuerwehrhistoriker in NÖ,<br> Feuerwehrnamen in Zeitungen <cite>$s_suchffname</cite></th></tr>";
echo '<tr><th>Item</th><th>Titel(n)</th><th>Jahrg.-Jahr-Nr.</th><th>Zeitung</th><th>Feuerwehrname</th></tr>';

$arr_zeitg = explode(',', $zeitg_liste);
for ($i = 0; ! empty($arr_zeitg[$i]); $i ++) {
    $rec_arr = explode("|", $arr_zeitg[$i]);
    $select_f = "WHERE `ih_id`='$rec_arr[1]' ";
    $sql = "SELECT * FROM `$rec_arr[0]` $select_f  ORDER BY `ih_id` ASC";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU FNam List sql </pre>";
    echo "</div>";
    
    $return_fo = SQL_QUERY($db, $sql);
    while ($row = mysqli_fetch_object($return_fo)) {
        $zt_titel = $zt_index[$row->ih_zt_id];
        echo "<tr><td>$row->ih_id</td><td>$row->ih_titel</td><td>$row->ih_jahrgang-$row->ih_jahr-$row->ih_nr</td><td>$zt_titel</td><td>$row->ih_fwehr</td></tr>";
    }
}
echo "</table><br/>";

echo "</fieldset></div></div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_FNam_List.inc beendet</pre>";
}
?>