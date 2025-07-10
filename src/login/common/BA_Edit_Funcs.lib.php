<?php

/**
 * Bibliothek für Eingabe-Formulare
 *
 * 2019       B.R.Gaicki  - neu
 * 2012       J. Rohowsky - adaptierug für VFHNÖ
 * 2024       J. Rohowsky - Umstellung von Tabelle auf w3-row
 *
 * enthält in Admin-Edit programmen verwendetet Funktionen
 *  - Edit_Send_Button           Send Button für Edit Phase 0 / 1
 *  - Edit_Separator_Zeile       Trennzeile in Edit  wir durch Block_Separator_start und Block_Separator_Ende ersetzt
 *  - KEXI_Edit_Text             Erzeugt eine Tabellen Zeile dem Wert als Text
 *  - Edit_Daten_Feld            Erzeugt eine Tabellen Zeile mit einem <input> Feld
 *  - Edit_Daten_Feld_Butt       Erzeugt eine Tabellen Zeile mit einem <input> Feld mit Button für Displ unhide
 *  - Edit_textarea_Feld         Erzeugt eine Tabellen Zeile mit einem <textarea> Feld
 *  - Edit_Radio_Feld            Erzeugt eine Tabellen Zeile mit <input type='radio'> Feldern
 *  - Edit_CheckBox              Erzeugt einen Checkbox - Input  als Einzelfelder
 *  - Edit_Check_Box             Erzeugt einen Checkbox - Input  als Array
 *  - Edit_Select_Feld           Erzeugt eine Tabellen Zeile mit einem <select> Feld - mit mehreren <option>
 *  - Edit_Feld_Zeile_header     internes Programm zum erzeugen der Tabellen Zeile mit Feld Titel
 *  - Edit_Feld_Zeile_Trailer    internes Programm zum erzeugen von Tabellen Zeile Ende
 *  - Edit_Show_pict             Anzeige eines vorhandenen Bildes
 *  - Edit_Upload_file           Upload einer neuen Datei
 *  - Edit_Tabellen_header       Start des Eingabe- Formulars
 *  - Edit_tabellen_trailer      Ende des Eingabe- Fotmulars
 *  - float_line                 Schimmel für Floating Aus
 *  - odd_even                   Feststellen ob Zahl gerade uder ungerade ist (True für ungerade)
 *
 * Es wird eine Tabelle erstellt mit den Spalten
 *    - Feld Name    - optional - gesteuert von $Edit_Funcs_FeldName
 *    - Beschreibung - Der Wert wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Feldnamen als Index genommen
 *    - Daten        - Der Daten Wert (value=) wird aus dem Array $neu mit dem Feldnamen als Index genommen
 *    - Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Feldname als Index genommen
 *
 *     wenn $phase <> 0 sind die felder Output olny
 *     auch wenn $Edit_Funcs_Protect==True ist
 *     * @globale boolean $Edit_Funcs_Protect == 0 True -> Nur Anzeige, keine Eingabe möglich
 *     mit $Edit_Funcs_FeldName==False kann die Feld Namens Spalte unterdrückt werden
 *
 * Änderungen:
 *   2019       B.R.Gaicki  - neu
 *   2019-05-12 B.R.Gaicki  - abhängig von $phase gemacht
 *   2019-05-29 B.R.Gaicki  - Edit_textarea_Feld - neu
 *   2020-01-01 B.R.Gaicki  - neue variable $Edit_Funcs_FeldName
 *   2020-03-28 B.R.Gaicki  - neue Variable $Edit_Funcs_Protect
 *   2019-09-26 J.Rohowsky  - Bilder anzeigen und hochladen - neu
 *   2019-10-23 J.Rohowsky  - Checkboxen ausgeben
 *   2019-12-27 J.Rohowsky, Abgl mit akt Edit_Funcs
 *   2020-04-25 B.R.Gaicki  - Edit_Radio_Feld erweitert um attribute für einezelne Buttons zu ermöglichen
 *   2020-07-17 B.R.Gaicki  - v3 includes auf .inc unbenannt
 *   2020-09-17 B.R.Gaicki  - neu Edit_CheckBox
 *   2020-10-16 B.R.Gaicki  - Edit_CheckBox weiterer Parameter : $FeldAttrr
 *   2022       J. Rohowsky - * @globale boolean $Edit_Funcs_Protect == 0 True -> Nur Anzeige, keine Eingabe möglich
 *   2024-11-11 J. Rohowsky - Umstellung von Tabelle auf w3-row
 */
flow_add($module, "Edit_Funcs.inc geladen");

if ($debug) {
    echo "Edit_funcs.inc.php ist geladen. <br>";
}

/**
 * Formular: Ausgabe eines Send- Buttons,
 *
 * entweder Phase = 1 oder phase = 0 wenn in phase == 1
 * oder Link für Rücksprung, wenn $zurück nicht leer
 *
 *
 * @param string $text
 *            Text für den Button
 * @param string $zurück
 *            Link für den Rücksprung
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global int $Errors Fehler- Zähler, wenn>0 Anzeige, dass Fehler korrigiert werden müssen
 *
 */
# ------------------------------------------------------------------------------------------------------------------------------------------
function Edit_Send_Button($text = '', $zurück = '') # Send Button für Edit Phase 0 / 1
# ------------------------------------------------------------------------------------------------------------------------------------------
{
    global $phase, $Errors;

    flow_add($module, "Edit_Funcs.inc Func, $modulet: Send_Button");

    echo "<br><div class='white'>";
    if ($Errors > 0) {
        echo "<span class='error'>$Errors Fehlermeldung(en). Vervollständigen/Korrigieren Sie die Eingabefelder des Formulares." . "</span><br>";
    }

    if ($phase == 0) {
        echo 'Nach Eingabe aller Daten - drücken Sie ' . "<button type='submit' name='phase' value=1 class=green>Daten Speichern</button><i>$text</i>";
        if (! empty($zurück)) {
            echo "<br>Wenn sie die Änderungen nicht Speichern möchten - drücken Sie <a href='$zurück'>Zurück zur Liste</a>";
        }
    } else {
        echo 'Wenn Sie Daten ändern möchten - klicken Sie ' . '<button type="submit" name="phase" value=0 class=green>Daten Ändern</button>';
        if (! empty($zurück)) {
            echo " Andernfalls - drücken Sie <a href='$zurück'>Zurück zur Liste</a>";
        }
    }

    echo '</div><br>';
}

/**
 * Form: Anzeige einer Separtor/Titel Zeile
 *
 * @param string $text   Anzeigtext
 *  @param string $button
 */
# ------------------------------------------------------------------------------------------------------------------------------------------
function Edit_Separator_Zeile($text, $button = "") # Trennzeile in Edit
{
    global $module;
    flow_add($module, "Edit_Funcs.inc Funct: Edit_Separator_Zeile");

    echo "<div class='w3-container' style='width:100%; background:#e4e4e4; padding-left:3%; padding-right:3%;'>";
    echo "<p class='w3-medium  ' style= 'width:auto'><b>$text</b>
          &nbsp; &nbsp; $button 
         </p>";
    echo "</div>";


}

# ------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Formular: Eingabe- Anzeige für ein Feld.
 *
 * Erzeugt eine Tabellen Zeile mit einem Text.
 *
 *
 * @param string $FeldName
 *            Anzuzeigendes Textfeld für Anzeige/Eingabe
 * @param string $InfoText
 *            Zusatzinformation für Feld
 *
 * @global $KEXI_Edt_neu array Abbild des Datensatzes (alle benötigten Felder)
 */
function Edit_Text($FeldName, $InfoText = '')
{
    global $neu, $ed_lcnt; # Array mit den neuen Werten

    $ed_lcnt++;
    $style = odd_even($ed_lcnt);

    Edit_Feld_Zeile_header($FeldName, $style);
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);

}

# End of function KEXI_Edit_Text

# ------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Formular: Anzeige einer Eingabe- Zeile mit Daten-Feld
 *
 * Erzeugt eine Tabellen Zeile mit einem <input> Feld
 * - wenn die $FeldLaenge mit 0 angegeben ist (default) UND $FeldAttr='' ist - wird <input type='hidden' ...> erzeugt
 * - bei Feld Länge > 0 wird das <input> Feld mit den angegeben $FeldAttr erzeugt - Default für type= ist 'text'
 * - bei $Edit_Funcs_Protect = True wird das Feld nur angezeigt
 *
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Der Daten Wert (value=) wird aus dem Array $neu mit dem Index $FeldName genommen
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 * Mit dem Feld $Edit_FeldMsg[$FeldName] kann autofocus oder class=error gesetzt werden ??
 *
 * @param string $feldname
 * Array index Name in $neu und $Tabellen_Spalten_COMMENT
 * @param string $FeldLaenge
 * Feld Länge für maxlength und size
 * @param string $InfoText
 * Zusatz Informationen für das Feld
 * @param string $FeldAttr
 * Attribut/Parameter für <input tag
 *     - required  = Eingabe erforderlich
 *     - maxlength= $feldlänge = max Eigabelänge
 *     - size=   $feldlänge = Anzeigelänge (= Default)
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 * @globale boolean $Edit_Funcs_Protect True -> Nur Anzeige, keine Eingabe möglich
 */
function Edit_Daten_Feld($FeldName, $FeldLaenge = 0, $InfoText = '', $FeldAttr = '')
{
    global $phase, $module, $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $Edit_Funcs_Protect , $ed_lcnt,$Tabellen_Spalten_MAXLENGTH,$Tabellen_Spalten_typ;

    flow_add($module, 'Edit_Funcs.inc.php Funct: Edit_Daten_Feld');

    Edit_Feld_Zeile_header($FeldName);

    $InputParm = "'id='$FeldName' name='$FeldName' value='" . $neu[$FeldName] . "' $FeldAttr" ."class='monitor'";
    if (($FeldLaenge == 0 & $FeldAttr == '') or $phase != 0 or $Edit_Funcs_Protect) {
        echo $neu[$FeldName]; # ."<input type='hidden' $InputParm>";
    } else {
        if (mb_strpos($InputParm, 'type=') === false) {
            $InputParm = " type='text' $InputParm";
        }
        # var_dump($Tabellen_Spalten_MAXLENGTH[$FeldName]);
        if (!$FeldLaenge == 0) {
            if (mb_strpos($InputParm, 'maxlength=') === false) {
                if ($Tabellen_Spalten_typ[$FeldName] == "text") {
                    $InputParm .= " maxlength='".$Tabellen_Spalten_MAXLENGTH[$FeldName]."'";
                }
            }

            if (mb_strpos($InputParm, 'size=') === false) {
                $InputParm .= " size='$FeldLaenge'";
            }
        }
        if (! empty($Err_msg[$FeldName])) {
            console_log(" <span class='error'>$Err_msg[$FeldName]</span>"); //
            if (mb_strpos($InputParm, 'autofocus') === false) {
                $InputParm .= ' autofocus';
            }
            if (!empty($Edit_FeldMsg[$FeldName])) {
                if (mb_strpos($InputParm, 'autofocus') === false) {
                    $InputParm .= ' autofocus';
                }
                if (mb_strpos($InputParm, 'class=') === false) {
                    $InputParm .= ' class="error"';
                }
            }
        }

        echo "<input  $InputParm>"; # class='w3-input'
    }
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }

    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# End of function

/**
 * Formular: Anzeige einer Eingabe- Zeile mit Daten-Feld
 *
 * Erzeugt eine Tabellen Zeile mit einem <input> Feld
 * - wenn die $FeldLaenge mit 0 angegeben ist (default) UND $FeldAttr='' ist - wird <input type='hidden' ...> erzeugt
 * - bei Feld Länge > 0 wird das <input> Feld mit den angegeben $FeldAttr erzeugt - Default für type= ist 'text'
 * - bei $Edit_Funcs_Protect = True wird das Feld nur angezeigt
 *
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Der Daten Wert (value=) wird aus dem Array $neu mit dem Index $FeldName genommen
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 * @param string $feldname
 * Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $FeldLaenge
 * Feld Länge für maxlength und size
 * @param string $InfoText
 * Zusatz Informationen für das Feld
 * @param string $FeldAttr
 * Attribut/Parameter für <input tag
 * @param string Button für unhide Auswahl zum Ändern/Eingeben
 * Beispiel: siehe VF_MA_Edit_ph0.inc.php
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 * @globale boolean $Edit_Funcs_Protect True -> Nur Anzeige, keine Eingabe möglich
 */
function Edit_Daten_Feld_Button($FeldName, $FeldLaenge = 0, $InfoText = '', $FeldAttr = '', $button = '')
{
    global $phase, $module, $Tabellen_Spalten_COMMENT, $Tabellen_Spalten_MAXLENGTH, $neu, $Err_msg, $Edit_Funcs_Protect, $ed_lcnt  ;

    Edit_Feld_Zeile_header($FeldName, $button);

    $InputParm = "'id='$FeldName' name='$FeldName' value='" . $neu[$FeldName] . "' $FeldAttr";
    if (($FeldLaenge == 0 & $FeldAttr == '') or $phase != 0 or $Edit_Funcs_Protect) {
        echo $neu[$FeldName]; # ."<input type='hidden' $InputParm>";
    } else {
        if (mb_strpos($InputParm, 'type=') === false) {
            $InputParm = " type='text' $InputParm";
        }
        /*
        if (! $FeldLaenge == 0) {
            if (mb_strpos($InputParm, 'maxlength=') === false) {
                $InputParm .= " maxlength='$FeldLaenge'";
            }
            if (mb_strpos($InputParm, 'size=') === false) {
                $InputParm .= " size='$FeldLaenge'";
            }
        }
        */
        if (!$FeldLaenge == 0) {
            $FldLength = "";
            if (isset($Tabellen_Spalten_MAXLENGTH[$FeldName]) && $Tabellen_Spalten_MAXLENGTH[$FeldName] > 0) {
                $FldLength = $Tabellen_Spalten_MAXLENGTH[$FeldName];
                $InfoText .= " Maximale Eingabe $FldLength Zeichen ";
            }

            if (mb_strpos($InputParm, 'maxlength=') === false) {
                $InputParm .= " maxlength='$FldLength'";
            }
            if (mb_strpos($InputParm, 'size=') === false) {
                $InputParm .= " size='$FeldLaenge'";
            }
        }

        if (!empty($KEXI_Edit_FeldMsg[$FeldName])) {
            if (mb_strpos($InputParm, 'autofocus') === false) {
                $InputParm .= ' autofocus';
            }
            if (mb_strpos($InputParm, 'class=') === false) {
                $InputParm .= ' class="error"';
            }
        }
        if (! empty($Err_msg[$FeldName])) {
            console_log(" <span class='error'>$Err_msg[$FeldName]</span>");
            if (mb_strpos($InputParm, 'autofocus') === false) {
                $InputParm .= ' autofocus';
            }
            if (mb_strpos($InputParm, 'class=') === false) {
                $InputParm .= ' class="error"';
            }
        }

        echo "<input  $InputParm>"; # class='w3-input'
    }
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }

    Edit_Feld_Zeile_Trailer($FeldName.$button, $InfoText);

}

# End of function

/**
 * Format: Anzeige einer Text- Area
 *
 * Erzeugt eine Tabellen Zeile mit einem <textarea> Feld
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Der Daten Wert (value=) wird aus dem Array $neu mit dem Index $FeldName genommen
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 * @param string $FeldAttr
 *            Attribut/Parameter für <input tag
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global boolean $Edit_Funcs_Protect == 0 True -> Nur Anzeige, keine Eingabe möglich
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 *
 */
function Edit_textarea_Feld($FeldName, $InfoText = '', $FeldAttr = '')
{
    global $phase, $Edit_Funcs_Protect, $Tabellen_Spalten_COMMENT, $Tabellen_Spalten_MAXLENGTH ,$neu, $Err_msg, $module, $ed_lcnt  ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_textarea");
    # var_dump($Tabellen_Spalten_MAXLENGTH[$FeldName]);
    Edit_Feld_Zeile_header($FeldName);
    $FldLength = $MaxLength = "";
    if (isset($Tabellen_Spalten_MAXLENGTH[$FeldName]) && $Tabellen_Spalten_MAXLENGTH[$FeldName] > 0) {
        $FldLength = $Tabellen_Spalten_MAXLENGTH[$FeldName];
        $MaxLength = "maxlenght='$FldLength'";
        $InfoText .= " Maximale Eingabe $FldLength Zeichen ";
    }
    $InputParm = "id='$FeldName' name='$FeldName' $FeldAttr rows='3' cols='50' $MaxLength";

    if (! empty($Err_msg[$FeldName])) {
        if (mb_strpos($InputParm, 'autofocus') === false) {
            $InputParm .= ' autofocus';
        }
    }

    if ($Edit_Funcs_Protect or $phase != 0) {
        echo $neu[$FeldName];
    } else {
        echo "<textarea  $InputParm>" . $neu[$FeldName] . "</textarea>"; # class='w3-input'
    }
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# End of function

/**
 * Forms: Anzeige von Radio- Buttons
 *
 * Erzeugt eine Tabellen Zeile mit <input type='radio'> Feldern
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Die möglichen Daten Werte wird aus dem Array $Buttons genommen
 * Der Radio Button dessen Wert mit dem Wert in Array $neu mit dem Index $FeldName übereinstimmt wird selektiert.
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 *
 * @param string $FeldName
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param array $buttons
 *            Auswahl- Array 'SelCode'=>'Auswahl-text'
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 *
 * @globale boolean $Edit_Funcs_Protect == 0 True -> Nur Anzeige, keine Eingabe möglich
 */
function Edit_Radio_Feld($FeldName, array $Buttons, $InfoText = '') # Zusatz Informationen für das Feld
{
    global $phase, $Edit_Funcs_Protect, $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $module , $ed_lcnt ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_Radio_Feld");

    Edit_Feld_Zeile_header($FeldName);

    $cnt = count($Buttons);

    foreach ($Buttons as $value => $text) {
        $attr = '';
        if ($value == "") {
            $value = " ";
        }

        if ($neu[$FeldName] == $value or $cnt == 1) {
            $attr .= ' checked';
        } elseif ($Edit_Funcs_Protect or $phase != 0) {
            $attr .= ' disabled';
        }
        if (is_array($text)) {
            $attr .= ' ' . $text[1];
        }

        if (is_array($text)) {
            $text = $text[0];
        }
        echo "<label><input class='w3-radio ' type='radio' name='$FeldName' id='$FeldName' value='$value' $attr> $text <br></label> ";
    }
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }

    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# of function

# ------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Formular: Anzeigevon Check- Boxen
 *
 * Erzeugt eine Tabellen Zeile mit <input type='checkbox'>
 * Input- Anzeige als Einzelfeld ($feldname als Input-Feld-Name)
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $text
 *            Ausgabe- Text
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 * @param string $FeldAttr
 *            Attribut/Parameter für <input tag
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global boolean $Edit_Funcs_Protect True -> Nur Anzeige, keine Eingabe möglich
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 */
function Edit_CheckBox($FeldName, $text, $InfoText = '', $FeldAttr = '')
{
    global $phase, $Edit_Funcs_Protect, $neu, $Err_msg, $module, $ed_lcnt  ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_CheckBox");

    $Err_msg; # array mit Fehlermeldungen
    Edit_Feld_Zeile_header($FeldName);
    $InputParm = "id='$FeldName' name='$FeldName' value='Y' $FeldAttr";
    if ($Edit_Funcs_Protect) {
        echo "$text";
    } else {
        $checked = "";
        if ($neu[$FeldName] == "Y") {
            $checked = "checked='checked'";
        }
        echo "<input  class='w3-check ' type='checkbox' $InputParm  $checked > <b style='font-size:medium;font-style:normal'>$text</b>";
    }
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# of function

/**
 * Formular: Anzeige einer Check- Box
 *
 * Erzeugt eine Tabellen Zeile mit <input type='checkbox'> Feldern
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Die möglichen Daten Werte wird aus dem Array $Boxes genommen
 * Die Check-box dessen Wert mit dem Wert in Array $neu mit dem Index $FeldName übereinstimmt wird selektiert.
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $F_Name[] genommen
 *
 *
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param array $Boxes
 *            Array in der Form: wert => text
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_Msg mit $Feldname als Key
 */
function Edit_Check_Box($FeldName, array $Boxes, $InfoText = '')
{
    global $phase, $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $module, $ed_lcnt  ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_Check_Box");

    Edit_Feld_Zeile_header($FeldName);

    foreach ($Boxes as $value => $text) {
        if ($neu[$FeldName] == $value) {
            $checked = 'checked';
        } elseif ($phase != 0) {
            $checked = 'disabled';
        } else {
            $checked = '';
        }
        echo "<label><input  class='w3-check' type='checkbox' name='F_Name[]' id='$FeldName' value='$value' $checked> $text <br> </label> ";  #  w3-input
    }

    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# of function

/**
 * Formular: Anzeige eines Select- Feldes
 *
 * Erzeugt eine Tabellen Zeile mit einem <select> Feld - mit mehreren <option>
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Die möglichen Daten Werte für <option value=..> wird aus dem Array $Options genommen
 * Die <option> dessen Wert mit dem Wert in Array $neu mit dem Index $FeldName übereinstimmt wird selektiert.
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param array $Options
 *            Anzeige- Array
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global boolean $Edit_Funcs_Protect True -> Nur Anzeige, keine Eingabe möglich
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 */
function Edit_Select_Feld($FeldName, array $Options, $InfoText = '')
{
    global $phase, $Edit_Funcs_Protect, $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $module , $ed_lcnt ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_Select_Feld");

    Edit_Feld_Zeile_header($FeldName);
    echo "<select id='$FeldName' name='$FeldName' id='$FeldName' size='1'>";
    foreach ($Options as $value => $text) {
        if ($neu[$FeldName] == $value) {
            $sel = ' selected';
        } elseif ($Edit_Funcs_Protect or $phase != 0) {
            $sel = 'disabled';
        } else {
            $sel = '';
        }
        echo "<option value='$value' $sel>[$value] $text</option>";
    }
    echo '</select>';
    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# of function


/**
 * Formular: Unterfunktion der lokalen Funktionen: Ausgabe der Zeile, 1.
 * Teil
 *
 * internes Programm zum erzeugen der Tabellen Zeile mit Feld Titel
 *
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 *
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global boolean $Edit_Funcs_FeldName
 * @param  string $style Hintergrund odd/even, default weiss
 */
function Edit_Feld_Zeile_header($FeldName, $button_act = "")  # = "background-color:white"
{
    global $Tabellen_Spalten_COMMENT, $Edit_Funcs_FeldName, $ed_lcnt ;

    $ed_lcnt++;
    $style = odd_even($ed_lcnt);

    if ($style) {
        echo "<div class='w3-row' style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe #c0c0ff
    } else {
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    }

    echo "<div class='w3-third  $style ' >"; // Beginn der Anzeige Feld-Name
    if (! (isset($Edit_Funcs_FeldName) and $Edit_Funcs_FeldName == false)) {
        echo " [$FeldName] ";
    }
    if (isset($Tabellen_Spalten_COMMENT[$FeldName])) {
        #echo "  <span class='ed-beschr'>$Tabellen_Spalten_COMMENT[$FeldName]</span>";
        echo "  <span class='info'>$Tabellen_Spalten_COMMENT[$FeldName] $button_act</span>";
    } else {

    }
    echo "  </div>";  // Ende Feldname
    echo "  <div class='w3-twothird   $style' >"; // Beginn Inhalt- Spalte

} # ende function Feld_Zeilen_header

/**
 * Formular: Formular: Unterfunktion der lokalen Funktionen: Ausgabe der Zeile, 2.
 * Teil
 *
 * internes Programm zum erzeugen von Tabellen Zeile Ende
 *
 *
 * @param string $feldname
 *            wird nicht benutzt !! Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 */
function Edit_Feld_Zeile_Trailer($FeldName, $InfoText, $auto_resp = "")
{
    global $Tabellen_Spalten_TYPE;

    if (! empty($InfoText)) {
        echo " <span class='info'>$InfoText</span>";

    }
    echo "$auto_resp";

    echo "</div>"; // Ende der Inhalt Spalte
    echo "</div>"; // Ende der Ausgabe- Einheit Feld

} # ende function n_Feld_Zeilen_trailer


/**
 * Formular: File- Uploads Eingaben.
 * Fotos und Dokumene hochladen
 *
 * Erzeugt eine Tabellen Zeile mit einem <input> Feld
 * - wenn die $FeldLaenge mit 0 angegeben ist (default) UND $FeldAttr='' ist - wird <input type='hidden' ...> erzeugt
 * - bei Feld Länge > 0 wird das <input> Feld mit den angegeben $FeldAttr erzeugt - Default für type= ist 'text'
 *
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Der Daten Wert (value=) wird aus dem Array $neu mit dem Index $FeldName genommen
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $Ident
 *            Identifikation für mehrre Uploads
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 * @param string $FeldAttr
 *            Attribut/Parameter für <input tag
 *
 * @global int $phase wenn 0 normale Eingabe
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 * @global string $pict_path Path zum Speicherort der Bilder / Dokumente
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 */
function Edit_Upload_File($FeldName, $Ident = '1', $InfoText = '', $FeldAttr = '')
{
    global $phase, $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $pict_path, $debug, $module, $ed_lcnt ;

    flow_add($module, "Edit_Funcs.inc.php Funct: Edit_Upload_File");

    echo '<input type="hidden" name="MAX_FILE_SIZE" value="40000000" >';

    echo "<input type='hidden' name='$FeldName$Ident' value='$neu[$FeldName]' >";

    if (isset($Tabellen_Spalten_COMMENT[$FeldName])) {
        echo "  <td><span class='info'>$Tabellen_Spalten_COMMENT[$FeldName] <b>$FeldName</b> Bild/Datei hochladen </span></td>";
    } else {
        echo "  <td></td>";
    }
    echo "<td><input type='file'   id='f_Doc_$Ident' name='uploaddatei_$Ident' />"; // accept=VF_zuldateitypen

    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
}

# of function

/**
 * Formular: Anzeige einer Bilddatei
 * - wenn die $FeldLaenge mit 0 angegeben ist (default) UND $FeldAttr='' ist - wird <input type='hidden' ...> erzeugt
 * - bei Feld Länge > 0 wird das <input> Feld mit den angegeben $FeldAttr erzeugt - Default für type= ist 'text'
 *
 * Der Feld Titel wird aus dem Array $Tabellen_Spalten_COMMENT mit dem Index $FeldName genommen
 * Der Daten Wert (value=) wird aus dem Array $neu mit dem Index $FeldName genommen
 * Eine allfällige Fehlermeldung wird aus dem Array $Err_msg mit dem Index $FeldName genommen
 *
 *
 * @param string $feldname
 *            Array index Name in $neu[] und $Tabellen_Spalten_COMMENT[]
 * @param string $FeldLaenge
 *            Bildbreite
 * @param string $InfoText
 *            Zusatz Informationen für das Feld
 * @param string $FeldAttr
 *            Attribut/Parameter für <input tag
 *
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $neu Eingelesene Daten Felder
 * @global array $Err_msg array mit Fehlermeldungen, $FeldName als Key
 * @global string $pict_path Path zum Speicherort der Bilder / Dokumente
 */
function Edit_Show_Pict($FeldName, $FeldLaenge = '100', $InfoText = '', $FeldAttr = '') # Attribut/Parameter
{
    global $Tabellen_Spalten_COMMENT, $neu, $Err_msg, $pict_path, $module, $ed_lcnt ;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_Show_Pict");

    if ($neu[$FeldName] != "") {

        if (isset($Tabellen_Spalten_COMMENT[$FeldName])) {
            echo "  <span class='info'>$Tabellen_Spalten_COMMENT[$FeldName] <b>$FeldName</b> ist geladen </span>";
        } else {
            echo "  <b>$neu[$FeldName]</b> ist geladen";
        }
        if (strpos($neu[$FeldName], ".pdf") || strpos($neu[$FeldName], ".php")) {
            echo "<a href='" . $pict_path . $neu[$FeldName] . "' target='PDF' >" . $neu[$FeldName] . "</a> &nbsp;  " . $Tabellen_Spalten_COMMENT[$FeldName];
        } else {
            echo "<img src='" . $pict_path . $neu[$FeldName] . "' alt='" . $Tabellen_Spalten_COMMENT[$FeldName] . "' width='$FeldLaenge' />";
        }
    }

    if (! empty($Err_msg[$FeldName])) {
        echo " <span class='error'>$Err_msg[$FeldName]</span>";
    }
    Edit_Feld_Zeile_Trailer($FeldName, $InfoText);
} # of function

/**
 * Formular: Defintion der Anzeige- Tablle
 *
 * @global int $Errors Fehler- Zähler, wenn>0 Anzeige, dass Fehler korrigiert werden müssen
 */
function Edit_Tabellen_Header($text = '')
{
    global $Errors, $module;

    flow_add($module, "Edit_Funcs.inc Funct: Edit_Tabellen_Header");

    echo "<div class='w3-container white'>";
    if ($Errors > 0) {
        echo "<span class='error'>" . "$Errors Fehlermeldung(en). Vervollständigen/Korrigieren Sie die Eingabefelder des Formulares." . "</span><br>";
    }#ecf2f9

    echo "<div class='w3-container' style='width:100%; background:#cfcfcf; padding-left:2%; padding-right:2%;'>";
    echo "<p class='w3-large'><b>$text</b></p>";
    echo "</div>";

    echo '</div>';
} // ende function_Edit_Tabellen_header

/**
 * Formular: Schließen der Anzeigen- Tabelle
 */
function Edit_Tabellen_Trailer()
{
    global $module;
    flow_add($module, "Edit_Funcs.inc Funct: Edit_Tabellen_Trailer");

    echo "</div'>";
    # echo "</fieldset>";
}

/**
 * Struktur- Definition für floating input
 *
 */
function define_float_line()
{

    Edit_Tabellen_Header($text);

    Edit_Separator_Zeile($text);

    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<div class='w3-third'>"; // Beginn der Anzeige Feld-Name
    /*
     * Datenausgabe Anzeige Feldbezeichnung
     */
    echo "  </div>";  // Ende Feldname
    echo "  <div class='w3-twothird'>"; // Beginn Inhalt- Spalte
    /*
     * Datenausgabe Eingabefeld
     */
    echo "</div>"; // Ende der Inhalt Spalte
    echo "</div>"; // Ende der Ausgabe- Einheit Feld

    Edit_Tabellen_Trailer();
}

function odd_even($cnt)
{

    if (($cnt % 2) == 0) {
        return true;
    } else {
        return false;
    }
} // ende funct odd_even

/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20241219
 */
