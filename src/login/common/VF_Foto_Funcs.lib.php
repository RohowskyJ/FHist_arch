<?php
/**
 * Funktionen für Foto- Bearbeitung
 *
 * Hochladen mittels prototype.js
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

if ($debug_log) {file_put_contents('Fo_up_debug.log', "VF_Foto_funcs L 007, $inputFile, $outputFile " . PHP_EOL, FILE_APPEND);}

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
    global $ttf_file,$debug_log , $rot_left, $rot_right;
    if (!isset($debug_log)) {$debug_log = False;}
    if (!isset($rot_left)) {$rot_left = False;}
    if (!isset($rot_right)) {$rot_right = False;}

    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0110 maxw $maxWidth maxh $maxHeight file $file \n outp $outputPath \nCR $copyrightText ttf-f $ttf_file \n" . PHP_EOL, FILE_APPEND);}
    if(!isset($ttf_file)) {
        $fontFile = "Fonts/arialbd.ttf";
    } else {
        $fontFile = $ttf_file;
    }
    #if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0118 ttf-f $ttf_file    \n" . PHP_EOL, FILE_APPEND);}
    if (is_file($file)) {
        file_put_contents('API_debug_log', "Foto L 0120 $file existiert \n" . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents('API_debug_log', "Foto L 0122 $file existiert NICHT \n" . PHP_EOL, FILE_APPEND);
    }
    if (is_file($ttf_file)) {
        file_put_contents('API_debug_log', "Foto L 0125 $ttf_file existiert  \n" . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents('API_debug_log', "Foto L 0127 $ttf_file existiert NICHT ++++++++++++++++++++++++++++++++++++++              +++++++++++++++++++++++++++++++++++ \n" . PHP_EOL, FILE_APPEND);
    }

    // EXIF-Daten auslesen und Bild drehen
    $exif = @exif_read_data($file);
    if (!empty($exif['Orientation'])) {
        $orientation = $exif['Orientation'];
        $source = null;

        // Ursprüngliches Bild laden
        switch (exif_imagetype($file)) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($file);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($file);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($file);
                break;
            default:
                throw new Exception('Unsupported image type');
        }

        // Bild drehen basierend auf der EXIF-Ausrichtung
        switch ($orientation) {
            case 3:
                $source = imagerotate($source, 180, 0);
                break;
            case 6:
                $source = imagerotate($source, -90, 0);
                break;
            case 8:
                $source = imagerotate($source, 90, 0);
                break;
        }

        // Bild speichern, um die Änderungen zu übernehmen
        switch (exif_imagetype($file)) {
            case IMAGETYPE_JPEG:
                imagejpeg($source, $file, 100);
                break;
            case IMAGETYPE_PNG:
                imagepng($source, $file);
                break;
            case IMAGETYPE_GIF:
                imagegif($source, $file);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($source, $file);
                break;
        }

        imagedestroy($source);
    }

    // Bildinformationen abrufen
    list($width, $height, $type) = getimagesize($file);
    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0131 w $width  h $height  t $type  \n" . PHP_EOL, FILE_APPEND);}
    // Berechnung des neuen Bildes
    $ratio = $width / $height;
    if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
    } else {
        $maxHeight = $maxWidth / $ratio;
    }
    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0139 maxw $maxWidth  maxh $maxHeight   \n" . PHP_EOL, FILE_APPEND);}
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
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($file);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0160 vor resample. \n" . PHP_EOL, FILE_APPEND);}
    // Bild resizen
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0163 nach resample. \n" . PHP_EOL, FILE_APPEND);}

    if ($rot_right || $rot_left) { // rotate
        if ($rot_left ) {
            $angle = 90;
        }
        if ($rot_right ) {
            $angle = 270;
        }
        if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0172 rotate $angle \n" . PHP_EOL, FILE_APPEND);}
        $newimage = imagerotate($newImage,$angle,0);
        if (!$newimage) {
            if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0175 nach rotate. \n" . PHP_EOL, FILE_APPEND);}
        }

    }

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
        file_put_contents('API_debug_log', "Foto L 0194 nach textbox. \n" . PHP_EOL, FILE_APPEND);
        // Textposition
        /*
         $x = ($maxWidth - $textWidth) / 2; // Zentriert
         $y = $maxHeight - $textHeight - 10; // 10 Pixel vom unteren Rand
         */
        $x = 10; //($maxWidth - $textWidth) ; // Zentriert
        $y = $maxHeight - $textHeight - 1; // 10 Pixel vom unteren Rand
        // Text auf das Bild schreiben

        # imagettftext($newImage, $fontSize, 0, $x-6, $y-6, $textColor_2, $fontFile, $copyrightText);
        imagettftext($newImage, $fontSize, 0, $x, $y, $textColor, $fontFile, $copyrightText);

        imagettftext($newImage, $fontSize, 0, $x-3, $y-3, $textColor_1, $fontFile, $copyrightText);
        if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0208 nach cr_einfügen \n" . PHP_EOL, FILE_APPEND);}
    }

    /*
    // Bild speichern
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($newImage, $outputPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($newImage, $outputPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($newImage, $outputPath);
            break;
    }
    */
    $p_arr = pathinfo ($outputPath, PATHINFO_ALL);
    $resized_file = $p_arr['dirname']."/".$p_arr['filename'].".jpeg";
    # imagewebp($newImage, $resized_file);
    imagejpeg($newImage, $outputPath);
    if ($debug_log) {file_put_contents('API_debug_log', "Foto L 0228  foto_Funcs ausgegeben ".$p_arr['dirname']."/".$p_arr['filename'].".jpeg \n" . PHP_EOL, FILE_APPEND);}

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
    var_dump(gd_info());
}

?>
