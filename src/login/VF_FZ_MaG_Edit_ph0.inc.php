<?php

/**
 * Anzeige der Geräte aines Eigentümers, Wartug, Formular
 *
 * @author Josef Rohowsky  neu 2019
 *
 * 1. Auswahl des Eigentümers
 * 2. Anzeige der Fahrzeuge
 *
 */

$Inc_Arr[] = "VF_FZ_MaG_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_FZ_MaG_Edit_ph0.inc.php ist gestarted</pre>";
}

if ($neu['ge_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

echo "<input type='hidden' name='ge_id' value='$ge_id'/>";
echo "<input type='hidden' name='ge_invnr' value='".$neu['ge_invnr']."'/>";

Edit_Tabellen_Header('Motorbetriebene Geräte des Eigentümers '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('ge_id');
Edit_Daten_Feld('ge_eignr');
# Edit_Daten_Feld('ge_invnr');

# =========================================================================================================
$button = "";
if ($hide_area != 0) {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_sa')\">zum anzeigen/ändern klicken!</button>";
}

Edit_Separator_Zeile('Sammlung'.$button);
# =========================================================================================================

echo "<input type='hidden' id='ge_sammlg' name='ge_sammlg' value='".$neu['ge_sammlg']."'>";
$Edit_Funcs_Protect = true;
Edit_Daten_Feld('ge_sammlg', '');
Edit_Daten_Feld('sa_name', '');
$Edit_Funcs_Protect = false;

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_sa' style='display:none'>";
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
        $MS_Init = VF_Sel_SA_MA_G; # VF_Sel_SA_Such|VF_Sel_AOrd
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

echo "</div>"; # ende dropdown Sammlung


# =========================================================================================================
Edit_Separator_Zeile('Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('ge_bezeich', 100);
Edit_Daten_Feld('ge_type', 60);
Edit_Daten_Feld('ge_indienst', 10, 'Datum oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('ge_ausdienst', 10, 'Datum oder zumindest Jahr der Ausserdienst- Stellung');
Edit_textarea_Feld('ge_komment');

Edit_Daten_Feld('ge_leistg', 10);
Edit_Daten_Feld('ge_lei_bed', 50);
Edit_Daten_Feld('ge_leinh', 50);
Edit_Daten_Feld('ge_herst', 60);
Edit_Daten_Feld('ge_baujahr', 10);
Edit_Daten_Feld('ge_gesgew', 10);

Edit_Daten_Feld('ge_mo_herst', 60);
Edit_Daten_Feld('ge_mo_typ', 60);
Edit_Daten_Feld('ge_mo_sernr', 60);
Edit_Daten_Feld('ge_mo_treibst', 60);
Edit_Daten_Feld('ge_mo_leistung', 10);
Edit_Daten_Feld('ge_mo_leibed', 50);
Edit_Daten_Feld('ge_mo_leinh', 50);

Edit_Daten_Feld('ge_ag_herst', 60);
Edit_Daten_Feld('ge_ag_type', 60);
Edit_Daten_Feld('ge_ag_sernr', 60);
Edit_Daten_Feld('ge_ag_leistung', 10);
Edit_Daten_Feld('ge_ag_leibed', 60);
Edit_Daten_Feld('ge_ag_leinh', 60);

Edit_Select_Feld(Prefix . 'ge_zustand', VF_Zustand, '');

# =========================================================================================================
$button_f = "";
if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
    $button_f = "<button type='button' class='button-sm'  onclick='toggleAll()'>Foto Daten eingeben/ändern</button>";
}
Edit_Separator_Zeile('Fotos'.$button_f);
# =========================================================================================================
echo "<div>";

echo "<input type='hidden' id='sammlung' value='".$neu['ge_sammlg'] ."'>";
echo "<input type='hidden' id='eigner' value='".$neu['ge_eignr'] ."'>";

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='ge_foto_1' value='" . $neu['ge_foto_1'] . "'>";
echo "<input type='hidden' name='ge_foto_2' value='" . $neu['ge_foto_2'] . "'>";
echo "<input type='hidden' name='ge_foto_3' value='" . $neu['ge_foto_3'] . "'>";
echo "<input type='hidden' name='ge_foto_4' value='" . $neu['ge_foto_4'] . "'>";

echo "<input type='hidden' name='ge_g1_foto' value='" . $neu['ge_g1_foto'] . "'>";
echo "<input type='hidden' name='ge_g2_foto' value='" . $neu['ge_g2_foto'] . "'>";
echo "<input type='hidden' name='ge_g3_foto' value='" . $neu['ge_g3_foto'] . "'>";
echo "<input type='hidden' name='ge_g4_foto' value='" . $neu['ge_g4_foto'] . "'>";
echo "<input type='hidden' name='ge_g5_foto' value='" . $neu['ge_g5_foto'] . "'>";
echo "<input type='hidden' name='ge_g6_foto' value='" . $neu['ge_g6_foto'] . "'>";
echo "<input type='hidden' name='ge_g7_foto' value='" . $neu['ge_g7_foto'] . "'>";
echo "<input type='hidden' name='ge_g8_foto' value='" . $neu['ge_g8_foto'] . "'>";
echo "<input type='hidden' name='ge_g9_foto' value='" . $neu['ge_g9_foto'] . "'>";
echo "<input type='hidden' name='ge_g10_foto' value='" . $neu['ge_g10_foto'] . "'>";

echo "<input type='hidden' name='ge_g1_name' value='" . $neu['ge_g1_name'] . "'>";
echo "<input type='hidden' name='ge_g2_name' value='" . $neu['ge_g2_name'] . "'>";
echo "<input type='hidden' name='ge_g3_name' value='" . $neu['ge_g3_name'] . "'>";
echo "<input type='hidden' name='ge_g4_name' value='" . $neu['ge_g4_name'] . "'>";
echo "<input type='hidden' name='ge_g5_name' value='" . $neu['ge_g5_name'] . "'>";
echo "<input type='hidden' name='ge_g6_name' value='" . $neu['ge_g6_name'] . "'>";
echo "<input type='hidden' name='ge_g7_name' value='" . $neu['ge_g7_name'] . "'>";
echo "<input type='hidden' name='ge_g8_name' value='" . $neu['ge_g8_name'] . "'>";
echo "<input type='hidden' name='ge_g9_name' value='" . $neu['ge_g9_name'] . "'>";
echo "<input type='hidden' name='ge_g10_name' value='" . $neu['ge_g10_name'] . "'>";

echo "<input type='hidden' name='ge_g1_sernr' value='" . $neu['ge_g1_sernr'] . "'>";
echo "<input type='hidden' name='ge_g2_sernr' value='" . $neu['ge_g2_sernr'] . "'>";
echo "<input type='hidden' name='ge_g3_sernr' value='" . $neu['ge_g3_sernr'] . "'>";
echo "<input type='hidden' name='ge_g4_sernr' value='" . $neu['ge_g4_sernr'] . "'>";
echo "<input type='hidden' name='ge_g5_sernr' value='" . $neu['ge_g5_sernr'] . "'>";
echo "<input type='hidden' name='ge_g6_sernr' value='" . $neu['ge_g6_sernr'] . "'>";
echo "<input type='hidden' name='ge_g7_sernr' value='" . $neu['ge_g7_sernr'] . "'>";
echo "<input type='hidden' name='ge_g8_sernr' value='" . $neu['ge_g8_sernr'] . "'>";
echo "<input type='hidden' name='ge_g9_sernr' value='" . $neu['ge_g9_sernr'] . "'>";
echo "<input type='hidden' name='ge_g10_sernr' value='" . $neu['ge_g10_sernr'] . "'>";


echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner']."/"; # . "/MaG/";

$Feldlaenge = "100px";

# $pict_path = VF_M_Upl_Pfad ($aufnDatum, $suffix, $aoPfad);
# $pict_path = $path2ROOT."login/AOrd_Verz/";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'ge_komm_'.$i, 'bi' => 'ge_foto_'.$i, 'rb' => '', 'up_err' => '', 'f1' => '','f2' => '');
    $i++;
}

$num_foto = 10;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'ge_g'.$i.'_beschr', 'bi' => 'ge_g'.$i.'_foto', 'rb' => '', 'up_err' => '','f1' => 'ge_g'.$i.'_name','f2' => 'ge_g'.$i.'_sernr' );
    $i++;
}
VF_Upload_Form_M();

echo "</div>";
# =========================================================================================================
Edit_Separator_Zeile('Lagerort');
# =========================================================================================================

Edit_Daten_Feld('ge_fzg', 10);
Edit_Daten_Feld('ge_raum', 60);
Edit_Daten_Feld('ge_ort', 60);

# =========================================================================================================
Edit_Separator_Zeile('Daten geprüft');
# =========================================================================================================

Edit_Daten_Feld('ge_pruef_id', 10);
Edit_Daten_Feld('ge_pruef_dat', 10);

echo "</div>";

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('ge_aenduid');
Edit_Daten_Feld('ge_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FZ_MaFG_List.php'>Zurück zur Liste</a></p>"; # ?ID='.$_SESSION[$module]['fz_sammlung']

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MaG_Edit_ph0.inc.php beendet</pre>";
}
