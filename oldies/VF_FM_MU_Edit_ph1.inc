<?php
/**
 * Liste der Geräte eines Eigentümers, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky  neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph1.inc ist gestarted</pre>";
}
#
foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

if (!isset($neu['fm_invnr'])) {$neu['fm_invnr']= "";}

$neu['fm_bezeich'] = mb_convert_case($neu['fm_bezeich'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$neu['fm_aenduid'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

$neu['fm_eignr'] = $_SESSION['Eigner']['eig_eigner'];

if (isset($neu['l_sb_s6']) && $neu['l_sb_s6'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s6']);
} elseif (isset($neu['l_sb_s5']) && $neu['l_sb_s5'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s5']);
} elseif (isset($neu['l_sb_s4']) && $neu['l_sb_s4'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s4']);
} elseif (isset($neu['l_sb_s3']) && $neu['l_sb_s3'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s3']);
} elseif (isset($neu['l_sb_s2']) && $neu['l_sb_s2'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s2']);
} elseif (isset($neu['l_sb_s1']) && $neu['l_sb_s1'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_1']);
}

if (isset($arr_sb)) {
    $neu['fm_suchbegr_1'] = $arr_sb[0];
    $neu['fm_suchbegr_2'] = $arr_sb[1];
    $neu['fm_suchbegr_3'] = $arr_sb[2];
    $neu['fm_suchbegr_4'] = $arr_sb[3];
    $neu['fm_suchbegr_5'] = $arr_sb[4];
    $neu['fm_suchbegr_6'] = $arr_sb[5];
}

$uploaddir =  "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/GER/";

if (! file_exists($uploaddir)) {
    mkdir($uploaddir);
}

if ($_FILES['uploaddatei_01']['name'] != "" ) {
    $neu['fm_foto_1'] = VF_Upload($uploaddir, '01');
}
if ($_FILES['uploaddatei_02']['name'] != "" ) {
    $neu['fm_foto_2'] = VF_Upload($uploaddir, '02');
}
if ($_FILES['uploaddatei_03']['name'] != "" ) {
    $neu['fm_foto_3'] = VF_Upload($uploaddir, '03');
}
if ($_FILES['uploaddatei_04']['name'] != "" ) {
    $neu['fm_foto_4'] = VF_Upload($uploaddir, '04');
}

$neu['fm_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if ($neu['fm_id'] == 0) { # neueingabe
    $sql = "INSERT INTO $tabelle_a (
                fm_eignr,fm_invnr,fm_bezeich,fm_type,fm_leistung,fm_lei_bed,
                fm_herst,fm_baujahr,fm_indienst,fm_ausdienst,fm_komment,fm_gew,
                fm_fgstnr,fm_zug,
                fm_foto_1,fm_komm_1,fm_foto_2,fm_komm_2,fm_foto_3,fm_komm_3,fm_foto_4,fm_komm_4,
                fm_suchbegr_1,fm_suchbegr_2,fm_suchbegr_3,fm_suchbegr_4,fm_suchbegr_5,fm_suchbegr_6,
                fm_sammlg,fm_zustand,fm_uidaend,fm_aenddat
              ) VALUE (
                '$neu[fm_eignr]','$neu[fm_invnr]','$neu[fm_bezeich]','$neu[fm_type]','$neu[fm_leistung]','$neu[fm_lei_bed]',
                '$neu[fm_herst]','$neu[fm_baujahr]','$neu[fm_indienst]','$neu[fm_ausdienst]','$neu[fm_komment]','$neu[fm_gew]',
                '$neu[fm_fgstnr]','$neu[fm_zug]',
                '$neu[fm_foto_1]','$neu[fm_komm_1]','$neu[fm_foto_2]','$neu[fm_komm_2]','$neu[fm_foto_3]','$neu[fm_komm_3]','$neu[fm_foto_4]','$neu[fm_komm_4]',
                '$neu[fm_suchbegr_1]','$neu[fm_suchbegr_2]','$neu[fm_suchbegr_3]','$neu[fm_suchbegr_4]','$neu[fm_suchbegr_5]','$neu[fm_suchbegr_6]',
                '$neu[fm_sammlg]','$neu[fm_zustand]','" . $_SESSION['VF_Prim']['p_uid'] . "',now()
               )";

    $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));


    $errno = mysqli_errno($db);
    if ($errno == "1146") {
        $sql_newT = "CREATE TABLE IF NOT EXISTS $tabelle_a LIKE $tabelle";
        mysqli_query($db, $sql_newT);
        $result = SQL_QUERY($db, $sql);
    }
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
        if ($name == "fm_foto_101") {
            continue;
        } #
        if ($name == "fm_foto_202") {
            continue;
        } #
        if ($name == "fm_foto_303") {
            continue;
        } #
        if ($name == "fm_foto_404") {
            continue;
        } #
        if ($name == "fm_aenduid") {
            continue;
        } #
        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if (substr($name, 0, 3) == "sel") {
            continue;
        }
        if (substr($name, 0, 3) == "lev") {
            continue;
        }
        if (substr($name, 0, 4) == 'l_sb') {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle_a SET  $updas WHERE `fm_id`='" . $_SESSION[$module]['fm_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    header("Location: VF_FM_GE_List.php?ID=".$_SESSION[$module]['fm_sammlung']);
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph1.inc beendet</pre>";
}
?>