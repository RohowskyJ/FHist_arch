<?php
/**
 * Foto Liste der für den Bericht ausgewählten Fotos
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FO_List_Ber_Det.inc ist gestarted</pre>";
}

$_SESSION['VF_LISTE']['SpaltenNamenAnzeige'] =  "Ein";

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
if (! isset($_SESSION[$module][$sub_mod])) {
    VF_Displ_Urheb_n($_SESSION[$module][$sub_mod]['eig_eigner']);
}

if ($_SESSION[$module]['URHEBER']['BE']['ei_media'] == "F") {
    $media = "Foto";
} else {
    $media = "Video";
}

$T_list_texte = array(
    "Alle" => "Alle " . $media . "s des Urhebers. ",
);
/*
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = $media . " für den Bericht";

$header = "";
$logo = 'NEIN';
HTML_header($title, 'Auswahl', '', '', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
*/
List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

 $Tabellen_Spalten = array('vd_flnr','vb_unter','vb_titel','fo_begltxt','vb_foto','vb_foto_Urheber');
 $Tabellen_Spalten_COMMENT = array('vd_flnr'=>'Fortl.Nr.','vb_flnr'=>'Berichts- Nummer','vb_unter'=>'Unterseite<br> Sortierung','vb_titel'=>'','md_beschreibg'=>'Beschreibung','vb_foto'=>'Foto','vb_foto_Urheber'=>'Urheber');

$Tabellen_Spalten_style['vb_flnr'] = $Tabellen_Spalten_style['md_eigner'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Foto - Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>'.'<li>Script: Vf_O_Fo_List_Ber_Det.inc</li>';

switch ($T_List) {
    case "Alle":

        break;

    default:

}
$List_Hinweise .= '</ul></li>';

$zus_ausw = "";

List_Action_Bar($tabelle,$media . "s des Urhebers " . $_SESSION[$module][$sub_mod]['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben
$sql = "SELECT * FROM $tabelle ";

$sql_where = " WHERE vb_flnr='" . $neu['vb_flnr'] . "' ";
$orderBy = " ORDER BY vb_flnr, vb_unter, vb_suffix ASC ";
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

$sql .= $sql_where . $orderBy;

$TabButton = "2|green|Bilder für den Bericht speichern.|VF_BE_List.php?Act=" . $_SESSION[$module]['Act']."|True"; # 0: phase, 1: Farbe, 2: Text, 3: Rücksprung-Link

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
HTML_trailer();

?>
