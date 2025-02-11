<?php 

/**
 * Wartung der Archive und Bibliotheken, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
if ($debug) {echo "<pre class=debug>VFH_O_AR_Edit_ph0.php ist gestarted</pre>";}

echo "<input type='hidden' name='fa_id' value='".$neu['fa_id']."' >";

# =========================================================================================================
Edit_Tabellen_Header('Bibliotheks- und Archiv- Links'); 
# =========================================================================================================

  Edit_Daten_Feld('fa_id');
  
  # =========================================================================================================
  Edit_Separator_Zeile('Link- Daten');
  # =========================================================================================================
    
  Edit_Daten_Feld(Prefix.'fa_link',50);
  Edit_Daten_Feld(Prefix.'fa_text',100);

# =========================================================================================================
  Edit_Tabellen_Trailer();

  if ( $_SESSION[$module]['all_upd'] )
       {
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
      }
      
      if ($_SESSION[$module]['Act'] == 1) {
          echo "<p><a href='VF_O_AR_List.php?Act=l'>Zurück zur Liste</a></p>";
      } else {
          echo "<p><a href='VF_O_AR_List.php?Act=0'>Zurück zur Liste</a></p>";
      }
      
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_O_AR_Edit_ph0.inc.php beendet</pre>";}
?>