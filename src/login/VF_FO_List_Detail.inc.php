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
/*
if (! isset($_SESSION[$module]['URHEBER'])) {
    VF_Z_U_Sel_List($_SESSION[$module]['URHEBER']['fm_eigner']);
}
*/
$eignr = $_SESSION['Eigner']['eig_eigner'];
# echo "L 019 eignr $eignr <br>";
if ($_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ'] == "F") {
    $media = "Foto";
} else {
    $media = "Video";
}

$fo_aufn_d = $_SESSION[$module]['fo_aufn_d'];
$T_list_texte = array(
    "Alle" => "Alle " . $media . "s des Urhebers. ",
    "NeuItem" => "<a href='VF_FO_Edit.php?fo_id=0&verz=N&fo_aufn_d=$fo_aufn_d' > Neues $media anlegen </a>"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = $media . "s des Urhebers " . $_SESSION['Eigner']['eig_eigner'] . " - " . $_SESSION['Eigner']['eig_verant'];

$header = "";

BA_HTML_header($title, '', 'Admin', '180em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# echo "$heading";
#
echo "<fieldset>";
if (!$reply) {
    echo "$reply <br>";
}

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle .= "_" . $_SESSION['Eigner']['eig_eigner'];

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

if ($_SESSION[$module]['FOTO']) {
    $Tabellen_Spalten = array(
        'fo_id',
        'fo_dsn',
        'fo_aufn_datum',
        'fo_begltxt',
        'fo_namen',
        'fo_typ',
        'fo_media'
    ); 
    $Tabellen_Spalten_COMMENT['fo_namen'] = "Namen, Suchbegriffe";
} elseif ($_SESSION[$module]['Fo']['BERI']) {
    $Tabellen_Spalten = array(
        'fo_id',
        'fo_dsn',
        'Aktion',
        'fo_begltxt',
        'fo_typ',
        'fo_media',
        'fo_namen',
        'fo_aenddat'
    );
    $Tabellen_Spalten_COMMENT['Aktion'] = "Sortierung Auswahl";
}

$Tabellen_Spalten_style['fo_id'] = $Tabellen_Spalten_style['fo_eigner'] = 'text-align:center;';

$return = Cr_n_fo_daten($tabelle);
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

List_Action_Bar($tabelle,$media . "s des Urhebers " . $_SESSION['Eigner']['eig_eigner'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

$sql = "SELECT * FROM $tabelle ";


$sql_where = " WHERE fo_aufn_datum='" . $_SESSION[$module]['fo_aufn_d'] . "' AND fo_aufn_suff ='" . $_SESSION[$module]['fo_aufn_s'] . "' "; #  AND fo_basepath = '$basepath'  AND fo_zus_pfad = '$zus_pfad'
$orderBy = "  ";
if (isset($_SESSION[$module]['select_string']) and $_SESSION[$module]['select_string'] != '') {
    $select_string = $_SESSION[$module]['select_string'];
}

# var_dump($_SESSION[$module]['URHEBER'][$eignr]);
$sql .= $sql_where . $orderBy;

echo "<p>Wo keine Bilder angezeigt werden, sind sie aus Platzgründen nicht auf dem Server. Die angezeigten Bilder werden von Berichten benutzt..</p>";

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben
echo "</fieldset>";

BA_HTML_trailer();

?>
