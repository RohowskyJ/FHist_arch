<?php

/**
 * Liste der Geräte eines Eigentümers, Wartung, Formular
 *
 * @author Josef Rohowsky  neu 2019
 *
 */

if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph0.inc.php ist gestarted</pre>";
}
echo "<input type='hidden' id='fm_id' name='fm_id' value='".$neu['fm_id']."' >"; 
# =========================================================================================================

Edit_Tabellen_Header('Gerätebeschreibung Eigentümer: '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fm_id');
Edit_Daten_Feld('fm_eignr');
Edit_Daten_Feld('fm_invnr');
# =========================================================================================================
Edit_Separator_Zeile('Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fm_bezeich', 100);
Edit_Daten_Feld('fm_type', 60);
Edit_Daten_Feld('fm_indienst', 10, 'Datum oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fm_ausdienst', 10, 'Datum oder zumindest Jahr der Ausserdienst- Stellung');
Edit_textarea_Feld('fm_komment');

Edit_Daten_Feld('fm_leistung', 10);
Edit_Daten_Feld('fm_lei_bed', 50);
 
if ($neu['fi_name'] != "") {
    $neu['fm_herst'] .= " - ".$neu['fi_name'].", ".$neu['fi_ort'];
}

Edit_Daten_Feld('fm_herst', 60);
BA_Auto_Herstell();

Edit_Daten_Feld('fm_baujahr', 10);
Edit_Select_Feld(Prefix . 'fm_zustand', VF_Zustand, '');
Edit_Daten_Feld('fm_fgstnr', 60);

Edit_Daten_Feld('fm_gew', 10);

Edit_Daten_Feld('fm_zug', 10);

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================

echo "<input type='hidden' name='fm_foto_1' value='" . $neu['fm_foto_1'] . "'>";
echo "<input type='hidden' name='fm_foto_2' value='" . $neu['fm_foto_2'] . "'>";
echo "<input type='hidden' name='fm_foto_3' value='" . $neu['fm_foto_3'] . "'>";
echo "<input type='hidden' name='fm_foto_4' value='" . $neu['fm_foto_4'] . "'>";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuF/";

$Feldlaenge = "100px";

$pic_arr = array(
    "01" => "||fm_komm_1|fm_foto_1",
    "02" => "||fm_komm_2|fm_foto_2",
    "03" => "||fm_komm_3|fm_foto_3",
    "04" => "||fm_komm_4|fm_foto_4"
);
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================

echo "<div class='w3-container w3-aqua'> ";
Edit_Daten_Feld('sa_name');

echo "</div>";

echo "<input type='hidden' name='fm_sammlg' value='".$neu['fm_sammlg']."'/>";

if (mb_strlen($neu['fm_sammlg']) <= 4) {
    echo "<div>";
} else {
    echo "<p>Sollte die Sammlungsbezeichnung nicht stimmen,
       <button type='button' onclick=\"document.getElementById('dprdown').style.display='block'\">zum ändern drücken!</button>
       </p>";
    echo "<div id='dprdown' style='display:none'>";
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
VF_Multi_Dropdown($in_val,$titel);
echo "</div>";

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

echo "<p><a href='VF_FM_List.php?ID=MU_F'>Zurück zur Liste</a></p>";

BA_Auto_Funktion();
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FM_MU_Edit_ph0.inc.php beendet</pre>";
}
?>