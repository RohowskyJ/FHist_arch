<?php

/**
 * Foto Detailanzeige
 * 
 * @author Josef Rohowsy - neu 2023
 * 
 * 
 */
session_start();

$module = 'OEF';
$sub_mod = 'Foto';

$tabelle = 'dm_edien_';

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
    $md_id = $_GET['id'];
}

if (isset($_GET['eig'])) {
    $md_eigner = $_GET['eig'];
}

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

$tabelle = $tabelle . $md_eigner;

$sql = "SELECT md_eigner,md_dsn_1, md_Urheber, md_aufn_datum,md_beschreibg,md_namen FROM $tabelle WHERE md_id='$md_id' ";

$return = SQL_QUERY($db, $sql);

$row = mysqli_fetch_object($return);

$pict_path = "../login/AOrd_Verz/$row->md_eigner/09/06/";

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

$d_path = $pict_path . $row->md_aufn_datum."/" ;

$header = "";
BA_HTML_header('Foto- Detailanzeige', $header, 'Form', '50em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<table>";
echo "<tr><th>Aufnahmedatum: $row->md_aufn_datum</th></tr>";
echo "<tr><th><img src='$d_path$row->md_dsn_1' alt='foto' ></th></tr>";
if ($row->md_namen != "") {
    echo "<tr><th>$row->md_namen</th></tr>";
}

echo "<tr><th><b>$row->md_beschreibg</b></th></tr>";
echo "<tr><th>Urheber: $row->md_Urheber</th></tr>";
echo "</table>";
BA_HTML_trailer();
?>