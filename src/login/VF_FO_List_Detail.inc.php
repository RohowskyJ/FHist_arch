<?php
/**
 * Foto Liste Details (Fotos je Aufnahmedatum), Suchliste
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$urheb = $_SESSION[$module][$sub_mod]['eig_eigner'];

$md_aufn_d = $_SESSION[$module]['md_aufn_d'];
$T_list_texte = array(
    "Alle" => "Alle Medien des Urhebers. "
);

# ===========================================================================================================
# Header ausgeben
# ===========================================================================================================
$title = "medien des Urhebers " . $_SESSION[$module][$sub_mod]['eig_eigner'] . " - " . $_SESSION[$module][$sub_mod]['eig_verant'];

$header = "";

BA_HTML_header($title, '', 'Admin', '180em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# echo "$heading";
#
/*
echo "<fieldset>";
if (!$reply) {
    echo "$reply <br>";
}
*/
$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_FO_Edit.php?md_id=0&verz=N&md_aufn_d=$md_aufn_d' > Neues Medium anlegen </a>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle .= $_SESSION[$module][$sub_mod]['eig_eigner'];

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

if ($_SESSION[$module]['Foto']) {
    $Tabellen_Spalten = array(
        'md_id',
        'md_dsn_1',
        'md_aufn_datum',
        'md_beschreibg',
        'md_namen',
        'md_media'
    ); 
    $Tabellen_Spalten_COMMENT['md_namen'] = "Namen, Suchbegriffe";
} elseif ($_SESSION[$module]['Fo']['BERI']) {
    $Tabellen_Spalten = array(
        'md_id',
        'md_dsn_1',
        'Aktion',
        'md_beschreibg',
        'md_namen',
        'md_aenddat'
    );
    $Tabellen_Spalten_COMMENT['Aktion'] = "Sortierung Auswahl";
}

$Tabellen_Spalten_style['md_id'] = $Tabellen_Spalten_style['md_eigner'] = 'text-align:center;';

$return = Cr_n_Medien_Daten($tabelle);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Foto - Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>';

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

List_Action_Bar($tabelle,"Medien des Urhebers " . $_SESSION[$module][$sub_mod]['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

$sql = "SELECT * FROM $tabelle ";


$sql_where = " WHERE md_aufn_datum='" . $_SESSION[$module]['md_aufn_d'] . "' "; #  AND fo_basepath = '$basepath'  AND fo_zus_pfad = '$zus_pfad'
$orderBy = "  ";
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

# var_dump($_SESSION[$module]['URHEBER'][$eignr]);
$sql .= $sql_where . $orderBy;

echo "<p>Wo keine Bilder angezeigt werden, sind sie aus Platzgründen nicht auf dem Server. Die angezeigten Bilder werden von Berichten benutzt..</p>";

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>FO List Detail vor list_create $sql </pre>";
echo "</div>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
echo "</fieldset>";

BA_HTML_trailer();

?>
