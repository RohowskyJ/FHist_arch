<?php
/**
 * Foto Liste Details (Fotos je Aufnahmedatum), Suchliste
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_FO_List_Bericht.inc.php ist gestartet</pre>";}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (!isset($_SESSION[$module]['Fo']['URHEBER'])) {
    VF_Displ_Urheb_n($_SESSION['Eigner']['eig_eigner']);
}
if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
    $media = "Foto";
} else {
    $media = "Video";
}

$T_list_texte = array(
    "Alle" => "Alle " . $media . "s des Urhebers. ",
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = $media . "s des Urhebers " . $_SESSION['Eigner']['eig_eigner'] . " - " . $_SESSION['Eigner']['eig_verant'];

$header = "";
$logo = 'NEIN';
HTML_header($title, 'Auswahl', '', 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

$tabelle .= "_" . $_SESSION['Eigner']['eig_eigner'];
# echo "L 0133 tabelle $tabelle <br>";

# echo "$heading";
#
# List_Prolog($T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen
$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle,'MEM'); # lesen der Tabellen Spalten Informationen

if ($_SESSION[$module]['Fo']['FOTO']) {
    $Tabellen_Spalten = array(
        'fo_id',
        'fo_dsn',
        'in Bericht',
        'fo_begltxt',
        'fo_typ',
        'fo_media',
        'fo_uidaend',
        'fo_aenddat'
    );
    /*
    'fo_eigner',
    'fo_urheber',
            'fo_aufn_datum',
               
                    'fo_suchb',
    */
} elseif ($_SESSION[$module]['BERI']) {
    $Tabellen_Spalten = array(
        'fo_id',
        'fo_dsn',
        'Aktion',
        'fo_begltxt',
        'fo_suchb',
        'fo_typ',
        'fo_media',
        'fo_namen',
        'fo_aenddat'
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

List_Action_Bar($tabelle,$media . "s des Urhebers " . $_SESSION['Eigner']['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
$sql = "SELECT * FROM $tabelle ";

$sql_where = " WHERE fo_aufn_datum='" . $_SESSION[$module]['fo_aufn_d'] . "' ";
$orderBy = "  ";
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

$sql .= $sql_where . $orderBy;

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

if ($debug) {echo "<pre class=debug>VF_FO_List_Bericht.inc.php ist beendet</pre>";}

HTML_trailer();

?>
