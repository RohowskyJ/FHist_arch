<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky  neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FZ_MuF_Edit_ph1.inc.php ist gestarted</pre>";
}
$Inc_Arr[] = "VF_FZ_MuF_Edit_ph1.inc.php";
#
foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

if (!isset($neu['fm_invnr'])) {
    $neu['fm_invnr'] = "";
}

# $neu['fm_bezeich'] = mb_convert_case($neu['fm_bezeich'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$debug = true;
$neu['fm_aenduid'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if (isset($neu['suggestHersteller']) && $neu['suggestHersteller'] != "") {
    $neu['fm_herst'] = $neu['suggestHersteller'];
    unset($neu['suggestHersteller']);

}
unset($neu['hersteller']);

$neu['fm_eignr'] = $_SESSION['Eigner']['eig_eigner'];

/* Sammlung aufbereiten */

if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {

    } else {
        $neu['fm_sammlg'] = $response;
    }
}

$uploaddir = VF_Upload_Pfad_M('');

if (! file_exists($uploaddir)) {
    mkdir($uploaddir, 0770, true);
}

if ($neu['bild_datei_1'] != "") {
    $neu['fm_foto_1'] = $neu['bild_datei_1'];
}

if ($neu['bild_datei_2'] != "") {
    $neu['fm_foto_2'] = $neu['bild_datei_2'];
}

if ($neu['bild_datei_3'] != "") {
    $neu['fm_foto_3'] = $neu['bild_datei_3'];
}

if ($neu['bild_datei_4'] != "") {
    $neu['fm_foto_4'] = $neu['bild_datei_4'];
}

if (isset($_FILES)) {
    $i = 0;

    foreach ($_FILES as $upLoad  => $file_arr) {
        #var_dump($_FILES[$upLoad]);
        # var_dump($_SESSION[$module]['Pct_Arr']);
        if ($_FILES[$upLoad] != "") {
            # $result = VF_Upload_M($uploaddir,$upLoad,$urh_abk,$fo_aufn_datum);
            $result = VF_Upload_Save_M($uploaddir, $upLoad); # ,$urh_abk,$fo_aufn_datum

            if ($result == "") {
                continue;
            }
            if (substr($result, 0, 5) == 'Err: ') {
                continue;
            }
            $neu["fm_foto_".$i + 1] = $result;

            $i++;
        }
    }
}

$neu['fm_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if ($neu['fm_id'] == 0) { # neueingabe

    Cr_n_mu_fahrzeug($tabelle_a);

    $sql = "INSERT INTO $tabelle_a (
                fm_eignr,fm_invnr,fm_bezeich,fm_type,fm_leistung,fm_lei_bed,
                fm_herst,fm_baujahr,fm_indienst,fm_ausdienst,fm_komment,fm_gew,
                fm_fgstnr,fm_zug,
                fm_foto_1,fm_komm_1,fm_foto_2,fm_komm_2,fm_foto_3,fm_komm_3,fm_foto_4,fm_komm_4,
                fm_sammlg,fm_zustand,fm_uidaend,fm_aenddat
              ) VALUE (
                '$neu[fm_eignr]','$neu[fm_invnr]','$neu[fm_bezeich]','$neu[fm_type]','$neu[fm_leistung]','$neu[fm_lei_bed]',
                '$neu[fm_herst]','$neu[fm_baujahr]','$neu[fm_indienst]','$neu[fm_ausdienst]','$neu[fm_komment]','$neu[fm_gew]',
                '$neu[fm_fgstnr]','$neu[fm_zug]',
                '$neu[fm_foto_1]','$neu[fm_komm_1]','$neu[fm_foto_2]','$neu[fm_komm_2]','$neu[fm_foto_3]','$neu[fm_komm_3]','$neu[fm_foto_4]','$neu[fm_komm_4]',
                '$neu[fm_sammlg]','$neu[fm_zustand]','" . $_SESSION['VF_Prim']['p_uid'] . "',now()
               )";

    $result = SQL_QUERY($db, $sql);

} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) { # für alle Felder aus der tabelle
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        }
        # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if (substr($name,0,3) != 'fm_') {
            continue;
        }
        if ($name == "fm_aenduid" || $name == 'fm_id') {
            continue;
        } #
        /*
        if ($name == "fz_herst") {
            continue;
        } #
        if ($name == "sammlg") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        
        if (substr($name, 0, 3) == "sel") {
            continue;
        }
       
        if (substr($name, 0, 3) == "lev") {
            continue;
        }
        if (substr($name, 0, 3) == "sa_") {
            continue;
        } #
        if (substr($name, 0, 3) == "fot") {
            continue;
        } #
   */
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = substr($updas,1);
    $sql = "UPDATE $tabelle_a SET  $updas WHERE `fm_id`='" . $_SESSION[$module]['fm_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0150: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    unset($_SESSION[$module]['Pct_Arr']);

    header("Location: VF_FZ_MuFG_List.php?ID=".$_SESSION[$module]['fm_sammlung']);
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MuF_Edit_ph1.inc.php beendet</pre>";
}
