<?php
/** 
 * Ausgabe der Liste der Muskelbetriebenen Geräte
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

$tabelle = "mu_geraet_".$_SESSION['Eigner']['eig_eigner'];

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle); # lesen der Tabellen Spalten Informationen 

$T_list_texte = array(
    "Alle" => "Nach Indienststellung. ",
    "NextEig" => "<a href='VF_FM_List.php?ID=NextEig' > anderen Eigentümer auswählen </a>",
    "NextSam" => "<a href='VF_FM_List.php?ID=NextSam' > andere Sammlung auswählen </a>",
    "NeuItem" => "<a href='VF_FM_GE_Edit.php?mg_id=0' target='neu' > Neuen Datensatz anlegen </a>"
);

echo "<fieldset>";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Daten ändern: Auf die Zahl in Spalte <q>*_id</q> Klicken.</li>';
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

$eig_data = VF_Displ_Eig($_SESSION['Eigner']['eig_eigner']);

List_Action_Bar($tabelle,"Fahrzeuge des Eigentümers " . $_SESSION['Eigner']['eig_eigner'] . " " . $_SESSION['Eigner']['eig_org'] . ", " . $_SESSION['Eigner']['eig_verant'], $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

# ===========================================================================================================
# Die Sammlungs- Auswahl anzeigen:
# ===========================================================================================================

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
 *
 *
 * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
 * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
 * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
 *
 * @Input-Parm $_POST['Level1...6']
 */

$MS_Lvl   = 4; # 1 ... 6
$MS_Opt   = 1; # 1: SA für Sammlung, 2: AO für Archivordnung

$MS_Txt = array(
    'Auswahl der Sammlungs- Type (1. Ebene) &nbsp;  ',
    'Auswahl der Sammlungs- Gruppe (2. Ebene) &nbsp; ',
    'Auswahl der Untergrupppe (3. Ebene) &nbsp; ',
    'Auswahl der Spezifikation (4. Ebene) &nbsp; '
);

switch ($MS_Opt) {
    case 1:
        $in_val = 'MA_G';
        $MS_Init = VF_Sel_SA_MU_G; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
        /*
         case 2:
         $in_val = '07';
         $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
         break;
         */
}

$titel  = 'Welche Sammlung soll angezeigt werden: ';
VF_Multi_Dropdown($in_val,$titel);

$return = Cr_n_mu_geraet($tabelle);
if ($return != True) {
    echo "$tabelle error: mysqli_errno($return)";
}

$sql = "SELECT * FROM $tabelle ";
$sql_where = "";
$orderBy = "";

$Tabellen_Spalten = array(
    'mg_id',
    'mg_bezeich',
    'mg_type',
    'mg_indienst',
    'mg_ausdienst',
    'mg_herst',
    'mg_baujahr',
    'mg_sammlg',
    'mg_foto_1'
);
$Tabellen_Spalten_style['mg_eignr'] = $Tabellen_Spalten_style['mg_id'] = $Tabellen_Spalten_style['mg_baujahr'] = 'text-align:center;';
# dzt nicht $sql_where=" WHERE fm_sammlg = '".$_SESSION[$module]['sammlung']."' " ;
$sql_where = "";
if ($_SESSION[$module]['sammlung'] != "") {
    $sql_where = " WHERE mg_sammlg LIKE '%".$_SESSION[$module]['sammlung']."%' ";
}

$orderBy = "  ";
