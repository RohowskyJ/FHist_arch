<?php

/**
 * OEffentlichkitsarbits - Menu
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start();

const Module_Name = 'OEF';
$module = Module_Name;
# const Tabellen_Name = 'fh_dokumente';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = True;

flow_add($module,"VF_O_Verw.php");

initial_debug();

VF_chk_valid();
VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

$LinkDB_database = "";
$db = linkDB('VFH');
VF_Count_add();

$ini_arr = parse_ini_file($path2ROOT.'login/common/config_m.ini',True,INI_SCANNER_NORMAL);
$cnt_m = count($ini_arr['Modules']);
if (isset($ini_arr['Modules']) && $cnt_m >10){
    
    /** Tabellen für Urheber-Name einlesen */
    VF_Urh_ini();
    
   # ===========================================================================================================
   # Haeder ausgeben
   # ===========================================================================================================
    
   BA_HTML_header('Öffentlichkeits- Arbeit und Museen', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
    
   Edit_Tabellen_Header('Öffentlichkeitsarbeit' );
   
   Edit_Separator_Zeile('Archiv- und Bibliotheks- Links');
   echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
   echo "Pflege der öffentlichen Links zu Bibliotkeken und Archiven.";
   echo "  </div>";  // Ende Feldname
   echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
   echo "<a href='VF_O_AR_List.php?sk=$sk&Act=1' target='Archive'>Archiv- und Bibliotheks- Links</a>";
   echo "</div>"; // Ende der Ausgabe- Einheit Feld
   
   if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
       Edit_Separator_Zeile('Marktplatz, Biete/Suche');
       echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
       echo "Jedes Mitglied hier seine Wünsche und freies Material anbieten/suchen.";
       echo "  </div>";  // Ende Feldname
       echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
       echo "<a href='VF_O_AN_List.php?sk=$sk&Act=1' target='Biete-Suche'>Biete- /Suche- Marktplatz, Adminstrativer Teil</a>";
       echo "</div>"; // Ende der Ausgabe- Einheit Feld
       
       Edit_Separator_Zeile('Buch Rezensionen');
       echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
       echo "Pflege der Buch Rezensionen";
       echo "  </div>";  // Ende Feldname
       echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
       if ($ini_arr['Modules']['m_2'] == "J") {
           echo "<a href='VF_O_BU_List.php?sk=$sk&Act=1' target='Bücher'>Buch- Rezensionen, Verwalten, Redigieren, Freischalten</a>";
       } else {
           echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
       }
       echo "</div>"; // Ende der Ausgabe- Einheit Feld
       
       Edit_Separator_Zeile('Dokumentationen zum herunterladen');
       echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
       echo "Hier werden die verschiedenen im Verein erstellten und Vorgetragenen Dokumentationen ins Netz gestellt,
          und können heruntergeladen und dürfen für Zwecke der Feuerwehrgeschichte verwendet werden.";
       echo "  </div>";  // Ende Feldname
       echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
       echo "<a href='VF_O_DO_List.php?sk=$sk&Act=1' target='Doku'>Dokumentationen zum herunterladen</a>";
       echo "</div>"; // Ende der Ausgabe- Einheit Feld
       /*
       if ($ini_arr['Modules']['m_6'] == "J") {
           
       } else {
           echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
       }
       */
   }


    Edit_Separator_Zeile('Fotos, Videos (Filme), Berichte');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Hier können die von Mitgliedern erstellten Fotos <b>einzeln oder als Masse (Verzeichnisweise</b> ins Netz gestellt,
       und  heruntergeladen und dürfen für Zwecke des Vereines mit Namensnennung des Fotografen (Urheber) verwendet werden.
       Für die Berichtserstellung werden diese Fotos direkt verwendet - kein extra Upload notwendig.";
    echo "  </div>";  // Ende Feldname
    echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
    echo "<a href='VF_FO_Ber_Verw.php?sk=$sk&Act=1' target='Foto_Ber'>Foto, Video und Berichte- Verwaltung</a>";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    Edit_Separator_Zeile('Museumsdaten warten');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Pflege der Museumsliste ";
    echo "  </div>";  // Ende Feldname
    echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
    echo "<a href='VF_O_MU_List.php?sk=$sk&Act=1' target='Museen'>Museumsdatenwartung</a>";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    
    Edit_Separator_Zeile('Archivalien- und Inventar- Verwaltung');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Verwaltung aller Dokumente, Video- Listen, Foto-(Negativ)-Listen (die Fotos und Videos selbst sind unter \"Foto,Video und Berichte\" zu finden), ...";
    echo "</div>";
    echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
    if ($ini_arr['Modules']['m_1'] == "J") {    
        echo "<a href='VF_A_Archiv_Verw.php?sk=$sk' target='A-Verwaltung'>Archivalienverwaltung und erweiterte Archivordnung </a>";
    } else {
        echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
    }
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
    if ($ini_arr['Modules']['m_5'] == "J") {
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "Verwaltung aller nicht unter Dokumente fallenden Gegenstände.";
        echo "  </div>";  // Ende Feldname
        echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
        echo "<a href='VF_I_Inv_Verw.php?sk=$sk' target='Inventar'>Inventar- Verwaltung</a>";
        echo "</div>"; // Ende der Ausgabe- Einheit Feld
    } else {
        echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
    }
    
    if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten"){
        Edit_Separator_Zeile('Presse- Ausschnitte');
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "Pflege der in der Presse veröffentlichen Artikel";
        echo "  </div>";  // Ende Feldname
        echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
        if ($ini_arr['Modules']['m_8'] == "J") {
            echo "<a href='VF_O_PR_List.php?sk=$sk&Act=1' target='Presse'>Presse-Informationen verwalten</a>";
        } else {
            echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
        }
        echo "</div>"; // Ende der Ausgabe- Einheit Feld
        
        Edit_Separator_Zeile('Terminplan  (Kalender)');
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "Hier werden die Termine in den Kalender eingepflegt ";
        echo "  </div>";  // Ende Feldname
        echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
        if ($ini_arr['Modules']['m_6'] == "J") {
            echo "<a href='VF_O_TE_List.php?sk=$sk&Act=1' target='Terminplan'>Terminplan und Anmeldungs- Bearbeitung</a>";
        } else {
            echo " &nbsp;  &nbsp; &nbsp; &nbsp; Programmteil nicht verfügbar.";
        }
        echo "</div>"; // Ende der Ausgabe- Einheit Feld
        
        Edit_Separator_Zeile('Index von Feuerwehrzeitungen');
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "<tr><td>Eingabe und Pflege von Index für Feuerwehrzeitugen.";
        echo "  </div>";  // Ende Feldname
        echo "<div class='w3-row'>"; // Beginn der Anzeige Feld-Name
        echo "<a href='VF_O_ZT_List.php?sk=$sk' target='ZT-Index'>Zeitungsindex</a>";
        echo "</div>"; // Ende der Ausgabe- Einheit Feld
        
    }
    
} else {
    echo "Konfigurations- Fehler. Konfirguration der <b>Module</b> neu aufsetzen. <br>";
}

BA_HTML_trailer();
?>
