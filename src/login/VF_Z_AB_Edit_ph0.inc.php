<?php 

/**
 * Benutzervrwaltung, Warten, Formularab_
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_AB_Edit_ph0.inc.php";

if ($debug) {echo "<pre class=debug>VF_Z_AB_Edit_ph0.inc.php ist gestarted</pre>";}

echo "<div class='white'>";

echo "<input type='hidden' name='ab_id' value='".$neu['ab_id']."' />";
# echo "<input type='hidden' name='ab_grp' value='".$neu['ab_grp']."' />";

# =========================================================================================================
  Edit_Tabellen_Header('Abkürzung' );
# =========================================================================================================

  Edit_Daten_Feld('ab_id');;
 
  # =========================================================================================================
  Edit_Separator_Zeile('Abkürzung für : ');
  # =========================================================================================================
  
  Edit_Select_Feld('ab_grp', VF_Abk);
  Edit_Daten_Feld('ab_abk',40);
  Edit_Daten_Feld('ab_bezeichn',100,5);
 
  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
  Edit_Daten_Feld('ab_uidaend');
  Edit_Daten_Feld('ab_aenddat');
  
# =========================================================================================================
  Edit_Tabellen_Trailer();
  if ( $_SESSION[$module]['all_upd'] ) 
  {
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
   }
  
  echo "<p><a href='VF_Z_B_List.php'>Zurück zur Liste</a></p>";
  
  
  echo "</div>";    
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_AB_Edit_ph0.inc.php beendet</pre>";}
?>