<?php
session_start();
# ------------------------------------------------------------------------------------------------------------------
#
#Inhaltsverzeichnis und Aufruf für Archivierte Berichte


$module    = 'VF_Arch';
$path2ROOT = "../../../../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

require $path2ROOT.'login/common/BA_HTML_Funcs.lib.php' ;  // Diverse Unterprogramme
 require $path2ROOT.'login/common/BA_Funcs.lib.php' ;  // Diverse Unterprogramme

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$logo = 'JA';
$header = "<link  href='".$path2ROOT."login/common/css/frame_pict.css' rel='stylesheet' type='text/css'>";
BA_HTML_header('Verein Feuerwehrhistoriker in NÖ) ',$header,'Formm','90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>


<p>
<a href="..\..\..\scripts\updata\Termine\2011\20110430_Oldtimer_Bad_Sauerbrunn.pdf" target=_new>
Am Samstag 30.04.2011 um ca. 9:00 Uhr begannen sich einige ältere Feuerwehrfahrzeuge beim Feuerwehrhaus
in Bad Sauerbrunn (Burgenland) zu Sammeln, darunter einige Raritäten.</a>
Bis 13:00 hatten die Besucher Gelegenheit, sich alle Fahrzeuge anzusehen. Die Dampfspritze der
Berufsfeuerwehr Wien war die ganze Zeit in Betrieb und zeigte allen Interessierten ihr können.<br/>
Um 13:00 Uhr setzten sich die meisten Fahrzeuge in Richtung Feuerwehrmuseum Frohsdorf in Marsch.<br/>
Nach einer ca. 25 km langen Fahrt erreichten die Fahrzeuge ihr Ziel. Die anwesenden Personen konnten sich
stärken und das Feuerwehrmuseum besichtigen.
<br/>
Nach ca. 1 Stunde setzte sich der Troß; wieder in Richtung Bad Sauerbrunn in Bewegung.
</p>
<p>Mit der linken Maustaste auf en Bild klicken, dann wird das Bild grösser angezeigt.
</p>

<div class='w3-table' style='margin=auto'>

<table>
<tbody>
<tr><td><strong>Teilnehmer: </strong></td></tr>                                      <img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_5926.WebP" alt="Fahnenweihe" align="right">
<tr><th>Marke/Type</th><th>Bauj.</th><th>Takt.Verw.</th><th>Aufbau</th><th>PS/kW</th><th>Hubr.</th><th>GesGew.</th><th>Organisation</th><th colspan="2">Bilder</th></tr>
<tr><td>Puch 700 C</td><td></td><td>Mil-Streife</td><td>--</td><td>/18</td><td></td><td></td><td></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4551.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4551.WebP" alt="Puch" width="250px"/></a></td></tr>
<tr><td>Puch Haflinger</td><td>1967</td><td>Polizei</td><td>Werksaufbau</td><td>28/ </td><td></td><td></td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4554.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4554.WebP" alt="Puch Haflinger"  width="250px"/></a></td></tr>
<tr><td>Puch 250SG</td><td></td><td>Feuerwehrstreife</td><td></td><td></td><td>250</td><td></td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4557.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4557.WebP" alt="Puch 250SG" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4589.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4589.WebP" alt="Puch 250SG" width="250px"/></a></td></tr>
<tr><td>Puch 250SG</td><td></td><td>Gendarmerie</td><td></td><td>16,6/</td><td>250</td><td></td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4557.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4557.WebP" alt="Puch 250SG" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4589.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4589.WebP" alt="Puch 250SG" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>1963</td><td>LLF</td><td>?</td><td>60/</td><td>2500</td><td>    </td><td>FF Bad Sauerbrunn</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4560.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4560.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4592.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4592.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Peugeot</td><td>  </td><td>ELF</td><td>?</td><td>/</td><td></td><td>    </td><td>Landesfeuerwehrschule</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4561.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4561.WebP" alt="Peugeot" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4593.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4593.WebP" alt="Peugeot" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>19xx</td><td>LLF</td><td>?</td><td>  /</td><td>    </td><td>    </td><td>FF Markt Felixdorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4562.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4562.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4594.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4594.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>1963</td><td>LF</td><td>?</td><td>60/</td><td>2500</td><td>3520</td><td>FF Laxenburg</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4563.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4563.WebP" alt="Opel Blitz" width="250px"></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4595.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4595.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>19xx</td><td>LF</td><td>?</td><td>60/</td><td>2500</td><td>3520</td><td>FF Weidling</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4564.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4564.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4596.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4596.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Land Rover</td><td>1974/td><td>Mehrzweckfahrzeug</td><td></td><td>66/90</td><td></td><td>2680</td><td>FF Marz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4565.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4565.WebP" alt="Land Rover" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4597.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4597.WebP" alt="Land Rover" width="250px"/></a></td></tr>
<tr><td>Ford Transit</td><td>1977</td><td>KLF</td><td></td><td>70/</td><td></td><td>2000</td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4566.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4566.WebP" alt="Ford Transit" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4598.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4598.WebP" alt="Ford Transit" width="250px"/></a></td></tr>
<tr><td>MG Sportwagen</td><td></td><td></td><td></td><td>/</td><td></td><td></td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4567.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4567.WebP" alt="MG" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4599.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4599.WebP" alt="MG" width="250px"/></a></td></tr>
<tr><td>Steyr</td><td></td><td>TLFA 2000</td><td>Rosenbauer</td><td>/</td><td></td><td></td><td>P&ouml;ttsching</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4568.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4568.WebP" alt="Steyr"  width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4600.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4600.WebP" alt="Steyr" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>19xx</td><td>TLF1000</td><td>?</td><td></td><td></td><td></td><td>FF Katzelsdorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4570.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4570.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4601.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4601.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>1960</td><td>LLF</td><td>?</td><td></td><td></td><td></td><td>FF Katzelsdorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4571.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4571.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4602.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4602.WebP" alt="Opel Blitz" width="250px"/></a></td></tr>
<tr><td>Karrenspritze</td><td>19xx</td><td>Pumpe</td><td>?</td><td></td><td></td><td></td><td>FF Neud&ouml;rfl an der Leitha</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4515 p.png"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4515.WebP" alt="Handpumpe" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4604.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4604.WebP" alt="Handpumpe" width="250px"/></a></td></tr>
<tr><td>Opel Blitz</td><td>19xx</td><td>TLF</td><td>?</td><td>60/</td><td>2500</td><td>3520</td><td>FF Kritzendorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4573.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4573.WebP" alt="Opel Blitz" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4605.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4605.WebP" alt="Opel Blitz" width="250px"></a></td></tr>
<tr><td>Puch Pinzgauer, 3 Achsig</td><td>1983</td><td>KLFA</td><td>Rosenbauer</td><td>60/87</td><td>2499</td><td>3450</td><td>Neustift</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4575.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4575.WebP" alt="Puch Pinzgauer" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4607.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4607.WebP" alt="Puch Pinzgauer" width="250px"/></a></td></tr>
<tr><td>Ford? </td><td>1983</td><td>TLFA</td><td>Rosenbauer</td><td>60/87</td><td>2499</td><td>3450</td><td>Sumetendorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4576.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4576.WebP" alt="" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4608.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4608.WebP" alt="" width="250px"/></a></td></tr>
<tr><td>Steyr 380</td><td>1967</td><td>TLF</td><td>Rosenbauer</td><td>88/118</td><td></td><td></td><td>FF Brunn am Gebirge</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4578.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4577.WebP" alt="Steyr 380" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4609.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4609.WebP" alt="Steyr 380" width="250px"/></a></td></tr>
<tr><td>Steyr 680</td><td>1965</td><td>TLF-2000</td><td>Rosenbauer</td><td>132/</td><td></td><td></td><td>FF Bad V&ouml;slau</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4579.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4579.WebP" alt="Steyr 680" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4610.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4610.WebP" alt="Steyr 680" width="250px"/></a></td></tr>
<tr><td>Steyr 680</td><td>1965</td><td>TLF-4000</td><td>Rosenbauer</td><td>110/</td><td></td><td></td><td>FF Neud&ouml;rfl an der Leitha</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4580.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4580.WebP" alt="Steyr 680" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4611.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4611.WebP" alt="Steyr 680" width="250px"/></a></td></tr>
<tr><td>Gr&auml;f &amp; Stift</td><td>1926</td><td>LF</td><td></td><td>45</td><td></td><td></td><td>FF Bad V&ouml;slau</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4581.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4581.WebP" alt="Gr&auml;f & Stift" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4612.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4612.WebP" alt="Gr&auml;f & Stift" width="250px"/></a></td></tr>
<tr><td>Traktor mit TSW</td><td>1946</td><td>TSW</td><td> </td><td></td><td></td><td></td><td>Neud&ouml;rfler Traktorfreunde</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4585.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4585.WebP" alt="TSW" width="250px"></a></td></tr>
<tr><td>Traktor mit Gasspritze</td><td>1946</td><td>Gasspritze</td><td> </td><td></td><td></td><td></td><td>Neud&ouml;rfler Traktorfreunde</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4587.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4587.WebP" alt="Gasspr." width="250px"/></a></td></tr>



<tr><th colspan="11">Diese Fahrzeuge waren nicht mit auf der Fahrt nach Frohsdorf:</th></tr>

<tr><td>Rettungskutsche</td><td>1977</td><td></td><td></td><td></td><td></td><td></td><td>RK M&ouml;dling</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2983.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2983.WebP" alt="Rettungskutsche" width="250px"/></a></td></tr>
<tr><td>Steyr 380</td><td>1954</td><td>TLF 3000</td><td></td><td></td><td></td><td></td><td>FF Mattersburg</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4532.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4532.WebP" alt="Steyr 380" width="250px"/></a></td></tr>
<tr><td>Magirus Dreh- und Schiebeleiter</td><td>1909</td><td></td><td></td><td></td><td></td><td></td><td>FW Museum Pottendorf</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4534.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4534.WebP" alt="Magirus Dreh- und Schiebeleiter" width="250px"/></a></td></tr>
<tr><td>VW Bus T1</td><td>1958</td><td>RTW</td><td></td><td>30/</td><td></td><td></td><td>Privatbesitz</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4543.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4543.WebP" alt="VW Bus T1" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4544.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4544.WebP" alt="VW Bus T1" width="250px"/></a></td></tr>
<tr><td>Dampfspritze Kernreuter</td><td></td><td>Pumpe</td><td></td><td></td><td></td><td></td><td>BF Wien</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2964.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2964.WebP" alt="Dampfspritze Kernreuter" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4536.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4536.WebP" alt="Dampfspritze Kernreuter" width="250px"/></a></td></tr>
<tr><td>Karrenspritze</td><td>1894</td><td>Pumpe</td><td>Knaust</td><td></td><td></td><td></td><td>FF Lichtenw&ouml;rth</td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4641.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4641.WebP" alt="Karrenspritze Knaust" width="250px"/></a></td><td><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4664.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4664.WebP" alt="Karrenspritze Knaust" width="250px"/></a></td></tr>

<tr><td colspan="11"></td></tr>
<tr><td colspan="11">Bei der Feuerwehr in Bad Sauerbrunn herrschte am Samstag 30.04.2011 einige (freudige) Unruhe. Oldtimer waren aufgereiht.
   Die Feldk&uuml;che des Roten Kreuzes erzeugte mit ihrer Gulaschkanone Gulasch, das laut den Personen, die es zu sich namen, sehr gut war.
   Die Verpflegung f&uuml;r nicht-Gulaschianer konnte sich ebenfalls schmecken lassen. Vor der Abfahrt nach Frohsdorf zum Feuerwehrmuseum waren
   noch nicht sehr viele Besucher anwesen.
   </td></tr>
<tr><th colspan="6"><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2980.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2980.WebP" alt="B.Sauerbrunn" width="250px"/></a></th><th colspan="5"><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2992.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-DSCN2992.WebP" alt="B.Sauerbrunn" width="250px"/></a></th></tr>

<tr><td colspan="11">In Frohsdorf beim Feuerwehrmuseum wurde der Parkplatz knapp, daf&uuml;r war der Empfang sehr herzlich.
  Die Teilnehmer der Fahrt konnten sich bei einem Imbiss st&auml;rken, beziehungsweise das Feuerwehrmuseum Frohsdorf besichtigen.
  Nach einiger Zeit mussten wir nach Bad Sauerbrunn zur&uuml;ckfahren.
</td></tr>
<tr><th colspan="6"><a href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4625.WebP"><img src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4625.WebP" alt="" width="250px"/></a></th><th colspan="5"><a
					href="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-IMG_4542.WebP"><img
						src="../../../../login/AOrd_Verz/124/09/06/20110430/124-20110430-img_4544.WebP"
						alt="VW Bus T1" width="250px" /></a></th></tr>


</tbody>
     </table>

     </div>

<?php 
 BA_HTML_trailer();
 ?>
