<?php

$debug = true;
if ($debug) {
    echo "<pre class=debug>Proj_Conf_Edit_ph1.inc.php ist gestarted</pre>";
}
# var_dump($_FILES);
$uploaddir = $path2ROOT . "login/common/imgs/";

$target1 = "";
if (! empty($_FILES['uploaddatei_01'])) {
    $pict1 = basename($_FILES['uploaddatei_01']['name']);
    if (! empty($pict1)) {
        $target1 = $uploaddir . basename($_FILES['uploaddatei_01']['name']);
        if (move_uploaded_file($_FILES['uploaddatei_01']['tmp_name'], $target1)) {
            $f_arr = pathinfo($target1);
            $neu['c_logo'] = $f_arr['basename'];
        }
    }
}
if (! empty($_FILES['uploaddatei_02'])) {
    $pict1 = basename($_FILES['uploaddatei_02']['name']);
    if (! empty($pict1)) {
        $target1 = $uploaddir . basename($_FILES['uploaddatei_02']['name']);
        if (move_uploaded_file($_FILES['uploaddatei_02']['tmp_name'], $target1)) {
            $f_arr = pathinfo($target1);
            $neu['c_1page'] = $f_arr['basename'];
        }
    }
}
/*
#$c_logo = "";
if (isset($_FILES)) {
     $_SESSION[$module]['sign'] = $c_logo = VF_Upload_Pic('sign', $path2ROOT."login/common/imgs/", "", "");
}
if (isset($_FILES['uploaddatei_1']['name'])) {

    if ($_FILES['uploaddatei_01']['name'] != "" ) {
        $neu['c_logo'] = VF_Upload($uploaddir, '01');
    }

    if ($_FILES['uploaddatei_02']['name'] != "" ) {
        $neu['c_1page'] = VF_Upload($uploaddir, '02');
    }
}
*/
console_log("logo ".$neu['c_logo']);

$updas_s = "\n[Config]\n";
$updas_m = "\n[Modules]\n"; # assignements for UPDATE xxxxx SET `variable` = 'Wert'

foreach ($neu as $name => $value) { # für alle Felder aus der tabelle
    if (! preg_match("/[^0-9]/", $name)) {
        continue;
    } # überspringe Numerische Feldnamen
    if ($name == "c_flnr") {
        continue;
    } #
    if ($name == "phase") {
        continue;
    } #
    if ($name == "MAX_FILE_SIZE") {
        continue;
    } #
    if ($name == "c_logo1") {
        continue;
    } #

    if ($name == "c_Institution") {
        $updas_s .= "inst = '$value'\n ";
    }
    if ($name == "c_Vereinsreg") {
        $updas_s .= "vreg = '$value'\n ";
    }
    if ($name == "c_Verantwortl") {
        $updas_s .= "vant = '$value'\n ";
    }
    if ($name == "c_email") {
        $updas_s .= "vema = '$value'\n ";
    }
    if ($name == "c_Ver_Tel") {
        $updas_s .= "vtel = '$value'\n ";
    }
    if ($name == "c_mode") {
        $updas_s .= "mode = '$value'\n ";
    }
    if ($name == "c_Wartung") {
        $updas_s .= "wart = '$value'\n ";
    }

    if ($name == "c_Wart_Grund") {
        $updas_s .= "warg = '$value'\n ";
    }
    if ($name == "c_Eignr") {
        $updas_s .= "eignr = '$value'\n ";
    }
    if ($name == "c_logo") {
        $updas_s .= "sign = '$value'\n ";
    }
    if ($name == "c_1page") {
        $updas_s .= "fpage = '$value'\n ";
    }
    if ($name == "c_Homepage") {
        $updas_s .= "homp = '$value'\n ";
    }
    if ($name == "c_Eigner") {
        $updas_s .= "eignr = '$value'\n ";
    }
    if ($name == "c_ptyp") {
        $updas_s .= "ptyp = '$value'\n ";
    }
    if ($name == "c_store") {
        $updas_s .= "store = '$value'\n ";
    }
    if ($name == "c_def_pw") {
        $updas_s .= "def_pw = '$value'\n ";
    }

    if ($name == "c_Module_1") {
        $updas_m .= "m_1 = '$value'\n ";
    }
    if ($name == "c_Module_2") {
        $updas_m .= "m_2 = '$value'\n ";
    }

    if ($name == "c_Module_3") {
        $updas_m .= "m_3 = '$value'\n ";
    }
    if ($name == "c_Module_4") {
        $updas_m .= "m_4 = '$value'\n ";
    }
    if ($name == "c_Module_5") {
        $updas_m .= "m_5 = '$value'\n ";
    }
    if ($name == "c_Module_6") {
        $updas_m .= "m_6 = '$value'\n ";
    }
    if ($name == "c_Module_7") {
        $updas_m .= "m_7 = '$value'\n ";
    }
    if ($name == "c_Module_8") {
        $updas_m .= "m_8 = '$value'\n ";
    }
    if ($name == "c_Module_9") {
        $updas_m .= "m_9 = '$value'\n ";
    }
    if ($name == "c_Module_10") {
        $updas_m .= "m_10 = '$value'\n ";
    }
    if ($name == "c_Module_11") {
        $updas_m .= "m_11 = '$value'\n ";
    }
    if ($name == "c_Module_12") {
        $updas_m .= "m_12 = '$value'\n ";
    }
    if ($name == "c_Module_13") {
        $updas_m .= "m_13 = '$value'\n ";
    }
    if ($name == "c_Module_14") {
        $updas_m .= "m_14 = '$value'\n ";
    }
    if ($name == "c_Module_15") {
        $updas_m .= "m_15 = '$value'\n ";
    }

} # Ende der Schleife

echo "L 0159 updas_s $updas_s <br>";
$dsn = $path2ROOT."login/common/config_s.ini";

$datei = fopen($dsn, 'w');
fputs($datei, $updas_s);
fclose($datei);

$dsn = $path2ROOT."login/common/config_m.ini";

$datei = fopen($dsn, 'w');
fputs($datei, $updas_m);
fclose($datei);

$updas = "";
foreach ($neu as $fld => $value ) {
    if ($fld == 'MAX_FILE_SIZE' || $fld == 'phase') {continue;}
    $updas .= ",$fld = '$value'";
}

$sql = "UPDATE fh_proj_config SET  c_aenduid='".$_SESSION['VF_Prim']['p_uid']."'$updas WHERE `c_flnr`='1' ";
if ($debug) {
    echo '<pre class=debug> L 0197: \$sql $sql </pre>';
}

echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
$result = SQL_QUERY($db, $sql);

if (isset($_SESSION[$module]['inst'])) {
    header("Location: ".$_SESSION[$module]['inst']);
} else {
    header("Location: ".$path2ROOT."/VFH/index.php");
}

if ($debug) {
    echo "<pre class=debug>Proj_Conf_Edit_ph1.inc.php beendet</pre>";
}
