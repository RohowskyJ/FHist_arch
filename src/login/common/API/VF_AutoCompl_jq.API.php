<?php

/**
 * Eigentümer- Auswahl
 *
 * Auswahl eines Leihers oder neuer Eigentümer
 * Aufruf mittels AJAX UI mittels BA_Auto_Compl.API.php aus verschiedenen Programmen
 *
 * @author Josef Rohowsky -  neu 2018
 *
 *
 */

header('Content-Type: application/json charset=utf-8');
/*
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
error_log( "Hello, errors!" );
*/

const Module_Name = 'Auto-Complete';
$module = Module_Name;
$tabelle = "";

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../../";
$debug = false;
require $path2ROOT . 'login/common/BA_Funcs.lib.php';

if (isset($_GET["term"])) {
    $term = $_GET["term"];
}

if (isset($_POST['query'])) {
    $term = $_POST['query'];
}
if (isset($_POST['q'])) {
    $term = $_POST['q'];
}
if (isset($_POST['proc'])) {
    $proc = $_POST['proc'];
} else {
    $proc = "Eigent";
}
if (isset($_GET['proc'])) {
    $proc = $_GET['proc'];
}
if (isset($_GET['query'])) {
    $term = $_GET['query'];
}
$LinkDB_database = "";
$db = LinkDB('VFH'); // Connect zur Datenbank
/*
 $dsn = "autocomp.log";
 $eintragen = Date("Y-m-d H:i:s")."\n";
 $eintragen .= "term $term \n";
 $eintragen .= "proc $proc \n";

 $datei = fopen($dsn, "a");
 fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
 fclose($datei);

 $eintragen = "l 069 isset term, $term \n proc $proc \n";

 $datei = fopen($dsn, "a");
 fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
 fclose($datei);
  */
if ($proc == "Eigentuemer") {
    eigent($term);
}
if ($proc == "Taktisch") {
    taktische($term);
}
if ($proc == "Hersteller") {
    hersteller($term);
}
if ($proc == "Aufbauer") {
    aufbauer($term);
}
if ($proc == "Urheber") {
    urheb($term);
}

$response[] = ['value' => '', 'label' => "Keine Auswahl $proc gefunden"];
# echo json_encode($response);


function eigent($term)
{
    global $db, $module, $srch_arr;
    /*
    $dsn = "autocomp_eig.log";
    $eintragen = "f Eig  $term L 099\n";

    $datei = fopen($dsn, "a");
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);
    */
    $query = "SELECT * FROM fh_eigentuemer WHERE ei_name LIKE '{$term}%' OR ei_org_name  LIKE '{$term}%' LIMIT 100";
    $result = SQL_QUERY($db, $query);

    if (mysqli_num_rows($result) > 0) {
        /*
         $eintragen = "num row > 0 \L 0104n";

         $datei = fopen($dsn, "a");
         fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
         fclose($datei);
         */
        while ($user = mysqli_fetch_array($result)) {
            $response[] = [
                'value' => $user['ei_id'],
                'label' => $user['ei_org_name'] . " - " . $user['ei_name'] . " " . $user['ei_vname']
            ];
        }
        /*
        $dsn = "autocomp_eig.log";
        $eintragen = "response ".json_encode($response)."  $term L 0123\n";

        $datei = fopen($dsn, "a");
        fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
        fclose($datei);
        */
        echo json_encode($response);
    } else {
        $response[] = ['value' => '', 'label' => 'Keine EIntragung gefunden'];

        echo json_encode($response);
    }

} # ende funct eigent

function urheb($term)
{
    global $db, $module, $srch_arr;

    $query = "SELECT * FROM fh_eigentuemer WHERE ei_media<> '' AND (ei_name LIKE '{$term}%' OR ei_org_name  LIKE '{$term}%') LIMIT 100";

    $result = SQL_QUERY($db, $query);

    if (mysqli_num_rows($result) > 0) {

        while ($user = mysqli_fetch_array($result)) {
            $response[] = [
                'value' => $user['ei_id'],
                'label' => $user['ei_name']
            ];
        }

        echo json_encode($response);
    } else {
        $response[] = ['value' => '', 'label' => 'Keine Eintragung gefunden'];
        echo json_encode($response);
    }

} # ende funct urheb

function taktische($term)
{
    global $db, $module, $srch_arr, $dsn,$proc;
    /*
     $dsn = "autocomp_tak.log";
     $eintragen = "l 0161 taktische  term $term \n";

     $datei = fopen($dsn, "a");
     fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
     fclose($datei);
     */
    $query = "SELECT * FROM fh_abk WHERE ab_bezeichn LIKE '{$term}%' OR ab_abk  LIKE '{$term}%' LIMIT 100";

    $result = SQL_QUERY($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $response = array();
        while ($abk = mysqli_fetch_array($result)) {
            $response[] = [
                'value' => $abk['ab_abk'],
                'label' => $abk['ab_bezeichn']. " - ". $abk['ab_grp']."  (".$abk['ab_abk'] .")"
            ];
        }
        /* */
        $dsn = "autocomp_tak.log";
        $eintragen = "response ".json_encode($response)."  $term L 0186\n";

        $datei = fopen($dsn, "a");
        fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
        fclose($datei);
        /* */
        echo json_encode($response);
    } else {
        $response[] = ['value' => '', 'label' => 'Keine EIntragung gefunden'];
        echo json_encode($response);
    }

} # ende funct abkuerz

function hersteller($term)
{
    global $db, $module, $srch_arr;
    /*
     $dsn = "autocomp_her.log";
     $eintragen = " isset term term $term \n";

     $datei = fopen($dsn, "a");
     fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
     fclose($datei);
     */
    $query = "SELECT * FROM fh_firmen WHERE fi_funkt = 'F' and (fi_name LIKE '{$term}%' OR fi_abk  LIKE '{$term}%') LIMIT 100";

    $result = SQL_QUERY($db, $query);

    if (mysqli_num_rows($result) > 0) {

        while ($abk = mysqli_fetch_array($result)) {
            $response[] = [
                'value' => $abk['fi_abk'],
                'label' => $abk['fi_name']
            ];
        }

        echo json_encode($response);
    } else {
        $response[] = ['value' => '', 'label' => 'Keine EIntragung gefunden'];
        echo json_encode($response);
    }

} # ende funct abkuerz

function aufbauer($term)
{
    global $db, $module, $srch_arr;
    /*
     $dsn = "autocomp_auf.log";
     $eintragen = " isset term term $term \n";

     $datei = fopen($dsn, "a");
     fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
     fclose($datei);
     */
    $query = "SELECT * FROM fh_firmen WHERE fi_funkt = 'A' and (fi_name LIKE '{$term}%' OR fi_abk  LIKE '{$term}%') LIMIT 100";

    $result = SQL_QUERY($db, $query);

    if (mysqli_num_rows($result) > 0) {

        while ($abk = mysqli_fetch_array($result)) {
            $response[] = [
                'value' => $abk['fi_abk'],
                'label' => $abk['fi_name']
            ];
        }

        echo json_encode($response);
    } else {
        $response[] = ['value' => '', 'label' => 'Keine Eintragung gefunden'];
        echo json_encode($response);
    }

} # ende funct abkuerz
