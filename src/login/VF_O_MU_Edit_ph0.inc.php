<?php

/**
 * Museums- Daten- Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph0.inc.php ist gestarted</pre>";
}

if ($neu['mu_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header('Museumsdaten');
# =========================================================================================================

Edit_Daten_Feld('mu_id');
echo "<input type='hidden' name='mu_id' value='" . $neu['mu_id'] . "'";
# =========================================================================================================
Edit_Separator_Zeile('Museums- Ort');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'mu_name', 50);
Edit_Daten_Feld(Prefix . 'mu_bezeichng', 50);
Edit_Daten_Feld(Prefix . 'mu_adresse_a', 50);
Edit_Daten_Feld(Prefix . 'mu_plz_a', 7);
Edit_Daten_Feld(Prefix . 'mu_ort_a', 50);

# =========================================================================================================
Edit_Separator_Zeile('Post- Adresse, wenn anders als Standort:');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'mu_adresse_p', 50);
Edit_Daten_Feld(Prefix . 'mu_plz_p', 7);
Edit_Daten_Feld(Prefix . 'mu_ort_p', 50);

# =========================================================================================================
Edit_Separator_Zeile('Land, Bundesland');
# =========================================================================================================

$BD_Opt = VF_Sel_Bdld('mu_bdland', '8');

Edit_Select_Feld(Prefix . 'mu_bdland', $BD_Opt);
$ST_Opt = VF_Sel_Staat('mu_staat', '9');
Edit_Select_Feld(Prefix . 'mu_staat', $ST_Opt);
Edit_Daten_Feld(Prefix . 'mu_eigner', 8);

# =========================================================================================================
Edit_Separator_Zeile('Sammlung');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'mu_sammlbeg', 4);
Edit_textarea_Feld(Prefix . 'mu_sammlgschw', 200);
Edit_textarea_Feld(Prefix . 'mu_besobj_1', 100);
Edit_textarea_Feld(Prefix . 'mu_besobj_2', 100);
Edit_textarea_Feld(Prefix . 'mu_besobj_3', 100);
Edit_Daten_Feld(Prefix . 'mu_anz_obj', 4);

Edit_Select_Feld(Prefix . 'mu_archiv', VF_JN);
Edit_Select_Feld(Prefix . 'mu_protbuch', VF_JN);
Edit_Select_Feld(Prefix . 'mu_abzeich', VF_JN);
Edit_Select_Feld(Prefix . 'mu_ausruest', VF_JN);
Edit_Select_Feld(Prefix . 'mu_kleinger', VF_JN);
Edit_Select_Feld(Prefix . 'mu_grossger', VF_JN);

Edit_Select_Feld(Prefix . 'mu_mustyp', VF_Mus_Typ);
$Et = array(
    "F" => "Feuerwehr",
    "P" => "Privat",
    "V" => "Verein"
);
Edit_Select_Feld(Prefix . 'mu_museigtyp', $Et);

# =========================================================================================================
Edit_Separator_Zeile('Kustos');
# =========================================================================================================

Edit_Daten_Feld(Prefix . 'mu_kustos_titel', 10);
Edit_Daten_Feld(Prefix . 'mu_kustos_vname', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_name', 40);
Edit_Daten_Feld(Prefix . 'mu_kustos_dgr', 10);
Edit_Daten_Feld(Prefix . 'mu_kustos_tel', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_fax', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_handy', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_intern', 50);
Edit_Daten_Feld(Prefix . 'mu_kustos_email', 50);

# =========================================================================================================
Edit_Separator_Zeile('Infrastruktur');
# =========================================================================================================
Edit_Select_Feld(Prefix . 'mu_toilett', VF_JN);
Edit_Select_Feld(Prefix . 'mu_garderobe', VF_JN);
Edit_Select_Feld(Prefix . 'mu_cafe', VF_JN);
Edit_Select_Feld(Prefix . 'mu_rollstuhl', VF_JN);
Edit_Daten_Feld(Prefix . 'mu_beheinr', 30);
Edit_Daten_Feld(Prefix . 'mu_sonst_anb', 60);

# =========================================================================================================
Edit_Separator_Zeile('Öffnungszeiten');
# =========================================================================================================

$Oo = array(
    "G" => "Ganzjährig",
    "S" => "Saisonal",
    "V" => "nur nach Vereinbarung"
);
Edit_Select_Feld(Prefix . 'mu_oeffnung', $Oo);
Edit_Daten_Feld(Prefix . 'mu_saison', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_mo', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_di', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_mi', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_do', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_fr', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_sa', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_so', 30);
Edit_Daten_Feld(Prefix . 'mu_oez_fei', 30);

# =========================================================================================================
Edit_Separator_Zeile('Führungen, Auskunft');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'mu_f1_titel', 20);
Edit_Daten_Feld(Prefix . 'mu_f1_vname', 35);
Edit_Daten_Feld(Prefix . 'mu_f1_name', 35);
Edit_Daten_Feld(Prefix . 'mu_f1_dgr', 10);
Edit_Daten_Feld(Prefix . 'mu_f1_tel', 20);
Edit_Daten_Feld(Prefix . 'mu_f1_handy', 20);
Edit_Daten_Feld(Prefix . 'mu_f1_email', 45);

Edit_Daten_Feld(Prefix . 'mu_f2_titel', 20);
Edit_Daten_Feld(Prefix . 'mu_f2_vname', 35);
Edit_Daten_Feld(Prefix . 'mu_f2_name', 35);
Edit_Daten_Feld(Prefix . 'mu_f2_dgr', 10);
Edit_Daten_Feld(Prefix . 'mu_f2_tel', 20);
Edit_Daten_Feld(Prefix . 'mu_f2_handy', 20);
Edit_Daten_Feld(Prefix . 'mu_f2_email', 45);

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
// Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
$button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Bilder ',$button_f);
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = $path2ROOT."login/AOrd_Verz/Museen/";

echo "<input type='hidden' name='mu_bildnam_1' value='" . $neu['mu_bildnam_1'] . "'>";
echo "<input type='hidden' name='mu_bildnam_2' value='" . $neu['mu_bildnam_2'] . "'>";

echo "<input type='hidden' id='urhNr' value=''>";
echo "<input type='hidden' id='aOrd' value=''>";

echo "<input type='hidden' id='reSize' value='1754'>";

$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 2;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => '', 'bi' => 'mu_bildnam_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    
    echo "<input type='hidden' id='aOrd_$i' value='Museen/'>";
    $i++;
}

VF_Upload_Form_M();

# =========================================================================================================

Edit_Separator_Zeile('Letzte Änderung ');
# =========================================================================================================
Edit_Daten_Feld('mu_uidaend', 5);
Edit_Daten_Feld('mu_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_O_MU_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

echo "</div>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_toggle.js' ></script>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph0.inc.php beendet</pre>";
}
?>