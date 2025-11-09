<?php
/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_I_IN_VL_List.php"; 

# ===========================================================================================
#
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
#
# ===========================================================================================
$T_list_texte = array(
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_I_IN_VL_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle_ft = "in_vent_verleih";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_ft);

$tabelle_in_a = $tabelle_ft . "_" . $_SESSION['Eigner']['eig_eigner'];

Cr_n_in_vent_verleih($tabelle_in_a);

$select_ft = " WHERE `ei_invnr` = '" . $_SESSION[$module]['in_id'] . "' ";
$sort_ft = " ORDER BY `vl_id`";
$sql_ft = "SELECT * FROM `$tabelle_in_a` $select_ft  $sort_ft ";

$Tabellen_Spalten = array(
    'vl_id',
    'in_id',
    'ei_leiher',
    'ei_verlbeg',
    'ei_verlend',
    'ei_verlgrund',
    'ei_verlrueck',
    'ei_verleihuebn',
    'ei_zustand_aus',
    'ei_zust_aus_bild'
);

$Tabellen_Spalten_style['in_id'] = $Tabellen_Spalten_style['vl_id'] = $Tabellen_Spalten_style['ei_leiher'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>vl_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle_in_a,"Inventar- Verleih", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'I IN VL List vor list_create $sql_ft </pre>";
echo "</div>";

$New_Link = "";

List_Create($db, $sql_ft,'', $tabelle_in_a,'', $New_Link);

?>