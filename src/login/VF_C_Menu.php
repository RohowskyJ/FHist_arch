<?php
/**
 * Haupt- Menu im internen Bereich
 * 2012 neu Josef Rohowsky
 */
session_start();

$module = 'VF_Prim';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT .  'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT .  'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT .  'login/common/BA_Funcs.lib.php';
require $path2ROOT .  'login/common/BA_Edit_Funcs.lib.php';

$N = False;
if (isset($_GET['N'])) {$N = True;}

$db = LinkDB('VFH');

$title = "Haupt- Menu";

$logo = 'JA';
$form_start = True;
$actor = "VF_C_Menu.php";
BA_HTML_header($title, '', 'Form', '70em'); 

echo "<fieldset>";

$flow_list = False;

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$db = LinkDB('VFH');

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if (isset($_POST['Submit'])) {
    $name = $_POST['Submit'];
}
if (isset($_POST['emailadr'])) {
    $emailadr = $_POST['emailadr'];
} else {
    $emailadr = "";
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
} else {
    $password = "";
}
if (isset($_POST['pwchg'])) {
    $pwchg = $_POST['pwchg'];
} else {
    $pwchg = "N";
}

if (isset($_POST['phase_p'])) {
    $phase_p = $_POST['phase_p'];
} else
    $phase_p = "";
if (isset($_GET['p_uid'])) {
    $p_uid = $_GET['p_uid'];
} else {
    $p_uid = "";
}
if (isset($_POST['p_uid'])) {
    $p_uid = $_POST['p_uid'];
}

if (isset($_GET['phase'])) {
    $phase = $_GET['phase'];
}

# VF_set_module_p();

if ($phase_p == 1) {
    if (isset($_POST['password1'])) {
        $password1 = $_POST['password1'];
    }
    ;
    if ($password == $password1) {
        Log_Pw_Upd($password, $password1);
        $phase_p = "";
    } else {
        $p_uid = $_SESSION[$module]['p_uid'];
        echo '<form id="test" name="test" method="post" action="VF_C_Menu.php">';
        echo "<p class='error'>Die eingegebenen Passworte sind nicht gleich, bitte neu eingeben.</p>";
        Log_Pw_Chg();
        echo "<input name='pwchg' type='hidden' id='pwchg' value='$pwchg'>";
        echo "<input name='phase_p' type='hidden' id='phase_p' value='$phase_p'>";
        echo "<input name='p_uid' type='hidden' id='p_uid' value='$p_uid'>";
        echo '<input name="Submit" type="submit" class="erfolg" value="Ändern">';
        echo "</fieldset></div>";
        echo "</form>";
        HTML_trailer();
        exit();
    }
}

# VF_chk_valid();
# VF_set_module_p();

if ($p_uid == "") {
    if (isset($_GET['p_uid'])) {
        $p_uid = $_GET['p_uid'];
    }
}
if ($debug) {
    echo "menu L 0119: \$p_uid $p_uid <br>";
}
if ($emailadr != "" && $password != "") { // Login auf erlaubt prüfen, zugriffe eheben, ev- Passwort ändern
    if ($debug) {
        echo "menu L 0123: $emailadr,$password <br>";
    }
    VF_Login($emailadr, $password);
    if ($debug) {
        echo "L 0127 nach Login <br>" . print_r($_SESSION['VF_Prim']) . "<br>";
    }
    if ($_SESSION['VF_Prim']['p_uid'] == "NoGood") {
        if (! isset($_SESSION['VF_Prim']['l_err'])) {
            $_SESSION['VF_Prim']['l_err'] = 0;
        }
        $_SESSION['VF_Prim']['l_err'] ++;
        echo "Benutzer-ID oder Passwort falsch. Bitte neu eingeben.";
        header("Location: index_int.php");
    }

    if ($pwchg == "J") {
        if ($phase_p == "") {
            $phase_p = "1";
        }

        VF_Log_Pw_Chg();

        echo "<input name=\"pwchg\" type=\"hidden\" id=\"pwchg\" value=\"$pwchg\"/>";
        echo "<input name=\"phase_p\" type=\"hidden\" id=\"phase_p\" value=\"$phase_p\"/>";
        echo "<input name=\"p_uid\" type=\"hidden\" id=\"p_uid\" value=\"$p_uid\"/><br>";

        echo "<button type='submit' name='phase' value='1' class=green>Ändern</button></p>";
        echo "</fieldset></div>";

        HTML_trailer();
        exit();
    }
} else { // kein Login, test ob aktive Session
    if ($p_uid == "") {
        if ($_SESSION['VF_Prim']['p_uid'] != "") {
            $p_uid = $_SESSION['VF_Prim']['p_uid'];
            VF_upd();
            # $p_uid = $_SESSION['VF_Prim']['p_uid'];
            $p_zug = $_SESSION[$module]['p_zug'];

            $all_upd = $_SESSION[$module]['all_upd'];
        } else {
            echo "<p class='error'>Keine Zugriffs- Berechtigung vorhanden.</p>";
            echo "<a href='index.php' >Zurück zum Login</a>"; //
        }
    }
}

VF_Count_add();

if ($debug) {
    echo "L 176: nach einlesen zugr <br>";
    print_r($_SESSION[$module]);
    echo "<br>";
}

$sk = $_SESSION['VF_Prim']['SK'];

$ini_arr = parse_ini_file($path2ROOT.'login/common/config_m.ini',True,INI_SCANNER_NORMAL);
$cnt_m = count($ini_arr['Modules']);
if (isset($ini_arr['Modules']) && $cnt_m >10){
    Edit_Tabellen_Header('Programmauswahl für Mitglieder');
    
    Edit_Separator_Zeile('Suchen nach Suchbegriffen');
    echo "<div class='w3-row'>"; // Kommentar
    echo "Beschreibungen von Fahrzeugen und Geräten: muskelgezogen und Motorgezogen";
    echo "</div>"; // Ende der Ausgabe- Kommentar
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    if ($ini_arr['Modules']['m_9'] == "J") {
        echo "<a href='VF_S_SU_Ausw.php?sk=$sk' target='Suchausw'>Suchen nach Suchbegriffen</a>";
    } else {
        echo "Programmteil nicht verfügbar.";
    }
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    Edit_Separator_Zeile('Referat 1 - Organisation');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<b>Datenabfrage laut DSVGO, E-Mail an andere Mitglieder, Protokolle, </b> Verwaltung der Daten von Mitgliedern, Benutzern und Zugriffen, Eigentümern, Empfängerliste autom. E-Mails, .... ";
    echo "</div>"; // Ende der Inhalt Spalte
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<a href='VF_V_Zentral_Verw.php?sk=$sk' target='Zentrale Verwaltung'>Zentrale Verwaltung Basisdaten</a>";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    Edit_Separator_Zeile('Referat 2 - Fahrzeuge und Geräte, mit Muskel oder Motor bewegt, Beschreibungen');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Beschreibungen von Fahrzeugen und Geräten: muskelgezogen und Motorgezogen";
    
    echo "</div>"; // Ende der Inhalt Spalte
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    if ($ini_arr['Modules']['m_3'] == "J") {
        echo "<a href='VF_FZ_Ger_Verw.php?sk=$sk' target='F-Verwaltung'>Fahrzeug und Geräte- Verwaltung </a>";
    } else {
        echo "Programmteil nicht verfügbar.";
    }
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    Edit_Separator_Zeile('Referat 3 - Öffentlichkeitsarbeit und Museen');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Links zu Bibliotheken, Marktplatz, Buch- Rezensionen, Dokumente zu herunterladen, Fotos, Videos, Museumsdaten, Presseberichte, Terminplan, Veranstaltungsberichte.";
    echo "</div>"; // Ende der Inhalt Spalte
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<a href='VF_O_Verw.php?sk=$sk' target='Oeffi'>Öffentlichkeitsarbeit</a>";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
        Edit_Separator_Zeile('Persönliche Ausrüstung - Beschreibungen');
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "Beschreibungen für alle Gegenstände die je FF Mitglied direkt der Person zugordnet ist (Uniform, Auszeichnugen, ... )";
        echo "</div>"; // Ende der Inhalt Spalte
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        if ($ini_arr['Modules']['m_8'] == "J") {
            echo "<a href='VF_PS_Info_Ausz_Abz.php?sk=$sk' target='Info_R4'>Auszeichnungen, Ärmelabzeichen, Wappen - DA 1.5.3, Uniformen, Heraldik</a>";
        } else {
            echo "Programmteil nicht verfügbar.";
        }
        echo "</div>"; // Ende der Ausgabe- Einheit Feld
    }

    Edit_Separator_Zeile('Beenden');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<div class='w3-light-grey'> &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp <a href='../'>LOGOFF (HomePage)</a></div>";
    echo "</div>"; // Ende der Inhalt Spalte
    
    Edit_Tabellen_Trailer();
    
} else {
    echo "Konfigurations- Fehler. Konfirguration der <b>Module</b> neu aufsetzen. <br>";
}

#echo "</fieldset>";
BA_HTML_trailer();
?>
