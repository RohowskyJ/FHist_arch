<?php
/**
 * Inventarverwaltung für Feuerwehren, Eingabe / Änderung der Daten, Formular
 * 
 * @author  Josef Rohowsky - neu 2019
 * 
 * 
 */
if ($debug) {
    echo "<pre class=debug>VF_I_IN_Edit_ph0.inc.php ist gestarted</pre>";
}


if ($neu['in_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

echo "<input type='hidden' name='in_id' value='" . $neu['in_id'] . "'/>";
echo "<input type='hidden' name='ei_id' value='" . $neu['ei_id'] . "'/>";
echo "<input type='hidden' name='in_sammlg' value='" . $neu['in_sammlg'] . "'/>";
echo "<input type='hidden' name='in_linkerkl' value='" . $neu['in_linkerkl'] . "'/>";

$inveignr = $_SESSION['Eigner']['eig_eigner'];
$pref_eignr_s = "0" . $inveignr; // Kurz- Variante

$pref_eignr = substr("00000", 1, 5 - strlen($inveignr)) . $inveignr;
$in_id = $_SESSION[$module]['in_id'];
$pref_invnr_s = "0" . $in_id; // kurz- Variante
$in_flnr = substr("000000", 1, 6 - strlen($in_id)) . $in_id;
$neu['InvNr'] = "<b>V$pref_eignr" . $_SESSION[$module]['sammlung'] . $in_flnr . " </b> oder <b>V:$pref_eignr_s" . $_SESSION[$module]['sammlung'] . ":$pref_invnr_s </b>";
$Tabellen_Spalten_COMMENT['InvNr'] = "Volle oder Kurze Inventarnummer '";

# =========================================================================================================
Edit_Tabellen_Header("Inventar für ".$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('in_id');
Edit_Daten_Feld('ei_id');
Edit_Daten_Feld('InvNr');

if ($neu['in_id'] == 0) {
    # =========================================================================================================
    Edit_Separator_Zeile('Sammlung');
    # =========================================================================================================
    Edit_Daten_Feld('InvNr');
    
    Edit_Daten_Feld('in_sammlg','');
    Edit_Daten_feld('sa_name','');
   # echo "</div>";
    
    echo "<div class='w3-container '> ";
    
    echo "<input type='hidden' name='in_sammlg' value='".$neu['in_sammlg']."'/>";
    
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
            $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
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
    
} else {
    Edit_Daten_Feld('in_sammlg','');
    Edit_Daten_feld('sa_name','');
}
echo "</div>";
# =========================================================================================================
Edit_Separator_Zeile('Inventar- Beschreibung');
# =========================================================================================================
# var_dump($Tabellen_Spalten_COMMENT);
# var_dump($Tabellen_Spalten_MAXLENGTH);
Edit_Daten_Feld('in_bezeichnung', 60);
Edit_textarea_Feld('in_kommentar');

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";

if ($neu['in_sammlg'] != "") {
    $pict_path .= $neu['in_sammlg'] . "/";
    $Opt_Det = VF_Sel_Det($neu['in_sammlg'], $neu['in_linkerkl'], 8);
    Edit_Select_Feld('in_linkerkl', $Opt_Det);
}

Edit_Daten_Feld('in_entstehungszeit', 10);
Edit_Select_Feld('in_epoche', VF_Epoche);

Edit_Daten_Feld('in_hersteller', 60);
/*
$ST_Opt_A = VF_Sel_Staat('in_herstld', '9');
Edit_Select_Feld('in_herstld', $ST_Opt_A);

$ST_Opt_A = VF_Sel_Staat('in_aufbld_1', '9');
Edit_Select_Feld('in_aufbld_1', $ST_Opt_A);
# Edit_Daten_Feld('in_aufbld_1',10);
*/
Edit_Daten_Feld('in_wert_neu', 30);

Edit_Daten_Feld('in_neu_waehrg', 50);
Edit_Daten_Feld('in_wert_kauf', 30);
Edit_Daten_Feld('in_kauf_waehrung', 50);
Edit_Daten_Feld('in_wert_besch', 30);
Edit_Daten_Feld('in_besch_waehrung', 50);
Edit_textarea_Feld('in_zustand');

Edit_textarea_Feld('in_namen'); // ,'',"Namen, durch Beistrich <b><q>,</q></b> getrennt");

Edit_Daten_Feld('in_abmess', 50, " Abmessungen:  l x b xh in mm");

Edit_Daten_Feld('in_gewicht', 50, " in Kg");

echo "<input type='hidden' name='in_vwlinks' value='" . $neu['in_vwlinks'] . "'/>";
echo "<input type='hidden' name='in_beschreibung' value='" . $neu['in_beschreibung'] . "'/>";
echo "<input type='hidden' name='in_refindex' value='" . $neu['in_refindex'] . "'/>";

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

echo "<input type='hidden' name='in_foto_1' value='" . $neu['in_foto_1'] . "'>";
echo "<input type='hidden' name='in_foto_2' value='" . $neu['in_foto_2'] . "'>";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";

echo "<input type='hidden' id='sammlung' value='".$neu['in_sammlg'] ."'>";
echo "<input type='hidden' id='eigner' value='".$neu['ei_id'] ."'>";
echo "<input type='hidden' id='urhNr' value=''>";

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/INV/";
$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 2;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'in_fbeschr_'.$i, 'bi' => 'in_foto_'.$i, 'rb' => '', 'up_err' => '', 'f1' => '','f2'=>'');
    $i++;
}
VF_Upload_Form_M();

echo "</div>";

# =========================================================================================================
Edit_Separator_Zeile('Archiv- Einteilung (Bestandsdaten)');
# =========================================================================================================
# Edit_Daten_Feld('in_altbestand',10);// VF_JN
Edit_Select_Feld('in_altbestand', VF_JN, '');
Edit_Daten_Feld('in_invjahr', 10);
Edit_Daten_Feld('in_eingbuchnr', 15);
Edit_Daten_Feld('in_eingbuchdat', 10);
Edit_Daten_Feld('in_erstdat', 10);
Edit_Daten_Feld('in_ausgdat', 10);


$button = "";
/*
if ($hide_area != 0) {
    // Der Button, der das toggling übernimmt
    $button = " &nbsp; &nbsp; <button type='button' class='button-sm' onclick=\"toggleVisibility('unhide_ne')\">zum ändern klicken!</button>";
}
*/
Edit_Daten_Feld('in_neueigner',75,$button); #
/*
if ($hide_area == 0) {
    echo "<div>";
} else {
    echo "<div id='unhide_ne' style='display:none'>";
}
VF_Auto_Eigent('E','');
echo "<div>";
*/
# =========================================================================================================
Edit_Separator_Zeile('Lagerort');
# =========================================================================================================
Edit_Daten_Feld('in_raum', 40);
Edit_Daten_Feld('in_platz', 40);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================

Edit_Daten_Feld('in_uidaend');
Edit_Daten_Feld('in_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

# require "VF_I_IN_VL_List.php";

echo "<p><a href='VF_I_IN_List.php'>Zurück zur Liste</a></p>";
echo "</div>";
echo "</div>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/common/javascript/VF_toggle.js' ></script>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_I_IN_Edit_ph0.inc.php beendet</pre>";
}
?>