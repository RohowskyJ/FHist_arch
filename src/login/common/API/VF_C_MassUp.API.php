<?php
# header('Content-Type: application/json');
$debug_log_file = 'VF_C_MassUp_debug.log.txt';
file_put_contents($debug_log_file, "\n==== API CALL ====\n".date('Y-m-d H:i:s')."\nMETHOD: ".@$_SERVER['REQUEST_METHOD']."\n", FILE_APPEND);
// VF_C_MassUP.API.php
// Universelle Upload-API für Bilder und Dokumente (AJAX, mehrere Dateien, Metadaten)
// Benötigt: PHP 7+, Schreibrechte im Zielverzeichnis

include "..//BA_Funcs.lib.php";
include "..//VF_Foto_Funcs.lib.php";


ini_set('display_errors', '1');
ini_set('log_errors', 1);
ini_set('error_log', 'VF_C_MassUp_API_php-error.log.txt');

$debug_log = true;

// end default vars
// Konfiguration
$uploadDir = __DIR__ . '../../../AOrd_Verz/VF_Upload/'; // Zielverzeichnis (anpassen!)
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$allowedExtensions = [
    'gif', 'ico', 'jpeg', 'jpg', 'png', 'tiff', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods', 'mp3', 'mp4'
];


// Hilfsfunktion für Bildbearbeitung (Dummy, implementieren nach Bedarf)
function handleImageSpecial($filePath, $urhNr, $urhName, $aufnDat, $rotation = 0, $urhEinfgJa = 'N', $resize = '800', $aOrd = '') {
    global $debug_log, $debug_log_file,$uploadDir,$storePath;
    // Hier können Sie Bildbearbeitung, Rotation, Wasserzeichen, Resize etc. einbauen
    // Beispiel: return $filePath; // oder neuen Pfad nach Bearbeitung
    // $filepath == fullpath of uploaed sourcefile

    
    if ($urhNr != '' && $urhName == '' ) {
        $urh_arr = parse_ini_file('../config_u.ini', true, INI_SCANNER_NORMAL);
        $urhName = $urh_arr['Urheber'][$urhNr];
    }

    $storePath .= "06/$aufnDat/";  // Storagepath für Fotos fertig
    
    if ($aOrd != ''  ) {
        # $resize = '1754';
        $storePath = "../../AOrd_Verz/$urhNr/$aOrd/";
        # $urhEinfgJa = 'N';
    }
    
    if (!is_dir($storePath)) {
        mkdir($storePath, 0755, true);
    } 
      
    if ($debug_log) {
        $eintragen  = " filepath $filePath \n";
        $eintragen .= " rotation $rotation \n";
        $eintragen .= " urhEinfgJa $urhEinfgJa \n";
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
        if ($fcnt < 3 && $urhEinfgJa == 'J') { // Umbenennen
             $w = "N-";
             $w = "W-";
             $fileName = $fn_arr[$fcnt -1];
             $out_name = $urhNr . "-" . $aufnDat . "-".$w . $fileName;
        } else { // orig-Name bleibt
             $out_name = $fileName;
        }
        $outputFile = $storePath . $out_name; ## '../../' .
        
        // CR- Text einfügen
        $CR_text = '';
        if ($urhEinfgJa == 'J') {
            $ttf_file = "../Fonts/arialbd .ttf";
            $CR_text = '© '.$urhName;
        }
       
        if ($debug_log) {
            file_put_contents($debug_log_file, "L 065 CR Text $CR_text \n" . PHP_EOL, FILE_APPEND);
        }
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
    $urhEinfgJa = $_POST['urhEinfgJa'] ?? 'N';
    $reSize = $_POST['reSize'] ?? '800';   
    $aOrd = $_POST['aOrd'] ??''; 
    $eigner = $_POST['eigner'] ??''; 
    
    if ($urhEinfgJa == '1') { $urhEinfgJa ='J';}
    
    if ($aOrd != '') {
        $urhEinfgJa ='N';
    }
    
  $storePath = "";

  if ($debug_log) {
        $eintragen  = " urhEinfgJa $urhEinfgJa \n";
        $eintragen .= " aufn_dat $aufnDat \n";
        $vard = var_export($rotations,true);
        $eintragen .= " $vard \n";
        $eintragen .= " urhNr $urhNr \n";
        $eintragen .= " urhName $urhName \n";
        $eintragen .= " aOrd $aOrd \n";
        # $eintragen .= " eigner $eigner \n";
        $eintragen .= " reSize $reSize \n";
        
        file_put_contents($debug_log_file, "L 134 $eintragen" . PHP_EOL, FILE_APPEND);
    }
    // Dateien
    if (!isset($_FILES['file'])) {
        echo json_encode(['status' => 'error', 'message' => 'Keine Datei(en) empfangen.']);
        exit;
    }
    $fileCount = is_array($_FILES['file']['name']) ? count($_FILES['file']['name']) : 1;

    $filecnt = 0;
    for ($i = 0; $i < $fileCount; $i++) {
        // Auswahl prüfen: JS sendet nur selektierte Dateien, daher keine Prüfung mehr nötig
        $name = is_array($_FILES['file']['name']) ? $_FILES['file']['name'][$i] : $_FILES['file']['name'];
        $tmp = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][$i] : $_FILES['file']['tmp_name'];
        $error = is_array($_FILES['file']['error']) ? $_FILES['file']['error'][$i] : $_FILES['file']['error'];
        $size = is_array($_FILES['file']['size']) ? $_FILES['file']['size'][$i] : $_FILES['file']['size'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($debug_log) {
            file_put_contents($debug_log_file, "L 0162 Params: name $name size $size ext $ext " . PHP_EOL, FILE_APPEND);
        }
        $rotation = isset($rotations) ? (int)$rotations : 0;
        
        $trans = array(  'ä'=>'ae','ö'=>'oe', 'ü'=>'ue', 'Ä'=>'Ae','Ö'=>'Oe','Ü'=>'Ue','ß'=>'ss',' - '=>'_', '-'=>'_',' '=>'_');  
        $rename = strtr($name,$trans);

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
        if ($debug_log) {
           file_put_contents($debug_log_file, "L 0182 vor move_upl $targetPath; rename $rename  " . PHP_EOL, FILE_APPEND);
        }
        
        if (move_uploaded_file($tmp, $targetPath)) { 
            // Spezialbehandlung für Bilder
            if (in_array($ext, ['gif', 'ico', 'jpeg', 'jpg', 'png', 'tiff']) ) {
                $storePath = "../../AOrd_Verz/$urhNr/09/";  // unvollständiger Pfad zum Zielverzeichnis
                if ($debug_log) {
                    file_put_contents($debug_log_file, "L 0189  Zielpfad $storePath  " . PHP_EOL, FILE_APPEND);
                }
                $targetPath = handleImageSpecial($targetPath, $urhNr, $urhName, $aufnDat,$rotation, $urhEinfgJa, $reSize, $aOrd);        
                if ($debug_log) {
                    file_put_contents($debug_log_file, "L 0193 fertiges Bild $targetPath  " . PHP_EOL, FILE_APPEND);
                }
            } else {
                if (in_array($ext, ['mp3'])) {
                    $storePath = "../../AOrd_Verz/$urhNr/09/02/"; 
                    
                } elseif (in_array($ext, ['mp4'])) {
                    $storePath = "../../AOrd_Verz/$urhNr/09/10/";       // vollständiger Pfad für Videos
                    
                } else {    // if (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods'])) {
                    file_put_contents($debug_log_file, "L 0199 aOrd $aOrd  " . PHP_EOL, FILE_APPEND);
                    if ($eigner != "" && $aOrd != "") {
                        $storePath = "../../AOrd_Verz/$eigner/$aOrd/";  // vollständiger Pfad für Archivdaten
                    } else {
                        $response['errors'][] = "$name: Verzeichnis nach Archivordnung nicht angegeben.";
                    }
                }
                file_put_contents($debug_log_file, "L 0206 storepath $storePath  " . PHP_EOL, FILE_APPEND);
                if (!is_dir($storePath)) {
                    mkdir($storePath, 0755, true);
                }
                copy($targetPath,$storePath . basename($targetPath));
                unset($targetPath);
                $targetPath = $storePath . basename($rename);
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
    if ($debug_log) {
        file_put_contents($debug_log_file, "L 0216 ".json_encode($response) . PHP_EOL, FILE_APPEND);
    }
    
    echo json_encode($response);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage.']);
exit;

