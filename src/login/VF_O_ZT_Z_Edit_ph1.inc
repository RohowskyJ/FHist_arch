<?php

/**
 * Zeitungs- Liste, Wartung, Date schreiben
 *
 * @author J.Rohowsky
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_ZT_Z_Edit_ph1.inc ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$neu['zt_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['zt_id'] == 0) { # Neueingabe
    $sql = "INSERT INTO $tabelle (
               zt_name,zt_herausg,zt_internet,zt_email,zt_daten,zt_erstausgdat,zt_letztausgabe,zt_uidaend
              ) VALUE (
                '$neu[zt_name]','$neu[zt_herausg]','$neu[zt_internet]','$neu[zt_email]','$neu[zt_daten]','$neu[zt_erstausgdat]','$neu[zt_letztausgabe]' ,
                '$neu[zt_uidaend]'
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
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

        if ($name == "phase") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `zt_id`='" . $_SESSION[$module]['zt_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 047: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_O_ZT_List.php");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_Z_Edit_ph1.inc beendet</pre>";
}
?>