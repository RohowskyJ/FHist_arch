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
if ($debug) {
    echo "<pre class=debug>VF_FA_GE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<input type='hidden' name='ge_id' value='$ge_id'/>";

Edit_Tabellen_Header('Motorbetriebene Geräte des Eigentümers '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('ge_id');
Edit_Daten_Feld('ge_eignr');
Edit_Daten_Feld('ge_invnr');
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

Edit_textarea_Feld(Prefix . 'ge_komment');

Edit_Select_Feld(Prefix . 'ge_zustand', VF_Zustand, '');

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================

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

$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MaG/";

$Feldlaenge = "100px";

$pic_arr = array(
    "01" => "||ge_komm_1|ge_foto_1",
    "02" => "||ge_komm_2|ge_foto_2",
    "03" => "||ge_komm_3|ge_foto_3",
    "04" => "||ge_komm_4|ge_foto_4"
);
VF_Multi_Foto($pic_arr);

$pic_arr = array(
    "0" => "ge_g1_name|ge_g1_sernr|ge_g1_beschr|ge_g1_foto",
    "1" => "ge_g2_name|ge_g2_sernr|ge_g2_beschr|ge_g2_foto",
    "2" => "ge_g3_name|ge_g3_sernr|ge_g3_beschr|ge_g3_foto",
    "3" => "ge_g4_name|ge_g4_sernr|ge_g4_beschr|ge_g4_foto",
    "4" => "ge_g5_name|ge_g5_sernr|ge_g5_beschr|ge_g5_foto",
    "5" => "ge_g6_name|ge_g6_sernr|ge_g6_beschr|ge_g6_foto",
    "6" => "ge_g7_name|ge_g7_sernr|ge_g7_beschr|ge_g7_foto",
    "7" => "ge_g8_name|ge_g8_sernr|ge_g8_beschr|ge_g8_foto",
    "8" => "ge_g9_name|ge_g9_sernr|ge_g9_beschr|ge_g9_foto",
    "9" => "ge_g10_name|ge_g10_sernr|ge_g10_beschr|ge_g10_foto"
);
VF_Multi_Foto($pic_arr);

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

# =========================================================================================================
Edit_Separator_Zeile('Organisatorisches');
# =========================================================================================================
Edit_Daten_Feld('ge_invnr', 50);

echo "<tr><td colspan='2'><div class='w3-container w3-aqua'>  <div class='label'>Sammlung - Sammlungsbezeichnung</div></div></td><td>";
echo "<div class='w3-container w3-aqua'>";

Edit_Daten_Feld('ge_sammlg', '');
Edit_Daten_Feld('sa_name','');

echo "</div>";

if (mb_strlen($neu['ge_sammlg']) <= 4) {
    echo "<div>";
} else {
    echo "<p>Sollte die Sammlungsbezeichnung nicht stimmen,
       <button type='button' onclick=\"document.getElementById('dprdown').style.display='block'\">zum ändern drücken!</button>
       </p>";
    echo "<div id='dprdown' style='display:none'>";
}

echo "<input type='hidden' name='ge_sammlg' value='".$neu['ge_sammlg']."'/>";

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
VF_Multi_Dropdown($in_val,$titel);
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


echo "<p><a href='VF_FA_List.php'>Zurück zur Liste</a></p>"; # ?ID='.$_SESSION[$module]['fz_sammlung']

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FA_GE_Edit_ph0.inc.php beendet</pre>";
}
?>