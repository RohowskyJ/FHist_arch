<?php
ob_clean();
header('Content-Type: application/json');

require "../BA_Funcs.lib.php";
require "../VF_Comm_Funcs.lib.php";
require "../VF_Const.lib.php";

$path2ROOT = "../../../";
$module = "";

$debug = false;
$VS_err_log = "VF_SelPict_php-error.log.txt";
/* für PHP-Logging 
ini_set('display_errors', '1');
ini_set("log_errors", 1);
ini_set("error_log", $VS_err_log);
error_log( "Hello, errors!" );
*/ 
$debug_log = false;
$VS_debug_log = "VF_SelPictLib_Debug.log.txt";
if ($debug_log) {
    file_put_contents($VS_debug_log, "SF_SelPictLib.API L 007 " . PHP_EOL, FILE_APPEND);
}

# if ($_SERVER['REQUEST_METHOD'] === 'GET') { #) { # $_SERVER['REQUEST_METHOD'] === 'POST' ) { #

$eintragen = Date("Y-m-d H:i:s") . "\n";

$sammlg = $_POST['sammlg'] ?? '';
$eigner = $_POST['eigner'] ?? '';
$aufndat = $_POST['aufnDat'] ?? '';
$berID    = $_POST['berID'] ?? '0';

/**  Test- Parms für direkt- Aufruf 
  $sammlg = "Ma_F-L";
  $eigner = "21"; #"Wiener Neudorf";
  $aufndat = '20250504';
  $berID    = '1';
*/

// Dateien
if ($sammlg == '' && $eigner == '' && $aufndat == '') {
    echo json_encode(['status' => 'error', 'message' => 'Keine Daten empfangen.']);
    ob_end_flush();
    exit;
}

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
    file_put_contents($VS_debug_log, "L 040 ini_d $ini_d " . PHP_EOL, FILE_APPEND);
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
        # file_put_contents($VS_debug_log, "L 066 $dbhost $dbuser $database " . PHP_EOL, FILE_APPEND);
        
        $db = $dblink = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Verbindung zu MySQL gescheitert!' . mysqli_connect_error());
        
        mysqli_select_db($dblink, $database) or die("Datenbankzugriff zu $database gescheitert!");
        mysqli_set_charset($dblink, 'utf8mb4');
        $LinkDB_database = $database; # wird in Funktion Tabellen_Spalten_v2.php verwendet

} else {
    echo json_encode(['status' => 'error', 'message' => 'Datenbank kann nicht geöffnet werden - Abbruch.']);
    ob_end_flush();
    exit;
}

// für direkt- Aufruf auskommentiert
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [
        'status' => 'ok',
        'files' => [],
        'errors' => []
    ];
    
    $basispfad = "../../AOrd_Verz/";
    
    if ($debug_log) {
        $eintragen .= "sammlg $sammlg \n";
        $eintragen .= "eigner $eigner  \n";
        $eintragen .= "aufndat $aufndat\n";
        $eintragen .= "berPhase $berPhase\n";
        $eintragen .= "Ber- Nr $berID\n";
        file_put_contents($VS_debug_log, "L 0113 $eintragen" . PHP_EOL, FILE_APPEND);
    }
    $used_arr = array();
    if ($berPhase == 'addNew') { // Einlesen der Bilder, die bereits benutzt werden
        $sql_usd = "SELECT  vd_foto FROM vb_ber_detail_4 WHERE vb_flnr='$berID' ";
        $res_usd = SQL_QUERY($db,$sql_usd);
        if ($res_usd ) {
            WHILE ($row_usd = mysqli_fetch_object($res_usd)) {
                $used_arr[] = $row_usd->vd_foto;
            }
        }
        # var_dump($used_arr);
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
            
            if ($aufndat != "") {
                $where = " WHERE md_aufn_datum = '$aufndat' ";
            } else {
                $where = " WHERE md_sammlg LIKE '%$sammlg%'  AND  md_fw_id LIKE '%$eigner%' ";
            }
            
            $sql = "SELECT * FROM $dsn $where ";
            
            file_put_contents($VS_debug_log, "L 0135 sql $sql " . PHP_EOL, FILE_APPEND);
            
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
                file_put_contents($VS_debug_log, "L 0152 $eintragen" . PHP_EOL, FILE_APPEND);
                
                $p_arr = pathinfo($row->md_dsn_1);
                $subsg = "";
                if (in_array(strtolower($p_arr['extension']), GrafFiles)) {
                    $subsg = "06";
                }
                $sdir = "";
                if ($row->md_aufn_datum != "") {
                    $sdir  = $row->md_aufn_datum."/";
                }
                $foto = $row->md_dsn_1;
                $flnr = 0;
                $page  = 0;
                $pos = 0;
                $titel = '';
                $beschreibg = '';
                if ($berID > 0) {
                    
                    $sql_upd = "SELECT * FROM vb_ber_detail_4 WHERE vb_flnr='$berID' AND vd_foto = '$foto' " ;
                    $res_upd =  SQL_QUERY($db,$sql_upd);
                    if ($res_upd) {
                        $num_recs = mysqli_num_rows($res_upd);
                        WHILE ($row_upd = mysqli_fetch_object($res_upd)) {
                            $flnr = $row_upd->vd_flnr;
                            $page = $row_upd->vd_unter;
                            $pos = $row_upd->vd_suffix;
                            $titel = $row_upd->vd_titel;
                            $beschreibg = $row_upd->vd_beschreibung;
                        }
                    }
                    if ($beschreibg == '') {
                        $beschreibg = $row->md_beschreibg;
                    }
                }
                
                $response['files'][] = [
                    'flNr' => $flnr,
                    'page' => $page,
                    'pos' => $pos,
                    'titel' => $titel,
                    'dateiname' => $row->md_dsn_1,
                    'pfad' => "AOrd_Verz/$row->md_eigner/09/$subsg/$sdir$row->md_dsn_1",
                    'beschreibung' => $row->md_beschreibg
                ];
            }
        }
        # var_dump($bilder);
   
    if ($debug_log) {
        file_put_contents($VS_debug_log, "L 0178 ".json_encode($response) . PHP_EOL, FILE_APPEND);
    }
    ob_clean();
    echo json_encode($response);
    exit;
    
// }
    echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage.']);
    exit;