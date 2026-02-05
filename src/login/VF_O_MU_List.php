<?php

/**
 * Museums- Listen 
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();
 
$module = 'OEF';
$sub_mod = 'MUS';

$tabelle = 'mu_basis';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_O_MU_List.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');
/*
$jq = $jqui = true;
$BA_AJA = true;

$jq_toggle = $jq_tabsort = $jq_accordion = true;
*/
$jq_tabsort = $jq = true;
BA_HTML_header('Museums- Daten- Verwaltung', '', 'Admin', '150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "DropdownAnzeige" => "Aus",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
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

if (isset($_GET['Act']) and $_GET['Act'] == 1) {
    $_SESSION[$module]['Act'] = $Act = $_GET['Act'];
    VF_chk_valid();
    VF_set_module_p();
    $_SESSION['VF_LISTE']['LangListe'] = "Aus";
} else {-
    $_SESSION[$module]['Act'] = $Act = 0;
    $_SESSION['VF_Prim']['p_uid'] = 999999999;
    $_SESSION[$module]['all_upd'] = False;
}

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

VF_Count_add();

if (isset($_POST['mu_staat'])) {
    $mu_staat = $_POST['mu_staat'];
} else {
    $mu_staat = "AT";
}
if (isset($_POST['bdld'])) {
    $mu_bdland = $_POST['bdld'];
} else {
    $mu_bdland = "";
}
if (isset($_POST['mu_mustyp'])) {
    $mu_mustyp = $_POST['mu_mustyp'];
} else {
    $mu_mustyp = "3";
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$SelMuTyp = "<select name='mu_mustyp' class='cms_inputtext' id='mu_mustyp'>";
foreach (VF_Mus_Typ as $key => $value) {
    $select0 = $select1 = $select2 = $select3 = "";

    switch ($key) {
        case '0':
            $select0 = "selected";
            break;
        case '1':
            $select1 = "selected";
            break;
        case '2':
            $select2 = "selected";
            break;
        case '3':
            $select3 = "selected";
            break;
    }
}
$SelMuTyp .= "<option $select3 style=\"color:#ff0000;\" value=\"3\" size=\"50\">Schauraum, Museum</option>\n";
$SelMuTyp .= "<option $select2 style=\"color:#ff0000;\" value=\"2\" size=\"50\">Traditionsraum</option>\n";
$SelMuTyp .= "<option $select1 style=\"color:#ff0000;\" value=\"1\" size=\"50\">Schausammlung</option>\n";
$SelMuTyp .= "<option $select0 style=\"color:#ff0000;\" value=\"0\" size=\"50\">Sammlung, Depot</option>\n";
$SelMuTyp .= "<option  style=\"color:#ff0000;\" value=\"alle\" >alle Auswählen</option>\n";
$SelMuTyp .= "</select><br/> &nbsp; &nbsp; (Definition: \"Handbuch zur Feuerwehrgeschichte\", ÖBFV, 2005)";

$opt_val = VF_Sel_Bdld($mu_bdland, '9', 'AT');
$SelBdld = "<select name='bdld' class='cms_inputtext' id='bdld'>";
foreach ($opt_val as $key => $value) {
    $selected = "";
    if ($key == $mu_bdland) {
        $selected = "selected";
    }
    $SelBdld .= "<option $selected style='color:#ff0000;' value='$key' size='50'>$value</option>\n";
}
$SelBdld .= "</select>";

$opt_val = VF_Sel_Staat('AT', '');
$SelStaat = "<select name='mu_staat' class='cms_inputtext' id='mu_staat'>";
foreach ($opt_val as $key => $value) {
    $selected = "";
    if ($key == $mu_staat) {
        $selected = "selected";
    }
    $SelStaat .= "<option $selected style='color:#ff0000;' value='$key' size='50'>$value</option>\n";
}
$SelStaat .= "</select>";

if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $T_list_texte = array(
        "Alle" => "Alle bekannten Museen anzeigen",
        "Staat" => "Auswahl nach Staat",
        "Bundld" => "Auswahl nach Bundesland",
        "MTyp" => "Auswahl nach Museumstyp"
    );
} else {
    $T_list_texte = array(
        "Alle" => "Alle bekannten Museen anzeigen",
        "Staat" => "Auswahl nach Staat ",
        "Bundld" => "Auswahl nach Bundesland  ",
        "MTyp" => "Auswahl nach Museumstyp  "
    );
}

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_MU_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $Tabellen_Spalten = array(
        'mu_name',
        'Information',
        'mu_bild_1'
    );
    $Tabellen_Spalten_COMMENT['Information'] = "Öffnungszeiten, Kontakt, Sammlungsschwerpunkt";
    $Tabellen_Spalten_COMMENT['mu_name'] = "Museumsname, Adresse, Typ";
    $Tabellen_Spalten_typ['Information'] = 'text';
    #$Tabellen_Spalten_typ['mu_name'] = 'text';
    $Tabellen_Spalten_MAXLENGTH['Information'] = 40;
    #$Tabellen_Spalten_MAXLENGTH['mu_name'] = ;
} else {
    $Tabellen_Spalten = array(
        'mu_id',
        'mu_staat',
        'mu_bdland',
        'mu_name',
        'mu_bezeichng',
        'mu_adresse_a',
        'mu_plz_a',
        'mu_ort_a',
        'mu_bild_1'
    );
}

$Tabellen_Spalten_style['mu_nr'] = $Tabellen_Spalten_style['bu_fbw_st_abk'] = $Tabellen_Spalten_style['ei_staat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Museums - Daten ändern: Auf die Zahl in Spalte <q>mu_id</q> Klicken.</li>';
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
$add_list = "<a href=\"https://de.wikipedia.org/wiki/Liste_von_Feuerwehrmuseen\" target='MusList'>Internationale Liste von Feuerwehrmuseen (WikiPedia)</a><br>";
switch ($T_List) {
    case "Alle":

        break;
    case "Staat":
        $add_list .= "Auswahl nach Staat $SelStaat<br>";
        break;
    case "Bundld":
        $add_list .= "Auswahl nach Bundesland $SelBdld";
        break;
    case "MTyp":
        $add_list .= "Auswahl nach Museumstyp $SelMuTyp";
        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     */
}
/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
List_Action_Bar($tabelle, "Feuerwehr und andere Blaulicht- Museen ", $T_list_texte, $T_List, $List_Hinweise, $add_list); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$sql = "SELECT * FROM $tabelle ";
$orderBy = $sql_where = "";

switch ($T_List) {
    case "Alle":
        $sql_where = " ";
        $orderBy = ' ORDER BY mu_id';
        break;
    case "Staat":
        $sql_where = "WHERE mu_staat = '$mu_staat' ";
        $orderBy = ' ORDER BY mu_id';
        break;
    case "Bundld":
        $sql_where = "WHERE mu_bdland = '$mu_bdland' ";
        $orderBy = ' ORDER BY mu_id';
        break;
    case "MTyp":
        $sql_where = "WHERE mu_mustyp = '$mu_mustyp' ";
        $orderBy = ' ORDER BY mu_id';
        break;
    default:
        BA_HTML_trailer();
        exit(); # wenn noch nix gewÃ¤hlt wurde >> beenden
}

$sql .= $sql_where . $orderBy;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O MU List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql, '', $tabelle, ''); # die liste ausgeben

echo "<</fieldset>";

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
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module;

    $pict_path = $path2ROOT."login/AOrd_Verz/Museen/";

    if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {

        $row['Information'] = "";
        $mu_name = $row['mu_name'];
        $row['mu_name'] = "<b>$mu_name </b><br/>" . $row['mu_adresse_a'] . "<br/>" . $row['mu_plz_a'] . " " . $row['mu_ort_a'] . "<br/>" . VF_Mus_Typ[$row['mu_mustyp']];

        $oeffnung = "<strong>Öffnungszeiten: </strong><br/>";
        if ($row['mu_oeffnung'] != "") {
            $oeffnung .= VF_Mus_Oeffzeit[$row['mu_oeffnung']] . "<br/>";
        }

        if ($row['mu_oez_mo'] == $row['mu_oez_di'] && $row['mu_oez_mo'] == $row['mu_oez_mi'] && $row['mu_oez_mo'] == $row['mu_oez_do'] && $row['mu_oez_mo'] != "") {
            $oeffnung .= "Montag bis Donnerstag: " . $row['mu_oez_mo'];
            if ($row['mu_oez_fr'] != "") {
                $oeffnung .= " &nbsp; &nbsp; Freitag: " . $row['mu_oez_fr'];
            }
        } else if ($row['mu_oez_di'] == $row['mu_oez_mi'] && $row['mu_oez_di'] == $row['mu_oez_do'] && $row['mu_oez_di'] == $row['mu_oez_fr'] && $row['mu_oez_di'] == $row['mu_oez_sa'] && $row['mu_oez_di'] != "") {
            $oeffnung .= "Dienstag bis Samstag: " . $row['mu_oez_di'];
        } else {
            if ($row['mu_oez_mo'] != "") {
                $oeffnung .= " Montag " . $row['mu_oez_mo'];
            }
            if ($row['mu_oez_di'] != "") {
                $oeffnung .= " Dienstag " . $row['mu_oez_di'];
            }
            if ($row['mu_oez_mi'] != "") {
                $oeffnung .= " Mittwoch " . $row['mu_oez_mi'];
            }
            if ($row['mu_oez_do'] != "") {
                $oeffnung .= " Donnerstag " . $row['mu_oez_do'];
            }
            if ($row['mu_oez_fr'] != "") {
                $oeffnung .= " Freitag " . $row['mu_oez_fr'];
            }
            if ($row['mu_oez_sa'] != "") {
                $oeffnung .= " Samstag " . $row['mu_oez_sa'];
            }
            if ($row['mu_oez_so'] != "") {
                $oeffnung .= " Sonntag " . $row['mu_oez_so'];
            }
            if ($row['mu_oez_fei'] != "") {
                $oeffnung .= " Feiertag " . $row['mu_oez_fei'];
            }
        }

        if ($row['mu_kustos_intern'] != "") {
                $inet = $row['mu_kustos_intern'];
                $oeffnung .= "<br/><a href=\"http://$inet\" target=_new>$inet</a><br/>";
                $row['mu_kustos_intern'] = "<a href='$inet' target='_blank' > $inet</a>";
        }

        if ($row['mu_f1_name'] != "" || $row['mu_f2_name'] != "" || $row['mu_f1_tel'] != "" || $row['mu_f2_tel'] != "" || $row['mu_f1_handy'] != "" || $row['mu_f2_handy'] != "") {
            $oeffnung .= "<strong>Anmeldung oder Auskunft bei: </strong><br/>";
            if ($row['mu_f1_name'] != "" || $row['mu_f1_tel'] != "" || $row['mu_f1_handy'] != "") {
                $oeffnung .= $row['mu_f1_titel'] . " " . $row['mu_f1_vname'] . " " . $row['mu_f1_name'] . " " . $row['mu_f1_dgr'] . " " . $row['mu_f1_tel'] . " " . $row['mu_f1_handy'] . " " . $row['mu_f1_email'];
            }
            if ($row['mu_f2_name'] != "" || $row['mu_f2_tel'] != "" || $row['mu_f2_handy'] != "") {
                $oeffnung .= "<br/>" . $row['mu_f2_titel'] . " " . $row['mu_f2_vname'] . " " . $row['mu_f2_name'] . " " . $row['mu_f2_dgr'] . " " . $row['mu_f2_tel'] . " " . $row['mu_f2_handy'] . " " . $row['mu_f2_email'];
            }
        }
        if ($row['mu_sammlgschw'] != "") {
            $oeffnung .= "<br/>Sammlungsschwerpunkt: ";
            $oeffnung .= $row['mu_sammlgschw'];
        }

        $row['Information'] = "$oeffnung";

        
    } else {
        $mu_id = $row['mu_id'];
        $row['mu_id'] = "<a href='VF_O_MU_Edit.php?ID=$mu_id' >$mu_id</a>";

        if ($row['mu_kustos_intern'] != "") {
                $mu_internet = "http://" . $row['mu_kustos_intern'];
                $row['mu_kustos_intern'] = "<a href='$mu_internet' target='_blank' > $mu_internet</a>";
        }
        
    }
    
    if ($row['mu_bild_1'] != "") {

        $mu_bild_1 = $row['mu_bild_1'];
        
        $dn_a = pathinfo(strtolower($mu_bild_1));
        
        if ($dn_a['extension'] == "pdf" || $dn_a['extension'] == 'doc') {
            $image1 = "<a href='".$path2ROOT ."login/AOrd_Verz/Museen/$mu_bild_1' > $mu_bild_1 </a>";
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
            $fo_arr = explode("-", $mu_bild_1);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                
                $image1 = $pict_path.$urh."09/".$aord_sp.$verz.$mu_bild_1;
                
                if (!is_file($image1)) {
                    $image1 = $pict_path . $mu_bild_1;
                }
            } else {
                $image1 = $pict_path . "Museen/". $mu_bild_1;
            }
            $image2 = "<img src='$image1' alt='Bild 1' width='100px'/> ";
        }
        $row['mu_bild_1'] = "<a href='".$image1."'  target='_blanc'> $image2 </a>";
    }

    # -------------------------------------------------------------------------------------------------------------------------
    return True;
} # Ende von Function modifyRow

?>
