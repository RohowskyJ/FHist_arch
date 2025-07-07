<?php

# header('Content-Type: application/json');

require "../VF_Foto_Funcs.lib.php";
require "../VF_Const.lib.php";

/* für PHP-Logging 
 * ini_set('display_errors', '1');
ini_set("log_errors", 1);
ini_set("error_log", "MUP_php-error.log");
error_log( "Hello, errors!" );
*/

$debug_log = False;

if ($debug_log) {file_put_contents('Media_up_debug.log', "VF_Upload_Media.API L 007 " . PHP_EOL, FILE_APPEND);}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) { #) { # $_SERVER['REQUEST_METHOD'] === 'POST' ) { #

    $eintragen = Date("Y-m-d H:i:s") . "\n";

    $urhNr = $_POST['urhNr'] ?? '';
    $urhName = $_POST['urhName'] ?? '';
    $aufnDat = $_POST['aufnDat'] ?? '';
    
    $uploadsArr = $_POST['upload'] ?? [];
    $rotationArr = $_POST['rotation'] ?? [];
    $watermarkArr = $_POST['watermark'] ?? [];
  
    /**  Testt- Parms für direkt- Aufruf
    $urhNr = '77';
    $urhName = 'Kasermandl';
    $aufnDat  = '20250704_lo';
    $uploadsArr = array('0' => '1','1' => '0');
    $rotationArr = array('0' => '0','1'=> '180');
    $watermarkArr = array('0' => '1','1' =>'0');
    $fileName = "IMG_3816.JPG";
    $InputFile = $tmpPath = "IMG_3816.JPG";
    */
   
    $basispfad = "../../AOrd_Verz/$urhNr/09/";
    
    if ($debug_log) {
        $eintragen .= "urh_name $urhName \n";
        $eintragen .= "urh_nr  \n";
        $eintragen .= "aufn_dat $aufnDat \n";
        $eintragen .= "Basispfad = $basispfad\n";
        file_put_contents('Media_up_debug.log', "L 039 $eintragen" . PHP_EOL, FILE_APPEND);
    }

    $allowed_extensions = [
        "gif",
        "ico",
        "jpeg",
        "jpg",
        "png",
        "tiff",
        "mp3",
        "mp4"
    ];

    ### neu anfang
    
    $fileData = $_FILES['file'];
    $tmpPath = $fileData['tmp_name'];
    $fileName = basename($fileData['name']);
    
    $eintragen .= "fileName $fileName\n";
    
    if (isset($_FILES['file']) && !$_FILES['file']['error'] === UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'error', "message'=>'Datei $fileName laden fehlgeschlagen"]);
        exit;
    }
   
    /** vorbereitung für rename */
    $fn_arr = explode("-", $fileName);
    $fcnt = count($fn_arr);
    
    if (isset($uploadsArr)) { // $_POST['upload']
        $eintragen .= "L 070 <br>";
        foreach ($uploadsArr as $k => $value) { // $_POST['upload']
            $eintragen .= "L 072 <br>";
            if ($value == 1) { // hochladen
                $rotation = isset($rotationArr[$k]) ? (int)$rotationArr[$k] : 0;
                $watermarkFlag = isset($watermarkArr[$k]) ? (int)$watermarkArr[$k] : 0;
                
                $eintragen .= "watermark $watermarkFlag\n";
                $eintragen .= "rotate $rotation\n";
                
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $eintragen .= "L 079 <br>";
                // Überprüfen, ob die Dateiendung erlaubt ist
                if (in_array($extension, $allowed_extensions)) {
                    $eintragen .= "L 082 <br>";
                    echo "L 094 ext $extension <br>";
                    $uploadDir = '';
                    switch ($extension) {
                        case "mp3"  :
                            $uploadDir = $basispfad.'02/'.$aufDat.'/';
                            $fTyp = 'Aud';
                            break;
                        case "gif"  :
                        case "ico"  :
                        case "jpg"  :
                        case "jpeg" :
                        case "png"  :
                        case "tiff" :
                            $uploadDir = $basispfad.'06/'.$aufnDat.'/';
                            $fTyp = 'Fot';
                            break;
                        case "mp4"  :
                            $uploadDir = $basispfad.'10/.$aufnDat.'/'';
                            $fTyp = 'Vid';
                            break;
                    }
                    echo "L 0114 upldir $uploadDir <br>";
                    $eintragen .= "L 0100 Uploaddir = $uploadDir\n";
                    if (! is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                } else {
                    echo json_encode(['status'=>'error', "message'=>'Datei $fileName : ungültige Datei (Extension)"]);
                    exit;
                }
                
                $uploadFile = mb_strtolower($uploadDir . basename($fileName));
                
                if (move_uploaded_file($tmpPath, $uploadFile)) {
                    if (in_array(strtolower($extension), GrafFiles)) { /** bearbeiten - Resize, CR- Watermark, Drehen */
                        
                        $InputFile = $uploadFile;
                        if ($fcnt < 3) { // Umbenennen
                            $w = "N-";
                            $w = "W-";
                            $out_name = $urhNr . "-" . $aufnDat . "-".$w . $fileName;
                        } else { // orig-Name bleibt
                            $out_name = $fileName;
                        }
                        $outputFile = $uploadDir . $out_name; ## '../../' . 
                        
                        $ttf_file = "../Fonts/arialbd.ttf";
                        
                        $CR_text = '© '.$urhName;
                        
                        if ($debug_log) {file_put_contents('Media_up_debug.log', "L 0137 CR Text $CR_text \n" . PHP_EOL, FILE_APPEND);}
                        # resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
                        $res_file = resizeImage($InputFile, 800, 800, $outputFile, $CR_text);
                        if ($res_file == "") {
                            
                            if ($debug_log) {file_put_contents('Media_up_debug.log', "L 0142 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);}
                            
                        } else {
                            $response['valid_files'][] = $res_file; // Erfolgreich hochgeladene Datei speichern
                        }
                        
                    } else {  /** nicht bearbeiten, nur Rückmeldung dass OK */
                        echo json_encode(['status'=>'success', 'message'=>'Datei gespeichert', 'path'=>$uploadFile]);
                    }
                }
            }
        }
    }
}
?>
