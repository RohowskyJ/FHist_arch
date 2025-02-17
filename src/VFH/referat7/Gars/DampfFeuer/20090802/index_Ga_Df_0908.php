<?php
session_start();
# ------------------------------------------------------------------------------------------------------------------
#
#Inhaltsverzeichnis und Aufruf für Archivierte Berichte


$module    = 'VF_Arch';
$path2ROOT = "../../../../../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

 require $path2ROOT.'login/common/BA_Funcs.lib.php' ;  // Diverse Unterprogramme

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$logo = 'JA';
$header = "<link  href='".$path2ROOT."login/common/css/frame_pict.css' rel='stylesheet' type='text/css'>";
HTML_header('Verein Feuerwehrhistoriker in NÖ) ','Achivierte Berichte',$header,'Form','75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>

<body class="w3-container ">
<div class="w3-content ">  <!-- max-width:45em; margin:5em;--->

<p>
<img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9442.WebP" alt="Dampflok" align="right" width="500px">
Am 2.8.2009, so gegen 8 Uhr, versammelten sich einige ältere Feuerwehrfahrzeuge am Bahnhofsplatz in Gmünd.
Gegenüber, am Schmalspurbahnhof, standen zwei Züge, noch ohne Lokomotive, einsam am Bahnsteig bereit.
So gegen 9 Uhr waren schon einige Personen versammelt, die sich die Feuerwehroldtimer ansahen, und dann
zum bereitstehenden Zug gingen. Knapp vor 9 Uhr kam dann eine Dampflok angefahren, und für den zweiten Zug
eine Diesellok. Nach dem Eintreffen des Sonderzuges aus Wien konnte es endlich losgehen.
Die Teilnehmer setzten sich in Bewegung - auf der Schiene als auch auf der Strasse.
</p>
<legend>Die Teilnehmer: <font size="-1">(FF Gmünd ÖBB)</font></legend><br>

<div class='w3-table' style='margin=auto'>

<table>
<tbody>
<tr><td><strong>Teilnehmer: </strong></td></tr>
<tr><th>Marke/Type</th><th>Bauj.</th><th>Takt.Verw.</th><th>Aufbau</th><th>PS/kW</th><th>Hubr.</th><th>EigGew.</th><th>Organisation</th><th>&nbsp;</th></tr>
<tr><td>Steyr 680M3 Allrad</td><td>1969</td><td>TLF-A</td><td>Rosenbauer</td><td>150 /112</td><td>5975</td><td>8400</td><td>FF Stadt Gm&uuml;nd</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9463.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9463.WebP" alt="Mercedes" width="250px"/></a></td></tr>
<tr><td>Praga R1/II</td><td>1914</td><td>Beleuchtungsfahrzeug</td><td>Werksaufbau</td><td>32</td><td>3824</td><td>2200</td><td>Privatbesitz</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9465.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9465.WebP" alt="Praga" width="250px"/></a></td></tr>
<tr><td>Mercedes L1500S</td><td>1941</td><td>LLG</td><td>Rosenbauer</td><td>60/</td><td>2594</td><td>3450</td><td>Feuerwehrmuseum Gars</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9466.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9466.WebP" alt="Mercedes" width="250px"/></a></td></tr>
<tr><td>Opel Blitz 1,7t</td><td>1957</td><td>LF</td><td>?</td><td>54/</td><td>2500</td><td>1700</td><td>Privatbesitz</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9467.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9467.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Mercedes 1113</td><td>1968</td><td>TLF 2400</td><td>Rosenbauer</td><td>150</td><td>5675</td><td>6250</td><td>FF Gars - Feuerwehrmuseum Gars</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9468.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9468.WebP" alt="Mercedes" width="250px"/></a></td></tr>
<tr><td>Steyr 480 f</td><td>1967</td><td>R&uuml;st</td><td>5+1, Kasten, Fa. Langer, Wr. Neudorf</td><td>95/70</td><td>5320</td><td>5900</td><td>FF Brand</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9470.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9470.WebP" alt="Praga" width="250px"/></a></td></tr>
<tr><td>VW-Bus mit TLF-A</td><td></td><td></td><td></td><td></td><td></td><td></td><td>Feuerwehr der Stadt Gm&uuml;nd</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9472.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9472.WebP" alt="Praga" width="250px"/></a></td></tr>
<tr><td>RF UNIMOG 30</td><td>1961</td><td>RF - &Ouml;L</td><td>Eigenaufbau</td><td>30/</td><td></td><td>3500</td><td>Feuerwehr der Stadt Gm&uuml;nd</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9474.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9474.WebP" alt="Praga" width="250px"/></a></td></tr>
<tr><td>Puch 250 SG</td><td>1955</td><td>FF-Krad</td><td></td><td>12/</td><td>250</td><td>154</td><td>FF-Sigmundsherberg</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9475.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9475.WebP" alt="Krad" width="250px"/></a></td></tr>
<tr><td>DKW F800/3</td><td>1956</td><td>KLF</td><td>Serie</td><td>38/</td><td>896</td><td>1190</td><td>FF Buchberg</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9476.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9476.WebP" alt="DKW" width="250px"/></a></td></tr>
<tr><td>Opel Blitz 3,6-36-30</td><td>1942</td><td>TLF-1700</td><td>Rosenbauer</td><td>75/</td><td>3626</td><td>3450</td><td>Feuerwehrmuseum Gars</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9477.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9477.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Tatra 138A V8</td><td>1968</td><td>Kran</td><td></td><td>141/</td><td></td><td></td><td>FF Gro&szlig; Siegharts</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9478.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9478.WebP" alt="Praga" width="250px"/></a></td></tr>
<tr><td>Steyr 680M3 Allrad</td><td>1969</td><td>TLF-A</td><td>Rosenbauer</td><td>150 /112</td><td>5975</td><td>8400</td><td>private Oldtimersammlung</td><td><a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9479.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9479.WebP" alt="Steyr" width="250px"/></a></td></tr>


</tbody>
     </table>

     </div>
<p>
<a href="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9450.WebP"><img src="../../../../../login/AOrd_Verz/124/09/06/20090802/JR-20090802-img_9450.WebP" alt="Dampf- Betankung"  align="left" width="250px"/></a>
Im Zug war es angenehm, die Zeit hatte keine Eile, mit ein paar Aufenthalten, wenn Strasse und Schiene beieinander lagen, mit Parallelfahrt, beziehungsweise gegenseitigem &uuml;berholen, ging es nach Litschau.
<br>
Nach dem aussteigen in Litschau wurde gezeigt, wie man die Lokomotive mit der Dampfspritze aus Gainfarn (Knaust Dampffeuerspritze, 3 Zylinder, 1911, "Kathi") mit
Wasser betanken kann.
<br>
Nach der Parade der Fahrzeuge am Hauptplatz ging es dann wieder nach Gm&uuml;nd zur&uuml;ck, wo wir entspannt ankamen.
</p>


</div>
<?php 
 HTML_trailer();
 ?>
