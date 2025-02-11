<?php

/**
 * Liste der Veranstaltungsberichte, Wartung, Eingabe Berichts/Foto Datum
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph95.inc.php ist gestarted</pre>";}

echo $Err_Msg ;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='".$neu['vb_flnr']."' />";

$Edit_Funcs_FeldName = false;  // Feldname der Tabelle wird nicht angezeigt !!
# =========================================================================================================
Edit_Tabellen_Header();
# =========================================================================================================

# =========================================================================================================
Edit_Separator_Zeile('Veranstaltungs- Datum eingeben');
# =========================================================================================================

echo "<tr><th colspan='2'> &nbsp; &nbsp; </th<th><input type='date' name='vb_datum' id='vb_datum' value='".$neu['vb_datum']."'></td></tr>";

# =========================================================================================================
Edit_Tabellen_Trailer();
    
echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='96' class='green'>Fotos für das eingegebene Datum suchen</button></p>";

echo "<p><a href='VF_BE_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";
       
if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph95.inc.php beendet</pre>";}
?>