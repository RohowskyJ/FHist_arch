<?php

/**
 * Foto Urheber, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FO_U_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}
# $neu['fo_fw_id'] = $_SESSION[$module]['fw_id'];
# echo '<pre class=debug>';echo '<hr>$neu: '; print_r($neu); echo '</pre>';

$neu['fm_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['fm_id'] == 0) { # neuanlage
    if ($neu['fm_eigner'] != "") {
        $ei_arr = explode("-",$neu['fm_eigner']);
        
        $neu['fm_eigner'] = trim($ei_arr[0]);
        if ($neu['fm_urheber'] == "") {
            if (trim($ei_arr[1]) != "") {
                $neu['fm_urheber'] = trim($ei_arr[1]);
            } else {
                $neu['fm_urheber'] = trim($ei_arr[2]);
            }
        }

        $uploaddir = "AOrd_Verz/" . $neu['fm_eigner'] . "/";
        echo "L 055 \$uploaddir $uploaddir <br>";
        if (! file_exists($uploaddir)) {
            mkdir($uploaddir,0770,true);
        }
    }

    $sql = "INSERT INTO $tabelle (
                fm_eigner,fm_urheber,fm_urh_kurzz,fm_typ,
                fm_uidaend,fm_aenddat
              ) VALUE (
                '$neu[fm_eigner]','$neu[fm_urheber]','$neu[fm_urh_kurzz]','$neu[fm_typ]',
                '$neu[fm_uidaend]',now()
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
    
    $recnr = mysqli_insert_id($db);
    
    $neu_u['fs_flnr'] = 0;
    $neu_u['fs_fm_id'] = $recnr;
    $neu_u['fs_eigner'] = $neu['fm_eigner'];
    $neu_u['fs_typ'] = $neu['fm_typ'];
    $neu_u['fs_fotograf'] = $neu['fm_urheber'];
    $neu_u['fs_urh_kurzz'] = $neu['fm_urh_kurzz'];
   
   $neu = $neu_u;
   print_r($neu_u);echo "<br>neu_u<br>";
   require "VF_FO_U_Ed_Su_ph1.inc.php";
    
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
        if ($name == "fm_id") {
            continue;
        }
        if ($name == "phase") {
            continue;
        }
        if ($name == "tabelle") {
            continue;
        }
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    $sql = "UPDATE $tabelle SET  $updas WHERE `fm_id`='" . $neu['fm_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 050: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
}

header("Location: VF_FO_List.php?ID=NextEig");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_U_Edit_ph1.inc.php beendet</pre>";
}
?>