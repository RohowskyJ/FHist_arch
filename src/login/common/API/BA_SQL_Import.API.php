<?php
/**
 * BA_SQL_Import.API.php
 * 
 * Importieren von SQL Tabellen mit PHP unter Benutzung von js/AJAX
 * 
 */
header('Content-Type: application/json; charset=utf-8');
ob_start();

$debug_log_file = 'VF_SQL_Import_API_debug.log.txt';
file_put_contents($debug_log_file, "\n==== API CALL ====\n".date('Y-m-d H:i:s')."\nMETHOD: ".@$_SERVER['REQUEST_METHOD']."\n", FILE_APPEND);

include "../BA_Funcs.lib.php";
include "../VF_Foto_Funcs.lib.php";


ini_set('display_errors', '1');
ini_set('log_errors', 1);
ini_set('error_log', 'VF_SQL_Import_API_php-error.log.txt');

$debug_log = true;

// end default vars

$sqlFile = $_FILES['sql'] ?? null;

if ($debug_log) {
    $eintragen = "Jetzand:\n\n";
    $vard = var_export($sqlFile,true);
    $eintragen .= "Files:  $vard \n";
    
    file_put_contents($debug_log_file, "L 042 $eintragen" . PHP_EOL, FILE_APPEND);
}

if ( !$sqlFile || $sqlFile['error'] !== UPLOAD_ERR_OK) {
    sendResponse(false, 0, 0, 'Ungültige Eingabedaten oder Datei-Upload fehlgeschlagen.');
}

// Funktion zum Senden der JSON-Antwort
function sendResponse($ok, $queries = 0, $size = 0, $error = '') {
    global $debug_log, $debug_log_file;
    if ($debug_log) {
        $eintragen  = " Queries $queries \n";
        if ($ok) {
            $eintragen .= " OK Ja \n";
        } else {
            $eintragen .= " OK Nein \n";
        }
        
        $eintragen .= " Error $error \n";
        
        file_put_contents($debug_log_file, "L 059 $eintragen" . PHP_EOL, FILE_APPEND);
    }
    file_put_contents($debug_log_file, "L 061 Response ".json_encode([
        'ok' => $ok,
        'queries' => $queries,
        'size' => $size,
        'error' => $error
    ]) . PHP_EOL, FILE_APPEND);
    
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok' => $ok,
        'queries' => $queries,
        'size' => $size,
        'error' => $error
    ]);
    exit;
}

$sqlContent = file_get_contents($sqlFile['tmp_name']);
if ($sqlContent === false) {
    sendResponse(false, 0, 0, 'Fehler beim Lesen der SQL-Datei.');
}

/**
 * DB Parameter einlesen
 */
$module = 'ADM';
$sub_mod = 'all';
$debug = false;
$path2ROOT = "../../../";

$mysqli = linkDB('');
/*
if (!mysqli) {
    sendResponse(false, 0, 0, 'Datenbankverbindung fehlgeschlagen: ' );
}
*/
$queriesExecuted = 0;
if ($mysqli->multi_query($sqlContent)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
        $queriesExecuted++;
    }
    while ($mysqli->more_results() && $mysqli->next_result());
} else {
    sendResponse(false, $queriesExecuted, strlen($sqlContent), 'Fehler beim Ausführen der SQL-Statements: ' . $mysqli->error);
}

$mysqli->close();
sendResponse(true, $queriesExecuted, strlen($sqlContent));

