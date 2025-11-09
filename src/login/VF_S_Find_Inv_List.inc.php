<?php

/**
 * Liste der gefundenen  Begrffe im Inventar
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
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Inv_List.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_0_SU_Find_Inv_List_v3.inc ist gestarted</pre>";
}

?>
<style>
td, th {solid #999;
	padding: 0, 5rem;
	border: 1px
}
</style>
<?php

echo "<div class='w3-content'><fieldset>";
echo "<h2>Museale Gegenstände</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo '<table summary="Inventardaten"  width="98%" >  ';
echo '<thead><tr>  ';
echo "<th colspan='8'>Verein der Feuerwehrhistoriker in NÖ,<br>Inventardaten $s_titel</th></tr>";
# if ($detail == "J") {
# echo "<tr><th width=\"15%\">Inv.Nr.</th><th>Referat</th><th>Epoc</th><th>Sb1</th><th>Sb2</th><th>Sb3</th><th>HLd</th><th>HLdA1</th><th>HLdA2</th><th>HLdA3</th><th>NLd</th><th>Beschreibung</th><th width=\"8%\">Ausst.raum</th><th width=\"10%\">Platz</th><th>Namen</th><th>Suchbegriff 1-6</th><th></th></tr>";
# } else {
echo "<tr><th width='15%'>Eigent.<hr/>Inv.Nr.</th><th>Beschreibung</th><th width='8%'>Ausst.raum</th><th width='10%'>Platz</th><th>Namen</th><th>Bild</th><th>Sammlung</th></tr>";
echo "</head><tbody>";

$select = "";

$arr_inv = explode(',', $inv_liste);

foreach ($arr_inv as $value) {
    $inv_arr = explode("|", $value); // $arr_inv[$i]);
    $tabelle = $inv_arr[0];

    $narr = (explode("_", $tabelle));
    $nacnt = count($narr) - 1;

    $eignr_t = $narr[$nacnt];

    $select = "WHERE `in_id`='$inv_arr[1]'";
    $sec_string = "";

    VF_Displ_Eig($eignr_t);

    $eig_name = $_SESSION['Eigner']['eig_name'];
    $eig_verant = $_SESSION['Eigner']['eig_verant'];

    $sql_in = "SELECT * FROM $tabelle $select ";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU Inv List sql_in </pre>";
    echo "</div>";
    
    $return_in = SQL_QUERY($db, $sql_in);

    while ($row = mysqli_fetch_object($return_in)) {

        $pict = $p1 = "";
        if ($row->in_foto_1 != "") {
            $pictpath = "AOrd_Verz/$eignr_t/INV/";
            $p1 = $pictpath . $row->in_foto_1;
            $pict = "<a href='$p1' target='Inventar-Bild' > <img src='$p1' alter='Bild' width='70px'> <br/>$row->in_foto_1   </a>";
        }
        echo "<tr><td width='20%'>$eig_name <hr>$eignr_t<hr/><input name='item' value='inv|$row->in_id|$eignr' type='radio' onClick=submit()>$row->in_invjahr $row->in_invnr</td><td>$row->in_beschreibg</td><td>$row->in_raum</td><td>$row->in_platz</td><td>$row->in_namen</td><td>$p1</td><td>$row->in_sammlg</tr>";
    }
}
echo '</tbody></table><br><br>    ';

echo "</fieldset></div></div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_0_SU_Find_Inv_List_v3.inc  beendet</pre>";
}
?>