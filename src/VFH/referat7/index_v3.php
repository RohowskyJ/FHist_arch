<?php

# ------------------------------------------------------------------------------------------------------------------
#
#Inhaltsverzeichnis und Aufruf für Archivierte Berichte


$module    = 'VF_Arch';
$path2VF = "../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

 require $path2VF.'login/common/VF_Funcs_v3.inc' ;  // Diverse Unterprogramme

VF_initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$VF_logo = 'JA';
VF_HTML_header('Verein Feuerwehrhistoriker in NÖ) ','Achivierte Berichte','','Form','75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>

<body class="w3-container ">
<div class="w3-content ">  <!-- max-width:45em; margin:5em;--->
<fieldset>


     <div class='w3-table' style='margin=auto'>
     <table>
<h1>Berichte über Veranstaltungen und Veröffentlichungen</h1>


<table summary="Einstiegsseite" border="1" width="100%">

<tbody>
<tr>
   <th>
      <font size="+2">
         Grundlagen - Gesetze
      </font>
    </th>
 </tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="Pflichtabgabe/Pflichtablieferung_BGBLA_2009_II_271.pdf" target='PflA'>Pflichtablieferung von Druckwerken - Gesetzestext</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Pflichtabgabe/5-2009_Information_Pflichtablieferung.pdf" target='PflA'>Pflichtablieferung von Druckwerken - Kurzinformation</a>  <br>
      </font>
     </th>
</tr>

<tr><th>&nbsp;</th></tr>

<tr>
   <th>
      <font size="+2">
         LFKDO N&Ouml; - Ausbildung SG Feuerwehrgeschichte
      </font>
    </th>
 </tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="LFKDO/20100116_Ausb_2010.pdf"  target='Ausb'>Ausbildung SG Feuerwehrgeschichte 2010</a>  </br>
      </font>
     </th>
</tr>

<tr><th>&nbsp;</th></tr>

<tr>
   <th>
      <font size="+2">
        BFKDO Krems - Internetseite, zur Feuerwehrgeschichte im Bezirk Krems
      </font>
    </th>
 </tr>

<tr>
    <th>
      <font size="+1"> </br>
        <a href="http://www.feuerwehr-krems.at"  target='Krems'>Feuerwehr Krems, siehe Feuerwehrgeschichte</a>  <br>
          (Im Menuebereich hinunterscrollen, -> Sitemap -> Bezirk -> Geschichte)  <br/>
      </font>
     </th>
</tr>

<tr>
   <th>
      <font size="+2">
         BFKDO Mödling
      </font>
    </th>
 </tr>

<tr>
    <th>
      <font size="+1"> </br>
        <a href="LFKDO/BFKDO_Moedling/4-2009_Aufbau_Sammlung_Bezirk.pdf" target='Sammlg'>Aufbau einer Jahresbericht- und Festschriftensammlung im Bezirk</a>  </br>
      </font>
     </th>
</tr>

<tr><th>&nbsp;</th></tr>

<tr>
   <th>
      <font size="+2">
         Veranstaltungen
      </font>
    </th>
 </tr>
<tr>
    <th>
      <font size="+1"> </br>
        <a href="VFHNOE/Genv/20060903/index_v3.php" target='GV2006'>3.9.2006 - Generalversammlung 2006 des Vereines - Gars/Kamp</a>  <Ibr>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Gars/FireTrophy/20060902/index_v3.php" target='firetr'>2. - 3. 9.2006 - 2. Waldviertler FIRE-Trophy 2006 - Gars/Kamp</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="LFS_TU/20060915/index_v3.php" target='Schaur'>15.9.2006 - Eröffnung LFS neu Tulln</a><br>
         16.9.2006 - LFS neu Tulln - Tag der offenen Tür
</a> <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="CTIF/20060927/index_v3.php" target='ctif'>27. - 29.9.2006 -  CTIF Arbeitsgemeinschaft für Feuerwehr und Brandschutzgeschichte<br>
         in Jonsdorf
</a> <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="VFHNOE/BildgReise/20061021/index_v3.php" target='StdR'>21.10.2006 - Studienreise 2006</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2007  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="StPoelten/20070512/index_v3.php" target='StP'>12.5.2007 - 140 Jahre FF St. P&ouml;lten</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="StFlorian/20070512/index_v3.php" target='StFlo'>12.5.2007 - 1. Museumsfest St. Florian 2007</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Spillern/20070615/index_v3.php" target='Spill'>15.6.2007 - 135 Jahre FF Spillern mit Fahnenweihe</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="genv_hist_2006/index.html" target='GV'>Generalversammlung 2006 des Vereines - Gars/Kamp</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2008  <br>
      </font>
     </th>
</tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="LFS_TU/20080405/index_v3.php" target='LFS'>05.04.2008 - 1. Achivar- Lehrgang an der Landesfeuerwehrschule in Tulln</a>  <br>
      </font>
     </th>
</tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="Perchtoldsdorf/20080503/index_v3.php" target='PDorf'>03.-04.05.2008 - Florianitag in Perchtoldsdorf</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2009  <br>
      </font>
     </th>
</tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="CTIF/20090414/index_v3.php" target='Tausch'>14.4.2009 - Jaromir Tausch verstorben</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="StFlorian/20090523/index_v3.php" target='StFlo'>23.5.2009 - 2. Museumsfest St. Florian 2009 - 25 Jahre Feuerwehrmuseum</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Gars/FireTrophy/20090904/index_v3.php" target='Ftro'>4-6.9.2009 - Firetrophy 2009, Teilnehmerliste</a>  <br>
        <a href="Gars/FireTrophy/20090904/Ber_Annerl_v3.php" target='Ftro'>4-6.9.2009 - Firetrophy 2009, Bericht Günter Annerl</a>  <br>
      </font>
     </th>
</tr> 

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Gars/DampfFeuer/20090802/index_v3.php" target='DaFeu'>2.8.2009 - Dampf und Feuer - Parallelfahrt Dampfzug- Feuerwehroldtimer</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="StPoelten/20090919/index_v3.php" target='LFV'>19.9.2009 - 140 Jahre Landesfeuerwehrverband NÖ, in St. Pölten</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2010  <br>
      </font>
     </th>
</tr>
<!-- 
<tr>
    <th>
      <font size="+1"> <br>
        <a href="vfh_20101023_bldgreise/index.html" target='Brei'>23.10.2010-24.10.2010 - Bildungsreise</a>  <br>
      </font>
     </th>
</tr>
 -->
<tr>
    <th>
      <font size="+1"> <br>
        <a href="CTIF/20100929/index_v3.php" target='CTIF'>29.9.2010-02.10.2010 - 18. Tagung der Internat. Arbeitsgemeinschaft für Feuerwehr- und Brandschutzgeschichte in Varazdin</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="VFHNOE/Histtag/20100925/index_v3.php" target='HistT'>25.9.2010 - Historikertag des Vereines der Feuerwehrhistoriker in NÖ</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Purgstall/20100918/index_v3.php" target='Purg'>18.9.2010 - Feier zum 140 jährigen Bestehen der FF Purgstall</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Purgstall/20100423/index_v3.php" target='Purgst'>23.4.2010 - Ausstellung in Purgstaller Feuerwehrmuseum</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="fflustenau_oldie/index.html" target='Lust'>Teile für Restaurierung ben&ouml;tigt - AF Bj. 1926 FF Lustenau</a>  <br>
        <a href="http://www.feuerwehr.lustenau.at/fiigo/"  target='figoA'>Restaurationsszenarien</a>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="Marcali_HU/20100814/index_v3.php" target='Marca'>Ein Bewerb der etwas anderen Art - BF Marcali (HU)- altes Gerät im Einsatz</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2011  <br>
      </font>
     </th>
</tr>

  <tr>
    <th>
      <font size="+1"> <br>
        <a href="BadSauerbrunn/20110430/index_v3.php" target='Oldies'>1. Burgenländisches Oldtimertreffen Bad. Sauerbrunn 30.04.2011</a>  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
     <font size="+1"> <br>
     <!--
        <a href="2011_eimer_katschutz/eimer_katschutz.html" target='EBra'>Ausstellung "Vom Eimer zum Katastrophenschutz" im Stift Geras, 2011 - 2012</a>  <br>
        <a href="2011_historikerstammtisch/hist_stammtisch.html" target='EBra'>Historikerstammtisch im Stift Geras, 2011</a>  <br>
        -->
        <a href="VFHNOE/Histtag/20110917/index_v3.php" target='EBra'>Historikertag im Stift Geras, 2011</a>  <br>
     </font>
    </th>
</tr>

<tr>
    <th>
      <font size="+1"> <br>
        2012  <br>
      </font>
     </th>
</tr>

<tr>
    <th>
     <font size="+1"> <br>
        <a href="Spillern/20120517_140J/index_v3.php" target='Spill'">Umzug historischer Fahrzeuge zum 140 jährigen Bestand der FF Spillern</a>  <br>
     </font>
    </th>
</tr>






</tbody></table>

<table summary="Einstiegsseite" border="1" width="100%">

<tbody>
<tr>
   <th>
      <font size="+2">
         Veröffentlichungen
      </font>
    </th>
 </tr>
<tr>
    <th>
      <font size="+1"> <br>
        <a href="buecher/kaier/index.html" target='Helm'>Helmbuch von Arnold Kaier</a>  <br>
      </font>
     </th>

</tr>

<tr>
    <th>
      <font size="+1"> <br>
        <a href="buecher/rux/index.html" target='TS'>Die Tragkraftspritze von Günter Rux</a>  <br>
      </font>
     </th>

</tr>



     </table>

     </div>



</form>
</fieldset>
</div>
<?php 
 VF_HTML_trailer();
 ?>
