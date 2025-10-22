<?php

/**
 * Lste der Presse, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_O_PR_Edit_ph1.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = trim(mysqli_real_escape_string($db, $value));
}
var_dump($neu);
if ( $neu['bild_datei_1'] != '') {
    $neu['pr_bild_1'] =  $neu['bild_datei_1'];
}
if ( $neu['bild_datei_2'] != '') {
    $neu['pr_bild_2'] =  $neu['bild_datei_2'];
}
if ( $neu['bild_datei_3'] != '') {
    $neu['pr_bild_3'] =  $neu['bild_datei_3'];
}
if ( $neu['bild_datei_4'] != '') {
    $neu['pr_bild_4'] =  $neu['bild_datei_4'];
}
if ( $neu['bild_datei_5'] != '') {
    $neu['pr_bild_5'] =  $neu['bild_datei_5'];
}
if ( $neu['bild_datei_6'] != '') {
    $neu['pr_bild_6'] =  $neu['bild_datei_6'];
}

$p_uid = $_SESSION['VF_Prim']['p_uid']; 
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['pr_id'] == 0) { # neueingabe
    $sql = "INSERT INTO pr_esse (
              pr_datum,pr_name,pr_ausg,pr_medium,pr_seite,
              pr_teaser,pr_text,pr_bild1,pr_bild2,pr_bild3,pr_bild4,
              pr_bild5,pr_web_site,pr_web_text,pr_inet,pr_uidaend
              ) VALUE (
               '$neu[pr_datum]','$neu[pr_name]','$neu[pr_ausg]','$neu[pr_medium]','$neu[pr_seite]',
               '$neu[pr_teaser]','$neu[pr_text]','$neu[pr_bild_1]','$neu[pr_bild_2]','$neu[pr_bild_3]','$neu[pr_bild_4]',
               '$neu[pr_bild_5]','$neu[pr_bild_6]','$neu[pr_web_site]','$neu[pr_web_text]','$neu[pr_inet]','$p_uid'
               )";

    $result = SQL_QUERY($db, $sql) ;
    
} else { # update
   
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (substr($name,0,3) != "pr_") {
            continue;
        } #

        if ($name == "pr_id") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE `pr_esse` SET  $updas WHERE `pr_id`='$neu[pr_id]' ";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

}

header("Location: VF_O_PR_List.php?Act=" . $_SESSION[$module]['Act']);

if ($debug) {
    echo "<pre class=debug>VFH_O_PR_Edit_ph1.php beendet </pre>";
}
?>