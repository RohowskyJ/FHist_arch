<?php

/**
 * Renumerierung Archivaliennummer entsprechend dem Dokumentendatum
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

VF_chk_valid();

initial_debug();

$LinkDB_database = '';
$db = LinkDB('Mem');

VF_chk_valid();
VF_set_module_p();

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
        $_SESSION['Eigner']['eig_name'] = "";
        $_SESSION['Eigner']['eig_verant'] = "";
        $_SESSION['Eigner']['eig_staat'] = "";
        $_SESSION['Eigner']['eig_adr'] = "";
        $_SESSION['Eigner']['eig_ort'] = "";
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['ei_id'])) {
    $_SESSION['Eigner']['eig_eigner'] = $ei_id = $_GET['ei_id'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

VF_Displ_Eig($ei_id);

$tabelle_m = $_SESSION[$module]['tabelle_m'] = "ar_chivdt";
$tabelle = $tabelle_m . "_" . $_SESSION['Eigner']['eig_eigner'];
$_SESSION[$module]['tabelle'] = $tabelle;

$ar_sg = array();
if ($ei_id == 1) {
    $ar_sg = array(
        "01|01|0|0",
        "01|01|01|0",
        "01|01|02|0",
        "01|01|06|0"
    );
}
if ($ei_id == 21) {
    $ar_sg = array(
        "01|01|01|0",
        "01|01|02|0",
        "01|01|03|0",
        "01|01|04|0",
        "01|01|05|0",
        "01|01|06|0",
        "o1|03|00|00",
        "01|03|01|00",
        "01|03|02|00",
        "01|03|03|00",
        "01|03|06|00",
        "01|03|07|00",
        "01|03|10|00",
        "01|06|00|00",
        "01|06|04|00",
        "01|06|06|00",
        "01|06|08|00",
        "01|07|00|00",
        "01|07|01|00",
        "01|07|04|00",
        "01|07|08|00",
        "01|10|01|00",
        "01|11|01|00",
        "01|13|05|00",
        "01|14|00|00",
        "01|14|01|00",
        "01|14|02|00",
        "01|14|02|00",
        "01|14|04|00",
        "01|14|05|01",
        "01|14|05|02",
        "01|14|06|00",
        "01|14|12|00",
        "01|14|13|00",
        "01|14|14|00",
        "01|15|00|00",
        "01|14|05|01",
        "01|14|05|02",
        "01|14|15|01",
        "01|14|15|02",
        "01|14|15|03",
        "01|14|18|01",
        "01|14|23|00",
        "01|14|24|00",
        "02|00|00|00",
        "03|00|00|00",
        "03|04|02|00",
        "03|04|03|00",
        "03|04|04|00",
        "03|040|070|00",
        "04|01|01|00",
        "03|04|03|00"
    );
    # ## SF-CH/SB EL URK Übungen Ausb AS FM ZM Aush LB/JB FB LFKDO NÖ
}

foreach ($ar_sg as $aord) {

    $sql = "SELECT * FROM $tabelle ";

    $ao_arr = explode("|", $aord);
    $cnt = count($ao_arr);

    $ad_sg = $ad_subsg = $ad_lcsg = $ad_lcssg = "";

    if ($ao_arr[0] != "00") {
        $ad_sg = $ao_arr[0];
    }
    if ($ao_arr[1] != "00") {
        $ad_subsg = $ao_arr[1];
    }
    if ($ao_arr[2] != "00") {
        $ad_lcsg = $ao_arr[2];
    }
    if ($cnt >= 4 and $ao_arr[3] != "00") {
        $ad_lcssg = $ao_arr[3];
    }
    # $sql_where = " WHERE ad_sg='01' AND ad_subsg='14' AND ad_lcsg='15' AND ad_lcssg='$lcssg' ";
    $sql_where = " WHERE  ad_sg='$ad_sg' AND ad_subsg='$ad_subsg'  ";
    if ($ad_lcsg != "") {
        $sql_where .= " AND ad_lcsg='$ad_lcsg'";
    }
    if ($ad_lcssg != "") {
        $sql_where .= " AND ad_lcssg='$ad_lcssg'";
    }

    $sql_orderBy = " ORDER BY ad_doc_date  ";

    $sql .= $sql_where . $sql_orderBy;
    # echo "L 0120 \$sql $sql <br/>";

    # echo "L 211 \$sql ".$_SESSION[$module]['sql_dr']." <br/>";

    $return = SQL_QUERY($db, $sql);
    $num_recs = mysqli_num_rows($return);
    # echo "L 126: <b>$num_recs für sql $sql gefunden</b><br>";

    $arch_arr = array();
    $i = 0;

    WHILE ($row = mysqli_fetch_row($return)) {
        # print_r($row);
        $arch_arr[$i] = $row;
        $i ++;
    }

    $i = 1;
    foreach ($arch_arr as $key => $val_arr) {
        $arch_arr[$key][6] = $i ++;
    }

    foreach ($arch_arr as $key => $val_arr) {
        $ao_fort = $arch_arr[$key][6];
        $ad_id = $arch_arr[$key][0];
        $sql = "UPDATE $tabelle SET ad_ao_fortlnr = '$ao_fort' WHERE ad_id ='$ad_id'";
        $return = SQL_QUERY($db, $sql);
    }

    echo "Neu- Numerierung für ad_sg $ad_sg - ad_subsg $ad_subsg - ad_lcsg $ad_lcsg - ad_lcssg $ad_lcssg  ist abgeschlossen.<br>";
}

echo "Neu Nummerierung ist abgeschlossen.<br>";

?>
