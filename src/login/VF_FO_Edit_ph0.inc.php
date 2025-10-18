<?php

/**
 * Foto- Verwaltung, Wartung. Formular
 *
 * @author J. Rohowsky  - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph0.inc.php ist gestarted</pre>";
}

$Inc_Arr[] = "VF_FO_Edit_ph0.inc.php";
# var_dump($neu);
if ($neu['md_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

if (!isset($neu['sa_name'])) { /** initialisierung falls noch nicht existent */
    $neu['sa_name'] = "";
}

echo "<input type='hidden' name='md_id' value='" . $neu['md_id'] . "'/>";
echo "<input type='hidden' id='urhNr' name='md_eigner' value='" . $neu['md_eigner'] . "'/>";
echo "<input type='hidden' name='md_media' value='" . $neu['md_media'] . "'/>";
echo "<input type='hidden' name='md_Urheber' value='" . $neu['md_Urheber'] . "'/>";
echo "<input type='hidden' name='md_aenduid' value='" . $neu['md_aenduid'] . "'/>";
echo "<input type='hidden' name='md_aenddat' value='" . $neu['md_aenddat'] . "'/>";

if (!empty($Err_msg) ) {echo "<span class='error'>Eingabe Fehler,bitte korrigieren.</span>";}

# =========================================================================================================
Edit_Tabellen_Header('Foto- / Video Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('md_id');
Edit_Daten_Feld('md_eigner');
# Edit_Daten_Feld('fo_Urheber', 50);

# =========================================================================================================
Edit_Separator_Zeile('Daten');
# =========================================================================================================

Edit_textarea_Feld(Prefix . 'md_namen', 'Namen der Personen am Bild', "rows='2' cols='50'");

Edit_textarea_Feld(Prefix . 'md_suchbegr', 'Suchbegriffe', "rows='2' cols='50'");

echo "<input type='hidden' id='aufnDat_1' name='md_aufn_datum' value='" . $neu['md_aufn_datum'] . "'/>";
echo "<input type='hidden' name='md_dsn_1' value='" . $neu['md_dsn_1'] . "'/>";
echo "<input type='hidden' name='verz' value='" . $verz . "'/>";

if ($hide_area == 0) {
    Edit_Daten_Feld('md_aufn_datum',30,'YYYYmmdd _123',"required  ");
}

Edit_Daten_Feld('md_Urheber', 60, 'Verfüger');

echo "<input type='hidden' name='MAX_FILE_SIZE' value='500000' />";

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
   $checked_f = 'checked';
}
// Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
$button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Fotos'.$button_f);
# =========================================================================================================
echo "<div>";

$pict_path = $path2ROOT."login/".$_SESSION['VF_Prim']['store'].'/'.$neu['md_eigner']."/09/";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 1;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'md_beschreibg', 'bi' => 'md_dsn_1', 'rb' => '', 'up_err' => '','f1'=>'','f2'=>'');
    $i++;
}

VF_Upload_Form_M();

echo "</div>";

# =========================================================================================================
$button = "";
if ($hide_area != 0) {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleBlock10' > zum anzeigen/ändern anklicken</label> ";
}
Edit_Separator_Zeile('Sammlung'.$button);
# =========================================================================================================

echo "<input type='hidden' name='md_sammlg' value='".$neu['md_sammlg']."'/>";

// $Edit_Funcs_Protect = True;
Edit_Daten_Feld('md_sammlg','30');
// $Edit_Funcs_Protect = False;
Edit_Daten_Feld('sa_name');

if ($hide_area == 0 || mb_strlen($neu['md_sammlg']) <= 4) {
    echo "<div>";
} else {
    echo "<div class='block-container' >";
    echo "<div class='toggle-block' id='block10'>";
}
# =========================================================================================================

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt jquery
 *
 *
 * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Daten kommen aus Tabellen) [Werte array(Key=>txt)]
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
    'Auswahl des Spezifikation (4. Ebene)'
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
}

$titel  = 'Suche nach der Sammlungs- Beschreibung ( oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val,$titel);

echo "</div>"; # ende toggle
echo "</div>"; # ende dropdown Sammlung

if ($hide_area == 0) {  //toggle??
    $button = "";
} else {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleBlock80' > zum anzeigen/ändern anklicken</label> ";
}

# $Edit_Funcs_Protect = true;
Edit_Daten_Feld('md_fw_id',10,'','',$button);
$Edit_Funcs_Protect = false;
echo $button;
if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div class='block-container' >";
    echo "<div class='toggle-block' id='block80'>";
}

VF_Auto_Eigent('E','',1);

echo "</div>"; # ende toggle
echo "</div>"; # ende hide Eigner

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('md_aenduid');
Edit_Daten_Feld('md_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FO_List_Detail.php?md_aufn_d=" . $neu['md_aufn_datum'] . "'>Zurück zur Liste</a></p>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/VF_toggle.js' ></script>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_Edit_ph0.inc.php beendet</pre>";
}
?>