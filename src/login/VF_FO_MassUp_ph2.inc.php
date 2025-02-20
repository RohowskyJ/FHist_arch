<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Daten in die Tabellen enfügn
 *
 * @author  Josef Rohowsky - neu 2023
 *
 * 
 */

if ($debug) {
    echo "<pre class=debug>VF_FO_MassUp_ph2.inc.php ist gestarted</pre>";
}
#var_dump($_SESSION[$module]);

# var_dump($_POST);

$pict_path = "AOrd_Verz/".$_SESSION[$module]['URHEBER']['ei_id']."/";

if ($_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ'] == 'F' ) {
    $_SESSION['AOrd_[sel']['pict_path'] = $pict_path .= "09/06/";
} elseif ($_SESSON[$module]['URHEBER'][$eignr]['urh_abk']['typ'] == 'V' )  {
    $_SESSION['AOrd_[sel']['pict_path'] = $pict_path .= "09/10/";
}

$aufn_dat = $basepath =  $zus_pfad = $aufn_suff = $begltxt = "";

$_SESSION[$module]['Up_Parm']['urh_abk'] = $_SESSION[$module]['Up_Parm']['basis_pfad'] = $_SESSION[$module]['Up_Parm']['zus_pfad'] = 
   $_SESSION[$module]['Up_Parm']['aufn_dat'] = $_SESSION[$module]['Up_Parm']['aufn_suff'] = "";

if (isset($_POST['urh_abk'])) {
    $urh_abk = $_SESSION[$module]['Up_Parm']['urh_abk'] = $_POST['urh_abk'];
} 
if (isset($_POST['basis_pfad'])) {
    $basepath = $_SESSION[$module]['Up_Parm']['basis_pfad'] = $_POST['basis_pfad'];
}
if (isset($_POST['zus_pfad'])) {
    $zus_pfad = $_SESSION[$module]['Up_Parm']['zus_pfad'] = $_POST['zus_pfad'];
}

if (isset($_POST['aufn_suff'])) {
    $aufn_suff = $_SESSION[$module]['Up_Parm']['aufn_suff'] = $_POST['aufn_suff'];
}
if (isset($_POST['aufn_dat'])) {
    $aufn_dat = $_SESSION[$module]['Up_Parm']['aufn_dat'] = $_POST['aufn_dat'];
}
if (isset($_POST['beschreibg'])) {
    $begltxt = $_SESSION[$module]['Up_Parm']['beschreibg'] = $_POST['beschreibg'];
}
$urheinfueg ="N";
if (isset($_POST['urheinfueg'])) {
    $urheinfueg = $_SESSION[$module]['Up_Parm']['urheinfueg'] = $_POST['urheinfueg'];
}

$f_path = VF_set_PictPfad($_SESSION[$module]['Up_Parm']['aufn_dat'],$_SESSION[$module]['Up_Parm']['basis_pfad'],$_SESSION[$module]['Up_Parm']['zus_pfad'],$_SESSION[$module]['Up_Parm']['aufn_suff']);

$_SESSION[$module]['Up_Parm']['TargetPfad'] = $targ_pfad = $pict_path . $f_path ;

if (! file_exists($targ_pfad)) {
    mkdir($targ_pfad, 0777, true);
}

$from_pf = $_SESSION[$module]['Up_Parm']['pfad'] = "VF_Upload/".$_SESSION['VF_Prim']['p_uid']."/";

// anlegen des Verzeichnis- Records

/**
 * ist Datenrecord vorhanden -> ersetzten des Dateinamens - sonst neuer Datensatz
 */
$eignr = $_SESSION[$module]['URHEBER']['ei_id'];
$tabelle_in = "fo_todaten_$eignr";

Cr_n_fo_daten($tabelle_in);

$sql = "SELECT * FROM $tabelle_in where fo_aufn_datum = '$aufn_dat' AND fo_basepath= '$basepath' and fo_zus_pfad= '$zus_pfad' AND fo_aufn_suff = '$aufn_suff' AND fo_dsn = '' ";
$urhnam = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fotograf'];

$typ = $_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['typ'];

if ($typ == "F") {
    $media = "Foto";
}
if ($typ == "V") {
    $media = "Video";
}
$return = SQL_QUERY($db, $sql);

if (mysqli_num_rows($return) == "0") {
    
    $sql = "INSERT INTO $tabelle_in (
                         fo_eigner,fo_urheber,fo_urh_kurzz,fo_dsn,fo_aufn_datum,fo_aufn_suff,fo_basepath,fo_zus_pfad,fo_begltxt,fo_namen,
                         fo_typ,fo_media,
                         fo_uidaend
                      ) VALUE (
                        '$eignr','$urhnam','$urh_abk','','$aufn_dat','$aufn_suff','$basepath','$zus_pfad','$begltxt','',
                        '$typ','$media',
                        '" . $_SESSION['VF_Prim']['p_uid'] . "'
                      )";
    #echo "L 0135 sql $sql <br>";
    $result = SQL_QUERY($db, $sql);
} else {
    #echo "L 0144 Verzeichnis- Datensatz vorhanden, könnte geändert werden <br>";
    /*
     * $sql = "UPDATE $tabelle SET $updas WHERE `fo_id`='".$_SESSION[$module]['fo_id']."'";
     * if ( $debug ) { echo '<pre class=debug> L 047: \$sql $sql </pre>'; }
     *
     * echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
     * $result = VF_SQL_QUEry($db,$sql);
     */
    
}
# var_dump($_SESSION[$module]['URHEBER']);
// Ausgabe der notwendigen Parameter als hidden input

Edit_Tabellen_Header("Hochladen für Urheber: ".$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fotograf']);
Edit_Separator_Zeile("Aufnahmedatum: $aufn_dat ");

echo "<p>Titel: $begltxt</p>";

echo "<input type='hidden' id='urhName' value='".$_SESSION[$module]['URHEBER'][$eignr]['urh_abk']['fotograf']."' >";
echo "<input type='hidden' id='targPfad' value='$targ_pfad' >";
echo "<input type='hidden' id='urhAbk' value='$urh_abk' >";
echo "<input type='hidden' id='aufnDat' value='$aufn_dat' >";
// echo "<input type='hidden' id='beglTxt' value='$begltxt' >";
echo "<input type='hidden' id='urhEinfg' value='$urheinfueg' >";

// einlesen uo´pload-files,anzeigen der Bilder mit Auswahl

echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
echo "<div class='w3-third   ' >";
echo "<label for='fileInput'>Wählen Sie Bilder aus:</label>";
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
    document.observe("dom:loaded", function() {
        // Debugging: Überprüfen, ob das Element existiert
        var uploadButton = $('uploadButton');
        if (!uploadButton) {
            console.error("Upload Button nicht gefunden!");
            return;
        }

        uploadButton.observe('click', function(event) {
            event.preventDefault(); // Verhindert das Standardverhalten des Formulars
            console.log('Upload gestartet');

            var fileInput = $('fileInput');
            if (!fileInput) {
                console.error("File Input nicht gefunden!");
                return;
            }

            var files = fileInput.files;
            
            var allowedExtensions = ["gif", "ico", "jpeg", "jpg", "png", "tiff", "mp4", "pdf"];
            var validFiles = [];
            var messageDiv = $('message');
            
            if (!messageDiv) {
                console.error("Message Div nicht gefunden!");
                return;
            }
            
            messageDiv.update(''); // Vorherige Nachrichten löschen

            for (var i = 0; i < files.length; i++) {
                var fileName = files[i].name;
                var fileSize = files[i].size;
                // console.log(fileName);
                console.log(fileSize);
                if (fileSize <= 40000000) { // 40 MB
                    var extension = fileName.split('.').pop().toLowerCase();
                    if (allowedExtensions.includes(extension)) {
                        validFiles.push(files[i]); // Gültige Datei zur Liste hinzufügen
                        // console.log(validFiles);
                    } else {
                        messageDiv.insert('Die Datei ' + fileName + ' hat eine unerlaubte Dateiendung und wurde ausgeschlossen.<br>');
                    }  
                } else {
                    messageDiv.insert('Die Datei ' + fileName + ' überschreitet die maximale Größe von 40 MB und wurde ausgeschlossen.<br>');
                }
            }
            // console.log(validFiles.length);

            // Hier kannst du mit validFiles weiterarbeiten, z.B. die Dateien hochladen
            if (validFiles.length > 0) {
                var urhName = $F('urhName');
                var targPfad = $F('targPfad');
                var urhAbk = $F('urhAbk');              
                var urhEinfg = $F('urhEinfg');
                var aufnDat  = $F('aufnDat');     
                // var beglTxt  = $F('beglTxt');
            
                // Funktion zum Hochladen einer Datei
                function uploadFile(file) {
                    var formData = new FormData();
                    formData.append('file', file); // 'file' ist der Name, unter dem die Datei gesendet wird
                    formData.append('urhName', urhName);
                    formData.append('targPfad', targPfad);                  
                    formData.append('urhAbk', urhAbk);                   
                    formData.append('urhEinfg', urhEinfg);
                    formData.append('aufnDat', aufnDat);
                    // formData.append('beglTxt', beglTxt);
                    
                    // Checkboxen abfragen
                    var rotateLeftCheckboxes = document.querySelectorAll('input[name="rotateLeft[]"]:checked');
                    var rotateRightCheckboxes = document.querySelectorAll('input[name="rotateRight[]"]:checked');

                    // Werte der Checkboxen zu FormData hinzufügen
                    rotateLeftCheckboxes.forEach(function(checkbox) {
                       formData.append('rotateLeft[]', checkbox.value);
                    });
                    rotateRightCheckboxes.forEach(function(checkbox) {
                       formData.append('rotateRight[]', checkbox.value);
                    });
                               
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'common/API/BA_Upload_loc.API.php', true);

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var jsonrepl = xhr.responseText.trim();
                            // console.log(jsonrepl);
                            // var jsonResponse = JSON.parse(xhr.responseText);
                            var jsonResponse = JSON.parse(jsonrepl);
                            // console.log(jsonResponse);
                            messageDiv.insert('Datei erfolgreich hochgeladen: ' + file.name + '<br>');
                        } else {
                            messageDiv.insert('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                        }
                    };

                    xhr.onerror = function() {
                        console.error('Ein Fehler ist aufgetreten:', xhr.statusText);
                        messageDiv.insert('Fehler beim Hochladen der Datei: ' + file.name + '<br>');
                    };

                    xhr.send(formData); // FormData senden
                }

                // Alle Dateien nacheinander hochladen
                for (var i = 0; i < validFiles.length; i++) {
                    uploadFile(validFiles[i]);
                }
            } else {
                messageDiv.insert('Es wurden keine Dateien zum Hochladen gefunden.');
            }
        });
    });

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
                                     <input type="checkbox" name="selectedFiles" checked value="${file.name}"> Auswählen
                                     <input type="checkbox" name="rotateLeft[]" value="${file.name}"> Links drehen
                                     <input type="checkbox" name="rotateRight[]" value="${file.name}"> Rechts drehen`;
                                    ;
                    preview.appendChild(div);
                };
            })(files[i]);
            reader.readAsDataURL(files[i]);
        }
    }
    
</script>

<?php 
if ($debug) {
    echo "<pre class=debug>VF_FO_MassUp_ph2.inc.php beendet</pre>";
}
?>