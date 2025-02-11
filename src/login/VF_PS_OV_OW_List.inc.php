<?php

/**
 * Liste der Ortswappen 
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * Script wird von VF_PS_OV_M_Edit aufgerufen
 * 
 */

# ===========================================================================================
#
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
#
# ===========================================================================================
if (! isset($_SESSION[$module]['sub_mod'])) {
    $_SESSION[$module]['sub_mod'] = "OV";
} else {
    $_SESSION[$module]['sub_mod'] = "OV";
}

$table_ow = "aw_ort_wappen";
$select_ow = " WHERE `fo_fw_id` = '$fw_id' ";
$sort_ow = " ORDER BY `fo_gde_w_sort`";
$sql_ow = "SELECT * FROM `$table_ow` $select_ow  $sort_ow ";

$csv = False;

$T_list_texte = array(
    "NeuItem" => "<a href='VF_PS_OV_OW_Edit.php?ID=0' >Neue Datensatz eingeben</a>"
);

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_ow);

$Tabellen_Spalten_style['fo_id'] = $Tabellen_Spalten_style['fo_fw_id'] = $Tabellen_Spalten_style['fo_gde_wappen'] = $Tabellen_Spalten_style['fo_aenduid'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($table_ow,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_PS_OV_OW_Edit.php?ID=0' >Neuen Datensatz eingeben</a>";
}
List_Create($db, $sql_ow,'', $table_ow,'', $New_Link); # die liste ausgeben

# HTML_trailer();
?>