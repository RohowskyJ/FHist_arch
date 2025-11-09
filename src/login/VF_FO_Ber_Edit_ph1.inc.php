
<?php
/**
 * Liste der Veranstaltungsberichte, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_FO_Ber_Edit_ph1.inc.php";

if ($debug) {echo "<pre class=debug>VF_FO_Ber_Edit_ph1.inc.php ist gestartet</pre>";}
var_dump($_POST);
var_dump($neu);
# =====================================================================================================
# Datensatz in der Tabelle ändern
# =====================================================================================================
if (!isset($neu['vb_foto'])) { $neu['vb_foto'] = '';}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($neu['vb_flnr'] == 0) {

    $p_uid = $_SESSION['VF_Prim']['p_uid'];
    $sql = "INSERT INTO vb_bericht_4 (vb_datum,vb_unterseiten,vb_titel,vb_beschreibung,vb_foto,vb_fzg_beschr,vb_uid
                      ) VALUE (
                        '$neu[vb_datum]','$neu[vb_unterseiten]','$neu[vb_titel]','$neu[vb_beschreibung]','$neu[vb_foto]','$neu[vb_fzg_beschr]'
                        ,'$p_uid'             
                       ) ";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>L 022 $sql</pre>";
    $result = SQL_QUERY($db, $sql);
    $vb_flnr = mysqli_insert_id($db);
    #console_log("neuer Recno ".$neu['vb_flnr']);
    
    foreach ($neu as $name => $bild_data ) { // Detail-Recs anlegen
       # console_log("Name $name");
       # console_log("B-String $bild_data");
       # echo "L 030 name $name $bild_data <br>";
        if (substr($name,0,5) == 'bild_') {
            #console_log("name $name ");
            $d_arr = explode("|",$bild_data); // Seite|Position|Bild-Dsn|Text
            # var_dump($d_arr);
            
            $sql = "INSERT INTO vb_ber_detail_4 (vb_flnr,vd_unter,vd_suffix,vd_foto,vd_beschreibung,vd_titel,vd_uid
                         ) VALUE ( '$vb_flnr','$d_arr[0]','$d_arr[1]','$d_arr[2]','$d_arr[3]','$d_arr[4]','$p_uid')";
            $result = SQL_QUERY($db, $sql);
            
           // fotodatei kommentar updaten foto d_arr[2] kommentar d_arr[3]
            $u_arr = explode('-',$d_arr[2]);
            $urh = $u_arr[0];
            $sql_fo = "UPDATE dm_edien_$urh SET md_beschreibg='$d_arr[3]' WHERE md_dsn_1='$d_arr[2]'  ";
            
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO Ber Edit_ph1_create $sql </pre>";
            echo "</div>";
            
            $ret = SQL_QUERY($db,$sql_fo);
        }
    }


} else {

    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if (substr($name,0,3) != 'vb_') {
            continue;
        } 

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $update_dat = ",vb_uid='" . $_SESSION['VF_Prim']['p_uid'] . "'";

    $vb_flnr = $neu['vb_flnr'];
    $sql = "UPDATE `vb_bericht_4` SET $updas $update_dat WHERE vb_flnr='$vb_flnr'";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO Ber Edit_ph1_create $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);

    foreach ($neu as $key => $data) {
        if (substr($key,0,5)  == 'bild_') {
            $d_arr = explode('|',$data);
            if ($d_arr[5] > 0) { // update/delete
                $sql = "UPDATE vb_ber_detail_4 SET vb_flnr='$vb_flnr', vd_unter='$d_arr[0]',vd_suffix='$d_arr[1]',vd_beschreibung='$d_arr[3]',vd_foto='$d_arr[2]' WHERE vd_flnr='$d_arr[5]' ";
            } else { // neu anlegen
                $sql = "INSERT INTO vb_ber_detail_4 (vb_flnr,vd_unter,vd_suffix,vd_foto,vd_beschreibung,vd_titel,vd_uid
                         ) VALUE ( '$vb_flnr','$d_arr[0]','$d_arr[1]','$d_arr[2]','$d_arr[3]','$d_arr[4]','$p_uid')";
            }
            
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO Ber Edit_ph1 $sql </pre>";
            echo "</div>";
            
            $result = SQL_QUERY($db, $sql);
            // fotodatei kommentar updaten foto d_arr[2] kommentar d_arr[3]
            $u_arr = explode('-',$d_arr[2]);
            $urh = $u_arr[0];
            $sql_fo = "UPDATE dm_edien_$urh SET md_beschreibg='$d_arr[3]' WHERE md_dsn_1='$d_arr[2]'  ";
            
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO Ber Edit_ph1 $sql_fo </pre>";
            echo "</div>";
            
            $ret = SQL_QUERY($db,$sql_fo);
            
        }
    }
    
 
}

if ($debug) {echo "<pre class=debug>VF_FO_Ber_Edit_ph1.inc.php ist beendet</pre>";}

header("Location: VF_FO_Ber_List.php");
?>
