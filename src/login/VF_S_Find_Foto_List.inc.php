<?php

/**
 * Liste der Fotos, bei denen der Suchbegriff gefunden wurde
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_S_Find_Foto_List.inc ist gestarted</pre>";
}

echo "<div class='w3-content'><fieldset>";
echo "<h2>Fotos</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo "<table                border=\"1\" summary=\"Fotoliste\"><br/>";

echo "<th colspan=\"7\">Verein der Feuerwehrhistoriker in NÖ,<br> Fotobeschreibungen$s_titel</th></tr>";
echo '<tr><th>Eigent.<hr/>Item</th><th>Text/Name(n)/Suchbegriffe</th><th>Namen</th><th>Bild</th><th></th></tr>';

$arr_foto = explode(',', $foto_liste);
for ($i = 0; ! empty($arr_foto[$i]); $i ++) {
    $foto_arr = explode("|", $arr_foto[$i]);

    $table_f = $foto_arr[0];

    $eig_arr = explode("_", $foto_arr[0]);
    $eignr = $eig_arr[2];

    $sec_string = "";
    $leihname = "";
    $leihplz = "";
    $leihadr = "";

    VF_Displ_Eig($eignr);
    $eigent =  $_SESSION['Eigner']['eig_name'];

    $select_f = "WHERE `fo_id`='$foto_arr[1]' ";
    $sql = "SELECT * FROM `$table_f` $select_f  ORDER BY `fo_id` ASC";
    # echo "Find_Foto_sb L 038: \$sql $sql <br>";
    $return_fo = mysqli_query($db, $sql) or die("Datenbankabfrage gescheitert. " . mysql_error($db));
    while ($row = mysqli_fetch_object($return_fo)) {
        $fot_dsn = $row->fo_dsn;
        $fot_id = $row->fo_id;
        $fot_text = $row->fo_begltxt;
        $fot_namen = $row->fo_namen;
        $fot_suchbeg = $row->fo_suchbegr;
        if (strlen($fot_namen) >= 80) {
            $fot_namen = substr($fot_namen, 0, 80) . "...";
        }
       
        if (strlen($fot_dsn) == 0) {
            echo "<tr><td>$eigent<hr><input name=\"item\" value=\"foto|$fot_id|$eignr\" type=\"radio\" onClick=submit()>$fot_id</td><td>$fot_text<hr width=\"90%\">$fot_namen<hr width=\"90%\">$fot_suchbeg</td><td>Verzeichnis</td><td></td><td></td></tr>";
        } else {
            $fo_d_spl = explode("-", $fot_dsn);
            $cnt_f_d = count($fo_d_spl);
            
            $pict_path = "../login/AOrd_Verz/$row->fo_eigner/09/06/";
            
            $d_path = $pict_path . $row->fo_aufn_datum . "/";
            if ($row->fo_aufn_suff != "") {
                $d_path .= $row->fo_aufn_suff . "/";
            }

            $pict = "$d_path/$row->fo_dsn";

            echo "<tr><td>$eigent<hr><input name=\"item\" value=\"foto|$fot_id|$eignr\" type=\"radio\" onClick=submit()>$fot_id</td><td>$fot_text<hr width=\"90%\">$fot_namen<hr width=\"90%\">$fot_suchbeg</td><td></td><td><a href='$pict' target='_blank'><img src='$pict' alt='Bild' height='200' ></a></td><td><br/></td></tr>";
        }
    }
}

echo "</table><br/>";
echo "</fieldset></div></div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_Foto_List.inc beendet</pre>";
}
?>