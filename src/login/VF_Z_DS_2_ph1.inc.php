<?php 
/**
 * Daten aus csv- Dateien in Tabellen einlesen, Abfrage  source
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_C_DS_2_ph1.inc.php ist gestarted</pre>";}
#----------------------------------------------------------------------------------
#  VF_C_DS_2_v3ph1_v3.inc

$field_arr=$cont_arr= "";

$form_cont = "";
$k = -1;
if (file_exists("$indata") ) {          // Eingegebene Datei existiert
    echo "Datei <b>$indata wurde gefunden.</b><br/>";
    echo "Einlesen des Inhaltes.<br/>";
    $phase = 2;
    $i = $j = 0;
    $handle = fopen("$indata","rt");
    $table_name = $table_varfld = $table_fixfld = $table_vardata = "";
    while (!feof($handle)) {
        $line = fgets($handle);
        $lcont_arr = explode("|",$line);
        $linetype = "";
        foreach($lcont_arr as $value) {
            if ($value == "0") {    //kommentar
                continue 2;
            }
            if ($value == "1") {    // Table name, fix valued Cols
                $dsarr = explode("=",$lcont_arr[1]);
                $table_name = $dsarr[1];
                $table_fixfld = $lcont_arr[2];
                echo "Daten für die Tabelle $table_name wird eingelesen.<br/>";
                continue 2;
            }
            if ($value == "2" || $linetype == "2") {    //  Col Headers of variable Values
                if ($value =="2") {
                    $linetype = "2";
                    $i = 0;
                } else {
                    $fldname = ltrim($value);
                    $table_varfld[$i] = $fldname;
                    $i++;
                }
            }
            
            if ($value == "D" || $linetype == "D") {    //  Col Content
                if ($value == "D") {
                    $linetype = "D";
                    $i = 0;
                    $k++;
                } else {
                    $fldcont = rtrim($value);
                    $table_vardata[$k][$i] = $fldcont;
                    $i++;
                }
            }
        }
    }
    
    fclose($handle);
    
    foreach ( $table_vardata as $subt) {
        $j=0;
        $varfld = "";
        foreach ($table_varfld as $fldname) {
            if ($varfld == "") {
                $varfld = "$fldname='$subt[$j]'";
            } else {
                $varfld .= ",$fldname='$subt[$j]'";
            }
            $j++;
        }
        $sql = "INSERT INTO `$table_name` SET $table_fixfld,$varfld  ";
        $result = mysqli_query($connect,$sql) or die("Die Operation $sql konnte nicht durchgeführt werden. ".mysqli_error($connect));
    }
    mysqli_close($connect);
    echo "<h2>$k Datensätze wurden eingelesen.</h2>";
    
} else {
    echo "Datei $indata konnte nicht gefunden werden.<br/>";
    echo "Programm wird abgebrochen.<br/>";

    echo "<button type='submit' name='phase' value='0' class='red'>Abbrechen</button></p>";
}

# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_C_DS_2_ph1.inc.php beendet</pre>";}
?>