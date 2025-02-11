<?php

/**
 * Fahrzeug Daten im Typenschein, Formular
 * 
 * @author Josef Rohowsky - neu 2018 
 */

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================

if ($neu['fz_sammlg'] == 'BA') {
    $T_list_texte  = array(
        "NeuItem" =>"<a href='VF_FA_BA_TS_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
    );
} elseif ($neu['fz_sammlg'] == 'BH') {
    $T_list_texte  = array(
        "NeuItem" =>"<a href='VF_FA_BH_TS_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
    );
} else {
    $T_list_texte  = array(
        "NeuItem" =>"<a href='VF_FA_BA_TS_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
        );
}
# ===========================================================================================================
#
#                                            Haeder ausgeben
#
# ===========================================================================================================

   $VF_logo = 'NEIN';

  List_Prolog($module,$T_list_texte); #  Paramerter einlesen und die Listen Auswahl anzeigen

  $LinkDB_database  = '';

  $db = LinkDB('VFH');
  
  $tabelle_ft = "fz_fz_type";
  
  $Tabellen_Spalten = Tabellen_Spalten_parms($db,$tabelle_ft);
  
  $tabelle_ft_a = $tabelle_ft."_".$_SESSION['Eigner']['eig_eigner'];
  
  $return = Cr_n_fz_type($tabelle_ft_a);
  if ($return <> True)  {
      echo "error: mysqli_errno($return)";
  }
 
  $select_ft = " WHERE `fz_t_id` = '$fz_id' ";
  $sort_ft   = " ORDER BY `ft_id`";
  $sql_ft = "SELECT * FROM `$tabelle_ft_a` $select_ft  $sort_ft ";
      
  $Tabellen_Spalten =  array('ft_id','fz_t_id','fz_herstell_fg','fz_fgtyp','fz_baujahr');
  $Tabellen_Spalten_style['ft_id'     ] = 
  $Tabellen_Spalten_style['fz_t_id'  ] =
  $Tabellen_Spalten_style['fz_baujahr'  ] ='text-align:center;';
 
  
  $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar'
                 . '<ul style="margin:0 1em 0em 1em;padding:0;">'
                 . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>fz_t_id</q> Klicken.</li>';
 
  $List_Hinweise .= '</ul></li>';
 
  List_Action_Bar($tabelle,"",$T_list_texte,$T_List,$List_Hinweise); # Action Bar ausgeben
   
# ===========================================================================================================
#  Die Daten lesen und Ausgeben
# ===========================================================================================================
 
      $New_Link = "";
      if ( $_SESSION[$module]['all_upd']  ) {
          if ($_SESSION[$module]['sammlung'] == 'MA_F-AH') {
              $New_Link = "<a href='VF_FA_BH_TS_Edit.php?ID=0' >Neu</a>"  ;
          } elseif ($_SESSION[$module]['sammlung'] == 'MA_F') {
              $New_Link = "<a href='VF_FA_BA_TS_Edit.php?ID=0' >Neu</a>"  ;
          }
             
       }
 List_Create($db,$sql_ft,'',$tabelle_ft_a,'',$New_Link); # die liste ausgeben

?>