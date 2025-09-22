
<?php
/**
 * Liste der Veranstaltungsberichte, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph1.inc.php ist gestartet</pre>";}

# =====================================================================================================
# Datensatz in der Tabelle ändern
# =====================================================================================================
if ($neu['vb_flnr'] == 0) {

    $p_uid = $_SESSION['VF_Prim']['p_uid'];
    $sql = "INSERT INTO vb_bericht_4 (vb_datum,vb_titel,vb_unterseiten,vb_beschreibung,vb_uid
                      ) VALUE (
                        '$neu[vb_datum]','$neu[vb_titel]','$neu[vb_unterseiten]','$neu[vb_beschreibung]'
                        ,'$p_uid'             
                       ) ";
    $result = SQL_QUERY($db, $sql);
    $neu['vb_flnr'] = mysqli_insert_id($db);

    foreach ($ber_fo_arr as $key => $value) { # anlegen der Foto-Records
        $far = explode("|", $value);

        $sql = "INSERT INTO vb_ber_detail_4 (vb_flnr,vb_unter,vb_suffix,vb_foto_urheber,vb_foto,vb_uid
                         ) VALUE ( '$neu[vb_flnr]','','','$far[0]','$far[1]','$p_uid')";
        $result = SQL_QUERY($db, $sql);
        $_SESSION[$module]['v_flnr'] = mysqli_insert_id($db);
    }
} else {

    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if ($name == "vb_foto1") {
            continue;
        } #

        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "tabelle") {
            continue;
        }
        if ($name == "vb_beschreibung") {
            continue;
        }
        if (substr($name, 0, 5) == "vb_un" || substr($name, 0, 5) == "vb_su" || substr($name, 0, 5) == "vb_ti" || substr($name, 0, 5) == "fo_be"  ) {
            continue;
        }
        if (is_numeric($name)) {
            continue;
        } # die Recr der Fotos ignorieren

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $update_dat = ",vb_uid='" . $_SESSION['VF_Prim']['p_uid'] . "'";

    $vb_flnr = $neu['vb_flnr'];
    $sql = "UPDATE `vb_bericht_4` SET $updas $update_dat WHERE vb_flnr='$vb_flnr'";

    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    foreach ($ber_fo_arr as $key) { # prüfen ob vorhanden, wenn nicht neu anlegen der Foto-Records

        $far = explode("|", $key);

        $sql = "SELECT * FROM vb_ber_detail_4 WHERE vb_foto_urheber='".$far[0]."' AND vb_foto= '".$far [1]."' ";

        $result = SQL_QUERY($db, $sql);
        if ($result) { # update
        } else { # add record
        }
    }
}

# echo "</div>";

if ($debug) {echo "<pre class=debug>VF_O_BE_Edit_ph1.inc.php ist beendet</pre>";}

?>
