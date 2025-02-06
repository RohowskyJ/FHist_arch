<?php

/**
 * Liste der Dokumentationen, Wartun, Daten wegschreben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_DO_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
$updir = "AOrd_Verz/Downloads/";

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = $updir . $neu['dk_Path2Dsn'];
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['dk_Dsn'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['dk_Dsn_2'] = VF_Upload($uploaddir, 2);
    }
    
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($dk_nr == 0) { // neuer Eintrag
    $sql = "INSERT INTO $tabelle (
              dk_Thema,dk_Titel,dk_Author,dk_Urspr,dk_Dsn,dk_Dsn_2,
              dk_Path2Dsn,dk_url,dk_aenduid,dk_aenddat
              ) VALUE (
               '$neu[dk_Thema]','$neu[dk_Titel]','$neu[dk_Author]','$neu[dk_Urspr]','$neu[dk_Dsn]','$neu[dk_Dsn_2]',
               '$neu[dk_Path2Dsn]','$neu[dk_url]','$p_uid',now()
               
               )";

    $result = SQL_QUERY($db, $sql);
} else { // Update
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
        }
        if ($name == "dk_Dsn1") {
            continue;
        }
        if ($name == "dk_Dsn_22") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `dk_nr`='$dk_nr'";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
}

header("Location: VF_O_DO_List.php?thema=1");

if ($debug) {
    echo "<pre class=debug>VF_O_DO_Edit_ph1.inc.php beendet</pre>";
}
?>