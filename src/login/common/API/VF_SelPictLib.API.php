<?php

# header('Content-Type: application/json');

require "../BA_Funcs.lib.php";
require "../VF_Comm_Funcs.lib.php";
require "../VF_Const.lib.php";

/* für PHP-Logging */
 ini_set('display_errors', '1');
 ini_set("log_errors", 1);
 ini_set("error_log", "SelPict_php-error.log");
 error_log( "Hello, errors!" );
/* */
$debug = false;
$debug_log = true;

if ($debug_log) {
    file_put_contents('SelPictLib_debug.log', "VF_SelPictLib.API L 007 " . PHP_EOL, FILE_APPEND);
}

# if ($_SERVER['REQUEST_METHOD'] === 'GET') { #) { # $_SERVER['REQUEST_METHOD'] === 'POST' ) { #

$eintragen = Date("Y-m-d H:i:s") . "\n";

$sammlg = $_GET['sammlg'] ?? '';
$eigner = $_GET['eigner'] ?? '';


/**  Testt- Parms für direkt- Aufruf 
  $sammlg = "Ma_F-L";
  $eigner = "21"; #"Wiener Neudorf";
*/

$ini_d = "../config_d.ini";
$ini_arr = parse_ini_file($ini_d, true, INI_SCANNER_NORMAL);

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

$LinkDB_database = $database;

$basispfad = "../../AOrd_Verz/";

if ($debug_log) {
    $eintragen .= "sammlg $sammlg \n";
    $eintragen .= "feuerwehr $eigner  \n";
    file_put_contents('SelPictLib_debug.log', "L 097 $eintragen" . PHP_EOL, FILE_APPEND);
}



$ar_arr = $dm_arr = $in_arr = $maf_arr = $mag_arr  = $muf_arr  = $mug_arr  = $ge_arr =  $zt_arr = array();
$tables_act = VF_tableExist();          // Array der existierenden Tabellen
# var_dump($dm_arr);
$where = "";

$bilder = array();
$anz_bilder = 0;
foreach ($dm_arr as $dsn => $drop) {
    if ($dsn == 'dm_edien_') {
        continue;
    }

    $where = " WHERE md_sammlg LIKE '%$sammlg%'  AND  md_fw_id LIKE '%$eigner%' ";

    $sql = "SELECT * FROM $dsn $where ";
   
    # echo "L 0141 sql $sql <br>";

    $return = SQL_QUERY($db, $sql);
    if (mysqli_num_rows($return) === 0) {
        if (strlen($sammlg) > 4 ) {
            $s_samm = substr($sammlg,0,4);
            $sql = "SELECT * FROM $dsn WHERE  md_fw_id LIKE '%$eigner%'  "; // md_sammlg like '%s_samm%' AND 
            $return = SQL_QUERY($db,$sql);
        }
    }
    while ($row = mysqli_fetch_object($return)) {
        # print_r($row);echo "<br>";
        if ($row->md_dsn_1 == "") {
            continue;
        }
        
        $eintragen = "$sammlg  row $row->md_dsn_1 \n";
        file_put_contents('SelPictLib_debug.log', "L 0136 $eintragen" . PHP_EOL, FILE_APPEND);
        
        $p_arr = pathinfo($row->md_dsn_1);
        $subsg = "";
        if (in_array(strtolower($p_arr['extension']), GrafFiles)) {
            $subsg = "06";
        }
        $sdir = "";
        if ($row->md_aufn_datum != "") {
            $sdir  = $row->md_aufn_datum."/";
        }
        if ($debug_log) {
            $eintragen .= "sammlg $sammlg \n";
            $eintragen .= "feuerwehr $eigner  \n";
            $eintragen .= "Foto $row->md_dsn_1 \n";
            file_put_contents('SelPictLib_debug.log', "L 0151 $eintragen" . PHP_EOL, FILE_APPEND);
        }
        
        # echo "L 0149 subsg $subsg <br>";
        # echo "L 0150 arr_entry 'dateiname' => $row->md_dsn_1, 'pfad' => AOrd_VERZ/$row->md_eigner/09/$subsg/$sdir.$row->md_dsn_1  <br>";
        $bilder[] = array('dateiname' => $row->md_dsn_1, 'pfad' => "AOrd_VERZ/$row->md_eigner/09/$subsg/$sdir$row->md_dsn_1");
        $anz_bilder ++;
    }
    # var_dump($bilder);
}
# $cnt = count($bilder);
#echo "L 0157 anzahl Einträge $cnt <br>";
#var_dump($bilder);
#exit;
if ($debug_log) {
    $eintragen .= "json ".json_encode($bilder)." \n";
    $eintragen .= "$sammlg  $eigner anz_foto $anz_bilder \n";
    file_put_contents('SelPictLib_debug.log', "L 0167 $eintragen" . PHP_EOL, FILE_APPEND);
}
echo json_encode($bilder);
# return json_encode($bilder);

# }
