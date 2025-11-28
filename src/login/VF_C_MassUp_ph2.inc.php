<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Daten in die Tabellen enfügn
 *
 * @author  Josef Rohowsky - neu 2025
 *
 *
 */
/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_C_Massup_ph2.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_ph2.inc.php ist gestarted</pre>";
}

if ($_SESSION[$module]['Up_Typ'] == 'Arch') {
    require "VF_C_MassUp_AR_ph2.inc.php";
} else {
    require "VF_C_MassUp_FO_ph2.inc.php";
}

if (!isset($urhname)) {$urhname = "";}
if (!isset($aufn_dat)) {$aufn_dat = '';}
if (!isset($beschreibg)) {$beschreibg = '';}

?>

<!-- Datei-Input -->
<div class='w3-row'>
<div class='w3-third'>
<label for='fileInput'>Bilder auswählen:</label>
</div>

<!-- Vorschau + Auswahl -->
<div id='preview'></div>
<!--  
<h3>Hochladen für Urheber: <span id="urhname"><?php echo htmlspecialchars($urhname); ?></span></h3>
<p>Aufnahmedatum: <?php echo htmlspecialchars($aufn_dat); ?></p>
<p>Beschreibung: <?php echo htmlspecialchars($beschreibg); ?></p>
 -->

<input type='hidden' name='md_beschreibg' value="<?php echo  htmlspecialchars($beschreibg); ?>" >
<input type="hidden" id="urhName" name='md_Urheber' value="<?php echo htmlspecialchars($urhname); ?>">
<input type="hidden" id="urhNr" name'md_eigner' value="<?php echo htmlspecialchars($eignr); ?>">
<input type="hidden" id="aufnDat" name='md_aufn_datum' value="<?php echo htmlspecialchars($aufn_dat); ?>">    
 
  <div class='w3-twothird'>
    <input type='file' id='fileInput' multiple accept='image/*' onchange='showImages()'>
  </div>
</div>
 
<!-- Vorschau 
<div id='preview'></div>
-->
<!-- Hochladen -->
<div class='w3-row' style='margin-top:10px;'>
  <input type="button" id="uploadButton" value="Bilder hochladen" />
</div>

<!-- Fortschritt -->
<div id='progressContainer' style='margin-top:20px; display:none;'>
  <progress id='uploadProgress' value='0' max='100' style='width:100%;'></progress>
  <div id='progressText'></div>
</div>

<!--   Input für die Bildername zut Tabellenerstelung -->
<div id='inPutFields'></div>
<!-- Nachrichten -->
<div id='message'></div>

</body>
</html>

