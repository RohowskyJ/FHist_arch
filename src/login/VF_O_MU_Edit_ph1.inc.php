<?php

/**
 * Museums- Daten- Wartung, Daten schreben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/Museen/";
    # echo "L 072 \$uploaddir $uploaddir <br/>";
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['mu_bildnam_1'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['mu_bildnam_2'] = VF_Upload($uploaddir, 2);
    }
}

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($mu_id == 0) { # neueingabe
    $sql = "INSERT INTO $tabelle (
               mu_staat,mu_bdland,mu_bez,mu_name,mu_bezeichng,mu_adresse_a,mu_plz_a,mu_ort_a,
               mu_adresse_p,mu_plz_p,mu_ort_p,mu_eigner,
               mu_kustos_titel,mu_kustos_vname,mu_kustos_name,mu_kustos_dgr,mu_kustos_tel,
               mu_kustos_fax,mu_kustos_handy,mu_kustos_intern,mu_kustos_email,
               mu_sammlbeg,mu_bildnam_1,mu_bildnam_2,mu_mustyp,mu_museigtyp,mu_sammlgschw,
               mu_besobj_1,mu_besobj_2,mu_besobj_3,mu_anz_obj,mu_archiv,
               mu_protbuch,mu_abzeich,mu_ausruest,mu_kleinger,mu_grossger,
               mu_toilett,mu_garderobe,mu_cafe,mu_sonst_anb,mu_rollstuhl,
               mu_beheinr,mu_oeffnung, mu_saison,mu_oez_mo,mu_oez_di,
               mu_oez_mi,mu_oez_do,mu_oez_fr,mu_oez_sa,mu_oez_so,
               mu_oez_fei,mu_f1_titel,mu_f1_vname,mu_f1_name,mu_f1_tel,
               mu_f1_handy,mu_f1_email,mu_f2_titel,mu_f2_vname,mu_f2_name,
               mu_f2_dgr,mu_f2_tel,mu_f2_handy,mu_f2_email,mu_uidaend,mu_aenddat
              ) VALUE (
                '$neu[mu_staat]','$neu[mu_bdland]','$neu[mu_bez]','$neu[mu_name]','$neu[mu_bezeichng]','$neu[mu_adresse_a]','$neu[mu_plz_a]','$neu[mu_ort_a]',
                '$neu[mu_adresse_p]','$neu[mu_plz_p]','$neu[mu_ort_p]','$neu[mu_eigner]',
                '$neu[mu_kustos_titel]','$neu[mu_kustos_vname]','$neu[mu_kustos_name]','$neu[mu_kustos_dgr]','$neu[mu_kustos_tel]',
                '$neu[mu_kustos_fax]','$neu[mu_kustos_handy]','$neu[mu_kustos_intern]','$neu[mu_kustos_email]',
                '$neu[mu_sammlbeg]','$neu[mu_bildnam_1]','$neu[mu_bildnam_2]','$neu[mu_mustyp]','$neu[mu_museigtyp]','$neu[mu_sammlgschw]',
                '$neu[mu_besobj_1]','$neu[mu_besobj_2]','$neu[mu_besobj_3]','$neu[mu_anz_obj]','$neu[mu_archiv]'
                '$neu[mu_protbuch]','$neu[mu_abzeich]','$neu[mu_ausruest]','$neu[mu_kleinger]','$neu[mu_grossger]',
                '$neu[mu_toilett]','$neu[mu_garderobe]','$neu[mu_cafe]','$neu[mu_sonst_anb]','$neu[mu_rollstuhl]',
                '$neu[mu_beheinr]','$neu[mu_oeffnung]','$neu[mu_saison]','$neu[mu_oez_mo]','$neu[mu_oez_di]',
                '$neu[mu_oez_mi]','$neu[mu_oez_do]','$neu[mu_oez_fr]','$neu[mu_oez_sa]','$neu[mu_oez_so]',
                '$neu[mu_oez_fei]','$neu[mu_f1_titel]','$neu[mu_f1_vname]','$neu[mu_f1_name]','$neu[mu_f1_tel]','$neu[mu_f1_dgr]',
                '$neu[mu_f1_handy]','$neu[mu_f1_email]','$neu[mu_f2_titel]','$neu[mu_f2_vname]','$neu[mu_f2_name]',
                '$neu[mu_f2_dgr]','$neu[mu_f2_tel]','$neu[mu_f2_handy]','$neu[mu_f2_email]','$p_uid',now()
               )";
    
    $result = SQL_QUERY($db, $sql);
    
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
        if ($name == "mu_bildnam_11") {
            continue;
        }
        if ($name == "mu_bildnam_22") {
            continue;
        }
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    $sql = "UPDATE $tabelle SET  $updas WHERE `mu_id`='$mu_id'";
    if ($debug) {
        echo '<pre class=debug> L 0127: \$sql $sql </pre>';
    }
    
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
    
    $mu_id = $_SESSION[$module]['mu_id'];
}

header("Location: VF_O_MU_List.php?Act=" . $_SESSION[$module]['Act']);
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph1.inc.php beendet</pre>";
}
?>