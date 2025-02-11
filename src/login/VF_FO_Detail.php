<?php

/**
 * Foto Detailanzeige
 * 
 * @author Josef Rohowsy - neu 2023
 * 
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
$tabelle = 'fo_todaten';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_GET['id'])) {
    $fo_id = $_GET['id'];
}

if (isset($_GET['eig'])) {
    $fo_eigner = $_GET['eig'];
}

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$tabelle = $tabelle . "_" . $fo_eigner;

$sql = "SELECT fo_eigner,fo_dsn, fo_Urheber, fo_aufn_datum,fo_basepath,fo_zus_pfad,fo_begltxt,fo_namen,fo_basepath,fo_aufn_suff FROM $tabelle WHERE fo_id='$fo_id' ";

$return = SQL_QUERY($db, $sql);

$row = mysqli_fetch_object($return);

$pict_path = "../login/AOrd_Verz/$row->fo_eigner/09/06/";

$nodat = false;
/*
if ($row->fo_basepath != "") {
    $d_path = $pict_path . $row->fo_basepath ."/";
} elseif ($row->fo_aufn_datum != "") {
    $d_path = $pict_path . $row->fo_aufn_datum."/" ;
    $nodat = True;
}

if ($row->fo_aufn_datum != "" AND !$nodat) {
    $d_path .=  $row->fo_aufn_datum . "/";
}

if ($row->fo_zus_pfad  != "") {
    $d_path .= $row->fo_zus_pfad . "/";
} else {
    #$d_path .=  "/";
}
*/
$f_path = VF_set_PictPfad($row->fo_aufn_datum,$row->fo_basepath,$row->fo_zus_pfad,$row->fo_aufn_suff);
$d_path = $pict_path . $f_path ;

$header = "";
BA_HTML_header('Foto- Detailanzeige', $header, 'Form', '50em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<table>";
echo "<tr><th>Aufnahmedatum: $row->fo_aufn_datum</th></tr>";
echo "<tr><th><img src='$d_path$row->fo_dsn' alt='foto' ></th></tr>";
if ($row->fo_namen != "") {
    echo "<tr><th>$row->fo_namen</th></tr>";
}

echo "<tr><th><b>$row->fo_begltxt</b></th></tr>";
echo "<tr><th>Urheber: $row->fo_Urheber</th></tr>";
echo "</table>";
BA_HTML_trailer();
?>