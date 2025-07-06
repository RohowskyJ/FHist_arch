<?php
/**
 * Funktionen für Foto- Bearbeitung
 *
 * Hochladen mittels AJAX mit jquery
 * Resizing Copyright einfügen und Umbenennen (Urheber)wenn jpg, png oder gif
 *
 *
 */

/**
 * Funktionen
 *  convertImage    Konvertieren von jpg, png, gif in eine der drei
 */
/**
 * Image- Convertierung von jpg, png, gif in eine der drei
 * @param string $inputFile
 * @param string $outputFile
 */

function convertImage($inputFile, $outputFile) {
   global $debug_log;

file_put_contents('Fo_up_debug.log', "VF_Foto_funcs L 024, $inputFile, $outputFile " . PHP_EOL, FILE_APPEND);

    // Bildinformationen abrufen
    list($width, $height, $type) = getimagesize($inputFile);

    // Ursprüngliches Bild laden
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($inputFile);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($inputFile);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($inputFile);
            break;
        default:
            throw new Exception('Unsupported image type');
    }

    // Neues Bild erstellen
    $newImage = imagecreatetruecolor($width, $height);

    // Bild kopieren
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $width, $height, $width, $height);
    /*
    // Bild speichern
    $outputType = pathinfo($outputFile, PATHINFO_EXTENSION);
    switch (strtolower($outputType)) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($newImage, $outputFile);
            break;
        case 'png':
            imagepng($newImage, $outputFile);
            break;
        case 'gif':
            imagegif($newImage, $outputFile);
            break;
        default:
            throw new Exception('Unsupported output image type');
    }
   
    imagewebp($newImage, $outputFile);
 */
    imagejpeg($newImage, $outputFile);
    // Speicher freigeben
    imagedestroy($source);
    imagedestroy($newImage);

/**
 *

// Beispielaufruf
try {
    $inputFile = 'path/to/your/image.jpg'; // Pfad zum Eingabebild
    $outputFile = 'path/to/your/image.png'; // Pfad zum Ausgabebild

    convertImage($inputFile, $outputFile);
    echo "Bild erfolgreich konvertiert.";
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
?>

Erläuterungen zum Skript

    convertImage-Funktion: Diese Funktion nimmt den Pfad zum Eingabebild und den Pfad zum Ausgabebild entgegen. Sie lädt das Bild basierend auf dem Typ, resized es (in diesem Fall wird die Größe nicht verändert, aber du kannst das anpassen) und speichert es im gewünschten Format.
    Typen: Das Skript unterstützt JPEG, PNG und GIF. Du kannst die unterstützten Typen erweitern, wenn nötig.
    Speichern: Das Bild wird im Format gespeichert, das durch die Dateiendung des Ausgabepfads bestimmt wird.

Verwendung

    Ersetze die Platzhalter path/to/your/image.jpg und path/to/your/image.png durch die tatsächlichen Pfade zu deinem Eingangs- und Ausgangsbild.
    Stelle sicher, dass die GD-Bibliothek in deiner PHP-Installation aktiviert ist.
    Führe das Skript aus, um das Bild zu konvertieren.
*/
} // ende funct convertImage

/**
 *
 * @param string $file
 * @param string $maxWidth
 * @param string $maxHeight
 * @param string $outputPath
 * @param string $copyrightText
 */
function resizeImage($file, $maxWidth='800', $maxHeight='800', $outputPath = '', $copyrightText = '' ) {
    global $ttf_file,$debug_log , $rotation;
    if (!isset($debug_log)) {$debug_log = False;}
    if (!isset($rotation)) {$rotation = '0';}

    // if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0116 maxw $maxWidth maxh $maxHeight file $file \n outp $outputPath \nCR $copyrightText ttf-f $ttf_file \n" . PHP_EOL, FILE_APPEND);}
    
    if(!isset($ttf_file)) {
        $fontFile = "Fonts/arialbd.ttf";
    } else {
        $fontFile = $ttf_file;
    }

    if ($rotation >= 0) {
        if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0189 in sel rotate rotation = $rotation  \n" . PHP_EOL, FILE_APPEND);}
        $img = NULL;
        $img = imagecreatefromstring(file_get_contents($file));
        if (!$img) {
            echo json_encode(['status'=>'error', 'message'=>'Bild laden fehlgeschlagen']);
            exit;
        }
        $rot_img = imagerotate($img, $rotation, 0);
        
        // Bild speichern, um die Änderungen zu übernehmen
        switch (exif_imagetype($file)) {
            case IMAGETYPE_JPEG:
                imagejpeg($rot_img, $file, 100);
                break;
            case IMAGETYPE_PNG:
                imagepng($rot_img, $file);
                break;
            case IMAGETYPE_GIF:
                imagegif($rot_img, $file);
                break;
        }
        imagedestroy($img);
    }
    
    // Bildinformationen abrufen
    list($width, $height, $type) = getimagesize($file);
    #if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0199 w $width  h $height  t $type  \n" . PHP_EOL, FILE_APPEND);}
    // Berechnung des neuen Bildes
    $ratio = $width / $height;
    if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
    } else {
        $maxHeight = $maxWidth / $ratio;
    }
    
    $maxWidth = intval($maxWidth);
    $maxHeight =intval($maxHeight);
    
    #if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0207 maxw $maxWidth  maxh $maxHeight   \n" . PHP_EOL, FILE_APPEND);}
    // Neues Bild erstellen
    $newImage = imagecreatetruecolor($maxWidth, $maxHeight);

    // Ursprüngliches Bild laden
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($file);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($file);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
   # if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0225 vor resample. \n" . PHP_EOL, FILE_APPEND);}
    // Bild resizen
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
    #if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0229 nach resample. \n" . PHP_EOL, FILE_APPEND);}

    if ($copyrightText  != "") {
        // Copyright-Text hinzufügen
        $textColor = imagecolorallocate($newImage, 255, 255, 255); // Weiß
        $textColor_1 = imagecolorallocate($newImage, 0, 0, 0); // schwarz
        #$textColor_2 = imagecolorallocate($newImage, 255, 87, 51); // orange

        $fontSize = 18; // Schriftgröße

        #$fontFile = 'path/to/your/font.ttf'; // Pfad zur TTF-Schriftart
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $copyrightText);

        #var_dump($textBox);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[1] - $textBox[7];
        #file_put_contents('Foto_Func_debug.log', "Foto L 0264 nach textbox. \n" . PHP_EOL, FILE_APPEND);
        // Textposition
        /*
         $x = ($maxWidth - $textWidth) / 2; // Zentriert
         $y = $maxHeight - $textHeight - 10; // 10 Pixel vom unteren Rand
         */
        $x = 10; //($maxWidth - $textWidth) ; // Zentriert
        $y = $maxHeight - $textHeight - 1; // 10 Pixel vom unteren Rand
        // Text auf das Bild schreiben
        
        $x = intval($x);
        $y = intval($y);
        
        # imagettftext($newImage, $fontSize, 0, $x-6, $y-6, $textColor_2, $fontFile, $copyrightText);
        imagettftext($newImage, $fontSize, 0, $x, $y, $textColor, $fontFile, $copyrightText);

        imagettftext($newImage, $fontSize, 0, $x-3, $y-3, $textColor_1, $fontFile, $copyrightText);
        #if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0258 nach cr_einfügen \n" . PHP_EOL, FILE_APPEND);}
    }

    $p_arr = pathinfo ($outputPath, PATHINFO_ALL);
    $resized_file = $p_arr['dirname']."/".$p_arr['filename'].".jpg";
    # imagewebp($newImage, $resized_file);
    imagejpeg($newImage, $outputPath);
    #if ($debug_log) {file_put_contents('Foto_Func_debug.log', "Foto L 0279  foto_Funcs ausgegeben $resized_file \n" . PHP_EOL, FILE_APPEND);}

    // Speicher freigeben
    imagedestroy($source);
    imagedestroy($newImage);

    unlink($file); // löschen der Input- Datei

    return ($resized_file);
/**
 *

// Beispielaufruf
try {
    $inputFile = 'path/to/your/image.jpg'; // Pfad zum Eingabebild
    $outputFile = 'path/to/your/resized_image.jpg'; // Pfad zum Ausgabebild
    $copyrightText = '© 2024 Dein Name'; // Copyright-Text
    $ttf_file = ""; # one of: BAYLEYSC.TTF black_fire.ttf Fire Storm.otf SEAWFA__.TTF
    resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
    echo "Bild erfolgreich resized und Copyright-Text hinzugefügt.";
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
?>

2. Erläuterungen zum Skript

    resizeImage-Funktion: Diese Funktion nimmt das Eingabebild, die maximalen Breiten- und Höhenwerte, den Ausgabepfad und den Copyright-Text entgegen.
    Bildtypen: Das Skript unterstützt JPEG, PNG und GIF. Du kannst die unterstützten Typen erweitern, wenn nötig.
    Schriftart: Du musst eine TTF-Schriftart auf deinem Server haben. Ändere den Pfad in der Variable $fontFile zu einer gültigen TTF-Datei.
    Textposition: Der Copyright-Text wird zentriert und 10 Pixel vom unteren Rand des Bildes platziert.

3. Verwendung

    Ersetze die Platzhalter path/to/your/image.jpg und path/to/your/resized_image.jpg durch die tatsächlichen Pfade zu deinem Eingangs- und Ausgangsbild.
    Stelle sicher, dass die GD-Bibliothek in deiner PHP-Installation aktiviert ist.
    Führe das Skript aus, um das Bild zu verarbeiten.
 */
} // ende function resizeImage

function gd_inform() {
    #var_dump(gd_info());
    file_put_contents('Foto_Func_debug.log', "Foto L 0116 maxw $maxWidth maxh $maxHeight file $file \n outp $outputPath \nCR $copyrightText ttf-f $ttf_file \n" . PHP_EOL, FILE_APPEND);
}

?>
