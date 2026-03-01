<?php

/**
 * Bibliothek zur Ausgabe von Listen.
 *
 * @author B.R.Gaicki  - neu 2018
 * Enthält in Admin Tabellen Programmen verwendete Funktionen
 *   - VF_List_Prolog        Eingabenerfassung und Defaults
 *   - VF_List_Action_Bar    Action bar zur Listen Auswahl
 *   - VF_List_Create        Lesen der SQL Tabelle und Ausgabe als HTML Tabelle
 *
 * Verwendet in allen List_xxxx Scripts
 * vorher durchgeführter Aufruf von
 *    - Funktion List_Tabellen_Spalten_parms - liest aus sql die Spaltendefinitionen und stellt diese in 5 Arrays
 *
 * Wenn das Listxxxx.php mit <a> aufgerufen wird - werden nicht alle Variablen/Werte übergeben
 * um dennoch alle Werte zu haben - werden diese in SESSION Variablen gespeichert
 * Es werden immer alle Variablen abgehandelt - auch wenn diese nicht beötigt werden. Eine Logik ist zu aufwendig
 *  - die variable $id enthält 'VF_'.Module_Name
 *  - $_SESSION[$id]['T_List']) - enthält die id des zuletzt gewählten Radio Buttons (Listen- Auswahl)
 *  - $_SESSION[$id]['scol']    - enthält den Namen der Spalte nach welcher zuletzt sortiert wurde
 *  - $_SESSION[$id]['sord']    - enthält die Sortierrichtung ASC/DESC nach welcher zuletzt sortiert wurde
 *  - $_SESSION[$id]['hide']    - enthält die Liste der Spalten - welche zuletzt versteckt wurden
 *
 * change Avtivity:
 *   2018       B.R.Gaicki  - neu
 *   2019-01-04 B.R.Gaicki  - session start entfernt >> in aufrufer gestellt
 *   2019-05-29 B.R.Gaicki  - Wenn es nur 1e Wahlmöglikeit gibt: diese ohne Anzeige Automatisch selektieren
 *   2019-06-04 B.R.Gaicki  - Text in 2 Spalten (Verwendung von GRID)
 *   2019-06-04 B.R.Gaicki  - Defaults für Sortierfolgen in List_Auswahl_v2.php definiert
 *   2019-06-16 B.R.Gaicki  - neu: Spezifische Anzeige 1er row - geteueret mit Variable $Row_ID
 *   2019-05-09 B.R.Gaicki  - Spalten mit Komentar=blank anzeigen. Spalten mit Kommentar Position 1=! NICHT anzeigen
 *   2019-06-29 B.R.Gaicki  - neu: optionale variable $List_Hinweise für Zusätzliche Hinweise
 *   2019-07-09 B.R.Gaicki - als List_Funcs_v2.php
 *   2019-08-22 B.R.Gaicki - Spalten mit Wert NULL nicht angezeigen
 *                           if ( isset($row[$column_name]) ) und if ( isset($csvrow[$column_name]) ) korrigiert
 *   2019-08-24 B.R.Gaicki - Name und Verzeichniss der .csv files geändert
 *                         - ;'" in csv als blank ausgeben - str_replace($weg, "",
 *   2019-09-16 B.R.Gaicki - Wenn $select_string nur blanks enthält >> auf '' setzen
 *   2020-01-10 B.R.Gaicki - neue Variable $SelectAnzeige zur Steuerung der Anzeige der Select Anweisung  (Query)
 *   2020-01-10 B.R.Gaicki - neue Variable $SpaltenNamenAnzeige zur Steuerung der Anzeige der Spalten Namen
 *   2020-04-10 B.R.Gaicki - verwendung von style z-index für pulldowns
 *   2020-04-19 B.R.Gaicki - neu: $List_Parameter um Listen Parameter weiterzureichen
 *   2020-10-06 B.R.Gaicki - Tabellen_Name als Parameter übergeben anstat Verwendung der Konstante
 *   2021-01-15 B.R.Gaicki - prefix VF_ zu funktionsnamen hinzugefügt
 *   2022-02-05 B.R.Gaicki - V5 (PixRipTab & login )
 *   2022-02-05 B.R.Gaicki - </form> ans ende von </table> - damit werden eingabefelder innerhalb der Tabelle möglich
 *   2022-02-05 B.R.Gaicki - 'options' dropdown durch text '& Eingabefelder' erweitert
 *
 *   2024-03-14 J Rohowsky - Merge Kexi-VF-KOBV Versionen erweiterte Lese- Funktionen
 *                         - CSV- Dateien abschaltbar
 *                         - Tabellen größe  (Höhe) variierbar
 *                         - VF_List[select_string|SelectAnzeige|SpaltenNamenAnzeige|DropDownAnzeige||CSVAusgabe
 *   2025-10-24 J Rohowsky - Umbau einiger Funktionen: Sortierung im sql, Sortierung immer mit js-inline, Neu-Eingabe immer unter Tabellen-Ansicht,  umbau Optionen und Hinweise
 *                           Anzeige Spaltenname immer Komment-Felder, per js zuschalten der Feld-Namen, Feldnamen sind immer vorbereitet, aber hidden              
 *   2025-10-26 JR           Selectanzeige Systemweit durch Sess-Parm VT_Prim.$module-SqlDisp ersetzt  
 *                           Sortierung mit js immer auf allen spalten aktiv, Sort- Auswahl ausgebaut
 *                           Tabellen- Titelzeile  ist immer (so vorhanden) mit dem Feld- Kommentar beschrieben, Dropdown = Ein 
 *                               zeigt  zusätzlich die Feldnamen, Auswahl ist nur mehr Feld nicht anzeigen
 *
 *   2026-01-13 JR           Tabellen auf jquery-ui mit Verstecken und wiederherstellen und jquer sort umgebaut, 
 *   
 *   20260116 JR             Umbau gestartet. Sortierung und Column- hide wird auf jquera oder react umgebaut, statt session-parameter wird sessionStorage verwenden
 *                           Kontroll- Abschitt  (action_bar) wird auch modifiziert
 *                           Liste: Inhalte werden beim Caller aufbereitet, anzuzeigende Listen- Spalten werden per array übergeben, 
 *                           Titel- Inhalte kommen aus COMMENT, wenn nicht durch eigenes array überschrieben werden ['feldname' => 'Alternativ- Titel'] für andere Titel 
 *                           Sortierung per js, array NoSort ['feldname' => 'J'] - wird nicht sortiebar markiert, arraa NoHide ['feldname' => 'J'] Spalte kann nicht verssteckt werden
 *                           
 *   
 */

/**
 * Ausgabe des oberen Listenteiles, Eingabenerfassung und Defaults
 *
 * Wenn ein Listxxxx.php mit <a> aufgerufen wird - werden nicht alle Variablen/Werte übergeben
 * um dennoch alle Werte zu haben - werden diese in SESSION Variablen gespeichert
 * Es werden immer alle Variablen abgehandelt - auch wenn diese nicht beötigt werden. Eine Logik ist zu aufwendig
 * - die variable $id enthält 'VF_'.Module_Name
 * - $_SESSION[$id]['T_List']) - enthält die id des zuletzt gewählten Radio Buttons
 * - $_SESSION[$id]['scol'] - enthält den Namen der Spalte nach welcher zuletzt sortiert wurde
 * - $_SESSION[$id]['sord'] - enthält die Sortierrichtung ASC/DESC nach welcher zuletzt sortiert wurde
 * - $_SESSION[$id]['hide'] - enthält die Liste der Spalten - welche zuletzt versteckt wurden
 *
 *
 * @param string $id
 *            Haupt- Index der $_SESSION [ 'VF_'.Module_Name $module]
 * @param array $T_List_Texte
 *            Auswahltexte für die Liste
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 * @global array @List_parm Aussehen der Liste: Ausgabe- Array: Auswahlstring, Sortierspalte, Sortierrichtung
 *         - $List_parm['select_string']
 *         - $List_parm['sort_column'] Name der Spalte nach welcher sortiert werden soll
 *         - $List_parm['sort_richtung'] Sortierrichtung ASC/DESC
 * @global string $csv_DSN Name der *.csv- Datei
 * @global string $DropdownAnzeige Anzeige des Dropdown-Menus im Listenkopf
 * @global string $T_List Auswahlname der gewählten Liste, definiert in $T_List_Texte ($_POST)
 *
 *
 */
function List_Prolog($id, $T_List_Texte)
{
    global $debug, $path2ROOT, $module, $sub_mod, $T_List, $List_parm, $csv_DSN, $DropdownAnzeige ;     

    # ===========================================================================================================
    # die Parameter lesen und die Werte fürs nächste mal aufheben
    # ===========================================================================================================
    $List_parm = array();
    if (! isset($_SESSION["VF_LISTE"])) {
        $_SESSION["VF_LISTE"] = array();
    }
    if (! isset($_SESSION[$id])) {
        $_SESSION[$id] = array();
    }
    # $id = 'VF_'.Module_Name;

    if ($debug) {
        echo '<pre class=debug>L 0107 $_GET: ';
        print_r($_GET);
        echo '$_POST: ';
        print_r($_POST);
        echo '<hr>$id=' . $id . '<br>$_SESSION[' . $id . ']: ';
        print_r($_SESSION[$id]);
        echo '$_SESSION["VF_LISTE"]: ';
        print_r($_SESSION["VF_LISTE"]);
        echo '</pre>';
    }

    # -----------------------------------------------------------------------------------------------------------------
    # Tabellenspezifische Listen Auswahl in Variale $T_List stellen:
    # #Wenn der Wert in $T_List falsch ist - nimm 1en möglichen Wert
    # -----------------------------------------------------------------------------------------------------------------
    $T_List = ''; # der default Wert
    if (isset($_GET['T_List'])) {
        $T_List = $_GET['T_List'];
    } elseif (isset($_POST['T_List'])) {
        $T_List = $_POST['T_List'];
    } elseif (isset($_SESSION[$id]['T_List'])) {
        $T_List = $_SESSION[$id]['T_List'];
    }

    if (! isset($T_List_Texte[$T_List])) { # Wert in $T_List falsch
        foreach ($T_List_Texte as $key => $text) { # Dursuche alle möglichen Werte
            $T_List = $key;
            break;
        } # nimm den 1e möglichen Wert
    }

    $_SESSION[$id]['T_List'] = $List_parm['T_List'] = $T_List;

    # -----------------------------------------------------------------------------------------------------------------
    # Tabellenspezifische und Listenspezifische Liste der zu unterdrückenden Spalten
    # -----------------------------------------------------------------------------------------------------------------
    $idT = $id . '_' . $T_List; # id = Tabellenspezifisch und Listenspezifisch

    # -----------------------------------------------------------------------------------------------------------------
    # Such Auswahl
    # -----------------------------------------------------------------------------------------------------------------
    $select_string = ''; # der default Wert
    if (isset($_POST['select_string'])) {
        $select_string = $_POST['select_string'];
    } elseif (isset($_SESSION[$module]['select_string'])) {
        $select_string = $_SESSION[$module]['select_string'];
    }
    if (trim($select_string) == '') {
        $select_string = '';
    }
    $_SESSION[$module]['select_string'] = $List_parm['select_string'] = $select_string;

    # -----------------------------------------------------------------------------------------------------------------
    # Dropdown Listen Anzeige
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION["VF_LISTE"]['DropdownAnzeige'])) {
        $DropdownAnzeige = 'Aus'; # der default Wert
    }
    if (isset($_GET['DropdownAnzeige'])) {
        $DropdownAnzeige = $_GET['DropdownAnzeige'];
    } elseif (isset($_SESSION["VF_LISTE"]['DropdownAnzeige'])) {
        $DropdownAnzeige = $_SESSION["VF_LISTE"]['DropdownAnzeige'];
    }
    $_SESSION["VF_LISTE"]['DropdownAnzeige'] = $List_parm['DropdownAnzeige'] = $DropdownAnzeige;

    # -----------------------------------------------------------------------------------------------------------------
    # CSV Datei Ausgabe
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION["VF_LISTE"]['CSVDatei'])) {
        $CSVDatei = 'Ein'; # der default Wert
    }
    if (isset($_GET['CSVDatei'])) {
        $CSVDatei = $_GET['CSVDatei'];
    } elseif (isset($_SESSION["VF_LISTE"]['CSVDatei'])) {
        $CSVDatei = $_SESSION["VF_LISTE"]['CSVDatei'];
    }
    $_SESSION["VF_LISTE"]['CSVDatei'] = $List_parm['CSVDatei'] = $CSVDatei;

    # -----------------------------------------------------------------------------------------------------------------
    # Kurze oder Lange Liste (Eingabe/Druck)
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION["VF_LISTE"]['LangListe'])) {
        $LangListe = 'Aus'; # der default Wert
    }
    if (isset($_GET['LangListe'])) {
        $LangListe = $_GET['LangListe'];
    } elseif (isset($_SESSION["VF_LISTE"]['LangListe'])) {
        $LangListe = $_SESSION["VF_LISTE"]['LangListe'];
    }
    $_SESSION["VF_LISTE"]['LangListe'] = $List_parm['LangListe'] = $LangListe;

    # -----------------------------------------------------------------------------------------------------------------
    # Listenhöhe nach Zeilenanzahl
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION['VF_LISTE']['VarTableHight'])) {
        $VarTableHight = 'Ein'; # der default Wert
    }
    if (isset($_GET['VarTableHight'])) {
        $VarTableHight = $_GET['VarTableHight'];
    } elseif (isset($_SESSION['VF_LISTE']['VarTableHight'])) {
        $VarTableHight = $_SESSION['VF_LISTE']['VarTableHight'];
    }
    $_SESSION['VF_LISTE']['VarTableHight'] = $List_parm['VarTableHight'] = $VarTableHight;

    # -----------------------------------------------------------------------------------------------------------------
    # Name für CSV file
    # -----------------------------------------------------------------------------------------------------------------

    if ($csv_DSN == "") {
        $csv_DSN = $module;
    }
    if (isset($_SERVER['REMOTE_USER'])) {
        $csv_DSN .= $_SERVER['REMOTE_USER'];
    }
    $csv_DSN = $path2ROOT . "login/Downloads/$csv_DSN.csv";

   
}

# Ende von Function VF_List_Prolog

/**
 * Action bar zur Listen Auswahl
 *
 * @param string $Tabellen_Name
 *            Name der anzutigenden Tabelle
 * @param string $Heading
 *            Titel der Liste
 * @param array $T_List_Texte
 *            Array mit den Texten für die Radio Buttons
 * @param string $T_List
 *            die ausgewähle Listen Art
 * @param string $Hinweise
 *            Text der Hinweis-Anzeige
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global array @List_parm Aussehen der Liste: Ausgabe- Array: Auswahlstring, Sortierspalte, Sortierrichtung
 *         - $List_parm['select_string']
 *         - $List_parm['sort_column'] Name der Spalte nach welcher sortiert werden soll
 *         - $List_parm['sort_richtung'] Sortierrichtung ASC/DESC
 *
 */
function List_Action_Bar($Tabellen_Name, $Heading, $T_List_Texte, $T_List, $Hinweise = '', $Zus_Ausw = '', $addit_act = '')
{
    global $debug, $path2ROOT, $List_parm, $List_Parameter, $module, $sub_mod, $NeuRec, $csv_DSN, $TabObject;

#echo __LINE__ ."path2Root $path2ROOT <br>";

    if (! isset($List_Parameter)) {
        $List_Parameter = '';
    }

    $DD_List = $DD_aktiv = '';
    foreach ($T_List_Texte as $key => $text) { # für alle
        if ($T_List == $key) { # Aktiv
            $DD_aktiv = $text;
            $DD_List .= "<a class='w3-bar-item'><span style='color:green'>$text</span></a>";
        } else {
            $DD_List .= "<a class='w3-bar-item w3-button' href='$_SERVER[PHP_SELF]?T_List=$key$List_Parameter'>$text</a>";
        }
    }

    $grosklein = "Die Auswahl erfolgt unabhängig von Groß/Kleinschreibung.
                  <br>Ausgenommen Umlaute - welche exakt angegeben sein müssen.";
    if ($Tabellen_Name == 'tfb3_users') {
        $grosklein = "Die Auswahl muss exakt angegeben sein.";
    }
    $OrgName = "";
    ?>
<!--  
    /**
     * Seiten- Kopf neu
     */
-->	
<div class="List-grid-container"
     style="grid-template-columns: 100px auto">

     <div>
     <?php

    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', true, INI_SCANNER_NORMAL);
    }
    if (isset($ini_arr['Config'])) {
        $logo = $ini_arr['Config']['sign'];
        echo "<img src='" . $path2ROOT . "login/common/imgs/$logo' alt='Signet Verein Feuerwehrhistoriker' style='border: 3px solid lightblue;  display: block; margin-left: auto; margin-right: auto; margin-top:6px;  width: 80%;'>";
        $OrgName = $ini_arr['Config']['inst'];
    }

    ?>
     </div>

     <div>
       <?php echo "<span style='float: left;'> <label>".$OrgName."</label></span>"; ?>
       <p class='w3-xlarge'><?php echo $Heading;?></p>
     
       <input type='hidden' name='tabelle' value='<?php echo $Tabellen_Name;?>'>
         
        <b><?php echo $DD_aktiv;?></b> 
         
        <div class="container w3-border w3-light-grey " style="max-width: 65em;">
        <!-- =================================================================================================
        # Tabellen-Ansicht Dropdown
        ================================================================================================== -->
            <div class='w3-dropdown-hover'
                    style='padding-left: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 61000;'>
                    
                 <b style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Tabellen- Auswahl</b>:
                    
                 <div class='w3-dropdown-content w3-bar-block w3-card-4' style='width:40em;'><?php echo $DD_List;?>
                 </div>
                    <!-- w3-dropdown-content -->
            </div>
            <!-- w3-dropdown-hover -->
            
            <?php 
            if ($NeuRec != '') {
                echo $NeuRec;
            }
            
            ?>
           <!--  <b><?php echo $DD_aktiv;?></b>  --> 
            
            <!-- =================================================================================================
            # Optionales Auswahl Feld
            ================================================================================================= -->
            <?php if (!strpos($DD_aktiv, 'Auswahl-') == false) { ?>
            <div class='w3-dropdown-hover w3-light-grey '
                    style='padding-left: 5px; padding-right: 5px; z-index: 11000'>
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Suche <input type='text' name='select_string'
                         value='<?php echo $List_parm['select_string'];?>' maxlength=40
                         size=10> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            </div>
 
            <!-- =================================================================================================
            # Refresh Button
            ================================================================================================= -->
            <div class='w3-dropdown-hover w3-light-grey'
                    style='padding-left: 5px; padding-right: 5px; z-index: 11000'>
                    <button type='submit' style='font-size: 18px'>Daten neu einlesen</button>
            </div>
      <?php } ?>
            <!-- =================================================================================================
            # Hinweise Dropdown
            ================================================================================================= -->
            <div class='w3-dropdown-hover w3-right'
                    style='padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 11000'>
                    <b style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Hinweise</b>
               <div class='w3-dropdown-content w3-bar-block w3-card-4'
                         style='width: 50em; right: 0'>
                   <ul style='margin: 0 1em 0em 1em; padding: 0;'>
                   <?php echo $Hinweise;?>

                   <!-- ------------------------------------------------------- -->
                       <li><div class=tooltip>
                              Nach Spalteninhalten Sortieren
                         <!-- ------------------------------------------------------- -->
                         <div class='tooltiptext'
                                             style='bottom: 100%; left: 0; width: 38em;'>
                                             Um die Liste nach dem Inhalt einen Spalte zu sortiern:
                                             <ol>
                                                  <li>Den Cursor auf den <b>Spalten-Titel-Text</b>
                                                       positionieren.
                                                  </li>
                                                  <li>     <!--  -->Rechts vom Text wird ein kleiner Pfeil sichtbar - dann ist diese Spalte sortierbar. -->
                                                  Auf den Pfeil klicken, dann wird diese Spalte sortiert</li>
                                             </ol>
                                             <!--  -->
                                             <b>Die aktive Sortierung</b> wird in der jeweiligen Spalte mit einem Pfeil <i>nach oben (</i><b>▴</b><i> - Aufsteigend), 
                                             nach unten (</i><b>▾</b></i> - Absteigend) oder nach rechts (</i><b>▸</b><i> - nicht sortiert)</i> angezeigt.
                                              -->
                         </div>
               </div></li>

                     <!-- ------------------------------------------------------- -->
                     <li><div class=tooltip>
                                Anzeige von Spalten Unterdrücken
                                <!-- ------------------------------------------------------- -->
                                <div class='tooltiptext'
                                             style='bottom: 100%; left: 0; width: 40em;'>
                                             Um die Anzeige einer eine Spalte zu unterdrücken:
                                    <ol>
                                                  <li>Den Cursor auf den <b>blauen Spalten-Titel-Text</b>
                                                       positionieren.
                                                  </li>
                                                  <li>In der Pull-Down-Liste - <q>Spalte nicht anzeigen</q>'
                                                       Kicken.
                                                  </li>
                                    </ol>
                                             <p>Dieser Vorgang kann für mehrere Spalte wiederholt werden.</p>
                                             Wenn mit dieser Funktion Spalten nicht angezeigt werden - wird
                                             die Liste der nicht angezeigten Spalten angezeigt - Und ein
                                             Button <q>Alle anzeigen</q>.
                                </div>
                        </div></li>

                              <!-- ------------------------------------------------------- -->
                              <li><div class=tooltip>
                                        Größe der Tabelle Ändern
                                        <!-- ------------------------------------------------------- -->
                                        <div class='tooltiptext'
                                             style='bottom: 100%; left: 0; width: 40em;'>
                                             Um die Tabellengröße zu verändern
                                             <ul>
                                                  <li>Das winzig kleine punktierte Dreieck - rechts unten in der
                                                       Tabelle - mit dem Cursor <b>anklicken und ziehen</b>.
                                                  </li>
                                             </ul>
                                             <br> <b>Achtung</b>: Wenn Sie die Tabellengröße auf diese Art
                                             verändern - ist die Tabellengröße fixiert und passt sich nicht
                                             mehr automatisch der Fensterbreite an.
                                        </div>
                                   </div></li>
                              <!-- ------------------------------------------------------- -->
                              <li>Auswahl Feld: Bei mancher <q><i>Tabellen-Ansicht</i></q> ist
                                   es möglich die Angezeigten Zeilen einzuschränken. Für diese wird
                                   ein zusätzliches Feld <q><i>Auswahl</i>:</q> angezeigt. <!-- ------------------------------------------------------- -->
                                   Es gelten
                                   <div class=tooltip>
                                        diese Regeln
                                        <div class='tooltiptext'
                                             style='top: 100%; left: 0; width: 40em;'>
                                             Geben sie im Feld <q><i>Auswahl</i>:</q> die Zeichenkette an
                                             nach welcher ausgewählt werden soll (Name oder E-Mail_Adresse
                                             oder ..) <br> <b>Groß/Kleinschreibung:</b><br><?php echo $grosklein;?>
                                             <br> <b>Wild-Cards:</b>
                                             <ul style='list-style-type: none; margin: 0;'>
                                                  <li>% steht für beliebig viele Zeichen</li>
                                                  <li>_ entpricht genau einem unbekannten Zeichen</li>
                                             </ul>
                                        </div>
                                   </div>

                         </ul>
                         <br>
                    </div>
                    <!-- w3-dropdown-content -->

               </div>
               <!-- w3-dropdown-hover -->
	
    <!-- =================================================================================================
    # Options Dropdown
    ================================================================================================== -->
    <div class='w3-dropdown-hover w3-right'
                    style='padding-left: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 11000'>
       <b style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Optionen</b>
       <div class='w3-dropdown-content w3-bar-block w3-card-4'
                         style='width: 25em; right: 0;'>
          <?php
          if ($_SESSION['VF_LISTE']['DropdownAnzeige'] == 'Ein') {
              $EinAus = "Aus$List_Parameter'>Dropdown & Eingabefelder nicht anzeigen";
          } else {
              $EinAus = "Ein$List_Parameter'>Dropdown & Eingabefelder anzeigen";
          }
          echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?DropdownAnzeige=$EinAus</a>";
          
          if ($_SESSION['VF_LISTE']['LangListe'] == 'Ein') {
              $EinAus = "Aus$List_Parameter'>Liste im Kurzformat (für Eingabe)";
          } else {
              $EinAus = "Ein$List_Parameter'>Liste im Langformat (für Druck)";
          }
          echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?LangListe=$EinAus</a>";
          
          if ($_SESSION['VF_LISTE']['VarTableHight'] == 'Ein') {
              $EinAus = "Aus$List_Parameter'>Listenhöhe abhängig von Anzahl der Datensätze";
          } else {
              $EinAus = "Ein$List_Parameter'>Listenhöhe Groß";
          }
          echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?VarTableHight=$EinAus</a>";
          
          if ($_SESSION['VF_LISTE']['CSVDatei'] == 'Ein') {
              $EinAus = "Aus$List_Parameter'>CSV Datei nicht erzeugen";
          } else {
              $EinAus = "Ein$List_Parameter'>CSV Datei erzeugen";
          }
          echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?CSVDatei=$EinAus</a>";
          
          ?>

      </div>

   
                    <!-- w3-dropdown-content -->
     </div>
     <!-- w3-dropdown-hover -->

    <!-- =================================================================================================
    # Nicht angezeigte Spalten
    ================================================================================================== -->
<?php
/*
    if (! $List_parm['hide'] == '') { # -------------------------- Anzeige von Spalten --------------------------
        echo "<div class='w3-dropdown-hover w3-light-grey w3-right' style='padding-left:5px;padding-right:5px;padding-top:5px;padding-bottom:5px;'>";
        echo "Nicht angezeigte Spalten: <q>" . $List_parm['hide'] . "</q> ";
        echo "<a class=button href='$_SERVER[PHP_SELF]?unhide=Y$List_Parameter' style='font-size:100%;'>alle anzeigen</a>";
        echo "</div>";
    } # Anzeige von Spalten
    */
    ?>

  </div>
  
  <div class="container" style="max-width: 65em; background-color: #FFFAF0;"> <!-- 2. Bar -->
   <!--  <div class="w3-bar w3-border  " style="max-width: 65em; background-color: #FFFAF0;">--> <!-- 2. Bar --> 
           <?php 
           # var_dump($_SESSION);
           $allow = erlaubnis();
           # echo $allow;
           if ($allow >= 4) { // ($_SESSION['BE']['all_upd'] == '1') {
        echo $NeuRec;
        /**
         *  debug switch beginn
         */
       
        
        
        if ( $allow >= 2)  { // isset($_SESSION['VF_Prim']['p_uid']) && $_SESSION['VF_Prim']['p_uid'] == '1' ) {
            $Hinweise = "<li>Blau unterstrichene Daten sind Klickbar <ul style='margin:0 1em 0em 1em;padding:0;'>  <li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li> ";
            $adm_cont = "
                <ul style='margin: 0 1em 0em 1em; padding: 0;'>
                $Hinweise
               </ul>
                ";
                
                ?>
           <!-- opPopOver -->
         
           <div class="dropup w3-center">
                <b class='dropupstrg' style='color:lightgrey; background-color:white;font-size: 10px; float: right;'>Dbg</b>
               <div class="dropup-content" style='bottom: -100px; right: -300px;'>
                   <b>Entwanzungs-Optionen</b> <br>
                   <i>Script-Module</i><br>
                   <?php
             
                   if (isset($_SESSION[$module]['Inc_Arr']) && count($_SESSION[$module]['Inc_Arr']) > 0) {
                       echo '<ul style="margin: 8px 0; padding-left: 20px; list-style: disc;">';
                       foreach ($_SESSION[$module]['Inc_Arr'] as $key) {
                           echo '<li style="margin: 4px 0; font-size: 0.9em;">' . htmlspecialchars($key) . '</li>';
                       }
                       echo '</ul>';
                   } else {
                       echo '<p style="color: #999; font-size: 0.9em; margin: 8px 0;">Keine Script Information enthalten</p>';
                   }
                   ?>
                   <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #ddd;">
                       SQL Befehl anzeigen <button id="toggleButt-sD" class='button-sm'>Einschalten</button><br>
                   </div>
         
             </div>
          </div>

        
            <!-- opPopOver ende -->
            <?php 
       
        # echo "</div>"; // ende kurzer Teil
        /**
         *  debug switch ende
         */
        }
        echo "<span class='toggle-csvDisp'>";   
        echo " <a href='$csv_DSN'> &nbsp; &nbsp; CSV Version ansehen</a>";
        echo "</span>";
    }
    ?>
 
  </div>  <!-- 2. Bar -->

  <!-- w3-bar -->


   </div>
  </div>
  
 

<?php

    if ($addit_act != "") { # # Funct- Aufruf oder code ? Berater- Auswahl oder ?
        echo "<div class='w3-center'><br>";
        echo $addit_act;
        echo "</div>"; // w3-noprint
    }
}

# End of Function VF_List_Action_Bar

/**
 * Ausgabe der Data $daten als HTML Tabelle
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $sql
 *            SQL Statement
 * @param string $Tabellen_Name
 *            Name der Tabelle
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global array $List_parm Aussehen der Liste: Ausgabe- Array: Auswahlstring, Sortierspalte, Sortierrichtung
 *         - $List_parm['select_string']
 *         - $List_parm['sort_column'] Name der Spalte nach welcher sortiert werden soll
 *         - $List_parm['sort_richtung'] Sortierrichtung ASC/DESC
 * @global array $Tabellen_Spalten Global Array (Schlüssel: Spaltenname) mit den Spaltennamen
 * @global array $Tabellen_Spalten_NULLABLE Global Array (Schlüssel: SpaltenName = 'Y' Feld ist Nullable
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $Tabellen_Spalten_tabelle Global Array (Schlüssel: Spaltenname) mit dem Namen der Tabelle
 * @global array $Tabellen_Spalten_typ Global Array (Schlüssel: Spaltenname) mit 'text'/'num'
 * @global array $Tabellen_Spalten_style Global Array (Schlüssel: Spaltenname) mit den Style für das <td> Element
 * @global array $Tabellen_Spalten_MAXLENGTH  Global Array (Schlüssel: Spaltenname) mit der maximalen Länge des Eingabestrings
 * @global string $csv_DSN Name der *.csv- Datei
 * @global string $SelectAnzeige Anzeige des Query ($sql) Ein/Aus ->$_Sess
 * @global string $SpaltenNamenAnzeige Anzeige des Spaltennamens (Ein) oder des Kommentares (Klarname) ->$_Sess
 * @global string $List_Parameter wird beim Link zur Auswahl der Listenart benötigt ?? ->$_Sess
 * @global string $DropdownAnzeige Anzeige des Dropdown-Menus im Listenkopf ->$_Sess
 * @global string $TabButton Ein: Button, grün, mit phase = $on am Ende der form 'phase|green|Butt-Txt|return-to-scr'
 * @global string $Kateg_Name Text für die Teilen-Anzeige
 */ 
function List_Create($meta, $data, $showColumns = [], $altTitel = [], $sortAble = [], $hideAble = [], $editAble = [], $tableId = 'default')
{
    global $debug, $path2ROOT, $module,  $sub_mod, $List_parm, $logger,
    $Div_Parm, $List_Parameter, $DropdownAnzeige, $TabButton,$Kateg_Name, $zus_text, $CSV_Spalten, $csv_DSN ;
    # $NeuRec;
    # var_dump($data);
    $logger->log('List_Create: ', $module, basename(__FILE__));
    
    if (!isset($csvDsn)) {$csvDsn = "";}
    
    if (!isset($zus_text)) {
        $zus_text = "";
    }
    if (!isset($Kateg_name)) {
        $Kateg_Name = "";
    }
    if (isset($Kateg_name) && $Kateg_Name != "") {
        $Kateg_Name = "<span style='background-color:yellow;' > $Kateg_Name </span>";
    }

    $zeilen = count($data); // sollte vor dem AUfruf der liste angezeigt werden, mehr spielraum
  
    echo "\n<div class=white>";
    echo "<b>$zeilen Eintragungen mit den gewählten Kriterien $Kateg_Name gefunden. $zus_text</b>";

    /** sessionStore
    if ($_SESSION['VF_LISTE']['CSVDatei'] == "Ein") {
        if (isset($csv_DSN)) {
            echo " <a href='$csv_DSN'> &nbsp; &nbsp; CSV Version ansehen</a>";
        }
    }
*/
    echo '</div>';
   
    if ($zeilen == 0) {
        goto beenden;
    }

    # =======================================================================================================
    # Tabelle ausgeben
    # =======================================================================================================
    $weg = array(
        "'",
        '"',
        ";"
    ); # Zeichen welche im CSV nicht vorkommen dürfen - diese werden auf blank übersetzt
    /*
    $CSV_Text = ";Erzeugt " . date("Y-m-d H:i:s") . " von " . $_SERVER['PHP_SELF'] . "\n;Query: " . str_replace("\n", "", $module);
    
    if (! $List_parm['hide'] == '') {
        $CSV_Text .= "\n;Nicht angezeigte Spalten: " . $List_parm['hide'];
    }
    */
    
    $colComments = $meta->getCommentsMap();
    $colTypes    = $meta->getTypesMap();
    $colStyles    = $meta->getStylesMap();
    
    ?>
    <div id="table-container">
    
    <!-- pure and slim container start-->
    <div class="simple-container-div">
    <table id="<?php echo $tableId ?>" class="display   " > <!--  pure-table pure-table-striped  -->
    <thead>
    <?php 
    
    # var_dump($colComments);
  #var_dump($showColumns);
    # var_dump($sortAble);
    # var_dump($colTypes);
    $titelText = "";
    $header1 ="<tr><th>Akt</th>";
    foreach ($showColumns as $col) {
        if (isset($altTitel[$col]) ) {
            $titelText = $altTitel[$col];
        } else {
            if (isset($colComments[$col]) ) {
                $titelText = $colComments[$col];
            } else {
                $titelText = ucfirst($col);
            }
        }
        $so = "";
        $sortParm = "";
        
        #if (isset($sortAble[$col])) {
            
            if ($colTypes[$col] == 'text') {
                $so = 'sorter-text';
            } elseif ($colTypes[$col] == 'num') {
                $so = 'sorter-digit';
            }
             
            $sortParm = " data-field=$col class='$so' ";
            
        #}
        /*
        <thead>
        <tr>
        <th data-field="id" class="sorter-digit">ID</th>
        <th data-field="name" class="sorter-text">Name</th>
        <th data-field="email" class="sorter-text">E-Mail</th>
        <th data-field="age" class="sorter-digit">Alter</th>
        </tr>
        </thead>
        */
        $header1 .= "<th $sortParm >$titelText</th>";
    }
    
    echo $header1."</tr>";
    ?>
    </thead>
    <tbody>
    <?php 
    $Zeilen_Nr = 0;
    foreach ($data as $row) {
        $Zeilen_Nr++;
        echo "<tr  data-id='1' >";
        foreach ($showColumns as $key => $column_name) { # alle Spalten ausgeben
           # if (mb_strpos($hide_columns, " $column_name ") !== false) {
               # continue;
           # } # hide column : skip ip
            $value = '';
            if (isset($row[$column_name])) {
                $value = $row[$column_name];
            }
            $style = "";
            if (isset($sty[$column_name])) {
                $style = $colStyles[$column_name];
            }
            echo "<td style='$style'>$value</td>";
        } # alle Spaltenausgeben
        echo "</tr>";
    } # while Ende # Für alle Tabellenzeilen
          
    
    ?>
    </tbody>
    <tfoot> 
    <tr><th colspan='7'>da soll auch was rein </th></tr>
    </tfoot>
    </table>

    </div>
    <!-- pure and slim container end -->
    <?php
    /*
    ?>
    <!-- older Table begin  -->
    <div id="<?php echo $tableId ?>_1">
    <div id="column-controls" style="margin-bottom:10px;">
    
    <table id="data-table" class="pure-table pure-table-striped ">
    <thead>
    <?php 
    
    # var_dump($colComments);
  #var_dump($showColumns);
    # var_dump($sortAble);
    # var_dump($colTypes);
    $titelText = "";
    $header1 ="<tr>";
    foreach ($showColumns as $col) {
        if (isset($altTitel[$col]) ) {
            $titelText = $altTitel[$col];
        } else {
            if (isset($colComments[$col]) ) {
                $titelText = $colComments[$col];
            } else {
                $titelText = ucfirst($col);
            }
        }
        $so = "";
        $sortParm = "";
        
        #if (isset($sortAble[$col])) {
            
            if ($colTypes[$col] == 'text') {
                $so = 'sorter-text';
            } elseif ($colTypes[$col] == 'num') {
                $so = 'sorter-digit';
            }
             
            $sortParm = " data-field=$col class='$so' ";
            
        #}

        $header1 .= "<th $sortParm >$titelText</th>";
    }
    
    echo $header1."</tr>";
    ?>
    </thead>
    <tbody>
    <?php 
    $Zeilen_Nr = 0;
    foreach ($data as $row) {
        $Zeilen_Nr++;
        foreach ($showColumns as $key => $column_name) { # alle Spalten ausgeben
           # if (mb_strpos($hide_columns, " $column_name ") !== false) {
               # continue;
           # } # hide column : skip ip
            $value = '';
            if (isset($row[$column_name])) {
                $value = $row[$column_name];
            }
            $style = "";
            if (isset($sty[$column_name])) {
                $style = $colStyles[$column_name];
            }
            echo "<td style='$style'>$value</td>";
        } # alle Spaltenausgeben
        echo "</tr>";
    } # while Ende # Für alle Tabellenzeilen
          
    
    ?>
    </tbody>
    </table>
    <?php
    */
    

    beenden:
}

# Ende von Function VF_List_Create

/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20240318
 */
?>
