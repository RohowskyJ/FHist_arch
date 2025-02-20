<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Abfrage Aufnahmedatum
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */
#var_dump($_SESSION[$module]['URHEBER']);

$basis_pfad = $pfad = $beschreibg = "";

echo "<div class='white'></div>";

Edit_Tabellen_Header("Zieldaten zum hochladen erfragen");

$eignr = $_SESSION[$module]['URHEBER']['ei_id'];
echo "Daten für den Eigentümer / Urheber: <i><b>".$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fotograf']."</b></i> werden bearbeitet (kopiert und in Tabellen eingelesen)<br>";

echo "Urheber-Kurzzeichen : <b>".$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['kurzz']."</b><br>";
echo " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Mediatyp : <b>".VF_Foto_Video[$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ']]."</b><br>";
echo " &nbsp; &nbsp;&nbsp;Basis- Verzeichnis : <b>".$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['verz']."</b><br>";

if ($_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['verz'] != "" ) {
    $basis_pfad  = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['verz'];
}
Edit_Separator_Zeile('Ziel- Pfad der Bilder');
 
echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='basPfad'>Basispfad (wenn nicht Aufnahme-Datum) </label> ";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird      ' >"; // Beginn Inhalt- Spalte
echo "<input type='text' id='basPfad' name='basis_pfad' value=$basis_pfad  />";
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