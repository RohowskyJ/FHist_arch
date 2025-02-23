<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verzeichnis, in das die Dateien hochgeladen werden
    $uploadDir = 'VF_Upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Verzeichnis erstellen, falls es nicht existiert
    }
    
    if (isset($_POST['pUid'])) {
        echo $_POST['pUid']." ".$_POST['urHeber']." ".$_POST['aufnDat']."\n";
    }

    // Überprüfen, ob Dateien hochgeladen wurden
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        $uploadedFiles = [];
        foreach ($_FILES['files']['name'] as $key => $name) {
            $tmpName = $_FILES['files']['tmp_name'][$key];
            $uploadFile = mb_strtolower($uploadDir . basename($name));

            // Datei hochladen
            if (move_uploaded_file($tmpName, $uploadFile)) {
                $uploadedFiles[] = $name ; // Erfolgreich hochgeladene Datei speichern

                foreach (GrafFiles as $ext) {
                    if (stripos($uploadFile, $ext)) {
                        // resize und CR- String
                    }
                }
            } else {
                echo "Fehler beim Hochladen der Datei: $name";
            }
        }

        // Erfolgreich hochgeladene Dateien zurückgeben
        if (!empty($uploadedFiles)) {
            echo "Hochgeladene Dateien: " . implode(', ', $uploadedFiles);

        } else {
            echo "Keine Dateien hochgeladen.";
        }
    } else {
        echo "Keine Dateien hochgeladen.";
    }
} else {
    echo "Keine Dateien hochgeladen.";
}

// Header für JSON setzen
header('Content-Type: application/json');

// JSON-Daten ausgeben
#echo json_encode($data);
?>
