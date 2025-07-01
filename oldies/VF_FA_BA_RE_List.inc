<?php

/**
 * Liste der Reparaturen
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
$T_list_texte = array(
    "NeuItem" => "<a href='VF_FA_BA_RE_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_re = "fz_reparat";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_re);

$tabelle_re_a = $tabelle_re . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_fz_reparat($tabelle_re_a);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$select_re = " WHERE `fz_id` = '$fz_id' ";
$sort_re = " ORDER BY `fz_rep_id`";
$sql_re = "SELECT * FROM `$tabelle_re_a` $select_re  $sort_re ";

$Tabellen_Spalten = array(
    'fz_rep_id',
    'fz_id',
    'fz_repdat',
    'fz_reptext'
);

$Tabellen_Spalten_style['fz_rep_id'] = $Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_repdat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_rep_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle_re_a,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
#
# Die Daten lesen und Ausgeben
#
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_FA_BA_RE_Edit.php?ID=0' >Neu</a>";
}
List_Create($db, $sql_re,'', $tabelle_re_a,'', $New_Link); # die liste ausgeben

?>