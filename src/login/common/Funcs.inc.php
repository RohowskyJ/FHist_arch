<?php
/**
 * Bibliothek für allgemeine Funktionen.
 * 
 * @author  B.R.Gaicki  - neu 2018   mod 2024     
 * 
 * Funcs - Gemeinsame Konstantendefinitionen und Unterprogramme - Version 5 by B.Richard Gaicki
 * 
 * change Avtivity:
 * 2024_04_28 JR          - Test Prefix für Aufrufe 
 * ----------------------------------------------------------------------------------
 * Unterprogramme:
 *  - logo         - Logo Banner
 *  - getmicrotime - zur Berechnung der Dauer der Abfrage (Zeitpunke für $startzeit $stopzeit)
 *  - writelog     - zum Schreiben von Eintragungen in LOG Files 
 *  - absoluteURL  - konvertiert eine relative URL zu einer absoluten URL (in Mails benötigt)
 *  - logandMail   - zum Schreiben von Eintragungen in LOG Files und versenden einer Mail an die Admins 
 *  - LinkDB       - um den 'Link' zur SQL Datenbank herzustellen 
 *  - Vdir_sort     - Anzeige der sortierten Log Direktory-Inhalte          - von VF_ übernommen
 *  - EMail_Eingabe - Anzeige des Feldes zur Eingabe der E-Mail-Adresse zur Indentifikation des Mitglieds 
 *  - EMail_Adr_check - E-Mail-Adresse prüfen und Eintrag aus TNTab lesen - oder Fehlermedung zurückgeben 
 *  - HTML_header  - gibt den HTML Header aus 
 *  - HTML_trailer - gibt passend zu VF_HTML_Header den trailer aus 
 *  - initial_debug- Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES
 *  - Button_senden - fügt die Aufforderung zum Fehler beheben - und den Button 'senden' - ein 
 *  - SQL_QUERY     - Aufruf des mysqli Query 
 *  - mb_ucfirst    - weil es derzeit in php keine unktion mb_ucfirst gibt  
 *  - console.log   - Text information in console von WEB-Tools
 *  - flow_add      - Aufrufs- Mitschnitte 
 */
flow_add('funcs', "Funcs.inc.php geladen");
if ($debug) {
    echo "Funcs.inc ist geladen<br>";
}

/**
 * Unterprogramm um das Logo Bild einzufügen.
 *
 * @return string Anzeige des Logo-Bildes
 *        
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 */
function logo()
// --------------------------------------------------------------------------------
{
    global $path2ROOT, $module;

    flow_add($module, "Funcs.inc Funct: Logo");

    $logo = "";

    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
    }

    if (isset($ini_arr['Config'])) {
        $logo = "";
        if ($ini_arr['Config']['sign'] != "") {
            $logo_i = $ini_arr['Config']['sign'];
            echo $logo_i;
            $logo = "<img src='" . $path2ROOT . "login/common/imgs/$logo_i' alt='Signet' style='border: 3px solid lightblue;  display: block; margin-left: auto; margin-right: auto; margin-top:6px; width:    80px; '>";
        }
    }

    return $logo;
}

// Ende von function logo

/**
 * Unterprogramm um Zeitpunke (startzeit/stopzeit) zu bekommen zur Berechnung der Dauer der Abfrage
 *
 * @return number
 */
function getmicrotime()
// --------------------------------------------------------------------------------
{
    flow_add($module, "Funcs.inc Funct: getmicrotime");

    list ($usec, $sec) = explode(' ', microtime());
    return ((float) $usec + (float) $sec);
}

// Ende von function getmicrotime

/**
 * Unterprogramm zum Schreiben von Eintragungen in LOG Files.
 *
 *
 *
 * @param string $log_DSN
 *            Dateiname (wird mit Jahr und .log ergänzt)
 * @param string $logtext
 *            Log- Text welcher Einzutragen ist (wird mit Systemdaten ergänzt)
 * @return string Ergänzter DSN des MonatsLogFiles (mit YYYY und .log)
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function writelog($log_DSN, $logtext)
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: writelog");

    # Dem FileNamen YYYY- voranstellen
    $exploded = explode('/', $log_DSN); // zerlegen in ein Array (der FileName ist das letzte Array Element)
    $exploded[] = date('Y-') . array_pop($exploded) . '.log'; // Datum und Typ zum FileNamen hinzufügen
    $dsn = implode('/', $exploded); // DSN wieder zusammenfügen
    if ($debug) {
        echo "<pre class=debug>writelog log_DSN:$log_DSN dsn: $dsn</pre>";
    }

    $eintragen = "\n\n" . date('Y-m-d H:i:s ') . $_SERVER['REQUEST_URI'] . "\n$logtext" . "\nSystemdaten ***************************************************************";
    if (isset($_SERVER['REMOTE_USER'])) {
        $eintragen .= "\n         REMOTE_USER: " . $_SERVER['REMOTE_USER'];
    }
    $eintragen .= "\n                Host: " . gethostbyaddr($_SERVER["REMOTE_ADDR"]) . 
    # . "\n IP: " . $_SERVER["REMOTE_ADDR"]
    # . "\n Server name: " . $_SERVER['SERVER_NAME']
    "\n                Page: " . $_SERVER["REQUEST_URI"];
    if (isset($_SERVER["HTTP_REFERER"])) {
        $eintragen .= "\n             Referer: " . $_SERVER["HTTP_REFERER"];
    }
    $eintragen .= "\n             Browser: " . $_SERVER["HTTP_USER_AGENT"] . "\nHTTP         _ACCEPT: " . $_SERVER["HTTP_ACCEPT"] . "\nHTTP_ACCEPT_LANGUAGE: " . $_SERVER["HTTP_ACCEPT_LANGUAGE"] . "\nHTTP     _CONNECTION: " . $_SERVER["HTTP_CONNECTION"] . "\n         REMOTE_PORT: " . $_SERVER["REMOTE_PORT"] . "\n**** LOG ENDE *************************************************************";

    $datei = fopen($dsn, 'at');
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);

    if ($debug) {
        echo "<pre class=debug>Log Record in $dsn<hr>" . nl2br("$eintragen") . "</pre>";
    }

    return $dsn;
}

// Ende von function writelog

/**
 * Konvertiert eine relative URL zu einer absoluten URL - wie in Mails benötigt
 *
 * @param string $url
 *            relative URL
 * @return string absolute URL
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function absoluteURL($url) //
                            // --------------------------------------------------------------------------------
{
    global $debug, $module;
    if ($debug) {
        echo "<pre class=debug>function absoluteURL: relative URL = '$url'</pre>";
    }

    flow_add($module, "Funcs.inc Funct: absoluteURL");

    $exploded = explode('/', $url); // zerlegen in ein Array
    while ($exploded[0] == '..') {
        $x = array_shift($exploded);
    } // entferne alle ..
    if ($debug) {
        echo "<pre class=debug>URL = '" . implode('/', $exploded) . "'</pre>";
    }

    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        $absoluteURL = $url;
    } # weil keine mail gesendet wird
    else {
        $absoluteURL = $_SESSION['Config'][HomePage] . "/" . implode('/', $exploded);
    }

    if ($debug) {
        echo "<pre class=debug>absolute URL = '$absoluteURL'</pre>";
    }
    return $absoluteURL;
}

// von function absoluteURL

/**
 * Unterprogramm zum Schreiben von Eintragungen in LOG Files und versenden einer Mail an die Admins
 *
 * @param string $log_DSN
 *            Name der Log Datei (wird mit Jahr und .log ergänzt)
 * @param string $logtext
 *            Text welcher in den LOG Einzutragen ist (wird mit Datum , Uhrzeit und Systemdaten ergänzt)
 * @param string $MailTo
 *            Mail Empänger(Liste)
 * @param string $MailSubject
 *            Subject Text der EMail
 * @param string $Mailtext
 *            Inhalt der Email in HTML format (wird mit Zusatzinformation ergän
 * @return boolean Rückgabewert: immer True
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global $module Modul-Name für $_SESSION[$module] - Parameter
 */
function logandMail($log_DSN, $logtext, $MailTo, $MailSubject, $Mailtext)
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: LogandMail");

    $logtext = htmlspecialchars_decode($logtext, ENT_QUOTES);
    $logDateiname = writelog($log_DSN, "$MailSubject\n$logtext");
    $URL = absoluteURL($logDateiname);
    $dsn = realpath($logDateiname);

    $Mailbody = $Mailtext . "<br><pre>$logtext</pre>";
    if ($_SERVER['SERVER_NAME'] != 'localhost') {
        $Mailbody .= "<p>Die Information wurde im Log Jahresfile <a href='$URL'>$URL</a> gespeichert.</p>";
    } else {
        $Mailbody .= "<p>Die Information wurde im Log Jahresfile <q>$dsn</q> gespeichert.</p>";
    }

    sendEmail($MailTo, "VFH $module: $MailSubject", $Mailbody);

    return true;
}

// Ende von function logandMail

/**
 * Unterprogramm um den Link zur SQL Datenbank herzustellen
 *
 * @param
 *            string <code>$db_proj</code> Falls mehrer Datenbanken benutzt werden (
 * @return array Datenbank Handle
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $LinkDB_database Datenbank- Name
 *         - diese wird in Funktion Tabellen_Spalten_parms (Tabellen_Spalten) verwendet
 *        
 */
function LinkDB($db_proj = "")
{
    global $debug, $module, $LinkDB_database, $path2ROOT;

    flow_add($module, "Funcs.inc Funct: LinkDB_n");
    # echo $path2ROOT."login/common/config_d.ini <br> ";
    $ini_d = $path2ROOT . "login/common/config_d.ini";
    $ini_arr = parse_ini_file($ini_d, True, INI_SCANNER_NORMAL);
    # print_r($ini_arr); echo "<br>L 0239 ini_arr <br>";

    $server_name = $_SERVER['SERVER_NAME'];

    # echo "L 0248 server name $server_name <br>";
    if (stripos($server_name, "www") || stripos($server_name, "WWW")) {
        $url_arr = explode(".", $server_name);
        $cnt_u = count($url_arr);
        $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
        # echo "l 0247 srvNam $server_name <br>";
    }

    if (isset($ini_arr)) { # (isset($ini_arr[$server_name])){
        if ($server_name == 'localhost') {
            if (isset($ini_arr[$server_name])) {
                $dbhost = $ini_arr[$server_name]['l_dbh'];
                $dbuser = $ini_arr[$server_name]['l_dbu'];
                $dbpass = $ini_arr[$server_name]['l_dbp'];
                $database = $ini_arr[$server_name]['l_dbn'];
            }
        } else {
            if (stripos($server_name, "www") || stripos($server_name, "WWW")) {
                $url_arr = explode(".", $server_name);
                $cnt_u = count($url_arr);
                $server_name = $url_arr[$cnt_u - 2] . "." . $url_arr[$cnt_u - 1];
                # echo "l 0247 srvNam $server_name <br>";
            }
            $server_name = "HOST";
            if (isset($ini_arr[$server_name])) {
                $dbhost = $ini_arr[$server_name]['h_dbh'];
                $dbuser = $ini_arr[$server_name]['h_dbu'];
                $dbpass = $ini_arr[$server_name]['h_dbp'];
                $database = $ini_arr[$server_name]['h_dbn'];
            }
        }

        # echo "L 280 linkdb_n dbhost $dbhost dbnam $database user $dbuser pass $dbpass <br>";

        $dblink = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Verbindung zu MySQL gescheitert!' . mysqli_connect_error());

        mysqli_select_db($dblink, $database) or die("Datenbankzugriff zu $database gescheitert!");
        # if ($debug) { echo "<pre class=debug> mysqli_select_db:"; print_r($dblink); echo '</pre>'; }
        mysqli_set_charset($dblink, 'utf8');
        $LinkDB_database = $database; # wird in Funktion Tabellen_Spalten_v2.php verwendet
        return $dblink;
    } else {
        echo "Configurations-Fehler - keine Datenbank - Abbruch";
        exit();
    }
}

# ende linkDB

/**
 * Ausgabe eines Verzeichnis.
 * Inhaltes.
 * Unterprogramm zeigt eine Liste der im angegebenen Directory befindlichen Files an Defaults $Pfad = "./" (aktuelles Dir), $FType = "txt" (Log-Dateien).
 *
 * @param string $Pfad
 * @param string $FType
 * @param string $direkt_ausg
 * @return string
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function dir_sort($Pfad, $FType, $direkt_ausg)
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: dir_sort");

    # if ($debug) {echo "<pre class=debug>Funct dir_sort L 206: <br/>Pfad=$Pfad <br/>FType=$FType</pre>";}
    if ($Pfad == "") {
        $Pfad = "./";
        if ($FTyp == "") {
            $FTyp = "txt";
        }
        if ($direct_ausg == "") {
            $direkt_ausg = false;
        }
    }

    $DirFiles = array();
    if ($handle = opendir("$Pfad")) {
        while (false !== ($file = readdir($handle))) {
            if ($file == substr_compare($file, $FType, - 3, 3)) {
                $DirFiles[] = $file;
            }
        }
        # print_r($DirFiles);
        closedir($handle);
        $thelist = "";
        sort($DirFiles);
        foreach ($DirFiles as $file) {
            $thelist .= '<a href="' . $Pfad . $file . '">' . $Pfad . $file . '<br></a>';
        }
    }
    # if ($debug) {echo "<pre class=debug>Funct dir_sort L 224: <br/>thelist=$thelist </pre>";}
    if ($direkt_ausg) {
        echo "<P>Gewünschte Datei zum Ansehen einfach anklicken. </p>";
        echo $thelist;
    }
    return ($thelist);
}

// Ende von function dir_sort
/**
 * Unterprogramm zum Prüfen der eingegeben E-Mail_adresse
 *
 * @param string $EMail
 * @param array $dblink
 *            Datenbank Handle
 * @param string $MailTo
 * @return array returniert immer ein Array. Bestehend aus
 *         - entweder dem element 'message' - weches den Fehlertext enthält
 *         - oder aus den Spalten des gefundenen Eintrags der Tabelle TNTab
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 */
function EMail_Adr_check(&$EMail, $dblink, $MailTo = '')
{
    global $debug, $module, $path2ROOT;

    flow_add($module, "Funcs.inc Funct:EMail_Adr_Check");

    $EMail = trim($EMail);
    if ($debug) {
        echo "<pre class=debug>EMail_Adr_check: \$EMail='$EMail'</pre>";
    }
    if ($EMail == "") {
        return array(
            'message' => "Sie haben keine E-Mail-Adresse eingetragen."
        );
    }

    $sql = "SELECT * FROM TNTab LEFT JOIN PixRipTab ON TNTab.t_id=PixRipTab.pr_t_id ";
    if (is_numeric($EMail)) {
        $sql .= "WHERE t_id= '$EMail'";
    } else {
        $EMail = mb_strtolower($EMail, 'UTF-8'); // Adresse in Kleinbuchstaben umwandeln

        if (! filter_var($EMail, FILTER_VALIDATE_EMAIL)) { // Email Plausibilitätsprüfung
            return array(
                'message' => "Ist keine korrekte E-Mail-Adresse"
            );
        }
        $sql .= "WHERE EMail LIKE '$EMail'";
    }

    # ------- E-Mail-Addresse ist korrekt - Daten aus TNTAb lesen
    $sql = "SELECT * FROM fh_mitglieder "; # WHERE mi_id LIKE '$EMail'";

    if (is_numeric($EMail)) {
        $sql .= "WHERE mi_id= '$EMail'";
    } else {
        $EMail = mb_strtolower($EMail, 'UTF-8'); // Adresse in Kleinbuchstaben umwandeln
        if ($debug) {
            echo "<pre class=debug>L 532: EM Adr Chk: \$EMail : $EMail
            </pre>";
        }
        if (! filter_var($EMail, FILTER_VALIDATE_EMAIL)) { // Email Plausibilitätsprüfung
            return array(
                'message' => "Ist keine korrekte E-Mail-Adresse"
            );
        }
        $sql .= "WHERE mi_email LIKE '$EMail'";
    }

    if ($debug) {
        echo "<pre class=debug>L 540: EM Adr Chk: \$sql : $sql
            </pre>";
    }

    $sqlresult = SQL_QUERY($dblink, $sql);

    if ($debug) {
        echo "<pre class=debug>EM Adr Chk: \$sql : $sql
            " . print_r($sqlresult) . "
            </pre>";
    }

    if (mysqli_num_rows($sqlresult) == 0) # keine Eintragung in TNTAB gefunden ----------------------------------
    {
        $MailSubject = "Abgewiesener Request! E-Mail-Adresse $EMail unbekannt."; // text in the Subject field of the mail
        $Mailbody = "<p>Abgewiesener $module Request!</p>";
        logandMail($path2ROOT . "login/logs/" . $module . "_nok_log", '', $MailTo, $MailSubject, $Mailbody);

        return array(
            'message' => "E-Mail-Adresse $EMail unbekannt."
        );
    }

    # -------- Eintragung in fh_miglieder gefunden -> Daten lesen
    $TNTab_row = mysqli_fetch_assoc($sqlresult);
    if ($debug) {
        echo "<pre class=debug>TNtab row:";
        print_r($TNTab_row);
        echo '</pre>';
    }

    $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
    $email = $ini_arr['Config']['vema'];
    if ($TNTab_row['mi_einversterkl'] != "Y") # Keine Einverständniserklärung -------------------------------------
    {
        $MailSubject = "VFH-$module: Abgewiesener Request! E-Mail-Adresse $EMail. Keine Einverständniserklärung."; // text in the Subject field of the mail
        $Mailbody = "<p>Abgewiesener $module Request!</p>";
        logandMail($path2ROOT . "login/logs/" . $module . "_nok_log", // Name der Log Datei (wird mit Jahr ,Monat .log ergänzt)
        '', // Text welcher in den LOG Einzutragen ist (wird mit Datum , Uhrzeit und Systemdaten ergänzt)
        $email, // Mail Empänger(Liste)
        $MailSubject, // Subject Text der EMail
        $Mailbody); // Inhalt der Email in HTML format (wird mit Zusatzinformation ergänzt)

        return array(
            'message' => "Keine Einverständnisserklärung gegeben"
        );
    }

    return $TNTab_row;
}

// Ende von function

/**
 * Unterprogramm gibt den HTML Header aus
 *
 * @param string $title
 *            <title> tag text
 * @param string $stitle
 *            Unter- usatz- ) Titel
 * @param string $head
 *            zusätzliche <head> Zeilen. Auch <style>......</style
 * @param string $type
 *            Form der Seite
 *            
 *            
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $logo NEIN: keine Anzeige des Logo
 */
function HTML_header($title, $stitle, $head = '', $type = 'Form', $width = '90em', $PrtCtrl = "print")
// --------------------------------------------------------------------------------
{
    global $path2ROOT, $module, $logo, $jq, $jq_ui, $prot, $actor, $Anfix, $noform,$form_start;

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
   
    if (isset($jq_ui) && $jq_ui) {
        echo "<link  href='" . $path2ROOT . "login/common/javascript/jquery-ui-1.14.0/jquery-ui.min.css'  rel='stylesheet' type='text/css'>";
        echo "<link  href='" . $path2ROOT . "login/common/javascript/jquery-ui-1.14.0/jquery-ui.theme.min.css'  rel='stylesheet' type='text/css'>";
    }

    if (isset($jq) && $jq) {
        echo "<script src='" . $path2ROOT . "login/common/javascript/jquery-1.12.4.min.js' ></script>";
    }
    if (isset($jq_ui) && $jq_ui) {   
        echo "<script src='" . $path2ROOT . "login/common/javascript/jquery-ui-1.14.0/jquery-ui.min.js'></script>";
    }
    if (isset($prot) && $prot) {
       echo "<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
    }
    # echo " <script src='" . $path2ROOT."login/common/javascript/stylesheet-wechsler.js' type='text/javascript'></script>";
    # echo " <script src='" . $path2ROOT."login/common/javascript/stoprkey.js' type='text/javascript'></script>";
    
    echo $head;
    echo "</head>";

    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
        if ($ini_arr['Config']['mode'] == "Single" && $ini_arr['Config']['eignr'] != "") {
            if (!isset($_SESSION['VF_Prim'])) {
               
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
    
    if ($type == 'Form') {
       echo "<body class='w3-container' style='max-width:$width;' >"; //
       echo '<fieldset>'; ## FS 1 - 
       
       ?>
           <div class='w3-container' id='header'>
    <fieldset>
    <header>
    
       <div class='w3-row'>
          <div class='w3-col s9 m10 l11 '>
            <label><?php echo "<div style='float: left;'> <label>".$ini_arr['Config']['inst']."</label></div>"?><br>
            <p class='w3-center w3-xlarge'><?php echo $title ?></p>
          </div>
          <div class='w3-col s3 m2 l1 ' >
          <logo>
             <img  src= '<?php echo $path2ROOT?>login/common/imgs/<?php echo $ini_arr['Config']['sign'];?>' width='90%'> 
          </logo> 
        </div>
    </div>
    
    </header>
    </fieldset>
   
   <?php 
       
        if ($ini_arr['Config']['wart'] == "N") {} else {
            
            if ($ini_arr['Config']['wart'] == "J") {
                echo "<p class='error' style='font-size: 1.875em;'>Wartungsarbeiten - nur Abfragen möglich - keine Änderungen</p>";
            }
            if ($ini_arr['Config']['wart'] == "U") {
                echo "<p class='error' style='font-size: 1.875em;'>" . $ini_arr['Config']['warg'] . " </p>";
            }
        }

       echo '</div>';                               ## div 1 end - titeln und Logo
       echo "<fieldset>"; ## FS 2 Institution und Titel
    } else {
        echo "<body class='w3-container'  style='max-width:$width;'>";
        echo '<fieldset>';
    }
    if (!isset($form_start) || !$form_start) {
        echo "<form id='myform' name='myform' method='post' action='$actor' enctype='multipart/form-data'>";
    }
   
    flow_add($module, "Funcs.inc.php Funct: HTML_Header");
}

// Ende von function

/**
 * Unterprogramm gibt passend zu HTML_Header den trailer aus
 */
function HTML_trailer()
// --------------------------------------------------------------------------------
{
    global $module;

    flow_add($module, "Funcs.inc Funct: HTML_trailer");

   
    echo "
    <br>
    <footer class='footer'>
    <div class='copyrights' style='font-size: 0.7rem'>
    Copyright &copy; <span id='year'>
    <script>document.getElementById('year').innerHTML = new Date().getFullYear();</script>
    </span>
    Josef Rohowsky - All Rights Reserved
    </div>
    </footer>
";
    echo "</form>";
    
    echo "</div></fieldset></body></html>";
}

// Ende von function

/**
 * Unterprogramm Initial debug
 *
 * Wenn $debug=true - - Inhalte von $_SERVER, $_POST, $_GET, $FILES,$_SESSION werden angezeigt
 *
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function initial_debug()
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: initial_debug");

    if ($debug) {
        echo "<pre class=debug>Start von $module: " . $_SERVER['SCRIPT_FILENAME'];
        echo '<br>mb_internal_encoding(): <q>' . mb_internal_encoding() . '</q>';
        echo '<br>setlocale(0,0): ' . setlocale(0, 0) . '</pre>';
        echo '<pre class=debug>$SERVER: ';
        print_r($_SERVER);
        echo '<hr>$POST: ';
        print_r($_POST);
        echo '<hr>$GET: ';
        print_r($_GET);
        echo '<hr>$FILES: ';
        print_r($_FILES);
        if (isset($_SESSION)) {
            echo '<hr>$_SESSION: ';
            print_r($_SESSION);
        }
        echo '</pre>';
    }
}

// Ende von function

/**
 * Unterprogramm zum Einfügen des Submit Buttons - welcher die Phase auf 1 setzt
 *
 * Fügt ein:
 * - wenn Fehler aufgetreten sind - Die Aufforderung zum Beheben der Fehler
 * - Hinweis zum Betätigen des 'xxxxxx senden' Buttons
 * - 'xxxxxx Senden' Button (=submit) (welcher die Phase auf 1 setzt )
 *
 *
 * @param string $Button_text
 *            Text für den Button
 * @param number $Errors
 *            Fehler- Anzahl der vorhergegangnen Eingabe
 *            
 *            
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function Button_senden($Button_text, $Errors = 0)
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: Button_senden");

    echo "<div class='white'>";
    if ($Errors > 0) {
        echo "<span class='error'>$Errors Fehler. Vervollständigen/Korrigieren Sie die Eingabefelder des Formulares.</span><br>";
    }
    echo "Anschließend klicken Sie die Schaltfläche "; # <q>$Button_text</q>";
    echo "<button type='submit' name='phase' value=1 class=green>$Button_text</button>";
    echo "</div>";
}

// Ende von function

/**
 * Unterprogramm zum Aufruf des mysqli Query
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $sql
 *            SQL- Statement
 * @return array|boolean Antwort des mysqli_query
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function SQL_QUERY($db, $sql)
// --------------------------------------------------------------------------------
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: SQL_QUERY sql: $sql ");

    if ($debug) {
        echo "<pre class=debug>$sql</pre>";
    }
    # $return = mysqli_query($db,$sql)
    # or die("<br><b style='color:red;background:white;'>Fehler in mysql Query: <i>".mysqli_error($db)."</i></b> <b><pre style='background:white;'>$sql</pre></b><br>");
    # if ($debug OR $return===FALSE ) { echo '<pre class=debug>sql result: ' ; print_r($return); echo '</pre>'; }

    if ($return = mysqli_query($db, $sql)) {
        # echo "<pre class=debug>sql $sql <br>result: " ; print_r($return); echo '</pre>';
        return $return;
    } else {
        echo "<br><b style='color:red;background:white;'>Fehler in mysqli_query: <i>" . mysqli_error($db) . "</i></b> <b><pre style='background:white;'>$sql</pre></b><br>";
        exit();
        if ($debug or $return === FALSE) {
            echo '<pre class=debug>sql result: ';
            print_r($return);
            echo '</pre>';
        }
    }
    return $return;
}

// Ende von function SQL_QUERY

/**
 * Unterprogramm zur Indentifikation des Mitglieds.
 *
 * Anzeige des Feldes zur Eingabe der E-Mail-Adresse.
 *
 *
 * @param string $Feld_name
 *            Name des E-Mail-Adressen Feldes
 * @param string $EMail
 *            Fehler Meldung Text
 * @param string $Err_EMail
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function EMail_Eingabe($Feld_name, $EMail, $Err_EMail)
{
    global $debug, $module;

    flow_add($module, "Funcs.inc Funct: EMail_Eingabe");

    if (empty($Err_EMail)) {
        $attr = '';
    } else {
        $attr = 'autofocus class="error"';
    }
    echo '<fieldset>';
    echo "<b style='font-size:110%;color:darkblue'>Geben Sie die E-Mail-Adresse an," . "<br>mit der Sie in der Mitgliederdatei eingetragen sind:</b> ";
    # echo "<label for='f_EMail'>E-Mail-Adresse:</label> ";
    echo "<input type='text' name='$Feld_name' id='f_EMail' size='28' value='$EMail' required $attr>";
    if (! empty($Err_EMail)) {
        echo "<br><span class='error'>$Err_EMail</span>";
    }
    echo '</fieldset>';
}

# Ende von function EMail_Eingabe

/**
 * Unterprogramm zur Übersetzung der 1en Buchstsaben eines strings.
 *
 * weil es derzeit in php keine unktion mb_ucfirst gibt.
 *
 *
 * @param string $str
 *            source string
 * @return string übersetzter string
 */
function _mb_ucfirst($str)
{
    flow_add($module, "Funcs.inc Funct: _mb_ucfirst");

    $a = mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8');
    return $a . mb_substr($str, 1, null, 'UTF-8');
}

/**
 * Gibt einen Text auf der Browser Konsole aus.
 *
 * Die Methode verwendet die Javascript Funktion <code>console.log</code>, um den Text auf der Browser Konsole auszugeben.
 * print_r($xyz,True) soll ie Daten dann weitergeben
 * var_export($xyz,True) ebenso
 *
 *
 * @param string $output
 *            der auszugebende Text
 * @param boolean $with_script_tags
 *            wenn <code>true</code> dann werden &lt;script> tags um den Javascript code erzeugt
 */
function console_log(string $output, $with_script_tags = true)
{
    global $module;

    flow_add($module, "Funcs.inc Funct: console_log");

    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

/**
 * Aufzeichnen der Aufrufe
 *
 *
 * @param string $id
 *            Modul- Name
 * @param string $text
 *            Log- Text
 */
function flow_add($id, $text)
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

/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20240501
 */
?>