<?php
/**
 * Laden der Daten in die Archivalien-Tabellen des gewählten Eigentümers, Abfrage Archivordnung
 *
 * @author  Josef Rohowsky - neu 2025
 *
 *
 */
/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_C_Massup_AR_ph2.inc.php.php";

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_AR_ph2.inc.php ist gestarted</pre>";
}

if (!isset($_SESSION[$module]['archord'] )) {
    $_SESSION[$module]['archord'] = array();
}
/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix" ) {
        
    } else {
        $response = $_SESSION[$module]['archord'] = $response;
    }
}

// Vorbereitung zum Kopieren/löschen der Dateien

$arr_ak = array('00','00','00','00','00','00');

$aord_arr = explode(" ",$_SESSION[$module]['archord']);

$ao_a = explode (".",$aord_arr[0]);
if (isset($ao_a[0]) ) {
    $arr_ak[0] = $ao_a[0];
}
if (isset($ao_a[1])) {
    $arr_ak[1] = $ao_a[1];
}

if (isset($aord_arr[1]) && $aord_arr[1] != '00') {
    $arr_ak[2] = $aord_arr[1];
}
if (isset($aord_arr[2]) && $aord_arr[2] != '00') {
    $arr_ak[3] = $aord_arr[2];
}
if (isset($aord_arr[3]) && $aord_arr[3] != '00') {
    $arr_ak[4] = $aord_arr[3];
}
if (isset($aord_arr[4]) && $aord_arr[4] != '00') {
    $arr_ak[5] = $aord_arr[4];
}

$AO_dat = "";
if (isset($arr_ak)) {
    
    if ($arr_ak[2] == "00") {
        $AO_dat = VF_Displ_Aro($arr_ak[0], $arr_ak[1]);
    } elseif ($arr_ak[3] == "00") {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3], $arr_ak[4], $arr_ak[5]);
    } else {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3], $arr_ak[4], $arr_ak[5]);
    }
}

$aOrd = $arr_ak[0]."/".$arr_ak[1];

$basis_pfad = $pfad = $beschreibg = "";
$eignr = $_SESSION['Eigner']['eig_eigner'];

echo "<input type='hidden' id='eiId' name='eigner' value='$eignr'>";
echo "<input type='hidden' id='aOrd' name='aOrd' value='$aOrd'>";
echo "<input type='hidden' id='aoText' name='aoText' value='$AO_dat'>";
echo "<input type='hidden' id='reSize' name='reSize' value='1754'>";

echo "<div class='white'>";

Edit_Tabellen_Header("hochladen von Daten für <br> ".$_SESSION['Eigner']['eig_eigner']);

Edit_Separator_Zeile("Die Daten werden im Verzeichnis /login/VF_Upload/$eignr/$aOrd abgelegt <br>$AO_dat");

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_AR_ph2.inc.php ist beendet</pre>";
}

