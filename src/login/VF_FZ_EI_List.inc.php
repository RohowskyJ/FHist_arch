<?php

/**
 * Fahrzeuge, Typenschein, Liste
 *
 * @author Josef Rohowsky - neu 2025
 *
 */
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons
# ===========================================================================================

$T_list_texte = array(
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$logo = 'NEIN';
$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_FZ_EI_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_ei = "ma_eigner";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_ei);

$tabelle_ei_a = $tabelle_ei . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_ma_eigner($tabelle_ei_a);
if ($return != true) {
    echo "error: mysqli_errno($return)";
}

$select_ei = " WHERE `fz_id` = '$fz_id' ";
$sort_ei = " ORDER BY `fz_eign_id`";
$sql_ei = "SELECT * FROM `$tabelle_ei_a` $select_ei  $sort_ei ";

$Tabellen_Spalten = array(
    'fz_eign_id',
    'fz_id',
    'fz_docbez',
    'fz_zuldaten',
    'fz_zul_dat',
    'fz_zul_end_dat'
);

$Tabellen_Spalten_style['fz_eign_id'] = $Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_zul_end_dat'] = $Tabellen_Spalten_style['fz_zul_dat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_eign_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle, "", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
#
# Die Daten lesen und Ausgeben
#
# ===========================================================================================================

$New_Link = "";

List_Create($db, $sql_ei, '', $tabelle_ei_a, '', $New_Link); # die liste ausgeben
