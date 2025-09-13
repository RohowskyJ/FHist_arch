<?php

/**
 * Foto-  Video-  und Berichts- Verwaltung - Menu
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

$flow_list = False;

initial_debug();

VF_chk_valid();
VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

$db = linkDB('VFH');
VF_Count_add();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Foto/Video und Berichte- Verwaltung', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

Edit_Separator_Zeile('Fotos, Videos (Filme)');
echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Hier werden die von Mitgliedern erstellten Fotos <b>einzeln</b> ins Netz gestellt, 
und können heruntergeladen und dürfen für Zwecke des Vereines mit Namensnennung des Fotografen (Urheber) verwendet werden.";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Ab 2024.06 müssen die hochzuladenden Foto-Dateinamen das richtige Format haben (Urh-Datum-original-Namen) und die richtige Größe (maximal 20 x 20 cm oder 800 x 800 Pixel), sonst werden sie nicht hochgeladen.";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Die Funktionen Massupload (Hochladen) und Tabellen- erstellen können zum Hochladen von kompleten Fotoserien benutzt werden.";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<a href='VF_FO_List.php?sk=$sk' target='Foto'>Fotos, Videos </a>";
echo "</div>"; // Ende der Ausgabe- Einheit Feld


if ($_SESSION['VF_Prim']['p_uid'] == 1) {

    Edit_Separator_Zeile('Massen upload mit Rework (Größe, Wasserzeichen, Verzeichnis- Record wird erstellt, Fotos werden am Speicherort abgelegt und vor der Anzeige in die Tabellen eingefügt)');
    
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "Hier können die von Mitgliedern erstellten Fotos auf den Server je  Veranstaltung massenweise geladen und die erste Tabelleneintragung für die Fotos wird erstellt.
          Die bearbeiteten Fotos (Größenanpassung, Copyright Info) werden in dem dazu bestimmten Verzeichnis abgelegt.
          Die Daten über die Veranstaltung werden erfasst. Die erstellung der Tabellen- Eintragungen erfolgt durch Aufruf der Foto- Funktionen. ";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld

    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<a href='VF_FO_Media_MassUp.php?sk=$sk&res_eign=1' target='Foto_Up'> Medien- Massen- Upload (Audio, Fotos, Videos)</a>";
    echo "</div>"; // Ende der Ausgabe- Einheit Feld
}

Edit_Separator_Zeile('Berichte über Vereins- oder historisch interessante Ereignisse');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "Eingabe der Berichte - <b>muss noch erstellt werden</b>.";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<a href='VF_FO_Ber_List.php?sk=$sk&Act=1' target='Bericht'>Veranstaltungs- Berichte erstellen</a>";
echo "</div>"; // Ende der Ausgabe- Einheit Feld

BA_HTML_trailer();
?>
