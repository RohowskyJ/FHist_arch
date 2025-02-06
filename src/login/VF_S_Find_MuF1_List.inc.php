<?php

/**
 * Liste der gefundenen Sachen bei Muskel- Fahrzeugen und Geräten
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_S_Find_MuF1_List.inc.php ist gestarted</pre>";
}

echo "<div class='w3-content W3-tiny'><fieldset>";
echo "<h2>Fahrzeuge (Muskelgezogen)</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo "<table border='1' summary='Muskelgezogenes'><br/>";
echo "<th colspan='4'>Verein der Feuerwehrhistoriker in NÖ,<br> Beschreibungen Muskelgezogenes $s_titel</th></tr>";
echo '<tr><th>Eigent.<hr/>Item</th><th>Fahrzeugbeschreibung</th><th>bild</th><th>Sammlung</th></tr>';

$arr_fzg = explode(',', $fzg_1_liste);

for ($i = 0; ! empty($arr_fzg[$i]); $i ++) {
    $fz_arr = explode("|", $arr_fzg[$i]);
    $tabelle = $fz_arr[0];
    $eignr = $fz_arr[2];

    $sec_string = "";
    VF_Displ_Eig($eignr);
    $eig_name = $_SESSION['Eigner']['eig_name'];
    $eig_verant = $_SESSION['Eigner']['eig_verant'];

    $select_f = "WHERE `fm_id`='".$fz_arr[1]."' ";
    $sql = "SELECT * FROM ".$fz_arr[0]." $select_f  ORDER BY `fm_id` ASC";

    $return_fz = SQL_QUERY($db, $sql);
    while ($row = mysqli_fetch_object($return_fz)) {

        $pict = "";
        if ($row->fm_foto_1 != "") {
            $pictpath = "AOrd_Verz/$eignr/MuF/";
            $p1 = $pictpath . $row->fm_foto_1;
            $pict = "<a href='$p1' target='Fahrzeug-Bild' > <img src='$p1' alter='$p1' width='150px'> <br/>$row->fm_foto_1   </a>";
        }

      #   $o_suchb1 = $o_suchb2 = $o_suchb3 = $o_suchb4 = $o_suchb5 = $o_suchb6 = "&nbsp;";
      #   VF_Displ_Suchb($row->fm_suchbegr_1, $row->fm_suchbegr_2, $row->fm_suchbegr_3, $row->fm_suchbegr_4, $row->fm_suchbegr_5, $row->fm_suchbegr_6);
        echo "<tr><td width='20%'>$eig_name<hr/>$row->fm_eignr<hr/><input name='item\" value='fzg_1|$row->fm_id|$row->fm_eignr' type='radio' onClick=submit()> $row->fm_id</td><td>$row->fm_indienst $row->fm_ausdienst $row->fm_type - $row->fm_indienst $row->fm_ausdienst<hr>$row->fm_herst</td><td>$pict</td><td>$row->fm_sammlg</td></tr>";
    }
}
echo "</table><br/>";

echo "</fieldset></div></div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MuF1_List.inc.php beendet</pre>";
}
?>