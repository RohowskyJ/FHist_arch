<?php

/**
 * Liste der Auszeichnungen bei der Feuerwehr
 *
 * @author Josef Rohowsky - neu 2020
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_PS_OV_AZ_List.inc.php"; 

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (! isset($_SESSION[$module]['sub_mod'])) {
    $_SESSION[$module]['sub_mod'] = "AW";
} else {
    $_SESSION[$module]['sub_mod'] = "AW";
}

$table_az = "az_auszeich";
$select_az = " WHERE `az_ad_id` = '$ad_id' ";
$sort_az = " ORDER BY az_fw_id,`az_ad_id`,az_stufe  ASC";
$sql_az = "SELECT * FROM `$table_az` $select_az  $sort_az ";

$T_list_texte = array(
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_PS_OV_AZ_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $table_az);

$Tabellen_Spalten = array('az_id','az_ad_id','az_mat','az_bild_v','az_bild_r','az_bild_m','az_bild_m_r','az_bild_klsp','az_aend_uid','az_aenddat');

$Tabellen_Spalten_style['az_id'] = $Tabellen_Spalten_style['az_ad_id'] = $Tabellen_Spalten_style['az_stufe'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>a*?_id</q> Klicken.</li>' . '<li>Script VF_PS_OV_AZ_List </li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($table_az,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>PS OV AZ List vor list_create $sql_az </pre>";
echo "</div>";

$New_Link = "";
List_Create($db, $sql_az,'', $table_az,'', $New_Link); # die liste ausgeben

?>