<?php

require "../VF_Foto_Funcs.lib.php";
require "../VF_Const.lib.php";

$debug_log = True;
if ($debug_log) {file_put_contents('Fo_up_debug.log', "BA_Upl_loc L 007 " . PHP_EOL, FILE_APPEND);}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $eintragen = Date("Y-m-d H:i:s") . "\n";

    if (isset($_POST['targPfad'])) {
        $targ_pfad = $_POST['targPfad'];
    }
    if (isset($_POST['urhName'])) {
        $urh_name = $_POST['urhName'];
    }
    if (isset($_POST['urhAbk'])) {
        $urh_abk = $_POST['urhAbk'];
    }
    if (isset($_POST['urhEinfg'])) {
        $urh_einfueg = $_POST['urhEinfg'];
    }
    if (isset($_POST['aufnDat'])) {
        $aufn_dat = $_POST['aufnDat'];
    }
    if (isset($_POST['beglTxt'])) {
        $bechreibg = $_POST['beglTxt'];
    }

    if ($debug_log) {
        $eintragen .= "targ_pfad $targ_pfad \n";
        $eintragen .= "urh_name $urh_name \n";
        $eintragen .= "urh_abk $urh_abk \n";
        $eintragen .= "urh_einfueg $urh_einfueg \n";
        $eintragen .= "aufn_dat $aufn_dat \n";
        $eintragen .= "beschreibg $bechreibg \n";
        file_put_contents('Fo_up_debug.log', "L 039 $eintragen" . PHP_EOL, FILE_APPEND);
    }

    // Zugriff auf die Checkboxen
    $rotateLeft = isset($_POST['rotateLeft']) ? $_POST['rotateLeft'] : [];
    $rotateRight = isset($_POST['rotateRight']) ? $_POST['rotateRight'] : [];

    $uploadDir = "../../$targ_pfad";

    if (! is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    header('Content-Type: application/json');

    $allowed_extensions = [
        "gif",
        "ico",
        "jpeg",
        "jpg",
        "png",
        "tiff",
        "webp",
        "mp4"
    ];

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        $name = $_FILES['file']['name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Überprüfen, ob die Dateiendung erlaubt ist
        if (in_array(strtolower($extension), $allowed_extensions)) {
            $tmpName = $_FILES['file']['tmp_name'];
            $uploadFile = mb_strtolower($uploadDir . basename($name));

            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 089 Uploadfile  $uploadFile \n " . PHP_EOL, FILE_APPEND);}

            // Datei hochladen
            if (move_uploaded_file($tmpName, $uploadFile)) {

                if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 080 hochgeladen \n" . PHP_EOL, FILE_APPEND);}

                $fn_arr = explode("-", $name);
                $fcnt = count($fn_arr);
                if ($fcnt < 3) { // Umbenennen
                    $out_name = $urh_abk . "-" . $aufn_dat . "-" . $name;
                } else { // orig-Name bleibt
                    $out_name = $name;
                }
                $outputFile = '../../' . $targ_pfad . $out_name;

                if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0105 Outputfile  $outputFile \n" . PHP_EOL, FILE_APPEND);}

                if (in_array(strtolower($extension), GrafFiles)) { // bearbeiten

                    if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0109 fotobearb? \n" . PHP_EOL, FILE_APPEND);}


                    $InputFile = '../../' . $targ_pfad . $name;

                    if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0113 InputFile $InputFile \n outputFile $outputFile \n" . PHP_EOL, FILE_APPEND);}

                    // Beispiel: Verarbeitung der Checkboxen
                    $rot_rtight = $rot_left = False;
                    if (!empty($rotateLeft)) {
                        foreach ($rotateLeft as $fileName) {
                            // Hier können Sie die Logik für die Links-Dreh-Operation implementieren
                            #echo "Datei $fileName soll links gedreht werden.<br>";
                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0103 Datei $fileName soll links gedreht werden. \n " . PHP_EOL, FILE_APPEND);}
                        }
                        if (in_array($name, $rotateLeft)) {
                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0117 Datei $name soll rechts gedreht werden. \n " . PHP_EOL, FILE_APPEND);}
                            $rot_left = True;
                        }
                    }

                    if (!empty($rotateRight)) {
                        foreach ($rotateRight as $fileName) {
                            // Hier können Sie die Logik für die Rechts-Dreh-Operation implementieren
                            #echo "Datei $fileName soll rechts gedreht werden.<br>";
                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0116 Datei $fileName soll rechts gedreht werden. \n " . PHP_EOL, FILE_APPEND);}
                        }
                        if (in_array($name, $rotateRight)) {
                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0119 Datei $name soll rechts gedreht werden. \n " . PHP_EOL, FILE_APPEND);}
                            $rot_right = True;
                        }
                    }

                    if ($urh_einfueg == "J") {

                        if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0129 Urheber ins Bild Ja \n" . PHP_EOL, FILE_APPEND);}

                        $ttf_file = "../Fonts/arialbd.ttf";

                        $CR_text = '© '.$urh_name;

                        if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0131 CR Text $CR_text \n" . PHP_EOL, FILE_APPEND);}
                        # resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
                        $res_file = resizeImage($InputFile, 800, 800, $outputFile, $CR_text);
                        if ($res_file == "") {

                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0137 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);}

                        } else {
                            $response['valid_files'][] = $res_file; // Erfolgreich hochgeladene Datei speichern
                        }
                    } else {
                        if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0143 vor resize img ohne cd text \n" . PHP_EOL, FILE_APPEND);}
                        # resizeImage($inputFile, 800, 600, $outputFile); // Maximal 800x600

                        $res_file = !resizeImage($InputFile, 800, 800, $outputFile);
                        if ($res_file == "") {

                            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0149 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);}
                        } else {
                            $response['valid_files'][] = $res_file; // Erfolgreich hochgeladene Datei speichern
                        }
                    }
                   ;
                }
                if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0156 Urheber ins Bild ?? \n" . PHP_EOL, FILE_APPEND);}

            }

            if ($debug_log) {file_put_contents('Fo_up_debug.log', "L 0160 ende hochladen  $outputFile \n" . PHP_EOL, FILE_APPEND);}


            if (!isset($response))  {
                $response['valid_files'][] = $name; // Erfolgreich hochgeladene Datei speichern
            }

        } else {
            $response['invalid_files'][] = $name; // Ungültige Datei zur Liste hinzufügen
        }

    } else {
        $response['invalid_files'][] = "Fehler beim Hochladen der Datei: $name";
    }

if ($debug_log) {file_put_contents('Fo_up_debug.log', json_encode($response) . PHP_EOL, FILE_APPEND);}
// Rückgabe der Ergebnisse als JSON
echo json_encode($response);

}
?>
