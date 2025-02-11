<?php

/**
 * PSA Beschreibug Quellen
 *
 * @author Josef Rohowsky - neu 2018
 */
session_start();

const Module_Name = 'PSA';
$module = Module_Name;
# const Tabellen_Name = 'fh_dokumente';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

initial_debug();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Information zu Auszeichnungen, Ärmelabzeichen, Wappen ', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab

Edit_Separator_Zeile('Abkürzungen');

echo "</div>";
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
echo "ÖBFV Österreichischer Bundes-Feuerwehrverband, NÖLFV Niederösterreichischer Landesfeuerwehrverband, FF Freiwillige Feuerwehr, BtF Betriebsfeuerwehr, FM Feuerwehrmuseum.
       ";
echo "</div>";

Edit_Separator_Zeile('Quellen');

echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
echo "Unterlagen und Ausarbeitungen von Foist Johann, Giczi Leopold, Schneider Hans (ÖBFV,NÖ LFV), Hollauf Siegfried (NÖ LFV), Schmidt Günter Erik (Orden und Ehrenzeichen Österreichs, 1918 - 1938, 1945 - 1999)";
echo "</div>";


Edit_Separator_Zeile('Objekte zum Fotografieren oder Bilder von:');

echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
echo "Alle namentlich erwähnten Institutionen und Personen (außer es steht in Klammer der Staat) sind aus Österreich.<br/>";
echo "ÖBFV, FM Baden Stadt, FM Gumpoldskirchen, FM Fischamend, FM Frohsdorf, FF/Gde Kaltenleutgeben, FM Laxenburg (mit Sammlung Foist), FF Maria Lanzendorf, FM Möllersdorf, FF Purkersdorf, 
        Braunstein Franz, Binder Hubert, Dachauer Johann, Florian Hell, 
        Fastl Christian, Gutmann Heinrich, Hollauf Siegfried (NÖ LFV), Iszovitz Stefan, Iszovitz Werner, Klaedtke Bernd (D), Krenn Heinrich, Laager Brigitte, Lef&egrave;vre Horst (D), 
        Maresch Friedrich, Mischinger Manfred, Mislivececk Manfred, Poloma Peter,  
        Pink Wolfgang, Rath Roman, Reinholz Heiko (D), Rohowsky Josef, Schanda Herbert (ELDBSTV), Terscinar Reinhard, FM Vorarlberg, Wandl Rudi, FM Wien, Weiß Alois, Wörner Frank (D), Zehetner Karl.
       ";
echo "</div>";


Edit_Separator_Zeile('Literaturhinweise');
;
echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
echo "finden sich im Buch 'Niederösterreichische Feuerwehrstudien 8', 
        Autor Christian Fastl</u>, 'Bibliographie zur Niederösterreichischen Feuerwehrgeschichte, Tulln 2011'.
        Seite 16 - 18, Auszeichnungen, (Leistungs-) Abzeichen, Abzeichen, Ehrungen.";
echo "</div>";

BA_HTML_trailer();
?>
