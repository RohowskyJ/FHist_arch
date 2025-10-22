<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Abfrage Aufnahmedatum
 *
 * @author  Josef Rohowsky - neu 2023
 *
 *
 */
#var_dump($_SESSION);
#var_dump($_POST);

$basis_pfad = $pfad = $beschreibg = "";
$eignr = $_SESSION['Eigner']['eig_eigner'];

echo "<input type='hidden' id='eiId' value='$eignr'>";
echo "<input type='hidden' id='docPfad' value='VF_Upload/$eignr/' >";

echo "<div class='white'>";

Edit_Tabellen_Header("Hochladen von Daten für <br>".$_SESSION['Eigner']['eig_eigner'] );

Edit_Separator_Zeile("Die Daten werden im Verzeichnis /login/VF_Upload/$eignr abgelegt");

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='fileInput'>Wählen Sie Dateien aus:</label>";
echo "  </div>";  // Ende Feldname
echo "  <div class='w3-twothird  ' >"; // Beginn Inhalt- Spalte
echo "<input type='file' id='fileInput' name='files[]' multiple accept='image/*' onchange='showImages()'>";
echo "</div>";
echo "</div>";

echo "<div id='preview'></div>";

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<input type='button' id='uploadButton' value='Bilder hochladen'>"; // Button zum Hochladen
echo "</div>";
echo "<div id='message'></div>";

Edit_Tabellen_Trailer();

?>

<script>
    function showImages() {
        const files = document.getElementById('fileInput').files;
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = (function(file) {
                return function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-image';
                    div.innerHTML = `<img src="${e.target.result}" alt="${file.name}" width="800"><p>${file.name}</p>
                                     <input type="checkbox" name="selectedFiles[]" checked value="${file.name}"> Auswählen
                                     <input type="checkbox" name="rotateLeft[]" value="${file.name}"> Links drehen
                                     <input type="checkbox" name="rotateRight[]" value="${file.name}"> Rechts drehen`;
                                    ;
                    preview.appendChild(div);
                };
            })(files[i]);
            reader.readAsDataURL(files[i]);
        }
    }
 

    $(document).ready(function() {
        // Debugging: Überprüfen, ob das Element existiert
        var $uploadButton = $('#uploadButton');
        if ($uploadButton.length === 0) {
            console.error("Upload Button nicht gefunden!");
            return;
        }
        $uploadButton.on('click', function(event) {
            event.preventDefault(); // Verhindert das Standardverhalten des Formulars
            console.log('Upload gestartet');

            var $fileInput = $('#fileInput');
            if ($fileInput.length === 0) {
                console.error("File Input nicht gefunden!");
                return;
            }

            var files = $fileInput[0].files;
            var allowedExtensions = ["jpeg", "jpg", "webp", "pdf"];
            var validFiles = [];
            var $messageDiv = $('#message');

            if ($messageDiv.length === 0) {
                console.error("Message Div nicht gefunden!");
                return;
            }

            // $messageDiv.empty(); // Vorherige Nachrichten löschen (optional)

            for (var i = 0; i < files.length; i++) {
                var fileName = files[i].name;
                var fileSize = files[i].size;
                console.log(fileSize);
                if (fileSize <= 40000000) { // 40 MB
                    var extension = fileName.split('.').pop().toLowerCase();
                    if (allowedExtensions.includes(extension)) {
                        validFiles.push(files[i]); // Gültige Datei zur Liste hinzufügen
                    } else {
                        $messageDiv.append('Die Datei ' + fileName + ' hat eine unerlaubte Dateiendung und wurde ausgeschlossen.<br>');
                    }
                } else {
                    $messageDiv.append('Die Datei ' + fileName + ' überschreitet die maximale Größe von 40 MB und wurde ausgeschlossen.<br>');
                }
            }

            // Hier kannst du mit validFiles weiterarbeiten, z.B. die Dateien hochladen
            if (validFiles.length > 0) {
                var eiId = $('#eiId').val();
                var docPfad = $('#docPfad').val();
                console.log(docPfad);
                // Funktion zum Hochladen einer Datei
                function uploadFile(file) {
                    var formData = new FormData();
                    formData.append('file', file); // 'file' ist der Name, unter dem die Datei gesendet wird
                    formData.append('eiId', eiId);
                    formData.append('docPfad', docPfad);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'common/API/VF_Upload_AR.API.php', true);

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var jsonrepl = xhr.responseText.trim();
                            var jsonResponse = JSON.parse(jsonrepl);
                            $messageDiv.append('Datei erfolgreich hochgeladen: ' + file.name + '<br>');
                        } else {
                            $messageDiv.append('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                        }
                    };

                    xhr.onerror = function() {
                        console.error('Ein Fehler ist aufgetreten:', xhr.statusText);
                        $messageDiv.append('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                    };

                    xhr.send(formData); // FormData senden
                }

                // Alle Dateien nacheinander hochladen
                for (var i = 0; i < validFiles.length; i++) {
                    uploadFile(validFiles[i]);
                }
            } else {
                $messageDiv.append('Es wurden keine Dateien zum Hochladen gefunden.');
            }
        });
    });


</script>

<?php 
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_FO_MassUp_ph1.inc beendet</pre>";
}
?>