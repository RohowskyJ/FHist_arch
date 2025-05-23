<?php
/**
 * Fahrzeug- Liste, Wartun, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
#$debug = True;
if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph1.inc.php ist gestarted</pre>";
    var_dump($_POST);echo "<br>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}
# var_dump($neu);
# $neu['fz_name'] = mb_convert_case($neu['fz_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$neu['fz_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$_POST: ';
    print_r($_POST);
    echo '<hr>$neu: ';
    print_r($neu);
    var_dump($_FILES);
    echo '</pre>';
}

$neu['fz_eignr'] = $_SESSION['Eigner']['eig_eigner'];

/* Sammlung aufbereiten */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {
        
    } else {
        $neu['fz_sammlg'] = $response;
    }
}
#    echo "L 045 <br>";
$uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";

if (! file_exists($uploaddir)) {
    mkdir($uploaddir, 0770, true);
}

# var_dump($_FILES['uploaddatei_01']); echo "<br>L 053 <br>";
if (isset($_FILES['uploaddatei_01']) && $_FILES['uploaddatei_01']['name'] != "" ) {
    $neu['fz_bild_1'] = VF_Upload($uploaddir, '01');
}
if (isset($_FILES['uploaddatei_02']) && $_FILES['uploaddatei_02']['name'] != "" ) {
    $neu['fz_bild_2'] = VF_Upload($uploaddir, '02');
}
if (isset($_FILES['uploaddatei_03']) && $_FILES['uploaddatei_03']['name'] != "" ) {
    $neu['fz_bild_3'] = VF_Upload($uploaddir, '03');
}
if (isset($_FILES['uploaddatei_04']) && $_FILES['uploaddatei_04']['name'] != "" ) {
    $neu['fz_bild_4'] = VF_Upload($uploaddir, '04');
}

$neu['fz_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if (isset($neu['suggestAufbauer'])) {
    unset ($neu['suggestAufbauer']);
}
if (isset($neu['suggestEigener'])) {
    unset ($neu['suggestEigener']);
}
if (isset($neu['suggestHersteller'])) {
    unset ($neu['suggestHersteller']);
}
if (isset($neu['suggestTaktisch'])) {
    unset ($neu['suggestTaktisch']);
}
if (isset($neu['suggestUrheber'])) {
    unset ($neu['suggestUrheber']);
}
if (isset($neu['suggestTaktisch'])) {
    unset ($neu['suggestTaktisch']);
}
if (isset($neu['aufbauer']) && $neu['aufbauer'] != '') {
    $neu['fz_aufbauer'] = $neu['aufbauer'];
    unset($neu['aufbauer']);
}
if (isset($neu['hersteller']) && $neu['hersteller'] != '') {
    $neu['fz_herstell_fg'] = $neu['hersteller'];
    unset($neu['hersteller']);
}
if (isset($neu['eigentuemer']) && $neu['eigentuemer'] != '') {
    # $neu['fz_taktbez'] = $neu['eigentuemer'];
    unset($neu['eigentuemer']);
}
if (isset($neu['urheber']) && $neu['urheber'] != '') {
    # $neu['fz_taktbez'] = $neu['urheber'];
    unset($neu['urheber']);
}
if (isset($neu['taktisch']) && $neu['taktisch'] != '') {
    $neu['fz_taktbez'] = $neu['taktisch'];
    unset($neu['taktisch']);
}

if (stripos($neu['fz_taktbez'], " - ") >= 1) {
    $in_ar = explode (" - ", $neu['fz_taktbez']);
    $neu['fz_taktbez'] = $in_ar[0];
}

if ($neu['fz_id'] == 0) { # neueingabe
    
    Cr_n_ma_fz_beschr($tabelle_a);
   
    $sql = "INSERT INTO $tabelle_a (
                fz_eignr,fz_sammlg,fz_taktbez,fz_hist_bezeichng,fz_baujahr,fz_indienstst,  fz_ausdienst, \n
                fz_zeitraum,fz_allg_beschr,fz_herstell_fg,fz_typ,fz_modell,    \n
                fz_motor,fz_antrieb,fz_geschwindigkeit,fz_aufbauer,fz_aufb_typ,fz_besatzung,fz_beschreibg_det,   \n
                fz_bild_1,fz_b_1_komm,fz_bild_2,fz_b_2_komm,fz_bild_3,fz_b_3_komm,fz_bild_4,fz_b_4_komm,   \n
                fz_zustand,fz_ctif_klass,fz_ctif_date,fz_ctif_juroren,fz_ctif_darst_jahr,         \n
                fz_l_tank,fz_l_monitor,fz_l_pumpe,                                   \n
                fz_t_kran,fz_t_winde,fz_t_leiter,fz_t_abschlepp,fz_t_beleuchtg,fz_t_strom,fz_g_atemsch, \n
                fz_eigent_freig,fz_verfueg_freig,\n
                fz_pruefg_id,fz_pruefg,fz_aenduid    \n
              ) VALUE (
                '$neu[fz_eignr]','$neu[fz_sammlg]','$neu[fz_taktbez]','$neu[fz_hist_bezeichng]','$neu[fz_baujahr]','$neu[fz_indienstst]','$neu[fz_ausdienst]',    \n
                '$neu[fz_zeitraum]','$neu[fz_allg_beschr]','$neu[fz_herstell_fg]', '$neu[fz_typ]','$neu[fz_modell]',             \n
                '$neu[fz_motor]','$neu[fz_antrieb]','$neu[fz_geschwindigkeit]','$neu[fz_aufbauer]','$neu[fz_aufb_typ]','$neu[fz_besatzung]','$neu[fz_beschreibg_det]',     \n
                '$neu[fz_bild_1]','$neu[fz_b_1_komm]','$neu[fz_bild_2]','$neu[fz_b_2_komm]','$neu[fz_bild_3]','$neu[fz_b_3_komm]','$neu[fz_bild_4]','$neu[fz_b_4_komm]',  \n
                '$neu[fz_zustand]','$neu[fz_ctif_klass]','$neu[fz_ctif_date]','$neu[fz_ctif_juroren]','$neu[fz_ctif_darst_jahr]',      \n
                '$neu[fz_l_tank]','$neu[fz_l_monitor]','$neu[fz_l_pumpe]',     \n
                '$neu[fz_t_kran]','$neu[fz_t_winde]','$neu[fz_t_leiter]','$neu[fz_t_abschlepp]','$neu[fz_t_beleuchtg]','$neu[fz_t_strom]','$neu[fz_g_atemsch]', \n
                '$neu[fz_eigent_freig]','$neu[fz_verfueg_freig]',         \n
                '$neu[fz_pruefg_id]','$neu[fz_pruefg]','$neu[fz_aenduid]'
               )";

    # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
    # echo mysqli_error($db) . "  L 0149: \$sql $sql <br/>";

    $neu['fz_id'] = mysqli_insert_id($db);

} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #s 
        if ($name == "fz_bild_101" || $name == "fz_bild_202" || $name == "fz_bild_303" || $name == "fz_bild_404" ) {
            continue;
        } #

        if ($name == "phase") {
            continue;
        } #
        if ($name == "fz_uidaend") {
            continue;
        } #

        if ($name == "tabelle") {
            continue;
        }
        if ($name == "sa_name") {
            continue;
        }
        
        if (substr($name, 0, 4) == 'leve') {
            continue;
        }
        if ($name == 'aufbauer' || $name == 'eigentuemer' || $name == 'hersteller' || $name == 'taktisch' || $name == 'urheber' || $name == 'fo_org' ) {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    if ($_SESSION[$module]['all_upd']) {
        $sql = "UPDATE $tabelle_a SET  $updas WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "'";
        if ($debug) {
            echo '<pre class=debug> L 0197: \$sql $sql </pre>';
        }
        
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
    }

}

header("Location: VF_FZ_List.php");  # ?ID=".$_SESSION[$module]['fz_sammlung']

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph1.inc.php beendet</pre>";
}
?>