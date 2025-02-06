<?php

/**
 * Unterstützer- Verwaltung, Date abspeichern
 *
 * @author Josef Rohowsky - neu 2020
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_M_Unt_Edit_ph1.inc.php ist gestarted</pre>";
}
/*
$neu['mi_org_name'] = mb_convert_case($neu['mi_org_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_name'] = mb_convert_case($neu['mi_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_vname'] = mb_convert_case($neu['mi_vname'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_titel'] = mb_convert_case($neu['mi_titel'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_n_titel'] = mb_convert_case($neu['mi_n_titel'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_ort'] = mb_convert_case($neu['mi_ort'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['mi_anschr'] = mb_convert_case($neu['mi_anschr'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
*/
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    var_dump($neu);
    echo '<hr>$_SESSION[$module] : ';
    print_r($_SESSION[$module]);
    echo '</pre>';
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($neu['fu_id'] == 0) { // Neu anlegen eines Mitglieds- Datensatzes
    $sql = "INSERT INTO fh_unterst (
              fu_kateg,fu_aktiv,fu_weihn_post,fu_zugr,fu_tit_vor,fu_tit_nach,
              fu_anrede,fu_dgr,fu_vname,fu_name,fu_plz,fu_ort,fu_adresse,
              fu_tel,fu_email,fu_orgname,fu_uidaend
              ) VALUE (
               '$neu[fu_kateg]','$neu[fu_aktiv]','$neu[fu_weihn_post]','$neu[fu_zugr]','$neu[fu_tit_vor]','$neu[fu_tit_nach]',
               '$neu[fu_anrede]','$neu[fu_dgr]','$neu[fu_vname]','$neu[fu_name]','$neu[fu_plz]','$neu[fu_ort]','$neu[fu_adresse]',
               '$neu[fu_tel]','$neu[fu_email]','$neu[fu_orgname]','$p_uid'
               )";

} else {
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
    
    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        
        if ($name == "phase") {
            continue;
        } #
        if ($name == "p_uid") {
            continue;
        } #
        # if ($name == "fo_aenduid") {continue;}
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    $sql = "UPDATE $tabelle SET  $updas WHERE `fu_id`='" . $neu['fu_id'] . "'";
    if ($debug) {
        echo '<pre class=debug> L 0112: \$sql $sql </pre>';
    }
    
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
}

$result = SQL_QUERY($db, $sql);

header ("Location: VF_M_Unterst_List.php");
if ($debug) {
    echo "<pre class=debug>VF_M_Unt_Edit_ph1.inc.php beendet</pre>";
}
?>