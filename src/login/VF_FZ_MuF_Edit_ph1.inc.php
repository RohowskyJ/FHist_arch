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

$_SESSION[$module]['Inc_Arr'][] = "VF_FZ_MuF_Edit_ph1.inc.php";

foreach ($_POST as $name => $value) {
    $neu[$name] = trim(mysqli_real_escape_string($db, $value));
}

if (!isset($neu['fm_invnr'])) {
    $neu['fm_invnr'] = "";
}

# $neu['fm_bezeich'] = mb_convert_case($neu['fm_bezeich'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
#$debug = true;
$neu['fm_aenduid'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if (isset($neu['hersteller']) && $neu['hersteller'] != "") {
    $neu['fm_herst'] = $neu['hersteller'];
    unset($neu['hHersteller']);

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

$pic_cnt = $neu['pic_cnt'];
for ($i=1;$i<=$pic_cnt;$i++) {
    if ($neu['bild_datei_'.$i] != "") {
        $neu['fm_foto_'.$i] = $neu['bild_datei_'.$i];
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
   
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = substr($updas,1);
    $sql = "UPDATE $tabelle_a SET  $updas WHERE `fm_id`='" . $_SESSION[$module]['fm_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0150: \$sql $sql </pre>';
    }

    #echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    unset($_SESSION[$module]['Pct_Arr']);

    header("Location: VF_FZ_MuFG_List.php?ID=".$_SESSION[$module]['fm_sammlung']);
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MuF_Edit_ph1.inc.php beendet</pre>";
}
