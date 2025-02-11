<?php

/**
 * Liste der Wappen bei der Feuerwehr
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (! isset($_SESSION[$module]['sub_mod'])) {
    $_SESSION[$module]['sub_mod'] = "OW";
} else {
    $_SESSION[$module]['sub_mod'] = "OW";
}

$table_ow = "aw_ff_wappen";
$select_ow = " WHERE `fo_fw_id` = '$fw_id' ";
$sort_ow = " ORDER BY `fo_ff_w_sort`";
$sql_ow = "SELECT * FROM `$table_ow` $select_ow  $sort_ow ";

$csv = False;

$T_list_texte = array(
    "NeuItem" => "<a href='VF_PS_OV_FW_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_ow);

$Tabellen_Spalten_style['fo_id'] = 
$Tabellen_Spalten_style['fo_fw_id'] = $Tabellen_Spalten_style['fo_ff_wappen'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($table_ow,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_PS_OV_FW_Edit.php?ID=0' >Neuen Datensatz eingeben</a>";
}
List_Create($db, $sql_ow,'', $table_ow,'', $New_Link); # die liste ausgeben

?>