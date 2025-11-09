<?php

/**
 * Mail an andere Mitglieder senden, Formular
 *
 * @author  osef Rohowsky - neu 2023
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_M_Mail_kontakt_ph0.inc.php"; 

if ($debug) {echo "<pre class=debug>pls_kontakt_ph0.inc.php gestarted</pre>";}
#----------------------------------------------------------------------------------
# # Mail an anders Mitglied senden - Phase 0
#
# change Avtivity:
#   2021-08-03 B.R.Gaicki  - überarbeitet: links auf V3 required auf .inc
#   2022-02-05 B.R.Gaicki - V5 (PixRipTab & login )
# ------------------------------------------------------------------------------------------------------------------
  if ( strpos($REMOTE_USER, '@')!== false ) {
      echo "<input type='hidden' name='EMail' value='$EMail'>";
      echo "\n<div class='white'>";
      echo "<h4>$sender_row[Anrede]</h4>";
      echo "<span class='error'>$Err_EMail</span>"; 
      echo "</div>";
  }
  else {
      EMail_Eingabe('mi_email',$EMail,$Err_EMail); # zeige die E-Mail_Adressen eingabe Box
  }
  $empf_anr = $_SESSION['empf']['mi_anrede']." ".$_SESSION['empf']['mi_titel']." ".$_SESSION['empf']['mi_vname']." ".$_SESSION['empf']['mi_name']." ".$_SESSION['empf']['mi_n_titel'];
?>
<!-- ======================================================================================================= -->
<fieldset><h4>Nachricht an <span style='font-size:130%; color:black'><?php echo $empf_anr;?></span></h4>
<?php
   # if (!empty($empf_row['pr_Bild'])) {echo "<img src='../k27/images/$empf_row[pr_Bild].jpg ' alt='Bild'>";}
?>
<!-- ======================================================================================================= -->
 <p>Ihre Nachricht bitte FORMLOS in dieses Feld eintragen (maximal 500 Zeichen):
 <span class="error"><?php echo $Err_text;?></span>
  <textarea name='text' maxlength='500' rows=3 style='width:100%' ><?php echo "$text";?></textarea></p>
  <span class='info'>Hinweis: <b>Damit der Empfänger antworten kann - 
         werden Ihr Name und Ihre E-Mail-Adresse mitgesendet!</b></span>
</fieldset>

<!-- =============================================================================================== -->
<?php Button_senden("Nachricht absenden",0);?> <!-- Trailer mit Buttons -->
<!-- =============================================================================================== -->
<?php if ($debug) {echo "<pre class=debug>pls_contakt_ph0.inc.php beendet</pre>";} ?>