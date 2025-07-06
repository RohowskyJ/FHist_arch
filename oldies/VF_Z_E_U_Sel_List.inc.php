<?php
/**
 * Foto Urheber select Liste
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_E_U_Sel_List.inc.php ist gestarted</pre>";
}

$tabelle_m = 'fh_eigentuemer';

/**
 * Pfad zum Root- Verzeichnis, wird abgelöst
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

unset($_SESSION[$module]['URHEBER']);
unset($_SESSION[$module]['Fo']);
unset($_SESSION[$module]['UP_Parm']);

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    'Aktive' => "Aktive Einträge (Urh-Kurzz ! leer)",
);
##      "Alle" => "Foto- Urheber ( Auswahl ) ",   ## "NeuItem" => "<a href='VF_Z_E_U_Ed_Su.php?ID=0' >Neuen Eigentümer eingeben</a>"
List_Prolog($tabelle_m,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_m);

$Tabellen_Spalten = array(
    'ei_id',
    'ei_name',
    'ei_urh_kurzz',
    'ei_media',
    'Urh_Erw'
); 

$Tabellen_Spalten_style['fm_id'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fm_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle,$title, $T_list_texte, $T_List, $List_Hinweise,''); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
$act_datum = date("Y-m-d");
$sql_where = $orderBy = "";
$sql = "SELECT * FROM $tabelle_m ";

$sql_where = " WHERE ei_urh_kurzz <> '' ";

$sql .= $sql_where . $orderBy;

# ===========================================================================================================
# Die Daten lesen und Ausgeben
# ===========================================================================================================
$TabButton = '1|green|weiter||1';
# echo "$Err_msg<br>";
List_Create($db, $sql,'', $tabelle_m,''); # die liste ausgeben

if ($debug) {
    echo "<pre class=debug>VF_Z_E_U_Sel_List.inc.php ist beendet</pre>";
}

?>