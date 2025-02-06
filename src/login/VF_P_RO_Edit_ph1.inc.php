<?php
/**
 * Protokolle des Vereines, Hochladen
 *
 * @author  Josef Rohowsky - neu 2021
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_P_RO_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$uploaddir = $pict_path;
$target1 = "";
if (! empty($_FILES['uploaddatei_1'])) {
    $pict1 = basename($_FILES['uploaddatei_1']['name']);
    if (! empty($pict1)) {
        $target1 = $uploaddir . basename($_FILES['uploaddatei_1']['name']);
        if (move_uploaded_file($_FILES['uploaddatei_1']['tmp_name'], $target1)) {
            echo "Datei 1 geladen!<br><br><br>";
        }
    } else {
        $target1 = "";
    }
}

$LinkDB_database = "";
$db = LinkDB('VFH'); // Connect zur Datenbank

$ar_arr = array();
VF_tableExist();

if (! in_array('ar_chivdt_1', $ar_arr)) {
    Cr_n_ar_chivdt('ar_chivdt_1');
}

$sql = "SELECT * FROM ar_chivdt_1 WHERE ad_doc_1='$pict1' ";
$return = SQL_QUERY($db, $sql);
$num_rec = mysqli_num_rows($return);

if ($num_rec === 0) {

    $sql_nr = "SELECT * FROM ar_chivdt_1
                WHERE `ad_sg`='01' AND `ad_subsg`='01' AND `ad_lcsg`='0' AND `ad_lcssg`='0' ";
    $return_nr = SQL_QUERY($db, $sql_nr);
    if ($return_nr) {
        $row = mysqli_fetch_object($return_nr);
        # $flnr = $row->ad_ao_fortlnr;
        $numrow = mysqli_num_rows($return_nr);
        $ad_ao_fortlnr = $numrow + 1;
    } else {
        $ad_ao_fortlnr = 1;
    }

    $f_arr = explode("_", $pict1);

    $doc_date = substr($f_arr[0], 0, 4) . "-" . substr($f_arr[0], 4, 2) . "-" . substr($f_arr[0], 6, 2);

    $beschr = "$f_arr[1]. Sitzung $f_arr[2]";
    $uid = $_SESSION['VF_Prim']['p_uid'];

    $sql = "INSERT INTO ar_chivdt_1 (
           ad_eignr,ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_ao_fortlnr,
           ad_doc_date,ad_type,ad_format,
           ad_beschreibg,
           ad_doc_1,ad_uidaend,ad_aenddat
           ) VALUE (
           '1','01','01','0','0','$ad_ao_fortlnr',
           '$doc_date','PR','A4',
           '$beschr',
           '$pict1','$uid', now()
           )";

    $return = SQL_QUERY($db, $sql);
}

header("Location: VF_P_RO_List.php");

if ($debug) {
    echo "<pre class=debug>VF_P_RO_Edit_ph1.inc.php beendet</pre>";
}
?>