<?php

/**
 * Fahrzeug- Liste, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_FZ_MaF_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph0.inc.php gestartet </pre>";
}

$dataSetAct = "";
if ($neu['fz_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1; 
    $dataSetAct = "data-active-index='false'";
}

/** alle <input und <textara Felder werden als readonly gesetzt */
if ($_SESSION[$module]['all_upd'] == '0' ){
    $readOnly = 'readonly';
}

echo "<input type='hidden' id='recId' name='fz_id' value='".$neu['fz_id']."'/>";
echo "<input type='hidden' id='recEigner' name='fz_eignr' value='".$_SESSION['Eigner']['eig_eigner']."'/>";
echo "<input type='hidden' id='allUpd' name='allUpd' value='".$_SESSION[$module]['all_upd']."'/>";
echo "<input type='hidden' id='hide_area' value='$hide_area'>";
# echo "Verstecken $hide_area <br>";
# =========================================================================================================
Edit_Tabellen_Header('Motorisierte Fahrzeuge von Eigentümer '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fz_id');
Edit_Daten_Feld('fz_eignr');

// accordion für Sammlung
echo "<ul id='sa-accordion' class='accordionjs' $dataSetAct >"; // sa- für Sammlung
echo "<li>";
echo "<div>";
$readOnly = 'readonly'; # $Edit_Funcs_Protect = true;
Edit_Daten_Feld_Button('fz_sammlg', '20', '', '');
Edit_Daten_Feld('sa_name', '');
if ($_SESSION[$module]['all_upd'] == '1' ){
    $readOnly = '';
}
echo "Zum Ändern / Suchen - hier anklicken";
echo "</div>";
echo "<div>";
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

VF_Multi_Dropdown($in_val, $titel);

echo "</div>";
echo "</li>";
echo "</ul>";
// Ende Acccordion für Sammlung

# =========================================================================================================
Edit_Separator_Zeile('Fahrzeug/Geräte- Beschreibung');
# =========================================================================================================

// accordion für Takt. Bezeichnung
echo "<ul id='ta-accordion' class='accordionjs' $dataSetAct >"; // ta- für Taktische Bezeichnung
echo "<li>";
echo "<div>";
$readOnly = 'readonly'; # $Edit_Funcs_Protect = true;
Edit_Daten_Feld_Button('fz_taktbez', 40, '', '');
echo "Zum Ändern / Suchen - hier anklicken";
echo "</div>";
echo "<div>";
if ($_SESSION[$module]['all_upd'] == '1' ){
    $readOnly = '';
}
VF_Auto_Taktb($neu['fz_taktbez']);
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Takt. Bez

Edit_Daten_Feld('fz_name', 50);

Edit_Daten_Feld('fz_hist_bezeichng', 50);

$opt_aera = VF_FZG_Aera;
Edit_Select_Feld(Prefix . 'fz_zeitraum', $opt_aera, '');

Edit_textarea_Feld(Prefix . 'fz_allg_beschr');

Edit_textarea_Feld(Prefix . 'fz_beschreibg_det');

Edit_Daten_Feld('fz_indienstst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fz_ausdienst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Ausserdienst- Stellung');

Edit_Select_Feld(Prefix . 'fz_zustand', VF_Zustand, '');

// accordion für Fahrgestell- Hersteller
echo "<ul id='fg-accordion' class='accordionjs' $dataSetAct >"; // fg- für Fahrzeughersteller
echo "<li>";
echo "<div>";
$readOnly = 'readonly'; #$Edit_Funcs_Protect = true;
Edit_Daten_Feld_Button(Prefix . 'fz_herstell_fg', 70, '', '');
if ($_SESSION[$module]['all_upd'] == '1' ){
    $readOnly = '';
}
echo "Zum Ändern / Suchen - hier anklicken";
echo "</div>";
echo "<div>";
VF_Auto_Herstell();
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für  Fahrgestell- Hersteller

Edit_Daten_Feld(Prefix . 'fz_typ', 70);
Edit_Daten_Feld(Prefix . 'fz_modell', 50);

Edit_Daten_Feld(Prefix . 'fz_motor', 50);
Edit_Daten_Feld(Prefix . 'fz_antrieb', 50);

Edit_Daten_Feld(Prefix . 'fz_geschwindigkeit', 10, ' km/Std');

echo "<input type='hidden' name='fz_aufbauer' value='".$neu['fz_aufbauer']."' >";

// accordion für Aufbauer
echo "<ul id='au-accordion' class='accordionjs' $dataSetAct >"; // au- für Aufbauer
echo "<li>";
echo "<div>";
// $readOnly = 'readonly'; #$Edit_Funcs_Protect = True;
Edit_Daten_Feld_Button(Prefix . 'fz_aufbauer', 50,'','');
if ($_SESSION[$module]['all_upd'] == '1' ){
    $readOnly = '';
}
echo "Zum Ändern / Suchen - hier anklicken";
echo "</div>";
echo "<div>";
VF_Auto_Aufbau();
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Aufbauer

Edit_Daten_Feld(Prefix . 'fz_aufb_typ', 50);
Edit_Daten_Feld(Prefix . 'fz_besatzung', 10);

Edit_Daten_Feld(Prefix . 'fz_baujahr', 4);

# =========================================================================================================
$checkbox_f = "<a href='#' class='toggle-string' data-toggle-group='1'>Foto Daten eingeben/ändern</a>";
Edit_Separator_Zeile('Fotos',$checkbox_f);  #
# =========================================================================================================
/*
echo "<div>";
*/
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='fz_bild_1' value='".$neu['fz_bild_1']."' class='monitor' >";
echo "<input type='hidden' name='fz_bild_2' value='".$neu['fz_bild_2']."' >";
echo "<input type='hidden' name='fz_bild_3' value='".$neu['fz_bild_3']."' >";
echo "<input type='hidden' name='fz_bild_4' value='".$neu['fz_bild_4']."' >";

echo "<input type='hidden' id='sammlung' name= value='".$neu['fz_sammlg'] ."'>";
# echo "<input type='hidden' id='eigner' value='".$neu['fz_eignr'] ."'>";

echo "<input type='hidden' id='aOrd' name='aOrd' value'sammlung'=''>";
echo "<input type='hidden' id='urhNr' name='urhNr' value=''>";

$pict_path = "";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'fz_b_'.$i.'_komm', 'bi' => 'fz_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    $i++;
}

VF_Upload_Form_M();
// ende Foto

// accordion für eingebaute Ausrüstung
echo "<ul id='eb-accordion' class='accordionjs' $dataSetAct >"; // ta- für Taktische Bezeichnung
echo "<li>";
echo "<div>Eingebaute Ausrüstung - für Details anklicken</div>";
echo "<div>";
Edit_Daten_Feld('fz_l_tank', 70);
Edit_Daten_Feld('fz_l_monitor', 70);
Edit_Daten_Feld('fz_l_pumpe', 70);
Edit_Daten_Feld('fz_t_kran', 70);
Edit_Daten_Feld('fz_t_winde', 70);
Edit_Daten_Feld('fz_t_leiter', 70);
Edit_Daten_Feld('fz_t_abschlepp', 70);
Edit_Daten_Feld('fz_t_beleuchtg', 70);
Edit_Daten_Feld('fz_t_geraet', 70);
Edit_Daten_Feld('fz_t_strom', 70);
Edit_Daten_Feld('fz_g_atemsch', 70);
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für eingebaute Ausrüstung

// accordion für CTIF zertifikat
echo "<ul id='ct-accordion' class='accordionjs' $dataSetAct >"; // ct- für ctif- Zertifizierung
echo "<li>";
echo "<div>CTIF Zertifizierung - für Details anklicken</div>";
echo "<div>";
Edit_Select_Feld('fz_ctif_klass', VF_CTIF_Class);
Edit_Daten_Feld('fz_ctif_date', 10);
Edit_Daten_Feld('fz_ctif_darst_jahr', 4);
Edit_Daten_Feld('fz_ctif_juroren', 70);
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für CTIF zertifikat

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

<!-- Bereich für Debug-Infos  -->
<div id="debug">Wartet auf Eingaben...
</div>
<?php

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FZ_MaFG_List.php'>Zurück zur Liste</a></p>";

/**
 * Anzeige der bisherigen Besitzer
 */

require "VF_FZ_EI_List.inc.php";
#echo "</fieldset>";
echo "</div>"; # ende dropdown Eigentümer
#echo "</fieldset>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FZ_MA_Edit_ph0.inc.php beendet</pre>";
}
?>
