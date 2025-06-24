<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
/*
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", "php-error_Multisel.log");
error_log( "Hello, errors!" );   
*/
$result = [
    'status' => 'ok',
    'data' => [],
    'error' => null,
];

if (empty($_POST) && empty($_GET)) {
    $result['status'] = 'error';
    $result['error'] = 'Keine Daten übergeben';
    echo json_encode($result);
    ob_end_flush();
    exit;
}

$ini_d = "../config_d.ini";
$ini_arr = parse_ini_file($ini_d, True, INI_SCANNER_NORMAL);

$server_name = $_SERVER['SERVER_NAME'];
if (stripos($server_name, "www") !== false) {
    $url_arr = explode(".", $server_name);
    $cnt_u = count($url_arr);
    if ($cnt_u >= 2) {
        $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
    }
}

$dbhost = '';
$dbuser = '';
$dbpass = '';
$database = '';

if (isset($ini_arr[$server_name])) {
    if ($server_name == 'localhost') {
        $dbhost = $ini_arr[$server_name]['l_dbh'];
        $dbuser = $ini_arr[$server_name]['l_dbu'];
        $dbpass = $ini_arr[$server_name]['l_dbp'];
        $database = $ini_arr[$server_name]['l_dbn'];
    } else {
        if (stripos($server_name, "www") !== false) {
            $url_arr = explode(".", $server_name);
            $cnt_u = count($url_arr);
            $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
        }
        $server_name = "HOST"; // Fallback
        if (isset($ini_arr[$server_name])) {
            $dbhost = $ini_arr[$server_name]['h_dbh'];
            $dbuser = $ini_arr[$server_name]['h_dbu'];
            $dbpass = $ini_arr[$server_name]['h_dbp'];
            $database = $ini_arr[$server_name]['h_dbn'];
        }
    }
} else {
    $result['status'] = 'error';
    $result['error'] = 'Server-Konfiguration nicht gefunden.';
    echo json_encode($result);
    ob_end_flush();
    exit;
}

$db = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$db) {
    $result['status'] = 'error';
    $result['error'] = 'Verbindung zu MySQL fehlgeschlagen: ' . mysqli_connect_error();
    echo json_encode($result);
    ob_end_flush();
    exit;
}
mysqli_select_db($db, $database);
mysqli_set_charset($db, 'utf8');

// Parameter
$level = isset($_GET['level']) ? intval($_GET['level']) : 0;
$parent = isset($_GET['parent']) ? trim($_GET['parent']) : "";
$opval = isset($_GET['opval']) ? $_GET['opval'] : '1';

/* */
$dsn = "multidrop.log";
$eintragen = Date("Y-m-d H:i:s")."\n";
$eintragen .= "term $term \n";
$eintragen .= "proc $proc \n";
/*
$datei = fopen($dsn, "a");
fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
fclose($datei);

$eintragen = "l 069 isset term, $term \n proc $proc \n";

$datei = fopen($dsn, "a");
fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
fclose($datei);
*/
$data[] = ['value' => '', 'text' => 'Bitte auswählen' ,];

if ($opval == '1') {
    // Beispiel: Sammlung (1. Ebene)
    /*
    $dsn = "multidrop.log";
    $eintragen = Date("Y-m-d H:i:s")."\n";
    $eintragen .= "term $term \n";
    $eintragen .= "proc $proc \n";
    */
    $sql = "SELECT * FROM fh_sammlung WHERE sa_grup LIKE '$parent' ORDER BY sa_sammlg ASC"; 
    $result_set = mysqli_query($db, $sql);
   
    if ($result_set) {
        while ($row = mysqli_fetch_object($result_set)) {

            $data[] = [
                'value' => $row->sa_sammlg,
                'text' => $row->sa_name,
            ];
            $eintragen .= "parent $parent; p_cnt $cnt_par; a_cnt $act_cnt; sa_grup $row->sa_grup; sa_sammlg $row->sa_sammlg; txt $row->sa_name \n";
        } 
    }
    /* 
    $datei = fopen($dsn, "a");
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);
    */
} elseif ($opval == '2') {
    // Beispiel: Archivordnung
    if ($level == 1) {
        $sql = "SELECT * FROM ar_chivord WHERE ar_sg = '$parent' AND ar_sub_sg != '0'";
        $resq = mysqli_query($db, $sql);
        if ($resq) {
            while ($row = mysqli_fetch_object($resq)) {
                $data[] = [
                    'value' => "$row->ar_sg.$row->ar_sub_sg",
                    'text' => $row->ar_sgname,
                ];
            }
        }
    } elseif ($level == 2) {
        $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$parent' AND al_lcsg > '00' AND al_lcssg='00'";
        $resq = mysqli_query($db, $sql);
        if ($resq) {
            while ($row = mysqli_fetch_object($resq)) {
                $value = "$row->al_sg $row->al_lcsg";
                $text = $row->al_bezeich;
                $data[] = ['value' => $value, 'text' => $text];
            }
        }
    }
    // Weitere Level je nach Bedarf
}
$ret = json_encode([
    'status' => 'ok',
    'data' => $data,
    'error' => null,
]);
# error_log("L 0136: respond: $ret");
echo json_encode([
    'status' => 'ok',
    'data' => $data,
    'error' => null,
]);

ob_end_flush();
?>
