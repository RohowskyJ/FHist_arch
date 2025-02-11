<?php 

/**
 * Wartung der Archive und Bibliotheken, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {echo "<pre class=debug>VF_O_AR_Edit_ph0.php ist gestarted</pre>";}
#----------------------------------------------------------------------------------

foreach ($_POST as $name => $value)
{ $neu[$name] = mysqli_real_escape_string($db,$value);  }
$p_uid = $_SESSION[$module]['p_uid'];
if ( $debug ) { echo '<pre class=debug>';echo '<hr>$neu: ';     print_r($neu); echo '</pre>'; }
if ($fa_id == 0) { # neuengabe
    
    $sql = "INSERT INTO fh_falinks (
              fa_link,fa_text,
              fa_aenduid,fa_aenddat
              ) VALUE (
               '$neu[fa_link]','$neu[fa_text]',
               '$p_uid',now()
               )";
    
    $result = SQL_QUERY($db,$sql);
    
    if ($_SESSION[$module]['Act']) {
        header("Location: VF_O_AR_List.php?Act=l");
    } else {
        header("Location: VF_O_AR_List.php?Act=".$_SESSION[$module]['Act']);
    }
} else { # Update
    $updas   = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
    
    foreach ($neu as $name => $value) # für alle  Felder aus der tabelle
    {
        if ( !preg_match ("/[^0-9]/", $name) ) {continue;}    # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {continue;}    #
        if ($name == "phase") {continue;}    #
        
        $updas .= ",`$name`='".$neu[$name]."'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    $updas = mb_substr($updas,1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    
    if ( $_SESSION[$module]['all_upd']
        ) {
            $sql = "UPDATE `fh_falinks` SET  $updas WHERE `fa_id`='$fa_id'";
            if ( $debug ) { echo '<pre class=debug> L 0197: \$sql $sql </pre>'; }
        }
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db,$sql);
        
        header("Location:  VF_O_AR_List.php?Act=".$_SESSION[$module]['Act']);
        
}

if ($debug) {echo "<pre class=debug>VF_O_AR_Edit_ph0.php beendet</pre>";}
?>
