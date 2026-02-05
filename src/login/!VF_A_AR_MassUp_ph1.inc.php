<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Abfrage Aufnahmedatum
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */

if (!isset($_SESSION[$module]['archord'] )) {
    $_SESSION[$module]['archord'] = array();
}
/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix" ) {
        
    } else {
        $response = $_SESSION[$module]['archord'] = $response;
    }
}

// Vorbereitung zum Kopieren/löschen der Dateien

$arr_ak = array('00','00','00','00','00','00');

$aord_arr = explode(" ",$_SESSION[$module]['archord']);

$ao_a = explode (".",$aord_arr[0]);
if (isset($ao_a[0]) ) {
    $arr_ak[0] = $ao_a[0];
}
if (isset($ao_a[1])) {
    $arr_ak[1] = $ao_a[1];
}

if (isset($aord_arr[1]) && $aord_arr[1] != '00') {
    $arr_ak[2] = $aord_arr[1];
}
if (isset($aord_arr[2]) && $aord_arr[2] != '00') {
    $arr_ak[3] = $aord_arr[2];
}
if (isset($aord_arr[3]) && $aord_arr[3] != '00') {
    $arr_ak[4] = $aord_arr[3];
}
if (isset($aord_arr[4]) && $aord_arr[4] != '00') {
    $arr_ak[5] = $aord_arr[4];
}

$AO_dat = "";
if (isset($arr_ak)) {
    
    if ($arr_ak[2] == "00") {
        $AO_dat = VF_Displ_Aro($arr_ak[0], $arr_ak[1]);
    } elseif ($arr_ak[3] == "00") {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3], $arr_ak[4], $arr_ak[5]);
    } else {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3], $arr_ak[4], $arr_ak[5]);
        # = "$row->al_lcsg|$row->al_lcssg|$row->al_lcssg_s0|$row->al_lcssg_s1|$row->al_sammlung|$row->al_bezeich";
    }
}

$aOrd = $arr_ak[0]."/".$arr_ak[1];

$basis_pfad = $pfad = $beschreibg = "";
$eignr = $_SESSION['Eigner']['eig_eigner'];

echo "<input type='hidden' id='eiId' name='eigner' value='$eignr'>";
echo "<input type='hidden' id='aOrd' name='aOrd' value='$aOrd'>";
echo "<input type='hidden' id='aoText' name='aoText' value='$AO_dat'>";
echo "<input type='hidden' id='reSize' name='reSize' value='1754'>";

echo "<div class='white'>";

Edit_Tabellen_Header("hochladen von Daten für <br> ".$_SESSION['Eigner']['eig_eigner']);

Edit_Separator_Zeile("Die Daten werden im Verzeichnis /login/VF_Upload/$eignr/$aOrd abgelegt <br>$AO_dat");

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='fileInput'>Wählen Sie Dateien aus:</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird  ' >"; // Beginn Inhalt- Spalte
echo "<input type='file' id='fileInput' name='files[]' multiple  onchange='showImages()'>";   //accept='audio/*,video/*,image/*,text/*'
echo "</div>";
echo "</div>";

echo "<div id='preview'></div>";

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<input type='button' id='uploadButton' value='Dateien hochladen'>"; // Button zum Hochladen
echo "</div>";
echo "<button type='submit' name='phase' value='3' class=green>Daten abspeichern</button></p>";

?>
<!--   Input für die Bildername zut Tabellenerstelung -->
<div id='inPutFields'></div>

<!-- Fortschritt -->
<div id='progressContainer' style='margin-top:20px; display:none;'>
<progress id='uploadProgress' value='0' max='100' style='width:100%;'></progress>
<div id='progressText'></div>
</div>

<!-- Nachrichten -->
<div id='message'></div>
<?php 

Edit_Tabellen_Trailer();

?>

<script>

// Globale Variable
var selectedFiles = [];

 
 // Vorschau der Bilder
function showImages() {
  selectedFiles = Array.from($('#fileInput')[0].files);
  $('#preview').empty();
  selectedFiles.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = function(e) {
      $('#preview').append(
        `<div>
          <img src="${e.target.result}" style="max-width:360px; max-height:360px;"><br>
          <label><input type="checkbox" class="uploadCheckbox" data-index="${index}" checked> Hochladen</label><br>

        </div>`
          /*
          <label><input type="checkbox" class="wmarkCheckbox" data-index="${index}" checked> Wasserzeichen (Urheber- Namen) einfügen</label><br>
          <label>Drehwinkel (nach links):</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="90"> 90°</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="180"> 180°</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="-90"> 270°</label>
          
          */
      );
    };
    reader.readAsDataURL(file);
  });
}
 
// Button zum Hochladen
$('#uploadButton').on('click', function() {
  if (selectedFiles.length === 0) { alert('Bitte Bilder auswählen'); return; }
  // Fortschrittsanzeige anzeigen
  $('#progressContainer').show();
  $('#uploadProgress').val(0);
  $('#message').text('');
  
  // nur Dateien, die zum Hochladen markiert sind
  const filesToUpload = [];
  for (let i=0; i<selectedFiles.length; i++) {
    if ($('.uploadCheckbox[data-index="'+i+'"]:checked').length > 0) {
      filesToUpload.push({file: selectedFiles[i], index: i});
    }
  }
  uploadNext(0, filesToUpload);
});

// Funktion zum Hochladen aller Bilder sequenziell
function uploadNext(i, files) {
  if (i >= files.length) {
    $('#message').append('Hochladen abgeschlossen.<br>');
    $('#progressContainer').hide();
    // Automatisches Submit auslösen
    /*
    $('<input>').attr({
         type: 'hidden',
         name: 'phase',
         value: '3'
    }).appendTo('#myform');
    $('#myform').submit();
    return;
    */
  }
  const item = files[i];
  const file = item.file;
  const index = item.index;
  
  // Parameter sammeln
  /*
  let watermark = $('#urhEinfgJa').is(':checked') ? 1 : 0 ;
  console.log('watermark ',watermark);
  
  var checkedValue = $('.messageCheckbox:checked').val();
  $('.wmarkCheckbox[data-index="'+index+'"]:checked').each(function() {
    watermark = $(this).val();
  });
  console.log('watermark ',watermark);
  // Mehrere Drehwinkel können, falls gewünscht, verarbeitet werden:
  let rotationVal = 0;
  $('.rotateCheckbox[data-index="'+index+'"]:checked').each(function() {
    rotationVal = parseInt($(this).data('angle'));
  });
  */
  // Daten für API

  const eiId = $('#eiId').val();
  const aOrd = $('#aOrd').val();
  const reSize = $('#reSize').val();
  console.log('ArchOrd ',aOrd);
                
  var formData = new FormData();
  formData.append('file', file); // 'file' ist der Name, unter dem die Datei gesendet wird
  formData.append('urhNr', eiId);
  formData.append('aord', aOrd);
  formData.append('reSize',reSize);
  formData.append('urhEinfgJa','N');
  
  console.log('form_Date ',formData);

  $.ajax({
    url: 'common/API/VF_Upload.API.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    xhr: function() {
      var xhr = new XMLHttpRequest();
      xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
          var perc = Math.round((e.loaded / e.total) * 100);
          $('#uploadProgress').val(perc);
          $('#progressText').text('Hochladen: ' + perc + '%');
        }
      }, false);
      return xhr;
    },
    success: function(resp) {
                console.log('Upload response typeof:', typeof resp, resp);
                // If resp is a string, try to parse as JSON
                if (typeof resp === 'string') {
                    try {
                        response = JSON.parse(resp);
                        console.log('Parsed JSON response:', response);
                    } catch(e) {
                        console.log('JSON parse error:', e, response);
                    }
                }
     if (response && response.files) {
        console.log('Upload response files:', response.files);
        response.files.forEach(function(fileObj, idx) {
            // Hier der Index i (von der höheren Funktion), um den Namen zu generieren
            var inputName = 'name_' + i; // i ist die Variable aus deiner uploadNext-Funktion
            
            // Visualisierung: Log für Debug, dann Input erstellen
            console.log('Erstelle Input für Index:', i, 'Datei:', fileObj.filename);
            
            var hiddenInput = $('<input>', {
                type: 'text',
                name: inputName,
                value: fileObj.filename // oder eine andere Eigenschaft, die du speichern willst
            });
            
            $('#inPutFields').append(hiddenInput);
        });
    } else {
        console.log('Keine Dateien in Response oder Response unvollständig:', response);
    }
      $('#message').append('Bild '+file.name+' hochgeladen.<br>');
      var perc = Math.round(((i+1)/files.length)*100);
      $('#uploadProgress').val(perc);
      $('#progressText').text('Fortschritt: ' + perc + '%');
      uploadNext(i+1, files);
    },
    error: function() {
      $('#message').append('Fehler beim Hochladen von '+file.name+'<br>');
      uploadNext(i+1, files);
    }
  });
}

</script>

<?php 
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_MassUp_ph1.inc beendet</pre>";
}
?>