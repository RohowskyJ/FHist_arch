<?php
/**
 * Funktionsbibliothek für diverse Zusatzfunktionen für Feuerwehrhistoriker HP.
 *
 * @author  Josef Rohowsky josef@kexi.at start 01.01.2025
 *
 * Enthält und Unterprogramme für die Auwahl von Namen und Begriffen,
 *
 *  BA_HTML_header      - Ausgabe des Seiten- Headers, Laden der Seitenparameter aud config_s.ini
 *  BA_HTML_trailer     - Ausgabe Seitenende
 *
 *
 * jquery und  per $jq und jqui = true laden
 */

if ($debug) {
    echo "L 015: VF_HTML_Funcs.inc.php ist geladen. <br/>";
}


/**
 * Unterprogramm gibt den HTML Header aus
 *
 * input Felder für die PHP Error- Log-Datei und die Debug-Datei nach dem <Form Statement eingefügrt (id='cPError und cPdebug )
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
 * @global bool $jq lädt jquery.js
 * @global bool $qui lädt jquery-ui
 */
function BA_HTML_header($title, $head = '', $type = 'Form', $width = '90em')
// --------------------------------------------------------------------------------
{
    global $path2ROOT, $module, $logo, $jq, $jqui, $BA_AJA, $actor, $Anfix, $form_start,
     $A_Off;

    if (!isset($form_start)) {
        $form_start = true;
    }

    echo "<!DOCTYPE html>";
    echo "<html lang='de' style='overflow-x:scroll;'>"; # style='overflow-x:scroll;'
    echo "<head>";
    echo "  <meta charset='UTF-8'>";
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
    echo "  <title>$title</title>";
    echo "  <meta  name='viewport' content='width=device-width, initial-scale=1.00'>";
    echo '<meta name="description" content="Feuerwehrhistoriker Dokumentationen - Archiv, Inventar, Beschreibungen, Kataloge, ...">';
    echo "<meta name='copyright' content='Ing. Josef Rohowsky 2020-2025'>";
    echo '<meta name="robots" content="noindex">';
    echo '<meta name="robots" content="nofollow">';

    echo "<link rel='icon' type='image/x-icon' href='" . $path2ROOT . "login/common/imgs/favicon.ico'>";

    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/w3-5.02.css'  type='text/css'>"; 
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/add.css' type='text/css'>";
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/dropup.css' type='text/css'>";
    # in add.css eingefügt echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/accordeon.css' type='text/css'>";
    echo " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/BA_sortable.css' type='text/css'>";
    # echo " <link rel='stylesheet' href='vz_drpdwnmenu.css' type='text/css'>";

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
    }
    /** eigene JS Funktionen laden */
    if (isset($BA_AJA) && $BA_AJA) {
        echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/BA_AJAX_Scripts.js' ></script>";
    }
  
    echo $head;
    echo "</head>";

    if (!isset($_SESSION['VF_Prim'])) {
        $_SESSION['VF_Prim'] = array('mode'=>'Mandanten','eignr'=>'','ptyp'=>'','store'=>'','cPerr'=>'','cDeb'=>'',
            'OrgNam'=>'OrgNam','p_uid'=>'','Inc_Arr'=> '','benutzer'=>'','zu_eignr'=>'',
            'SUC'=>'','F_G'=>'','F_M'=>'','PSA'=>'','ARC'=>'','INV'=>'','OEF'=>'','ADM'=>'','MVW'=>'','SK'=>'',
        );
    }
    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', true, INI_SCANNER_NORMAL);
        # var_dump($ini_arr);
        if ($ini_arr['Config']['mode'] == "Single" && $ini_arr['Config']['eignr'] != "") {
            $_SESSION['VF_Prim']['mode'] = $ini_arr['Config']['mode'];
            $_SESSION['VF_Prim']['eignr'] = $ini_arr['Config']['eignr'];
            VF_Displ_Eig($ini_arr['Config']['eignr']);
        }
        $_SESSION['VF_Prim']['ptyp'] = $ini_arr['Config']['ptyp'];
        $_SESSION['VF_Prim']['store'] = $ini_arr['Config']['store'];
        $c_Date = date("Ymd");
        $_SESSION['VF_Prim']['cPerr'] = $path2ROOT."login/logs/debug/".$c_Date."_".$ini_arr['Config']['cPerr'];
        $_SESSION['VF_Prim']['cDeb'] = $path2ROOT."login/logs/debug/".$c_Date."_".$ini_arr['Config']['cDeb'];
        $_SESSION['VF_Prim']['OrgNam'] = $ini_arr['Config']['inst'];
        /** Aufdrehen des PHP Error Log to file */
        if ($_SESSION['VF_Prim']['cPerr'] != "") { 
            ini_set('log_errors', 1);
            $err_dsn = $_SESSION['VF_Prim']['cPerr'];
            ini_set('error_log', $err_dsn ) ; 
        }
    }

    if (! isset($actor) || $actor == "") {
        $actor = $_SERVER["PHP_SELF"];
    }

    echo "<body class='w3-container' style='max-width:$width;' >"; //
   
    # var_dump($_GET);
    echo '<fieldset>'; ## ganze seite

    if ($type == 'Form') {
        echo "<div class='w3-container' id='header'><fieldset>";  // Seitenkopf start
        echo "<div class='w3-row'>";
       #  echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</div></label><br>";
        echo "<div class='w3-col s9 m10 l11 '>"; // div langer Teil
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</div></label><br>";
        echo "<span class='w3-center w3-xlarge'> $title </span>";
        echo "</div>"; // Ende langer Teil
        echo "<div class='w3-col s3 m2 l1 ' >"; // div kurzer Teil
        echo "<logo><img  src= '".$path2ROOT."login/common/imgs/".$ini_arr['Config']['sign']."' width='90%'></logo>"; 
        
        echo "</div>"; // ende kurzer Teil
        if ($ini_arr['Config']['wart'] == "N") {
        } else {

            if ($ini_arr['Config']['wart'] == "J") {
                echo "<p class='error' style='font-size: 1.875em;'>Wartungsarbeiten - nur Abfragen möglich - keine Änderungen</p>";
            }
            if ($ini_arr['Config']['wart'] == "U") {
                echo "<p class='error' style='font-size: 1.875em;'>" . $ini_arr['Config']['warg'] . " </p>";
            }
        }

        echo "</div>"; // Ende w3-row
        echo "</div><fieldset>"; ## Ende Seitenkopf
    } elseif ($type == '1P') {    // 1st Page mit grossem Bild
        echo "<div class='w3-container' id='header'><fieldset>";  // Seitenkopf start
        echo "<div class='w3-row'>";
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</div></label><br>";
        echo "<img src='".$path2ROOT."login/common/imgs/2013_01_top_72_jr.png' alt='imgs/".$ini_arr['Config']['fpage']."' width='98%'>";
        if ($ini_arr['Config']['wart'] == "N") {
        } else {

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
        
        /* move nach list_funcs
        echo "<div class='w3-row'>";
        echo "<label><div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div><br>";
        echo "</div>"; // Ende w3-row
        #echo "<body class='w3-container'  style='max-width:$width;'>";
        #echo '<fieldset>';
        */
    }
    $set_auto = "";
    if (isset($A_Off) && $A_Off) {
        $set_auto = " autocomplete='off' ";
    }
    if (isset($form_start) && $form_start) {
        echo "<form id='myform' name='myform' method='post' action='$actor' enctype='multipart/form-data' $set_auto >";

    }
    # var_dump($_SESSION['VF_Prim']['debug']);
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
   /*
       function submitForm() {
           console.log('on click ausgelöst');
           document.getElementById('myform').submit();
       }
       */
    </script>

    <br>
    <footer class='footer'>
    <div class='copyrights' style='font-size: 0.7rem'>
    Copyright &copy; 2016 - <span id='year'>
    <script>document.getElementById('year').innerHTML = new Date().getFullYear();</script>
    </span>
    Josef Rohowsky - alle Rechte vorbehalten - All Rights Reserved
     
    <?php  
    if ( isset($_SESSION['VF_Prim']['p_uid']) && $_SESSION['VF_Prim']['p_uid'] == '1' ) {
        $Hinweise = "<li>Blau unterstrichene Daten sind Klickbar <ul style='margin:0 1em 0em 1em;padding:0;'>  <li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li> ";
        $adm_cont = "
                         <ul style='margin: 0 1em 0em 1em; padding: 0;'>
                         $Hinweise 
                         </ul>
                     ";
        
        ?>
         <div class="dropup w3-center">
               <!-- <button class="dropupbtn">Kammerjäger</button> -->
            <b class='dropupstrg' style='color:red;'>Kammerjäger</b>
            <div class="dropup-content">
              <b>Entwanzungs- Optionen</b> <br>
         
            <?php      
              /*
              if ($_SESSION['VF_Prim']['debug']['cPerr_A'] == 'A') {
                  $EinAus = "I '>PHP Error Datei nicht schreiben";
              } else {
                  $EinAus = "A '>PHP Error Datei schreiben";
              }
              echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . " ?cPerr_A=$EinAus'</a>";
              if ($_SESSION['VF_Prim']['debug']['cDeb_A'] == 'A') {
                  $EinAus = "I '>Debug Datei nicht schreiben";
              } else {
                  $EinAus = "A '>Debug Datei schreiben";
              }
              echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . " ?cDeb_A=$EinAus'</a>";
              */
              echo "<i>Script- Module</i><br>";
              if (isset($_SESSION[$module]['Inc_Arr'] ))
              {
                  foreach ($_SESSION[$module]['Inc_Arr'] as $key )    {
                      echo "$key <br>";
                  }
              } else {
                  echo "Keine Script Information enthalten <br>";
              }
              # echo "<br>$adm_cont ";
              
              ?>
             
              SQL Befehl anzeigen <button id="toggleButt-sD"  class='button-sm' >Einschalten</button><br>  

            </div>
         </div> 
       
         
<script>
    // Funktion zum Toggeln der Sichtbarkeit
    function toggleElements(buttonId, className) {
        const button = document.getElementById(buttonId); // Button mit ID auswählen
        if (button) {
            console.log('Button gefunden:', button);
            button.addEventListener('click', function() {
                const elements = document.querySelectorAll('.' + className);
                elements.forEach(element => {
                    // Sichtbarkeit umschalten
                    element.style.display = (element.style.display === 'none' || element.style.display === '') ? 'block' : 'none';
                });

                // Text des Buttons umschalten
                button.textContent = button.textContent === 'Einschalten' ? 'Ausschalten' : 'Einschalten';
            });
        } else {
            console.error(`Button mit ID ${buttonId} nicht gefunden.`);
        }
    }

    // Aufruf der Funktion für jedes Toggle-Element
    toggleElements('toggleButt-sD', 'toggle-SqlDisp');
    // Füge hier weitere Aufrufe für andere Buttons hinzu
    /*
       toggleElements('toggleButt-dD', 'toggle-dropDown');
       toggleElements('toggleButt-cS', 'toggle-csvDisp');
       
       toggleElements('toggleButt-pE', 'toggle-pError');
       toggleElements('toggleButt-dB', 'toggle-pDebug');
    
       toggleElements('toggleButt-lL', 'toggle-langList');
       toggleElements('toggleButt-vL', 'toggle-varList');
    */
</script>
     

    <?php     
    }
    
    # echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_auto_tip.js' ></script>";
    # echo "<script type='text/javascript' src='VZ_drpdwnmenu.js' ></script>";
    ?>
    
    </div>
    </footer>
    </form>
    
    </div></fieldset></body></html>  
    <?php
}

// Ende von function
