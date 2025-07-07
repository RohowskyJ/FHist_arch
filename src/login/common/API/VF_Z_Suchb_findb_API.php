<?php
$path2ROOT = "../";

$debug_log = false;

if ($debug_log) {file_put_contents('Suchb_findb.log', "VF_Z_Suchb_findb.API L 007 " . PHP_EOL, FILE_APPEND);}

/* für PHP-Logging

 ini_set('display_errors', '1');
 ini_set("log_errors", 1);
 ini_set("error_log", "Findbuch_php-error.log");
 error_log( "Hello, errors!" );
 */

$debug_log = false;

if ($debug_log) {file_put_contents('Findb_debug.log', "VF_Z_Suchb_findb.API L 018 " . PHP_EOL, FILE_APPEND);}

# echo "findb gestartet";
$ini_d = $path2ROOT . "config_d.ini";
$ini_arr = parse_ini_file($ini_d, True, INI_SCANNER_NORMAL);

$server_name = $_SERVER['SERVER_NAME'];

if (stripos($server_name, "www") || stripos($server_name, "WWW")) {
    $url_arr = explode(".", $server_name);
    $cnt_u = count($url_arr);
    $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
}

if (isset($ini_arr)) { # (isset($ini_arr[$server_name])){
    if ($server_name == 'localhost') {
        if (isset($ini_arr[$server_name])) {
            $dbhost = $ini_arr[$server_name]['l_dbh'];
            $dbuser = $ini_arr[$server_name]['l_dbu'];
            $dbpass = $ini_arr[$server_name]['l_dbp'];
            $database = $ini_arr[$server_name]['l_dbn'];
        }
    } else {
        if (stripos($server_name, "www") || stripos($server_name, "WWW")) {
            $url_arr = explode(".", $server_name);
            $cnt_u = count($url_arr);
            $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
            # echo "l 0247 srvNam $server_name <br>";
        }
        $server_name = "HOST";
        if (isset($ini_arr[$server_name])) {
            $dbhost = $ini_arr[$server_name]['h_dbh'];
            $dbuser = $ini_arr[$server_name]['h_dbu'];
            $dbpass = $ini_arr[$server_name]['h_dbp'];
            $database = $ini_arr[$server_name]['h_dbn'];
        }
    }
    
    $db = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Verbindung zu MySQL gescheitert!' . mysqli_connect_error());
    
    mysqli_select_db($db, $database) or die("Datenbankzugriff zu $database gescheitert!");
    mysqli_set_charset($db, 'utf8');
    $LinkDB_database = $database; # wird in Funktion Tabellen_Spalten_v2.php verwendet
}

if ($debug_log) {file_put_contents('Findb_debug.log', "VF_Z_Suchb_findb.API L 063 DB verbunden " . PHP_EOL, FILE_APPEND);}

require $path2ROOT . 'BA_Funcs.lib.php'; // Diverse allgemeine Unterprogramme
require $path2ROOT . 'VF_Comm_Funcs.lib.php';

setlocale(LC_CTYPE, "de_AT"); // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$line = "";

$line .= date("Y-d-m h:i:s")."\n";


# $ar_arr, $dm_arr, $maf_arr, $mag_arr, $muf_arr, $mug_arr, $in_arr, $zt_arr, $mar_arr,
$ar_arr = array();
$dm_arr = array();
$in_arr = array();
$maf_arr = array();
$mag_arr  = array();
$muf_arr  = array();
$mug_arr  = array();
$ge_arr = array();
$zt_arr = array();
$tables_act = VF_tableExist();

$eignr = $na_eign = "";

$line = "<b>Reorganisieren Findbuch fh_findbuch </b>\n";
// loeschen alte DB Saetze
$sql_del = "TRUNCATE `fh_findbuch`";
$return_del = mysqli_query($db, $sql_del) or die("Datensatzlöschen gescheitert fh_findbuch fehlgeschlagen.<br/>\$sql_del $sql_del <br/>");

$fld = "ad_keywords";
$find_sum = $find_total = 0;

if ($debug_log) {file_put_contents('Findb_debug.log', "L 0100 vor ar analyse " . PHP_EOL, FILE_APPEND);}

foreach ($ar_arr as $ar_table => $ar) {
    
    if ($debug_log) {file_put_contents('Findb_debug.log', "L 014 var_table $ar_table ar $ar  " . PHP_EOL, FILE_APPEND);} 
    
    if ($ar_table == "ar_chivord" or $ar_table == "ar_chivdt" or $ar_table == "ar_chivdt_" or $ar_table == "ar_ch_verl" or $ar_table == "ar_ord_local" ) {
        continue;
    }
     
    $ei_ar  = explode("_",$ar_table);
    $eignr = $ei_ar[2];
    
    $sql_in = "SELECT * FROM `$ar_table` ORDER BY ad_id ";
   
    if ($debug_log) {file_put_contents('Findb_debug.log', "L 0111 ar_table $ar_table " . PHP_EOL, FILE_APPEND);}
    
    $return_in = SQL_QUERY($db, $sql_in);
    $num_recs = mysqli_num_rows($return_in);
    if ($debug_log) {file_put_contents('Findb_debug.log', "VF_Z_Suchb_findb.API L 0115 ANz Sätzr $num_recs " . PHP_EOL, FILE_APPEND);}
    $find_sum = 0;
    $i = 0;
    while ($row = mysqli_fetch_object($return_in)) {
        if ($row->ad_keywords != " ") {
            $fdid = $row->ad_id;
            
            $keywords = $row->ad_keywords;
            if ($keywords == "") {
                continue;
            }
            $find_arr = explode(",", $row->ad_keywords);
            $find_cnt = count($find_arr);
            $find_sum += $find_cnt;
            foreach ($find_arr as $key) {
                $key = trim($key);
                $sql_fi = "SELECT   * FROM `fh_findbuch` WHERE `fi_table`='$ar_table' AND `fi_fldname`='$fld' AND `fi_fdid`='$fdid' AND `fi_suchbegr`='$key'";
                $return_fi = mysqli_query($db, $sql_fi) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
                $recnum = mysqli_num_rows($return_fi);
               
                if ($recnum == 0) {
                    $sql_fb = "INSERT INTO `fh_findbuch` (`fi_table`, `fi_fldname`,
                                      `fi_fdid`, `fi_suchbegr`, `fi_suchbegr_all`, `fi_eigner`
                                      ) VALUES
                                         ('$ar_table','$fld','$fdid','$key', '$keywords', '$eignr'
                                           )";
                    
                    $return_fb = SQL_QUERY($db, $sql_fb) or die("Datenbankabfrage gescheitert. <br/>$sql_fb <br/>" . mysqli_error($db));
                }
            }
            
        }
        if ($debug_log) {file_put_contents('Findb_debug.log', "L 063 anzahl der Sätze mit Eintragungen $i" . PHP_EOL, FILE_APPEND);}
        $i++;
    }
    $line .= "<b>Die Datei $ar_table wurde eingelesen und nach Suchegriffen analysiert. $find_sum Suchbegriffe wurden analysiert.<br>\n";
    $find_total += $find_sum;
}


if ($debug_log) {file_put_contents('Findb_debug.log', "L 0159 vor dm analyse " . PHP_EOL, FILE_APPEND);}

$fld = "md_suchbegr";
# $find_sum = $find_total = 0;
foreach ($dm_arr as $fo_table => $dm) { 
    
    if ($debug_log) {file_put_contents('Findb_debug.log', "L 0165 fo_table $fo_table dm $dm " . PHP_EOL, FILE_APPEND);}
    
    $sql_in = "SELECT * FROM `$fo_table` ORDER BY md_id ";
    
    if ($debug_log) {file_put_contents('Findb_debug.log', "L 0165 Tabelle $fo_table wird eingelesen" . PHP_EOL, FILE_APPEND);}
    
    $return_in = SQL_QUERY($db, $sql_in);
    
    $num_recs = mysqli_num_rows($return_in);
    if ($debug_log) {file_put_contents('Findb_debug.log', "L 063 anzahl der Datensätze $num_recs " . PHP_EOL, FILE_APPEND);}
    
    $find_sum = 0;
    while ($row = mysqli_fetch_object($return_in)) {
        if ($row->md_suchbegr != " ") {
            $fdid = $row->md_id;
            
            $ei_ar  = explode("_",$fo_table);
            $eignr = $ei_ar[2];
            
            $keywords = $row->md_suchbegr;
            if ($keywords == "") {
                continue;
            }
            $find_arr = explode(",", $row->md_suchbegr);
            $find_cnt = count($find_arr);
            $find_sum += $find_cnt;
            foreach ($find_arr as $key) {
                $key = trim($key);
                $sql_fi = "SELECT   * FROM `fh_findbuch` WHERE `fi_table`='$fo_table' AND `fi_fldname`='$fld' AND `fi_fdid`='$fdid' AND `fi_suchbegr`='$key'";
               
                $return_fi = mysqli_query($db, $sql_fi) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
                $recnum = mysqli_num_rows($return_fi);
                
                if ($recnum == 0) {
                    $sql_fb = "INSERT INTO `fh_findbuch` (`fi_table`, `fi_fldname`,
                                      `fi_fdid`, `fi_suchbegr`, `fi_suchbegr_all`, `fi_eigner`
                                      ) VALUES
                                         ('$fo_table','$fld','$fdid','$key', '$keywords', '$eignr'
                                           )";
                    $return_fb = SQL_QUERY($db, $sql_fb) or die("Datenbankabfrage gescheitert. <br/>$sql_fb <br/>" . mysqli_error($db));
                }
            }
        }
    }
    
    $line .= "<b>Die Datei $fo_table wurde eingelesen und nach Suchegriffen analysiert. $find_sum Suchbegriffe wurden analysiert.<br>\n";
    $find_total += $find_sum;
}


$line .= date("Y-d-m h:i:s")."\n<b>Findbuch- Regenerierung abgeschlossen. <i>$find_total</i> Suchbegriffe wurden registriert.</b>\n";

echo $line;
?>
