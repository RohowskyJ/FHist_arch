<?php 
/**
 * Daten aus csv- Dateien in Tabellen einlesen, Abfrage  source
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "Z_DS_2_ph2.inc.php";

if ($debug) {echo "<pre class=debug>VF_C_DS_2_ph1.inc.php ist gestarted</pre>";}

$field_arr=$cont_arr= "";

$form_cont = "";
$k = 0;
if (file_exists("$indata") ) {          // Eingegebene Datei existiert
    echo "Datei <b>$indata wurde gefunden.</b><br/>";
    echo "Einlesen des Inhaltes.<br/>";
    $phase = 2;
   $l = $i = $j = 0;
    $handle = @fopen("$indata","r");
    $table_name = $table_varfld = $table_fixfld = $table_vardata = "";
    $fields = "";
    while (!feof($handle)) {
        $line = fgets($handle);
       
        echo "L 026 line $line <br>";
        if ($line == "") {
            break;
        }
        $l++;
        $lcont_arr = explode("|",$line);
        $linetype = "";
        #var_dump($lcont_arr);
        if ($l === 1){
            $table_name = trim ($line);
            echo "Daten für die Tabelle $table_name wird eingelesen.<br/>";
            continue;
        }
        $ds_arr = explode("|", $line);
        #var_dump($ds_arr);
        if ($l == 2) {
            foreach ( $ds_arr as $fld) {
                $fields .= $fld.",";
            }
            $fields = substr($fields,0,-1);
            #var_dump($fields);
        }
        if ($l >= 3) {
            $k++;
            $content = "";
            foreach ($ds_arr as $cont) {
                $content .= "'$cont',";
            }
            #var_dump($content);
            $content = substr($content,0,-1);
            $sql = "INSERT INTO $table_name ($fields) VALUES ($content)";
            
            echo "<div class='toggle-SqlDisp'>";
            echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'DS 2 Table Edit $sql </pre>";
            echo "</div>";
            
            $return = SQL_QUERY($db,$sql);

        }
        
    }
    
    fclose($handle);
    
    mysqli_close($db);
    echo "<h2>$k Datensätze wurden eingelesen.</h2>";
    
} else {
    echo "Datei $indata konnte nicht gefunden werden.<br/>";
    echo "Programm wird abgebrochen.<br/>";

    echo "<button type='submit' name='phase' value='0' class='red'>Abbrechen</button></p>";
}

# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_C_DS_2_ph1.inc.php beendet</pre>";}
?>