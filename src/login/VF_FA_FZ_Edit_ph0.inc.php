<?php

/**
 * Fahrzeug- Liste, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_FA_FZ_Edit_ph0.inc.php gestartet </pre>";
}
echo "<input type='hidden' name='fz_id' value='$fz_id'/>";
echo "<input type='hidden' name='fz_invnr' value='".$neu['fz_invnr']."' />";
# =========================================================================================================
Edit_Tabellen_Header('Motorisierte Fahrzeuge von Eigentümer '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('fz_id');
Edit_Daten_Feld('fz_eignr');

# =========================================================================================================
Edit_Separator_Zeile('Fahrzeug/Geräte- Beschreibung');
# =========================================================================================================

Edit_Daten_Feld('fz_name', 30, 'Rufname');
Edit_Daten_Feld('fz_taktbez', 100);
Edit_Daten_Feld('fz_indienstst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Indienst- Stellung');
Edit_Daten_Feld('fz_ausdienst', 10, 'Datum YYYY-MM-DD oder zumindest Jahr der Ausserdienst- Stellung');

$opt_aera = VF_FZG_Aera; 
Edit_Select_Feld(Prefix . 'fz_zeitraum', $opt_aera, '');

Edit_textarea_Feld(Prefix . 'fz_komment');

Edit_textarea_Feld(Prefix . 'fz_beschreibg_det');

Edit_Select_Feld(Prefix . 'fz_zustand', VF_Zustand, '');

Edit_Daten_Feld(Prefix . 'fz_herstell_fg', 100);
Edit_Daten_Feld(Prefix . 'fz_baujahr', 4);

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaF/";

$Feldlaenge = "100px";

echo "<input type='hidden' name='fz_bild_1' value='" . $neu['fz_bild_1'] . "'>";
echo "<input type='hidden' name='fz_bild_2' value='" . $neu['fz_bild_2'] . "'>";

$Feldlaenge = "150px";

$pic_arr = array(
    "01" => "||fz_b_1_komm|fz_bild_1",
    "02" => "||fz_b_2_komm|fz_bild_2"
);
console_log('vor multi_foto');
VF_Multi_Foto($pic_arr);

# =========================================================================================================
Edit_Separator_Zeile('CTIF Zertifizierung');
# =========================================================================================================
# Edit_Daten_Feld('fz_ctifklass', 5);

Edit_Select_Feld('fz_ctifklass',VF_CTIF_Class);

Edit_Daten_Feld('fz_ctifdate', 10);
Edit_Daten_Feld('ct_darstjahr', 4);
Edit_Daten_Feld('ct_juroren', 100);

Edit_Daten_Feld('fz_pruefg_id', 10);
Edit_Daten_Feld('fz_pruefg', 10);

# =========================================================================================================
Edit_Separator_Zeile('Datenfreigabe');
# =========================================================================================================

Edit_Select_Feld(Prefix . 'fz_eigent_freig', VF_JN, '');
Edit_Select_Feld(Prefix . 'fz_verfueg_freig', VF_JN, '');

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================
# Edit_Daten_Feld('fz_invnr',50);

echo "<div class='w3-container w3-aqua'>";

Edit_Daten_Feld('fz_sammlg', '');
Edit_Daten_Feld('sa_name','');

echo "</div>";

echo "<input type='hidden' name='fz_sammlg' value='".$neu['fz_sammlg']."'/>";
if (mb_strlen($neu['fz_sammlg']) <= 4) {
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
echo "</div>";

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

echo "<p><a href='VF_FA_List.php'>Zurück zur Liste</a></p>";

if ($neu['fz_id'] != 0) {
    $_SESSION[$module]['fz_id'] = $neu['fz_id'];
    switch (substr($neu['fz_sammlg'],0,4)) {

        case "MA_F":
            require 'VF_FA_BA_Lists.inc.php';
            break;
    }
} else {}
$Tab_Nam = array();

echo "<p><a href='VF_FA_List.php?fz_id=MU_F'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_FZ_Edit_ph0.inc.php beendet</pre>";
}
?>