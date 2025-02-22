<?php
/**
 * Funktionsbibliothek für diverse Zusatzfunktionen für Feuerwehrhistoriker HP.
 *
 * @author  Josef Rohowsky josef@kexi.at, start 01.01.2025
 *
 * Enthält Funktionen for AJAX betriebene Funktioen
 * 
 *  - BA_Auto_Compl       - Autokomplete Auswahl Eigner
 *  - BA_Multi_Dropdown   - Multiple Dropdown Auswahl mit bis zu 6 Ebenen, Verwendet für Sammlungsauswahl, AOrd- Auswahl      
 *  - BA_Multi_Sel_Input  - Daten zum abspeichern in DB Tabelle vobereiten
 *  - BA_Upload_single    - Mass- Upload für Bild, Video und Dokumente mit Dateitypen- Check
 *
 * Enthält und Unterprogramme für die Auwahl von Namen und Begriffen
 *
 *  - VF_Add_Findbuch   - Suchbegriff Schlagwort hinzufügen
 *  - VF_Add_Namen      - Suchbegriff Namen hinzufügen
 *  - VF_chk_Valid      - Prüfung ob gültige Aufruf, setzten der Zugriffs- Parameter für $module neu 20240120
 *  - VF_Count_add      - Add Record for startng an inernal Sub-Process (as Archive, Suche, Fzg
 *  - VF_Bdld_Displ     - Zeigt den Namen des Bundeslandes für die angegebene Abkürzung
 *  - VF_Displ_Arl      - Anzeige Archivordnung 3+4. Ebene Locale Sachgeb + Subsachgeb
 *  - VF_Displ_Aro      - Anzeige Archivordnug, 1+2. Ebene Generelles Sachgebiet und Sub-Sachgebiet
 *  - VF_Displ_Eig      - Daten zur Anzeige der Eigentümer-Daten, Speichern in SESSION[Eigner [
 *  - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen
 *  - VF_Displ_Urheb    - Urheber Daten in $_Sess[$module]['URHEBER'] einlesen
 *  - VF_Login          - Login durchführen
 *  - VF_Log_Pw_chg     - Passwortänderung beim Login Daten erfassen
 *  - VF_Log_PW_Upd     - Passworänderung schreiben
 *  - VF_Mail_Set       - gibt die E-Mail Adresse für die Recs aus fh_m_mail zurück neu 20240120
 *  - VF_Multi_Foto     - Anzeige mehrfach - s mit den texten paarweise n Zeile
 *  - VF_Sel_Bdld       - Auswahl Bundesland
 *  - VF_Sel_Det        - Detailbeschreibungs Selektion
 *  - VF_Sel_Sammlg     - Sammlungs- Selektion mit select list
 *  - VF_Sel_Staat      - Auswahl Staat
 *  - VF_Sel_Urheber    - Auswahl des Urhebers, speicherung Urhebernummer
 *  - VF_set_module_p   - setzen der Module- Parameter    neu 20240120
 *  - VF_set_PictPfad   - setze den Bilderpfad für Uploads und Anzeigen
 *  - VF_Show_Eig       - Auslesen ud zurückgeben der Eigner-Daten im Format wir Autocomplete
 *  - VF_tableExist     - test ob eine Tabelle existiert
 *  - VF_upd            - Berechtigungs- Feststellung je *_List Script entsprechend Eigentümer
 *  - VF_Upload_Pic     - Hochladen der Datei mit Umbenennung auf Foto- Video- Format Urh-Datum-Datei.Name
 *  - VF_trans_2_separate - Umlaute eines Strings von UTF-8 oder CP1252 auf gtrennte Schreibweise -> Ü ->UE
 
 *  - VF_Sel_Eigner     - Eigentümer- Auswahl für Berechtigungen (wieder aktiviert)
 */

if ($debug) {
    echo "L 042: BA_AJAX_Funcs.inc.php ist geladen. <br/>";
}


function BA_Auto_Compl($Proc='Eigent',$titel='Eigentümer')
{
    global $debug, $module, $flow_list, $tit_eig_leih;
    
    flow_add($module,"BA_Html_Funcs.lib.php Funct: BA_Auto_Compl" );
    ?>
    
    <input type='hidden' id="proc" value=<?php echo $Proc; ?>">
    
    <div class='w3-container' style='background-color: PeachPuff '> <!--   -->
         <div class='w3-container w3-light-blue'>
             <b>Auswahl des <?php echo $titel; ?>s</b>
         </div>
         <div class='w3-container w3-third'>   
             <label for='Level_e'>Namen des <?php echo $titel ?>s eingeben &nbsp;  </label> 
         </div>
         <div class='w3-container w3-twothird'>   
             <input class='w3-input' type='text' id='autocomplete' name='auto' placeholder='<?php echo $titel ?>- Name eingeben...' /> <!-- Zur Auswahl auf den gewünschten Namen klicken"  -->
             <div id='suggestions'></div>
         </div>
     </div>    
             
    <script>
       document.observe("dom:loaded", function() {
        var inputField = $("autocomplete");
        var suggestionsBox = $("suggestions");
        var proc = $("proc");
        
        inputField.observe("keyup", function() {
            var query = this.value.trim();
    
            if(query.length > 0) {
                new Ajax.Request('common/API/BA_Auto_Compl.API.php', { 
                    method: 'post',
                    parameters: { query : query, proc: proc },
                    onSuccess: function(response) {
                        suggestionsBox.update(response.responseText);
                        suggestionsBox.setStyle({ display: 'block' });
                    }
                });
            } else {
                suggestionsBox.update('');
                suggestionsBox.setStyle({ display: 'none' });
            }
        });
            
            suggestionsBox.observe("click", function(event) {
                var target = event.findElement('.autocomplete-suggestion');
                if(target) {
                    inputField.value = target.innerHTML;
                    suggestionsBox.update('');
                    suggestionsBox.setStyle({ display: 'none' });
                }
            });
    });
    </script>
        
    <?php 

} # end Funct VF_Eig_Ausw

/**
 * Multi Dropdown select für verschiedene Auswahlen
 * 
 * @param string  $in_val
 * @param string $titel
 */
function BA_Multi_Dropdown ($in_val,$titel='Mehrfach- Abfrage') {
    global $debug,$path2ROOT, $MS_Init,$MS_Lvl,$MS_Opt, $MS_Txt, $module  ;

    flow_add($module,"BA_AJAX_Funcs.inc.php Funct: VF_Multi_Dropdown" );

    echo "<div class='w3-container nav' style='background-color: PeachPuff '>";
    
    echo "<div class='w3-row'>";
    
    echo "    <div class='w3-container w3-light-blue'> ";
    echo "         <b>$titel</b>";
    echo "    </div>";
    echo "    <div class='w3-container w3-third'>";
    echo "         <label for='Level1'>".$MS_Txt[0]." &nbsp; </label>";
    echo "    </div>";
    echo "    <div class='w3-container w3-twothird'> ";
    echo "        <select class='w3-input'  id='level1' name='level1' onchange='updateOptions(1, this.value, $MS_Opt )'>";
    echo "             <option value='Nix'>Bitte wählen</option>";
    $checkd = "";
    foreach ($MS_Init  as $samlg => $name):
    if ($samlg == $in_val) {
        $checkd = 'checked';
    }
    echo "<option value='$samlg' $checkd>$name </option>";
    endforeach;
    
    echo "         </select>";
    echo "     </div>";
    
    echo "</div>";
    
    if ($MS_Lvl >= 2) {
        echo "<div class='w3-row'>";
        
        echo "    <div class='w3-container w3-third'>";
        echo "         <label for='Level2'>".$MS_Txt[1]." &nbsp;  </label>";
        echo "    </div>";
        echo "    <div class='w3-container w3-twothird'> ";
        echo "        <select class='w3-input' id='level2' name='level2' onchange='updateOptions(2, this.value, $MS_Opt )'>";
        echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
        echo "     </div>";
        
        echo "</div>";
        
        if ($MS_Lvl >= 3) {
            
            echo "<div class='w3-row'>";
            
            echo "    <div class='w3-container w3-third'>";
            echo "         <label for='Level3'>".$MS_Txt[2]." &nbsp;  </label>";
            echo "    </div>";
            echo "    <div class='w3-container w3-twothird'> ";
            echo "        <select class='w3-input' id='level3' name='level3' onchange='updateOptions(3, this.value, $MS_Opt )'>";
            echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
            echo "     </div>";
            
            echo "</div>";
            
            if ($MS_Lvl >=4) {
                
                echo "<div class='w3-row'>";
                
                echo "    <div class='w3-container w3-third'>";
                echo "         <label for='Level4'>".$MS_Txt[3]." &nbsp;  </label>";
                echo "    </div>";
                echo "    <div class='w3-container w3-twothird'> ";
                echo "        <select class='w3-input' id='level4' name='level4' onchange='updateOptions(4, this.value, $MS_Opt )'>";
                echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
                echo "     </div>";
                
                echo "</div>";
                
                
                if ($MS_Lvl >= 5) {
                    
                    echo "<div class='w3-row'>";
                    
                    echo "    <div class='w3-container w3-third'>";
                    echo "         <label for='Level5'>".$MS_Txt[4]." &nbsp;  </label>";
                    echo "    </div>";
                    echo "    <div class='w3-container w3-twothird'> ";
                    echo "        <select class='w3-input' id='level5' name='level5' onchange='updateOptions(5, this.value, $MS_Opt )'>";
                    echo "             <option value='Nix'>Bitte wählen</option> ";
                    echo "        </select>";
                    echo "     </div>";
                    
                    echo "</div>";
                    
                    
                    
                    if ($MS_Lvl == 6) {
                        
                        echo "<div class='w3-row'>";
                        echo "    <div class='w3-container w3-third'>";
                        echo "         <label for='Level6'>".$MS_Txt[5]." &nbsp; </label>";
                        echo "    </div>";
                        echo "    <div class='w3-container w3-twothird'> ";
                        echo "         <select class='w3-input' id='level6' name='level6' onchange='updateOptions(6, this.value, $MS_Opt )'>";
                        echo "             <option value='Nix'>Bitte wählen</option>
                                      </select>";
                        echo "     </div>";
                        
                        echo "</div>";
                        
                        
                    }
                }
            }
        }
    }
    echo "</div>";
    
    #echo "</td></tr>";
    ?>

<script>
function updateOptions(level, parentValue, optVal) {
    console.log(level);
    console.log(parentValue);
    console.log(optVal);

    new Ajax.Request('common/MultiSel_Opt.API.php', {
        method: 'get',
        parameters: { level: level, parent: parentValue, opval: optVal },
        onSuccess: function(transport) {
            const options = transport.responseText.split('|');
            console.log(options);
            const select = $('level' + (parseInt(level) + 1));
            select.innerHTML = ''; // Leere die vorherigen Optionen

            options.forEach(function(option) {
                const parts = option.split(':');
                if (parts.length === 2) { // Überprüfe, ob die Option gültig ist
                    const newOption = new Element('option', { value: parts[0] }).update(parts[1]);
                    select.insert(newOption);
                } else {
                    console.warn('Ungültige Option:', option);
                }
            });
        },
        onFailure: function() {
            alert('Fehler beim Laden der Optionen.');
        }
    });
} 
</script>
<?php 
} # ende function MultiSel_Edit

/**
 *  Auswertung der Eingabe vom Multi_Select_Dropdown
 *  
 *  Daten werden direkt von $_POST ausgewertet
 *  
 * @return string Sammlungs. Kennung
 */
function BA_Multi_Sel_Input () {
    global $debug, $path2ROOT, $module  ;
    
    flow_add($module,"BA_AJAX_Funcs.inc.php Funct: BA_Multi_Sel_Input" );
    
    $response = "";
    if (isset($_POST['level1']) && ($_POST['level1'] != "" ) ) {
        $response = trim($_POST['level1']);
    }
    
    if (isset($_POST['level2']) && ($_POST['level2'] != "" ) ) {
        if ($_POST['level2'] != "Nix") {
            $response = trim($_POST['level2']);
        }
    }
    
    if (isset($_POST['level3']) && ($_POST['level3'] != "" ) ) {
        if ($_POST['level3'] != "Nix") {
            $response = trim($_POST['level3']);
        }
    }
    
    if (isset($_POST['level4']) && ($_POST['level4'] != "") ) {
        if ($_POST['level4'] != "Nix") {
            $response = trim($_POST['level4']);
        }
    }
    
    if (isset($_POST['level5']) && ($_POST['level5'] != "") ) {
        if ($_POST['level5'] != "Nix") {
            $response = trim($_POST['level5']);
        }
    }
    
    if (isset($_POST['level6']) && ($_POST['level6'] != "") ) {
        if ($_POST['level6'] != "Nix") {
            $response = trim($_POST['level6']);
        }
    }

    return $response;
    
} # Ende Function VF_Multi_Sel_input


/**
 * File- Upload vielen Files 
 *
 *
 */
function BA_Upload_Single () {
    global $debug, $path2ROOT, $module  ;
    
    flow_add($module,"BA_AJAX_Funcs.inc.php Funct: BA_Upload_sinlge" );
    ?>
     <input type="file" id="fileInput" name="files[]" multiple /><br>
      
        <div id='message'>da kann eine Nachricht kommen</div><br>
        <button type="button" id="uploadButton">Hochladen</button><br>
        <div id="status" style="margin-top: 10px; font-weight: bold;"></div>
         <ul id="fileList"></ul> <!-- Liste für die Anzeige der Dateien -->
        
<script>       
document.observe("dom:loaded", function() {
    $('uploadButton').observe('click', function(event) {
        event.preventDefault(); // Verhindert das Standardverhalten des Formulars
        console.log('Upload gestartet');

        var fileInput = $('fileInput');
        var files = fileInput.files;
        var allowedExtensions = ["gif", "ico", "jpeg", "jpg", "png", "tiff", "mp4", "pdf"];
        var validFiles = [];
        var messageDiv = $('message');
        messageDiv.update(''); // Vorherige Nachrichten löschen

        for (var i = 0; i < files.length; i++) {
            var fileName = files[i].name;
            var fileSize = files[i].size;
            console.log(fileSize);
            if (fileSize <= 40000000) { // 40 MB
                //console.log('OK');
                var extension = fileName.split('.').pop().toLowerCase();
                if (allowedExtensions.includes(extension)) {
                    validFiles.push(files[i]); // Gültige Datei zur Liste hinzufügen
                } else {
                    messageDiv.insert('Die Datei ' + fileName + ' hat eine unerlaubte Dateiendung und wurde ausgeschlossen.<br>');
                }  
            } else {
                messageDiv.insert('Die Datei ' + fileName + ' überschreitet die maximale Größe von 40 MB und wurde ausgeschlossen.<br>');
            }
        }
        console.log(validFiles.length);
        // Hier kannst du mit validFiles weiterarbeiten, z.B. die Dateien hochladen
        if (validFiles.length > 0) {

            var pUid = $F('pUid');
    
            // Funktion zum Hochladen einer Datei
            function uploadFile(file) {
                var formData = new FormData();
                formData.append('file', validFiles[i]); // 'file' ist der Name, unter dem die Datei gesendet wird
                formData.append('pUid', pUid);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'common/API/BA_Upload.API.php', true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var jsonResponse = JSON.parse(xhr.responseText);
                        messageDiv.insert('Datei erfolgreich hochgeladen: ' + file.name + '<br>');
                        // Hier kannst du die Rückantwort verarbeiten
                    } else {
                        messageDiv.insert('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                    }
                };

                xhr.onerror = function() {
                    messageDiv.insert('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                };

                xhr.send(formData); // FormData senden
            }

            // Alle Dateien nacheinander hochladen
            for (var i = 0; i < files.length; i++) {
                uploadFile(files[i]);
            }
        } else {
            messageDiv.insert('Es wurden keine Dateien zum Hochladen gefunden.');
        }
    });
});

    </script>
    
    <?php
    
} // end function  BA_Upload_single


/**
 * File- Upload vielen Files
 *
 *
 */
function BA_Upload_parms () {
    global $debug, $path2ROOT, $module  ;
    
    flow_add($module,"BA_AJAX_Funcs.inc.php Funct: BA_Upload_parms" );
    ?>
    <script>
        let uploadDir = '';
        let selectedFiles = [];

        function submitParams() {
            uploadDir = document.getElementById('uploadDir').value;
            document.getElementById('fileInput').disabled = false; // Aktivieren des Datei-Inputs
            alert('Zielverzeichnis gesetzt: ' + uploadDir);
        }

        function showImages() {
            const files = document.getElementById('fileInput').files;
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            selectedFiles = []; // Leere die Liste der ausgewählten Dateien

            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (function(file) {
                    return function(e) {
                        const div = document.createElement('div');
                        div.className = 'preview-image';
                        div.innerHTML = `<img src="${e.target.result}" alt="${file.name}"><p>${file.name}</p>
                                         <input type="checkbox" name="selectedFiles" value="${file.name}"> Auswählen
                                         <input type="checkbox" name="rotateLeft[]" value="${file.name}"> Links drehen
                                         <input type="checkbox" name="rotateRight[]" value="${file.name}"> Rechts drehen`;
                        preview.appendChild(div);
                        selectedFiles.push(file); // Füge die Datei zur Liste der ausgewählten Dateien hinzu
                    };
                })(files[i]);
                reader.readAsDataURL(files[i]);
            }
        }

        function uploadImages() {
            const formData = new FormData();
            const rotateLeft = Array.from(document.querySelectorAll('input[name="rotateLeft[]"]:checked')).map(el => el.value);
            const rotateRight = Array.from(document.querySelectorAll('input[name="rotateRight[]"]:checked')).map(el => el.value);
            const selectedFileNames = Array.from(document.querySelectorAll('input[name="selectedFiles"]:checked')).map(el => el.value);

            selectedFileNames.forEach(fileName => {
                const file = selectedFiles.find(f => f.name === fileName);
                if (file) {
                    formData.append('files[]', file); // Füge jede ausgewählte Datei zum FormData hinzu
                }
            });

            formData.append('uploadDir', uploadDir);
            rotateLeft.forEach(file => formData.append('rotateLeft[]', file));
            rotateRight.forEach(file => formData.append('rotateRight[]', file));

            new Ajax.Request('upload.php', {
                method: 'post',
                postBody: formData,
                contentType: false,
                processData: false,
                onSuccess: function(response) {
                    alert(response.responseText);
                },
                onFailure: function(response) {
                    alert('Fehler beim Hochladen: ' + response.responseText);
                }
            });
        }

<?php

} // end function  BA_Upload_single
