<?php

/**
 * Prüfung, welche Bilder oder Dokumente fehlen oder zuviel sind (Tabellen gegen Verzeichnisse)
 * 
 * @author Josef Rohowsky - neu 2015
 */
session_start(); # die SESSION am leben halten 
const Module_Name = 'ADM';
$module    = Module_Name;
# $tabelle = 'fh_fw_ort_ref';
const Prefix  = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True; $debug = False;   // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php'; // Diverse Unterprogramme VF_HTML_header('Mitglieder_Tabelle mit Sortierklick','','Admin');
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php' ;

setlocale (LC_CTYPE,"de_AT");   // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper
initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

VF_chk_valid();

VF_set_module_p();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

const AUSW = array( "AERM" => "Ärmelabzeichen, Wappen"
                   ,"AUSZ" => "Auszeichnungen"
                   ,"FZG"  => "Fahrzeuge und Geräte"
                   ,"INV"  => "Inventar"
                   ,"ARCH" => "Archiv"
                  );

const DIRS = array( "AERM" => "Ärmelabzeichen, Wappen"
                   ,"AUSZ" => "Auszeichnungen"
                   ,"FZG"  => "Fahrzeuge und Geräte"
                   ,"INV"  => "Inventar"
                   ,"ARCH" => "Archiv"
                  );

$db = LinkDB('Mem'); // Connect zur Datenbank


if ( isset($_POST['phase'] ) ) { $phase    = $_POST['phase'];} else { $phase    = 0; }
if (isset($_POST['projekt'])) {$projekt = $_POST['projekt'];}

if ($phase == 1) {
    
    $arr_fehl = array();
    $arr_zuviel = array();
    
    if ($projekt == "AERM") {
       $tab_arr = array("aw_ort_wappen","aw_ff_wappen","aw_aermel_abz");
       $pic_arr = array("AOrd_Verz/PSA/AERM/Wappen_Ort/","AOrd_Verz/PSA/AERM/Wappen_FW/","AOrd_Verz/PSA/AERM/Aermel_Abz/");
       $sta_arr = array("","","");
    }
    
    if ($projekt == "AUSZ") {
        $tab_arr = array("az_auszeich");
        $pic_arr = array("AOrd_Verz/PSA/AUSZ/");
        $sta_arr = array("AOrd_Verz/PSA/AUSZ/"); 
    }
    
    if ($projekt == "FZG") {
        $tab_arr = array("ma_fz_beschr_","ma_geraet_","mu_fahrzeug_","mu_geraet_");
        $pic_arr = array("AOrd_Verz/","AOrd_Verz/","AOrd_Verz/","AOrd_Verz/");
        $sta_arr = array("","","",""); 
    }
    

    if ($projekt == "INV") {
        $tab_arr = array("in_ventar_");
        $pic_arr = array("AOrd_Verz/INV/");
        $sta_arr = array(""); 
    }
    
    if ($projekt == "ARCH") {
       $tab_arr = array('ar_chivdt_');
       $pic_arr = array("");
       $sta_arr = array(""); 
    }
 
    $TC_Arr = array();
    $TS_Arr = array();
    $DC_Arr = array();
    $DS_Arr = array();
    
    $len_i = count($tab_arr);
  
    for ($i=0;$i<$len_i;$i++) {
  #  foreach($tab_arr as $tabelle) {
        $table = $pict_path = $stat_path = "";
        $table      = $tab_arr[$i];
        $pict_path  = $pic_arr[$i];
        $stat_path  = $sta_arr[$i];
        
        if ($projekt == "AERM") {
         
            $Return = Tab_Pict($table,$TC_Arr);
            $Return = Picts($pict_path,$DC_Arr);
            $Return = Stats($stat_path,$DS_Arr);
        }
        
        if ($projekt == "AUSZ") {
            
            $bld_arr = array();
            $path_arr = array();
            
            $sql = "SELECT fw_id,fw_st_abk,fw_st_name,fw_bd_abk,fw_bd_name from aw_ort_ref WHERE fw_st_abk in ('AT','CTIF','VEREINE','OERK' ) ORDER BY fw_st_abk,fw_bd_abk,fw_id ";
            
            $return = SQL_QUERY($db,$sql);
            
            While ($row = mysqli_fetch_object($return)) {
                # print_r($row);echo "<br>L 0129 row<br>";
                if ($row->fw_bd_abk == "") {
                    $row->fw_bd_abk = $row->fw_st_abk;
                    $row->fw_bd_name = $row->fw_st_name;
                }
                if ($row->fw_bd_name == "") {
                    $row->fw_bd_name = $row->fw_st_name;
                }
                
                $bld_arr[$row->fw_bd_abk] = $row->fw_id;
                $path_arr[$row->fw_bd_abk] = $row->fw_bd_name;
            }
            
            # print_r($bld_arr);echo "<br>L 0139 bld_arr<br>";
            # print_r($path_arr);echo "<br>L 0140 path_arr<br>";
            $first = true;
            foreach ($bld_arr as $grp => $fwk) {
                # echo "L 0147 grp $grp <br>";
                if ($grp == 'CTIF' || $grp == 'OERK' || $grp == 'VEREINE') {
                    if ($grp == 'CTIF') {
                        $table_x = 'az_ausz_ctif';
                    }
                    if ($grp == 'VEREINE') {
                        $table_x = 'az_ausz_ve';
                    }
                    $pict_path_x = $pict_path.$grp."/";
                    $stat_path_x  = $pict_path.$grp."/Stat/";
                    
                    $Return = Tab_Pict($table_x,$TC_Arr);
                    $Return = Picts($pict_path_x,$DC_Arr);
                    $Return = Stats($stat_path,$DS_Arr);

                } else {
                    $pict_path_x = $pict_path.$grp."/";
                    $stat_path_x  = $pict_path.$grp."/Stat/";
                    # echo "L 0164 table $table pict_path_x $pict_path_x <br>";
                    if ($first) {
                        $Return = Tab_Pict($table,$TC_Arr);
                        $first = false;
                    }
                    
                    $Return = Picts($pict_path_x,$DC_Arr);
                    $Return = Stats($stat_path,$DS_Arr);
                }
                
            }

        }
        
        if ($projekt == "FZG") {
            $step_arr = array('MaF','MuF');
            
            $ar_arr = $fo_arr = $fz_arr = $fm_arr = $ge_arr = $in_arr = $zt_arr = array();
            $maf_arr = $mag_arr = $muf_arr = $mug_arr = array();
            VF_tableExist();
            
            foreach ($step_arr as $Step) {
                if ($Step  == 'MaF' ) {
                    # print_r($maf_arr);echo "<br>L 0188 fz_arr <BR>";
                    foreach ($maf_arr as $file => $val) {
                        # echo "L 0190 $file <br>";
                        $f_arr = explode("_",$file);
                        $len = count($f_arr);
                        # echo "L 0185 $len <br>";
                        if ($len  == 3) {
                            $eigner = $f_arr[2];
                            $table_x =  $file;
                            $pict_path_x = "AOrd_Verz/".$eigner."/MaF";
                            # echo "L 0190 $pict_path $eigner  <br>";
                            $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                            $Return = Picts($pict_path_x,$DC_Arr);
                            $Return = Stats($stat_path,$DS_Arr);
                        } else {
                            continue;
                        }
                    }
                    foreach ($mag_arr as $file => $val) {
                        # echo "L 0207 $file <br>";
                        $f_arr = explode("_",$file);
                        $len = count($f_arr);
                        # echo "L 0185 $len <br>";
                        if ($len  == 3) {
                            $eigner = $f_arr[2];
                            $table_x =  $file;
                            $pict_path_x = "AOrd_Verz/".$eigner."/MaG";
                            # echo "L 0190 $pict_path $eigner  <br>";
                            $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                            $Return = Picts($pict_path_x,$DC_Arr);
                            $Return = Stats($stat_path,$DS_Arr);
                        } else {
                            continue;
                        }
                    }
                    
                }
                if ($Step  == 'MuF' ) {
                    #print_r($ge_arr);echo "<br>L 0201 ge_arr <BR>";
                    foreach ($muf_arr as $file => $val) {
                        # echo "L 0228 $file <br>";
                        $f_arr = explode("_",$file);
                        $len = count($f_arr);
                        # echo "L 0206 $len <br>";
                        if ($len  == 3) {
                            $eigner = $f_arr[2];
                            $table_x =  $file;
                            $pict_path_x = "AOrd_Verz/".$eigner."/MuF";
                            # echo "L 0211 $pict_path $eigner  <br>";
                            $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                            $Return = Picts($pict_path_x,$DC_Arr);
                            
                        } else {
                            continue;
                        }
                    }
                    foreach ($mug_arr as $file => $val) {
                        # echo "L 0245 $file <br>";
                        $f_arr = explode("_",$file);
                        $len = count($f_arr);
                        # echo "L 0206 $len <br>";
                        if ($len  == 3) {
                            $eigner = $f_arr[2];
                            $table_x =  $file;
                            $pict_path_x = "AOrd_Verz/".$eigner."/MuG";
                            # echo "L 0211 $pict_path $eigner  <br>";
                            $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                            $Return = Picts($pict_path_x,$DC_Arr);
                            
                        } else {
                            continue;
                        }
                    }
                    
                }
                
            }
            
        }
        
        if ($projekt == "INV") {
            
            $ar_arr = $fo_arr = $fz_arr = $fm_arr = $ge_arr = $in_arr = $zt_arr = array();
            VF_tableExist();
            # print_r($ge_arr);echo "<br>L 0251 ge_arr <BR>";
            foreach ($in_arr as $file => $val) {
                # echo "L 0253 $file <br>";
                $f_arr = explode("_",$file);
                $len = count($f_arr);
                # echo "L 0256 $len <br>";
                if ($len  == 4) {
                    $eigner = $f_arr[3];
                    $table_x =  $file;
                    $pict_path_x = "AOrd_Verz/".$eigner."/INV";
                    # echo "L 261 $pict_path $eigner  <br>";
                    $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                    $Return = Picts($pict_path_x,$DC_Arr);
                    $Return = Stats($stat_path,$DS_Arr);
                } else {
                    continue;
                }
            }
            
        }
        
        if ($projekt == "ARCH") {
            
            $ar_arr = $fo_arr = $fz_arr = $fm_arr = $ge_arr = $in_arr = $zt_arr = array();
            VF_tableExist();
            # print_r($ar_arr);echo "<br>L 0276 ar_arr <BR>";
            foreach ($ar_arr as $file => $val) {
                # echo "L 0278 $file <br>";
                $f_arr = explode("_",$file);
                $len = count($f_arr);
                # echo "L 0281 $len <br>";
                if ($len  == 3) {
                    $eigner = $f_arr[2];
                    $table_x =  $file;
                    $pict_path_x = "AOrd_Verz/".$eigner;
                    # echo "L 286 $pict_path $eigner  <br>";
                    $Return = Tab_Pict($table_x,$TC_Arr,$TS_Arr);
                    $Return = Picts($pict_path_x,$DC_Arr);
                    $Return = Stats($stat_path,$DS_Arr);
                } else {
                    continue;
                }
            }
            
        }
        
        
    }
    

}



  
  BA_HTML_header('Bilder- Abgleich','','Form','90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 

  switch ($phase)
  { case 0 :  
      echo "<label for='projekt'>Auswahl des Projektes:</label>"; 
      echo "<select name='projekt' id='projekt'>"; 
      foreach(AUSW as $value => $Text) {
         echo " <option value='$value'>$Text</option>";
      }
      echo "</select>"; 
      
      echo "<br/><button type='submit' name='phase' value='1' class=green>Projektdaten lesen</button></p>";
      break;
      
  case 1:
      /*
      print_r($_POST);
      echo "<BR>L 157 POST<br>";
   
      print_r($TC_Arr);
      echo "<br><b>TC_Arr</b><br>";
      
      print_r($DC_Arr);
      echo "<br><b>DC_Arr</b><br>";
      
      print_r($TS_Arr);
      echo "<br><b>TS_Arr</b><br>";
      
      print_r($DS_Arr);
      echo "<br><b>DS_Arr</b><br>";
      
      print_r($arr_fehl);
      echo "<br><b>arr_fehl</B><br>";
      print_r($arr_zuviel);
      echo "<br><b>arr_zuviel</b><br>";
  */
      $len_tc = count($TC_Arr);
      $len_dc = count($DC_Arr);
      $len_ds = count($DS_Arr);
      
      echo "<h2>Zahlen:</h2>";
      echo "<b>Anzahl der Tabellen- Einträge $len_tc</b><br>";
      echo "<b>Anzahl der Bilder oder Dokus &nbsp;$len_dc</b><br>";
      echo "<b>Anzahl der Statuten- Dokumente $len_ds</b><br>";
 
      // vergleiche TC_Arr mit DC_Arr -> nicht vorhanden in arr_fehl Anzeige in Tabelle
      echo "<h3>Tabelle gegen Foto-Dir, fehlende fotos</h3>";
      echo "<div w3-content class='w3-table-all'>";
      echo "<table><tbody>";
      echo "<tr><th colspan='3'>Projekt $projekt, Fotos fehlend</th></tr>"; 
      
      foreach($TC_Arr as $key => $value) {
          if (array_key_exists($key,$DC_Arr)) {
               continue;
          } else {
              $arr_fehl[$key] = $value;
              echo "<tr><td>$key</td><td>$value</td></tr>";
          }
      }
      
      
      echo "</tbody></table>";
      echo "</div>";
      // vergleiche DC_Arr mit TC_Arr -> nict referenziert in arr_zuviel  Anzeige in Tabelle
      echo "<h3>Foto-Dir gegen Tabelle, Fotos zuviel</h3>";
      echo "<table><tbody>";
      echo "<tr><th colspan='3'>Projekt $projekt, Fotos zuviel</th></tr>";
      foreach($DC_Arr as $keyF=>$value) {
          if ($keyF == "." || $keyF ==".." || $keyF == "desktop.ini") {continue;}
          $d_loesch = "";
          if (array_key_exists($keyF,$TC_Arr)) {
               continue;
          } else {
              $arr_zuviel[$keyF] = $value ;
              $del = $value."/".$keyF;
              echo "L 0396 $del <br>";
              $ret_del = unlink($del);
              if ($ret_del) {
                  $d_loesch = $key." wurde geklöscht.";
              } 
              echo "<tr><td>$keyF</td><td>$d_loesch</td><td>$value</td></tr>";
              $val_arr = explode("|",$value);
          }
          
      }
      #var_dump($arr_zuviel);
      $len = count($arr_zuviel);
      if ($len == 0) {
          echo "<tr><td colspan='2'>Alle Fotos sind Zugeordnet</td></tr>";
      }
      echo "</tbody></table>";
      echo "</div>";
      
      if (isset($Ds_Arr) && count($Ds_Arr) !=0 ) {
          // vergleiche Ts_Arr mit Ds_Arr -> nicht vorhanden in arr_fehl  Anzeige in Tabelle
          echo "<h3>Tabelle gegen Statuten-Dir, fehlende Statuten</h3>";
          echo "<table><tbody>";
          echo "<tr><th colspan='3'>Projekt $projekt</th></tr>";
          
          
          echo "</tbody></table>";
          echo "</div>";
          
          // vergleiche Ds_Arr mit Ts_Arr -> nict referenziert in arr_zuviel Anzeige in Tabelle
          echo "<h3>Statuten-Dir gegen Tabelle, Statuten zu viel</h3>";
          echo "<table><tbody>";
          echo "<tr><th colspan='3'>Projekt $projekt</th></tr>";
          
          
          echo "</tbody></table>";
          echo "</div>";
          
         
      }

      

      break;
  }
 BA_HTML_trailer();

function Tab_Pict($Tabelle,&$TC_Arr)

{
global $db,$projekt;
 # echo "L 0434: \$Tabelle $Tabelle,  \$projekt $projekt <br/>";

$sql = "SELECT  * FROM $Tabelle ";

$ret = SQL_QUERY($db,$sql);
# echo "\$sql $sql<br>";print_r($ret);echo "<br/>";

WHILE($row = mysqli_fetch_object($ret)) {
    
    
    if ($projekt == "AERM") {
        
        if ($Tabelle == "aw_ort_wappen") {
            if ($row->fo_gde_wappen  != "") {
                $TC_Arr[$row->fo_gde_wappen] = ("$Tabelle|$row->fo_id|$row->fo_fw_id");
            }
        }
        if ($Tabelle == "aw_ff_wappen") {
            if ($row->fo_ff_wappen  != "") {
                $TC_Arr[$row->fo_ff_wappen] = ("$Tabelle|$row->fo_id|$row->fo_fw_id");
            }
        }
        if ($Tabelle == "aw_ff_abz") {

            if ($row->fo_aermel_abzeich  != "") {
                $TC_Arr[$row->fo_ff_abzeich] = ("$Tabelle|$row->fo_id|$row->fo_fw_id");
            }
        }      
    }
    
    if ($projekt == "AUSZ") {
        # echo "L 0465 Tabelle $Tabelle <br>";
        if ($Tabelle == "az_auszeich") {
            # print_r($row);echo "<br>L 0467 row <br>";
            if ($row->az_bild_v  != "") {
                $TC_Arr["$row->az_bild_v"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_bild_r  != "") {
                $TC_Arr["$row->az_bild_r"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_bild_m  != "") {
                $TC_Arr["$row->az_bild_m"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_bild_m_r  != "") {
                $TC_Arr["$row->az_bild_m_r"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_bild_klsp  != "") {
                $TC_Arr["$row->az_bild_klsp"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_urkund_1  != "") {
                $TS_arr["$row->az_urkund_1"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
            if ($row->az_urkund_2  != "") {
                $TS_arr["$row->az_urkund_2"] = ("$Tabelle|$row->az_id|$row->az_fw_id");
            }
        }
    }
    
    if ($projekt == "FZG") {
        $tabkurz = substr($Tabelle,0,8);
        
        switch  ($tabkurz) {
            case 'ma_fz_be': 
                if ($row->fz_bild_1  != "") {
                    $TC_Arr["$row->fz_bild_1"] = ("$Tabelle|$row->fz_id|$row->fz_eignr");
                }
                if ($row->fz_bild_2  != "") {
                    $TC_Arr["$row->fz_bild_2"] = ("$Tabelle|$row->fz_id|$row->fz_eignr");
                }
                break;
            case 'fz_fixei':
                if ($row->fz_ger_foto_1  != "") {
                    $TC_Arr[$row->fz_ger_foto_1] = ("$Tabelle|$row->fz_id");
                }
                if ($row->fz_ger_foto_2  != "") {
                    $TC_Arr[$row->fz_ger_foto_2] = ("$Tabelle|$row->fz_id");
                }
                if ($row->fz_ger_foto_3  != "") {
                    $TC_Arr[$row->fz_ger_foto_3] = ("$Tabelle|$row->fz_id");
                }
                if ($row->fz_ger_foto_4  != "") {
                    $TC_Arr[$row->fz_ger_foto_4] = ("$Tabelle|$row->fz_id");
                }
               
                break;
            case 'fz_lader':
                if ($row->lr_foto_1  != "") {
                    $TC_Arr[$row->lr_foto_1] = ("$Tabelle|$row->lr_id|$row->lr_fzg");
                }
                if ($row->lr_foto_2  != "") {
                    $TC_Arr[$row->lr_foto_2] = ("$Tabelle|$row->lr_id|$row->lr_fzg");
                }
                if ($row->lr_foto_3  != "") {
                    $TC_Arr[$row->lr_foto_3] = ("$Tabelle|$row->lr_id|$row->lr_fzg");
                }
                if ($row->lr_foto_4  != "") {
                    $TC_Arr[$row->lr_foto_4] = ("$Tabelle|$row->lr_id|$row->lr_fzg");
                }
                
                break;
            case 'ma_gerae':
                if ($row->ge_foto_1  != "") {
                    $TC_Arr[$row->ge_foto_1] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_foto_2  != "") {
                    $TC_Arr[$row->ge_foto_2] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_foto_3  != "") {
                    $TC_Arr[$row->ge_foto_3] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_foto_4  != "") {
                    $TC_Arr[$row->ge_foto_4] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g1_foto  != "") {
                    $TC_Arr[$row->ge_g1_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g2_foto != "") {
                    $TC_Arr[$row->ge_g2_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g3_foto  != "") {
                    $TC_Arr[$row->ge_g3_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g4_foto != "") {
                    $TC_Arr[$row->ge_g4_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g5_foto  != "") {
                    $TC_Arr[$row->ge_g5_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g6_foto  != "") {
                    $TC_Arr[$row->ge_g6_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g7_foto != "") {
                    $TC_Arr[$row->ge_g7_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g8_foto  != "") {
                    $TC_Arr[$row->ge_g8_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g9_foto  != "") {
                    $TC_Arr[$row->ge_g9_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                if ($row->ge_g10_foto  != "") {
                    $TC_Arr[$row->ge_g10_foto] = ("$Tabelle|$row->ge_id|$row->ge_eignr");
                }
                break;
                
            case 'mu_fahrz':
                if ($row->fm_foto_1  != "") {
                    $TC_Arr[$row->fm_foto_1] = ("$Tabelle|$row->fm_id|$row->fm_eignr");
                }
                if ($row->fm_foto_2  != "") {
                    $TC_Arr[$row->fm_foto_2] = ("$Tabelle|$row->fm_id|$row->fm_eignr");
                }
                if ($row->fm_foto_3  != "") {
                    $TC_Arr[$row->fm_foto_3] = ("$Tabelle|$row->fm_id|$row->fm_eignr");
                }
                if ($row->fm_foto_4  != "") {
                    $TC_Arr[$row->fm_foto_4] = ("$Tabelle|$row->fm_id|$row->fm_eignr");
                }
                
                break;
            case "mu_gerae":
                if ($row->mg_foto_1  != "") {
                    $TC_Arr[$row->mg_foto_1] = ("$Tabelle|$row->mg_id|$row->mg_eignr");
                }
                if ($row->mg_foto_2  != "") {
                    $TC_Arr[$row->mg_foto_2] = ("$Tabelle|$row->mg_id|$row->mg_eignr");
                }
                if ($row->mg_foto_3  != "") {
                    $TC_Arr[$row->gm_foto_3] = ("$Tabelle|$row->mg_id|$row->mg_eignr");
                }
                if ($row->mg_foto_4  != "") {
                    $TC_Arr[$row->mg_foto_4] = ("$Tabelle|$row->mg_id|$row->mg_eignr");
                }
                
                break;
            
        }
        
        
    }
    if ($projekt == "INV") {
        if ($row->in_foto_1  != "") {
            $TC_Arr[$row->in_foto_1] = ("$Tabelle|$row->in_id|$row->in_invjahr");
        }
        if ($row->in_foto_2  != "") {
            $TC_Arr[$row->in_foto_2] = ("$Tabelle|$row->in_id|$row->in_invjahr");
        }
    }
    
    if ($projekt == "ARCH") {
        if ($row->ad_doc_1  != "") {
            $TC_Arr[$row->ad_doc_1] = ("$Tabelle|$row->ad_id|$row->ad_eignr");
        }
        if ($row->ad_doc_2  != "") {
            $TC_Arr[$row->ad_doc_2] = ("$Tabelle|$row->ad_id|$row->ad_eignr");
        }
        if ($row->ad_doc_3  != "") {
            $TC_Arr[$row->ad_doc_3] = ("$Tabelle|$row->ad_id|$row->ad_eignr");
        }
        if ($row->ad_doc_4  != "") {
            $TC_Arr[$row->ad_doc_4] = ("$Tabelle|$row->ad_id|$row->ad_eignr");
        }
    }
}

# print_r($TC_Arr);echo "<br/>TC_Arr<br>";


 
} # ende function Tab_Pict

function Picts($PictPath,&$DC_Arr)
    
{
    global $db,$projekt;
    # echo "L 06051 $PictPath <br>";
    if (is_dir($PictPath)) {
        # echo "L 0606 $PictPath <br>";
    #if ($PictPath != ""  ) {
        if ($projekt == 'ARCH') {
            # echo "L 0609 vor xr_recursive <br";
            $file_info = array();
                      
            XR_recursive_scan($PictPath,$file_info);
            # var_dump($file_info);
            # echo "<br>L 0613 nach xr_recursive <br>";
            $len_dirs = count($file_info);
            
            
            $s_dirs = array();
            foreach($file_info  as $arr_nr => $files) {
                #if ($files == "." || $files == "..") {continue;}
                if ($arr_nr == 0) {   ## sub-Dirs
                    foreach ($files as $ind => $dir) {
                        $s_dirs[$ind] = $dir;
                    }
                   continue;
                }
                if (!is_array($files)) {continue;}
                foreach($files  as $key=>$value) {
                    #        echo "key $key value $value <br/>";
                    if ($key == "." || $key =="..") {continue;}
                    $DC_Arr[$value] = $PictPath;
                }
            }
            # var_dump($s_dirs);
            # var_dump($DC_Arr);
            

        } else {
            $D_dir = scandir($PictPath);
            #    print_r($D_dir);
            foreach($D_dir  as $key=>$value) {
                #        echo "key $key value $value <br/>";
                if ($key == "." || $key =="..") {continue;}
                $DC_Arr[$value] = $PictPath;
            }
        }
        
    }
    
} # ende funct Picts

function Stats($StatPath ,&$DS_Arr)
    
{
    global $db,$projekt;
    
    if ($StatPath != "") {
        $D_dir = scandir($StatPath);
        foreach($D_dir  as $key=>$value) {
            if ($key == "." || $key =="..") {continue;}
            $DS_Arr[$value] = $StatPath;
        }
    }
    
} # ende func Stats

/**
 * Recurviver Scan des Projektes
 *
 * @function recursive_scan
 * @description Recursively scans a folder and its child folders
 * @param $path ::
 *            Path of the folder/file
 */
function XR_recursive_scan($path,&$file_info)
{
    # global $file_info;
    $path = rtrim($path, '/');
    if (! is_dir($path)) {
        $file_info[] = $path;
    } else {
        $files = scandir($path);
        array_push($file_info,$files);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                
                XR_recursive_scan($path . '/' . $file,$file_info);
            }
        }
    }
}
?>