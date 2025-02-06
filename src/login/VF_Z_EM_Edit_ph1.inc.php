<?php

/**
 * Automatische Benachrichtigung für ADMINS bei Änderungen, Wartun, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if ($neu['em_flnr'] == 0) { // NeuItem

    $sql = "INSERT INTO $tabelle (
                em_mitgl_nr,em_mail_grp,em_active,em_uidaend,em_aenddat
              ) VALUE (
               '$neu[em_mitgl_nr]','" . $neu['em_mail_grp'] . "','$neu[em_active]'
              ,'$p_uid',now()
               )";

    $result = SQL_QUERY($db, $sql);
} else {
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if ($name == "phase") {
            continue;
        } #
    } # Ende der Schleife
    $updas .= "`em_mitgl_nr`='" . $neu['em_mitgl_nr'] . "',`em_active`='" . $neu['em_active'] . "'";
    # weiteres SET `variable` = 'Wert' fürs query
    # $updas = mb_substr($updas,1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `em_flnr`='$em_flnr'";
    if ($debug) {
        echo '<pre class=debug> L 052: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_Z_EM_List.php");

if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph1.inc.php beendet</pre>";
}
?>