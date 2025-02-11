<?php
/**
 * Fahrzeug- Liste, Wartun, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_FG_Edit_ph1.inc ist gestarted</pre>";
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
/*
 * if ($neu['sammlg'] != "") {
 * $neu['fz_sammlg'] = $neu['sammlg'];
 * }
 */
$uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/FZG/";

if (! file_exists($uploaddir)) {
    mkdir($uploaddir, 0770, true);
}

if ($_FILES['uploaddatei_01']['name'] != "" ) {
    $neu['fz_bild_1'] = VF_Upload($uploaddir, '01');
}
if ($_FILES['uploaddatei_02']['name'] != "" ) {
    $neu['fz_bild_2'] = VF_Upload($uploaddir, '02');
}

$neu['fz_aenduid'] = $_SESSION['VF_Prim']['p_uid'];

if (isset($neu['l_sb_s6']) && $neu['l_sb_s6'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s6']);
} elseif (isset($neu['l_sb_s5']) && $neu['l_sb_s5'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s5']);
} elseif (isset($neu['l_sb_s4']) && $neu['l_sb_s4'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s4']);
} elseif (isset($neu['l_sb_s3']) && $neu['l_sb_s3'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s3']);
} elseif (isset($neu['l_sb_s2']) && $neu['l_sb_s2'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s2']);
} elseif (isset($neu['l_sb_s1']) && $neu['l_sb_s1'] != "0|0|0|0|0|0") {
    $arr_sb = explode("|", $neu['l_sb_s1']);
}

if (isset($arr_sb)) {
    $neu['fz_suchbegr1'] = $arr_sb[0];
    $neu['fz_suchbegr2'] = $arr_sb[1];
    $neu['fz_suchbegr3'] = $arr_sb[2];
    $neu['fz_suchbegr4'] = $arr_sb[3];
    $neu['fz_suchbegr5'] = $arr_sb[4];
    $neu['fz_suchbegr6'] = $arr_sb[5];
}

if ($neu['fz_id'] == 0) { # neueingabe
    $sql = "INSERT INTO $tabelle_a (
                fz_eignr,fz_invnr,fz_sammlg,fz_name,fz_taktbez,
                fz_indienstst,fz_ausdienst,fz_zeitraum,fz_komment,fz_bild_1,
                fz_b_1_komm,fz_bild_2,fz_b_2_komm,fz_zustand,
                fz_herstell_fg,fz_baujahr,fz_ctifklass,
                fz_ctifdate,fz_beschreibg_det,fz_eigent_freig,fz_verfueg_freig,
                fz_pruefg_id,fz_pruefg,fz_suchbegr1,fz_suchbegr2,fz_suchbegr3,
                fz_suchbegr4,fz_suchbegr5,fz_suchbegr6,fz_aenduid,fz_aenddat
              ) VALUE (
                '$neu[fz_eignr]','$neu[fz_invnr]','$neu[fz_sammlg]','$neu[fz_name]','$neu[fz_taktbez]',
                '$neu[fz_indienstst]','$neu[fz_ausdienst]','$neu[fz_zeitraum]','$neu[fz_komment]','$neu[fz_bild_1]',
                '$neu[fz_b_1_komm]','$neu[fz_bild_2]','$neu[fz_b_2_komm]','$neu[fz_zustand]',
                '$neu[fz_herstell_fg]','$neu[fz_baujahr]','$neu[fz_ctifklass]',
                '$neu[fz_ctifdate]','$neu[fz_beschreibg_det]','$neu[fz_eigent_freig]','$neu[fz_verfueg_freig]',
                '$neu[fz_pruefg_id]','$neu[fz_pruefg]','$neu[fz_suchbegr1]','$neu[fz_suchbegr2]','$neu[fz_suchbegr3]',
                '$neu[fz_suchbegr4]','$neu[fz_suchbegr5]','$neu[fz_suchbegr6]','$neu[fz_aenduid]',now()
               )";

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);
    echo mysqli_error($db) . "  L 0149: \$sql $sql <br/>";

    $errno = mysqli_errno($db);
    if ($errno == "1146") {
        $sql_newT = "CREATE TABLE IF NOT EXISTS $tabelle_a LIKE $tabelle";
        mysqli_query($db, $sql_newT);
        $result = mysqli_query($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
    }

    $neu['fz_id'] = mysqli_insert_id($db);

    if ($neu['fz_sammlg'] == "BA") {
        if ($neu['fz_ctifdate'] != "") {
            $sql_in = "INSERT INTO `fz_ctif_klass` (
                 `fz_eignr`,`fz_id`,
                 `fz_klass_dat`, `fz_klasse`,`fz_darstjahr`,`fz_juroren`,
                 `fz_uidaend`
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

        if (substr($name, 0, 4) == 'l_sb') {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    if ($neu['fz_sammlg'] == "BA") {
        if ($_SESSION[$module]['all_upd']) {
            $sql = "UPDATE $tabelle_a SET  $updas WHERE `fz_id`='" . $_SESSION[$module]['fz_id'] . "'";
            if ($debug) {
                echo '<pre class=debug> L 0197: \$sql $sql </pre>';
            }

            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
            $result = SQL_QUERY($db, $sql);
        }
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
print_r($neu);
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
    if ($neu['fz_sammlg'] == "BA") {
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

header("Location: VF_FA_FG_List.php");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_FG_Edit_ph1.inc beendet</pre>";
}
?>