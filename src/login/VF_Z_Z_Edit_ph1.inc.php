<?php

/**
 * Wartung der Zugriffsberechtgungen der Benutzer, Dten schreiben
 *
 * @author Josef Rohowsky -  neu 2018
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_Z_Edit_ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_Z_Z_Edit_ph1.inc.php ist gestarted </pre>";
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

$zu_id = $neu['zu_id'];
if ($zu_id == 0) { // Neueingabe
    if ($neu['passwd'] != "" and $neu['passwd'] == $neu['passwd_K']) {
        $neu['zu_pw_enc'] = crypt($neu['passwd'], '$1$banane1a$');
    }
    $neu['zu_id'] = $_SESSION[$module]['neu_be_id'];

    $sql = "INSERT INTO $tabelle (
                  zu_uid,zu_pw_enc,zu_ref_leiter,zu_eignr_1,zu_eignr_2,zu_eignr_3,zu_eignr_4,zu_eignr_5,
                  ZU_F_G,zu_F_M,zu_S_G,zu_PSA,zu_ARC,zu_INV,zu_OEF,zu_MVW,zu_ADM,zu_SUC
                  zu_mitglverw,zu_meldliste,
                  zu_valid_until,zu_uidaend
              ) VALUE (
               '$neu[zu_uid]','$neu[zu_pw_enc]','$neu[zu_ref_leiter]','$neu[zu_eignr_1]','$neu[zu_eignr_2]','$neu[zu_eignr_3]','$neu[zu_eignr_4]','$neu[zu_eignr_5]',
               '$neu[zu_mitglverw]','$neu[zu_meldliste]',
               '$neu[zu_F_G]','$neu[zu_F_M]','$neu[zu_S_G]','$neu[zu_PSA]','$neu[zu_ARC]','$neu[zu_INV]','$neu[zu_OEF]','$neu[zu_MVW]','$neu[zu_ADM]','$neu[zu_SUC]',
               '$neu[zu_valid_until]','$p_uid'
               )";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z Z Edit $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
} else {

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
        }
        if ($name == "passwd") {
            continue;
        } #
        if ($name == "passwd_K") {
            continue;
        } #
          # if ($name == "zu_uid") {continue;}

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    if ($neu['passwd'] == "" && $neu['passwd_K'] == "") {
        $pw_chg = "";
        $pwupd = "";
    } else {
        if ($neu['passwd'] == $neu['passwd_K']) {
            $pw_chg = $neu['passwd'];
            $pw_chg_e = crypt($pw_chg, '$1$banane1a$');
            # } else {
            # $pw_chg_e = crypt($pw_chg, '$1$banane1a$');
        }
        $pwupd = "`zu_pw_enc`='$pw_chg_e'";
    }

    if ($pwupd == "") {
        $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    } else {
        $updas = $pwupd . $updas;
    }

    $sql = "UPDATE $tabelle SET  $updas WHERE `zu_id`='$zu_id'";
    if ($debug) {
        echo '<pre class=debug> L 0135: \$sql $sql </pre>';
    }

    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z Z Edit $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
}
header("Location: VF_Z_B_List.php");

// ==============================================================================================

if ($debug) {
    echo "<pre class=debug>VF_Z_Z_Edit_ph1.php beendet</pre>";
}
?>