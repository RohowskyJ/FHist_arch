<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
/*
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", "php-error_Multisel.log.txt");
error_log( "Hello, errors!" );
*/
$debug_log = true;
$debug_log_dsn ="MultiSel_debug.log.txt";
if ($debug_log) {
    file_put_contents($debug_log_dsn, "API L 018 " . PHP_EOL, FILE_APPEND);
}

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

$path2ROOT = "../../../";
$module = "";
$debug = false;
$debug_log = true;

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
    file_put_contents($debug_log_dsn, "L 051 ini_d $ini_d " . PHP_EOL, FILE_APPEND);
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
        # file_put_contents($debug_log_dsn, "L 066 $dbhost $dbuser $database " . PHP_EOL, FILE_APPEND);
        
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

// Parameter
$level = isset($_GET['level']) ? intval($_GET['level']) : 0;
$parent = isset($_GET['parent']) ? trim($_GET['parent']) : "";
$opval = isset($_GET['opval']) ? $_GET['opval'] : '1';

if ($debug_log) {
    file_put_contents($debug_log_dsn, "API L 0106 level $level; parent $parent; opval $opval" . PHP_EOL, FILE_APPEND);
}

$data[] = ['value' => '', 'text' => 'Bitte auswählen' ,];

if ($opval == '1') {
    // Beispiel: Sammlung (1. Ebene)
    if ($debug_log) {
        file_put_contents($debug_log_dsn, "API L 0114 opval = 1" . PHP_EOL, FILE_APPEND);
    }
    # error_log("opval da");
    $sql = "SELECT * FROM fh_sammlung WHERE sa_grup LIKE '$parent' ORDER BY sa_sammlg ASC";
    $result_set = mysqli_query($db, $sql);

    if ($result_set) {
        while ($row = mysqli_fetch_object($result_set)) {

            $data[] = [
                'value' => $row->sa_sammlg,
                'text' => $row->sa_name,
            ];
            if ($debug_log) {
                file_put_contents($debug_log_dsn, "API L 0128 parent $parent;sa_grup $row->sa_grup; sa_sammlg $row->sa_sammlg; txt $row->sa_name sql $sql " . PHP_EOL, FILE_APPEND);
            }
             error_log("parent $parent;sa_grup $row->sa_grup; sa_sammlg $row->sa_sammlg; txt $row->sa_name ");
        }
    }

} elseif ($opval == '2') {
    // Beispiel: Archivordnung
    if ($level == 1) {
        $sql = "SELECT * FROM ar_chivord WHERE ar_sg = '$parent' AND ar_sub_sg != '0'";
        if ($debug_log) {
            file_put_contents($debug_log_dsn, "API L 0139 sql $sql " . PHP_EOL, FILE_APPEND);
        }
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
    } elseif ($level == 3) {
        $p_arr = explode(' ',$parent) ;
        $parent = $p_arr[0];
        $lcsg = $p_arr[1];
        $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$parent' AND al_lcsg = $lcsg AND al_lcssg>'00' AND al_lcssg_s0='00' ";
        if ($debug_log) {
            file_put_contents($debug_log_dsn, "API L 0166 parent $parent; sql $sql" . PHP_EOL, FILE_APPEND);
        }
        $resq = mysqli_query($db, $sql);
        if ($resq) {
            while ($row = mysqli_fetch_object($resq)) {
                $value = "$row->al_sg $row->al_lcsg $row->al_lcssg";
                $text = $row->al_bezeich;
                $data[] = ['value' => $value, 'text' => $text];
            }
        }
    } elseif ($level == 4) {
        $p_arr = explode(' ',$parent) ;
        $parent = $p_arr[0];
        $lcsg = $p_arr[1];
        $lcssg = $p_arr[2];
        $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$parent' AND al_lcsg = $lcsg AND al_lcssg='$lcssg' AND al_lcssg_s0>'00' AND al_lcssg_s1='00' ";
        if ($debug_log) {
            file_put_contents($debug_log_dsn, "API L 0166 parent $parent; sql $sql" . PHP_EOL, FILE_APPEND);
        }
        $resq = mysqli_query($db, $sql);
        if ($resq) {
            while ($row = mysqli_fetch_object($resq)) {
                $value = "$row->al_sg $row->al_lcsg $row->al_lcssg $row->al_lcssg_s0";
                $text = $row->al_bezeich;
                $data[] = ['value' => $value, 'text' => $text];
            }
        }
    } elseif ($level == 5) {
        $p_arr = explode(' ',$parent) ;
        $parent = $p_arr[0];
        $lcsg = $p_arr[1];
        $lcssg = $p_arr[2];
        $lcssg_s0 = $p_arr[3];
        $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$parent' AND al_lcsg = $lcsg AND al_lcssg='$lcssg' AND al_lcssg_s0='$lcssg_s0' AND al_lcssg_s1>'00' "; #AND al_lcssg_s1>'00'
        if ($debug_log) {
            file_put_contents($debug_log_dsn, "API L 0166 parent $parent; sql $sql" . PHP_EOL, FILE_APPEND);
        }
        $resq = mysqli_query($db, $sql);
        if ($resq) {
            while ($row = mysqli_fetch_object($resq)) {
                $value = "$row->al_sg $row->al_lcsg $row->al_lcssg $row->al_lcssg_s0 $row->al_lcssg_s1";
                $text = $row->al_bezeich;
                $data[] = ['value' => $value, 'text' => $text];
            }
        }
    }
   
}
$ret = json_encode([
    'status' => 'ok',
    'data' => $data,
    'error' => null,
]);
error_log("L 0160: respond: $ret");
echo json_encode([
    'status' => 'ok',
    'data' => $data,
    'error' => null,
]);

ob_end_flush();
