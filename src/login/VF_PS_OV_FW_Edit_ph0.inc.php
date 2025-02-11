<?php 
/**
 * Feuerwehr- Wappen- Wartung: Formular
 *
 * @author josef Rohowsky - neu 2019
 *
 */
if ($debug) {echo "<pre class=debug>VF_PS_OW_FW_Edit_ph0.inc.php ist gestarted</pre>";}

echo "<div class='white'>";

$fo_fw_id = $neu['fo_fw_id'];
echo "<input name='fo_fw_id' type='hidden' value='$fo_fw_id' > ";
echo "<input type='hidden' name='fo_id' value='$fo_id'/>";
# =========================================================================================================
  Edit_Tabellen_Header('Ortswappen bei der Feuerwehr');
# =========================================================================================================

  Edit_Daten_Feld('fo_id');
  Edit_Daten_Feld('fo_fw_id');
 
  # =========================================================================================================
  Edit_Separator_Zeile('Ortswappen- Daten');
  # =========================================================================================================

  Edit_Daten_Feld(Prefix.'fo_ff_w_sort',4);
 
  
  # =========================================================================================================
  Edit_Separator_Zeile('Abbild des Wappens');
  # =========================================================================================================   
  echo "<input type='hidden' name='fo_ff_wappen' value='".$neu['fo_ff_wappen']."'>";

  $pict_path = "AOrd_Verz//PSA/AERM/Wappen_FW/";
  $Feldlaenge = "100px";
  Edit_Show_Pict(Prefix.'fo_ff_wappen'         # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,$Feldlaenge = '300px'    # Feld Länge für Bildbreite
      );  # Attribut/Parametegde_w_komment'])) {$neu['fo_gde_w_komment'] = $neu['fo_gde_wappen'];}
      
      Edit_textarea_Feld(Prefix.'fo_ff_w_komm');
    
      
      Edit_Upload_File('fo_ff_wappen'          # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
          ,$Ident = '1'    # Identifier bei mehreren uploads
          ) ;
      

  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
  
  Edit_Daten_Feld(Prefix.'fo_aenduid');
  Edit_Daten_Feld(Prefix.'fo_aenddat');
  
# =========================================================================================================
  Edit_Tabellen_Trailer();
  

  if ( $_SESSION[$module]['all_upd'] ) {
 #          Edit_Send_Button(''); # definiert in Edit_Funcs_v2.php  
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
      }
      echo "<p><a href='VF_PS_OV_FW_Edit.php?fo_id=".$neu['fo_id']."'>Zurück zur Liste</a></p>"; 
      
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_PS_OW_FW_Edit_ph0.inc.php beendet</pre>";}
?>