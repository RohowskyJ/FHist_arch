<?php

/**
 * Liste der Veranstaltungstermine, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_TE_Edit_ph0.inc ist gestarted</pre>";
}

if ($neu['va_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}

$cdate = date("Y-m-d");

echo $Err_Msg;
echo "<input name='va_id' id='va_id' type='hidden' value='" . $neu['va_id'] . "' />";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Veranstaltungs- Daten');
# =========================================================================================================
if ($_SESSION[$module]['Act'] == 0) {
    $Edit_Funcs_Protect = True;
}
  if ( $neu['va_id'] !== 0 & $neu['va_datum']<$cdate & $neu['va_datum']<>'0000-00-00' )
  {
    echo "<h2>Daten können nicht mehr geändert (nicht in die Tabelle gesichert) werden. </h2>";
    $Edit_Funcs_Protect = true;
}

if ($neu['va_id'] == "0") {
    Edit_Daten_Feld('va_id', 0, 'Neue Veranstaltung');
}

if (! empty($neu['va_angelegt'])) { # =========================================================================================================//
    Edit_Separator_Zeile("Veranstaltungs- Status");
    # =========================================================================================================
    Edit_Daten_Feld('va_angelegt', 0, ' von ' . $neu['va_ang_uid']);
    if (! is_null($neu['va_aenderung'])) {
        Edit_Daten_Feld('va_aenderung', 0, ' von ' . $neu['va_aend_uid']);
    }
}

# =========================================================================================================
Edit_Separator_Zeile('Datum und Zeit');
# =========================================================================================================
$min_date = date('Y-m-d');
Edit_Daten_Feld('va_datum', 10, '', "type='date' required min='$cdate'");
Edit_Daten_Feld('va_begzt', 5, 'Format: hh:mm', "type='time'");

Edit_Daten_Feld('va_end_dat', 10, '', "type='date'   min='$cdate'");

Edit_Daten_Feld('va_endzt', 5, 'Format: hh:mm', "type='time'");

# =========================================================================================================
Edit_Separator_Zeile('Titel und Beschreibung');
# =========================================================================================================
Edit_Daten_Feld('va_titel', 100, '', 'required');
Edit_textarea_Feld('va_beschr', '', 'cols=70 rows=4');
Edit_Select_Feld('va_umfang', VF_Term_Umfang);
Edit_Select_Feld('va_kateg', VF_Term_Kateg);

Edit_Select_Feld('va_anm_erf', VF_JN);

# =========================================================================================================
Edit_Separator_Zeile('Veranstaltungs- Ort');
# =========================================================================================================
Edit_Daten_Feld('va_inst', 50);
Edit_Daten_Feld('va_adresse', 50);
Edit_Daten_Feld('va_plz', 10);
Edit_Daten_Feld('va_ort', 50);

$ST_Opt = VF_Sel_Staat('va_staat', '9');
Edit_Select_Feld('va_staat', $ST_Opt);
# Edit_Daten_Feld('va_staat',50);

$stabkz = $neu['va_staat'];
$va_bdld = $neu['va_bdld'];
$ST_bdld = VF_Sel_Bdld($va_bdld, 8, $stabkz);
Edit_Select_Feld('va_bdld', $ST_bdld);
# Edit_Daten_Feld('va_bdld',50);

# =========================================================================================================
$checked_f = "";
if ($hide_area == 0) {  //toggle??
    $checked_f = 'checked';
}
// Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
$button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
Edit_Separator_Zeile('Fotos',$button_f);  #
# =========================================================================================================

#Edit_Daten_Feld('va_bild', 50);
echo "<input type='hidden' name='va_bild_1' value='" . $neu['va_bild_1'] . "'>";
echo "<input type='hidden' name='va_bild_2' value='" . $neu['va_bild_2'] . "'>";
echo "<input type='hidden' name='va_bild_3' value='" . $neu['va_bild_3'] . "'>";
echo "<input type='hidden' name='va_bild_4' value='" . $neu['va_bild_4'] . "'>";


$cjahr = substr($neu['va_datum'],0,4); #date('Y');

echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
$pict_path = $path2ROOT . "login/AOrd_Verz/Termine/" . $cjahr . "/";

echo "<input type='hidden' id='urhNr' value=''>";
echo "<input type='hidden' id='aOrd' value=''>";

echo "<input type='hidden' id='reSize' value='1754'>";

$Feldlaenge = "100px";

$_SESSION[$module]['Pct_Arr' ] = array();
$num_foto = 4;
$i = 1;
while ($i <= $num_foto) {
    $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => '', 'bi' => 'va_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
    
    echo "<input type='hidden' id='aOrd_$i' value='/Termine/".$cjahr."/'>";
    $i++;
}

VF_Upload_Form_M();
#===================================================

Edit_Daten_Feld('va_internet', 50);
Edit_Daten_Feld('va_anm_text', 50);
Edit_Daten_Feld('va_anmeld_end', 10, '', "type='date'   min='$cdate'");

if ($neu['va_id'] == 1) {
    $Edit_Funcs_Protect = false;
}

if ($_SESSION[$module]['Act'] == 1) {
    # =========================================================================================================
    # Edit_Separator_Zeile('Platzplanung');
    # =========================================================================================================
    Edit_Daten_Feld('va_raum', 50, ' in Lokation');
    Edit_Daten_Feld('va_plaetze', 5, '', 'type="number" min=0 max =9999 required');
    Edit_Daten_Feld('va_warte', 5, '', 'type="number" min=0 max=9999 required');

    if ($neu['va_id'] !== "0") { # =========================================================================================================
        Edit_Separator_Zeile('Aktuelle Platzbelegung');
        # =========================================================================================================
        Edit_Daten_Feld('va_akt_pl');
        Edit_Daten_Feld('va_wl_pl');
        Edit_Daten_Feld('va_anz_anmeld');
    }

    # =========================================================================================================
    Edit_Separator_Zeile('Kostenbeteiligungen, Verantwortlicher');
    # =========================================================================================================

    Edit_Daten_Feld('va_beitrag_m', 10);

    Edit_Daten_Feld('va_beitrag_g', 10);
    Edit_Daten_Feld('va_kontakt', 50);
    Edit_Daten_Feld('va_admin_email', 50);
    Edit_Daten_Feld('va_link_einladung', 100);

    $Edit_Funcs_Protect = False;
    if (! empty($neu['va_angelegt'])) {}
}

# =========================================================================================================
Edit_Tabellen_Trailer();

if ($cdate > $neu['va_datum'] && $neu['va_id'] !== 0) {} else {
    if ($_SESSION[$module]['all_upd']) {
        echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
        echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
    }
}

echo "<p><a href='VF_O_TE_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_toggle.js' ></script>";

# =========================================================================================================
if ($debug) {
    echo "<pre class=debug>VF_O_TE_Edit_ph0.php beendet</pre>";
}
?>