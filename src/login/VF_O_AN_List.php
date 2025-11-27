<?php

/**
 * Liste der Anbote / Nachfragen
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

$module = 'OEF';
$sub_mod ='AN';

$tabelle = 'bs_biete_suche';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_O_AN_List.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

BA_HTML_header('Marktplatz - Biete/Suche', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<main>";
echo "<framework>";
initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "DropdownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

// Wennn Aufruf von Offener Seite kommt - NUR LESE-Berechtigung!

if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = False;
}

if (isset($_GET['Act']) and $_GET['Act'] == 1) {
    $_SESSION[$module]['Act'] = $Act = $_GET['Act'];
    VF_chk_valid();
    VF_set_module_p();
    $_SESSION['VF_LISTE']['LangListe'] = "Aus";
} else {
    $_SESSION[$module]['Act'] = $Act = 0;
    $_SESSION['VF_Prim']['p_uid'] = 999999999;
    $_SESSION[$module]['all_upd'] = False;
}

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    if ($_SESSION[$module]['Act'] == 0) {
        header("Location: ../");
    } else {
        header("Location: ../login/VF_C_Menu.php");
    }
}

VF_Count_add();

$csv_DSN = "";

if (isset($_POST['sel_thema'])) {
    $sel_thema = $_POST['sel_thema'];
} else {
    $sel_thema = "";
}
if (isset($_POST['select_string'])) {
    $_SESSION[$module]['$select_string'] = $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Einträge "
    );
} else {
    $T_list_texte = array(
        "Alle" => "Alle verfügbaren Einträge "
    );
}



$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_AN_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

if ($_SESSION[$module]['Act'] == 0) {
    $Tabellen_Spalten = array(
        'bs_id',
        'bs_startdatum',
        'bs_enddatum',
        'bs_kurztext',
        'bs_text',
        'bs_email_1',
        'bs_email_2',
        'bs_bild_1',
        'bs_bild_2',
        'bs_bild_3',
        'bs_bild_4'
    );
} else  {
    $Tabellen_Spalten = array(
        'bs_id',
        'bs_startdatum',
        'bs_kurztext',
        'bs_text',
        'bs_email_1',
        'bs_email_2',
        'bs_bild_1',
        'bs_bild_2',
        'bs_bild_3',
        'bs_bild_4'
    );
}


$Tabellen_Spalten_style['bs_id'] =
$Tabellen_Spalten_style['bs_typ'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Buch - Daten ändern: Auf die Zahl in Spalte <q>bs_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":

        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     */
}
$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle," Biete- / Suche, Marktplatz ", $T_list_texte, $T_List, $List_Hinweise, ''); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$date = date('Y-m-d');
$where = "";
$sql = "SELECT * FROM $tabelle ";
if ($_SESSION[$module]['Act'] == 0) {
    $where = " WHERE bs_enddatum >= '$date' ";
}

$sql .= $where;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O AN List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

echo "</framework>";
echo "</main>";

BA_HTML_trailer();

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{
    global $path2ROOT, $T_List, $module;

    $pict_path = $path2ROOT."login/AOrd_Verz/Biete_Suche/";

    $defjahr = date("y"); // Beitragsjahr, ist Gegenwärtiges Jahr

    $bs_id = $row['bs_id'];
    $row['bs_id'] = "<a href='VF_O_AN_Edit.php?ID=$bs_id' >$bs_id</a>";
    
    if ($row['bs_typ'] == 'B')  {
        $row['bs_id'] .= "<br>Biete";
    } elseif ($row['bs_typ'] == 'B')  {
        $row['bs_id'] .= "<br>Suche";
    }

    if ($row['bs_kurztext'] == "") {
        if ($row['bs_kurztext'] == "") {} else {
            if (strlen($row['bs_text']) > 30) {
                $bs_text = substr($row['bs_text'], 0, 20);
                $row['bs_text'] = "$bs_text </b></i></p></font></span></div> <a href='VF_O_AN_Edit.php?ID=$bs_id' >weiter ...</a>";
            }
        }
    } else {
        if (strlen($row['bs_kurztext']) > 30) {
            $bs_kurztext = substr($row['bs_kurztext'], 0, 20);
            $row['bs_kurztext'] = "$bs_kurztext </b></i></p></font></span></div>  <a href='VF_O_AN_Edit.php?ID=$bs_id' >weiter ...</a>";
        }
        if ($row['bs_text'] == "") {} else {
            if (strlen($row['bs_text']) > 30) {
                $bs_text = substr($row['bs_text'], 0, 20);
                $row['bs_text'] = "$bs_text </b></i></p></font></span></div>  <a href='VF_O_AN_Edit.php?ID=$bs_id ' >weiter ...</a>";
            }
        }
    }

    if ($row['bs_bild_1'] != "") {
        $bs_bild_1 = $row['bs_bild_1'];
        
        $dn_a = pathinfo(strtolower($bs_bild_1));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$bs_bild_1' > $bs_bild_1 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Biete_Suche/";
                }
            }
            $fo_arr = explode("-", $bs_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image1 = $pict_path.$urh."09/".$aord_sp.$verz.$bs_bild_1;
                
                if (!is_file($image1)) {
                    $image1 = $pict_path . $bs_bild_1;
                }
            } else {
                $image1 = $pict_path . "Biete_Suche/". $bs_bild_1;
            }
            $image1 = "<img src='$image1' alt='Bild 1' width='100px'/> ";
        }
        $row['bs_bild_1'] = "<a href='".$pict_path . $bs_bild_1."'  target='_blanc'> $image1 </a>";
    }

    if ($row['bs_bild_2'] != "") {
        $bs_bild_2 = $row['bs_bild_2'];
        
        $dn_a = pathinfo(strtolower($bs_bild_2));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image2 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$bs_bild_2' > $bs_bild_2 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Biete_Suche/";
                }
            }
            $fo_arr = explode("-", $bs_bild_2);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image2 = $pict_path.$urh."09/".$aord_sp.$verz.$bs_bild_2;
                
                if (!is_file($image2)) {
                    $image2 = $pict_path . $bs_bild_2;
                }
            } else {
                $image2 = $pict_path . "Biete_Suche/". $bs_bild_2;
            }
            $image2 = "<img src='$image2' alt='Bild 2' width='100px'/> ";
        }
        $row['bs_bild_2'] = "<a href='".$pict_path . $bs_bild_2."'  target='_blanc'> $image2 </a>";
    }
    if ($row['bs_bild_3'] != "") {
        $bs_bild_3= $row['bs_bild_3'];
        
        $dn_a = pathinfo(strtolower($bs_bild_3));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image3 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$bs_bild_3' > $bs_bild_3 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Biete_Suche/";
                }
            }
            $fo_arr = explode("-", $bs_bild_3);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image3 = $pict_path.$urh."09/".$aord_sp.$verz.$bs_bild_3;
                
                if (!is_file($image1)) {
                    $image3 = $pict_path . $bs_bild_3;
                }
            } else {
                $image3 = $pict_path . "Biete_Suche/". $bs_bild_3;
            }
            $image3 = "<img src='$image3' alt='Bild 1' width='100px'/> ";
        }
        $row['bs_bild_3'] = "<a href='".$pict_path . $bs_bild_3."'  target='_blanc'> $image1 </a>";
    }
    if ($row['bs_bild_4'] != "") {
        $bs_bild_4 = $row['bs_bild_4'];
        
        $dn_a = pathinfo(strtolower($bs_bild_4));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Biete_Suche/$bs_bild_4' > $bs_bild_4 </a>";
        } else {
            $aord_sp = "";
            $pict_path = $path2ROOT ."login/AOrd_Verz/";
            foreach (GrafFiles as $key => $val ){
                if ($dn_a['extension'] == $val) {
                    $aord_sp = "06/";
                    
                }
            }
            
            if ($aord_sp == "") {
                if ($dn_a['extension'] == 'mp3') {
                    $aord_sp = "02/";
                } elseif ($dn_a['extension'] == 'mp4') {
                    $aord_sp = "10/";
                } else {
                    $aord_sp = "Biete_Suche/";
                }
            }
            $fo_arr = explode("-", $bs_bild_4);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image4 = $pict_path.$urh."09/".$aord_sp.$verz.$bs_bild_4;
                
                if (!is_file($image4)) {
                    $image4 = $pict_path . $bs_bild_4;
                }
            } else {
                $image4 = $pict_path . "Biete_Suche/". $bs_bild_4;
            }
            $image4 = "<img src='$image4' alt='Bild 4' width='100px'/> ";
        }
        $row['bs_bild_4'] = "<a href='".$pict_path . $bs_bild_4."'  target='_blanc'> $image4 </a>";
    }
    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>
