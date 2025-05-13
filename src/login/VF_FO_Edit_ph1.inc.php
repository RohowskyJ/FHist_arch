<?php

/**
 * Foto- Verwaltung, Wartung, Daten schreiben
 *
 * @author J. Rohowsky  - neu 2018
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph1.inc.php ist gestarted</pre>";
}
var_dump($_FILES);
$neu['fo_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr> \$neu: ';
    print_r($neu);
    echo '</pre>';
}

$neu['fo_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if (isset($neu['level1'])) {
    $smlg = VF_Multi_Sel_Input();
    if ($smlg !== "Nix") {
        $neu['fo_sammlg'] = $smlg;
    }
     
}
if (isset($_FILES['uploaddatei_1']['name'])) {
    $pict_path = $path2ROOT."login/AOrd_Verz/" . $neu['fo_eigner'] . "/09/"; # 06/" 10/;
    
    if ($neu['fo_typ'] == "F") {
        $pict_path .= "06/";
        $f_path = VF_set_PictPath($neu['fo_aufn_datum'],$neu['fo_aufn_suff']);
        $uploaddir = $pict_path.$f_path ;
    } elseif (
        $neu['fo_typ'] == "V") {
            $uploaddir = $pict_path."10/";
    }
    
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0770, true);
    }
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['fo_dsn'] = VF_Upload($uploaddir, 1, $neu['fo_Urh_kurzz'],$neu['fo_aufn_datum']);
    }
}

/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix" ) {
        
    } else {
        $neu['fo_sammlg'] = $_SESSION[$module]['sammlung'] = $response;
    }
}

if ($fo_id == 0) { # Neueingabe
    $sql = "INSERT INTO $tabelle (
                fo_eigner,fo_urheber,fo_aufn_datum,fo_dsn,fo_begltxt,fo_namen,
                fo_sammlg,fo_feuerwehr,fo_suchbegr,fo_typ,fo_media,
                fo_uidaend
              ) VALUE (
                '$neu[fo_eigner]','$neu[fo_Urheber]','$neu[fo_aufn_datum]','$neu[fo_dsn]','$neu[fo_begltxt]','$neu[fo_namen]',
                '$neu[fo_sammlg]','$neu[fo_feuerwehr]','$neu[fo_suchbegr]','$neu[fo_typ]','$neu[fo_media]',
                '$neu[fo_uidaend]'
               )";

    $result = SQL_QUERY($db, $sql);

    $recnr = mysqli_insert_id($db);
   
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

        if ($name == "phase") {
            continue;
        } #
        if ($name == "fo_dsn1" || $name == "fo_dsn01") {
            continue;
        } #
        if ($name == "fo_aenduid") {
            continue;
        } #
        if ($name == "verz") {
            continue;
        }
        if ($name == "urh_abk") {
            continue;
        }
        if (substr($name, 0, 3) == "fz_") {
            continue;
        }
        if (substr($name, 0, 3) == "l_s") {
            continue;
        }
        if (substr($name, 0, 3) == "lev") {
            continue;
        }
        # if ($name == "fo_aenduid") {continue;}

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle SET  $updas WHERE `fo_id`='" . $_SESSION[$module]['fo_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0112: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    $recnr = $_SESSION[$module]['fo_id'];
}

VF_Add_Namen($tabelle, # Einfügen der Schalgworte
$recnr, 'fo_id', $neu['fo_namen']); # für Referat

VF_Add_Findbuch($tabelle,$neu['fo_suchbegr'], 'fo_suchbegr', $recnr,  $neu['fo_eigner']);

if ($neu['fo_typ'] == "V") {
    header("Location: VF_FO_List.php");
} else {
    header("Location: VF_FO_List_Detail.php?fo_aufn_d=" . $neu['fo_aufn_datum']);
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph1.inc.php beendet</pre>";
}
?>