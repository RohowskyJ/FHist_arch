<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky  neu 2019
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */
$debug = true;
$Inc_Arr[] = "VF_FZ_MaG_Edit_ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_FZ_MaG_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$neu['ge_bezeich'] = mb_convert_case($neu['ge_bezeich'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$neu['ge_aenduid'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

$neu['ge_eignr'] = $_SESSION['Eigner']['eig_eigner'];

if (isset($_POST['level1']) != "") {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {

    } else {
        $neu['ge_sammlg'] = $response;
    }
}

$pic_cnt = $neu['pic_cnt'];
echo "L 045 pic_cnt $pic_cnt <br>";
for ($i=1;$i<=$pic_cnt;$i++) {
    
    if ($i < 5) {
        if ($neu['bild_datei_'.$i] != "") {
            $neu['ge_foto_'.$i] = $neu['bild_datei_'.$i];
            echo "L 051 foto $i ".$neu['fm_foto_'.$i]."<br>";
        }
    } else {
        switch ($i) {
            case '5':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g1_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            
            case '6':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g2_foto'] = $neu['bild_datei_'.$i];
                }
                break;
             case '7':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g3_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '8':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g4_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '9':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g5_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '10':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g6_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '11':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g7_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '12':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g8_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case '13':
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g9_foto'] = $neu['bild_datei_'.$i];
                }
                break;
            case 14;
                if ($neu['bild_datei_'.$i] != "") {
                    $neu['ge_g10_foto'] = $neu['bild_datei_'.$i];
                }
                break;
        }
    }
}

$gesa_arr = explode(" ", $neu['ge_sammlg']);
$neu['ge_sammlg'] = $gesa_arr[0];

/* Sammlung aufbereiten */
if (isset($_POST['level1'])) {
    $res =  VF_Multi_Sel_Input();
    if ($res != "" && $res !="Nix") {
        $neu['ge_sammlg'] = VF_Multi_Sel_Input();
    }
}

$neu['ge_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if ($neu['ge_id'] == 0) { # neueingabe

    Cr_n_ma_geraet($tabelle_a);

    $sql = "INSERT INTO $tabelle_a (
                ge_eignr,ge_invnr,ge_bezeich,ge_type,ge_leistg,ge_lei_bed,
                ge_leinh,ge_herst,ge_baujahr,ge_indienst,ge_ausdienst,ge_komment,ge_gesgew,
                ge_mo_herst,ge_mo_typ,ge_mo_sernr,ge_mo_treibst,ge_mo_leistung,ge_mo_leibed,ge_mo_leinh,
                ge_ag_herst,ge_ag_type,ge_ag_sernr,ge_ag_leistung,ge_ag_leibed,ge_ag_leinh,
                ge_foto_1,ge_komm_1,ge_foto_2,ge_komm_2,ge_foto_3,ge_komm_3,ge_foto_4,ge_komm_4,
                ge_sammlg,
                ge_g1_name,ge_g1_sernr,ge_g1_beschr,ge_g1_foto,
                ge_g2_name,ge_g2_sernr,ge_g2_beschr,ge_g2_foto,
                ge_g3_name,ge_g3_sernr,ge_g3_beschr,ge_g3_foto,
                ge_g4_name,ge_g4_sernr,ge_g4_beschr,ge_g4_foto,
                ge_g5_name,ge_g5_sernr,ge_g5_beschr,ge_g5_foto,
                ge_g6_name,ge_g6_sernr,ge_g6_beschr,ge_g6_foto,
                ge_g7_name,ge_g7_sernr,ge_g7_beschr,ge_g7_foto,
                ge_g8_name,ge_g8_sernr,ge_g8_beschr,ge_g8_foto,
                ge_g9_name,ge_g9_sernr,ge_g9_beschr,ge_g9_foto,
                ge_g10_name,ge_g10_sernr,ge_g10_beschr,ge_g10_foto,
                ge_fzg,ge_raum,ge_ort,ge_zustand,ge_pruef_id,ge_pruef_dat,ge_aenduid
              ) VALUE (
                '$neu[ge_eignr]','$neu[ge_invnr]','$neu[ge_bezeich]','$neu[ge_type]','$neu[ge_leistg]','$neu[ge_lei_bed]',
                '$neu[ge_leinh]','$neu[ge_herst]','$neu[ge_baujahr]','$neu[ge_indienst]','$neu[ge_ausdienst]','$neu[ge_komment]','$neu[ge_gesgew]',
                '$neu[ge_mo_herst]','$neu[ge_mo_typ]','$neu[ge_mo_sernr]','$neu[ge_mo_treibst]','$neu[ge_mo_leistung]','$neu[ge_mo_leibed]','$neu[ge_mo_leinh]',
                '$neu[ge_ag_herst]','$neu[ge_ag_type]','$neu[ge_ag_sernr]','$neu[ge_ag_leistung]','$neu[ge_ag_leibed]','$neu[ge_ag_leinh]',
                '$neu[ge_foto_1]','$neu[ge_komm_1]','$neu[ge_foto_2]','$neu[ge_komm_2]','$neu[ge_foto_3]','$neu[ge_komm_3]','$neu[ge_foto_4]','$neu[ge_komm_4]',
                '$neu[ge_sammlg]',
                '$neu[ge_g1_name]','$neu[ge_g1_sernr]','$neu[ge_g1_beschr]','$neu[ge_g1_foto]',
                '$neu[ge_g2_name]','$neu[ge_g2_sernr]','$neu[ge_g2_beschr]','$neu[ge_g2_foto]',
                '$neu[ge_g3_name]','$neu[ge_g3_sernr]','$neu[ge_g3_beschr]','$neu[ge_g3_foto]',
                '$neu[ge_g4_name]','$neu[ge_g4_sernr]','$neu[ge_g4_beschr]','$neu[ge_g4_foto]',
                '$neu[ge_g5_name]','$neu[ge_g5_sernr]','$neu[ge_g5_beschr]','$neu[ge_g5_foto]',
                '$neu[ge_g6_name]','$neu[ge_g6_sernr]','$neu[ge_g6_beschr]','$neu[ge_g6_foto]',
                '$neu[ge_g7_name]','$neu[ge_g7_sernr]','$neu[ge_g7_beschr]','$neu[ge_g7_foto]',
                '$neu[ge_g8_name]','$neu[ge_g8_sernr]','$neu[ge_g8_beschr]','$neu[ge_g8_foto]',
                '$neu[ge_g9_name]','$neu[ge_g9_sernr]','$neu[ge_g9_beschr]','$neu[ge_g9_foto]',
                '$neu[ge_g10_name]','$neu[ge_g10_sernr]','$neu[ge_g10_beschr]','$neu[ge_g10_foto]',
                '$neu[ge_fzg]','$neu[ge_raum]','$neu[ge_ort]','$neu[ge_zustand]','$neu[ge_pruef_id]','$neu[ge_pruef_dat]',
                '$neu[ge_aenduid]'
               )";

    # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>L 0152 $sql</pre>";
    $result = SQL_QUERY($db, $sql);
    $neu['ge_id'] = mysqli_insert_id($db);
} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) { # für alle Felder aus der tabelle
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        
        if (substr($name,0,3) != 'ge_') {
            continue;
        }
       
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle_a SET  $updas WHERE `ge_id`='" . $_SESSION[$module]['ge_id'] . "'";
    # echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
}

unset($_SESSION[$module]['Pct_Arr']);

header("Location: VF_FZ_MaFG_List.php?ID=".$_SESSION[$module]['sammlung']);

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MaG_Edit_ph1.inc.php beendet</pre>";
}
