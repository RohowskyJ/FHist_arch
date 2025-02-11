<?php

/**
 * Wappen und Auszeichnungen Daten sspeichern
 *
 * @author Josef Rohowsky - neu 2024
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_O_Edit_ph1.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$neu['fw_uid_aend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['fw_id'] == 0) { # Neueingabe
    $sql = "INSERT INTO aw_ort_ref (
                fw_st_abk,fw_bd_abk,fw_bz_abk,fw_bz_name,
                fw_ab_nr,fw_ab_name,fw_gd_nr,fw_gd_name,fw_gd_art,
                fw_fw_nr,fw_fw_name,fw_fw_typ,fw_grdg_dat,fw_end_dat,
                fw_kommentar,fw_ort_komm,fw_uid_aend
              ) VALUE (
               '$neu[fw_st_abk]','$neu[fw_bd_abk]','$neu[fw_bz_abk]','$neu[fw_bz_name]',
               '$neu[fw_ab_nr]','$neu[fw_ab_name]','$neu[fw_gd_nr]','$neu[fw_gd_name]','$neu[fw_gd_art]',
               '$neu[fw_fw_nr]','$neu[fw_fw_name]','$neu[fw_fw_typ]','$neu[fw_grdg_dat]','$neu[fw_end_dat]',
               '$neu[fw_kommentar]','$neu[fw_ort_komm]','$neu[fw_uid_aend]'
               )";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
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
        if ($name == "tabelle") {
            continue;
        } #
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE aw_ort_ref SET  $updas WHERE `fw_id`='" . $_SESSION[$module]['fw_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
}

header("Location: VF_PS_OV_O_List.php");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_PS_OV_O_Edit_ph1.php beendet</pre>";
}
?>