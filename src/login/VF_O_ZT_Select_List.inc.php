<?php

/**
 * Zeitungs- Auswahlliste
 * 
 * @author J.Rohowsky
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_ZT_Select_List.inc.php";

$tabelle = 'zt_zeitungen';

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - DropDownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "DropDownAnzeige"     => "Aus",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle Zeitungen ( Auswahl ) "
);

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_O_ZT_Z_Edit.php?ID=0' >Neue Zeitung eingeben</a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);
$Tabellen_Spalten = array('zt_id','zt_name','zt_herausg','zt_internet','zt_email','zt_daten');

$Tabellen_Spalten_style['zt_id'] = 
$Tabellen_Spalten_style['ei_mitglnr'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Zeitungsnamen ändern: Auf die Zahl in Spalte <q>zt_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":

    case "AdrList":
        break;

    default:
}
$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle,"Zeitungs- Verwaltung - Administrator", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = "";
$orderBy = " ORDER BY zt_id ASC ";
$sql = "SELECT * FROM $tabelle ";

$sql .= $sql_where . $orderBy;

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>O ZT Sel List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,'');

echo "</fieldset>";

HTML_trailer();

?>