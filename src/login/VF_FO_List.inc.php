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

$Inc_Arr[] = "VF_FO_List.inc.php";

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "Alle" => "Alle Fotos des Urhebers. ",
    "NextEig" => "<a href='VF_FO_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
    "NeuItem" => "<a href='VF_FO_Edit.php?md_id=0&verz=J' > Neues Verzeichnis anlegen </a>"
);

$eignr = $_SESSION['Eigner']['eig_eigner'];

$tabelle .=  $eignr;
echo "<fieldset>";
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen
$Tabellen_Spalten = array(
    'md_id',
    'md_dsn_1',
    'md_aufn_datum',
    'md_beschreibg',
    'md_suchb',
    'md_media' 
);
$Tabellen_Spalten_COMMENT['fo_basepath'] = "Pfad zu den Fotos";

$Tabellen_Spalten_style['md_eigner'] = $Tabellen_Spalten_style['md_id'] = $Tabellen_Spalten_style['fz_baujahr'] = 'text-align:center;';

$return = Cr_n_Medien_Daten($tabelle);
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

$sql_where = " WHERE md_dsn_1 = '' ";

$orderBy = " ORDER BY md_aufn_datum  ASC  "; # fo_id
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

$sql .= $sql_where . $orderBy;
#echo "L 0101 sql $sql <br>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
echo "</fieldset>";

if ($debug) {
    echo "<pre class=debug>VF_FO_List.inc.php ist beendet</pre>";
}

?>
