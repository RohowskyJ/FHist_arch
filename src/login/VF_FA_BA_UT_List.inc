<?php

/**
 * Fahrzeuge, Typisierungsänderung, Liste
 *
 * @author Josef Rohowsky - neu 2019 
 *
 *
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
# echo "kren";
$T_list_texte = array(
    "NeuItem" => "<a href='VF_FA_BA_UT_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

# ===========================================================================================================
# Header ausgeben
# ===========================================================================================================

$VF_logo = 'NEIN';

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_ty = "fz_typis_aend";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_ty);

$tabelle_ty_a = $tabelle_ty . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_fz_typis_aend($tabelle_ty_a);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$select_ty = " WHERE `fz_id` = '$fz_id' ";
$sort_ty = " ORDER BY `fz_typ_id`";
$sql_ty = "SELECT * FROM `$tabelle_ty_a` $select_ty  $sort_ty ";

$Tabellen_Spalten = array(
    'fz_typ_id',
    'fz_id',
    'fz_t_aenddat',
    'fz_infotext'
);

$Tabellen_Spalten_style['fz_typ_id'] = $Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_t_aenddat'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_typ_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle_ty_a, "", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_FA_BA_UT_Edit.php?ID=0' >Neu</a>";
}
List_Create($db, $sql_ty,'', $tabelle_ty_a,'', $New_Link); # die liste ausgeben

?>