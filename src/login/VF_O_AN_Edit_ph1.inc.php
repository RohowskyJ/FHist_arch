<?php

/**
 * Liste der Anbote / Nachfragen, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_AN_Edit.ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VFH_O_An_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$uploaddir = "../VFH/scripts/updata/Biete_Suche/";

if ( $neu['bild_datei_1'] != '') {
    $neu['bs_bild_1'] =  $neu['bild_datei_1'];
}
if ( $neu['bild_datei_2'] != '') {
    $neu['bs_bild_2'] =  $neu['bild_datei_2'];
}
if ( $neu['bild_datei_3'] != '') {
    $neu['bs_bild_3'] =  $neu['bild_datei_3'];
}
if ( $neu['bild_datei_4'] != '') {
    $neu['bs_bild_4'] =  $neu['bild_datei_4'];
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
              bs_email_1,bs_email_2,bs_bild_1,bs_bild_2,bs_bild_3,bs_bild_4,
              bs_aenduid
              ) VALUE (
               '$neu[bs_startdatum]','$neu[bs_enddatum]','$neu[bs_kurztext]','$neu[bs_typ]','$neu[bs_text]',
               '$neu[bs_email_1]','$neu[bs_email_2]','$neu[bs_bild_1]','$neu[bs_bild_2]','$neu[bs_bild_3]','$neu[bs_bild_4]',
               '$p_uid'
               )";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AN Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
} else { # Update betehender Satz

    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        
        if (substr($name,0,3) != "bs_") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `bs_id`='$bs_id'";
    if ($debug) {
        echo '<pre class=debug> L 099: \$sql $sql </pre>';
    }
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AN Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_O_AN_List.php?Act=" . $_SESSION[$module]['Act'] );

if ($debug) {
    echo "<pre class=debug>VFH_O_AN_Edit_ph0.php beendet</pre>";
}
?>