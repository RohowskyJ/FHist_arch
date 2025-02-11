<?php

/**
 * Archivalien zum Fahrzeug suchen
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_BA_AR_Srch_ph0.inc ist gestarted</pre>";
}

$eignr = $_SESSION['Eigner']['eig_eigner'];
$table = "ma_fz_beschr_$eignr";

$select = "WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "'";

$sql_in = "SELECT * FROM `$table`  inner join ar_ord_local ON ar_ord_local.al_sammlung = $table.fz_sammlg  $select ";
echo "L 019 sql $sql_in <br>";
$return_in = SQL_QUERY($db, $sql_in);
$i = 0;
$fzgarr = mysqli_fetch_assoc($return_in);

# var_dump($fzgarr);
mysqli_free_result($return_in);
$titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Automobile (Motorgezogene Fahrzeuge)<br/>Archivalien zum Fahrzeug: </legend>";
echo "<div class='w3-table-all border:4;'>";

echo '<table summary="Fahrzeugdaten"><tbody>';

echo '<tr>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '<td width="10%"></td>';
echo '</tr>';
//
echo '<tr>';
echo '<th colspan="11">';
echo '<b>Eigentümer: </b></br>';

echo "Eigentümernummer: $eignr";
echo '</th></tr>';
echo '<tr>';
echo '<th colspan="11">';
echo 'Taktische Bezeichnung: ';
echo "<font size='+1'>" . $fzgarr['fz_taktbez'] . "</font> ";
if (! empty($fzgarr['fz_name'])) {
    echo '<br/>Name (Patenname): ';
    echo "<font size='+1'>" . $fzgarr['fz_name'] . "</font>";
}
echo '<br/>In- und Ausserdienststellung: ';
echo "<font size='+1'>" . $fzgarr['fz_indienstst'] . " &nbsp; " . $fzgarr['fz_ausdienst'] . "</font>";
echo '</th></tr>';
# echo "fzg_arc_suche L 0072: \$invsb1 $invsb1 \$invsb2 $invsb2 <br/>";

$table_name = "ar_chivdt_$eignr";

#### suche ob ar:chvdt_urh vorhanden N-> Meldung, keine Azege
$ar_arr = array();
VF_tableExist();

$arr_fzg = array();
if(isset($ar_arr[$table_name])) {
    $sg_a = explode(".",$fzgarr['al_sg']);
    
    $select = "WHERE ad_sg='".$sg_a[0]."' AND ad_subsg='".$sg_a[1]."' AND ad_lcsg='".$fzgarr['al_lcsg']."'  "; # AND ad_lcssg='".$fgzarr['al_lcssg']."' 
        
        $j = 0;
        $sql_arch = "SELECT * FROM `$table_name` $select ORDER BY ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_ao_fortlnr ASC";
        #echo "L 079 sql_arch $sql_arch <br>";
        $return_arch = SQL_QUERY($db, $sql_arch); 
        
        $num_rows = mysqli_num_rows($return_arch);
        if ($num_rows == 0) {
            goto end;
        }
        while ($row = mysqli_fetch_assoc($return_arch)) {
            
            $arr_fzg[$j] = $eignr . "|" . $fzgarr['fz_id'] . "|" . $fzgarr['fz_sammlg'] .  "|" . $row['ad_id']  . "|" . $row['ad_beschreibg'] . "|" . $row['ad_doc_1'] . "|" . $row['ad_doc_2'] . "|" . $row['ad_doc_3'] . "|" . $row['ad_doc_4'] . "|";
            $j ++;
        }
  
} else {
    echo "Keine Archiv- Daten dieses Eigentümers vorhanden <br>";
    echo "<a href='VF_FA_FZ_Edit.php?id=".$_SESSION[$module]['fz_id']."' >zurück zum Fahrzeug</a> ";
}

$table_name = "fz_arc_fz_xref_$eignr";

if(in_array($table_name,$ar_arr)) {
    
}

$arrlen = sizeof($arr_fzg);
if ($arrlen == 0) {
    goto end;
}
for ($i = 0; $i < $arrlen; $i ++) {

    $value = $arr_fzg[$i];
    $arr_fld = explode("|", $value);
    $select = "AND `fa_arcnr`='$arr_fld[3]'";
    $sql_fzar = "SELECT * FROM `$table_name` WHERE `fa_eignr`='$eignr' AND `fa_id`='$fa_id' $select";
    $return_fzar = SQL_QUERY($db, $sql_fzar); // or die("Datenbankabfrage gescheitert. ".mysql_error($db)." ".mysqli_errno($db)."<br/>\$sql_fzar $sql_fzar<br/>";

    while ($row = mysqli_fetch_object($return_fzar)) {
        if ($row->fa_eignr == $eignr and $row->fa_fzgnr == $arr_fld[1] and $row->fa_arcnr == $arr_fld[3]) {
            $arr_fzg[$i] .= "checked";
        }
    }
}

foreach ($arr_fzg as $value) {
    $arr_fld = explode("|", $value);
    
    # var_dump($arr_fld);
    $docpath = "AOrd_Verz/".$arr_fld[0]."/07/01/";
    $checked = "";
    if ($arr_fld['1'] == $arr_fld[3]) {
        $checked = "checked";
    }
    echo '<tr>';
    echo "<td><input name='arc_id[]' value='$arr_fld[3];$arr_fld[4];$arr_fld[1];$arr_fld[2];'type='checkbox' $checked >$arr_fld[3]</td>";
    echo '<td >';
    echo "$arr_fld[4]</td>";
    if (! empty($arr_fld[5])) {
        echo "<td><a href='$docpath$arr_fld[5]' target=_new>$arr_fld[5]</a></td>";
    }
    if (! empty($arr_fld[6])) {
        echo "<td><a href='$docpath$arr_fld[6]' target=_new>$arr_fld[6]</a></td>";
    }
    if (! empty($arr_fld[7])) {
        echo "<td><a href='$docpath$arr_fld[7]' target=_new>$arr_fld[7]</a></td>";
    }
    if (! empty($arr_fld[8])) {
        echo "<td><a href='$docpath$arr_fld[8]' target=_new>$arr_fld[8]</a></td>";
    }
    echo '<td>&nbsp;</td>';
    echo '</tr>';
}

echo '</table>';
echo "</div>";
if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Auswahl der Daten drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
end:
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_BA_AR_Srch_ph0.inc beendet</pre>";
}
?>