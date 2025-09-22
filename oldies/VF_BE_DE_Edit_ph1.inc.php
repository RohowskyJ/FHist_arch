
<?php

/**
 * Liste der Veranstaltungsberichte, Wartung, Daten abspeichern
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

# =====================================================================================================
# Datensatz in der Tabelle ändern
# =====================================================================================================
$pict_path = $path2ROOT."login/AOrd_Verz/".$neu['vb_foto_Urheber'];
if ($_SESSION[$module]['URHEBER']['fm_typ'] == "F") {
    $pict_path .= "/09/06/";
} elseif ($_SESSION[$module]['URHEBER']['fm_typ'] == "V") {
    $pict_path .= "/09/10/";
}
if ($_SESSION[$module]['fo_aufn_d'] != "" ) {
    $pict_path .= $_SESSION[$module]['fo_aufn_d']."/";
}

$uploaddir = $pict_path;

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/09/06/";
    # echo "L 072 \$uploaddir $uploaddir <br/>";
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['vb_foto'] = VF_Upload($uploaddir, 1);
    }
}

if ($neu['vd_flnr'] == 0) { # neueingabe

    $p_uid = $_SESSION['VF_Prim']['p_uid'];
    $sql = "INSERT INTO vb_ber_detail (vb_flnr,vb_unter,vb_suffix,vb_titel,vb_beschreibung,vb_foto,vb_foto_urheber,vb_uid
                      ) VALUE (
                        '$neu[vb_flnr]','$neu[vb_unter]','$neu[vb_suffix]','$neu[vb_titel]','$neu[vb_beschreibung]','$neu[vb_foto]','$neu[vb_foto_Urheber]'
                        ,' $p_uid'             
                       ) ";
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
        if ($name == "vb_foto1") {
            continue;
        } #

        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    if ($_SESSION[$module]['all_upd']) {
        $update_dat = ",vb_uid='" . $_SESSION['VF_Prim']['p_uid'] . "'";
        $vb_flnr = $neu['vb_flnr'];
        $sql = "UPDATE `vb_ber_detail_4` SET $updas $update_dat WHERE vd_flnr='$neu[vd_flnr]' ";

        if ($debug) {
            echo '<pre class=debug> L 0197: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    }
}

$result = SQL_QUERY($db, $sql);

echo "<p><a href='VF_7_BE_List_v3.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

?>
