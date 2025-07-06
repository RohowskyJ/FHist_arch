<?php

/**
 * Auszeichnungs- Veraltung Vereins- Auszeichnungen, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_VE_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$neu['av_fw_id'] = $_SESSION[$module]['fw_id'];

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = $path2ROOT."login/AOrd_Verz/AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['az_bild_v'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['az_bild_r'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['av_urkund_1'] = VF_Upload($uploaddir, 3);
    }
    if ($_FILES['uploaddatei_4']['name'] != "" ) {
        $neu['av_urkund_2'] = VF_Upload($uploaddir, 4);
    }
}


if ($debug) {
    echo '<pre class=debug>';
    echo 'L 129: <hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($av_id == 0) { # neueingabe
    $sql = "INSERT INTO $tabelle (
                av_fw_id,av_ab_id,av_sort,av_mat,av_beschr, av_bild_v,av_bild_r,
                av_beschr_v,av_beschr_r,
                av_urkund_1,av_urkund_2,av_aend_uid,av_aenddat
              ) VALUE (
               '$neu[av_fw_id]','$neu[av_ab_id]','$neu[av_sort]','$neu[av_mat]','$neu[av_beschr]','$neu[av_bild_v]','$neu[av_bild_r]',
               '$neu[av_beschr_v]','$neu[av_beschr_r]',
               '$neu[av_urkund_1]','$neu[av_urkund_2]','$p_uid',now()
               )";

    $result = SQL_QUERY($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "av_bild_v1") {
            continue;
        }
        if ($name == "av_bild_r2") {
            continue;
        }

        if ($name == "av_urkund_13") {
            continue;
        }
        if ($name == "av_urkund_24") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `av_id`='$av_id'";
    if ($debug) {
        echo '<pre class=debug> L 0127: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
    $fw_id = $_SESSION[$module]['fw_id'];
    header("Location: VF_PS_OV_O_Edit.php?ID=$fw_id");
}

$ab_id = $_SESSION['AUSZ']['ab_id'];
header("Location: VF_PS_OV_AD_Edit.php?ID=$ab_id");

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_VE_Edit_ph1.inc.php ist beendet</pre>";
}
?>