<?php
/**
 * Funktionsbibliothek für diverse Zusatzfunktionen für Feuerwehrhistoriker HP.
 *
 * @author  Josef Rohowsky josef@kexi.at start 01.01.2025
 *
 * Enthält und Unterprogramme für die Auwahl von Namen und Begriffen
 *
 *  - VF_Add_Findbuch   - Suchbegriff Schlagwort hinzufügen
 *  - VF_Add_Namen      - Suchbegriff Namen hinzufügen
 *  - VF_chk_Valid      - Prüfung ob gültige Aufruf, setzten der Zugriffs- Parameter für $module neu 20240120
 *  - VF_Count_add      - Add Record for startng an inernal Sub-Process (as Archive, Suche, Fzg
 *  - VF_Bdld_Displ     - Zeigt den Namen des Bundeslandes für die angegebene Abkürzung
 *  - VF_Displ_Arl      - Anzeige Archivordnung 3+4. Ebene Locale Sachgeb + Subsachgeb
 *  - VF_Displ_Aro      - Anzeige Archivordnug, 1+2. Ebene Generelles Sachgebiet und Sub-Sachgebiet
 *  - VF_Displ_Eig      - Daten zur Anzeige der Eigentümer-Daten, Speichern in SESSION[Eigner [
 *  - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen
 *  - VF_Displ_Urheb    - Urheber Daten in $_Sess[$module]['URHEBER'] einlesen
 *  - VF_Login          - Login durchführen
 *  - VF_Log_Pw_chg     - Passwortänderung beim Login Daten erfassen
 *  - VF_Log_PW_Upd     - Passworänderung schreiben
 *  - VF_Mail_Set       - gibt die E-Mail Adresse für die Recs aus fh_m_mail zurück neu 20240120
 *  - VF_Multi_Foto     - Anzeige mehrfach - s mit den texten paarweise n Zeile
 *  - VF_Sel_Bdld       - Auswahl Bundesland
 *  - VF_Sel_Det        - Detailbeschreibungs Selektion
 *  - VF_Sel_Sammlg     - Sammlungs- Selektion mit select list
 *  - VF_Sel_Staat      - Auswahl Staat
 *  - VF_Sel_Urheber    - Auswahl des Urhebers, speicherung Urhebernummer
 *  - VF_set_module_p   - setzen der Module- Parameter    neu 20240120
 *  - VF_set_PictPfad   - setze den Bilderpfad für Uploads und Anzeigen
 *  - VF_Show_Eig       - Auslesen ud zurückgeben der Eigner-Daten im Format wir Autocomplete
 *  - VF_tableExist     - test ob eine Tabelle existiert
 *  - VF_upd            - Berechtigungs- Feststellung je *_List Script entsprechend Eigentümer
 *  - VF_Upload_Pic     - Hochladen der Datei mit Umbenennung auf Foto- Video- Format Urh-Datum-Datei.Name
 *  - VF_trans_2_separate - Umlaute eines Strings von UTF-8 oder CP1252 auf gtrennte Schreibweise -> Ü ->UE
 *  - VF_Eig_Ausw       - Autokomplete Auswahl Eigner
 *  - VF_Multi_Dropdown - Multiple Dropdown Auswahl mit bis zu 6 Ebenen, Verwendet für Sammlungsauswahl, AOrd- Auswahl
 *  - VF_Sel_Eigner     - Eigentümer- Auswahl für Berechtigungen (wieder aktiviert)
 */

if ($debug) {
    echo "L 042: VF_Common_Funcs.inc.php ist geladen. <br/>";
}


/**
 * Unterprogramm gibt den HTML Header aus
 *
 *
 *
 * @param string $title
 *            <title> tag text
 * @param string $head
 *            zusätzliche <head> Zeilen. Auch <style>......</style
 * @param string $type
 *            Form der Seite 
 *             == Form Ausgabe <body><fieldset><header</Fieldset><fieldset> aus
 *             == List gibt nur <body></fieldset aus
 *             == 1P Erste Seite: gibt das Bild aber kein Logo aus
 * @param string $width die Breite des Schirmes (div)
 *
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global bool $prot lädt prototybe.js
 */
function BA_HTML_header($title, $head = '', $type = 'Form', $width = '90em')
// --------------------------------------------------------------------------------
{
    global $path2ROOT, $module, $logo, $prot, $actor, $Anfix, $form_start;
    
    if (!isset($form_start)) {$form_start = True;}
    
    echo "<!DOCTYPE html>";
    echo "<html lang='de'>"; # style='overflow-x:scroll;'
    echo "<head>";
    echo "  <meta charset='UTF-8'>";
    echo "  <title>$title</title>";
    echo "  <meta  name='viewport' content='width=device-width, initial-scale=1.00'>";
    echo '<meta name="description" content="Feuerwehrhistoriker Dokumentationen - Archiv, Inventar, Beschreibungen, Kataloge, ...">';
    echo "<meta name='copyright' content='FT Ing. Josef Rohowsky 2020-2025'>";
    echo '<meta name="robots" content="noindex">';
    echo '<meta name="robots" content="nofollow">';
    
    echo "<link rel='icon' type='image/x-icon' href='" . $path2ROOT . "login/common/imgs/favicon.ico'>";
    
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/w3.css'  type='text/css'>";
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/add.css' type='text/css'>";
    #echo " <link rel='alternate stylesheet' href='" . $path2ROOT . "login/common/css/color_blue.css' type='text/css' title='standard'>";
    

    if (isset($prot) && $prot) {
        echo "<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
    }

    echo $head;
    echo "</head>";
    
    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
        if ($ini_arr['Config']['mode'] == "Single" && $ini_arr['Config']['eignr'] != "") {
            if (!isset($_SESSION['VF_Prim'])) {
                #$_SESSION['VF_PRIM'] = array();
                $_SESSION['VF_Prim']['mode'] = $ini_arr['Config']['mode'];
                $_SESSION['VF_Prim']['eignr'] = $ini_arr['Config']['eignr'];
                VF_Displ_Eig($ini_arr['Config']['eignr']);
            }
        } else {
            $_SESSION['VF_Prim']['mode'] = "Mandanten";
            $_SESSION['VF_Prim']['eignr'] = "";
        }
    }
    
    if (! isset($actor) || $actor == "") {
        $actor = $_SERVER["PHP_SELF"];
    }
    
    echo "<body class='w3-container' style='max-width:$width;' >"; //
    echo '<fieldset>'; ## ganze seite
    
    echo "<div class='w3-container' id='header'><fieldset><header>";  // Seitenkopf start
    echo "<div class='w3-row'>";
    echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div><br>";
    
    if ($type == 'Form') {

        echo "<div class='w3-col s9 m10 l11 '>"; // div langer Teil
        
        echo "<p class='w3-center w3-xlarge'> $title </p>";
        echo "</div>"; // Ende langer Teil
        echo "<div class='w3-col s3 m2 l1 ' >"; // div kurzer Teil
        echo "<logo><img  src= '".$path2ROOT."login/common/imgs/".$ini_arr['Config']['sign']."' width='90%'></logo>";
        echo "</div>"; // ende kurzer Teil
        if ($ini_arr['Config']['wart'] == "N") {} else {
            
            if ($ini_arr['Config']['wart'] == "J") {
                echo "<p class='error' style='font-size: 1.875em;'>Wartungsarbeiten - nur Abfragen möglich - keine Änderungen</p>";
            }
            if ($ini_arr['Config']['wart'] == "U") {
                echo "<p class='error' style='font-size: 1.875em;'>" . $ini_arr['Config']['warg'] . " </p>";
            }
        }
        
        echo "</div>"; // Ende w3-row
        echo "</div><fieldset>"; ## Ende Seitenkopf
    } elseif ($type == '1P') {
        echo "<img src='".$path2ROOT."login/common/imgs/2013_01_top_72_jr.png' alt='imgs/2013_01_top_72.png' width='98%'>";
        if ($ini_arr['Config']['wart'] == "N") {} else {
            
            if ($ini_arr['Config']['wart'] == "J") {
                echo "<p class='error' style='font-size: 1.875em;'>Wartungsarbeiten - nur Abfragen möglich - keine Änderungen</p>";
            }
            if ($ini_arr['Config']['wart'] == "U") {
                echo "<p class='error' style='font-size: 1.875em;'>" . $ini_arr['Config']['warg'] . " </p>";
            }
        }
        
        echo "</div>"; // Ende w3-row
        echo "</div><fieldset>"; ## Ende Seitenkopf
    } else { // List
        echo "<body class='w3-container'  style='max-width:$width;'>";
        echo '<fieldset>';
    }
   
    if (!isset($form_start) || !$form_start) {
        echo "<form id='myform' name='myform' method='post' action='$actor' enctype='multipart/form-data'>";
    }
   
    BA_flow_add($module, "BA_Html_Funcs.lib.php Funct: BA_HTML_Header");
}

// Ende von function BA_HTML_Header 


/**
 * Unterprogramm gibt passend zu HTML_Header den trailer aus
 */
function BA_HTML_trailer()
// --------------------------------------------------------------------------------
{
    global $module, $path2ROOT;

    BA_flow_add($module, "BA_Html_Funcs.lib.php Funct: BA_HTML_trailer");
   
   ?>
    <br>
    <footer class='footer'>
    <div class='copyrights' style='font-size: 0.7rem'>
    Copyright &copy; 2016 - <span id='year'>
    <script>document.getElementById('year').innerHTML = new Date().getFullYear();</script>
    </span>
    Josef Rohowsky - alle Rechte vorbehalten - All Rights Reserved
    </div>
    </footer>
    </form>
    
    </div></fieldset></body></html>" 
    <?php 
}

// Ende von function

/**
 * Aufzeichnen der Aufrufe
 *
 *
 * @param string $id
 *            Modul- Name
 * @param string $text
 *            Log- Text
 */
function BA_flow_add($id, $text)
{
    global $flow_list, $path2ROOT;
    
    if ($flow_list) {
        $date = date("Ymd-H");
        $dsn = $path2ROOT . "login/flow/" . $date . "_$id.flow";
        $datei = fopen($dsn, 'at');
        fputs($datei, mb_convert_encoding($text . "\n", "ISO-8859-1"));
        fclose($datei);
    }
} # ende Function flow_add

