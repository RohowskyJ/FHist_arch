<?php
if ($debug) {echo "<pre class=debug>VF_M_Anm_Chk_v3.inc.php ist gestarted</pre>";}
#----------------------------------------------------------------------------------
# VF_M_Aanm_chk_v3ph0.inc - Edit der Mitglieds-Daten - Eingabe - Version 2 by B.Richard Gaicki
#
# verwendet in Edit_tn_reg_v2.php & Edit_tntab_v2.php
#
# change Avtivity:
#   2019       B.R.Gaicki  - neu
#   2019-06-23 J. Rohowsky Check ob neu mitglied schon vorhanden
#
#----------------------------------------------------------------------------------
# $debug = True;
if ( $debug ) { echo '<pre class=debug>';echo '<hr>\$neu: ';     print_r($neu); echo '</pre>'; }

$select = "";
if ($neu['mi_name'] != "") {
    $select = "WHERE `mi_name`='".$neu['mi_name']."'";
    if ($neu['mi_vname'] != "") {
        $select .= "AND `mi_vname`='".$neu['mi_vname']."'";
    }
    if ($neu['mi_gebtag'] != "") {
        $select .= "AND `mi_gebtag`='".$neu['mi_gebtag']."'";
    }
    $sql_chk = "SELECT * FROM `fh_mitglieder` $select";
    $return_chk = VF_SQL_QUERY($db,$sql_chk) ;
    var_dump($return_chk);
  
    while ($row = mysqli_fetch_object($return_chk)) {
        if ($row->mi_beitritt == $neu['mi_beitritt']) {
            $mitgl_nr = $row->mi_id;
        }
    }

    $p_uid= 999999999;

    require("../login/VF_M_Edit_ph1.inc");
   # require("../login/dsgvo_anm_log.php");

    $datum=date("d.m.Y:");
    $zeit=date("H:i:s");

    $dsn="../login/log/anmeldlog";

    $log_rec  = "**** PFLICHTFELDER **** \nAnrede: " . $neu['mi_anrede'] . "\n";
    $log_rec .= "Familienname: " . $neu['mi_name']."\n";
    $log_rec .= "Vorname: " . $neu['mi_vname'] . "\n";
    $log_rec .= "E-Mail: " . $neu['mi_email'] . "\n";
    $log_rec .= "Adresse: " . $neu['mi_anschr'] . "\n";
    $log_rec .= "PLZ: " . $neu['mi_plz'] . "\n";
    $log_rec .= "Ort: " . $neu['mi_ort'] . "\n";

    //******** Optionale Felder ********
    $log_rec .= "**** Optionale Felder ****\nTitel: " . $neu['mi_titel'] . "\n";
    $log_rec .= "**** Optionale Felder ****\n nachfolg. Titel: " . $neu['mi_n_titel'] . "\n";

    $log_rec .= "Tel Nummmer: " . $neu['mi_tel_handy'] . "\n";
    $log_rec .= "Mobil Nummer: " . $neu['mi_handy'] . "\n";
    $log_rec .= "Fax: " . $neu['mi_fax']."\n";
    $log_rec .= "Geburtsdatum: " . $neu['mi_gebtag'] . "\n";
    $log_rec .= "Oganisationstyp: ". $neu['mi_org_typ']."\n";
    $log_rec .= "Organisation: ".$neu['mi_org_name']."\n";
    # $log_rec .= "Referatsfunktion: ".$neu['mi_ref_int']."\n";
    # $log_rec .= "Referatsmitarbeit: ".$neu['mi_ref_ma']."\n";
    # $log_rec .= "Referatsinormation: ".$neu['mi_ref_int']."\n";
    $log_rec .= "Einverstaendniserklaerung: ".$neu['mi_einversterkl']." $datum $zeit ".$neu['mi_einv_art']."\n";

    $text  =" $log_rec ***** \"LOG ENDE\" *****\n";
    $text .="Orig.TCP = ".$_SERVER['REMOTE_ADDR']."\n";
   
    $fname = VF_writelog($dsn,$text);
    $tr = array("\n"=>"<br>");
    $text = strtr($text,$tr);
  
   $adr_list = VF_Mail_Set('Mitgl');

   if ($module == "0_EM") {
       VF_sendEMail($neu['mi_email'].", $adr_list , josef@kexi.at","VFHNÖ Mitglieds- Neuanmeldung ",$text); # service@feuerwehrhistoriker.at, helmut-riegler@aon.at, f.blueml@gmx.at"
   }
   
   echo " Log- Dateiname \$fname $fname <br>";

   $text= "Zur Info: Soeben " . $datum . " / " . $zeit . " wurde eine Anmeldung online  dem System übergeben.\n";
   $text .=  "Im Formular wurden u.A. Name / Vorname / Emailadresse erfasst: " . $neu['mi_name'] . " / " . $neu['mi_vname'] . " / " . $neu['mi_email'] . ".\n";
   IF (!empty ($ftext)) { $text .=  "\nAchtung fehlende Pflichtangaben: $ftext\n\n"; }
   $text .=  "Weitere Infos sind im Anmeldelog ersichtlich.\n";
   $text .=  "http://www.feuerwehrhistoriker.at/login/log/DSVGO_log/";
   $text .=  "\nBitte beobachten ob die Anmeldung korrekt beendet wurde\n";
   $text .=  "und zu einem Teilnehmer Aufnameantrag geführt hat!\n";
   $text .=  "Zum Ansehen des Logs folgenden Link anklicken:\n";
   $text .=  "http://www.feuerwehrhistoriker.at/login/logs/dir.php\n";
   $text .=  "Anmeldelog  Mail Ende\n";
   
   VF_sendEmail("$adr_list, josef@kexi.at",         // Empänger(Liste)
       "Neuanmeldung ".$neu['mi_name']." " ,    // Subject Text der EMail
       $text,           // Inhalt der Email in HTML format
       "service@feuerwehrhistoriker.at");  // optionale 'Reply-To' E-Mail-Adresse

    require("VF_M_MEB_v3.inc");
}
if ($debug) {echo "<pre class=debug>VF_M_Anm_Chk_v3.php beendet</pre>";}
?>
