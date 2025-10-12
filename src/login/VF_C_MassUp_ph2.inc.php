<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Daten in die Tabellen enfügn
 *
 * @author  Josef Rohowsky - neu 2025
 *
 *
 */
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

<script type='text/javascript' src='common/javascript/VF_C_MassUp.js' ></script>
<script>
/*
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
          <label><input type="checkbox" class="wmarkCheckbox" data-index="${index}" checked> Wasserzeichen (Urheber- Namen) einfügen</label><br>
          <label>Drehwinkel (nach links):</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="90"> 90°</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="180"> 180°</label>
          <label><input type="checkbox" class="rotateCheckbox" data-index="${index}" data-angle="-90"> 270°</label>
        </div>`
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
  
  // nur Bilder, die zum Hochladen markiert sind
  const filesToUpload = [];
  for (let i=0; i<selectedFiles.length; i++) {
    if ($('.uploadCheckbox[data-index="'+i+'"]:checked').length > 0) {
      filesToUpload.push({file: selectedFiles[i], index: i});
    }
  }
  console.log('Files to Upload ',filesToUpload);
  uploadNext(0, filesToUpload);
});

// Funktion zum Hochladen aller Bilder sequenziell
function uploadNext(i, files) {
  if (i >= files.length) {
    $('#message').append('Hochladen abgeschlossen.<br>');
    $('#progressContainer').hide();
    // Automatisches Submit auslösen
    $('<input>').attr({
         type: 'hidden',
         name: 'phase',
         value: '3'
    }).appendTo('#myform');
    $('#myform').submit();
    
    return;
  }
  const item = files[i];
  const file = item.file;
  const index = item.index;
  
  // Parameter sammeln
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
  if (watermark === 'on') {watermark = 'J';}
  // Daten für API
  var formData = new FormData();
  formData.append('file', file);
  formData.append('urhNr', $('#urhNr').val());
  formData.append('urhName', $('#urhName').val());
  formData.append('aufnDat', $('#aufnDat').val());
  formData.append('aOrd',$('#aOrd').val());
  formData.append('rotation', rotationVal);
  formData.append('urhEinfgJa', watermark);
  console.log('form_Date ',formData);

  $.ajax({
    url: 'common/API/VF_C_MassUp.API.php',
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
                console.log('Upload resp typeof:', typeof resp, resp);
                // If response is a string, try to parse as JSON
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
                type: 'hidden',
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

    function renderPreviews() {
        previewDiv.html('');
        if (!selectedFiles.length) return;
        selectedFiles.forEach((file, idx) => {
            const fileRow = $('<div style="margin-bottom:10px;"></div>');
            const filenameInput = $('<input type="text" readonly style="width:200px; margin-right:10px;">').val(file.name);
            const rotateLeftBtn = $('<button type="button" style="margin-right:5px;">⟲</button>');
            const rotateRightBtn = $('<button type="button" style="margin-right:5px;">⟳</button>');
            const imgHolder = $('<span></span>');

            rotateLeftBtn.on('click', function() {
                rotations[idx] = (rotations[idx] - 90) % 360;
                renderImage(file, rotations[idx], imgHolder);
                
            });
            rotateRightBtn.on('click', function() {
                rotations[idx] = (rotations[idx] + 90) % 360;
                renderImage(file, rotations[idx], imgHolder);
            });

            fileRow.append(filenameInput, rotateLeftBtn, rotateRightBtn, imgHolder);
            previewDiv.append(fileRow);
            renderImage(file, rotations[idx], imgHolder);
        });
    }

    function renderImage(file, rotation, imgHolder) {
        if (!file) { imgHolder.html(''); return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = $('<img>').attr('src', e.target.result).css({
                'max-width': '200px',
                'transform': 'rotate(' + rotation + 'deg)'
            });
            imgHolder.html(img);
        };
        reader.readAsDataURL(file);
    }
    */
</script>

</body>
</html>

