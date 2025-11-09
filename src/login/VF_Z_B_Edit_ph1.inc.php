<?php

/**
 * Benutzervrwaltung, Warten, Daten schreiben
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_B_Edit_ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php ist gestarted</pre>";
}

$neu['be_ort'] = mb_convert_case($neu['be_ort'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['be_adresse'] = mb_convert_case($neu['be_adresse'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
                                                                                  # $neu['be_'] = mb_convert_case($neu['be_'] ,MB_CASE_TITLE,'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if (! isset($neu['be_mitglnr'])) {
    $neu['be_mitglnr'] = "";
}
if (! isset($neu['eig_id'])) {
    $neu['eig_id'] = "";
}
if (! isset($neu['be_uidaend'])) {
    $neu['be_uidaend'] = $p_uid;
}

$be_id = $neu['be_id'];

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if ($be_id == "0") {
    $sql = "INSERT INTO fh_benutzer (
                be_org_typ,be_org_name,be_mitglnr,be_anrede,be_titel,be_vname,be_name,be_n_titel,
                be_adresse,be_staat,be_plz,be_ort,be_telefon,be_fax,
                be_email,eig_id,be_uidaend
              ) VALUE (
               '$neu[be_org_typ]','$neu[be_org_name]','$neu[be_mitglnr]','$neu[be_anrede]','$neu[be_titel]','$neu[be_vname]','$neu[be_name]','$neu[be_n_titel]',
               '$neu[be_adresse]','$neu[be_staat]','$neu[be_plz]','$neu[be_ort]','$neu[be_telefon]','$neu[be_fax]',
               '$neu[be_email]','$neu[eig_id]','$neu[be_uidaend]'
               )";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z B Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);

    $ben_id = mysqli_insert_id($db);

    # # Zugriffs daten erstellen !!!!

    $odlpw = "";
    $newpw = crypt('banane1a', '$1$banane1a$');
    $sql_zu = "INSERT INTO `fh_zugriffe_n` (
    `zu_id`,`zu_pw_enc`,`zu_eignr_1`,`zu_eignr_2`,`zu_eignr_3`,`zu_eignr_4`,`zu_eignr_5`,
    zu_SUC,zu_F_G,zu_F_M,zu_S_G,zu_PSA,zu_ARC,zu_INV,zu_OEF,zu_MVW,zu_ADM,
    `zu_uidaend`) VALUES
    (
    '$ben_id','$newpw','$neu[eig_id]','$neu[eig_id]','$neu[eig_id]','$neu[eig_id]','$neu[eig_id]',
    'A','Q','Q','Q','Q','Q','Q','A','N','N',
    '$neu[be_uidaend]')";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z B Edit ph1 $sql_ZU </pre>";
    echo "</div>";
    
    $result_zu = SQL_QUERY($db, $sql_zu);
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
        } #

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE fh_benutzer SET  $updas WHERE `be_id`='$be_id'";
    if ($debug) {
        echo '<pre class=debug> L 052: \$sql $sql </pre>';
    }
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z B Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
}

if ($debug) {
    echo "<pre class=debug>VF_Z_B_Edit_ph1.inc.php beendet</pre>";
}
?>