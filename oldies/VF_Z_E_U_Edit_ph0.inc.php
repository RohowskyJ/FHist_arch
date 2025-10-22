<?php

/**
 * Foto Urheber, Wartung, Formular
 * 
 * @author Josef Rohowsky - neu 2019
 *  
 */
if ($debug) {
    echo "<pre class=debug>VF_FO_U_Edit_ph0.inc ist gestarted</pre>";
}

echo "<input type='hidden' name='fm_id' value='".$neu['fm_id']."' />";

# =========================================================================================================
Edit_Tabellen_Header('Urheber- Daten');
# =========================================================================================================

Edit_Daten_Feld('fm_id');
if ($neu['fm_id'] == 0 || $neu['fm_eigner'] == "") {
    Edit_Daten_Feld_auto('fm_eigner',75,'','','srch_eigent');
} else {
    Edit_Daten_Feld('fm_eigner');

    echo "<input type='hidden' name='fm_eigner' value='" . $neu['fm_eigner'] . "' />";
}
Edit_Daten_Feld('fm_urheber', 50);

Edit_Daten_Feld('fm_urh_kurzz', 15, '');

# =========================================================================================================
Edit_Separator_Zeile('Medium: Foto - Video');
# =========================================================================================================
Edit_Radio_Feld('fm_typ', VF_Foto_Video);

# =========================================================================================================
Edit_Separator_Zeile('Letzte Änderung');
# =========================================================================================================
Edit_Daten_Feld('fm_uidaend');
Edit_Daten_Feld('fm_aenddat');
# =========================================================================================================
Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_FO_List.php?ID=NextEig'>Zurück zur Liste</a></p>";

if ($neu['fm_id'] != 0 ) {
    foreach ( $neu as $key => $value) {
        if (is_numeric($key) ) {continue;}
        
        $_SESSION[$module]['Fo']['URHEBER'][$key] = $value;
    }
    require "!VF_FO_U_Li_Su.inc.php";
    
    echo "<p><a href='VF_FO_List.php?ID=NextEig'>Zurück zur Liste</a></p>";
}

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_U_Edit_ph0.inc beendet</pre>";
}
?>