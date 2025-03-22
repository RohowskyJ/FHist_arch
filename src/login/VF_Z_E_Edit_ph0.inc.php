<?php 

/**
 * Liste der Eigentümer, Wartung, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {echo "<pre class=debug>VF_Z_E_Edit_ph0.inc.php ist gestarted</pre>";}

echo "<div class='white'>";

echo "<input type='hidden' name='ei_id' value='".$neu['ei_id']."' />";
echo "<input type='hidden' name='ei_mitglnr' value='".$neu['ei_mitglnr']."' />";
# =========================================================================================================
  Edit_Tabellen_Header('Eigentümer: ');
# =========================================================================================================

  Edit_Daten_Feld('ei_id');
  if ($neu['ei_id'] == 0) {
      Edit_Daten_Feld('ei_mitglnr',15);
  } else {
      Edit_Daten_Feld('ei_mitglnr');
  }
  
 
  # =========================================================================================================
  Edit_Separator_Zeile('Organisations- Daten');
  # =========================================================================================================

  Edit_Radio_Feld(Prefix.'ei_org_typ',VF_Eig_Org_Typ);
  
  Edit_Daten_Feld('ei_org_name',100);
  $ST_Opt = VF_Sel_Staat('ei_staat','9','');
  Edit_Select_Feld('ei_staat',$ST_Opt);
  $BL_Opt = VF_Sel_Bdld('ei_bdld','9','');
  Edit_Select_Feld('ei_bdld',$BL_Opt);
  Edit_Daten_Feld('ei_bezirk',2);

  Edit_Daten_Feld('ei_fwkz',2);
  Edit_Daten_Feld('ei_grdgj',10);
  Edit_Daten_Feld('ei_adresse',35);
  Edit_Daten_Feld('ei_plz',7);
  Edit_Daten_Feld('ei_ort',60);
  Edit_Daten_Feld('ei_tel',45);
  Edit_Daten_Feld('ei_fax',45);
  Edit_Daten_Feld('ei_handy',45);
  Edit_Daten_Feld('ei_email',100);
  Edit_Daten_Feld('ei_internet',100);
  #Edit_Daten_Feld('ei_sterbdat',10,'',"type='date'");
  Edit_Daten_Feld('ei_abgdat',10,'',"type='date'");
  Edit_Daten_Feld('ei_neueigner',50);
  # =========================================================================================================
  Edit_Separator_Zeile('Urheber- Daten');
  # =======================================================================================================
  Edit_Daten_Feld('ei_urh_kurzz',15,'Kurzzeichen für Foto- Name');
  Edit_Daten_Feld('ei_media',15,'A,F,V .. Audio,Foto,Video');
  # =========================================================================================================
  Edit_Separator_Zeile('Verantwortlicher (Bei Organisaton)');
  # =========================================================================================================
  Edit_Daten_Feld('kont_name',100);
  Edit_Daten_Feld('ei_titel',10);
  Edit_Daten_Feld('ei_vname',35);
  Edit_Daten_Feld('ei_name',60);
  Edit_Daten_Feld('ei_dgr',10);
  
  # =========================================================================================================
  Edit_Separator_Zeile('Einverständnis zur Veröffentlichung');
  # =========================================================================================================
  Edit_Select_Feld('ei_wlpriv', VF_JN);
  Edit_Select_Feld('ei_vopriv', VF_JN);
  Edit_Select_Feld('ei_wlmus', VF_JN);
  Edit_Select_Feld('ei_vomus', VF_JN);
  Edit_Select_Feld('ei_wlinv', VF_JN);
  Edit_Select_Feld('ei_voinv', VF_JN);
  Edit_Select_Feld('ei_voinf', VF_JN);
  Edit_Select_Feld('ei_vofo', VF_JN);
  Edit_Select_Feld('ei_voar', VF_JN);
  Edit_Daten_Feld('ei_drwvs',10,'',"type='date'");
  Edit_Select_Feld('ei_drneu', VF_JN);

  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
  Edit_Daten_Feld('ei_uidaend');
  Edit_Daten_Feld('ei_aenddat');
 
# =========================================================================================================
  Edit_Tabellen_Trailer(); 

  if ($neu['ei_org_typ'] != "Privat" || strlen($neu['ei_media']) >2) {
      require "VF_Z_E_U_List.inc.php";
  }
  
  if ( $_SESSION[$module]['all_upd'] ) 
  {
          echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
          echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
   }
  
  echo "</div>";    
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_Z_E_Edit_ph0.inc.php beendet</pre>";}
?>