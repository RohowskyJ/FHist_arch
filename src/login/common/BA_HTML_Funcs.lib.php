<?php
/**
 * Funktionsbibliothek für diverse Zusatzfunktionen für Feuerwehrhistoriker HP.
 *
 * @author  Josef Rohowsky josef@kexi.at start 01.01.2025
 *
 * Enthält und Unterprogramme für die Auwahl von Namen und Begriffen
 * 
 *  BA_HTML_header      - Ausgabe des Seiten- Headers, Laden der Seitenparameter aud config_s.ini
 *  BA_HTML_trailer     - Ausgabe Seitenende
 *
 */

if ($debug) {
    echo "L 042: VF_HTML_Funcs.inc.php ist geladen. <br/>";
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
    global $path2ROOT, $module, $logo, $prot, $jq, $jqui, $BA_AJA, $actor, $Anfix, $form_start,
                      $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg;
    
    if (!isset($form_start)) {$form_start = True;}
    
    echo "<!DOCTYPE html>";
    echo "<html lang='de' style='overflow-x:scroll;'>"; # style='overflow-x:scroll;'
    echo "<head>";
    echo "  <meta charset='UTF-8'>";
    echo "  <title>$title</title>";
    echo "  <meta  name='viewport' content='width=device-width, initial-scale=1.00'>";
    echo '<meta name="description" content="Feuerwehrhistoriker Dokumentationen - Archiv, Inventar, Beschreibungen, Kataloge, ...">';
    echo "<meta name='copyright' content='Ing. Josef Rohowsky 2020-2025'>";
    echo '<meta name="robots" content="noindex">';
    echo '<meta name="robots" content="nofollow">';
    
    echo "<link rel='icon' type='image/x-icon' href='" . $path2ROOT . "login/common/imgs/favicon.ico'>";
    
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/w3.css'  type='text/css'>";
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/add.css' type='text/css'>";
    
    if (isset($prot) && $prot) {
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/prototype.js' ></script>";
    }
    if (isset($jq) && $jq) {
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/jquery-3.7.1.min.js' ></script>";
    }
    if (isset($jqui) && $jqui) {
        echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/jquery-ui.min.css' type='text/css'>";
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/jquery-ui.min.js' ></script>";
        ?>
        <style>
        /* Vorschlagsliste optisch anpassen */
        .ui-autocomplete {
            background-color: #fff;
            opacity: 0.95;
            z-index: 9999;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
        }
        </style>
        <?php  
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/jquery-ui.min.js' ></script>";
    }
    if (isset($BA_AJA) && $BA_AJA ) {
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_AJAX_Scripts.js' ></script>";
    }
    
    if (isset($js_au) && $js_au ) { // $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_Autocomplete.js' ></script>";
    }
    if (isset($js_md) && $js_md ) { // $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_MultiDropD.js' ></script>";
    }
    if (isset($js_mfu) && $js_mfu ) { // $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_MultiUpload.js' ></script>";
    }
    /*
    if (isset($js_suf) && $js_suf ) { // $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_.js' ></script>";
    }
     */
    if (isset($js_togg) && $js_togg ) { // $js_ini, $js_au, $js_md, $js_mfu, $js_suf, $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_ToggleBlocks.js' ></script>";
    }
   
    if (isset($js_ini) && $js_ini ) { // , , $js_md, , , $js_togg
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_init.js' ></script>";
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
        $_SESSION['VF_Prim']['ptyp'] = $ini_arr['Config']['ptyp'];
        $_SESSION['VF_Prim']['store'] = $ini_arr['Config']['store'];
    }
    
    if (! isset($actor) || $actor == "") {
        $actor = $_SERVER["PHP_SELF"];
    }
    
    echo "<body class='w3-container' style='max-width:$width;' >"; //
    echo '<fieldset>'; ## ganze seite
    
    if ($type == 'Form') {
        echo "<div class='w3-container' id='header'><fieldset>";  // Seitenkopf start
        echo "<div class='w3-row'>";
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div><br>";
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
        echo "<div class='w3-container' id='header'><fieldset>";  // Seitenkopf start
        echo "<div class='w3-row'>";
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div><br>";
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
        
        echo "<div class='w3-row'>";
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div><br>";
        echo "</div>"; // Ende w3-row
        #echo "<body class='w3-container'  style='max-width:$width;'>";
        #echo '<fieldset>';
    }
    $set_auto = "";
    if (isset($A_Off) && $A_Off) {
        $set_auto = " autocomplete='off' ";
    }
    if (isset($form_start) && $form_start) {
        echo "<form id='myform' name='myform' method='post' action='$actor' enctype='multipart/form-data' $set_auto >";
    }
   
    flow_add($module, "BA_HTML_Funcs.lib.php Funct: BA_HTML_Header");
}

// Ende von function BA_HTML_Header 


/**
 * Unterprogramm gibt passend zu HTML_Header den trailer aus
 */
function BA_HTML_trailer()
// --------------------------------------------------------------------------------
{
    global $module, $path2ROOT;

    flow_add($module, "BA_HTML_Funcs.lib.php Funct: BA_HTML_trailer");
   
   ?>
   <script>
       function submitForm() {
           console.log('on click ausgelöst');
           document.getElementById('myform').submit();
       }
    </script>
    
   
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

