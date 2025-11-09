<?php
/**
 * Foto Liste Details (Fotos je Aufnahmedatum), Suchliste
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_FO_List_Bericht.inc.php";

if ($debug) {echo "<pre class=debug>VF_FO_List_Bericht.inc.php ist gestartet</pre>";}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (!isset($_SESSION[$module][$sub_mod]['eig_eigner'])) {
    VF_Displ_Urheb($_SESSION[$module][$sub_mod]['eig_eigner'], 'U');
}
var_dump($_SESSION[$module]['Urheber']);
$urheb = $_SESSION[$module][$sub_mod]['eig_eigner'];

$T_list_texte = array(
    "Alle" => "Alle Medien der Urheber. ",
);

$tabelle .= "_" . $_SESSION[$module][$sub_mod]['eig_eigner'];
# echo "L 0133 tabelle $tabelle <br>";

# echo "$heading";

$NeuRec = '';
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen
$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle,'MEM'); # lesen der Tabellen Spalten Informationen

if ($_SESSION[$module]['Fo']['FOTO']) {
    $Tabellen_Spalten = array(
        'md_id',
        'md_dsn_1',
        'md beschreibg',
        'mdfo_media'
    );
    /*
    'fo_eigner',
    'fo_urheber',
            'fo_aufn_datum',
               
                    'fo_suchb',
    */
} elseif ($_SESSION[$module][$sub_mod]) {
    $Tabellen_Spalten = array(
        'md_id',
        'md_dsn_1',
        'Aktion',
        'md beschreibg',
        'md_media'
    );
    $Tabellen_Spalten_COMMENT['Aktion'] = "Sortierung Auswahl";
}

$Tabellen_Spalten_style['fo_id'] = $Tabellen_Spalten_style['fo_eigner'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Foto - Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>'.'<li>Script: Vf_O_Fo_List_Bericht.inc</li>' ;

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

List_Action_Bar($tabelle,$media . "s des Urhebers " . $_SESSION[$module][$sub_mod]['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
$sql = "SELECT * FROM $tabelle ";

$sql_where = " WHERE fo_aufn_datum='" . $_SESSION[$module]['fo_aufn_d'] . "' ";
$orderBy = "  ";
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

$sql .= $sql_where . $orderBy;

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO List vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

if ($debug) {echo "<pre class=debug>VF_FO_List_Bericht.inc.php ist beendet</pre>";}

?>
