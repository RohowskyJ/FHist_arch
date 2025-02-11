<?php

/**
 * Abzeichen Liste
 * 
 * @author Josef Rohowsky - neu 2018
 */
session_start(); # die SESSION aktivieren

const Module_Name   = 'Abz_List';
$module             = Module_Name;
$tabelle = 'fh_fw_ort_ref';


$path2ROOT          = "../";
$debug = True; $debug = False;   // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Funcs_v3.inc' ; 
require $path2ROOT . 'login/common/VF_List_Funcs_v3.inc' ;
require $path2ROOT . 'login/common/VF_Comm_Funcs_v3.inc' ;

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Ein"
    );
}


VF_initial_debug(); 

if (isset($_POST['bundesld'])) {$bundesld = $_POST['bundesld'];} else {$bundesld = "";}

    $VF_logo = 'NEIN';
    
    VF_HTML_header('Orts- und Feuerwehr- Daten- Verwaltung','Auswahl','','Form','90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 
  
    echo "<form id='myform' name='myform' method='post' action=".$_SERVER["PHP_SELF"]." enctype='multipart/form-data'>";
  
    if ($bundesld == "" ) {
        echo "Die Auszeichnungen welchen Bundeslandes sollen gedruckt werden?";
        
        echo "<input type='text' name='bundesld' value='$bundesld' >";
        echo "<button type='submit' name='phase' value='1' class='green'>Daten einlesen und Anzeigen</button>";
    } else {
        $pictpath="referat4/Auszeichnungen/".$bundesld."/";
 
            $files = scandir($pictpath);
            echo "<div class='w3-content'>";
            echo "<table class='w3-table-all'><tbody>";

            foreach($files as $file)  {
                if ($file == "." || $file =="..") {continue;}

            echo "<tr><td width='70%'> Datei-Name $file <br/>Name der Auszeichnung<br/><br/><br/>Abmessungen<br/><br/><br/>Stifter<br/><br/><br/>Doku vorhanden J/N<br/><br/></td><td><img src='$pictpath$file' width='150px' align='right'><br/>$file</td></tr>";

            }
            
            echo "</tbody></table></div<";
    }
    
    #
    echo "</form>";
    VF_HTML_trailer();

?>
