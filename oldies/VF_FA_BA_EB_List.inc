<?php

/**
 * Fahrzeuge, fixe Enbauten, Liste
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons
# ===========================================================================================
$T_list_texte = array(
    "NeuItem" => "<a href='VF_FA_BA_EB_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_eb = "fz_fixeinb";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_eb);

$tabelle_eb_a = $tabelle_eb . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_fz_fixeinb($tabelle_eb_a);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$select_eb = " WHERE `fz_id` = '$fz_id' ";
$sort_eb = " ORDER BY `fz_einb_id`";
$sql_eb = "SELECT * FROM `$tabelle_eb_a` $select_eb  $sort_eb ";

switch ($T_List) {
    /*
     * case "Alle" :
     *
     *
     * $Tabellen_Spalten = array('ei_id','ei_mitglnr','ei_staat','ei_bdld','ei_bezirk','ei_orgtyp','ei_org_name','kont_name','ei_fwkz','ei_grgjahrmi_ort'
     * ,'mi_gebtag','mi_tel_handy','mi_handy','mi_fax','mi_email','mi_email_status','mi_ref_leit','mi_ref_ma','mi_ref_int','mi_sterbdat'
     * ,'mi_austrdat','mi_einv_art','mi_einversterkl','mi_einv_dat','mi_uidaend','mi_aenddat'
     * );
     * break;
     *
     * case "AdrList":
     * $Tabellen_Spalten = array('ei_id','ei_org_typ','ei_org_name','kont_name','ei_anrede','ei_titel','ei_name','ei_vname','ei_anschr','ei_plz','ei_ort'
     * );
     * break;
     *
     */
    default:
        $Tabellen_Spalten = array(
            'fz_einb_id',
            'fz_id',
            'fz_gername',
            'fz_ger_herst',
            'fz_ger_typ',
            'fz_ger_baujahr',
            'fz_ger_foto_1'
        );
        break;
}

$Tabellen_Spalten_style['fz_einb_id'] = 
$Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_ger_baujahr'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_einb_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle_eb_a,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_FA_BA_EB_Edit.php?ID=0' >Neu</a>";
}
List_Create($db, $sql_eb,'', $tabelle_eb_a,'', $New_Link); # die liste ausgeben

?>