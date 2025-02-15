<?php
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if ($_SESSION['VF_Prim']['mode'] == 'Single') { // Fixer Eigentümer
    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextSam" => "<a href='VF_FA_List.php?ID=NextSam' > andere Sammlung auswählen </a>",
        "NeuItem" => "<a href='VF_FA_FZ_Edit.php?ID=0' > Neuen Datensatz anlegen </a>"
    );
} else {
    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextEig" => "<a href='VF_FA_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NextSam" => "<a href='VF_FA_List.php?ID=NextSam' > andere Sammlung auswählen </a>",
        "NeuItem" => "<a href='VF_FA_FZ_Edit.php?ID=0' > Neuen Datensatz anlegen </a>"
    );
}


echo "<fieldset>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle .= "ma_fz_beschr_" . $_SESSION['Eigner']['eig_eigner'];

$tab_typ = "fz_fz_type_" . $_SESSION['Eigner']['eig_eigner'];
$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tab_typ);

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

switch ($T_List) {
    case "Alle":
        $Tabellen_Spalten = array(
        'fz_id',
        'fz_sammlg',
        'fz_name',
        'fz_taktbez',
        'fz_indienstst',
        'fz_ausdienst',
        'fz_herstell_fg',
        'fz_baujahr',
        'fz_bild_1'
            );
        
        break;
    default:
        $Tabellen_Spalten = array(
        'fz_id',
        'fz_invnr',
        'fz_sammlg',
        'fz_name',
        'fz_taktbez',
        'fz_indienstst',
        'fz_ausdienst',
        'fz_herstell_fg',
        'fz_baujahr',
        'fz_bild_1'
            );
}

$Tabellen_Spalten_style['fz_eignr'] =
$Tabellen_Spalten_style['fz_id'] = $Tabellen_Spalten_style['fz_baujahr'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":
        
        break;
        
    default:
        /*
         * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
         * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
         * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
         * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
         */
}
$List_Hinweise .= '</ul></li>';


$zus_ausw = "";

List_Action_Bar($tabelle,"Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================

$return = Cr_n_ma_fz_beschr($tabelle);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$sql = "SELECT * FROM $tabelle ";



$sql_where = "";
$orderBy = "  ";

if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
    if ($_SESSION[$module]['sammlung'] != "") {
        $sql_where = " WHERE fz_sammlg LIKE '%".$_SESSION[$module]['sammlung']."%' ";
    } else {
        $sql_where = ""; # " WHERE fz_sammlg LIKE '" . $_SESSION[$module]['sammlung'] . "%' ";
    }
    
}
$orderBy = "  ";


?>