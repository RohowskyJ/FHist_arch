<?php 

/**
 * Lste der Presse, Wartu, Formular
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */

if ($debug) {echo "<pre class=debug>VFH_O_PR_Edit_ph0.php ist gestarted</pre>";}

if ($neu['pr_id'] == 0) { // Neueingabe
    $hide_area = 0;
} else {
    $hide_area = 1;
}
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
  $checked_f = "";
  if ($hide_area == 0) {  //toggle??
      $checked_f = 'checked';
  }
  // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
  $button_f = " &nbsp; &nbsp; <label><input type='checkbox' id='toggleGroup1' $checked_f > Foto Daten eingeben/ändern </label>";
  Edit_Separator_Zeile('Bilder ' ,$button_f);
  # =========================================================================================================
  echo "<input type='hidden' name='MAX_FILE_SIZE' value='400000' />";
  $pict_path = $path2ROOT."login/AOrd_Verz/Presse/";

 echo "<input type='hidden' name='pr_bild_1' value='".$neu['pr_bild_1']."' >";
 echo "<input type='hidden' name='pr_bild_2' value='".$neu['pr_bild_2']."' >";
 echo "<input type='hidden' name='pr_bild_3' value='".$neu['pr_bild_3']."' >";
 echo "<input type='hidden' name='pr_bild_4' value='".$neu['pr_bild_4']."' >";
 echo "<input type='hidden' name='pr_bild_5' value='".$neu['pr_bild_5']."' >";
 echo "<input type='hidden' name='pr_bild_6' value='".$neu['pr_bild_6']."' >";
 
 echo "<input type='hidden' id='urhNr' value=''>";
 echo "<input type='hidden' id='aOrd' value=''>";
 
 echo "<input type='hidden' id='reSize' value='1754'>";
 
 $Feldlaenge = "100px";
 
 $_SESSION[$module]['Pct_Arr' ] = array();
 $num_foto = 6;
 $i = 1;
 while ($i <= $num_foto) {
     $_SESSION[$module]['Pct_Arr' ][] = array('udir' => $pict_path, 'ko' => '', 'bi' => 'pr_bild_'.$i, 'rb' => '', 'up_err' => '','f1' => '','f2' => '');
     
     echo "<input type='hidden' id='aOrd_$i' value='Presse/'>";
     $i++;
 }
 
 VF_Upload_Form_M();
 #===================================================
# =========================================================================================================
  Edit_Tabellen_Trailer();
  if ( $_SESSION[$module]['all_upd']      ) 
  {
      echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
      echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
  }
  
  echo "<p><a href='VF_O_PR_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";
  
  echo "<script type='text/javascript' src='" . $path2ROOT . "login/VZ_toggle.js' ></script>";
  
# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VFH_O_PR_Edit_ph0.php beendet </pre>";}
?>