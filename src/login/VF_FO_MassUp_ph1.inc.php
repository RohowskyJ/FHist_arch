<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Abfrage Aufnahmedatum
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */

$pfad = $beschreibg = "";

echo "<div class='white'></div>";

Edit_Tabellen_Header("Zieldaten zum hochladen erfragen");

echo "<b>Daten für den Eigentümer / Urheber <i>".$_SESSION[$module]['Fo']['URHEBER']['fm_urheber']."</i> werden bearbeitet (kopiert und in Tabellen eingelesen)</b><br>";

$checked = "";
if (isset($_SESSION[$module]['Fo']['URHEBER']['urh_abk'])) {
    $u_cnt= count($_SESSION[$module]['Fo']['URHEBER']['urh_abk']);
    foreach($_SESSION[$module]['Fo']['URHEBER']['urh_abk'] as $key => $value) {
        if ($u_cnt == 1) {
            $checked = "checked";
        }
        echo "<input type='radio' id='urh_abk' name='urh_abk'  value='$key' $checked> <label for='urh_abk'>$value</label><br>";
        
    } 
} else {
    echo "<input type='hidden' id='urh_abk' name='urh_abk'  value='".$_SESSION[$module]['Fo']['URHEBER']['fm_urh_kurzz']."'> ";
    echo "<p><b>Urheber: ".$_SESSION[$module]['Fo']['URHEBER']['fm_urheber']."</b></p>";
}


Edit_Separator_Zeile('Ziel- Pfad der Bilder');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='basPfad'>Basispfad (wenn nicht Aufnahme-Datum) </label> ";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird      ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='basPfad' name='basis_pfad'   />";
echo "</div>";
echo "</div>";

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='zusPfad'>Zusatz- Pfad (Unterteilung)</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird      ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='zusPfad' name='zus_pfad'   />";
echo "</div>";
echo "</div>";

echo "<div class='w3-row'style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='aufnDat'>Aufnahme- Datum (Haupt- Pfad)</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird  ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='aufnDat' name='aufn_dat'   />  YYYYmmDD Format oder Jahreszahl"; 
echo "</div>";
echo "</div>";

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third  ' >";
echo "<label for='aufnSuff'>Datum Suffix, wenn am selben Datum 2. Ereignis </label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='aufnSuff' name='aufn_suff'   />";
echo "</div>";
echo "</div>";

echo "<div class='w3-row'style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='beschrBg'>Beschreibung</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
echo "<textarea type='text' name='beschreibg' id='beschrBg' rows='5' cols='50' stype='diplay:none; aria-hidden='true'>$beschreibg</textarea>";
echo "</div>";
echo "</div>";

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='urhEinfg'>Urheber ins Bild einfügen</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
echo "<input type='radio' name='urheinfueg' id='urhEinfg' value='J' checked ><label for='urheinfueg_J'>Ja</label><br>";
echo "<input type='radio' name='urheinfueg' id='urhEinfg' value='N'   ><label for='urheinfueg_J'>Nein</label><br>";
echo "</div>";
echo "</div>";

Edit_Tabellen_Trailer();

echo "<br><p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='2' class=green>Weiter zur Foto- Auswahl</button></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_MassUp_ph1.inc beendet</pre>";
}
?>