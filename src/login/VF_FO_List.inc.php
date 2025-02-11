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

# VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);
# VF_Displ_Urheb_n($_SESSION['Eigner']['eig_eigner']);

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$T_list_texte = array(
    "Alle" => "Alle Fotos des Urhebers. ",
    "NextEig" => "<a href='VF_FO_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
    "NeuItem" => "<a href='VF_FO_Edit.php?fo_id=0&verz=J' > Neues Verzeichnis anlegen </a>"
);

/**
 * Feststellen der Urheber- Nummer bei Organisation
 * $_SESSION[$module]['URHEBER'][$urh_nr] = Eigentümer- Nummer
 * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_media'] = Media Kennung A,F,I,V  Audio, Foto, Film, Video
 * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_fotograf'] = Name der Org (Verfüger) oder Name des Urhebers
 * $_SESSION[$module]['URHEBER'][$urh_nr][ei_urh_kurzz'] = Urheber- Kennzeichen, wenn kein urh_kenzz ausgewählt ist
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk'] array
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urhnr'] = Eigentümer- Nummer des Urhebers (wenn <> $urh_nr, diese nutzen)
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_fotograf'] = Name des Urhebers für Anzeige bei Bild
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urh_kurzz'] = Kennzeichen des Urhebers (für Dateinamens- Beginn)
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_typ'] = für die Zuordnung im Archiv
 */
$eignr = $_SESSION['Eigner']['eig_eigner'];
/*
if (isset($_SESSION[$module]['URHEBER'][$eignr]['urh_abk'])) {  // erweiterte Daten vorhanden
    
} else {
    $_SESSION[$module]['URHEBER'][$eignr]['urh_abk'] = array();
    $_SESSION[$module]['URHEBER'][$row->ei_id]['urh_abk'] =
    array('fs_urh_nr' => $_SESSION[$module]['URHEBER'][$eignr],'fs_fotograf'=>  $ei_fotograf,
        'fs_urh_kurzz'=> $ei_urh_kurzzz ,'fs_typ'=> $ei_media);
}
*/
if (isset($_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urhnr']) && 
        $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urhnr'] == $_SESSION[$module]['URHEBER'][$urh_nr]  ) {
    $urh_nr = $_SESSION[$module]['URHEBER'][$eignr];
    $ur_media = $_SESSION[$module]['URHEBER'][$eignr]['ei_media'];
} else {
    if (isset($_SESSION[$module]['URHEBER'][$eignr]['urh_abk'])) {
        $urh_nr = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urh_nr'];
        $ur_media = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_typ'];
    }   
}
$_SESSION[$module]['URHEBER']['ur_media'] = $ur_media;

$tabelle .= "_" . $urh_nr;
echo "<fieldset>";
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen
$Tabellen_Spalten = array(
    'fo_id',
    'fo_dsn',
    'fo_aufn_datum',
    'fo_basepath',
    'fo_begltxt',
    'fo_suchb',
    'fo_typ',
    'fo_media',
    'fo_aenddat'
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
