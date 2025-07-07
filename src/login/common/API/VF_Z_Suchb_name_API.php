<?php
$path2ROOT = "../";

$debug = False;
$debug_log = False;

if ($debug_log) {file_put_contents('Suchb_name.log', "VF_Z_Suchb_name.API L 007 " . PHP_EOL, FILE_APPEND);}


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

echo "<b>Reorganisieren Namens- Findbuches fh_find_namen </b><br>";

// loeschen alte DB Saetze
$sql_del = "TRUNCATE `fh_find_namen`";
$return_del = SQL_QUERY($db, $sql_del);

$fld = "fo_id";

// einlesen der Archivdaten in Arr
$arc_arr = "";

$fld = "md_namen";
$find_sum = $find_total = 0;

foreach ($dm_arr as $fo_table => $dm_key) {

    # print_r($return_in);echo "<br> return_in sql_in $sql_in <br>";
    $sql_foto = "SELECT *  FROM `$fo_table`  ORDER BY `md_id`";
    $return_foto = SQL_QUERY($db, $sql_foto);
    $find_sum = 0;
    while ($row = mysqli_fetch_object($return_foto)) {
        if ($row->md_namen != " ") {
            $fdid = $row->md_id;
            if ($row->md_namen == "") {
                continue;
            }
            $md_namen = $row->md_namen;
            $find_arr = explode(",", $row->md_namen);
            $find_cnt = count($find_arr);
            $find_sum += $find_cnt;
            
            foreach ($find_arr as $key) {
                $key = trim($key);
                $sql_fi = "SELECT   * FROM `fh_find_namen` WHERE `na_table`='$fo_table' AND `na_fldname`='$fld' AND `na_fdid`='$fdid' AND `na_name`='$key'";
                $return_fi = SQL_QUERY($db, $sql_fi);
                $recnum = mysqli_num_rows($return_fi);
                if ($recnum == 0) {
                    $sql_fb = "INSERT INTO `fh_find_namen` (`na_table`, `na_fldname`,
                                      `na_fdid`, `na_name`, `na_eigner`
                                ) VALUES
                                  ('$fo_table','$fld','$fdid','$key','$eignr'
                                      )";
                    $return_fb = SQL_QUERY($db, $sql_fb);
                }
            }
        }
    }
    #echo "<b>Die Datei $fo_table wurde eingelesen und nach Namen analysiert.<br></b>";
    $line .= "<b>Die Datei $fo_table wurde eingelesen und nach Namen analysiert. $find_sum Suchbegriffe wurden analysiert.</b>\n";
    $find_total += $find_sum;
    # }
}

// einlesen der Archivdaten in Arr
$fld = "in_id";
foreach ($in_arr as $in_table => $in_key) {
    if (substr($in_table, 0, 10) == "in_vent_ve") {
        continue;
    }
    $sql_inv = "SELECT *  FROM `$in_table`  ORDER BY `in_id`";
    $return_in = mysqli_query($db, $sql_inv);
    $find_sum = 0;
    while ($row = mysqli_fetch_object($return_in)) {
        if ($row->in_namen != " ") {
            $fdid = $row->in_id;
            if ($row->in_namen == "") {
                continue;
            }
            $in_namen = $row->in_namen;
            $find_arr = explode(",", $row->in_namen);
            $find_cnt = count($find_arr);
            $find_sum += $find_cnt;
            foreach ($find_arr as $key_n) {
                $key = trim($key_n);
                $sql_fi = "SELECT   * FROM `fh_find_namen` WHERE `na_table`='$in_table' AND `na_fldname`='$fld' AND `na_fdid`='$fdid' AND `na_name`='$key_n'";
                $return_fi = mysqli_query($db, $sql_fi) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
                $recnum = mysqli_num_rows($return_fi);
                if ($recnum == 0) {
                    $sql_fb = "INSERT INTO `fh_find_namen` (`na_table`, `na_fldname`,
                                      `na_fdid`, `na_name`, `na_eigner`
                                ) VALUES
                                     ('$in_table','$fld','$fdid','$key_n','$eignr'
                                       )";
                    $return_fb = mysqli_query($db, $sql_fb) or die("Datenbankabfrage gescheitert. <br/>$sql_fb <br/>" . mysqli_error($db));
                }
            }
        }
    }
    #echo "<b>Die Datei $in_table wurde eingelesen und nach Namen analysiert.<br></b>";
    $line .= "<b>Die Datei $in_table wurde eingelesen und nach Namen analysiert. $find_sum Suchbegriffe wurden analysiert.</b>\n";
    $find_total += $find_sum;
}

$fld = "ad_namen";
$find_sum = 0;
foreach ($ar_arr as $ar_table => $ar_key) {
    if (substr($ar_table, 0, 10) == "ar_chivord" or $ar_table == "ar_chivdt" or $ar_table == "ar_chivdt_" or $ar_table == "ar_ch_verl" or $ar_table == "ar_ord_local") {
        continue;
    }
    
    $sql_in = "SELECT * FROM `$ar_table` ORDER BY ad_id ";
    $return_in = SQL_QUERY($db, $sql_in);
    $find_sum = 0;
    while ($row = mysqli_fetch_object($return_in)) {
        if ($row->ad_namen != " ") {
            $fdid = $row->ad_id;
            
            if ($row->ad_namen == "") {
                continue;
            }
            $find_arr = explode(",", $row->ad_namen);
            $find_cnt = count($find_arr);
            $find_sum += $find_cnt;
            foreach ($find_arr as $key_n) {
                $key = trim($key_n);
                
                $sql_fi = "SELECT   * FROM `fh_find_namen` WHERE `na_table`='$ar_table' AND `na_fldname`='$fld' AND `na_fdid`='$fdid' AND `na_name`='$key_n'";
                $return_fi = SQL_QUERY($db, $sql_fi) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
                $recnum = mysqli_num_rows($return_fi);
                if ($recnum == 0) {
                    $sql_fb = "INSERT INTO `fh_find_namen` (`na_table`, `na_fldname`,
                                      `na_fdid`, `na_name`, `na_eigner`
                                ) VALUES
                                     ('$ar_table','$fld','$fdid','$key_n','$eignr'
                                       )";
                    $return_fb = mysqli_query($db, $sql_fb) or die("Datenbankabfrage gescheitert. <br/>$sql_fb <br/>" . mysqli_error($db));
                }
            }
        }
    }
    
    #echo "<b>Die Datei $ar_table wurde eingelesen und nach Suchegriffen analysiert.</b><br>";
    $line .= "<b>Die Datei $ar_table wurde eingelesen und nach Suchegriffen analysiert. $find_sum Suchbegriffe wurden analysiert.</b>\n";
    $find_total += $find_sum;
}

$line .=  date("Y-d-m h:i:s")."\n<b>Namens- Findbuch- Regenerierung abgeschlossen. <i>$find_total</i> Suchbegriffe wurden registriert.</b>\n";

// Verbindung schließen
$db->close();

// Header für JSON setzen
#header('Content-Type: application/json');

// JSON-Daten ausgeben
echo $line;
?>
