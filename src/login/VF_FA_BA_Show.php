<?php
/**
 * Anzeige eines Fahrzeuges aus dem Katalog
 * 
 * @autor Josef Rohowsky - neu 2020
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_M';
$module = Module_Name;
$tabelle = 'ma_fz_beschr';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/Funcs.inc.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.inc.php';
require $path2ROOT . 'login/common/const.inc.php';

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_GET['ID'])) {
    $fz_eig_id = $_GET['ID'];
} else {
    $fz_eig_id = "";
}

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$db = LinkDB('VFH');

HTML_header('Anzeige Historisches Fahrzeug', '', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

$id_arr = explode("|", $fz_eig_id);

$eignr = $id_arr[1];
$fz_id = $id_arr[0];

$tabelle = "ma_fz_beschr_$eignr";
# $select = " WHERE fz_id=$fz_id ";
$sql = "SELECT * FROM $tabelle  WHERE fz_id=$fz_id  ";

$return_in = SQL_QUERY($db, $sql);

$row = mysqli_fetch_object($return_in);

$titel = "";

echo "<fieldset>";
echo "<h2>Verein Feuerwehrhistoriker in NÖ, Fahrzeugkatalog</h2>";
# echo "<table>";
echo "<table class='w3-table w3-striped w3-hoverable scroll' style='border:1px solid black;background-color:white;margin:0px;'>";
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

echo '<tr>';
echo '<th colspan="11">';
echo "<font size='+1'>$row->fz_id</font> &nbsp; &nbsp; ";
echo "<font size='+1'>$row->fz_name</font> &nbsp; &nbsp; ";
echo "<font size='+1'>$row->fz_taktbez</font><br/>";
echo '</th></tr>';
echo '<tr>';

echo '<th colspan="11">';
$text = "";
$fz_aera = $row->fz_zeitraum;

echo "<font size='+1'>$text</font>";
echo '<br/>In- und Ausserdienststellung: ';
echo "<font size='+1'>$row->fz_indienstst &nbsp; - &nbsp; $row->fz_ausdienst</font>";
echo '</th></tr>';

echo '<tr>';

$ei = VF_Displ_Eig($row->fz_eignr);
# return("$leihname|$leih_verant|$leihadr|$leihstaat|$leihplz|$leihort|"$row->ei_vopriv|$row->ei_voar|$row->ei_vofo|$row->ei_voinv|$row->ei_vomus";"
$eig_arr = explode("|", $ei);

$ei_name = $eig_arr[0];

echo "Eigentümernummer: $row->fz_eignr $ei_name <br/>";
if (! empty($row->fz_pruefg_id) and ! empty($row->fz_pruefg)) {
    echo "<br/>Daten Ref 2 geprüft: " . $row->fz_pruefg_id . " " . $row->fz_pruefg;
}

echo '</th></tr>';
echo '<tr>';
echo '<th colspan="11">';

if (substr($row->fz_sammlg,0,4) == 'MA_F' ) {
    $smlg = 'MaF';
} else {
    $smlg = 'MaG';
}

$pict_path = "AOrd_Verz/" . $row->fz_eignr . "/$smlg/";
$pict_1 = $pict_path . $row->fz_bild_1;

if (! empty($row->fz_bild_1)) {
    echo "<img src='$pict_1' alt='Fahrzeugbild 1'  align='left' style='border: 1px solid #ddd; border-radius: 4px; padding: 5px;  ' >";
} else {
    echo "&nbsp;";
}
echo "<h3>Technische Daten:</h3>";
echo "$row->fz_herstell_fg <br/>";
# echo "$fzggesttyp <br/>";
echo 'Baujahr: ';
echo "$row->fz_baujahr <br/>";

echo "<font size='+1'>Zustand: </font>";
$zust = VF_Zustand[$row->fz_zustand];
echo "$zust ";

echo '</th></tr>';

if (! empty($row->fz_ctifdate)) {
    echo '<th colspan="11">';
    echo "<b>CTIF Klassifizierung </b> <br/>Datum: $row->fz_ctifdate in Klasse $row->fz_ctifklass";
    echo '</th></tr>';
}

echo '<tr>';
echo '<th colspan="11" align="left">';
echo '<b>Detailbeschreibung: </b><br/>';
echo "$row->fz_komment";
echo "$row->fz_beschreibg_det";
echo '</td>';
echo '</tr>';

echo '</table>';

echo "<button type='submit' name='phase' value='1' class=green>Zurück zur Liste</button></p>";

echo "</fieldset>";

HTML_trailer();