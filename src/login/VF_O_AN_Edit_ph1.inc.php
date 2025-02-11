<?php

/**
 * Liste der Anbote / Nachfragen, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_O_An_Edit_ph0.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$uploaddir = "../VFH/scripts/updata/Biete_Suche/";

if ($_FILES['uploaddatei_1']['name'] != "" ) {
    $neu['bs_bild_1'] = VF_Upload($uploaddir, 1);
}
if ($_FILES['uploaddatei_2']['name'] != "" ) {
    $neu['bs_bild_2'] = VF_Upload($uploaddir, 2);
}
if ($_FILES['uploaddatei_3']['name'] != "" ) {
    $neu['bs_bild_3'] = VF_Upload($uploaddir, 3);
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($bs_id == 0) { # Neuer Datensatz anlegen
    $sql = "INSERT INTO $tabelle (
              bs_startdatum,bs_enddatum,bs_kurztext,bs_typ,bs_text,
              bs_email_1,bs_email_2,bs_bild_1,bs_bild_2,bs_bild_3,
              bs_aenduid
              ) VALUE (
               '$neu[bs_startdatum]','$neu[bs_enddatum]','$neu[bs_kurztext]','$neu[bs_typ]','$neu[bs_text]',
               '$neu[bs_email_1]','$neu[bs_email_2]','$neu[bs_bild_1]','$neu[bs_bild_2]','$neu[bs_bild_3]',
               '$p_uid'
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
} else { # Update betehender Satz

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
        if ($name == "bs_bild_11") {
            continue;
        }
        if ($name == "bs_bild_22") {
            continue;
        }
        if ($name == "bs_bild_33") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `bs_id`='$bs_id'";
    if ($debug) {
        echo '<pre class=debug> L 099: \$sql $sql </pre>';
    }
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_O_AN_List.php?Act=" . $_SESSION[$module]['Act'] );

if ($debug) {
    echo "<pre class=debug>VFH_O_AN_Edit_ph0.php beendet</pre>";
}
?>