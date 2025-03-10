uid<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky  neu 2019 
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FM_GE_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
   $neu[$name] = mysqli_real_escape_string($db, $value);
    # $neu[$name] = $value;
}

$neu['mg_bezeich'] = mb_convert_case($neu['mg_bezeich'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$neu['mg_uidaend'] = $_SESSION['VF_Prim']['p_uid'];

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

$neu['mg_eignr'] = $_SESSION['Eigner']['eig_eigner'];

/* Sammlung aufbereiten */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {
        
    } else {
        $neu['mg_sammlg'] = $response;
    }
}

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuG/";
    
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir);
    }
    
    if ($_FILES['uploaddatei_01']['name'] != "" ) {
        $neu['mg_foto_1'] = VF_Upload($uploaddir, '01');
    }
    if ($_FILES['uploaddatei_02']['name'] != "" ) {
        $neu['mg_foto_2'] = VF_Upload($uploaddir, '02');
    }
    if ($_FILES['uploaddatei_03']['name'] != "" ) {
        $neu['mg_foto_3'] = VF_Upload($uploaddir, '03');
    }
    if ($_FILES['uploaddatei_04']['name'] != "" ) {
        $neu['mg_foto_4'] = VF_Upload($uploaddir, '04');
    }
}

$neu['mg_uidaend'] = $_SESSION['VF_Prim']['p_uid'];

if ($neu['mg_id'] == 0) { # neueingabe
    
    Cr_n_mu_geraet($tabelle_a);
    
    $sql = "INSERT INTO $tabelle_a (
                mg_eignr,mg_invnr,mg_bezeich,mg_type,
                mg_foto_1,mg_komm_1,mg_foto_2,mg_komm_2,mg_foto_3,mg_komm_3,mg_foto_4,mg_komm_4,            
                mg_sammlg,              
                mg_fzg,mg_raum,mg_ort,mg_zustand,mg_pruef_id,mg_pruef_dat,mg_uidaend
              ) VALUE (
                '$neu[mg_eignr]','$neu[mg_invnr]','$neu[mg_bezeich]','$neu[mg_type]',
                '$neu[mg_foto_1]','$neu[mg_komm_1]','$neu[mg_foto_2]','$neu[mg_komm_2]','$neu[mg_foto_3]','$neu[mg_komm_3]','$neu[mg_foto_4]','$neu[mg_komm_4]',   
                '$neu[mg_sammlg]',
                '$neu[mg_fzg]','$neu[mg_raum]','$neu[mg_ort]','$neu[mg_zustand]','$neu[mg_pruef_id]','$neu[mg_pruef_dat]',
                '$neu[mg_uidaend]'
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));
    #echo mysqli_error($db) . "  L 0149: \$sql $sql <br/>";

    $neu['mg_id'] = mysqli_insert_id($db);
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

        if ($name == "mg_foto_101") {
            continue;
        } #
        if ($name == "mg_foto_202") {
            continue;
        } #
        if ($name == "mg_foto_303") {
            continue;
        } #
        if ($name == "mg_foto_404") {
            continue;
        } #

        if (substr($name, 0, 3) == "sel") {
            continue;
        }
        if (substr($name, 0, 3) == "lev") {
            continue;
        }

        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if (substr($name,0,3) == "sa_") {
            continue;
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle_a SET  $updas WHERE `mg_id`='" . $_SESSION[$module]['mg_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
}

header("Location: VF_FM_List.php?ID=".$_SESSION[$module]['fm_sammlung']);

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FM_GE_Edit_ph1.inc.php beendet</pre>";
}
?>