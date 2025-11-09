<?php
/**
 * Archivordnung, Erweiterungen für Eigentümer, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Hinzufügen zweier zusätzlicher Ebenen zur Archivordnung vom ÖBFV
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_A_AOR_Edit_ph1.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_A_AO_Edit_ph1.php ist gestarted </pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['al_lcsg'] == "") {
    $neu['al_lcsg'] = "0";
}
if ($neu['al_lcssg'] == "") {
    $neu['al_lcssg'] = "0";
}
#if ($neu['al'])

$sg_a = explode(".", $neu['al_sg']);

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($neu['al_id'] == 0 ) { # neueingabe
    $sql_nr = "SELECT * FROM `$tabelle_a`
                WHERE `al_sg`='" . $neu['al_sg'] . "' AND `al_lcsg`='" . $neu['al_lcsg'] . "'  AND `al_lcssg`='" . $neu['al_lcssg'] . "'  ";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>A_AOR Edit ph1 $sql </pre>";
    echo "</div>";
    
    $return_nr = SQL_QUERY($db, $sql_nr);
    $num_rows = mysqli_num_rows($return_nr);
    if ($num_rows == 0) {
        $sql = "INSERT INTO $tabelle_a (
                al_sg,al_lcsg,al_lcssg,al_lcssg_s0,al_lcssg_s1,al_bezeich,
                al_sammlung,al_aenduid
              ) VALUE (
                '$neu[al_sg]','$neu[al_lcsg]','$neu[al_lcssg]','$neu[al_lcssg_s0]','$neu[al_lcssg_s1]','$neu[al_bezeich]',
                '$neu[al_sammlung]',$p_uid
               )";
        
        echo "<div class='toggle-SqlDisp'>";
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>A_AOR Edit ph1 $sql </pre>";
        echo "</div>";
        
        $result = SQL_QUERY($db, $sql); 
       
        $neu['al_id'] = mysqli_insert_id($db);
    }
} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
    
    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        
        if ($name == "phase") {
            continue;
        } #
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    
    $sql = "UPDATE $tabelle_a SET  $updas,al_aenduid='$p_uid' WHERE `al_id`='" . $_SESSION[$module]['al_id'] . "'";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>A_AOR Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db, $sql);
    
}

header("Location: VF_A_AOR_List.php"); 

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_A_AO_Edit_ph1.inc beendet</pre>";
}
?>