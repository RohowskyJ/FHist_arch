<?php

/**
 * Auswahl nach Suchbegriffen zur Anzeige, Auswahl der Suchparameter
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_S_SU_Ausw_ph0.inc.php ist gestarted</pre>";
}

# =========================================================================================================
Edit_Tabellen_Header('Suchbegriffe auswählen');
# =========================================================================================================

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name

echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt

echo "  </div>"; // Ende der Inhalt Spalte
echo "</div>"; // Ende der Ausgabe- Einheit Feld

# =========================================================================================================
Edit_Separator_Zeile('Such- Umfang');
# =========================================================================================================

$suchtext = $suchname = $suchffname = $suchztauth = $drive = $basedir = "";

$sel_i = $sel_g = $sel_f = "";
$ausw = explode(",", $_SESSION[$module]['su_Umf']);

if (stripos($_SESSION[$module]['su_Umf'], "I") >= 0) {
    $sel_i = "CHECKED";
}
if (stripos($_SESSION[$module]['su_Umf'], "G") >= 0) {
    $sel_g = "CHECKED";
}
if (stripos($_SESSION[$module]['su_Umf'], "F") >= 0) {
    $sel_f = "CHECKED";
}


echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
echo "<div class='label'>Suche in Bereichen:  </div>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
echo "<input name='suchumfang[]' value='I'  type='checkbox'  $sel_i  >Archiv- und Inventar- Daten<br/>";
echo "<input name='suchumfang[]' value='G'  type='checkbox'  $sel_g >Fahrzeuge und Geräte<br/>";
echo "<input name='suchumfang[]' value='F'  type='checkbox'  $sel_f >Fotos<br/>";
echo "  </div>"; // Ende der Inhalt Spalte
echo "</div>"; // Ende der Ausgabe- Einheit Feld

if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
    
    # =========================================================================================================
    Edit_Separator_Zeile('Suche bei Eigentümern:');
    # =========================================================================================================
    $j = "0";
    $sub_funct = "8";
    $menuepunkte_eg = "";
    $eigentmr = "";
    $mit_name = "";
    
    # $options = VF_Sel_Eigner("eigntmr", 8);
    $sel_ei_a = $sel_ei_i = "";
    if ($_SESSION[$module]['su_Eig'] == "A") {
        $sel_ei_a = "CHECKED";
        $eigentmr = 0;
    } else {
        $sel_ei_i = "CHECKED";
        $eigentmr = $_SESSION[$module]['su_Eig'];
    }
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
    echo "<div class='label'>Suche bei Eigentümer(n): </div>";
    echo "  </div>";  // Ende Feldname
    echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
    echo "<input name='suche_bei' id='suche_alle' value='allen'   type='radio' $sel_ei_a ><strong>bei allen Eigentümern</strong>";
    # echo "<input name='suche_bei' id='suche_bei' value='eigenen' type='radio' ><strong>bei den eigenen (Benutzerabhängig)</strong><br/>";
    echo "<br/><input name='suche_bei' id='suche_ausw' value='auswahl' type='radio' $sel_ei_i ><strong>bei ausgewählten Eigentümern</strong>";
    
    echo "<input id='srch_eigent' name='eigentmr' width='100'>";
    echo "  </div>"; // Ende der Inhalt Spalte
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    if ($err_Msg != "") {
        echo "<br><br><div class='error'><b>$err_Msg</b></div>";
    }
  
} else {
    echo "<input name='suche_bei' id='suche_alle' value='allen'   type='hidden' >";
}

$sel_su_sa = "checked";
# =========================================================================================================
Edit_Separator_Zeile('Suche nach Sammlung');
# =========================================================================================================
echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
echo "<div class='label'><input name='suchb_ausw' id='suchb_samm' value='sammlung' type='radio' $sel_su_sa ><strong> nach Sammlung</strong></div>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
#echo "<legend>Auswahl der Sammlung, in der gesucht werden soll: </legend>";

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
 *
 *
 * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
 * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
 * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
 *
 * @Input-Parm $_POST['Level1...6']
 */

$MS_Lvl   = 4; # 1 ... 6
$MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung

$MS_Txt = array(
    'Auswahl der Sammlungs- Type (1. Ebene)',
    'Auswahl der Sammlungs- Gruppe (2. Ebene)',
    'Auswahl der Untergrupppe (3. Ebene)',
    'Auswahl des Spezifikation (4. Ebene) &nbsp; '
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val,$titel);

echo "  </div>"; // Ende der Inhalt Spalte
echo "</div>"; // Ende der Ausgabe- Einheit Feld

$sel_su_na = "selected";
$sel_su_fi = "";
$sel_su_ff = "";
$sel_su_aut = "";
# =========================================================================================================
Edit_Separator_Zeile('Suche in Findbüchern (bei Engabe der Daten definierte Begriffe)');
# =========================================================================================================

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
echo "<div class='label'><input name='suchb_ausw' id='suchb_findb' value='findbuch' type='radio' $sel_su_fi ><strong>im Findbuch </strong> </div>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
echo "<input name='suchtext' value='$suchtext'  size='35' type='text'>";
echo "  </div>"; // Ende der Inhalt Spalte
echo "</div>"; // Ende der Ausgabe- Einheit Feld

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
echo "<div class='label'><input name='suchb_ausw' id='suchb_name' value='namen' type='radio' $sel_su_na ><strong>nach Namen</strong></div>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
echo "<input name='suchname' value='$suchname'  size='35' type='text'>";
echo "  </div>"; // Ende der Inhalt Spalte
echo "</div>"; // Ende der Ausgabe- Einheit Feld

if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
    echo "<div class='label'><input name='suchb_ausw' id='suchb_ffnam' value='ffname' type='radio' $sel_su_ff ><strong>nach Feuerwehrnamen (in Zeitschriften)</strong></div>";
    echo "  </div>";  // Ende Feldname
    echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
    echo "<input name='suchffname' value='$suchffname'  size='35' type='text'>";
    echo "  </div>"; // Ende der Inhalt Spalte
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
    echo "<div class='label'><input name='suchb_ausw' id='suchb_ztautnam' value='autnam' type='radio' $sel_su_aut ><strong>nach Authoren (in Zeitschriften)</strong></div>";
    echo "  </div>";  // Ende Feldname
    echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalt
    echo "<input name='suchztauth' value='$suchztauth'  size='35' type='text'>";
    echo "  </div>"; // Ende der Inhalt Spalte
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
}


# =========================================================================================================
Edit_Tabellen_Trailer();

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='1' class=green>Suchen</button></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_S_SU_Ausw_ph0.inc.php beendet</pre>";
}
?>