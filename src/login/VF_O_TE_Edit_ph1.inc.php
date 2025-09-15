<?php

/**
 * Liste der Veranstaltungstermine, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_TE_Edit_ph1.php gestartet</pre>";
}

echo "<div class=white> ";

# =====================================================================================================
# Datensatz in der Tabelle ändern
# =====================================================================================================

if (isset($_FILES['uploaddatei_1']['name'])) {
    $cjahr = substr($neu['va_datum'], 0, 4);
    $uploaddir = $path2ROOT . "/login/AOrd_Verz/Termine/" . $cjahr . "/";

    # echo "L 072 \$uploaddir $uploaddir <br/>";
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['va_bild'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['va_prosp_1'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['va_prosp_2'] = VF_Upload($uploaddir, 3);
    }

}

if ($neu['va_id'] == 0) { # Neueer Datensatz
    $p_uid = $_SESSION['VF_Prim']['p_uid'];
    $sql = "INSERT INTO va_daten (va_datum,va_begzt,va_end_dat,va_endzt,
                       va_titel,va_beschr,va_kateg,va_anm_erf,
                       va_inst,va_adresse,
                       va_plz,va_ort,va_staat,va_bdld,va_beitrag_m,va_beitrag_g,
                       va_admin_email,va_kontakt,va_link_einladung,va_umfang,
                       va_bild,va_prosp_1,va_prosp_2,va_internet,va_anm_text,va_raum,
                       va_plaetze,va_warte,
                       va_anmeld_end,
                       va_angelegt,va_ang_uid
                      ) VALUE (
                       '$neu[va_datum]','$neu[va_begzt]','$neu[va_end_dat]','$neu[va_endzt]',
                       '$neu[va_titel]','$neu[va_beschr]','$neu[va_kateg]','$neu[va_anm_erf]',
                       '$neu[va_inst]','$neu[va_adresse]',
                       '$neu[va_plz]','$neu[va_ort]','$neu[va_staat]','$neu[va_bdld]','$neu[va_beitrag_m]','$neu[va_beitrag_g]',
                       '$neu[va_admin_email]','$neu[va_kontakt]','$neu[va_link_einladung]','$neu[va_umfang]',
                       '$neu[va_bild]','$neu[va_prosp_1]','$neu[va_prosp_2]','$neu[va_internet]','$neu[va_anm_text]','$neu[va_raum]',
                       '$neu[va_plaetze]','$neu[va_warte]',
                       '$neu[va_anmeld_end]',
                       now(),' $p_uid'
                       ) ";
    $return = SQL_QUERY($db, $sql);
} else { # Update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if ($name == "va_bild1") {
            continue;
        } #
        if ($name == "va_prosp_12") {
            continue;
        } #
        if ($name == "va_prosp_23") {
            continue;
        } #
          #

        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $update_dat = ",va_aenderung=now(),va_aend_uid='" . $_SESSION['VF_Prim']['p_uid'] . "'";

    $sql = "UPDATE `va_daten` SET $updas $update_dat WHERE `va_id`='$va_id'";

    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql  </pre>';
    }

    $return = SQL_QUERY($db, $sql);
}

header("Location: VF_O_TE_List.php?Act=" . $_SESSION[$module]['Act']);

echo "</div>";

if ($debug) {
    echo "<pre class=debug>VF_O_TE_Edit_ph1.php beendet</pre>";
}

?>
