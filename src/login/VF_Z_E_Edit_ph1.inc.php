<?php

/**
 * Liste der Eigentümer, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_E_Edit_ph1.inc.php ist gestarted</pre>";
}

$neu['ei_org_name'] = mb_convert_case($neu['ei_org_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['ei_name'] = mb_convert_case($neu['ei_name'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['ei_vname'] = mb_convert_case($neu['ei_vname'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['ei_titel'] = mb_convert_case($neu['ei_titel'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['ei_ort'] = mb_convert_case($neu['ei_ort'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
$neu['ei_adresse'] = mb_convert_case($neu['ei_adresse'], MB_CASE_TITLE, 'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben
                                                                                  # $neu['ei_'] = mb_convert_case($neu['ei_'] ,MB_CASE_TITLE,'UTF-8'); // Wandelt jeden ersten Buchstaben eines Wortes in einen Großbuchstaben

if ($neu['ei_name'] == "") {
    $neu['ei_name'] = $neu['ei_org_name'];
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if (!isset($neu['ei_wlinv'])) {$neu['ei_wlinv'] = "";}
if (!isset($neu['ei_voinv'])) {$neu['ei_voinv'] = "";}
if (!isset($neu['ei_voinf'])) {$neu['ei_voinf'] = "";}
if (!isset($neu['ei_vofo'])) {$neu['ei_vofo'] = "";}
if (!isset($neu['ei_voar'])) {$neu['ei_voar'] = "";}
if (!isset($neu['ei_drneu'])) {$neu['ei_drneu'] = "";}
if (!isset($neu['ei_wlpriv'])) {$neu['ei_wlpriv'] = "";}
if (!isset($neu['ei_wlmus'])) {$neu['ei_wlmus'] = "";}
if (!isset($neu['ei_vomus'])) {$neu['ei_vomus'] = "";}
/*
if (!isset($neu[''])) {$neu[''] = "";}
if (!isset($neu[''])) {$neu[''] = "";}
*/

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}
if ($neu['ei_id'] == "0") { // NeuItem

    $sql = "INSERT INTO fh_eigentuemer (
                ei_mitglnr , ei_staat, ei_bdld , ei_bezirk , ei_org_typ ,ei_org_name,
                kont_name , ei_fwkz ,
                ei_grdgj , ei_titel , ei_vname , ei_name , ei_dgr , ei_adresse ,
                ei_plz , ei_ort , ei_tel , ei_fax , ei_handy , ei_email ,
                ei_internet , ei_sterbdat , ei_abgdat , 
                ei_urh_kurzz, ei_media, ei_neueigner , ei_wlpriv , ei_vopriv ,
                ei_wlmus , ei_vomus , ei_wlinv , ei_voinv , ei_voinf , ei_vofo ,
                ei_voar , ei_drwvs , ei_drneu , ei_uidaend , ei_aenddat
              ) VALUE (
               '$neu[ei_mitglnr]','$neu[ei_staat]','$neu[ei_bdld]','$neu[ei_bezirk]','$neu[ei_org_typ]','$neu[ei_org_name]',
               '$neu[kont_name]','$neu[ei_fwkz]',
               '$neu[ei_grdgj]','$neu[ei_titel]','$neu[ei_vname]','$neu[ei_name]','$neu[ei_dgr]','$neu[ei_adresse]',
               '$neu[ei_plz]','$neu[ei_ort]','$neu[ei_tel]','$neu[ei_fax]','$neu[ei_handy]','$neu[ei_email]',
               '$neu[ei_internet]','$neu[ei_sterbdat]','$neu[ei_abgdat]',
               '$neu[ei_urh_kurzz]','$neu[ei_media]','$neu[ei_neueigner]','$neu[ei_wlpriv]','$neu[ei_vopriv]',
               '$neu[ei_wlmus]','$neu[ei_vomus]','$neu[ei_wlinv]','$neu[ei_voinv]','$neu[ei_voinf]','$neu[ei_vofo]',
               '$neu[ei_voar]','$neu[ei_drwvs]','$neu[ei_drneu]','$p_uid',now()
               )";

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
        } #
        if ($name == "tabelle") {
            continue;
        } 

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $caller = explode("/", $_SERVER['REQUEST_URI']);
    $cnt = count($caller);

    if ($caller[$cnt - 1] == "VF_M_Anmeld.php" or $_SESSION[$module]['all_upd']) {
        $sql = "UPDATE `fh_eigentuemer` SET  $updas WHERE `ei_id`='$neu[ei_id]'";
        if ($debug) {
            echo '<pre class=debug> L 0197: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
    }
    
    if (strlen($neu['ei_media']) > 2 ){ // erweiterung angelegt? je medium ein Record!
        $med_arr = explode(",",$neu['ei_media']);
        foreach($med_arr as $medium) {
            $sql_m = "SELECT * FROM fh_eign_urh WHERE fs_eigner='".$neu['ei_id']."'  AND fs_typ='$medium' AND fs_urh_kurzz ='".$neu['ei_urh_kurzz']."' " ;
            $ret_m = SQL_QUERY($db,$sql_m);
            $num_rows = mysqli_num_rows($ret_m);
            echo "L 0114 num_rows $num_rows <br>";
            if ($num_rows === 0 ) {
                $sql_i = "INSERT INTO fh_eign_urh (fs_eigner,fs_typ,fs_fotograf,fs_urh_nr,fs_urh_kurzz,fs_uidaend
                      ) VALUE (
                        '$neu[ei_id]','$medium','".$neu['ei_org_typ']." ".$neu['ei_org_name']."','$neu[ei_id]','$neu[ei_urh_kurzz]','$p_uid'
                       ) ";
                $ret_i = SQL_QUERY($db,$sql_i);
            }
            
        }
        
    }
}

if ($debug) {
    echo "<pre class=debug>VF_Z_E_Edit_ph1.inc.php beendet</pre>";
}
?>