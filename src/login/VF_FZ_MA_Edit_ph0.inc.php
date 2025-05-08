<?php

/**
 * Fahrzeug- Liste, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph0.inc.php gestartet </pre>";
}
echo "<input type='hidden' name='fz_id' value='$fz_id'/>";

if ($neu['fz_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

# =========================================================================================================
Edit_Tabellen_Header('Motorisierte Fahrzeuge von Eigentümer '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fz_id');
Edit_Daten_Feld('fz_eignr');

# =========================================================================================================
Edit_Separator_Zeile('Fahrzeug/Geräte- Beschreibung');
# =========================================================================================================
$taktbez = "";
if ($neu['ab_bezeichn'] != "")    {
    $taktbez = $neu['ab_bezeichn'];
    $neu['fz_taktbez'] .= " - $taktbez"; 
}

if ($hide_area == 0) { // Hide Taktbez
    $button = "";
} else {
    $button = " &nbsp;  <button type='button' class='button-sm' onclick=\"document.getElementById('dprdown_taktb').style.display='block'\">zum anzeigen/ändern klicken!</button>";
}
Edit_Daten_Feld_Button('fz_taktbez',40,'','',$button);

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_taktb' style='display:none'>";
}
BA_Auto_Taktb($neu['fz_taktbez']);

echo "</div>"; # ende hide taktb

Edit_Daten_Feld('fz_hist_bezeichng', 50);

$opt_aera = VF_FZG_Aera; 
Edit_Select_Feld(Prefix . 'fz_zeitraum', $opt_aera, '');

Edit_textarea_Feld(Prefix . 'fz_allg_beschr');

Edit_textarea_Feld(Prefix . 'fz_beschreibg_det');

Edit_Daten_Feld('fz_indienstst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fz_ausdienst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Ausserdienst- Stellung');

Edit_Select_Feld(Prefix . 'fz_zustand', VF_Zustand, '');

if ($hide_area == 0) { // Hide Hersteller
    $button = "";
} else {
    $button = " &nbsp; &nbsp;  <button type='button' class='button-sm' onclick=\"document.getElementById('dprdown_herst').style.display='block'\">zum anzeigen/ändern klicken! >";
}
Edit_Daten_Feld_Button(Prefix . 'fz_herstell_fg', 100,'','',$button);

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_herst' style='display:none'>";
}
BA_Auto_Herstell();

echo "</div>"; # ende hide herstb

Edit_Daten_Feld(Prefix . 'fz_typ', 100);
Edit_Daten_Feld(Prefix . 'fz_modell', 100);

Edit_Daten_Feld(Prefix . 'fz_motor', 100);
Edit_Daten_Feld(Prefix . 'fz_antrieb', 100);

Edit_Daten_Feld(Prefix . 'fz_geschwindigkeit', 10,' km/Std');

if ($hide_area == 0) { // Hide Aufbauer
    $button = "";
} else {
    $button = " &nbsp; &nbsp; <button type='button'  class='button-sm' onclick=\"document.getElementById('dprdown_aufb').style.display='block'\">zum anzeigen/ändern klicken!</button>";
}

Edit_Daten_Feld_Button(Prefix . 'fz_aufbauer', 100,'','',$button);

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_aufb' style='display:none'>";
}

BA_Auto_Aufbau();

echo "</div>"; # ende hide aufbauer

Edit_Daten_Feld(Prefix . 'fz_aufb_typ', 100);
Edit_Daten_Feld(Prefix . 'fz_besatzung', 10);

Edit_Daten_Feld(Prefix . 'fz_baujahr', 4);

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";

echo "<input type='hidden' name='fz_bild_1' value='" . $neu['fz_bild_1'] . "'>";
echo "<input type='hidden' name='fz_bild_2' value='" . $neu['fz_bild_2'] . "'>";
echo "<input type='hidden' name='fz_bild_3' value='" . $neu['fz_bild_3'] . "'>";
echo "<input type='hidden' name='fz_bild_4' value='" . $neu['fz_bild_4'] . "'>";

$Feldlaenge = "150px";

$pic_arr = array(
    "01" => "||fz_b_1_komm|fz_bild_1",
    "02" => "||fz_b_2_komm|fz_bild_2",
    "03" => "||fz_b_3_komm|fz_bild_3",
    "04" => "||fz_b_4_komm|fz_bild_4"
);
console_log('vor multi_foto');
VF_Multi_Foto($pic_arr);

if ($hide_area == 0 ) { // Hide Eibauten
    $button = "";
} else {
    if ($neu['fz_ctif_klass'] == "") {
        $button = 'Keine Zertifizierung bekannt';
    }
    $button .= " &nbsp; &nbsp; <button type='button'  class='button-sm' onclick=\"document.getElementById('dprdown_ctif').style.display='block'\">zum anzeigen/ändern klicken!</button>";
}

# =========================================================================================================
Edit_Separator_Zeile('CTIF Zertifizierung ' , $button);
# =========================================================================================================

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_ctif' style='display:none'>";
}

Edit_Select_Feld('fz_ctif_klass',VF_CTIF_Class);
Edit_Daten_Feld('fz_ctif_date', 10);
Edit_Daten_Feld('fz_ctif_darst_jahr', 4);
Edit_Daten_Feld('fz_ctif_juroren', 100);

echo "</div>"; # ende hide ctif

if ($hide_area == 0) { // Hide Eibauten
    $button = "";
} else {
    $button = " &nbsp; &nbsp; <button type='button'  class='button-sm' onclick=\"document.getElementById('dprdown_einb').style.display='block'\">zum anzeigen/ändern klicken!</button>";
}

# =========================================================================================================
Edit_Separator_Zeile('Fix Eingebaute Ausrüstung ', $button);
# =========================================================================================================

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_einb' style='display:none'>";
}

Edit_Daten_Feld('fz_l_tank', 100);
Edit_Daten_Feld('fz_l_monitor', 100);
Edit_Daten_Feld('fz_l_pumpe', 100);
Edit_Daten_Feld('fz_t_kran', 100);
Edit_Daten_Feld('fz_t_winde', 100);
Edit_Daten_Feld('fz_t_leiter', 100);
Edit_Daten_Feld('fz_t_abschlepp', 100);
Edit_Daten_Feld('fz_t_beleuchtg', 100);
Edit_Daten_Feld('fz_t_strom', 100);
Edit_Daten_Feld('fz_g_atemsch', 100);

echo "</div>"; # ende hide einbauten

# =========================================================================================================
Edit_Separator_Zeile('Datenfreigabe');
# =========================================================================================================

Edit_Select_Feld(Prefix . 'fz_eigent_freig', VF_JN, '');
Edit_Select_Feld(Prefix . 'fz_verfueg_freig', VF_JN, '');

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================


#echo "<div class='w3-container w3-aqua'>";

if ($hide_area == 0 || mb_strlen($neu['fz_sammlg']) <= 4) { // Hide Eibauten
    $button = "";
} else {
    $button = " &nbsp; &nbsp; <button type='button'  class='button-sm' onclick=\"document.getElementById('dprdown_sa').style.display='block'\">zum anzeigen/ändern klicken!</button>";
}

Edit_Daten_Feld_Button('fz_sammlg', '20','','',$button);
$button = "";
Edit_Daten_Feld('sa_name','');


if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='dprdown_sa' style='display:none'>";
}
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
    'Auswahl der Sammlungs- Type (1. Ebene) &nbsp; &nbsp; &nbsp; ',
    'Auswahl der Sammlungs- Gruppe (2. Ebene) &nbsp; ',
    'Auswahl der Untergrupppe (3. Ebene) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;',
    'Auswahl des Spezifikation (4. Ebene) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_MA_F; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
        /*
    case 2:
        $in_val = '07';
        $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
        */
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val,$titel);
echo "</div>"; # ende dropdown Sammlung
/*
Edit_Daten_Feld('fz_pruefg_id', 10);
Edit_Daten_Feld('fz_pruefg', 10);
*/
# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fz_aenduid');
Edit_Daten_Feld('fz_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FZ_List.php'>Zurück zur Liste</a></p>";

/**
 * Anzeige der bisherigen Besitzer
 */

require "VF_FZ_EI_List.inc.php";

/*
BA_Auto_Eigent();
BA_Auto_Urheber();
*/
BA_Auto_Funktion();

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph0.inc.php beendet</pre>";
}
?>