<?php

/**
 * Foto- Verwaltung, Wartung, Daten schreiben
 *
 * @author J. Rohowsky  - neu 2018
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_FO_Edit_ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph1.inc.php ist gestarted</pre>";
}
#var_dump($neu);
$neu['md_aenduid'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr> \$neu: ';
    print_r($neu);
    echo '</pre>';
}

$neu['md_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if (isset($neu['level1'])) {
    $smlg = VF_Multi_Sel_Input();
    if ($smlg !== "Nix") {
        $neu['md_sammlg'] = $smlg;
    }
     
}

if (isset($neu['bild_datei_1']) && $neu['bild_datei_1'] != ""){
    $neu['md_dsn_1'] = $neu['bild_datei_1'];
}

/** setzen der Medien-Art */
if ($neu['md_dsn_1'] != "0_Verz") {
    $md_ar = pathinfo($neu['md_dsn_1']);
    if (in_array(strtolower($md_ar['extension']),AudioFiles)) {
        $neu['md_media'] = 'Audio';
    }
    if (in_array(strtolower($md_ar['extension']),GrafFiles)) {
        $neu['md_media'] = 'Foto';
    }
    if (in_array(strtolower($md_ar['extension']),VideoFiles)) {
        $neu['md_media'] = 'Video';
    }
}
if ($neu['eigentuemer_1'] != "") {
    $neu['md_fw_id'] = $neu['eigentuemer_1'];
    unset($neu['eigentuemer']);
}

/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix" ) {
        
    } else {
        $neu['md_sammlg'] = $_SESSION[$module]['sammlung'] = $response;
    }
}

if ($md_id == 0) { # Neueingabe
    #if ($verz == 'J' ) { /** erster Datensatz als Verzeichnis- Recod ohne Dateidaten ausgeben */  
        $sql = "INSERT INTO $tabelle (
                md_eigner,md_urheber,md_aufn_datum,md_dsn_1,md_beschreibg,md_namen,
                md_sammlg,md_fw_id,md_suchbegr,md_media,
                md_aenduid
              ) VALUE (
                '$neu[md_eigner]','$neu[md_Urheber]','$neu[md_aufn_datum]','$neu[md_dsn_1]','$neu[md_beschreibg]','$neu[md_namen]',
                '$neu[md_sammlg]','$neu[md_fw_id]','$neu[md_suchbegr]','$neu[md_media]',
                '$neu[md_aenduid]'
               )";
        
        if ($debug) {
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Foto Edit ph1 $sql </pre>";
            echo "</div>";
        }

        $result = SQL_QUERY($db, $sql);

    $recnr = mysqli_insert_id($db);
   
} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
    # var_dump($neu);
    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        
        if (substr($name,0,3) != 'md_') {
            continue;
        }

        if ($name == "md_aenduid") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `md_id`='" . $_SESSION[$module]['md_id'] . "'";
    
    if ($debug) {
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Foto Edit ph1 $sql </pre>";
        echo "</div>";
    }
   
    $result = SQL_QUERY($db, $sql);

    $recnr = $_SESSION[$module]['md_id'];
}

VF_Add_Namen($tabelle, # Einfügen der Schalgworte
    $recnr, 'md_id', $neu['md_namen'], $neu['md_eigner']); # für Referat

VF_Add_Findbuch($tabelle,$neu['md_suchbegr'], 'md_suchbegr', $recnr,  $neu['md_eigner']);

if ($neu['md_media'] == "Audio" || $neu['md_media'] == "Video") {
    header("Location: VF_FO_List.php?eigentuemer=".$neu['md_eigner']);
} else {
    header("Location: VF_FO_List_Detail.php?md_aufn_d=" . $neu['md_aufn_datum']);
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph1.inc.php beendet</pre>";
}
?>