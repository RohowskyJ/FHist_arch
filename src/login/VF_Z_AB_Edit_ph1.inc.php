<?php

/**
 * Benutzervrwaltung, Warten, Daten schreiben
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php ist gestarted</pre>";
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if (! isset($neu['ab_uidaend'])) {
    $neu['ab_uidaend'] = $p_uid;
}

$ab_id = $neu['ab_id'];

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if ($ab_id == "0") {
    $sql = "INSERT INTO fh_abk (
                ab_grp,ab_abk,ab_bezeichn,ab_uidaend,ab_aenddat
              ) VALUE (
               '$neu[ab_grp]','$neu[ab_abk]','$neu[ab_bezeichn]','$neu[ab_uidaend]',now()
               )";

    $result = SQL_QUERY($db, $sql);

    $ben_id = mysqli_insert_id($db);

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
        if ($name == "phase") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE fh_abk SET  $updas WHERE `ab_id`='$ab_id'";
    if ($debug) {
        echo '<pre class=debug> L 052: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_Z_AB_List.php");

if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php beendet</pre>";
}
?>