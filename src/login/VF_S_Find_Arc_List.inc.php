<?php 

/**
 * Liste der gefunden in Archivalien nach Findbuch
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_S_Find_Arc_List.inc ist gestarted</pre>";}

echo "<div class='w3-content'><fieldset>";
echo "<h2>Archivalien</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo '<table summary="Archivalien- Daten" border="1" width="98%">  ';
echo '<tbody><tr>  ';
echo "<th colspan=\"7\">Verein der Feuerwehrhistoriker in NÖ,<br> Archivaliendaten $s_titel</th></tr>";
echo "<tr><th width=\"15%\">Eigent.<hr/>Archiv-Nr.</th><th>Typ/Format</th><th>Beschreibung</th><th>Suchbegriffe<hr width=\"90%\">Namen</th><th></th></tr>";
$select = "";
$arr_arc = explode(',',$arc_liste);
#var_dump($arr_arc);
for ($i=0;!empty($arr_arc[$i]);$i++) {
    
    $it_arr = explode("|",$arr_arc[$i]);
    $eig_arr = explode("_",$it_arr[0]);
    if ($it_arr[0] == "ar_ch_verl") {continue;}
    $select = "WHERE `ad_id`='$it_arr[1]'";
    $sql_in = "SELECT * FROM `$it_arr[0]` $select ";
    #echo "L 033 sql_in $sql_in <br>";
    $return_in = SQL_QUERY($db,$sql_in);
    while ($row   = mysqli_fetch_object($return_in)) {
        
        $arctype = VF_Arc_Type[$row->ad_type];
        $arcformat = $row->ad_format;

        # $doc_dir = "referat5/$row->ad_eignr/$row->ad_sg/$row->ad_subsg";
        $doc_dir = "AOrd_Verz/$row->ad_eignr/$row->ad_sg/$row->ad_subsg";
        $doc_1 = $doc_2 = $doc_3 = "";
        if ($row->ad_doc_1 != "") {
            $doc_1 = "<a href='$doc_dir/$row->ad_doc_1' target=_new>$row->ad_doc_1</a>";
        }
        if ($row->ad_doc_2 != "") {
            $doc_2 = "<a href='$doc_dir/$row->ad_doc_2' target=_new>$row->ad_doc_2</a>";
        }
        if ($row->ad_doc_3 != "") {
            $doc_3 = "<a href='$doc_dir/$row->ad_doc_3' target=_new>$row->ad_doc_3</a>";
        }

        $arc_namen  = "";
       
        if (strlen($row->ad_namen) >= 50) {
            $arc_namen = substr($row->ad_namen,0,80)."...";
        }
#         echo "Arc_List L 047 arc_id $arc_id arc_date $arc_date <br>";

       #  VF_Displ_Suchb($row->ad_suchb1,$row->ad_suchb2,$row->ad_suchb3,$row->ad_suchb4,$row->ad_suchb5,$row->ad_suchb6);
        
        echo "<tr><td><input name='item' value='arc|$row->ad_id|$eig_arr[2]' type='radio'>$row->ad_eignr:$row->ad_sg.$row->ad_subsg.$row->ad_lcsg.$row->ad_lcssg-$row->ad_ao_fortlnr</td><td align='center'>$arctype<br/>$row->ad_format</td><td>$row->ad_beschreibg $doc_1 $doc_2 $doc_3</td><td>$row->ad_keywords<hr width=\"90%\">$arc_namen</td><td></td></tr>";
    }
}
echo "</table>";

echo "</fieldset></div></div>";


# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_S_Find_Arc_List.inc.php beendet</pre>";}
?>