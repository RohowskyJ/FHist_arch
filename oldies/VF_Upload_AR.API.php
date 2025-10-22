<?php

require "../VF_Const.lib.php";

$debug_log = false;
if ($debug_log) {
    file_put_contents('AR_Up_debug.log', "BA_Upl_loc L 007 " . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $eintragen = Date("Y-m-d H:i:s") . "\n";

    if (isset($_POST['eiId'])) {
        $ei_id = $_POST['eiId'];
    }

    if (isset($_POST['docPfad'])) {
        $doc_pfad = $_POST['docPfad'];
    }

    if ($debug_log) {
        $eintragen = "ei_id $ei_id \n";
        file_put_contents('AR_Up_debug.log', "L 039 $eintragen" . PHP_EOL, FILE_APPEND);
    }

    $uploadDir = "../../$doc_pfad";

    if (! is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    header('Content-Type: application/json');

    $allowed_extensions = [
        "jpeg",
        "jpg",
        "webp",
        "pdf"
    ];

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        $name = $_FILES['file']['name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        // Überprüfen, ob die Dateiendung erlaubt ist
        if (in_array(strtolower($extension), $allowed_extensions)) {
            $tmpName = $_FILES['file']['tmp_name'];
            $uploadFile = mb_strtolower($uploadDir . basename($name));

            if ($debug_log) {
                file_put_contents('AR_Up_debug.log', "L 089 Uploadfile  $uploadFile \n " . PHP_EOL, FILE_APPEND);
            }

            // Datei hochladen
            if (move_uploaded_file($tmpName, $uploadFile)) {

                if ($debug_log) {
                    file_put_contents('AR_Up_debug.log', "L 0105 Outputfile  $uploadFile \n" . PHP_EOL, FILE_APPEND);
                }
            }

            if ($debug_log) {
                file_put_contents('AR_Up_debug.log', "L 0160 $name ende hochladen  $uploadFile \n" . PHP_EOL, FILE_APPEND);
            }
            # $name = $outputFile;

            if (!isset($response)) {
                $response['valid_files'][] = $name; // Erfolgreich hochgeladene Datei speichern
            }

        } else {
            $response['invalid_files'][] = $name; // Ungültige Datei zur Liste hinzufügen
        }

    } else {
        $response['invalid_files'][] = "Fehler beim Hochladen der Datei: $name";
    }

    if ($debug_log) {
        file_put_contents('AR_Up_debug.log', json_encode($response) . PHP_EOL, FILE_APPEND);
    }
    // Rückgabe der Ergebnisse als JSON
    echo json_encode($response);

}
