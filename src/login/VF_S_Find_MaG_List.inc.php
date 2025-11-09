<?php

/**
 * Liste der  in Geräten gefundenen Begriffe
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Find_MaG_List.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_0_SU_Find_Ger_List_v3.inc ist gestarted</pre>";
}

echo "<div class='w3-content'><fieldset>";
echo "<h2>Geräte</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo "<table width=\"100%\" border=\"1\" summary=\"Geraeteliste\"><br/>";
echo "<th colspan=\"7\">Verein der Feuerwehrhistoriker in N&Ouml;,<br> Ger&auml;tebeschreibungen $s_titel</th></tr>";

echo '<tr><th>Eigent.<hr/>Item</th><th>Fahrzeugbeschreibung</th><th>Bild</th><th>Sammlung</th></tr>';
$arr_fzg = explode(',', $ger_liste);
for ($i = 0; ! empty($arr_fzg[$i]); $i ++) {
    $fz_arr = explode("|", $arr_fzg[$i]);
    $eig_arr = explode("_", $fz_arr[0]);
    $eignr = $eig_arr[2];
    $sec_string = "";
    $leihname = "";
    $leihplz = "";
    $leihadr = "";

    
  $tabelle = $fz_arr[0];
  
  $eignr = $fz_arr[2];
  $sec_string = "";
  
    VF_Displ_Eig($eignr);
    
    $eig_name = $_SESSION['Eigner']['eig_name'];
    $eig_verant = $_SESSION['Eigner']['eig_verant'];
    

    $select_f = "WHERE `ge_id`='$fz_arr[1]' ";
    $sql = "SELECT * FROM $tabelle $select_f  ORDER BY `ge_id` ASC";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU MaG List sql </pre>";
    echo "</div>";
    
    $return_fz = SQL_QUERY($db, $sql);
    while ($row = mysqli_fetch_object($return_fz)) {
        
        if ($row->ge_foto_1 != "") {
            $pictpath = "AOrd_Verz/$eignr/MaG/";
            $p1 = $pictpath . $row->ge_foto_1;
            $pict = "<a href='$p1' target='Fahrzeug-Bild' > <img src='$p1' alter='$p1' width='150px'> <br/>$row->ge_foto_1   </a>";
        }
        
        echo "<tr><td width='20%'>$eig_name<hr/><input name='item' value='ger|$row->ge_id|$row->ge_eignr' type=\"radio\" onClick=submit()> $row->ge_id</td><td>$row->ge_bezeich - $row->ge_indienst-$row->ge_ausdienst<hr>$row->ge_herst</td><td>$pict</td><td>$row->ge_sammlg</td><td>$row->ge_eignr</td></tr>";
    }
}
echo "</table><br/>";

echo "</fieldset></div></div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_0_SU_Find_Ger_List_v3.inc beendet</pre>";
}
?>