<?php
if ($debug) {echo "<pre class=debug>VF_O_BE_DE_Edit_ph0.inc ist gestarted</pre>";}
#----------------------------------------------------------------------------------
# VA_Edit_v3ph0.php - Edit der Veranstaltungs-daten va_daten Phase 0 - Version 2 by Josef Rohowsky, following B.Richard Gaicki
#
# change Avtivity:
#   2019       Josef Rohowsky  - neu
#----------------------------------------------------------------------------------

  echo $Err_Msg ;
  echo "<input name='vd_flnr' id='vd_flnr' type='hidden' value='".$neu['vd_flnr']."' />";
  echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='".$neu['vb_flnr']."' />";
  echo "<input name='vb_unter' id='vb_unter' type='hidden' value='".$neu['vb_unter']."' />";
  echo "<input name='vb_suffix' id='vb_suffix' type='hidden' value='".$neu['vb_suffix']."' />";

  $Edit_Funcs_FeldName = false;  // Feldname der Tabelle wird nicht angezeigt !!
# =========================================================================================================
  Edit_Tabellen_Header();
# =========================================================================================================
  if ($_SESSION['VF_Prim']['p_uid'] == 999999999 ) {
      $Edit_Funcs_Protect = True;
  }
  Edit_Daten_Feld('vd_flnr') ;
  Edit_Daten_Feld('vb_flnr') ;
  Edit_Daten_Feld('vb_unter') ;
  Edit_Daten_Feld('vb_suffix') ;

  # =========================================================================================================
  Edit_Separator_Zeile('Titel, Beschreibung, Foto');
  # =========================================================================================================
  
  Edit_Daten_feld('vb_titel',80);
  Edit_textarea_Feld('vb_beschreibung','','cols=70 rows=4');
  
  Edit_Daten_Feld('vb_foto',60);
  echo "<input type='hidden' name='vb_foto' value='".$neu['vb_foto']."'>";
  $pict_path = $path2ROOT."login/AOrd_Verz/".$neu['vb_foto_Urheber'];
  if ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "F") {
      $pict_path .= "/09/06/";
  } elseif ($_SESSION[$module]['Fo']['URHEBER']['fm_typ'] == "V") {
      $pict_path .= "/09/10/";
  }
  if ($_SESSION[$module]['fo_aufn_d'] != "" ) {
      $pict_path .= $_SESSION[$module]['fo_aufn_d']."/";
  }

  $Feldlaenge = "100px";
  
  Edit_Upload_File('vb_foto'          # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,$Ident = '1'    # Identifier bei mehreren uploads
      ,'' , '');  # Attribut/Parameter für <input tag
  
  if ($neu['vb_foto'] <> "") {
      VF_Displ_Urheb_n($neu['vb_foto_Urheber']);
      $fo_dsn = $neu['fo_dsn'];
      $DsName = $pict_path.$fo_dsn;
      $image1 = "<img src='$DsName' alt='Foto1' width='100px'/> ";
      $vb_foto = "<a href=\"$DsName\" target='Foto'> $image1 </a><br>".$_SESSION[$module]['Fo']['URHEBER']['fm_beschreibg'];
      echo "<tr><td>Anzeige des Fotos:</td><td>$vb_foto</td></tr>";
  }
      
  echo "<tr><th colspan='5'>$Err_Urheb</th></tr>";
  $urheb_arr[] = $neu['vb_foto_Urheber'];
  VF_Sel_Urheber_n();
  foreach ($_SESSION[$module]['Urheber_List'] as $key ) {
      
  }
      Edit_Select_Feld('vb_foto_Urheber', $urheb_arr);
      
  # =========================================================================================================
  Edit_Separator_Zeile('Änderungen');
  # =========================================================================================================
  Edit_Daten_Feld('vb_uid');
  Edit_Daten_Feld('vb_aenddat');
      
# =========================================================================================================
  Edit_Tabellen_Trailer();
 
  if ($_SESSION[$module]['all_upd']) {
      echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
      echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
  }
  
  echo "<p><a href='VF_O_BE_List.php?Act=?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";
  
# =========================================================================================================
if ($debug) {echo "<pre class=debug>VF_O_BE_DE_edit_v3ph0.php beendet</pre>";}
?>