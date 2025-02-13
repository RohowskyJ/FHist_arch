<?php
/**
 * Fahrzeug- Liste, Wartun, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_FZ_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$neu['fz_name'] = mb_convert_case($neu['fz_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$neu['fz_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
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

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";
    
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0770, true);
    }
    
    if ($_FILES['uploaddatei_01']['name'] != "" ) {
        $neu['fz_bild_1'] = VF_Upload($uploaddir, '01');
    }
    if ($_FILES['uploaddatei_02']['name'] != "" ) {
        $neu['fz_bild_2'] = VF_Upload($uploaddir, '02');
    }
}

$neu['fz_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if ($neu['fz_id'] == 0) { # neueingabe
    
    Cre_n_ma_fahrzeug($tabelle_a);
    
    $sql = "INSERT INTO $tabelle_a (
                fz_eignr,fz_invnr,fz_sammlg,fz_name,fz_taktbez, \n
                fz_indienstst,fz_ausdienst,fz_zeitraum,fz_komment,fz_bild_1,\n
                fz_b_1_komm,fz_bild_2,fz_b_2_komm,fz_zustand,\n
                fz_herstell_fg,fz_baujahr,fz_ctifklass,\n
                fz_ctifdate,fz_beschreibg_det,fz_eigent_freig,fz_verfueg_freig,\n
                fz_pruefg_id,fz_pruefg,fz_aenduid\n
              ) VALUE (
                '$neu[fz_eignr]','$neu[fz_invnr]','$neu[fz_sammlg]','$neu[fz_name]','$neu[fz_taktbez]',\n
                '$neu[fz_indienstst]','$neu[fz_ausdienst]','$neu[fz_zeitraum]','$neu[fz_komment]','$neu[fz_bild_1]',\n
                '$neu[fz_b_1_komm]','$neu[fz_bild_2]','$neu[fz_b_2_komm]','$neu[fz_zustand]',\n
                '$neu[fz_herstell_fg]','$neu[fz_baujahr]','$neu[fz_ctifklass]',\n
                '$neu[fz_ctifdate]','$neu[fz_beschreibg_det]','$neu[fz_eigent_freig]','$neu[fz_verfueg_freig]',         \n
                '$neu[fz_pruefg_id]','$neu[fz_pruefg]','$neu[fz_aenduid]'
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
    # echo mysqli_error($db) . "  L 0149: \$sql $sql <br/>";

    $neu['fz_id'] = mysqli_insert_id($db);

    if (substr($neu['fz_sammlg'],0,4) == "MA_F") {
        if ($neu['fz_ctifdate'] != "") {
            $sql_in = "INSERT INTO `fz_ctif_klass` (
                 `fz_eignr`,`fz_id`,
                 `fz_klass_dat`, `fz_klasse`,`fz_darstjahr`,`fz_juroren`,
                 `fz_uidaend`\n
                  ) VALUES
                  (
                 '$neu[fz_eignr]','$neu[fz_id]',
                 '$neu[fz_ctifdate]','$neu[fz_ctifklass]','$neu[ct_darstjahr]','$neu[ct_juroren]',
                  '$neu[fz_aenduid]'
                  )";
            $result = SQL_QUERY($db, $sql_in);
            $errno = mysqli_errno($db);
        }

        // Einlesen und abspeichern der Daten

        $sql_fk = "SELECT * FROM `fz_katalog` WHERE `fk_eigner`='$neu[fz_eignr]' AND `fk_fzgid`='$neu[fz_id]' ";
        $return_fk = SQL_QUERY($db, $sql_fk) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
        $recnum = mysqli_num_rows($return_fk);
        $fk_ind = substr($neu['fz_indienstst'], - 4);
        if ($recnum == 0) {
            $sql_fk = "INSERT INTO fz_katalog (`fk_aera`,
               `fk_bj`, `fk_indienst`, `fk_eigner`, `fk_fzgid`,
               `fk_aenduid`
               ) VALUES (
              '$neu[fz_zeitraum]','$neu[fz_baujahr]','$neu[fz_indienstst]','$neu[fz_eignr]','$neu[fz_id]','$neu[fz_aenduid]'
               )";
        } else {
            $sql_fk = "UPDATE `fz_katalog` SET
               `fk_aera`='$neu[fz_zeitraum]',
               `fk_bj`='', `fk_indienst`='$neu[fz_indienstst]',
               `fk_aenduid`='$neu[fz_aenduid]'
                WHERE `fk_eigner`='$neu[fz_eignr]' AND `fk_fzgid`='$neu[fz_id]' LIMIT 1";
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $return_fk = SQL_QUERY($db, $sql_fk);
    }
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
        if ($name == "fz_bild_101") {
            continue;
        } #
        if ($name == "fz_bild_202") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "fz_uidaend") {
            continue;
        } #
        if ($name == "ct_juroren") {
            continue;
        }
        if ($name == "ct_darstjahr") {
            continue;
        }
        if ($name == "tabelle") {
            continue;
        }
        if ($name == "sa_name") {
            continue;
        }
        
        if (substr($name, 0, 4) == 'leve') {
            continue;
        }
    
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    #if ($neu['fz_sammlg'] == "BA") {
        if ($_SESSION[$module]['all_upd']) {
            $sql = "UPDATE $tabelle_a SET  $updas WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "'";
            if ($debug) {
                echo '<pre class=debug> L 0197: \$sql $sql </pre>';
            }

            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
            $result = SQL_QUERY($db, $sql);
        }
    if ($neu['fz_sammlg'] == "BA") {
        if (isset($neu['fz_klass_dat'])) {
            if ($neu['fz_klass_dat'] != "") {
                $sql_in = "UPDATE `fz_ctif_klass` SET
                 `fz_eignr`='$neu[fz_eignr]',`fz_id`='$neu[fz_id]',
                 `fz_klass_dat`='$neu[fz_klass_dat]',`fz_klasse`='$neu[fz_klasse]',
                 `fz_darstjahr`='$neu[ct_darstjahr]', `fz_juroren`='$neu[ct_juroren]',
                 `fz_uidaend`='$p_uid'
                 WHERE `fz_id`='$neu[fz_id]' AND `fz_eignr`='$neu[fz_eignr]' AND `fz_klass_dat`='$neu[fz_klass_dat]' LIMIT 1";

                $result = SQL_QUERY($db, $sql_in);
            }
        }
    }

    # update von fz_katalog
    $updas = "";
    $where_e = $where_i = "";
    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if ($name == "fz_indienstst") {
            $updas .= "fk_indienst = '$neu[fz_indienstst]',";
        }

        if ($name == "fz_zeitraum") {
            $updas .= "fk_aera = '$neu[fz_zeitraum]',";
        }
        if ($name == "fz_baujahr") {
            $updas .= "fk_bj = '$neu[fz_baujahr]',";
        }
        
        if ($name == 'fz_id') {
            $where_i = "fk_fzgid = '" . $neu[$name] . "'";
        }
        
    } # Ende der Schleife

    $updas = mb_substr($updas, 0,-1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    if (substr($neu['fz_sammlg'],0,4) == "MF_A") {
        if ($_SESSION[$module]['all_upd']) {
            $sql = "UPDATE fz_katalog SET  $updas WHERE fk_eigner= '" . $_SESSION['Eigner']['eig_eigner'] . "' AND $where_i ";
            if ($debug) {
                echo '<pre class=debug> L 0251: \$sql $sql </pre>';
            }

            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
            $result = SQL_QUERY($db, $sql);
        }

    }
}

header("Location: VF_FA_List.php?ID=".$_SESSION[$module]['fz_sammlung']);

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_FZ_Edit_ph1.inc.php beendet</pre>";
}
?>