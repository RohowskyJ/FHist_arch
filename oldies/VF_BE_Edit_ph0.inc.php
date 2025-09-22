<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_BE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo $Err_Msg;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='" . $neu['vb_flnr'] . "' />";
echo "<input name='vb_datum' id='vb_datumr' type='hidden' value='" . $neu['vb_datum'] . "' />";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Daten der Verastaltung');
# =========================================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $Edit_Funcs_Protect = True;
}

Edit_Daten_Feld('vb_flnr');
# =========================================================================================================
Edit_Separator_Zeile('Datum');
# =========================================================================================================

# Edit_Daten_Feld('vb_datum', 10, '', "type='date' ");
Edit_Daten_Feld('vb_datum');

# =========================================================================================================
Edit_Separator_Zeile('Titel, Beschreibung, Foto');
# =========================================================================================================

Edit_Daten_feld('vb_titel', 80);
Edit_textarea_Feld('vb_beschreibung', '', 'cols=70 rows=4');

if ($neu['vb_flnr'] == 0) {
    $us_arr = array(
        "Unterseiten" => "mit Unterseiten",
        "Keine" => "Keine Unterseiten"
    );
    Edit_Radio_Feld('vb_unterseiten', $us_arr);
} else {
    $Edit_Funcs_protect = True;
    
    $us_arr = array(
        "Unterseiten" => "mit Unterseiten",
        "Keine" => "Keine Unterseiten"
    );
    Edit_Radio_Feld('vb_unterseiten', $us_arr);
    $Edit_Funcs_protect = False;
}

Edit_Separator_Zeile('Änderungen');

Edit_Daten_Feld('vb_uid');
Edit_Daten_Feld('vb_aenddat');

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_O_BE_List.php?Act=?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

if ($_SESSION[$module]['Fo']['FOTO']) {
    foreach ($eig_foto as $key) {
        $farr = explode("|", $key);
        
        if (!isset($farr[1])) {
            $farr[1] = "";
        }

        $fo_arr[$farr[0]] [] = $farr[1];
        VF_Displ_Urheb_n($farr[0]);
        VF_Displ_Eig($farr[0]);
        $tabelle = "fo_todaten";
    }
   
    require "VF_FO_List_Bericht.inc.php";
} else {
    $tabelle = "vb_ber_detail_4";
    /**
     * Phase, in der die EIngabe in der Tabelle landen soll
     *
     * @var string $TabButton 0: phase, 1: Farbe, 2: Text, 3: Rücksprung-Link
     */
    $TabButton = "2|green|Bilder für den Bericht speichern.|"; #
    require "VF_FO_List_Ber_Det.inc.php";
}

echo "<p><a href='VF_BE_List.php?Act=" . $_SESSION[$module]['Act'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================
if ($debug) {
    echo "<pre class=debug>VF_BE_edit.ph0.php beendet</pre>";
}

?>