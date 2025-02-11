<?php

/**
 * Liste der Ärmelabzeichen bei der Feuerwehr
 *
 * @author Josef Rohowsky - neu 2020
 *
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$table_ow = "aw_aermel_abz";
$select_ow = " WHERE `fo_fw_id` = '$fw_id' ";
$sort_ow = " ORDER BY `fo_ff_a_sort`";
$sql_ow = "SELECT * FROM `$table_ow` $select_ow  $sort_ow ";

$T_list_texte = array(
    "NeuItem" => "<a href='VF_PS_OV_AB_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_ow);

$Tabellen_Spalten_style['fo_id'] = 
$Tabellen_Spalten_style['fo_fw_id'] = $Tabellen_Spalten_style['fo_ff_abzeich'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>' . '<li>Script: VF_PS_OV_AB_List</li>';
/*
 * switch($T_List)
 * {
 * case "Alle":
 *
 *
 * case "AdrList":
 * break;
 *
 * default :
 *
 * }
 */
$List_Hinweise .= '</ul></li>';

List_Action_Bar($table_ow,'', $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
#
# Die Daten lesen und Ausgeben
#
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_PS_OV_AB_Edit.php?ID=0' >Neuen Datensatz eingeben</a>";
}

List_Create($db, $sql_ow,'', $table_ow,'', $New_Link); # die liste ausgeben

?>