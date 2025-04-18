<?php
/**
 * Foto- Verwaltung, Auswahlliste , Foto-Auswahl
 *
 *
 * @author J. Rohowsky  - neu 2018
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FO_List.inc.php ist gestarted</pre>";
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "Alle" => "Alle Fotos des Urhebers. ",
    "NextEig" => "<a href='VF_FO_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
    "NeuItem" => "<a href='VF_FO_Edit.php?fo_id=0&verz=J' > Neues Verzeichnis anlegen </a>"
);

/**
 * Neue Einteilung der Sess Var
 *
 * $_SESSION[$module]['URHEBER'][$eigner] = $ei_id;
 * $_SESSION[$module]['URHEBER'][$eigner]['ei_media'] = $ei_media
 * $_SESSION[$module]['URHEBER'][$eigner]['ei_fotograf'] = Privat: Titel Name Vorname , andere: Org_Typ OrgName
 * $_SESSION[$module]['URHEBER'][$eigner]['ei_urh_kurzz'] = $ei_urh_kurzz
 *
 * $_SESSION[$module]['URHEBER'][$eigner]['Media']['typ'] = ei_media wenn einstellig oder fs_typ
 * $_SESSION[$module]['URHEBER'][$eigner]['Media']['kurzz'] = ei_urh_kurzz oder fs_urh_kurzz
 * $_SESSION[$module]['URHEBER'][$eigner]['Media']['fotogr'] = ei_fotograf oder fs_fotograf
 * $_SESSION[$module]['URHEBER'][$eigner]['Media']['urh_nr'] = ei_id oder fs_urh_nr
 * $_SESSION[$module]['URHEBER'][$eigner]['Media']['verz'] = fs_urh_verzeich
 *
 */

$eignr = $_SESSION['Eigner']['eig_eigner'];

$_SESSION[$module]['URHEBER'][$eignr]['Media']['urh_nr'] = $eignr;
if (strlen($_SESSION[$module]['URHEBER'][$eignr]['Media']['typ']) == 1) {
    $typ = $_SESSION[$module]['URHEBER'][$eignr]['Media']['typ'];
} else {
    $t_a = explode(",",$_SESSION[$module]['URHEBER'][$eignr]['Media']['typ']);
    $typ = $t_a[0];
}
    
$ur_media = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ'];

$tabelle .= "_" . $eignr;
echo "<fieldset>";
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen
$Tabellen_Spalten = array(
    'fo_id',
    'fo_dsn',
    'fo_aufn_datum',
    'fo_begltxt',
    'fo_suchb',
    'fo_media' 
);
$Tabellen_Spalten_COMMENT['fo_basepath'] = "Pfad zu den Fotos";

$Tabellen_Spalten_style['fo_eigner'] = $Tabellen_Spalten_style['fo_id'] = $Tabellen_Spalten_style['fz_baujahr'] = 'text-align:center;';

$return = Cr_n_fo_daten($tabelle);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Foto - Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';

switch ($T_List) {
    case "Alle":

        break;

    default:
}
$List_Hinweise .= '</ul></li>';

$zus_ausw = "";

List_Action_Bar($tabelle,"Fotos/Videos des Urhebers " . $_SESSION['Eigner']['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

$sql_where = $sql_order = "";

$sql = "SELECT * FROM $tabelle ";

$sql_where = " WHERE fo_dsn = '' ";

$orderBy = " ORDER BY fo_aufn_datum  ASC  "; # fo_id
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

$sql .= $sql_where . $orderBy;

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
echo "</fieldset>";

if ($debug) {
    echo "<pre class=debug>VF_FO_List.inc.php ist beendet</pre>";
}

?>
