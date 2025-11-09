<?php

/**
 * Zeitungs- Index Liste, Wartung, Daten schreiben
 *
 * @author J.Rohowsky
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_ZT_I_Edit:ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_I_Edit.inc.php ist gestarted</pre>";
}

$neu['ih_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['ih_id'] == 0) { # Neueingabe
    $sql = "INSERT INTO $tabelle (
                ih_zt_id,ih_jahrgang,ih_jahr,ih_nr,ih_kateg,ih_sg,
                ih_ssg,ih_gruppe,ih_titel,ih_titelerw,ih_autor,ih_email,ih_tel,
                ih_fax,ih_seite,ih_spalte,ih_fwehr,ih_uidaend
              ) VALUE (
                '$neu[ih_zt_id]','$neu[ih_jahrgang]','$neu[ih_jahr]','$neu[ih_nr]','$neu[ih_kateg]','$neu[ih_sg]',
                '$neu[ih_ssg]','$neu[ih_gruppe]','$neu[ih_titel]','$neu[ih_titelerw]','$neu[ih_autor]','$neu[ih_email]','$neu[ih_tel]',
                '$neu[ih_fax]','$neu[ih_seite]','$neu[ih_spalte]','$neu[ih_fwehr]','$neu[ih_uidaend]'
               )";

    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'O ZT I Edit ph1 $sql_be </pre>";
    echo "</div>";
 
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
        if ($name == "ge_foto_101") {
            continue;
        } #

        if ($name == "phase") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `ih_id`='" . $neu['ih_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'O ZT I Edit ph1 $sql_be </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_O_ZT_List.php");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_O_ZT_I_Edit_ph1.inc.php beendet</pre>";
}
?>