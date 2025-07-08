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

echo "<input type='hidden' id='hide_area' value='$hide_area'>";
echo "Verstecken $hide_area <br>";
# =========================================================================================================
Edit_Tabellen_Header('Motorisierte Fahrzeuge von Eigentümer '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fz_id');
Edit_Daten_Feld('fz_eignr');

# =========================================================================================================
$button = "";
if ($hide_area != 0) {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_sa')\">zum anzeigen/ändern klicken!</button>";
}
Edit_Separator_Zeile('Sammlung'.$button);
# =========================================================================================================

echo "<input type='hidden' id='fz_sammlg' name='fz_sammlg' value='".$neu['fz_sammlg']."'>";
$Edit_Funcs_Protect = True;
Edit_Daten_Feld_Button('fz_sammlg', '20','','');
Edit_Daten_Feld('sa_name','');
$Edit_Funcs_Protect = False;

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

# =========================================================================================================
Edit_Separator_Zeile('Fahrzeug/Geräte- Beschreibung');
# =========================================================================================================
$taktbez = "";
if ($neu['ab_bezeichn'] != "")    {
    $taktbez = $neu['ab_bezeichn'];
    $neu['fz_taktbez'] .= " - $taktbez"; 
}

if ($hide_area == 0) {  //toggle??
    $button = "";
} else {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_taktb')\">zum anzeigen/ändern klicken!</button>";

}
echo "<input type='hidden' name='fz_taktbez' value='".$neu['fz_taktbez']."' >";
$Edit_Funcs_Protect = True;
Edit_Daten_Feld_Button('fz_taktbez',40,'','',$button);
$Edit_Funcs_Protect = False;

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_taktb' style='display:none'>";
}
VF_Auto_Taktb($neu['fz_taktbez']);

echo "</div>"; # ende hide taktb

Edit_Daten_Feld('fz_hist_bezeichng', 50);

$opt_aera = VF_FZG_Aera; 
Edit_Select_Feld(Prefix . 'fz_zeitraum', $opt_aera, '');

Edit_textarea_Feld(Prefix . 'fz_allg_beschr');

Edit_textarea_Feld(Prefix . 'fz_beschreibg_det');

Edit_Daten_Feld('fz_indienstst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fz_ausdienst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Ausserdienst- Stellung');

Edit_Select_Feld(Prefix . 'fz_zustand', VF_Zustand, '');

$button ="";
if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_herst')\">zum anzeigen/ändern klicken!</button>";
}
echo "<input type='hidden' name='fz_herstell_fg' value='".$neu['fz_herstell_fg']."' >";
$Edit_Funcs_Protect = True;
Edit_Daten_Feld_Button(Prefix . 'fz_herstell_fg', 70,'','',$button);
$Edit_Funcs_Protect = False;

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_herst' style='display:none'>";
}

VF_Auto_Herstell();

echo "</div>"; # ende hide herstb

Edit_Daten_Feld(Prefix . 'fz_typ', 70);
Edit_Daten_Feld(Prefix . 'fz_modell', 70);

Edit_Daten_Feld(Prefix . 'fz_motor', 70);
Edit_Daten_Feld(Prefix . 'fz_antrieb', 70);

Edit_Daten_Feld(Prefix . 'fz_geschwindigkeit', 10,' km/Std');

if ($hide_area == 0) {  //toggle??
    $button = "";
} else {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_aufb')\">zum anzeigen/ändern klicken!</button>";
}

#echo "<input type='hidden' name='fz_aufbauer' value='".$neu['fz_aufbauer']."' >";
#$Edit_Funcs_Protect = True;
Edit_Daten_Feld_Button(Prefix . 'fz_aufbauer', 50); # ,'','',$button
$Edit_Funcs_Protect = False;
/*
if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_aufb' style='display:none'>";
}

VF_Auto_Aufbau();

echo "</div>"; # ende hide aufbauer
*/
Edit_Daten_Feld(Prefix . 'fz_aufb_typ', 70);
Edit_Daten_Feld(Prefix . 'fz_besatzung', 10);

Edit_Daten_Feld(Prefix . 'fz_baujahr', 4);

# =========================================================================================================
$button_f = "";
if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
    $button_f = "<button type='button' class='button-sm'  onclick='toggleAll()'>Foto Daten eingeben/ändern</button>";
} 
Edit_Separator_Zeile('Fotos'.$button_f);  # 
# ========================================================================================================= 

echo "<div>";

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='fz_bild_1' value='".$neu['fz_bild_1']."' class='monitor' >";
echo "<input type='hidden' name='fz_bild_2' value='".$neu['fz_bild_2']."' >";
echo "<input type='hidden' name='fz_bild_3' value='".$neu['fz_bild_3']."' >";
echo "<input type='hidden' name='fz_bild_4' value='".$neu['fz_bild_4']."' >";


$pict_path = "";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'fz_b_'.$i.'_komm', 'bi' => 'fz_bild_'.$i, 'rb' => '', 'up_err' => '','f1'=>'','f2'=>'');
    $i++;
}
       
VF_M_Foto_N();

echo "</div";

if ($hide_area == 0) {  //toggle??
    $button = "";
} else {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_ctif')\">zum anzeigen/ändern klicken!</button>";
}
# =========================================================================================================
Edit_Separator_Zeile('CTIF Zertifizierung ' , $button);
# =========================================================================================================

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_ctif' style='display:none'>";
}

Edit_Select_Feld('fz_ctif_klass',VF_CTIF_Class);
Edit_Daten_Feld('fz_ctif_date', 10);
Edit_Daten_Feld('fz_ctif_darst_jahr', 4);
Edit_Daten_Feld('fz_ctif_juroren', 70);

echo "</div>"; # ende hide ctif

if ($hide_area == 0) {  //toggle??
    $button = "";
} else {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_einb')\">zum anzeigen/ändern klicken!</button>";
}
# =========================================================================================================
Edit_Separator_Zeile('Fix Eingebaute Ausrüstung ', $button);
# =========================================================================================================

if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_einb' style='display:none'>";
}

Edit_Daten_Feld('fz_l_tank', 700);
Edit_Daten_Feld('fz_l_monitor', 70);
Edit_Daten_Feld('fz_l_pumpe', 70);
Edit_Daten_Feld('fz_t_kran', 70);
Edit_Daten_Feld('fz_t_winde', 70);
Edit_Daten_Feld('fz_t_leiter', 70);
Edit_Daten_Feld('fz_t_abschlepp', 70);
Edit_Daten_Feld('fz_t_beleuchtg', 70);
Edit_Daten_Feld('fz_t_strom', 70);
Edit_Daten_Feld('fz_g_atemsch', 70);

echo "</div>"; # ende hide einbauten

# =========================================================================================================
Edit_Separator_Zeile('Datenfreigabe');
# =========================================================================================================

Edit_Select_Feld(Prefix . 'fz_eigent_freig', VF_JN, '');
Edit_Select_Feld(Prefix . 'fz_verfueg_freig', VF_JN, '');


/*
Edit_Daten_Feld('fz_pruefg_id', 1);
Edit_Daten_Feld('fz_pruefg',1);
*/
echo "<input type='hidden' name='fz_pruefg_id' value='".$neu['fz_pruefg_id']."' >";
echo "<input type='hidden' name='fz_pruefg' value='".$neu['fz_pruefg']."'  >";
# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('fz_aenduid');
Edit_Daten_Feld('fz_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

?>
<!-- Bereich zur Anzeige der aktuellen Werte -->
<div id="anzeige"></div>

<!-- Bereich für Debug-Infos -->
<div id="debug">Wartet auf Eingaben...</div>
<?php 

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}
#echo "L 0304 bild 1 ".$neu['fz_bild_1']."<br>";
echo "<p><a href='VF_FZ_MaFG_List.php'>Zurück zur Liste</a></p>";

/**
 * Anzeige der bisherigen Besitzer
 */

## require "VF_FZ_EI_List.inc.php";

echo "</div>"; # ende dropdown Eigentümer



# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph0.inc.php beendet</pre>";
}
?>
