<?php

/**
 * Museums- Daten- Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_O_MU_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph0.inc.php ist gestarted</pre>";
}

$dataSetAct = "";
if ($neu['mu_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
    $dataSetAct = "data-active-index='false'";
}

/** alle <input und <textara Felder werden als readonly gesetzt */
if ($_SESSION[$module]['all_upd'] == '0' ){
    $readOnly = 'readonly';
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

Edit_Select_Feld(Prefix . 'mu_mustyp', VF_Mus_Typ);
$Et = array(
    "F" => "Feuerwehr",
    "P" => "Privat",
    "V" => "Verein"
);
Edit_Select_Feld(Prefix . 'mu_museigtyp', $Et);

// accordion für Museums- Sammlung
echo "<ul id='ms-accordion' class='accordionjs' $dataSetAct >";
echo "<li>";
echo "<div>Sammlungs- Beschreibung - für Details anklicken</div>";
echo "<div>";
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
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Museums- Sammlung

// accordion für Museums- Kustos
echo "<ul id='ku-accordion' class='accordionjs' $dataSetAct >";
echo "<li>";
echo "<div>Kustos - für Details anklicken</div>";
echo "<div>";
Edit_Daten_Feld(Prefix . 'mu_kustos_titel', 10);
Edit_Daten_Feld(Prefix . 'mu_kustos_vname', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_name', 40);
Edit_Daten_Feld(Prefix . 'mu_kustos_dgr', 10);
Edit_Daten_Feld(Prefix . 'mu_kustos_tel', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_fax', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_handy', 35);
Edit_Daten_Feld(Prefix . 'mu_kustos_intern', 50);
Edit_Daten_Feld(Prefix . 'mu_kustos_email', 50);
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Museums- Kustos

// accordion für Museums- Infrastruktur
echo "<ul id='in-accordion' class='accordionjs' $dataSetAct >";
echo "<li>";
echo "<div>Infrastruktur - für Details anklicken</div>";
echo "<div>";
Edit_Select_Feld(Prefix . 'mu_toilett', VF_JN);
Edit_Select_Feld(Prefix . 'mu_garderobe', VF_JN);
Edit_Select_Feld(Prefix . 'mu_cafe', VF_JN);
Edit_Select_Feld(Prefix . 'mu_rollstuhl', VF_JN);
Edit_Daten_Feld(Prefix . 'mu_beheinr', 30);
Edit_Daten_Feld(Prefix . 'mu_sonst_anb', 60);
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Museums- Infrastruktur

// accordion für Museums- Öffnungszeiten
echo "<ul id='oe-accordion' class='accordionjs' $dataSetAct >";
echo "<li>";
echo "<div>Öffnungszeiten - für Details anklicken</div>";
echo "<div>";
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
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Museums- Öffnungszeiten

// accordion für Museums- Führungen
echo "<ul id='fu-accordion' class='accordionjs' $dataSetAct >"; 
echo "<li>";
echo "<div>Führungen, Auskunft - für Details anklicken</div>";
echo "<div>";
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
echo "</div>";
echo "</li>";
echo "</ul>";
// ende accordion für Museums- Führungen

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
$checkbox_f = "<a href='#' class='toggle-string' data-toggle-group='1'>Foto Daten eingeben/ändern</a>";
Edit_Separator_Zeile('Fotos',$checkbox_f);  #
# =========================================================================================================
echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = $path2ROOT."login/AOrd_Verz/Museen/";

echo "<input type='hidden' name='mu_bild_1' value='" . $neu['mu_bild_1'] . "'>";
echo "<input type='hidden' name='mu_bild_2' value='" . $neu['mu_bild_2'] . "'>";

echo "<input type='hidden' id='urhNr' value=''>";
echo "<input type='hidden' id='aOrd' value=''>";

echo "<input type='hidden' id='reSize' value='800'>";

$Feldlaenge = "200px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 2;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => 'mu_bibeschr_'.$i, 'bi' => 'mu_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    
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

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_O_MU_Edit_ph0.inc.php beendet</pre>";
}
?>