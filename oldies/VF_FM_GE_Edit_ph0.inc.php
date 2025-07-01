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
    echo "<pre class=debug>VF_FM_GE_Edit_ph0.inc.php ist gestarted</pre>";
}
echo "<input type='hidden' id='mg_id' name='mg_id' value='".$neu['mg_id']."' >";
# =========================================================================================================
Edit_Tabellen_Header('Daten von '.$_SESSION['Eigner']['eig_name']);
# =========================================================================================================

Edit_Daten_Feld('mg_id');
Edit_Daten_Feld('mg_eignr');
Edit_Daten_Feld('mg_invnr');
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
Edit_Daten_Feld('mg_gew', 10);


Edit_textarea_Feld(Prefix . 'mg_komment');

Edit_Select_Feld(Prefix . 'mg_zustand', VF_Zustand, '');

# =========================================================================================================
Edit_Separator_Zeile('Fotos');
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
echo "<input type='hidden' name='mg_foto_1' value='" . $neu['mg_foto_1'] . "'>";
echo "<input type='hidden' name='mg_foto_2' value='" . $neu['mg_foto_2'] . "'>";
echo "<input type='hidden' name='mg_foto_3' value='" . $neu['mg_foto_3'] . "'>";
echo "<input type='hidden' name='mg_foto_4' value='" . $neu['mg_foto_4'] . "'>";


$pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuG/";

$Feldlaenge = "100px";

$pic_arr = array(
    "01" => "||mg_komm_1|mg_foto_1",
    "02" => "||mg_komm_2|mg_foto_2",
    "03" => "||mg_komm_3|mg_foto_3",
    "04" => "||mg_komm_4|mg_foto_4"
);
VF_Multi_Foto($pic_arr);



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
Edit_Separator_Zeile('Organisatorisches');
# =========================================================================================================
Edit_Daten_Feld('mg_invnr', 50);

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================
# Edit_Daten_Feld('fz_invnr',50);

echo "<div class='w3-container w3-aqua'>  <div class='label'>Sammlung - Sammlungsbezeichnung</div></div></td><td>";

Edit_Daten_Feld('mg_sammlg','');

echo "</div>";

echo "<input type='hidden' name='mg_sammlg' value='".$neu['mg_sammlg']."'/>";

if (mb_strlen($neu['mg_sammlg']) <= 4) {
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
VF_Multi_Dropdown($in_val,$titel);
echo " </div>";

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

echo "<p><a href='VF_FM_List.php?ID=MU_G'>Zurück zur Liste</a></p>"; # ."

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FM_GE_Edit_ph0.inc.php beendet</pre>";
}
?>