<?php

require "../VF_Foto_Funcs.lib.php";
require "../VF_Const.lib.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
    $eintragen = Date("Y-m-d H:i:s")."\n";
    // parameters: { query : query, proc: proc, based: based, zuspf: zuspf, aufn: aufn, suff: suff },
    foreach ($_POST as $k => $v) {
        $eintragen .= "$k : $v \n";
    }
    $dsn = "upload.log";
    
    $datei = fopen($dsn, "a");
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);
    */
    $p_uid = "";
    if (isset($_POST['pUid'])) {
        $p_uid = $_POST['pUid'];
    }
    
    
    $uploadDir = '../../VF_Upload/';
    
    if ($p_uid != "") {
        $uploadDir .= "$p_uid/";
    }
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    header('Content-Type: application/json');
    
    $allowed_extensions = ["gif", "ico", "jpeg", "jpg", "png", "tiff", "mp4", "pdf"];
    
    // Überprüfen, ob eine Datei hochgeladen wurde
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $name = $_FILES['file']['name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        
        // Überprüfen, ob die Dateiendung erlaubt ist
        if (in_array(strtolower($extension), $allowed_extensions)) {
            $tmpName = $_FILES['file']['tmp_name'];
            $uploadFile = mb_strtolower($uploadDir . basename($name));
            
            // Datei hochladen
            if (move_uploaded_file($tmpName, $uploadFile)) {
                /*
                // Bildbearbeitung
                if (in_array(strtolower($extension), ["gif", "ico", "jpeg", "jpg", "png", "tiff"])) {
                    $image = imagecreatefromstring(file_get_contents($uploadFile));
                    $resizedImage = imagescale($image, 800, 800); // Bild auf 800x800 skalieren
                    
                    // Urheber-Info hinzufügen (z.B. als Wasserzeichen)
//                     $fontPath = '    Fonts/OpenFlame.ttf'; // Pfad zur Schriftart
                    $text = $_POST['urHeber'];
                    $black = imagecolorallocate($resizedImage, 0, 0, 0);
                    imagettftext($resizedImage, 20, 0, 10, 20, $black, $fontPath, $text);
                    
                    // Speichern des bearbeiteten Bildes
                    imagejpeg($resizedImage, $uploadFile); // Speichern als JPEG
                    $p_arr = pathinfo ($uploadFile, PATHINFO_ALL);
                    imagewebp($newImage, $p_arr['dirname']."/".$p_arr['filename'].".WebP");
                    imagedestroy($image);
                    imagedestroy($resizedImage);
                }
                */
                $response['valid_files'][] = $name; // Erfolgreich hochgeladene Datei speichern
            } else {
                $response['invalid_files'][] = "Fehler beim Hochladen der Datei: $name";
            }
        } else {
            $response['invalid_files'][] = $name; // Ungültige Datei zur Liste hinzufügen
        }
    } else {
        $response['invalid_files'][] = "Keine Datei hochgeladen oder ein Fehler ist aufgetreten.";
    }
}

// Rückgabe der Ergebnisse als JSON
echo json_encode($response);

?>
