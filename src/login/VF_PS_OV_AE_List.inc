<?php

/**
 * Liste der CTIF oder Vereins- Auszeichnungen
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

if (!isset($_SESSION[$module]['sub_mod'])) {$_SESSION[$module]['sub_mod'] ="AW";} else {$_SESSION[$module]['sub_mod'] ="AW";}

$proj = $_SESSION[$module]['proj'];
if ($_SESSION[$proj]['fw_bd_abk'] == "CTIF")  {
    $table_az = "az_ausz_ctif";
    $select_az = " WHERE `ac_ab_id` = '$ab_id' ";
    $sort_az   = " ORDER BY ac_fw_id,`ac_ab_id` ASC";
    $sql_az = "SELECT * FROM `$table_az` $select_az  $sort_az ";
    
    $T_list_texte  = array(
         "NeuItem" =>"<a href='VF_PS_OV_AZ_CT_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
        ,"AuszList"=>"<a href='VF_PS_OV_AD_Z_List.php?fw_id=".$_SESSION[$module]['fw_id']."' >Liste der Auszeichnungen für Druck anzeigen</a>"
        
    );
} elseif ($_SESSION[$proj]['fw_bd_abk'] == "VEREINE")  {
    $table_az = "az_ausz_ve";
    $select_az = " WHERE `av_ab_id` = '$ab_id' ";
    $sort_az   = " ORDER BY av_fw_id,`av_ab_id` ASC";
    $sql_az = "SELECT * FROM `$table_az` $select_az  $sort_az ";
    
    $T_list_texte  = array(
        "NeuItem" =>"<a href='VF_PS_OV_AZ_VE_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
        ,"AuszList"=>"<a href='VF_PS_OV_AD_Z_List.php?fw_id=".$_SESSION[$module]['fw_id']."' >Liste der Auszeichnungen für Druck anzeigen</a>"
    );
} else {
    $table_az = "az_adetail";
    $select_az = " WHERE `ad_ab_id` = '$ab_id' ";
    $sort_az   = " ORDER BY ad_fw_id,`ad_ab_id`,ad_id  ASC";
    $sql_az = "SELECT * FROM `$table_az` $select_az $sort_az ";
    
    $T_list_texte  = array(
        "NeuItem" =>"<a href='VF_PS_OV_AE_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
        ,"AuszList"=>"<a href='VF_PS_OV_AD_Z_List.php?fw_id=".$_SESSION[$module]['fw_id']."' >Liste der Auszeichnungen für Druck anzeigen</a>"
    );
}

# ===========================================================================================================
#                                            Haeder ausgeben
# ===========================================================================================================

   $logo = 'NEIN';

  List_Prolog($module,$T_list_texte); #  Paramerter einlesen und die Listen Auswahl anzeigen

  $Tabellen_Spalten = Tabellen_Spalten_parms($db,$table_az);
  if ($_SESSION[$proj]['fw_bd_abk'] == "CTIF")  {
      $Tabellen_Spalten = array(
          'ac_id', 'ac_beschr','ac_wettbsp_v','ac_gr_med_go_v','ac_kl_med_go_v','ac_so_med_go_v', 'ac_fabz_v','ac_teiln_v','ac_aend_uid','ac_aenddat'
      );
  } elseif ($_SESSION[$proj]['fw_bd_abk'] == "VEREINE")  {
          $Tabellen_Spalten = array(
              'av_id', 'av_beschr','av_bild_v','av_bild_r','av_aend_uid','av_aenddat'
          );
  } else {
      $Tabellen_Spalten = array(
          'ad_id','ad_name','ad_detail','ad_bez','ad_abmesg','ad_stiftg_date','ad_statut','ad_erklaer','ad_aend_uid','ad_aenddat'
      );
  }
 

  $Tabellen_Spalten_style['ac_id'     ] =
  $Tabellen_Spalten_style['ac_ad_id'  ] =
  
  $Tabellen_Spalten_style['ad_id'     ] = 

  $Tabellen_Spalten_style['ad_ab_id'  ] =
  $Tabellen_Spalten_style['ad_stufe'  ] ='text-align:center;';
 
  
  $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar'
                 . '<ul style="margin:0 1em 0em 1em;padding:0;">'
                 . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fo_id</q> Klicken.</li>'
                 . '<li>Script: VF_PS_OV_AE_List</li>';
  
  $List_Hinweise .= '</ul></li>';
 
  List_Action_Bar($table_az,"",$T_list_texte,$T_List,$List_Hinweise); # Action Bar ausgeben
 
# ===========================================================================================================
#  Die Daten lesen und Ausgeben
# ===========================================================================================================

 
      $New_Link = "";

 List_Create($db,$sql_az,'',$table_az,'',$New_Link); # die liste ausgeben

?>