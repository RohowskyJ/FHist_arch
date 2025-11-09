<?php

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */ 
$_SESSION[$module]['Inc_Arr'][] = 'VF_FZ_MaF_List.inc.php';

/**
 * einlesen Hersteller und Aufbauer in arrs
 */
$herst_arr = $aufb_arr = array();
$sql_a = "SELECT * FROM fh_firmen ORDER BY fi_name ASC  ";
$res_a = SQL_QUERY($db, $sql_a);
while ($row_a = mysqli_fetch_object($res_a)) {
    if ($row_a->fi_funkt == 'F') {
        $herst_arr[$row_a->fi_abk] = $row_a->fi_name.", ".$row_a->fi_ort;
    } else {
        $aufb_arr[$row_a->fi_abk] = $row_a->fi_name.", ".$row_a->fi_ort;
    }
}

/**
 * Einlesen der taktischen Bezeichnungen in taktb_arr
 */
$taktb_arr = array();
$sql_t = "SELECT * FROM fh_abk ORDER BY ab_abk  ASC ";
$res_t = SQL_QUERY($db, $sql_t);
while ($row_t = mysqli_fetch_object($res_t)) {
    $taktb_arr[$row_t->ab_abk] = $row_t->ab_bezeichn;
}

/**
 * Einlesen der Sammlungs- Kürzeln in arr
 */
$sam_arr = array();
$sql_s = "SELECT * FROM fh_sammlung ORDER BY sa_sammlg ";
$res_sa = SQL_QUERY($db, $sql_s);
while ($row_s = mysqli_fetch_object($res_sa)) {
    $sam_arr[$row_s->sa_sammlg] = $row_s->sa_name;
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten 
# ===========================================================================================
if ($_SESSION['VF_Prim']['mode'] == 'Single') { // Fixer Eigentümer
    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextSam" => "<a href='VF_FZ_MaFG_List.php?ID=NextSam' > andere Sammlung auswählen </a>"
    );
} else {
    $T_list_texte = array(
        "Alle" => "Nach Indienststellung. ",
        "NextEig" => "<a href='VF_FZ_MaFG_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
        "NextSam" => "<a href='VF_FZ_MaFG_List.php?ID=NextSam' > andere Sammlung auswählen </a>"
    );
}

$NeuRec = " &nbsp; &nbsp; &nbsp; <a href='VF_FZ_MaF_Edit.php?ID=0' > Neuen Datensatz anlegen </a>";

echo "<fieldset>";

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$tabelle .= $tabelle_f . $_SESSION['Eigner']['eig_eigner']; // ma_fahrzeug_

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen

switch ($T_List) {
    case "Alle":
        $Tabellen_Spalten = array(
        'fz_id',
        'fz_sammlg',
        'fz_taktbez',
        'fz_baujahr',
        'fz_herstell_fg',
        'fz_allg_beschr',
        'fz_bild_1'
            );

        break;
    default:
        $Tabellen_Spalten = array(
        'fz_id',
        'fz_sammlg',
        'fz_taktbez',
        'fz_baujahr',
        'fz_herstell_fg',
        'fz_allg_beschr',
        'fz_bild_1'
            );
}

$Tabellen_Spalten_style['fz_eignr'] =
$Tabellen_Spalten_style['fz_id'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li>';
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

List_Action_Bar($tabelle, "Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_name'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise, $zus_ausw); # Action Bar ausgeben

# ===========================================================================================================
# Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================

$return = Cr_n_ma_fahrzeug($tabelle);
if ($return != true) {
    echo "error: mysqli_errno($return)";
}

$sql_where = "";
$orderBy = "  ";

$sql = "SELECT * FROM `$tabelle` f \n
                    WHERE  f.fz_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";

$orderBy = "";
