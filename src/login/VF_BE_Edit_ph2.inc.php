
<?php
/**
 * Liste der Veranstaltungsberichte, Wartung, Daten schreiben. Bericht und Detail (Reihenfolge, Unterseiten)
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {echo "<pre class=debug>VF_O_BE_Edit_ph2.inc.php ist gestarted</pre>";}

/*
 * vb_ber_detail_4 updaten
 */
$update_dat = ",vb_uid='" . $_SESSION['VF_Prim']['p_uid'] . "' ";
foreach ($arr_ber_det as $var => $value) {

    if ($var == "0") {
        continue;
    }

    $entr_arr = explode(";", $value);

    $updas = "";
    $vd_flnr = 0;

    foreach ($entr_arr as $key => $valuer) {
        if ($valuer == "") {
            break;
        }
#echo "L 029 valuer $valuer <br>";
        $rec_arr = explode("|", $valuer);

        if ($vd_flnr == 0) {
            $vd_flnr = $rec_arr[0];
            $where = " vd_flnr='$vd_flnr' ";
        }
        if ($rec_arr[0] == 'unter') {
            
            $updas .= " vb_unter='" . $rec_arr[1] . "', ";
            if ($rec_arr[1] > 0) {
                $arr_ber['vb_unterseiten'] = "Unterseiten";
            }
        }
        if ($rec_arr[0] == 'suffix') {
            if (strpos($updas,"vb_suffix") >1 ) {continue;}
            $updas .= " vb_suffix='" . $rec_arr[1] . "', ";
        }
        if ($rec_arr[0] == 'titel') {
            $updas .= " vb_titel='" . $rec_arr[1] . "' ";
        }
    }

    $sql = "UPDATE `vb_ber_detail_4` SET $updas $update_dat WHERE vd_flnr = '$var' ";

    if ($debug) {
        echo "<pre class=debug> L 055: \$sql $sql  </pre>";
    }

    $result = SQL_QUERY($db, $sql);
    
    $updas = "";
}

/*
 * vb_bericht_4 updaten
 */

$updas = ""; # assignements for UPDATE xxxxx SET `variable` = 'Wert'

foreach ($arr_ber as $name => $value) # für alle Felder aus der tabelle
{
    if ($name == "vb_flnr") {
        $where = " WHERE vb_flnr='$value' ";
        continue;
    }
    $updas .= ",`$name`='" . $value . "'"; # weiteres SET `variable` = 'Wert' fürs query
}

$updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

$update_dat = ",vb_uid='" . $_SESSION['VF_Prim']['p_uid'] . "'";

$sql = "UPDATE `vb_bericht_4` SET $updas $update_dat $where ";

if ($debug) {
    echo '<pre class=debug> L 081: \$sql $sql </pre>';
}

$result = SQL_QUERY($db, $sql);

/**
 * Foto Text Updaten
 */
$updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
$update_dat = ",fo_uidaend='" . $_SESSION['VF_Prim']['p_uid'] . "'";
foreach ($arr_fot as $name => $value) # für alle Felder aus der tabelle
{
    if ($name == "vb_flnr") {
        $where = " WHERE vb_flnr='$value' ";
        continue;
    }

    $valuer = explode("|", $value); # key: Recnr, 0: Urh, 1 Feld, 2 Inhalt
}
$eig= $_SESSION['Eigner']['eig_eigner'];

$sql = "UPDATE `fo_todaten_$eig` SET fo_begltxt='$valuer[2]' $update_dat WHERE fo_id='$name' ";

$result = SQL_QUERY($db, $sql);
if ($debug) {
    echo '<pre class=debug> L 0111:  \$sql $sql </pre>';
}

if ($debug) {echo "<pre class=debug>VF_O_BE_Edit_ph2.inc.php ist beendet</pre>";}
    
header("Location: VF_BE_List.php?Act=" . $_SESSION[$module]['Act']);

?>
