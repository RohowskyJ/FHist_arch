<?php
session_start();

$module    = 'VF_Beschr';
/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

require $path2ROOT.'login/common/BA_HTML_Funcs.lib.php' ;  // Diverse Unterprogramme
require $path2ROOT.'login/common/BA_Funcs.lib.php' ;  // Diverse Unterprogramme

$flow_list = False;

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$header = "";
BA_HTML_header('Vorstand',$header,'Form','75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
echo "<div  class='w3-container'>";

echo "<framework>";

echo "<table>";

echo "<tr><th colspan='3'>Obmann</th></tr>";
echo "<tr><th>Lukas Brodtrager</th><td><b>Jahrgang 1994 - Angestellter beim ÖGB/Produktionsgewerkschaft (Kollektivvertragsbüro Agrar)</b>
<p>
Feuerwehr und Geschichte - zwei unzertrennbare Begriffe. Seit meinem 16. Lebensjahr engagiere ich mich für die Feuerwehrgeschichte, darüber hinaus für die politische-, Eisenbahn-, und Industriegeschichte in Österreich.
Neben meinen Tätigkeiten auf Abschnitts- und Bezirksebene bin ich seit 2021 auch Mitglied des Arbeitsausschusses für Feuerwehrgeschichte im NÖ Landesfeuerwehrverband. und fungiere hier als Bindeglied zwischen Verein und LFV. Hauptsächlich beschäftige ich mich mit der Aufarbeitung geschichtlicher Ereignisse sowie der Recherche aufgelassener Fabriks- und Betriebsfeuerwehren.

<p></td><td>
<a href='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040804.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040804.jpg' alt='Bild des Obmannes' height='200' ></a>
</td></tr>";

echo "<tr><th colspan='3'>1. Obmann Stellvertreter</th></tr>";
echo "<tr><th>Michael Sack</th><td>
<b></b><p></p>
</td><td>
<a href='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040806.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040806.jpg' alt='Bild des 1. Obmann Stellvertreters'  height='200' ></a>
</td></tr>";

echo "<tr><th colspan='3'>2. Obmann Stellvertreter</th></tr>";
echo "<tr><th>Johann Dachauer</th><td>
<b></b><p></p>
</td><td>
<a href='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040810.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040810.jpg' alt='Bild des 2. Obmann Stellvertreters'  height='200' >
</td></tr>";

echo "<tr><th colspan='3'>Kassier</th></tr>";
echo "<tr><th>Wolfgang Riegler</th><td>
<b></b><p></p>
</td><td>
<a href='../../ogin/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040807.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040807.jpg' alt='Bild des Kassiers'  height='200' ></a>
</td></tr>";

echo "<tr><th colspan='3'>Schiftführer</th></tr>";
echo "<tr><th>Josef Rohowsky</th><td>
<b>Jahrgag 1948 - Pensionist</b>
<p>Habe bei der Gründung des Vereines den Auftrag erhalten, eine Plattform zur Unterstützung der Mitglieder zur Dokumentation ihrer Schätze zu schaffen (Inventar, Archiv, Beschreibungen, Kataloge, Berichte, ...).</p>
<p> Die Plattform ist aktiv (es wird noch Erweiterungen geben) - wird aber von den Mitgliedern leider nicht zur Dokumentation Ihrer Bestände benutzt.</p>
<p>Die Dokumentation ist unter anderem auch wichtig, bei abhandenkommen von Gegenständen den Nachweis des Besitzes erbringen zu können (Zumindest als Eingagsbuch - bei Flohmarkt käufen - der einzige Nachweis!).<p>
<p>Wenn keine bleibende Dokumentation erstellt wird, geht das Wissen (das viele Spezialisten ja haben), leider mit ihnen verloren (zum grossen Teil Unwiederbringlich).</p>
 </td><td>
<a href='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040808.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040808.jpg' alt='Bild des Schriftführers'  height='200' ></a>
</td></tr>";

echo "<tr><th colspan='3'>Schriftführer Stellvertreterin</th></tr>";

echo "<tr><th>Daniela Jöchlinger</th><td>
<b></b><p>Vertritt bereits den Schriftführer sehr effizient beim Protokollieren.(JR)</p>
</td><td>
<a href='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040805.jpg' target='_blank'>
<img src='../../login/AOrd_Verz/158/09/06/20240505/VK-20240505-P5040805.jpg' alt='Bild der Schriftführer- Stellvertreterin'  height='200' ></a>
</td></tr>";
/*
echo "<tr><th colspan='3'>Obmann</th></tr>";
echo "<tr><th></th><td></td><td></td></tr>";

echo "";
*/
echo "</table>";

?>
<center>
<br><br>
<a href="index.php">
    Zur Startseite</a>
</center>
<?php

echo "<framework>";
echo "</div>";
BA_HTML_trailer();
?>

