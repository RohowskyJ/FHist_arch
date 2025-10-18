<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_BE_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_BE_Edit_ph0.inc.php ist gestarted</pre>";
}
# var_dump($neu);
echo $Err_Msg;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='" . $neu['vb_flnr'] . "' />";
echo "<input name='vb_datum' id='aufnDat' type='hidden' value='" . $neu['vb_datum'] . "' />";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!

# =========================================================================================================
Edit_Tabellen_Header('Daten der Veranstaltung');
# =========================================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $Edit_Funcs_Protect = True;
}

Edit_Daten_Feld('vb_flnr');
# =========================================================================================================
Edit_Separator_Zeile('Datum');
# =========================================================================================================

if ($neu['vb_flnr'] == 0) {
    Edit_Daten_Feld('vb_datum', 10, '', "type='date' ");
} else {
    $Edit_Funcs_protect = True;
    Edit_Daten_Feld('vb_datum',10);
    $Edit_Funcs_protect = False;
}

# =========================================================================================================
Edit_Separator_Zeile('Titel, Beschreibung');
# =========================================================================================================

Edit_Daten_feld('vb_titel', 80);
Edit_textarea_Feld('vb_beschreibung', '', 'cols=70 rows=4');

if ($neu['vb_flnr'] == 0) {
    Edit_Select_Feld('vb_unterseiten', VF_Unterseiten,'Unterseiten einrichten'); 
    Edit_Select_Feld('vb_fzg_beschr', VF_Fahrzeugbeschr,' z. B. für Corso- Berichte'); 
} else {
    $Edit_Funcs_protect = True;
    Edit_Select_Feld('vb_unterseiten', VF_Unterseiten,'Unterseiten einrichten'); 
    $Edit_Funcs_protect = False;
}

$button_f = "";
#if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
    $button_f = " &nbsp; &nbsp; &nbsp; <button type='button' class='button-sm'  onclick='startAjax(0)'>Foto Daten eingeben/ändern</button>";
#}
Edit_Separator_Zeile('Fotos',$button_f);  #

echo "<input type='hidden' id='sammlung' value=''>";
echo "<input type='hidden' id='eigner' value=''>";

    # =========================================================================================================
    Edit_Separator_Zeile('Auswahl der Fotos'); 
    # =========================================================================================================
    
    
    ?>
   
    <!-- Bereich für die diversen Ausgaben von js  -->
    <input type="hidden" id="bild-datei-auswahl" name="bilder_datei" value="" />
    

    <div id="bild-vorschau-auswahl"></div>
    
    <!-- 
        Bild- AUswahl neu, direkt- Anzeige
     -->
     <div id='bilderAuswahl' class='w3-row'></div> <!-- für die grobe Auswahl -->
     <div id='bilderAnzEdit' class='w3-row'>
         <div id='bilderAnzChg' class='w3-twothird'></div>
         <div id='bilderAnzAdd' class='w3-third'></div>
     </div>
     <div class='inputFields' ></div>
  
    <!-- Galerie-Container für die Bildauswahl
    <div id="bild-galerie_0" style="display:block; border:1px solid #ccc; padding:10px;"></div> // none
     -->
    <!-- Dialog für die Bilder-Auswahl (separater Dialog, eigene IDs)
    <div id="dialog-bilder_0" style="display:block;">  // none
    <div id="bild-vorschau-dialog_0"></div>
    <div id="dateiname-dialog_0"></div>
    <input type="hidden" id="bild-datei-dialog_0">
    </div>
    <hr>
     -->
    <?php 

Edit_Separator_Zeile('Änderungen');

Edit_Daten_Feld('vb_uid');
Edit_Daten_Feld('vb_aenddat');

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    # echo "<button type='submit' id='new_upload_btn' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
    echo "<button type='button'  style='visibility:visible;' onclick='startAnal()'>Daten abspeichern</button>";
}

echo "<script src='" . $path2ROOT . "login/common/javascript/VF_SelFotoLibs.js' ></script>";

echo "<p><a href='VF_FO_Ber_List.php?Act=" . $_SESSION[$module][$sub_mod]['Act'] . "'>Zurück zur Liste</a></p>";

# =========================================================================================================
if ($debug) {
    echo "<pre class=debug>VF_BE_edit.ph0.php beendet</pre>";
}

?>