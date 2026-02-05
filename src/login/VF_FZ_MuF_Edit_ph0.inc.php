<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Formular
 *
 * @author Josef Rohowsky  neu 2019
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FMZ_MuF_Edit_ph0.inc.php ist gestarted</pre>";
}

$_SESSION[$module]['Inc_Arr'][] = "VF_FZ_MuF_Edit_ph0.inc.php";

$dataSetAct = "";
if ($neu['fm_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
    $dataSetAct = "data-active-index='false'";
}

/** alle <input und <textara Felder werden als readonly gesetzt */
if ($_SESSION[$module]['all_upd'] == '0' ){
    $readOnly = 'readonly';
}

echo "<input type='hidden' id='recId' name='fm_id' value='".$neu['fm_id']."' >";
echo "<input type='hidden' id='recEigner' name='fm_eignr' value='".$_SESSION['Eigner']['eig_eigner']."' >";
echo "<input type='hidden' id='allUpd' name='allUpd' value='".$_SESSION[$module]['all_upd']."' >";

# =========================================================================================================
Edit_Tabellen_Header('Gerätebeschreibung Eigentümer: '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fm_id');
Edit_Daten_Feld('fm_eignr');
# Edit_Daten_Feld('fm_invnr');


echo "<input type='hidden' id='sammlung'  name='fm_sammlg' value='".$neu['fm_sammlg']."'/>";

# =========================================================================================================
// accordion für Sammlung
echo "<ul id='sa-accordion' class='accordionjs' $dataSetAct >"; // sa- für Sammlung
echo "<li>";
echo "<div>";
$readOnly = 'readonly'; 
Edit_Daten_Feld_Button('fm_sammlg', '20', '', '');
Edit_Daten_Feld('sa_name', '');
if ($_SESSION[$module]['all_upd'] == '1' ){
    $readOnly = '';
}
echo "Zum Ändern / Suchen - hier anklicken";
echo "</div>";
echo "<div>";
# =========================================================================================================

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
    'Auswahl der Sammlungs- Type (1. Ebene)',
    'Auswahl der Sammlungs- Gruppe (2. Ebene)',
    'Auswahl der Untergrupppe (3. Ebene)',
    'Auswahl des Spezifikation (4. Ebene) '
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_MU_F; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val, $titel);

echo "</div>";
echo "</li>";
echo "</ul>";
// Ende Acccordion für Sammlung

# =========================================================================================================
Edit_Separator_Zeile('Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fm_bezeich', 70);
Edit_Daten_Feld('fm_type', 60);
Edit_Daten_Feld('fm_indienst', 10, 'Datum oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fm_ausdienst', 10, 'Datum oder zumindest Jahr der Ausserdienst- Stellung');
Edit_textarea_Feld('fm_komment');

Edit_Daten_Feld('fm_leistung', 10);
Edit_Daten_Feld('fm_lei_bed', 50);

if (isset($neu['fi_name']) && $neu['fi_name'] != "") {
    $neu['fm_herst'] .= " - ".$neu['fi_name'].", ".$neu['fi_ort'];
}

$checkbox = "";
if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt
    $checkbox = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleBlock30' > zum anzeigen/ändern anklicken</label> ";
}

echo "<input type='hidden' name='fm_herst' value='".$neu['fm_herst']."' >";
//$Edit_Funcs_Protect = True;
Edit_Daten_Feld(Prefix . 'fm_herst', 60, '', '', $checkbox);
//$Edit_Funcs_Protect = False;
# Edit_Daten_Feld('fm_herst', 60);
if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div class='block-container' >";
    echo "<div class='toggle-block' id='block30'>";
}
VF_Auto_Herstell();

echo "</div>"; # ende toggle
echo "</div>"; # ende hide herstb

Edit_Daten_Feld('fm_baujahr', 10);
Edit_Select_Feld(Prefix . 'fm_zustand', VF_Zustand, '');
Edit_Daten_Feld('fm_fgstnr', 60);

Edit_Daten_Feld('fm_gew', 10);

Edit_Daten_Feld('fm_zug', 10);

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
$checkbox_f = "<a href='#' class='toggle-string' data-toggle-group='1'>Foto Daten eingeben/ändern</a>";
Edit_Separator_Zeile('Fotos',$checkbox_f);  #
# =========================================================================================================

echo "<input type='hidden' name='fm_foto_1' value='" . $neu['fm_foto_1'] . "'>";
echo "<input type='hidden' name='fm_foto_2' value='" . $neu['fm_foto_2'] . "'>";
echo "<input type='hidden' name='fm_foto_3' value='" . $neu['fm_foto_3'] . "'>";
echo "<input type='hidden' name='fm_foto_4' value='" . $neu['fm_foto_4'] . "'>";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";

echo "<input type='hidden' id='sammlung' value='".$neu['fm_sammlg'] ."'>";
echo "<input type='hidden' id='eigner' value='".$neu['fm_eignr'] ."'>";

echo "<input type='hidden' id='aOrd' value=''>";
echo "<input type='hidden' id='urhNr' value=''>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuF/";

$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;

$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'fm_komm_'.$i, 'bi' => 'fm_foto_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    $i++;
}

VF_Upload_Form_M();

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fm_uidaend');
Edit_Daten_Feld('fm_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    # Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FZ_MuFG_List.php?ID=MU_F'>Zurück zur Liste</a></p>";


# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MuF_Edit_ph0.inc.php beendet</pre>";
}
