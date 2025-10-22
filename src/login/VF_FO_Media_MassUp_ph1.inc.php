<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Abfrage Aufnahmedatum
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */

$basis_pfad = $pfad = $beschreibg = "";

Edit_Tabellen_Header("Zieldaten zum hochladen erfragen");

$urh_nr = $eignr = $_SESSION['Eigner']['eig_eigner'];
echo "Daten für den Eigentümer / Urheber: <i><b>".$_SESSION['Eigner']['eig_urhname']."</b></i> werden bearbeitet (kopiert und in Tabellen eingelesen)<br>";

echo "Urheber- (Eigentümer-) Nummer : <b>".$urh_nr."</b><br>";
echo " erlaubte Medien : <b>Audio (mp3), Foto (gif, ico, jpg, jpeg, png, tiff), Video (mp4)</b><br>";

Edit_Separator_Zeile('Aufnahme- Datum (Ziel- Pfad der Bilder erweitern mit Anhang möglich)');

echo "<div class='w3-row'style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='aufnDat'>Aufnahme- Datum (Haupt- Pfad)</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird  ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='aufnDat' name='aufn_dat'   requiredmmDD Format oder Jahreszahl";
echo "</div>";
echo "</div>";


echo "<div class='w3-row'style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='beschrBg'>Beschreibung</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
echo "<textarea type='text' name='beschreibg' id='beschrbg' rows='5' cols='50' maxlength='1024' stype='diplay:none; aria-hidden='true'>$beschreibg</textarea>";
echo "</div>";
echo "</div>";
/*
echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='urhEinfg'>Urheber ins Bild einfügen</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
echo "<input type='radio' name='urheinfueg' id='urhEinfg' value='J' checked ><label for='urheinfueg_J'>Ja</label><br>";
echo "<input type='radio' name='urheinfueg' id='urhEinfg' value='N'         ><label for='urheinfueg_J'>Nein</label><br>";
echo "</div>";
echo "</div>";
*/
Edit_Tabellen_Trailer();

echo "<br><p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='2' class=green>Weiter zur Foto- Auswahl</button></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_Media_MassUp_ph1.inc beendet</pre>";
}
