<?php
$debug_log_file = 'VF_Upload_debug.log';
file_put_contents($debug_log_file, "\n==== API CALL ====\n".date('Y-m-d H:i:s')."\nMETHOD: ".@$_SERVER['REQUEST_METHOD']."\n", FILE_APPEND);
// VF_Upload.API.php
// Universelle Upload-API für Bilder und Dokumente (AJAX, mehrere Dateien, Metadaten)
// Benötigt: PHP 7+, Schreibrechte im Zielverzeichnis

include "..//BA_Funcs.lib.php";
include "..//VF_Foto_Funcs.lib.php";

header('Content-Type: application/json');
ini_set('display_errors', '1');
ini_set('log_errors', 1);
ini_set('error_log', 'VF_Upload_API_php-error.log');

$debug_log = true;
$debug_log_file = 'VF_Upload_debug.log'; // Log-Datei für Debug-Ausgaben

// end default vars
// Konfiguration
$uploadDir = __DIR__ . '../../../AOrd_Verz/VF_Upload/'; // Zielverzeichnis (anpassen!)
$maxFileSize = 20 * 1024 * 1024; // 20 MB
$allowedExtensions = [
    'gif', 'ico', 'jpeg', 'jpg', 'png', 'tiff', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods', 'mp3', 'mp4'
];


// Hilfsfunktion für Bildbearbeitung (Dummy, implementieren nach Bedarf)
function handleImageSpecial($filePath, $urhNr, $urhName, $aufnDat, $rotation = 0, $watermark = 'N', $resize = '800') {
    global $debug_log, $debug_log_file,$uploadDir,$storePath;
    // Hier können Sie Bildbearbeitung, Rotation, Wasserzeichen, Resize etc. einbauen
    // Beispiel: return $filePath; // oder neuen Pfad nach Bearbeitung
    // $filepath == fullpath of uploaed sourcefile
    
    if ($urhNr != '' && $urhName == '' ) {
        $urh_arr = parse_ini_file('../config_u.ini', true, INI_SCANNER_NORMAL);
        
        $cnt = count($urh_arr['Urheber']);
        file_put_contents($debug_log_file, "L 038 urh_arr cnt $cnt  ".$urh_arr['Urheber'][$urhNr]."<br>");
        #if (in_array($urhNr, $urh_arr['Urheber'])) {
            $urhName = $urh_arr['Urheber'][$urhNr];
            /*
        } else {
            $urhName = 'Fehler';
        }
*/
    }

    $storePath .= "06/$aufnDat/";  // Storagepath für Fotos fertig
    if (!is_dir($storePath)) {
        mkdir($storePath, 0755, true);
    } 
      
    if ($debug_log) {
        $eintragen  = " filepath $filePath \n";
        $eintragen .= " rotation $rotation \n";
        $eintragen .= " watermark $watermark \n";
        $eintragen .= " resize $resize \n";
        $eintragen .= " storePath $storePath \n";
        $eintragen .= " urhName $urhName \n";
        file_put_contents($debug_log_file, "L 059 $eintragen" . PHP_EOL, FILE_APPEND);
    }
    $outputFile = $filePath; // Standard: keine Änderung
    $f_arr =pathinfo($filePath);
    
    $basename = basename($filePath);
    $fn_arr = explode('-', $basename);
    $fcnt = count($fn_arr);

        $InputFile = $uploadFile = $basename; 
        $fileName = $fn_arr[$fcnt - 1];
        if ($debug_log) {
            file_put_contents($debug_log_file, "L 048 fileName $fileName" . PHP_EOL, FILE_APPEND);
        }
                        if ($fcnt < 3) { // Umbenennen
                            $w = "N-";
                            $w = "W-";
                            $fileName = $fn_arr[$fcnt -1];
                            $out_name = $urhNr . "-" . $aufnDat . "-".$w . $fileName;
                        } else { // orig-Name bleibt
                            $out_name = $fileName;
                        }
                        $outputFile = $storePath . $out_name; ## '../../' .

                        $ttf_file = "../Fonts/arialbd.ttf";

                        $CR_text = '© '.$urhName;

                        if ($debug_log) {
                            file_put_contents($debug_log_file, "L 065 CR Text $CR_text \n" . PHP_EOL, FILE_APPEND);
                        }
                        # resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
                        $res_file = resizeImage($filePath, $resize, $resize, $outputFile, $CR_text);
                        if ($res_file == "") {

                            if ($debug_log) {
                                file_put_contents($debug_log_file, "L 066 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);
                            }
                        }
    // return $filePath;
    return $outputFile; // Rückgabe des neuen Pfads nach Bearbeitung
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [
        'status' => 'ok',
        'files' => [],
        'errors' => []
    ];

    #$vard =var_export($_POST);
    #error_log($vard);
   
    // Metadaten
    $rotations = isset($_POST['rotation']) ? $_POST['rotation'] : '';
    $urhNr = $_POST['urhNr'] ?? '';
    $urhName = $_POST['urhName'] ??  '';
    $aufnDat = $_POST['aufnDat'] ?? '';
    $watermark = $_POST['watermark'] ?? '';
    $reSize = $_POST['reSize'] ?? '';   
    $aOrd = $_POST['aord'] ??''; 
    $eigner = $_POST['eigner'] ??''; 

    // ... weitere Felder nach Bedarf

  if ($debug_log) {
        $eintragen  = " watermark $watermark \n";
        $eintragen .= " aufn_dat $aufnDat \n";
        $vard = var_export($rotations,true);
        $eintragen .= " $vard \n";
        $eintragen .= " urhNr $urhNr \n";
        $eintragen .= " urhName $urhName \n";
        $eintragen .= " aOrd $aOrd \n";
        $eintragen .= " eigner $eigner \n";
        $eintragen .= " reSize $reSize \n";
        
        file_put_contents($debug_log_file, "L 039 $eintragen" . PHP_EOL, FILE_APPEND);
    }
    // Dateien
    if (!isset($_FILES['file'])) {
        echo json_encode(['status' => 'error', 'message' => 'Keine Datei(en) empfangen.']);
        exit;
    }
    $fileCount = is_array($_FILES['file']['name']) ? count($_FILES['file']['name']) : 1;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
  
    $filecnt = 0;
    for ($i = 0; $i < $fileCount; $i++) {
        // Auswahl prüfen: JS sendet nur selektierte Dateien, daher keine Prüfung mehr nötig
        $name = is_array($_FILES['file']['name']) ? $_FILES['file']['name'][$i] : $_FILES['file']['name'];
        $tmp = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][$i] : $_FILES['file']['tmp_name'];
        $error = is_array($_FILES['file']['error']) ? $_FILES['file']['error'][$i] : $_FILES['file']['error'];
        $size = is_array($_FILES['file']['size']) ? $_FILES['file']['size'][$i] : $_FILES['file']['size'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        $rotation = isset($rotations) ? (int)$rotations : 0;
        $rename = isset($renames[$i]) && $renames[$i] ? $renames[$i] : $name;

        if ($error !== UPLOAD_ERR_OK) {
            $response['errors'][] = "$name: Upload-Fehler ($error)";
            continue;
        }
        
        if (!in_array($ext, $allowedExtensions)) {
            $response['errors'][] = "$name: Ungültige Dateiendung ($ext)";
            continue;
        }
        if ($size > $maxFileSize) {
            $response['errors'][] = "$name: Datei zu groß";
            continue;
        }
        $targetPath = $uploadDir . basename($rename);

        if (move_uploaded_file($tmp, $targetPath)) { 
            // Spezialbehandlung für Bilder
            if (in_array($ext, ['gif', 'ico', 'jpeg', 'jpg', 'png', 'tiff'])) {
                $storePath = "../../AOrd_Verz/$urhNr/09/";  // unvollständiger Pfad zum Zielverzeichnis
                $targetPath = handleImageSpecial($targetPath, $urhNr, $urhName, $aufnDat,$rotation, $watermark, $reSize);        
                 if ($debug_log) {
                    file_put_contents($debug_log_file, "L 0166 fertiges Bild $targetPath  " . PHP_EOL, FILE_APPEND);
                }
            } else {
                if (in_array($ext, ['mp3'])) {
                    $storePath = "../../AOrd_Verz/$urhNr/09/02/"; 
                    
                } elseif (in_array($ext, ['mp4'])) {
                    $storePath = "../../AOrd_Verz/$urhNr/09/10/";       // vollständiger Pfad für Videos
                    
                } elseif (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods'])) {
                    if ($eigner != "" && $aord != "") {
                        $storePath = "../../AOrd_Verz/$eigner/$aOrd/";  // vollständiger Pfad für Archivdaten
                    } else {
                        $response['errors'][] = "$name: Verzeichnis nach Archivordnung nicht angegeben.";
                    }
                }
                if (!is_dir($storePath)) {
                    mkdir($storePath, 0755, true);
                }
                copy($targetPath,$storePath . basename($targetPath));
                unset($targetPath);
                $targetPath = $storePath . basename($targetPath);
            }
            $response['files'][] = [
                'name' => $rename,
                'path' => $targetPath,
                'filename' => basename($targetPath),
                'rotation' => $rotation,
                'size' => $size
            ];
        } else {
            $response['errors'][] = "$name: Fehler beim Speichern.";
        }
    }
    echo json_encode($response);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage.']);
exit;
