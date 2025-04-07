<?php 

/**
 * Lste der Presse, Wartu, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */

if ($debug) {echo "<pre class=debug>VFH_O_PR_Edit_ph0.php ist gestarted</pre>";}

# =========================================================================================================
  Edit_Tabellen_Header('Pressebericht ');
# =========================================================================================================
  if ($_SESSION['VF_Prim']['p_uid'] == 999999999 ) {
      $Edit_Funcs_Protect = True;
  }
  Edit_Daten_Feld('pr_id');
  echo "<input type='hidden' name='pr_id' value='$pr_id' >";
  # =========================================================================================================
  Edit_Separator_Zeile('Aussendung');
  # =========================================================================================================
    
  Edit_Daten_Feld(Prefix.'pr_datum',10,'',"'type='date'");
  Edit_Daten_Feld(Prefix.'pr_name',50);
  Edit_Daten_Feld(Prefix.'pr_ausg',10);
  Edit_Radio_Feld(Prefix.'pr_medium',array ("PR"=>"Druck","TV"=>"Fensehen, Internet"));
  Edit_Daten_Feld(Prefix.'pr_seite',10);

  Edit_textarea_Feld(Prefix.'pr_teaser');
  Edit_textarea_Feld(Prefix.'pr_text');
 
  # =========================================================================================================
  Edit_Separator_Zeile('Bewertung');
  # =========================================================================================================
  Edit_Daten_Feld(Prefix.'pr_web_site',100);
  Edit_Daten_Feld(Prefix.'pr_web_text',50);
  Edit_Daten_Feld(Prefix.'pr_inet',100);
  
  # =========================================================================================================
  Edit_Separator_Zeile('Beschreiber');
  # =========================================================================================================
 
  Edit_Daten_Feld('pr_uidaend',5);
  Edit_Daten_Feld('pr_aenddat');
  

  # =========================================================================================================
  Edit_Separator_Zeile('Bilder ');
  # =========================================================================================================
  echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
  $pict_path = $path2ROOT."login/AOrd_Verz/Presse/";

 echo "<input type='hidden' name='pr_bild1' value='".$neu['pr_bild1']."' >";
 echo "<input type='hidden' name='pr_bild2' value='".$neu['pr_bild2']."' >";
 echo "<input type='hidden' name='pr_bild3' value='".$neu['pr_bild3']."' >";
 echo "<input type='hidden' name='pr_bild4' value='".$neu['pr_bild4']."' >";
 echo "<input type='hidden' name='pr_bild5' value='".$neu['pr_bild5']."' >";
 
 $Feldlaenge = "100px";
 
 $pic_arr = array("1"=>"|||pr_bild1"
     ,"2"=>"|||pr_bild2"
     ,"3"=>"|||pr_bild3"
     ,"4"=>"|||pr_bild4"
     ,"5"=>"|||pr_bild5"
 );
 VF_Multi_Foto($pic_arr)  ;
 
# =========================================================================================================
  Edit_Tabellen_Trailer();
  if ( $_SESSION[$module]['all_upd']      ) 
  {
      echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
      echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
  }
  
  echo "<p><a href='VF_O_PR_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";
  
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VFH_O_PR_Edit_ph0.php beendet </pre>";}
?>