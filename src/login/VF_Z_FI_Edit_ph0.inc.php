<?php 

/**
 * Benutzervrwaltung, Warten, Formular
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_FI_Edit_ph0.php";

if ($debug) {echo "<pre class=debug>VF_Z_FI_Edit_ph0.inc.php ist gestarted</pre>";}

echo "<div class='white'>";

echo "<input type='hidden' name='fi_id' value='".$neu['fi_id']."' />";

# =========================================================================================================
  Edit_Tabellen_Header('Firma');
# =========================================================================================================

  Edit_Daten_Feld('fi_id');
 
  
  Edit_Daten_Feld('fi_abk',20);
  
  Edit_Daten_Feld('fi_name',60);
  Edit_Daten_Feld('fi_ort',60);
  
  Edit_Select_Feld('fi_funkt', VF_Fahrz_Herst);
  
  Edit_Daten_Feld('fi_vorgaenger',60);
  
  Edit_Daten_Feld('fi_inet',60);

  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
  Edit_Daten_Feld('fi_uidaend');
  Edit_Daten_Feld('fi_aenddat');
  
# =========================================================================================================
  Edit_Tabellen_Trailer();
  if ( $_SESSION[$module]['all_upd'] ) 
  {
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
   }
  
  echo "<p><a href='VF_Z_FI_List.php'>Zurück zur Liste</a></p>";
  
  
  echo "</div>";    
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_FI_Edit_ph0.inc.php beendet</pre>";}
?>