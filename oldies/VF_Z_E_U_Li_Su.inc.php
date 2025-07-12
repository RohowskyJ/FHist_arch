<?php

/**
 * Liste Foto Urheber, Details
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
$T_list_texte = array(
    "Alle" => "Alle Daten anzeigen",
    "NeuItem" => "<a href='VF_FO_U_Ed_Su.php?ID=0' >Neuen Datensatz eingeben</a>"
);

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle = "fh_urh_erw_n";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

$fm_flnr = $neu['fm_id'];

$select = " WHERE `fs_fm_id` = '$fm_id' ";
$sort = " ORDER BY fs_fm_id ASC";
$sql = "SELECT * FROM `$tabelle` $select  $sort ";

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
            'fs_flnr',
            'fs_fm_id',
            'fs_eigner',
            'fs_fotograf',
            'fs_urh_kurzz'
        );
        break;
}

$Tabellen_Spalten_style['fs_flnr'] = $Tabellen_Spalten_style['fs_fm_id'] = $Tabellen_Spalten_style['fs_eingner'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_eign_id</q> Klicken.</li>' . '<li>Script: VF_O_FO_M_Lis_Su.inc</li>';
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

List_Action_Bar($tabelle,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================

$New_Link = "";
if ($_SESSION[$module]['all_upd']) {
    $New_Link = "<a href='VF_FO_U_Ed_Su.php?ID=0' >Neu</a>";
}
List_Create($db, $sql,'', $tabelle,'', $New_Link); # die liste ausgeben

?>