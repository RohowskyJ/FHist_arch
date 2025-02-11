<?php
/**
 * Bericht Fotos , neu in die Liste aufnehmen
 * 
 * 
 * 
 */
session_start(); # die SESSION am leben halten 
const Module_Name = 'OEF';
$module    = Module_Name;
$tabelle = 'vb_ber_detail_4';
const Prefix  = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";
$debug = False;    // Debug output Ein/Aus Schalter
#----------------------------------------------------------------------------------
# VF_M_Edit_v3.php - Edit der Mitglieds-daten TNTab - Version 2 by B.Richard Gaicki,bu_frei_id='$p_uid'
#
# change Avtivity:
#   2018       B.R.Gaicki  - neu
#   2019-10-04 Josef Rohowsky  - Anpassng an FFHIST, Eingabe neuer Datensätze, zurück  zur Liste 
# ------------------------------------------------------------------------------------------------------------------
# Die Verarbeitung efolgt in Phasen
#   Phase=0: Anzeige der aus der Tabelle befüllten Eingabefelder
#   Phase=1: Speichern det Daten in fh_mitglieder
#----------------------------------------------------------------------------------

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

initial_debug(); 

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$VF_LinkDB_database  = '';
$db = LinkDB('Mem');

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================  
if ( isset($_POST['phase'] ) ) { $phase    = $_POST['phase'];} else { $phase    = 0; }
if ( isset( $_GET['ID']  ) ) { $vd_flnr =  $_GET['ID']; } else {$vd_flnr = "NeuItem";}
if ( isset( $_GET['us']  ) ) { $us =  $_GET['us']; } else {$us = "K";}
if ( isset( $_GET['vb_flnr']  ) ) { $vb_flnr =  $_GET['vb_flnr']; }

if ($phase == 99 ) {
   header('Location: VF_C_FG_Edit_v3.php?fw_id='.$_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False;  // Feldname der Tabelle wird nicht angezeigt !!
# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
# $fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

  Tabellen_Spalten_parms($db,$tabelle);
  
  $Err_Msg = $Err_Urheb ="";
  $Err_Nr = 0;
  # -------------------------------------------------------------------------------------------------------
  #
  # -------------------------------------------------------------------------------------------------------
  if ( $phase == 0 )
  {
      if ($vd_flnr == 0 ) {
          $l_unter = $l_suff = 0;
          $sql = "SELECT * FROM $tabelle WHERE vb_flnr='$vb_flnr' ";
          $return = SQL_QUERY($db,$sql);
          WHile ($row = mysqli_fetch_object($return)) {
              # print_r($row);echo "<br>l 068 row <br>";
              if ($l_unter < $row->vb_unter) {
                  $l_unter = $row->vb_unter;
              }
              if($l_suff < $row->vb_suffix) {
                  $l_suff = $row->vb_suffix;
              }
          }
          if ($us=="U") {
              $l_unter++;
              $l_suff = 0;
          }
          if ($us == "K") {
              $l_suff++;
          }
          
          $neu['vd_flnr'] = 0;
          $neu['vb_flnr'] = $vb_flnr;
          $neu['vb_unter'] = $l_unter;
          $neu['vb_suffix'] = $l_suff;
          $neu['vb_titel'] = $neu['vb_beschreibung'] = $neu['vb_foto'] = $neu['vb_foto_Urheber'] = $neu['vb_uid'] = $neu['vb_aenddat'] =  "";
      } else {
          $sql = "SELECT * FROM $tabelle WHERE vd_flnr= '$vd_flnr' ";

              $result = SQL_QUERY($db,$sql) ;

              $num_rows = mysqli_num_rows($result);
              
              $neu = mysqli_fetch_array($result);
              
              $eig = $neu['vb_foto_Urheber'];
              $f_nr = $neu['vb_foto'];
              $sql_fo = "SELECT * FROM fo_todaten_$eig WHERE fo_id = '$f_nr' ";
              
              $return_fo = SQL_QUERY($db, $sql_fo);
              $row_fo = mysqli_fetch_array($return_fo);
              
              $neu['fo_dsn'] = $row_fo['fo_dsn'];

              if ( $debug ) { echo '<pre class=debug>';echo '<hr>\$neu: ';     print_r($neu); echo '</pre>'; }
      }

  }
 
  if ($phase == 1) {
      
      foreach ($_POST as $name => $value)
      { $neu[$name] = $value; }

      if ( $debug ) { echo '<pre class=debug>';echo 'L 111: <hr>\$neu: ';     print_r($neu); echo "<br> files ";print_r($_FILES);echo '</pre>'; }

      if ($neu['vb_foto1'] != "" || $neu['vb_foto'] != "") {
          if ($neu['vb_foto_Urheber'] == "0")  {
              $Err_Urheb = "Kein Urheber angegeben - ungültig. Urheber muss in der Öffentlichkeitsarbeit Fotos/Videos definiert werden";
              $Err_Nr++;
          }
      }
      
      if ($Err_Nr > 0 ) {
          $phase = 0;
      }
  }
  
  BA_HTML_header('Detailbeschreibungen','','Form','90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

 echo " <form id='myform' name='myform' method='post' action='".$_SERVER["PHP_SELF"]."' enctype='multipart/form-data'>";

  switch ($phase)
  { case 0 : require 'VF_BE_DE_Edit_ph0.inc.php' ;
             break;
    case 1 : require 'VF_BE_DE_Edit_ph1.inc.php' ;
             break;
  }

 echo " </form>";
BA_HTML_trailer();?>