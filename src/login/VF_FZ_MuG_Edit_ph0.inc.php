<?php

/**
 * Liste der Geräte aines Eigentümers, Wartug, Formular
 *
 * @author Josef Rohowsky  neu 2019
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FZ_MuG_Edit_ph0.inc.php ist gestarted</pre>";
}
if ($neu['mg_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}


echo "<input type='hidden' id='mg_id' name='mg_id' value='".$neu['mg_id']."' >";
echo "<input type='hidden' id='mg_id' name='mg_invnr' value='".$neu['mg_invnr']."' >";
# =========================================================================================================
Edit_Tabellen_Header('Daten von '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('mg_id');
Edit_Daten_Feld('mg_eignr');
# Edit_Daten_Feld('mg_invnr');

# =========================================================================================================
$button = "";
if ($hide_area != 0) {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleBlock10' > zum anzeigen/ändern anklicken</label> ";
}
Edit_Separator_Zeile('Sammlung'.$button);
# =========================================================================================================

echo "<input type='hidden' name='mg_sammlg' value='".$neu['mg_sammlg']."'/>";

// $Edit_Funcs_Protect = True;
Edit_Daten_Feld('mg_sammlg', '30');
// $Edit_Funcs_Protect = False;
Edit_Daten_Feld('sa_name');

if ($hide_area == 0 || mb_strlen($neu['mg_sammlg']) <= 4) {
    echo "<div>";
} else {
    echo "<div class='block-container' >";
    echo "<div class='toggle-block' id='block10'>";
}

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt jquery
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
        $MS_Init = VF_Sel_SA_MU_G; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
        /*
         case 2:
         $in_val = '07';
         $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
         break;
         */
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val, $titel);

echo "</div>"; # ende toggle
echo " </div>";

# =========================================================================================================
Edit_Separator_Zeile('Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('mg_bezeich', 100);
Edit_Daten_Feld('mg_type', 60);
Edit_Daten_Feld('mg_indienst', 10, 'Datum oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('mg_ausdienst', 10, 'Datum oder zumindest Jahr der Ausserdienst- Stellung');
Edit_textarea_Feld('mg_komment');

Edit_Daten_Feld('mg_herst', 60);
Edit_Daten_Feld('mg_baujahr', 10);
Edit_Daten_Feld('mg_gew', 6);

Edit_Select_Feld(Prefix . 'mg_zustand', VF_Zustand, '');

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
$checkbox_f = "<label> &nbsp; &nbsp; <input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Fotos',$checkbox_f);  #
# =========================================================================================================

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='mg_foto_1' value='" . $neu['mg_foto_1'] . "'>";
echo "<input type='hidden' name='mg_foto_2' value='" . $neu['mg_foto_2'] . "'>";
echo "<input type='hidden' name='mg_foto_3' value='" . $neu['mg_foto_3'] . "'>";
echo "<input type='hidden' name='mg_foto_4' value='" . $neu['mg_foto_4'] . "'>";

echo "<input type='hidden' id='sammlung' value='".$neu['mg_sammlg'] ."'>";
echo "<input type='hidden' id='eigner' value='".$neu['mg_eignr'] ."'>";

echo "<input type='hidden' id='aOrd' value=''>";
echo "<input type='hidden' id='urhNr' value=''>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuG/";

$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'mg_komm_'.$i, 'bi' => 'mg_foto_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    $i++;
}

VF_Upload_Form_M();


# =========================================================================================================
Edit_Separator_Zeile('Lagerort');
# =========================================================================================================

Edit_Daten_Feld('mg_fzg', 10);
Edit_Daten_Feld('mg_raum', 60);
Edit_Daten_Feld('mg_ort', 60);

# =========================================================================================================
Edit_Separator_Zeile('Daten geprüft');
# =========================================================================================================

Edit_Daten_Feld('mg_pruef_id', 10);
Edit_Daten_Feld('mg_pruef_dat', 10);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('mg_uidaend');
Edit_Daten_Feld('mg_aenddat');
# =========================================================================================================

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FZ_MuFG_List.php?ID=MU_G'>Zurück zur Liste</a></p>"; # ."

echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/VF_toggle.js' ></script>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MuG_Edit_ph0.inc.php beendet</pre>";
}
