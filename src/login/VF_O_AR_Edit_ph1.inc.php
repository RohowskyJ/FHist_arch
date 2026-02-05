<?php 

/**
 * Wartung der Archive und Bibliotheken, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_AR_Edit_ph1.inc.php";

if ($debug) {echo "<pre class=debug>VF_O_AR_Edit_ph1.inc.php ist gestarted</pre>";}
#----------------------------------------------------------------------------------

foreach ($_POST as $name => $value)
{ $neu[$name] = mysqli_real_escape_string($db,$value);  }
$p_uid = $_SESSION['VF_Prim']['p_uid'];
if ( $debug ) { echo '<pre class=debug>';echo '<hr>$neu: ';     print_r($neu); echo '</pre>'; }
if ($fa_id == 0) { # neuengabe
    
    $sql = "INSERT INTO fh_falinks (
              fa_link,fa_text,fa_url_chkd, fa_url_obsolete,
              fa_aenduid,fa_aenddat
              ) VALUE (
               '$neu[fa_link]','$neu[fa_text]','','',
               '$p_uid',now()
               )";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AR Edit ph1 $sql </pre>";
    echo "</div>";
    
    $result = SQL_QUERY($db,$sql);  
 
    header("Location: VF_O_AR_List.php?Act=".$_SESSION[$module]['Act']);

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
            
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AR Edit ph1 $sql </pre>";
            echo "</div>";
            
        }
        $result = SQL_QUERY($db,$sql);
        
        header("Location:  VF_O_AR_List.php?Act=".$_SESSION[$module]['Act']);
        
}

if ($debug) {echo "<pre class=debug>VF_O_AR_Edit_ph0.php beendet</pre>";}
?>
