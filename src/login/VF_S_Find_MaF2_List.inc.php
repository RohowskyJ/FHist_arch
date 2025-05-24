<?php

/**
 * Liste der  in Motor-Fahrzeugen gefundenen Begriffe
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_S_Find_MaF2_List.inc ist gestarted</pre>";
}

echo "<div class='w3-content'><fieldset>";
echo "<h2>Fahrzeuge (Automobile)</h2>";
echo "<div class='w3-table_all'>";
echo "<style>td,th {border: 1px solid black;}</style>";
echo "<table width=\"100%\" border=\"1\" summary=\"Automobilliste\"><br/>";
echo "<th colspan=\"7\">Verein der Feuerwehrhistoriker in NÖ,<br> Fahrzeugbeschreibungen $s_titel</th></tr>";
echo '<tr><th>Eigent.<hr/>Item</th><th>Fahrzeugbeschreibung</th><th>Bild</th><th>Sammlung</th></tr>';
$arr_fzg = explode(',', $fzg_2_liste);
for ($i = 0; ! empty($arr_fzg[$i]); $i ++) { // table|fz_id|eignr
    $fz_arr = explode("|", $arr_fzg[$i]);

    # $eig_arr = explode("_",$fz_arr[0]);
    $tabelle = $fz_arr[0];
    
    $eignr = $fz_arr[2];
    $sec_string = "";

    VF_Displ_Eig($eignr);

    $eig_name = $_SESSION['Eigner']['eig_name'];
    $eig_verant = $_SESSION['Eigner']['eig_verant'];

    $sec_arr = explode("|", $sec_string);

    $select_f = "WHERE `fz_id` LIKE '$fz_arr[1]' ";
    $sql = "SELECT * FROM $tabelle $select_f  ORDER BY `fz_id` ASC";

    $return_fz = SQL_QUERY($db, $sql);
    while ($row = mysqli_fetch_object($return_fz)) {

        $fzgzustand = $row->fz_zustand;
        $zustand = "";
        if ($row->fz_zustand == "ok") {
            $zustand = "<b>keine Beanstandung, alles OK</b><br/>";
        } else if ($row->fz_zustand == "fb") {
            $zustand = "<b>Fahrbereit</b><br/>";
        } else if ($row->fz_zustand == "rp") {
            $zustand = "<b>Reparaturbed&uuml;rftig</b><br/>";
        } else if ($row->fz_zustand == "rs") {
            $zustand .= "<b>Restauration notwendig</b><br/>";
        } else if ($row->fz_zustand == "vk") {
            $zustand = "<b>Abgegeben</b><br/>";
        } else if ($row->fz_zustand == "xx") {
            $zustand = "<b>Verschrottet</b><br/>";
        } else {
            $zustand = "<br/><br/>";
        }

        $pict_path = "AOrd_Verz/$eignr/MaF/";
        $pict = "";
        if ($row->fz_bild_1 != "") {
            
            $fo_arr = explode("-",$row->fz_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >=3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                if ($cnt_fo > 3)  {
                    if (isset($fo_arr[3]))
                        $s_verz = $fo_arr[3]."/";
                }
                $p1 = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$row->fz_bild_1;
                
                if (!is_file($p1)) {
                    $p1 = $pict_path . $row->fz_bild_1;
                }
            } else {
                $p1 = $pict_path . $row->fz_bild_1;
            }
            
            
            
            #$p1 = $pictpath . $row->fz_bild_1;
            $pict = "<a href='$p1' target='Fahrzeug-Bild' > <img src='$p1' alter='$p1' width='180px'> <br/>$row->fz_bild_1   </a>";
        }
        # VF_Displ_Suchb($row->fz_suchbegr1, $row->fz_suchbegr2, $row->fz_suchbegr3, $row->fz_suchbegr4, $row->fz_suchbegr5, $row->fz_suchbegr6);
        echo "<tr><td width='20%'>$eig_name<hr/><input name='item' value='fzg_2|$row->fz_id|$row->fz_eignr' type=\"radio\" onClick=submit()> $row->fz_id</td><td>$row->fz_taktbez - $row->fz_indienstst<br/>$zustand<hr/>$row->fz_herstell_fg </td><td>$pict</td><td>$row->fz_sammlg</td></tr>";
    }
    # }
}
echo "</table><br/>";

echo "</fieldset></div></div>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_Find_MaF2_List.inc beendet</pre>";
}

?>