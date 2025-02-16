<?php

/**
 * Foto Urheber, Details, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
# =====================================================================================================
# Datensatz in der Tabelle ändern
# =====================================================================================================
if ($neu['fs_flnr'] == 0) { # neue daten

    $p_uid = $_SESSION['VF_Prim']['p_uid'];
    $sql = "INSERT INTO fh_eign_urh (fs_eigner,fs_typ,fs_fotograf,fs_urh_nr,fs_urh_kurzz,fs_uidaend
                      ) VALUE (
                        '$neu[fs_eigner]','$neu[fs_typ]','$neu[fs_fotograf]','$neu[fs_urh_kurzznr]','$neu[fs_urh_kurzz]','$p_uid'             
                       ) ";
} else { # update

    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen

        if ($name == "phase") {
            continue;
        } #
        
        if (substr($name,0,7) == "fs_anz_") {
            continue;
        } #
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $update_dat = ",fs_uidaend='" . $_SESSION['VF_Prim']['p_uid'] . "'";
    $fs_flnr = $neu['fs_flnr'];
    $sql = "UPDATE `fh_eign_urh` SET $updas $update_dat WHERE fs_flnr='$neu[fs_flnr]' ";

    if ($debug) {
        echo '<pre class=debug> L 044: \$sql $sql </pre>';
    }
}

$result = SQL_QUERY($db, $sql);


header("Location: VF_Z_E_Edit.php?ID=".$neu['fs_eigner']);

?>
