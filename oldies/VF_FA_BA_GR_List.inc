<?php

/**
 * Fahrzeuge, Geräteräume, Liste
 *
 * @author Josef Rohowsky - neu 2029
 *
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "NeuItem" => "<a href='VF_FA_BA_GR_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================

$VF_logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_lr = "fz_laderaum";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_lr);

$tabelle_lr_a = $tabelle_lr . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_fz_laderaum($tabelle_lr_a);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$select_lr = " WHERE lr_fzg = '$fz_id' ";
$sort_lr = " ORDER BY lr_id ";
$sql_lr = "SELECT * FROM `$tabelle_lr_a` $select_lr  $sort_lr ";

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
            'lr_id',
            'lr_fzg',
            'lr_raum',
            'lr_beschreibung',
            'lr_foto_1',
            'lr_komm_1'
        );
        break;
}

$Tabellen_Spalten_style['lr_id'] = 
$Tabellen_Spalten_style['lr_fzg'] = $Tabellen_Spalten_style['fz_foto_1'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';
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

List_Action_Bar($tabelle_lr_a,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_FA_BA_GR_Edit.php?ID=NeuItem' >Neu</a>";
}
List_Create($db, $sql_lr,'', $tabelle_lr_a,'', $New_Link); # die liste ausgeben

?>