<?php

/**
 * Liste der vom Verein verliehenen Ehrungen
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * 
 */

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$_SESSION[$module]['mi_id'] = $neu['mi_id'];
$table_eh = "fh_m_ehrung";
$select_eh = " WHERE fe_m_id = '" . $neu['mi_id'] . "' ";
$sort_eh = " ORDER BY 'fe_eh_datum' ASC ";

$sql_eh = "SELECT * FROM $table_eh $select_eh $sort_eh";

$T_list_texte = array(
    "NeuItem" => "<a href='VF_M_EH_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen


$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_eh);
$Tabellen_Spalten = array(
    'fe_lfnr',
    'fe_m_id',
    'fe_ehrung',
    'fe_eh_datum',
    'fe_begruendg',
    'fe_bild1'
);

$Tabellen_Spalten_style['fe_lfnr'] = $Tabellen_Spalten_style['fe_m_id'] = 
$Tabellen_Spalten_style['fe_eh_datum'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>' . '<li>Script: VF_M_EH_List</li>';

List_Action_Bar($table_eh,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

$New_Link = "";

List_Create($db, $sql_eh,'', $table_eh,'', $New_Link,''); # die liste ausgeben

?>
