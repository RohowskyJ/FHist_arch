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
 *
 */
flow_add('List_Funcs', "BA_List_Funcs.lib.php Funct: Edit_Tabellen_Header");

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
 * @global string $SelectAnzeige Anzeige des Query ($sql) Ein/Aus
 * @global string $SpaltenNamenAnzeige Anzeige des Spaltennamens (Ein) oder des Kommentares (Klarname)
 * @global string $DropdownAnzeige Anzeige des Dropdown-Menus im Listenkopf
 * @global string $T_List Auswahlname der gewählten Liste, definiert in $T_List_Texte ($_POST)
 *
 *
 */
function List_Prolog($id, $T_List_Texte)
{
    global $debug, $path2ROOT, $module, $T_List, $List_parm, $csv_DSN, $SelectAnzeige, $SpaltenNamenAnzeige, $DropdownAnzeige;

    flow_add($module, "BA_List_Funcs.lib.php Funct: List_Prolog");

    # ===========================================================================================================
    # die Parameter lesen und die Werte fürs nächste mal aufheben
    # ===========================================================================================================
    $List_parm = Array();
    if (! isset($_SESSION["VF_LISTE"])) {
        $_SESSION["VF_LISTE"] = Array();
    }
    if (! isset($_SESSION[$id])) {
        $_SESSION[$id] = Array();
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

    if (! isset($T_List_Texte[$T_List])) # Wert in $T_List falsch
    {
        foreach ($T_List_Texte as $key => $text) # Dursuche alle möglichen Werte
        {
            $T_List = $key;
            break;
        } # nimm den 1e möglichen Wert
    }

    $_SESSION[$id]['T_List'] = $List_parm['T_List'] = $T_List;

    # -----------------------------------------------------------------------------------------------------------------
    # Tabellenspezifische Sortierfolge
    # -----------------------------------------------------------------------------------------------------------------
    if (isset($_GET['sord'])) {
        $sord = $_GET['sord'];
    } # nimm den als Parameter übergebenen Wert
    elseif (isset($_SESSION[$id]['sord'])) {
        $sord = $_SESSION[$id]['sord'];
    } else {
        $sord = '';
    }
    if ($sord != 'DESC') {
        $sord = 'ASC';
    } # kein Wert oder ungültiger Wert

    if (isset($_GET['scol'])) {
        $scol = $_GET['scol'];
    } # nimm den als Parameter übergebenen Wert
    elseif (isset($_SESSION[$id]['scol'])) {
        $scol = $_SESSION[$id]['scol'];
    } else {
        $scol = '';
    }

    $_SESSION[$id]['scol'] = $List_parm['sort_column'] = $scol; # Aufheben fürs nächste mal
    $_SESSION[$id]['sord'] = $List_parm['sort_richtung'] = $sord; # Aufheben fürs nächste mal

    # -----------------------------------------------------------------------------------------------------------------
    # Tabellenspezifische und Listenspezifische Liste der zu unterdrückenden Spalten
    # -----------------------------------------------------------------------------------------------------------------
    $idT = $id . '_' . $T_List; # id = Tabellenspezifisch und Listenspezifisch

    $hide_columns = ''; # der default Wert
    if (isset($_SESSION[$idT]['hide'])) {
        $hide_columns = $_SESSION[$idT]['hide'];
    }
    if (isset($_GET['hide'])) {
        if (mb_strpos(" $hide_columns ", $_GET['hide']) === false) {
            $hide_columns .= ' ' . $_GET['hide'];
        }
    }
    if (isset($_GET['unhide'])) {
        $hide_columns = '';
    } # lösche die Liste
    $_SESSION[$idT]['hide'] = $List_parm['hide'] = trim($hide_columns);

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
    # Select Anweisung Anzeige
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION["VF_LISTE"]['SelectAnzeige'])) {
        $SelectAnzeige = 'Aus'; # der default Wert
    }
    if (isset($_GET['SelectAnzeige'])) {
        $SelectAnzeige = $_GET['SelectAnzeige'];
    } elseif (isset($_SESSION["VF_LISTE"]['SelectAnzeige'])) {
        $SelectAnzeige = $_SESSION["VF_LISTE"]['SelectAnzeige'];
    }
    $_SESSION["VF_LISTE"]['SelectAnzeige'] = $List_parm['SelectAnzeige'] = $SelectAnzeige;

    # -----------------------------------------------------------------------------------------------------------------
    # Spalten Namen Anzeige
    # -----------------------------------------------------------------------------------------------------------------
    if (! isset($_SESSION["VF_LISTE"]['SpaltenNamenAnzeige'])) {
        $SpaltenNamenAnzeige = 'Ein'; # der default Wert
    }
    if (isset($_GET['SpaltenNamenAnzeige'])) {
        $SpaltenNamenAnzeige = $_GET['SpaltenNamenAnzeige'];
    } elseif (isset($_SESSION["VF_LISTE"]['SpaltenNamenAnzeige'])) {
        $SpaltenNamenAnzeige = $_SESSION['VF_LISTE']['SpaltenNamenAnzeige'];
    }
    $_SESSION['VF_LISTE']['SpaltenNamenAnzeige'] = $List_parm['SpaltenNamenAnzeige'] = $SpaltenNamenAnzeige;

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
        $csv_DSN = Module_Name;
    }
    if (isset($_SERVER['REMOTE_USER'])) {
        $csv_DSN .= $_SERVER['REMOTE_USER'];
    }
    $csv_DSN = $path2ROOT . "login/Downloads/$csv_DSN.csv";

    if ($debug) {
        echo '<pre class=debug>L 0292 $id=' . $id;
        echo '<br>$_SESSION[' . $id . ']: ';
        print_r($_SESSION[$id]);
        echo '<br>$_SESSION[' . $idT . ']: ';
        print_r($_SESSION[$idT]);
        echo '$_SESSION["VF_LISTE"]: ';
        print_r($_SESSION["VF_LISTE"]);
        echo '<hr>$List_parm: ';
        print_r($List_parm);
        echo '</pre>';
    }
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
    global $debug, $path2ROOT, $List_parm, $List_Parameter, $module;

    flow_add($module, "List_Funcs.inc.php Funct: List_Action_Bar");

    if ($debug) {
        echo '<pre class=debug>L 0336 ';
        echo '$_SESSION["VF_LISTE"]: ';
        print_r($_SESSION["VF_LISTE"]);
        echo '<hr>$List_parm: ';
        print_r($List_parm);
        echo "addit_act $addit_act";
        echo '</pre>';
    }

    if (! isset($List_Parameter)) {
        $List_Parameter = '';
    }

    $DD_List = $DD_aktiv = '';
    foreach ($T_List_Texte as $key => $text) # für alle
    {
        if ($T_List == $key) # Aktiv
        {
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

    ?>

<div class="List-grid-container"
     style="grid-template-columns: 100px auto">

     <div>
     <?php

    if (is_file($path2ROOT . 'login/common/config_s.ini')) {
        $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
    }
    if (isset($ini_arr['Config'])) {
        $logo = $ini_arr['Config']['sign'];
        echo "<img src='" . $path2ROOT . "login/common/imgs/$logo' alt='Signet Verein Feuerwehrhistoriker' style='border: 3px solid lightblue;  display: block; margin-left: auto; margin-right: auto; margin-top:6px;  width: 50%;'>";
    }

    ?>
     </div>

     <div>
     <!--
          <h2 style='text-align: left;'><?php echo $Heading;?></h2>
           -->
<p class='w3-xlarge'><?php echo $Heading;?></p>
          <input type='hidden' name='tabelle' value='<?php echo $Tabellen_Name;?>'>

    <div class="w3-bar w3-border w3-light-grey">

         <!-- =================================================================================================
        # Tabellen-Ansicht Dropdown
        ================================================================================================== -->
               <div class='w3-dropdown-hover'
                    style='padding-left: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 3'>
                    <b
                         style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Tabellen-Ansicht</b>:
                    <b><?php echo $DD_aktiv;?></b>
                    <div class='w3-dropdown-content w3-bar-block w3-card-4'><?php echo $DD_List;?>
                </div>
                    <!-- w3-dropdown-content -->
               </div>
               <!-- w3-dropdown-hover -->

     <!-- =================================================================================================
    # Optionales Auswahl Feld
    ================================================================================================= -->
    <?php if ( !strpos($DD_aktiv,'Auswahl')==false ) { ?>
    <div class='w3-dropdown-hover w3-light-grey'
                    style='padding-left: 5px; padding-right: 5px; z-index: 3'>
                    <input type='text' name='select_string'
                         value='<?php echo $List_parm['select_string'];?>' maxlength=40
                         size=10>
     </div>
     <!---
    < ?php } ?>
    --->
    <!-- =================================================================================================
    # Refresh Button
    ================================================================================================= -->
     <div class='w3-dropdown-hover w3-light-grey'
                    style='padding-left: 5px; padding-right: 5px; z-index: 3'>
                    <button type='submit' style='font-size: 18px'>Daten neu einlesen</button>
     </div>
<?php } ?>
    <!-- =================================================================================================
    # Hinweise Dropdown
    ================================================================================================= -->
     <div class='w3-dropdown-hover w3-right'
                    style='padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 3'>
                    <b
                         style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Hinweise</b>
          <div class='w3-dropdown-content w3-bar-block w3-card-4'
                         style='width: 50em; right: 0'>
                         <ul style='margin: 0 1em 0em 1em; padding: 0;'>
          <?php echo $Hinweise;?>

          <!-- ------------------------------------------------------- -->
           <li><div class=tooltip>
                              Nach Spalteninhalten Sortieren
                         <!-- ------------------------------------------------------- -->
                         <div class='tooltiptext'
                                             style='bottom: 100%; left: 0; width: 40em;'>
                                             Um die Liste nach dem Inhalt einen Spalte zu sortiern:
                                             <ol>
                                                  <li>Den Cursor auf den <b>blauen Spalten-Titel-Text</b>
                                                       positionieren.
                                                  </li>
                                                  <li>In der Pull-Down-Liste - die Sortierung durch Kicken
                                                       wählen.</li>
                                             </ol>
                                             <br>Die aktive Sortierung wird in der jeweiligen Spalte mit <img
                                                  src='<?php echo $path2ROOT;?>login/common/imgs/arrowUp.gif'
                                                  alt='Asc'> bzw. <img
                                                  src='<?php echo $path2ROOT;?>login/common/imgs/arrowDown.gif'
                                   alt='Desc'> angezeigt.
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
                    style='padding-left: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 3'>
                    <b
                         style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Optionen</b>
                    <div class='w3-dropdown-content w3-bar-block w3-card-4'
                         style='width: 25em; right: 0;'>
                  <?php
    if ($_SESSION['VF_LISTE']['SelectAnzeige'] == 'Ein') {
        $EinAus = "Aus$List_Parameter'>Select Anweisung nicht anzeigen";
    } else {
        $EinAus = "Ein$List_Parameter'>Select Anweisung anzeigen";
    }
    echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?SelectAnzeige=$EinAus</a>";

    if ($_SESSION['VF_LISTE']['SpaltenNamenAnzeige'] == 'Ein') {
        $EinAus = "Aus$List_Parameter'>Spalten Namen nicht anzeigen";
    } else {
        $EinAus = "Ein$List_Parameter'>Spalten Namen anzeigen";
    }
    echo "<a class='w3-bar-item w3-button' href='" . $_SERVER['PHP_SELF'] . "?SpaltenNamenAnzeige=$EinAus</a>";

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
    if (! $List_parm['hide'] == '') # -------------------------- Anzeige von Spalten --------------------------
    {
        echo "<div class='w3-dropdown-hover w3-light-grey w3-right' style='padding-left:5px;padding-right:5px;padding-top:5px;padding-bottom:5px;'>";
        echo "Nicht angezeigte Spalten: <q>" . $List_parm['hide'] . "</q> ";
        echo "<a class=button href='$_SERVER[PHP_SELF]?unhide=Y$List_Parameter' style='font-size:100%;'>alle anzeigen</a>";
        echo "</div>";
    } # Anzeige von Spalten
    ?>

  </div>

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
 * Lesen der SQL Tabelle und Ausgabe als HTML Tabelle
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
 * @global string $csv_DSN Name der *.csv- Datei
 * @global string $SelectAnzeige Anzeige des Query ($sql) Ein/Aus ->$_Sess
 * @global string $SpaltenNamenAnzeige Anzeige des Spaltennamens (Ein) oder des Kommentares (Klarname) ->$_Sess
 * @global string $List_Parameter wird beim Link zur Auswahl der Listenart benötigt ?? ->$_Sess
 * @global string $DropdownAnzeige Anzeige des Dropdown-Menus im Listenkopf ->$_Sess
 * @global string $TabButton Ein: Button, grün, mit phase = $on am Ende der form 'phase|green|Butt-Txt|return-to-scr'
 * @global string $Kateg_Name Text für die Teilen-Anzeige
 */
function List_Create($db, $sql_1, $sql_2 = '', $tab_nam_1 = '', $tab_nam_2 = '')
{
    global $debug, $path2ROOT, $module, $List_parm,
    $Tabellen_Spalten, $Tabellen_Spalten_COMMENT, $Tabellen_Spalten_tabelle, $Tabellen_Spalten_typ, $Tabellen_Spalten_style,
    $csv_DSN, $Div_Parm, $SelectAnzeige, $SpaltenNamenAnzeige, $List_Parameter, $DropdownAnzeige, $TabButton,$Kateg_Name, $zus_text, $CSV_Spalten;

    flow_add($module, "List_Funcs.inc Funct: List_Create");

    if ($debug) {
        echo "<pre class=debug style='color:red;'><b>Function List_Create in List_Funcs.inc</b></pre>";
    }

    if (!isset($zus_text)) {
        $zus_text = "";
    }
    if (!isset($Kateg_name)) {
        $kKateg_Name = "";
    }
    if ($Kateg_Name != "") {
        $Kateg_Name = "<span style='background-color:yellow;' > $Kateg_Name </span>";
    }

    # =================================================================================================================
    # Wenn das SQL Query Statement noch kein 'ORDER BY xxx' enthält >>> Ergänze es mit ORDER BY xxx
    # =================================================================================================================
    $sql_orderBy = '';
    if (strpos($sql_1, 'ORDER BY ') === false) # nicht enthalten
    { # --------------------------------------------------------------------------------------------------------------
      # Wenn die Spalte in $List_parm['sort_column'] angezeigt wird
      # - sortiere nach dieser Spalte
      # - andernfalls sortiern nach der 1en Spalte
      # ---------------------------------------------------------------------------------------------------------------
        foreach ($Tabellen_Spalten as $key => $column_name) # für alle angezeigten Spalten
        {
            if ($column_name == $List_parm['sort_column']) # Diese Spalte wird angezeigt
            {
                $sql_orderBy = "`$List_parm[sort_column]`  $List_parm[sort_richtung]";
                ;
                break; # Suche abbrechen
            }
        }

        if ($sql_orderBy == '') # diese Spalte wird nicht angezeigt
        {
            $List_parm['sort_column'] = $Tabellen_Spalten[0];
            $sql_orderBy = "`$Tabellen_Spalten[0]` $List_parm[sort_richtung]"; # Default: nach 1er Spalte sortieren
        }
        $sql_1 .= "\nORDER BY $sql_orderBy"; # ergänze das SQL QUERY Statement
        if ($sql_2 != "") {
            $sql_2 .= "\nORDER BY $sql_orderBy"; # ergänze das SQL QUERY Statement
        }
    }

    # =================================================================================================================
    # Das SQL Query Statement durchführen (schon hier weil die Zeilenanzahl benötigt wird)
    # =================================================================================================================
    if ($debug) {
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>1: $sql_1 \n2: $sql_2</pre>";
    }

    $query_result_1 = SQL_QUERY($db, $sql_1); # or die('Auslesen nicht möglich: ' . mysqli_error($db).' sql:'.$sql);
    $zeilen = mysqli_num_rows($query_result_1); # Anzahl der gefundenen Rows/Zeilen
    $Table_csv = array();
    $Table_Out = array();
    if ($debug) {
        echo "<pre class=debug>";
        print_r($query_result_1);
        echo "L 717 \$zeilen=$zeilen</pre>";
    }
    # echo "L 0714 vor einlesen tab_nam_1 $tab_nam_1 <br>";
    while ($row = mysqli_fetch_assoc($query_result_1)) # Für alle Tabellenzeilen
    {
        $Table_csv[] = $row;
        $modRC = modifyRow($row, $tab_nam_1);

        if (isset($row['Sort_Key']) && $row['Sort_Key'] != "") {
            $Table_Out[$row['Sort_Key']] = $row;
        /**
         *
         * @code $row['Sort_Key']] = $row[$name] ." ".$row[$vname] -> KO_Gem_List
         */
        } else {
            $Table_Out[] = $row;
        }
    }
    # echo "L 0730 nach dem einlesen von tab_nam_1 <br>";
    if ($sql_2 != "") {
        $query_result_2 = SQL_QUERY($db, $sql_2);
        while ($row = mysqli_fetch_assoc($query_result_2)) # Für alle Tabellenzeilen
        {
            $Table_csv[] = $row;
            $modRC = modifyRow($row, $tab_nam_2);

            if (isset($row['Sort_Key']) && $row['Sort_Key'] != "") {
                $Table_Out[$row['Sort_Key']] = $row;
            /**
             *
             * @code $row['Sort_Key']] = $row[$name] ." ".$row[$vname] -> KO_Gem_List
             */
            } else {
                $Table_Out[] = $row;
            }
        }
        ksort($Table_Out);
    }

    $zeilen = count($Table_Out);

    if ($SelectAnzeige == 'Ein') {
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql_1<br>$sql_2</pre>";
    }
    echo "\n<div class=white>";
    echo "<b>$zeilen Eintragungen mit den gewählten Kriterien $Kateg_Name gefunden. $zus_text</b>";

    if ($_SESSION['VF_LISTE']['CSVDatei'] == "Ein") {
        if (isset($csv_DSN)) {
            echo " <a href='$csv_DSN'>CSV Version ansehen</a>";
        }
    }

    echo '</div>';
    if ($zeilen == 0) {
        goto beenden;
    }

    if ($debug) {
        echo "<pre class=debug>L 769 Tabellen_Spalten ";
        print_r($Tabellen_Spalten);
        echo "</pre>";
        echo "<pre class=debug>Tabellen_Spalten_COMMENT ";
        print_r($Tabellen_Spalten_COMMENT);
        echo "</pre>";
        echo "<pre class=debug>Tabellen_Spalten_style ";
        print_r($Tabellen_Spalten_style);
        echo "</pre>";
    }

    # =======================================================================================================
    # Tabelle ausgeben
    # =======================================================================================================
    $weg = array(
        "'",
        '"',
        ";"
    ); # Zeichen welche im CSV nicht vorkommen dürfen - diese werden auf blank übersetzt

    $CSV_Text = ";Erzeugt " . date("Y-m-d H:i:s") . " von " . $_SERVER['PHP_SELF'] . "\n;Query: " . str_replace("\n", "", $sql_1);
    if (! $List_parm['hide'] == '') {
        $CSV_Text .= "\n;Nicht angezeigte Spalten: " . $List_parm['hide'];
    }

    $hide_columns = ' ' . $List_parm['hide'] . ' '; # nicht anzuzeigenden Spalten

    if ($_SESSION['VF_LISTE']['VarTableHight'] == "Ein") {
        if ($zeilen >= 15) {
            $T_Style = "style='min-height:15cm;'";
        } elseif ($zeilen >= 10) {
            $T_Style = "style='min-height:6cm;'";
        } else {
            $T_Style = "style='min-height:4cm; '";
        }
    } else {
        $T_Style = "style='min-height:15cm; '";
    }

    if ($_SESSION['VF_LISTE']['LangListe'] == "Aus") {
        echo "<div class='resize'  $T_Style  > ";
    } else {
        echo "<div  class='white'>";
    }
    ?>

<!-- =================================================================================================== -->
<!-- Tabelle Ausgeben                                                                                    -->
<!-- =================================================================================================== -->

<table id='myTable' class='w3-table w3-striped w3-hoverable scroll js-sort-table'
     style='border: 1px solid black; background-color: white; margin: 0px;'>

     <!-- =================================================================================================== -->
     <!-- Spalten Überschriften Ausgeben                                                                         -->
     <!-- =================================================================================================== -->
     <thead>
          <tr>
<?php
    $CSV_Text_zeile = $CSV_Text_zeile2 = ";";
    $i = 0; # Spaltenzähler

    foreach ($Tabellen_Spalten as $key => $column_name) # ================ für alle Spalten =================
    {

        if (mb_strpos($hide_columns, " $column_name ") !== false) {
            continue;
        } # skip hidden column
        $j = $i;
        $i ++; # Spaltenzähler
        # echo "\n  <th onclick='sortTable($j)'>";
        if (!isset($Tabellen_Spalten_typ[$column_name])) {
            echo "\n <th class='js-sort-none' title=\"class=&quot;js-sort-none&quot;\">";
        } else {
            switch ($Tabellen_Spalten_typ[$column_name]) {
                case "num":
                    echo "\n <th class='js-sort-number' title=\"class=&quot;js-sort-number&quot;\">";
                    break;
                case "text":
                    echo "\n <th <th class='js-sort-string' title=\"class=&quot;js-sort-string&quot;\">";
                    break;
                default:
                    echo "\n <th class='js-sort-none' title=\"class=&quot;js-sort-none&quot;\">";
                   break;
            }
        }

        if ($DropdownAnzeige == 'Ein') {
            echo "<div class='dropdown'>";
        }

        # -------------------------------------------- Spalten Überschriften -----------------------------------------

        $Pfeil = '';

        if ($column_name == $List_parm['sort_column'] & # Sortierrichtungs Pfeil nur für diese Spalte anzeigen
        $zeilen > 1) # Sortierrichtungs Pfeil nur anzeigen wenn es mehrere Zeilen gibt
        {
            if ($List_parm['sort_richtung'] == 'ASC') {
                $Pfeil = "<img src='" . $path2ROOT . "login/common/imgs/arrowUp.gif'   alt='Asc'> ";
            } else {
                $Pfeil = "<img src='" . $path2ROOT . "login/common/imgs/arrowDown.gif' alt='Desc'>";
            }
        }

        if ($SpaltenNamenAnzeige != 'Ein' && isset($Tabellen_Spalten_COMMENT[$column_name])) {
            $txt = $Tabellen_Spalten_COMMENT[$column_name];
            $txta = explode('<', $txt);
            $txt = $txta[0];
        } else {
            $txt = str_replace("_", " ", $column_name);
        }


        if ($DropdownAnzeige == 'Ein') {
            echo " <button class='dropbtn'>$txt</button>$Pfeil";
        } else {
            echo "$txt</th>";
            continue;
        }

        # -------------------------------------------- Spalten Drop Down Liste -----------------------------------
        if ($i < 6) {
            echo " <div class='dropdown-content'>";
        } else {
            echo " <div class='dropdown-content' style='right:0;'>";
        }

        if (isset($Tabellen_Spalten_COMMENT[$column_name])) {
            echo $Tabellen_Spalten_COMMENT[$column_name];
        }

        if ($zeilen > 1 & # Sortierrichtung nur wenn es mehrere Zeilen gibt
        isset($Tabellen_Spalten_tabelle[$column_name])) { # Sortierrichtung nur wenn die Spalte aus einer Tabelle kommt
            if ($Tabellen_Spalten_tabelle[$column_name] != '') { # Sortierrichtung nur wenn die Spalte aus einer Tabelle kommt
                echo "  <a href='$_SERVER[PHP_SELF]?scol=$column_name&sord=ASC$List_Parameter' >Aufsteigend sortieren</a>";
                echo "  <a href='$_SERVER[PHP_SELF]?scol=$column_name&sord=DESC$List_Parameter'>Absteigend sortieren</a>";
            }
        }

        echo "  <a href='$_SERVER[PHP_SELF]?hide=$column_name$List_Parameter'>Spalte nicht anzeigen</a>";
        echo "  </div>"; # Drop Down Liste beenden
        echo "</div>";
        echo "</th>";

    } # Header Spalten Schleife

    ?>

  </tr>
     </thead>
     <!-- =================================================================================================== -->
     <!-- Tabellen Daten Ausgeben                                                                         -->
     <!-- =================================================================================================== -->
     <tbody>
<?php
    # ========================================================================================================================
    # alle rows der SQL Tabelle lesen und die Werte in den Tabellen Body stellen
    # ========================================================================================================================
    $Zeilen_Nr = 0;

    Foreach ($Table_Out as $row) {
        $Zeilen_Nr ++;
        foreach ($Tabellen_Spalten as $key => $column_name) # alle Spalten ausgeben
        {
            if (mb_strpos($hide_columns, " $column_name ") !== false) {
                continue;
            } # hide column : skip ip
            $value = '';
            if (isset($row[$column_name])) {
                $value = $row[$column_name];
            }
            $style = "";
            if (isset($Tabellen_Spalten_style[$column_name])) {
                $style = $Tabellen_Spalten_style[$column_name];
            }
            echo "<td style='$style'>$value</td>";
        } # alle Spaltenausgeben
        echo "</tr>";
    } # while Ende # Für alle Tabellenzeilen

    # ========================================================================================================================
    # alle rows der SQL Tabelle lesen und die Werte in den Tabellen Body stellen
    # ========================================================================================================================
    if ($_SESSION['VF_LISTE']['CSVDatei'] == "Ein") {
        $Zeilen_Nr = 0;
        # var_dump($Table_csv);
        #echo "L 0946 $hide_columns <br>";

        if (isset($CSV_Spalten)) {
            $Tabellen_Spalten = $CSV_Spalten;
        }

        foreach($Tabellen_Spalten as $column_name )    {
            if (isset($Tabellen_Spalten_tabelle[$column_name])) {
                $CSV_Text_zeile .= "|$column_name";
                $CSV_Text_zeile2 .= "|";
                if (isset($Tabellen_Spalten_COMMENT[$column_name])) {
                    $CSV_Text_zeile2 .= str_replace($weg, "", $Tabellen_Spalten_COMMENT[$column_name]);
                }
            }
        }

        $CSV_Text .= "\n" . substr($CSV_Text_zeile, 1) . "\n" . substr($CSV_Text_zeile2, 1);

        Foreach ($Table_csv as $csvrow) {
            $Zeilen_Nr ++;
            $CSV_Text_zeile = $Zeilen_Nr . "| ";

            foreach ($Tabellen_Spalten as $key => $column_name) # alle Spalten ausgeben
            {

                if (mb_strpos($hide_columns, " $column_name ") !== false) {
                    continue;
                } # hide column : skip ip
                if (isset($csvrow[$column_name])) {
                    $value = $csvrow[$column_name];
                }

                if (isset($Tabellen_Spalten_tabelle[$column_name])) {
                    $CSV_Text_zeile .= '';
                    if ($Tabellen_Spalten_typ[$column_name] == 'text') {
                        $CSV_Text_zeile .= ' ';
                    }
                    if (isset($csvrow[$column_name])) {
                        $CSV_Text_zeile .= str_replace($weg, "", $csvrow[$column_name]);
                    }
                }
                if (isset($Tabellen_Spalten_typ[$column_name])) {
                    if ($Tabellen_Spalten_typ[$column_name] == 'text') {
                        $CSV_Text_zeile .= '|';
                    } else {
                        $CSV_Text_zeile .= '|';
                    }
                }

               # console_log( $CSV_Text_zeile);
            } # alle Spalten ausgeben
            #console_log( $CSV_Text_zeile);
            $CSV_Text_zeile = substr($CSV_Text_zeile, 0, - 1);
            $CSV_Text .= "\n" . $CSV_Text_zeile;
            #echo "L 0978 csv_zeile <br>";
        } # while Ende # Für alle Tabellenzeilen
    }

    ?>
  </tbody>
</table>

<?php
    echo "</div>";

    if (isset($TabButton)) { # phase in die die Eigabe gehen soll
        $tab_arr = explode("|", $TabButton); # 0: phase, 1: Farbe, 2: Text, 3: Rücksprung-Link
        $cnt = count($tab_arr);
        if ($cnt == 1) {
            $tab_arr[0] = 2;
            $tab_arr[1] = "green";
            $tab_arr[2] = "Daten abspeichern";
            $tab_arr[3] = ""; # retun to .scr
            $tab_arr[4] = False;
        }
        if ($_SESSION[$module]['all_upd'] | $tab_arr[4]) {
            echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
            echo "<button type='submit' name='phase' value='$tab_arr[0]' class='$tab_arr[1]'>$tab_arr[2]</button></p>";
        }
        if ($tab_arr[3] != "" && isset($_SESSION[$module]['Act'])) {
            echo "<p><a href='$tab_arr[3]?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";
        }
    }

    echo '</form>';

    if ($_SESSION['VF_LISTE']['CSVDatei'] == "Ein") {
        # ---------------------------------------------------------------------------------------------------------------
        # Die csv Version in einen File schreiben
        # ----------------------------------------------------------------------------------------------------------------
        if (isset($csv_DSN)) {
            $datei = fopen($csv_DSN, 'w');
            fputs($datei, mb_convert_encoding("$CSV_Text\n;Ende der Liste. $zeilen Datenzeilen", "ISO-8859-1"));
            fclose($datei);
        }

    }
    echo "<script src='common/javascript/sort-table.min.js' async></script>";


    beenden:
}

# Ende von Function VF_List_Create

/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20240318
 */
?>
