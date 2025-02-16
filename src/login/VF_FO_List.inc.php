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
 * Feststellen der Urheber- Nummer bei Organisation
 * $_SESSION[$module]['URHEBER'][$urh_nr] = Eigentümer- Nummer
 * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_media'] = Media Kennung A,F,I,V  Audio, Foto, Film, Video
 * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_fotograf'] = Name der Org (Verfüger) oder Name des Urhebers
 * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_urh_kurzz'] = Urheber- Kennzeichen, wenn kein urh_kenzz ausgewählt ist
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk'] array
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urhnr'] = Eigentümer- Nummer des Urhebers (wenn <> $urh_nr, diese nutzen)
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_fotograf'] = Name des Urhebers für Anzeige bei Bild
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urh_kurzz'] = Kennzeichen des Urhebers (für Dateinamens- Beginn)
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_typ'] = für die Zuordnung im Archiv
 * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_verz'] = für ein Verzeichnis
 * 
 * $_SESSION[$module]['URHEBER']['Media]['urh_nr']= array(['urh_nr]['type']['kurzz']['fotogr']['verz'])
 */
$eignr = $_SESSION['Eigner']['eig_eigner'];

$_SESSION[$module]['URHEBER']['Media']['urh_nr'] = $eignr;
if (strlen($_SESSION[$module]['URHEBER'][$eignr]['ei_media']) == 1) {
    $typ = $_SESSION[$module]['URHEBER'][$eignr]['ei_media'];
} else {
    $t_a = explode(",",$_SESSION[$module]['URHEBER'][$eignr]['ei_media']);
    $typ = $t_a[0];
}
$_SESSION[$module]['URHEBER']['Media']['urh_nr'] = array('ei_id'=>$eignr,'type'=> $typ,'kurzz'=>$_SESSION[$module]['URHEBER'][$eignr]['ei_urh_kurzz'],
    'fotogr'=>$_SESSION[$module]['URHEBER'][$eignr]['ei_fotograf'],'verz'=> "");

# var_dump($_SESSION[$module]['URHEBER']['Media']['urh_nr']);
if (isset($_SESSION[$module]['URHEBER'][$eignr]['urh_abk'] )) { // definierte Erweiterungen
    if (isset($_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urhnr']) &&
            $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urhnr'] != $_SESSION[$module]['URHEBER'][$urh_nr]  ) {
                $_SESSION[$module]['URHEBER']['Media']['urh_nr'] = array('ei_id'=>$_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urhnr'],'type'=> $type,'kurzz'=>$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_urh_kurzz'],
                'fotogr'=>$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fs_fotograf'],'verz'=> $_SESSION[$module]['URHEBER'][$eigner]['urh_abk']['fs_verz']); 
        }
}     
# var_dump($_SESSION[$module]['URHEBER']['Media']['urh_nr']);
$ur_media = $_SESSION[$module]['URHEBER']['Media']['urh_nr']['type'];

$tabelle .= "_" . $eignr;
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
