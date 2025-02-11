<?php 
/**
 * Wartung Archivalien, Formular
 *
 * @author josef Rohowsky - neu 2019
 *
 */
if ($debug) {echo "<pre class=debug>VF_A_AR_Edit_ph0.inc.php ist gestarted</pre>";}
#  print_r($neu);echo "<br>L 009 neu <br>";

$inveignr = $neu['ad_eignr'];

$pref_eignr_s = "0".$inveignr;   //  Kurz- Variante
$pref_eignr = substr("00000",1,5-strlen($inveignr)).$inveignr;

$ad_fl = $neu['ad_ao_fortlnr'];
$pref_invnr_s = "0".$ad_fl;    //  kurz- Variante
$ad_flnr = substr("000000",1,6-strlen($ad_fl)).$ad_fl;
$neu['ArchNr'] = "<b>$pref_eignr-".$neu['ad_sg'].".".$neu['ad_subsg'].".".$neu['ad_lcsg'].".".$neu['ad_lcssg']."-".$ad_flnr." </b> oder <b>V:$pref_eignr_s-".$neu['ad_sg'].".".$neu['ad_subsg'].".".$neu['ad_lcsg'].".".$neu['ad_lcssg']."-$pref_invnr_s  </b>";
$Tabellen_Spalten_COMMENT['ArchNr'] = "Volle oder Kurze Archivnummer '";

echo "<input type='hidden' name='ad_id' value='".$neu['ad_id']."'/>";
echo "<input type='hidden' name='ad_eignr' id='ad_eignr' value='".$neu['ad_eignr']."'/>";
echo "<input type='hidden' name='ad_ao_fortlnr' value='".$neu['ad_ao_fortlnr']."'/>";
echo "<input type='hidden' name='ad_sg' value='".$neu['ad_sg']."'/>";
echo "<input type='hidden' name='ad_subsg' value='".$neu['ad_subsg']."'/>";
echo "<input type='hidden' name='ad_lcsg' value='".$neu['ad_lcsg']."'/>";
echo "<input type='hidden' name='ad_lcssg' value='".$neu['ad_lcssg']."'/>";

echo "<input type='hidden' name='ad_uidaend' id='ad_uidaend' value='".$neu['ad_uidaend']."'/>";
echo "<input type='hidden' name='ad_aenddat' id='ad_aenddat' value='".$neu['ad_aenddat']."'/>";
# =========================================================================================================
  Edit_Tabellen_Header("Archivalien");
# =========================================================================================================

  Edit_Daten_Feld('ad_id');
  Edit_Daten_Feld('ad_eignr');
  Edit_Daten_Feld('ArchNr');
  if ($Error_Msg != "") {
      echo "<div class='error'>$Error_Msg</div>";
  }
  # =========================================================================================================
  Edit_Separator_Zeile('Einordnung laut Archivordnung');
  # =========================================================================================================
 
  if ($neu['ad_id'] == "0") {
      /**
       * Parameter für den Aufruf von Multi-Dropdown
       *
       * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
       *
       *
       * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
       * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
       * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
       *
       * @Input-Parm $_POST['Level1...6']
       */
      
      $MS_Txt = array(
          'Auswahl der Obersten (ÖBFV)- Ebene  ',
          'Auswahl der 2. ÖBFV- Ebene ',
          'Auswahl der Lokalen Erweiterung (3. Ebene) ',
          'Auswahl des 2. Erweiterung (4. Ebene)  ',
          'Auswahl des 3. Erweiterung (5. Ebene)  ',
          'Auswahl des 4. Erweiterung (6. Ebene)  '
      );
      
      $MS_Lvl   = 6; # 1 ... 6
      $MS_Opt   = 2; # 1: SA für Sammlung, 2: AO für Archivordnung
      
      switch ($MS_Opt) {
          case 1:
              $in_val = 'PA_R';
              $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
              break;
          case 2:
              $in_val = '07';
              $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
              break;
      }
      
      
      $titel  = 'Auswahl aus der Archivordnung';
      VF_Multi_Dropdown($in_val,$titel);
      
  } else {
      $Aro_Def = $Arl_Def = "";
      $Aro_Def = VF_Displ_Aro($neu['ad_sg'],$neu['ad_subsg']);
      $Arl_Def = VF_Displ_Arl($neu['ad_sg'],$neu['ad_subsg'],$neu['ad_lcsg'],$neu['ad_lcssg']);
      echo "<tr><td>Eingereiht nach  Archivordnung: </td><td colspan = '5'>$Aro_Def $Arl_Def </td></tr>";
  }
  
  # =========================================================================================================
  Edit_Separator_Zeile('Archivalien- Beschreibung');
  # =========================================================================================================

  Edit_textarea_Feld('ad_beschreibg');
  Edit_textarea_Feld('ad_keywords');
  Edit_textarea_Feld('ad_namen');
  Edit_Daten_Feld('ad_doc_date', 10,'',"type='date'");
 
  Edit_Daten_Feld('ad_isbn',15 );

  Edit_Select_Feld('ad_type',VF_Arc_Type);
  Edit_Select_Feld('ad_format',VF_Arc_Format);

  # =========================================================================================================
  Edit_Separator_Zeile('Dokument');
  # =========================================================================================================
 
  echo "<input type='hidden' name='ad_doc_1' value='".$neu['ad_doc_1']."'>";
  echo "<input type='hidden' name='ad_doc_2' value='".$neu['ad_doc_2']."'>";
  echo "<input type='hidden' name='ad_doc_3' value='".$neu['ad_doc_3']."'>";
  echo "<input type='hidden' name='ad_doc_4' value='".$neu['ad_doc_4']."'>";

  $pict_path = "AOrd_Verz/".$neu['ad_eignr']."/".$neu['ad_sg']."/".$neu['ad_subsg']."/";
 
  $doc_1 = $doc_2 = $doc_3 = $doc_4 = "";
  if ($neu['ad_doc_1'] <> "") {
      $doc_1 = "<a href='$pict_path".$neu['ad_doc_1']."' target='Doc_1'>".$neu['ad_doc_1']."</a>";
  }
  if ($neu['ad_doc_2'] <> "") {
      $doc_2 = "<a href='$pict_path".$neu['ad_doc_2']."' target='Doc_2'>".$neu['ad_doc_2']."</a>";
  }
  if ($neu['ad_doc_3'] <> "") {
      $doc_3 = "<a href='$pict_path".$neu['ad_doc_3']."' target='Doc_3'>".$neu['ad_doc_3']."</a>";
  }
  if ($neu['ad_doc_4'] <> "") {
      $doc_4 = "<a href='$pict_path".$neu['ad_doc_4']."' target='Doc_4'>".$neu['ad_doc_4']."</a>";
  } 
  echo "$doc_1 ";
  Edit_Upload_File('ad_doc_1'          # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,'1'    # Identifier bei mehreren uploads
      ) ;
  echo "<br>";
  echo "$doc_2 ";
  Edit_Upload_File('ad_doc_2'          # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,'2'    # Identifier bei mehreren uploads
      ) ;
  echo "<br>";
  echo "$doc_3";
  Edit_Upload_File('ad_doc_3'         # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,'3'    # Identifier bei mehreren uploads
      ) ;
  echo "<br>";
  echo "$doc_4";

  Edit_Upload_File('ad_doc_4'          # Array index Name in $neu[] und $Tabellen_Spalten_Titel[]
      ,'4'    # Identifier bei mehreren uploads
      ) ; 
  
  Edit_Daten_Feld('ad_neueigner');
  echo "<input type='hidden' name=''ad_neueigner' val='".$neu['ad_neueigner']."' >";
  $tit_eig_leih = "Neuen Eigentümer";
  VF_Eig_Ausw();
  
  # =========================================================================================================
  Edit_Separator_Zeile('Werte');
  # =========================================================================================================
  Edit_Daten_Feld('ad_wert_orig',20 );
  Edit_Daten_Feld('ad_orig_waehrung', 50);
  Edit_Daten_Feld('ad_wert_kauf',20);
  Edit_Daten_Feld('ad_kauf_waehrung',50 );
  Edit_Daten_Feld('ad_wert_besch',20 );
  Edit_Daten_Feld('ad_besch_waehrung', 50);
  # =========================================================================================================
  Edit_Separator_Zeile('Lagerort');
  # =========================================================================================================
  Edit_Daten_Feld('ad_lagerort',100 );
  
  Edit_Daten_Feld('ad_l_raum',50 );
  Edit_Daten_Feld('ad_l_kasten',50 );
  Edit_Daten_Feld('ad_l_fach',50 );
  Edit_Daten_Feld('ad_l_pos_x',50 );
  Edit_Daten_Feld('ad_l_pos_y',50 );
  /* temp aktiv */

  # =========================================================================================================
  Edit_Separator_Zeile('Letzte Änderung');
  # =========================================================================================================
 
  Edit_Daten_Feld('ad_uidaend');
  Edit_Daten_Feld('ad_aenddat' );
  # =========================================================================================================
 Edit_Tabellen_Trailer();
  
  // Verleihdaten fehlen noch
  
  if ( $_SESSION[$module]['all_upd']  ) {
      echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
      echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
  }

  echo "<p><a href='VF_A_AR_List.php'>Zurück zur Liste</a></p>";
           

# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_A_AR_Edit_ph0.inc beendet</pre>";}
?>