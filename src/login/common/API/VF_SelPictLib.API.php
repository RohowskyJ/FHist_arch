<?php

header('Content-Type: application/json');

require "../BA_Funcs.lib.php";
require "../VF_Comm_Funcs.lib.php";
require "../VF_Const.lib.php";

/* für PHP-Logging */
 ini_set('display_errors', '1');
 ini_set("log_errors", 1);
 ini_set("error_log", "SelPict_php-error.log");
 error_log( "Hello, errors!" );
/* */ 
$path2ROOT = "../../../";
$module = "";
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

$dbhost = "";
$dbuser = "";
$dbpass = "";
$database = "";

    $ini_s = "../config_s.ini";
    $ini_s_arr = parse_ini_file($ini_s, true, INI_SCANNER_NORMAL);
    $hompg = $ini_s_arr['Config']['homp'];
    $ini_d = "../config_d.ini";
    $ini_arr = parse_ini_file($ini_d, true, INI_SCANNER_NORMAL);
    # print_r($ini_s_arr); echo "<br>L 0251 ini_s_arr $hompg <br>";
    file_put_contents('SelPictLib_debug.log', "L 040 ini_d $ini_d " . PHP_EOL, FILE_APPEND);
    $server_name = $_SERVER['SERVER_NAME'];
    
    if (isset($ini_arr)) {
        if ($server_name == 'localhost') {
            if (isset($ini_arr[$server_name])) {
                $dbhost = $ini_arr[$server_name]['l_dbh'];
                $dbuser = $ini_arr[$server_name]['l_dbu'];
                $dbpass = $ini_arr[$server_name]['l_dbp'];
                $database = $ini_arr[$server_name]['l_dbn'];
            }
        } else {
            $s_a =  explode(".",$server_name);
            $cnt_s = count($s_a);
            $s_c =  explode(".",$hompg);
            $cnt_s = count($s_c);
            if ($cnt_s < $cnt_c) {
                if ($s_a[$cnt_s-2] == $s_c[$cnt_c-2]) {
                    $server_name = "HOST";
                }
            }
            $dbhost = $ini_arr[$server_name]['h_dbh'];
            $dbuser = $ini_arr[$server_name]['h_dbu'];
            $dbpass = $ini_arr[$server_name]['h_dbp'];
            $database = $ini_arr[$server_name]['h_dbn'];
        }
        # file_put_contents('SelPictLib_debug.log', "L 066 $dbhost $dbuser $database " . PHP_EOL, FILE_APPEND);
        
        $db = $dblink = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Verbindung zu MySQL gescheitert!' . mysqli_connect_error());
        
        mysqli_select_db($dblink, $database) or die("Datenbankzugriff zu $database gescheitert!");
        mysqli_set_charset($dblink, 'utf8mb4');
        $LinkDB_database = $database; # wird in Funktion Tabellen_Spalten_v2.php verwendet

} else {
    $result['status'] = 'error';
    $result['error'] = 'Konfigurations- Datei nicht gefunden ';
    echo json_encode($result);
    ob_end_flush();
    exit;
}


$db = $dblink = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Verbindung zu MySQL gescheitert!' . mysqli_connect_error());

mysqli_select_db($dblink, $database) or die("Datenbankzugriff zu $database gescheitert!");
mysqli_set_charset($dblink, 'utf8mb4');
$LinkDB_database = $database; # wird in Funktion Tabellen_Spalten_v2.php verwendet

$basispfad = "../../AOrd_Verz/";

if ($debug_log) {
    $eintragen .= "sammlg $sammlg \n";
    $eintragen .= "feuerwehr $eigner  \n";
    file_put_contents('SelPictLib_debug.log', "L 087 $eintragen" . PHP_EOL, FILE_APPEND);
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

    $where = " WHERE md_sammlg LIKE '%$sammlg%'  AND  md_feuerwehr LIKE '%$eigner%' ";

    $sql = "SELECT * FROM $dsn $where ";
   
    file_put_contents('SelPictLib_debug.log', "L 0108 sql $sql " . PHP_EOL, FILE_APPEND);

    $return = SQL_QUERY($db, $sql);
    if (mysqli_num_rows($return) === 0) {
        if (strlen($sammlg) > 4 ) {
            $s_samm = substr($sammlg,0,4);
            $sql = "SELECT * FROM $dsn WHERE  md_feuerwehr LIKE '%$eigner%'  "; // md_sammlg like '%s_samm%' AND 
            $return = SQL_QUERY($db,$sql);
        }
    }
    while ($row = mysqli_fetch_object($return)) {
        # print_r($row);echo "<br>";
        if ($row->md_dsn_1 == "") {
            continue;
        }
        
        $eintragen = "$sammlg  row $row->md_dsn_1 \n";
        file_put_contents('SelPictLib_debug.log', "L 0125 $eintragen" . PHP_EOL, FILE_APPEND);
        
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
            file_put_contents('SelPictLib_debug.log', "L 0140 $eintragen" . PHP_EOL, FILE_APPEND);
        }

        $bilder[] = array('dateiname' => $row->md_dsn_1, 'pfad' => "AOrd_Verz/$row->md_eigner/09/$subsg/$sdir$row->md_dsn_1");
        $anz_bilder ++;
    }
    # var_dump($bilder);
}

if ($debug_log) {
    $eintragen .= "json ".json_encode($bilder)." \n";
    $eintragen .= "$sammlg  $eigner anz_foto $anz_bilder \n";
    file_put_contents('SelPictLib_debug.log', "L 0152 $eintragen" . PHP_EOL, FILE_APPEND);
}
echo json_encode($bilder);
# return json_encode($bilder);

# }
