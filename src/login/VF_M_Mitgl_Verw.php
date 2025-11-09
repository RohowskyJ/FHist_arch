<?php

/**
 * Menu Mitgliederverwaltung
 * 
 * @author Josef Rohowsky - neu 2023
 */
session_start();

$module = 'MVW';
$sub_mod = 'all';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";



$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';

$flow_list = False;

initial_debug();

$LinkDB_database = '';
$db = LinkDB('VFH'); // Connect zur Datenbank

VF_chk_valid();

VF_set_module_p();

$sk = $_SESSION['VF_Prim']['SK'];

VF_Count_add();

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Mitglieder- Verwaltung', '', 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# ### ab hier nur mehr für Vorstandsmitglieder !!! Umbau
if ($_SESSION[$module]['p_zug'] == "V" or $_SESSION[$module]['p_zug'] == "A") {
    if ($_SESSION[$module]['p_zug'] == "A") {
        $_SESSION[$module]['all_upd'] = False;
    }
    # echo "<tr><th><br>Berechtigung umbauen<br></th></tr>";
    Edit_Separator_Zeile('Mitglieder- Verwaltung');
    
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<tr><td><a href='VF_M_List.php??sk=$sk' target='M-Verwaltung'>Mitgliederverwaltung</a></td></tr>";
    echo "  </div>";  // Ende Feldname
    
    Edit_Separator_Zeile('Ehrungen- Verwaltung');
    
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<tr><td><a href='VF_M_Ehrg_List.php??sk=$sk' target='M-Verwaltung'>Ehrungen</a></td></tr>";
    echo "  </div>";  // Ende Feldname
    

    
    Edit_Separator_Zeile('Unterstützer- Verwaltung');
    
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<tr><td><a href='VF_M_Unterst_List.php??sk=$sk' target='M-Verwaltung'>Unterstützer</a></td></tr>";
    echo "  </div>";  // Ende Feldname
    
    Edit_Separator_Zeile('Mitglieder- Zahlungseingangs- Verwaltung');
    echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
    echo "<tr><td>Hier werden die Zahlungseingänge (Mitgliedsbeitrag und ABO- Gebühr verwaltet). </td></tr>";
    echo "  </div>";  // Ende Feldname
    
    if ($_SESSION[$module]['p_zug'] == "V") {
        echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
        echo "<tr><td><a href='VF_MB_List.php?sk=$sk' target='M Bez.-Verwaltung'>Beitrags- Eingang</a></td></tr>";
        echo "  </div>";  // Ende Feldname
    }
    
}

Edit_Separator_Zeile('Mitglieder- E-Mail an');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<tr><td>Mitglieder können E-Mails an andere Mitglieder senden, ohne das Sie die E-Mail Adresse kennen.</a></td></tr>";
echo "  </div>";  // Ende Feldname

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<tr><td><a href='VF_M_Mail.php?sk=$sk' target='M-Mail'>Mail an andere Mitglieder senden </a></td></tr>";
echo "  </div>";  // Ende Feldname

Edit_Separator_Zeile('Mitglieder- Auskuft laut DSVGO');

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<tr><td>Jedes Mitglied kann sich die im System gespeicherten persönliche Daten entsprechend der DSVGO selbst anfordern und bekommt sie sofort per E-Mail zugeschickt.</td></tr>";
echo "  </div>";  // Ende Feldname

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<tr><td><a href='VF_M_yellow.php?sk=$sk' target='M-Datenabfrage'>Mitglieder-Daten Auskunft laut DSGVO</a></td></tr>";
echo "  </div>";  // Ende Feldname

BA_HTML_trailer();
?>
