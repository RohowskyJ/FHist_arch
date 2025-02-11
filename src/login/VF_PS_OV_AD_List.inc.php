<?php

/**
 * Liste der Abzeichen- Beschreibungen
 * 
 * @author Josef Rohowsky - neu 2019
 * 
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (! isset($_SESSION[$module]['sub_mod'])) {
    $_SESSION[$module]['sub_mod'] = "AB";
} else {
    $_SESSION[$module]['sub_mod'] = "AB";
}

$table_ow = "az_beschreibg";
$select_ow = " WHERE `ab_fw_id` = '$fw_id' ";
$sort_ow = " ORDER BY `ab_fw_id` ASC ";
$sql_ow = "SELECT * FROM `$table_ow` $select_ow  $sort_ow ";

$T_list_texte = array(
    "NeuItem" => "<a href='VF_PS_OV_AD_Edit.php?ID=0' >Neuen Datensatz eingeben</a>",
    "AuszList" => "<a href='VF_PS_OV_AD_Z_List.php?fw_id=$fw_id' >Liste der Auszeichnungen anzeigen</a>"
);

# ===========================================================================================================
#
# Haeder ausgeben
#
# ===========================================================================================================

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_ow);

$Tabellen_Spalten = array(
    'ab_id',
    'ab_fw_id',
    'ab_art',
    'ab_beschreibg',
    'ab_stifter',
    'ab_stiftg_datum',
    'ab_statut',
    'ab_erklaerung',
    'ab_aend_uid',
    'ab_aenddat'
);

$Tabellen_Spalten_style['ab_id'] = 
$Tabellen_Spalten_style['ab_fw_id'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>' . '<li>Script: VF_PS_OV_AD_List</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($table_ow,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

$New_Link = "";

List_Create($db, $sql_ow,'', $table_ow, $New_Link,''); # die liste ausgeben

?>