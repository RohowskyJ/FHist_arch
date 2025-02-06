<?php 
/**
 * Auswahl der weiteren Leveln von Sammlung
 *
 * @author Josef Rohowsky - neu 2024
 *
 * Erstellen der AUswahlwerte für Sammlungen, als Antwort auf eine Erstauswahl
 * 
 * Einlesen der DB- Parameter aus der config_d.ini
 * 
 */

$ini_d = "config_d.ini";
$ini_arr = parse_ini_file($ini_d, True, INI_SCANNER_NORMAL);
# print_r($ini_arr); echo "<br>L 0239 ini_arr <br>";

$server_name = $_SERVER['SERVER_NAME'];

# echo "L 0248 server name $server_name <br>";
if (stripos($server_name, "www") || stripos($server_name, "WWW")) {
    $url_arr = explode(".", $server_name);
    $cnt_u = count($url_arr);
    $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
    # echo "l 0247 srvNam $server_name <br>";
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

}

if (isset($_GET['level'])) {
    $level = $_GET['level'];
}
if (isset($_GET['parent'])) {
    $parent = trim($_GET['parent']);
    if (substr($parent,0,2) == "' ") {
        $parent = substr($parent,2);
    }
    # echo "L064 .$parent. <br>";
}
#echo "L008 level $level parent  $parentValue<br>";

if (isset($_GET['opval'])) {
    $Opt_Src = $_GET['opval'];
}

$lvl_arr = explode("-",$parent);
$l_cnt = count($lvl_arr);

if ($Opt_Src == 1) {
    
    $sql = "SELECT * FROM fh_sammlung WHERE sa_sammlg LIKE '%$parent%' ";
    # echo $sql;
    $return = mysqli_query($db,$sql);
    $response = "Nix: Auswählen |";
    if ($return) {
        WHILE ($row = mysqli_fetch_object($return)) {
            $d_arr = explode("-",$row->sa_sammlg);
            $d_cnt = count($d_arr);
            if ($d_cnt == $l_cnt +1) {
                $response .= "$row->sa_sammlg:$row->sa_name|" ;
            }
        }
    } else {
        $response = "Nix: Keine Daten";
    }
    
} elseif ($Opt_Src == 2) {
    
    if (isset($level) ) {
        if ($level == 1) {
            $sql = "SELECT * FROM ar_chivord WHERE ar_sg = '$parent' AND  ar_sub_sg!='0' "; # 1. Level
            $return = mysqli_query($db,$sql);
            $response = "Nix: Auswählen|";
            if ($return) {
                WHILE ($row = mysqli_fetch_object($return)) {
                    $response .= "$row->ar_sg.$row->ar_sub_sg : $row->ar_sgname|" ;
                }
            } else {
                $response = "'Nix': Keine Daten|";
            }
        } elseif ($level == 2)  {
            $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$parent' AND al_lcsg >'00' AND al_lcssg = '00' "; # 3. Level
            $return = mysqli_query($db,$sql);
            $response = "Nix: Auswählen|";
            if ($return) {
                WHILE ($row = mysqli_fetch_object($return)) {
                    $response .= "$row->al_sg $row->al_lcsg:$row->al_bezeich|" ;
                }
            } else {
                $response = "'Nix': Keine Daten|";
            }
        } elseif ($level == 3)  {
            $sg_arr = explode(" ",$parent);
            $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$sg_arr[0]' AND al_lcsg ='$sg_arr[1]' AND al_lcssg > '00' AND al_lcssg_s0='00' "; # 3. Level
            $return = mysqli_query($db,$sql);
            $response = "Nix: Auswählen|";
            if ($return) {
                WHILE ($row = mysqli_fetch_object($return)) {
                    $response .= "$row->al_sg $row->al_lcsg $row->al_lcssg:$row->al_bezeich|" ;
                }
            } else {
                $response = "'Nix': Keine Daten|";
            }
        } elseif ($level == 4)  {
            $sg_arr = explode(" ",$parent);
            $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$sg_arr[0]' AND al_lcsg ='$sg_arr[1]' AND al_lcssg = '$sg_arr[2]' AND al_lcssg_s0>'00'  AND al_lcssg_s1='00' "; # 3. Level
            $return = mysqli_query($db,$sql);
            $response = "Nix: Auswählen|";
            if ($return) {
                WHILE ($row = mysqli_fetch_object($return)) {
                    $response .= "$row->al_sg $row->al_lcsg $row->al_lcssg $row->al_lcssg_s0:$row->al_bezeich|" ;
                }
            } else {
                $response = "'Nix': Keine Daten|";
            }
        } elseif ($level == 5)  {
            $sg_arr = explode(" ",$parent);
            $sql = "SELECT * FROM ar_ord_local WHERE al_sg = '$sg_arr[0]' AND al_lcsg ='$sg_arr[1]' AND al_lcssg = '$sg_arr[2]' AND al_lcssg_s0='$sg_arr[3]'  "; # 3. Level  AND al_lcssg_s1='00'
            $return = mysqli_query($db,$sql);
            $response = "Nix: Auswählen|";
            if ($return) {
                WHILE ($row = mysqli_fetch_object($return)) {
                    $response .= "$row->al_sg $row->al_lcsg $row->al_lcssg $row->al_lcssg_s0 $row->al_lcssg_s1:$row->al_bezeich|" ;
                }
            } else {
                $response = "'Nix': Keine Daten|";
            }
        }
    }

}
if ($response != "") {
    echo $response;
}


?>
