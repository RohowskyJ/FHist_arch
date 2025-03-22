<?php 

/**
 * Benutzervrwaltung, Warten, Formular
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
if ($debug) {echo "<pre class=debug>VF_Z_B_Edit_ph0.inc.php ist gestarted</pre>";}

echo "<div class='white'>";

echo "<input type='hidden' name='be_id' value='".$neu['be_id']."' />";
echo "<input type='hidden' name='be_mitglnr' value='".$neu['be_mitglnr']."' />";
echo "<input type='hidden' name='eig_id' value='".$neu['eig_id']."' />";
# =========================================================================================================
  Edit_Tabellen_Header('Benutzer '.$neu['be_vname']." ".$neu['be_name']);
# =========================================================================================================

  Edit_Daten_Feld('be_id');
  Edit_Daten_Feld('be_mitglnr');
 
  # =========================================================================================================
  Edit_Separator_Zeile('Organisation, für Berechtigungen Sachgebiet FG - LFKDOs der Bundesländer wenn kein Mitglied');
  # =========================================================================================================
  Edit_Radio_Feld(Prefix.'be_org_typ',VF_Eig_Org_Typ);
#  Edit_Daten_Feld('be_org_typ',15);
  Edit_Daten_Feld('be_org_name',100);
  
  # =========================================================================================================
  Edit_Separator_Zeile('Benutzer');
  # =========================================================================================================
  Edit_Daten_Feld('be_anrede',15);
  Edit_Daten_Feld('be_titel',15);
  Edit_Daten_Feld('be_vname',35);
  Edit_Daten_Feld('be_name',35);
  Edit_Daten_Feld('be_n_titel',15);
  Edit_Daten_Feld('be_adresse',35);
  Edit_Daten_Feld('be_plz',7);
  Edit_Daten_Feld('be_ort',60);
  $ST_Opt = VF_Sel_Staat('be_staat','9','');
  Edit_Select_Feld('be_staat',$ST_Opt);
  Edit_Daten_Feld('be_telefon',45);
  Edit_Daten_Feld('be_fax',45);
  Edit_Daten_Feld('be_handy',45);
  Edit_Daten_Feld('be_email',100);
  
  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
  Edit_Daten_Feld('be_uidaend');
  Edit_Daten_Feld('be_aenddat');
  
# =========================================================================================================
  Edit_Tabellen_Trailer();
  if ( $_SESSION[$module]['all_upd'] ) 
  {
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
          echo "<a href='VF_Z_Z_Edit.php?be_id=".$neu['be_id']."&benu=".$neu['be_vname']." ".$neu['be_name']."' target='zuber'>Berechtigungen verwalten</a>";
   }
  
  echo "<p><a href='VF_Z_B_List.php'>Zurück zur Liste</a></p>";
  
  
  echo "</div>";    
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_B_Edit_ph0.inc.php beendet</pre>";}
?>