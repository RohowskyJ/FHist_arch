<?php

/**
 * PSA Menu, und diverse informationen
 *
 * @author Josef Rohowsky - neu 2018
 */
session_start(); # die SESSION aktivieren  

const Module_Name   = 'PSA';
$module             = Module_Name;
# const Tabellen_Name = 'fh_dokumente'; 

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT          = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug(); 

VF_chk_valid();

VF_set_module_p();

VF_Count_add();

# ===========================================================================================================
#                                            Haeder ausgeben
# ===========================================================================================================

  if ($_SESSION[$module]['p_zug'] == "Q") {
      $_SESSION[$module]['all_upd'] = True;
  }

  $sk= $_SESSION['VF_Prim']['SK'];
  
  BA_HTML_header('Auszeichnungen, Ärmelabzeichen, Wappen, Uniformen ','','Form','70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 

  Edit_Separator_Zeile('Ärmelabzeichen, Wappen'); 
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<font size='+1'>Dienstanweisungen NÖLFKDO für Ärmelabzeichen (zur Anzeige den entsprechenden Link anklicken)</font>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "1951 Rundschreiben, Vorläufer der DA 1.5.3";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "DA 1.5.3 01.11.2005 ";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='AOrd_Verz/PSA/DA/20061124_DA-1-5-3.php' target='DA_2006'>DA 1.5.3 24.11.2006</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "DA 1.5.3 01.08.2012";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='AOrd_Verz/PSA/DA/20140501_DA-1-5-3.php' target='DA_2014'>DA 1.5.3 01.05.2014</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='AOrd_Verz/PSA/DA/201701_da-3-6-2-dienstbekleidung.pdf' target='DA_2017'>DA 3.6.2 01.2017</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='AOrd_Verz/PSA/DA/202212_da-3-6-2-ergaenzung.pdf' target='DA_2022'>DA 3.6.2 Erweiterung 16.12.2022</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='http://de.wikipedia.org/wiki/Heraldik' target='Herald'>Heraldik </a>(Heroldskunst, Wappenwesen, Wappenkunst, Wappenkunde und Wappenrecht)";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='http://de.wikipedia.org/wiki/Blasonierung' target='Blasionierung'>Blasonierung </a>ist in der Heraldik die fachsprachliche Beschreibung eines Wappens.";
  echo "</div>";

  Edit_Separator_Zeile('Auszeichnungen, Ehrenzeichen, Leistungsabzeichen, ...'); 
 
  Edit_Separator_Zeile('Quellen, Abkürzungen, Hinweise, ...');
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_Info_Ausz_Quel_abk.php' target='Quel'>Quelle, Abkürzungen</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_Info_Ausz_Abbilder.php' target='Blder'>Bildgrößen</a>";
  echo "</div>";

  Edit_Separator_Zeile('Aufruf der Programmteile: '); 
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_OV_O_List.php?sk=$sk&proj=AERM' target='Orts-Daten' >Orts-, Wappen und Abzeichenverwaltung</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='VF_PS_Ausz.php?sk=$sk&proj=AUSZ' target='Auszeichnungs-Daten'>Auszeichnungen, Ehrenzeichenverwaltung</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<b>Druckwerke</b>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='".$path2ROOT."login/AOrd_Verz/Downloads/Drucke/Katalog-FG/Uniformen_01092009.pdf' target='Uniformen'>Uniformen</a>";
  echo "</div>";
  
  echo "<div class='w3-row' >"; // Beginn der Einheit Ausgab
  echo "<a href='".$path2ROOT."login/AOrd_Verz/Downloads/Drucke/Katalog-FG/Katalog_m_Gilb_W.pdf' target= 'Ausz_1' >Auszeichnungen, Teil 1</a><br>
    <a href='".$path2ROOT."login/AOrd_Verz/Downloads/Drucke/Katalog-FG/Ausz_Kat_Teil_2_20140403_n-1.pdf' target='Ausz_2' >Auszeichnungen, Teil 2</a><br>
     <a href='".$path2ROOT."login/AOrd_Verz/Downloads/Drucke/Katalog-FG/Ausz_Kat_3_20150415.pdf' target='Ausz_3' >Auszeichnungen, Teil 3</a>    ";
  echo "</div>";


BA_HTML_trailer();
?>
