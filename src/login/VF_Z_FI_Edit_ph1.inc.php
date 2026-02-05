<?php

/**
 * Benutzervrwaltung, Warten, Daten schreiben
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_FI_Edit_ph1.php";

if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php ist gestarted</pre>";
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];


if (! isset($neu['fi_uidaend'])) {
    $neu['fi_uidaend'] = $p_uid;
}

$fi_id = $neu['fi_id'];

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if ($fi_id == "0") {
    $sql = "INSERT INTO fh_firmen (
                fi_abk,fi_name,fi_ort,fi_vorgaenger,fi_funkt,fi_inet,fi_uidaend
              ) VALUE (
               '$neu[fi_abk]','$neu[fi_name]','$neu[fi_ort]','$neu[fi_vorgaenger]','$neu[fi_funkt]','$neu[fi_inet]',
               '$neu[fi_uidaend]'
               )";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z FI Edit $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);

  
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

    $sql = "UPDATE fh_firmen SET  $updas WHERE `fi_id`='$fi_id'";
    if ($debug) {
        echo '<pre class=debug> L 052: \$sql $sql </pre>';
    }
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z FI Edit $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_Z_FI_List.php");

if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php beendet</pre>";
}
?>