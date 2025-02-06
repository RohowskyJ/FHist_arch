<?php
// mitgl_upd.php Eigentuemer-Einverst-erkl update Verwaltung

$path2ROOT          = "../";

$debug = False; $debug = True; // Debug output Ein/Aus Schalter
require('common/VF_Funcs.inc'); // Diverse Unterprogramme VF_HTML_header('Mitglieder_Tabelle mit Sortierklick','','Admin');
require('common/VF_List_Funcs.inc');

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
VF_initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$db = VF_LinkDB('Mem'); // Connect zur Datenbank

if (isset($_GET['mitgl_nr'])) {$mitgl_nr = $_GET['mitgl_nr']; }
if (isset($_GET['mitgl_name'])) {$t_name = $_GET['mitgl_name']; }
if (isset($_GET['mitgl_vname'])) {$t_vname = $_GET['mitgl_vname']; }
if (isset($_GET['mitgl_email'])) {$t_email = $_GET['mitgl_email']; }

  $mitgl_einv_art = "PAP";
  $mitgl_einv_dat = date("Y.m.d H:i:s");
  $mitgl_einverkl = "Y";
 
    $sql_ein = "UPDATE `fh_mitglieder` SET
           `mi_einv_art`='$mitgl_einv_art',`mi_einversterkl`='$mitgl_einverkl',`mi_einv_dat`='$mitgl_einv_dat'
           WHERE `mi_id` = '$mitgl_nr' LIMIT 1";
  
  $result = VF_SQL_QUERY($db,$sql_ein);

  mysqli_close($db);
// log-Record schreiben
  $datum=date("d.m.Y:");
  $zeit=date("H:i:s");
  $ip=$_SERVER['REMOTE_ADDR'];
  $site = $_SERVER['REQUEST_URI'];
  $monate = array(1=>"Januar", 2=>"Feber", 3=>"März", 4=>"April", 5=>"Mai", 6=>"Juni", 7=>"Juli", 8=>"August", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Dezember");
  
  $monat = date("n");
  $jahr = date("y");
  $dateiname="logs/MitglLog/yel_log_$monate[$monat]_$jahr.txt";
  
  /* Get a the current date and time, formated */
  $shortdate = date("Y-m-d H:i:s");
  
  /* Build log string for writing */
  //  $log_rec = "\nReferer: " . $HTTP_REFERER . "\n";
#  $log_rec = "\nIP: " . $_SERVER['REMOTE_ADDR'] . ", Hostname: " . $_SERVER['REMOTE_HOST'] . "\n";
  $log_rec = "With: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
  #        $log_rec .= "requested von REMOTE_USER:>>>>" . $_SERVER['REMOTE_USER'] . "<<<<<\n";
  $log_rec .= "Page: " . $_SERVER['REQUEST_URI'] . "\n";
#  $log_rec .= "Requester-Name: " . $p_uid . "\n";
  $log_rec .= "Requester-Email: " . $t_email . "\n";
#  $log_rec .= "Error Kodes: " . "Noerr = " . $Noerr . " | .. errM = " . $errM . " | ..  emerr = " . $emerr . " | .. err3 = " . $err3 . "| ..err2 = " . $err2 . "| ..err1 = " . $err1 . " | Ende Errkode\n";
  $log_rec .= "MITGLIEDSNUMMER ( Datensatz-Nr.): " . $mitgl_nr . "\n";
 # $log_rec .= "Anrede: " . $t_H_F . "\n";
 # $log_rec .= "Titel: " . $t_titel . "\n";
  $log_rec .= "Name: " . $t_name . $t_vname ."\n";
  $log_rec .= "Email: " . $t_email . "\n";
  
  $log_rec .= "Einverstaendnis Datenschutz: $mitgl_einv_art $mitgl_einverkl  $mitgl_einv_dat \n";
  
  $eintragen="Datum - - [$datum$zeit] $log_rec " ;
  $adminmail=$eintragen . "****** Adminmail Ende ******";
  
  $datei=fopen($dateiname,"a+");
  fputs($datei,"$eintragen\n");
  fclose($datei);
      
      //HFS Mailheader folgt, baut Header der Mail zusammen    **********
      
      $Aheaders = 'From: Admin <service@feuerwehrhistoriker.at>' . "\n";
      $Aheaders .= 'Reply-To: service@feuerwehrhistoriker.at' . "\n";
      $Aheaders .= 'X-Priority: 1' . "\n";
      $Aheaders .= 'X-MSMail-Priority: High' . "\n";
      //$Aheaders .= 'Content-type: text/html';
      $Aheaders .= 'Content-type: text';
      //HFS Mailheader ENDE
      
      
      mail("service@feuerwehrhistoriker.at", "YELLOW-LOG-Adminkopie -Status", $adminmail, $Aheaders);
      #echo "<br><b>Email ging raus</b><br>";

  header("Location: VF_M_List.php?pgm_id=$pgm_id");
?>
