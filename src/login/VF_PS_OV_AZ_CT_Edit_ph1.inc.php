<?php

/**
 * Auszeichnungs- Verwaltung Vereins- Auszeichnungen, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_CT_Edit_ph1.inc.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

$ac_id = $neu['ac_id'];

$neu['ac_fw_id'] = $_SESSION[$module]['fw_id'];

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = "AOrd_Verz/PSA/AUSZ/" . $_SESSION[$proj]['fw_bd_abk'] . "/";
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['ac_wettbsp_v'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['ac_wettbsp_r'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['ac_gr_med_go_v'] = VF_Upload($uploaddir, 3);
    }
    if ($_FILES['uploaddatei_4']['name'] != "" ) {
        $neu['ac_gr_med_go_r'] = VF_Upload($uploaddir, 4);
    }
    
    if ($_FILES['uploaddatei_5']['name'] != "" ) {
        $neu['ac_gr_med_si_v'] = VF_Upload($uploaddir, 5);
    }
    
    if ($_FILES['uploaddatei_6']['name'] != "" ) {
        $neu['ac_gr_med_si_r'] = VF_Upload($uploaddir, 6);
    }
    
    if ($_FILES['uploaddatei_7']['name'] != "" ) {
        $neu['ac_gr_med_br_v'] = VF_Upload($uploaddir, 7);
    }
    
    if ($_FILES['uploaddatei_8']['name'] != "" ) {
        $neu['ac_gr_med_br_r'] = VF_Upload($uploaddir, 8);
    }
    
    if ($_FILES['uploaddatei_9']['name'] != "" ) {
        $neu['ac_kl_med_go_v'] = VF_Upload($uploaddir, 9);
    }
    
    if ($_FILES['uploaddatei_10']['name'] != "" ) {
        $neu['ac_kl_med_go_r'] = VF_Upload($uploaddir, 10);
    }
    
    if ($_FILES['uploaddatei_11']['name'] != "" ) {
        $neu['ac_kl_med_si_v'] = VF_Upload($uploaddir, 11);
    }
    
    if ($_FILES['uploaddatei_12']['name'] != "" ) {
        $neu['ac_kl_med_si_r'] = VF_Upload($uploaddir, 12);
    }
    
    if ($_FILES['uploaddatei_13']['name'] != "" ) {
        $neu['ac_kl_med_br_v'] = VF_Upload($uploaddir, 13);
    }
    
    if ($_FILES['uploaddatei_14']['name'] != "" ) {
        $neu['ac_kl_med_br_r'] = VF_Upload($uploaddir, 14);
    }
    
    if ($_FILES['uploaddatei_15']['name'] != "" ) {
        $neu['ac_so_med_go_v'] = VF_Upload($uploaddir, 15);
    }
    
    if ($_FILES['uploaddatei_16']['name'] != "" ) {
        $neu['ac_so_med_go_r'] = VF_Upload($uploaddir, 16);
    }
    
    if ($_FILES['uploaddatei_17']['name'] != "" ) {
        $neu['ac_so_med_si_v'] = VF_Upload($uploaddir, 17);
    }
    
    if ($_FILES['uploaddatei_18']['name'] != "" ) {
        $neu['ac_so_med_si_r'] = VF_Upload($uploaddir, 18);
    }
    
    if ($_FILES['uploaddatei_19']['name'] != "" ) {
        $neu['ac_so_med_br_v'] = VF_Upload($uploaddir, 19);
    }
    
    if ($_FILES['uploaddatei_20']['name'] != "" ) {
        $neu['ac_so_med_br_r'] = VF_Upload($uploaddir, 20);
    }
    
    if ($_FILES['uploaddatei_21']['name'] != "" ) {
        $neu['ac_urkund_1'] = VF_Upload($uploaddir, 21);
    }
    
    if ($_FILES['uploaddatei_22']['name'] != "" ) {
        $neu['ac_urkund_2'] = VF_Upload($uploaddir, 22);
    }
    
    if ($_FILES['uploaddatei_23']['name'] != "" ) {
        $neu['ac_fabz_v'] = VF_Upload($uploaddir, 23);
    }
    
    if ($_FILES['uploaddatei_24']['name'] != "" ) {
        $neu['ac_fabz_r'] = VF_Upload($uploaddir, 24);
    }
    
    if ($_FILES['uploaddatei_25']['name'] != "" ) {
        $neu['ac_teiln_v'] = VF_Upload($uploaddir, 25);
    }
    
    if ($_FILES['uploaddatei_26']['name'] != "" ) {
        $neu['ac_teiln_r'] = VF_Upload($uploaddir, 26);
    }
}

if ($debug) {
    echo '<pre class=debug>';
    echo 'L 129: <hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($ac_id == 0)  { # neueigabe
    $sql = "INSERT INTO $tabelle (
                ac_fw_id,ac_ab_id,ac_beschr, ac_wettbsp_v,ac_wettb_dok_v,ac_wettbsp_r,ac_wettb_dok_r,
                ac_gr_med_go_v,ac_gr_med_g_dok_v,ac_gr_med_go_r,ac_gr_med_g_dok_r, ac_gr_med_si_v,ac_gr_med_si_r,ac_gr_med_br_v,ac_gr_med_br_r,
                ac_kl_med_go_v,ac_kl_med_g_dok_v,ac_kl_med_go_r,ac_kl_med_g_dok_r, ac_kl_med_si_v,ac_kl_med_si_r,ac_kl_med_br_v,ac_kl_med_br_r,
                ac_so_med_go_v,ac_so_med_g_dok_v,ac_so_med_go_r,ac_so_med_g_dok_r, ac_so_med_si_v,ac_so_med_si_r,ac_so_med_br_v,ac_so_med_br_r,
                ac_so_beschr_1,ac_fabz_v,ac_fabz_dok_v,ac_fabz_r,ac_fabz_dok_r,ac_teiln_v,ac_teiln_dok_v,ac_teiln_r,ac_teiln_dok_r,
                ac_urkund_1,ac_urk_beschr_1,ac_urkund_2,ac_urk_beschr_2,ac_aend_uid,ac_aenddat
              ) VALUE (
               '$neu[ac_fw_id]','$neu[ac_ab_id]','$neu[ac_beschr]','$neu[ac_wettbsp_v]','$neu[ac_wettb_dok_v]','$neu[ac_wettbsp_r]','$neu[ac_wettb_dok_r]',
               '$neu[ac_gr_med_go_v]','$neu[ac_gr_med_g_dok_v]','$neu[ac_gr_med_go_r]','$neu[ac_gr_med_g_dok_r]','$neu[ac_gr_med_si_v]','$neu[ac_gr_med_si_r]','$neu[ac_gr_med_br_v]','$neu[ac_gr_med_br_r]',
               '$neu[ac_kl_med_go_v]','$neu[ac_kl_med_g_dok_v]','$neu[ac_kl_med_go_r]','$neu[ac_kl_med_g_dok_r]','$neu[ac_kl_med_si_v]','$neu[ac_kl_med_si_r]','$neu[ac_kl_med_br_v]','$neu[ac_kl_med_br_r]',
               '$neu[ac_so_med_go_v]','$neu[ac_so_med_g_dok_v]','$neu[ac_so_med_go_r]','$neu[ac_so_med_g_dok_r]','$neu[ac_so_med_si_v]','$neu[ac_so_med_si_r]','$neu[ac_so_med_br_v]','$neu[ac_so_med_br_r]',
               '$neu[ac_so_beschr_1]','$neu[ac_fabz_v]','$neu[ac_fabz_dok_v]','$neu[ac_fabz_r]','$neu[ac_fabz_dok_r]','$neu[ac_teiln_v]','$neu[ac_teiln_dok_v]','$neu[ac_teiln_r]','$neu[ac_teiln_dok_r]',
               '$neu[ac_urkund_1]','$neu[ac_urk_beschr_1]','$neu[ac_urkund_2]','$neu[ac_urk_beschr_2]','$p_uid',now()
               )";
    
    $result = SQL_QUERY($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
    $ab_id = $_SESSION['AUSZ']['ab_id'];
    header("Location: VF_PS_OV_AD_Edit.php?ID=$ab_id");
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
        if ($name == "ac_wettbsp_v1") {
            continue;
        }
        if ($name == "ac_wettbsp_r2") {
            continue;
        }
        if ($name == "ac_gr_med_go_v3") {
            continue;
        }
        if ($name == "ac_gr_med_go_r4") {
            continue;
        }
        if ($name == "ac_gr_med_si_v5") {
            continue;
        }
        if ($name == "ac_gr_med_si_r6") {
            continue;
        }
        if ($name == "ac_gr_med_br_v7") {
            continue;
        }
        if ($name == "ac_gr_med_br_r8") {
            continue;
        }
        if ($name == "ac_kl_med_go_v9") {
            continue;
        }
        if ($name == "ac_kl_med_go_r10") {
            continue;
        }
        if ($name == "ac_kl_med_si_v11") {
            continue;
        }
        if ($name == "ac_kl_med_si_r12") {
            continue;
        }
        if ($name == "ac_kl_med_br_v13") {
            continue;
        }
        if ($name == "ac_kl_med_br_r14") {
            continue;
        }
        if ($name == "ac_so_med_go_v15") {
            continue;
        }
        if ($name == "ac_so_med_go_r16") {
            continue;
        }
        if ($name == "ac_so_med_si_v17") {
            continue;
        }
        if ($name == "ac_so_med_si_r18") {
            continue;
        }
        if ($name == "ac_so_med_br_v19") {
            continue;
        }
        if ($name == "ac_so_med_br_r20") {
            continue;
        }
        if ($name == "ac_urkund_121") {
            continue;
        }
        if ($name == "ac_urkund_222") {
            continue;
        }
        if ($name == "ac_fabz_v23") {
            continue;
        }
        if ($name == "ac_fabz_r24") {
            continue;
        }
        if ($name == "ac_teiln_v25") {
            continue;
        }
        if ($name == "ac_teiln_r26") {
            continue;
        }
        if ($name == "ac_bild_v") {
            continue;
        }
        if ($name == "ac_bild_m") {
            continue;
        }
        if ($name == "ac_bild_m_r") {
            continue;
        }
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    $sql = "UPDATE $tabelle SET  $updas WHERE `ac_id`='$ac_id'";
    if ($debug) {
        echo '<pre class=debug> L 0127: \$sql $sql </pre>';
    }
    
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QuERY($db, $sql);
    
    $fw_id = $_SESSION[$module]['fw_id'];
    header("Location: VF_PS_OV_O_Edit.php?ID=$fw_id");
}
if ($debug) {
    echo "<pre class=debug>VF_PS_OV_AZ_CT_Edit_ph1.inc.php ist beendet</pre>";
}
?>