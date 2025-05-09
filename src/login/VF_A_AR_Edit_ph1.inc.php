<?php
/**
 * Wartung Archivalien, abspeichern
 *
 * @author josef Rohowsky - neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_A_AR_Edit_ph1.inc.php ist gestarted</pre>";
}

$neu['ad_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>L 15: $neu: <br>';
    print_r($neu);
    echo '</pre>';
}

$neu['in_eignr'] = $_SESSION['Eigner']['eig_eigner'];

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/" . $neu['ad_sg'] . "/" . $neu['ad_subsg'] . "/";
    # echo "L 072 \$uploaddir $uploaddir <br/>";
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['ad_doc_1'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['ad_doc_2'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['ad_doc_3'] = VF_Upload($uploaddir, 3);
    }
    if ($_FILES['uploaddatei_4']['name'] != "" ) {
        $neu['ad_doc_4'] = VF_Upload($uploaddir, 4);
    }
}

if (isset($neu['auto'])) {
    $ei_arr = explode("-",$neu['auto']);
    $neu['ad_neueigner'] = $ei_arr[0];
}


if ($neu['ad_id'] == 0) { # neueingabe
    $arcnewnr = "";
    $arcsg = $neu['ad_sg'];
    $arcssg = $neu['ad_subsg'];
    $arclcsg = $neu['ad_lcsg'];
    $arclcssg = $neu['ad_lcssg'];
    $sql_nr = "SELECT * FROM `$tabelle_a`
                WHERE `ad_sg`='$arcsg' AND `ad_subsg`='$arcssg' AND `ad_lcsg`='$arclcsg' AND `ad_lcssg`='$arclcssg' ";
    $return_nr = SQL_QUERY($db, $sql_nr); 
    if ($return_nr) {
        $row = mysqli_fetch_object($return_nr);
        # $flnr = $row->ad_ao_fortlnr;
        $numrow = mysqli_num_rows($return_nr);
        $neu['ad_ao_fortlnr'] = $numrow + 1;
    } else {
        $neu['ad_ao_fortlnr'] = 1;
    }

    $sql = "INSERT INTO $tabelle_a (
                ad_eignr,ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_ao_fortlnr,ad_sammlg,
                ad_doc_date,ad_type,ad_format,
                ad_keywords,ad_beschreibg,ad_wert_orig,ad_orig_waehrung,ad_wert_kauf,ad_kauf_waehrung,
                ad_wert_besch,ad_besch_waehrung,ad_namen,ad_doc_1,
                ad_doc_2,ad_doc_3,ad_doc_4,ad_isbn,ad_lagerort,
                ad_l_raum,ad_l_kasten,ad_l_fach,
                ad_l_pos_x,ad_l_pos_y,ad_neueigner,ad_uidaend,ad_aenddat
              ) VALUE (
                '$neu[ad_eignr]','$neu[ad_sg]','$neu[ad_subsg]','$neu[ad_lcsg]','$neu[ad_lcssg]','$neu[ad_ao_fortlnr]','$neu[ad_sammlg]',
                '$neu[ad_doc_date]','$neu[ad_type]','$neu[ad_format]',
                '$neu[ad_keywords]','$neu[ad_beschreibg]','$neu[ad_wert_orig]','$neu[ad_orig_waehrung]','$neu[ad_wert_kauf]','$neu[ad_kauf_waehrung]',
                '$neu[ad_wert_besch]','$neu[ad_besch_waehrung]','$neu[ad_namen]','$neu[ad_doc_1]',
                '$neu[ad_doc_2]','$neu[ad_doc_3]','$neu[ad_doc_4]','$neu[ad_isbn]','$neu[ad_lagerort]',
                '$neu[ad_l_raum]','$neu[ad_l_kasten]','$neu[ad_l_fach]',
                '$neu[ad_l_pos_x]','$neu[ad_l_pos_y]','$neu[ad_neueigner]','$neu[ad_uidaend]',now()
               )";

    if ($debug) {
        echo "<pre class=debug>";
        print_r($return_fi);
        echo "<br> \$sql $sql <br>";
        echo "</pre>";
    }

    $result = SQL_QUERY($db, $sql);

    $neu['ad_id'] = mysqli_insert_id($db);
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
        if ($name == "ad_doc_11") {
            continue;
        } #
        if ($name == "ad_doc_22") {
            continue;
        }
        if ($name == "ad_doc_33") {
            continue;
        }
        if ($name == "ad_doc_44") {
            continue;
        } #

        if ($name == 'ak_s0') {
            continue;
        }
        if (substr($name, 0, 4) == 'l_ad') {
            continue;
        }
        if (substr($name, 0, 4) == 'l_sb') {
            continue;
        }

        if ($name == "in_eignr") {
            continue;
        } #
        if ($name == "InvNr") {
            continue;
        } #
        if ($name == "eigentmr") {
            continue;
        } #

        if ($name == "ArchNr") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "auto") {
            continue;
        } #
        if (substr($name,0,4) == "leve") {
            continue;
        } #

        if (substr($name,0,10) == "ad_lcssg_s") {
            continue;
        } #
       
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
  
    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE $tabelle_a SET  $updas WHERE `ad_id`='" . $_SESSION[$module]['ad_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

    $debug = False;
}

if ($neu['ad_namen'] != "") {
    VF_Add_Namen($tabelle_a, $neu['ad_id'], 'ad_id', $neu['ad_namen']);
}

if ($neu['ad_keywords'] != "") {
    VF_Add_findbuch($tabelle_a, $neu['ad_keywords'], "ad_keywords", $neu['ad_id'], $neu['ad_eignr']);
}

header("Location: VF_A_AR_List.php");

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_A_AR_Edit_ph1.inc.php beendet</pre>";
}
?>