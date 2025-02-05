<?php
session_start();
require "../VF_Foto_Funcs.inc.php";
require "../VF_Const.inc.php";

#var_dump($_SESSION['OEF']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug.log', "L 009 " . PHP_EOL, FILE_APPEND);
    $eintragen = Date("Y-m-d H:i:s") . "\n";
    // parameters: { query : query, proc: proc, based: based, zuspf: zuspf, aufn: aufn, suff: suff },
    foreach ($_POST as $k => $v) {
        $eintragen .= "$k : $v \n";
    }
    file_put_contents('debug.log', "L 015 $eintragen" . PHP_EOL, FILE_APPEND);
    
    if (isset($_POST['urhName'])) {
        $urh_name = $_POST['urhName'];
    }
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

    
    $uploadDir = "../../$targ_pfad";

    if (! is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    # header('Content-Type: application/json');

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
$p_uid = $_SESSION['VF_prim']['p_uid'];
            file_put_contents('debug.log', "L 067 Uploadfile  $uploadFile \n p_uid $p_uid \n" . PHP_EOL, FILE_APPEND);

            // Datei hochladen
            if (move_uploaded_file($tmpName, $uploadFile)) {
               
                file_put_contents('debug.log', "L 070 hochgeladen \n" . PHP_EOL, FILE_APPEND);
                
                $fn_arr = explode("-", $name);
                $fcnt = count($fn_arr);
                if ($fcnt < 3) { // Umbenennen
                    $out_name = $urh_abk . "-" . $aufn_dat . "-" . $name;
                } else { // orig-Name bleibt
                    $out_name = $name;
                }
                $outputFile = '../../' . $targ_pfad . $out_name;
                
                file_put_contents('debug.log', "L 081 Outputfile  $outputFile \n" . PHP_EOL, FILE_APPEND);

                if (in_array(strtolower($extension), GrafFiles)) { // bearbeiten
                    
                    file_put_contents('debug.log', "L 085 fotobearb? \n" . PHP_EOL, FILE_APPEND);

                    $InputFile = '../../' . $targ_pfad . $name;
                    $response = array('success'=>true, 'message'=> $InputFile.' neuer Name');
                    echo json_encode($response);
                    
                    file_put_contents('debug.log', "L 091 InputFile $InputFile \n outputFile $outputFile \n" . PHP_EOL, FILE_APPEND);
                    if ($urh_einfueg == "J") {
                        
                        file_put_contents('debug.log', "L 099 Urheber ins Bild Ja \n" . PHP_EOL, FILE_APPEND);
                        
                        $ttf_file = "../Fonts/arialbd.ttf";
                        
                        $CR_text = '© '.$urh_name;
                        
                        file_put_contents('debug.log', "L 0101 CR Text $CR_text \n" . PHP_EOL, FILE_APPEND);
                        # resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
                        if (!resizeImage($InputFile, 800, 800, $outputFile, $CR_text)) {
                            
                            file_put_contents('debug.log', "L 0105 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);
                            
                        }
                    } else {
                        file_put_contents('debug.log', "L 0109 vor resize img ohne cd text \n" . PHP_EOL, FILE_APPEND);
                        # resizeImage($inputFile, 800, 600, $outputFile); // Maximal 800x600
                        if (!resizeImage($InputFile, 800, 600, $outputFile)) {
                            
                            file_put_contents('debug.log', "L 0113 Fehler beim Resizing des Bildes. \n" . PHP_EOL, FILE_APPEND);
                            
                        }
                    }
                }
                file_put_contents('debug.log', "L 0118 Urheber ins Bild ?? \n" . PHP_EOL, FILE_APPEND);

            }
            
            file_put_contents('debug.log', "L 0124 ende hochladen  $outputFile \n" . PHP_EOL, FILE_APPEND);
                
                # echo "Bild erfolgreich resized und Copyright-Text hinzugefügt. <br>";
                #$response['valid_files'][] = $name; // Erfolgreich hochgeladene Datei speichern
                $response = array('success'=>true, 'message'=> $name.'  hochgeladen');

        } else {
            #$response['invalid_files'][] = $name; // Ungültige Datei zur Liste hinzufügen
            $response = array('success'=>false, 'message'=>$name .' ungültig');
        }
        
    } else {
        # $response['invalid_files'][] = "Fehler beim Hochladen der Datei: $name";
        $response = array('success'=>false, 'message'=> $name .' NICHT hochgeladen');
    }

    
    
}
header('Content-Type: application/json'); // Setze den Content-Type auf JSON
file_put_contents('debug.log', json_encode($response) . PHP_EOL, FILE_APPEND);
// Rückgabe der Ergebnisse als JSON
echo json_encode($response);


/*
   
                if (in_array(strtolower($extension), GrafFiles)) { // bearbeiten
                    $eintragen .= "L 093 fotobearb? \n";
                    
                    $datei = fopen($dsn, 'a');
                    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                    fclose($datei);
                    $eintragen = "";
                    
                    $InputFile = $outputFile;
                   
                            $eintragen .= "L 0102 InputFile $InputFile \n outputFile $outputFile \n";
                            $datei = fopen($log_file, 'a');
                            fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                            fclose($datei);
                            $eintragen = "";
                           
                        
                    }
                   
                    $eintragen .= "L 0111 Urheber ins Bild \n";
                    $datei = fopen($log_file, 'a');
                    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                    fclose($datei);
                    $eintragen = "";
                    
                   
                    
                    if ($urh_einfueg == "J") {
                        $eintragen .= "L 0128 Urheber ins Bild \n";
                        $datei = fopen($log_file, 'at');
                        fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                        fclose($datei);
                        $eintragen = "";
                        
                        $ttf_file = "common/fonts/arialbd.ttf";
                        
                        if (isset($_SESSION[$module]['Up_Parm']['urh_abk'])) {
                            $CR_text = $_SESSION[$module]['Up_Parm']['urh_abk'][$urh] . " " . $_SESSION[$module]['Up_Parm']['fm_urheber'];
                        } else {
                            $CR_text = $urh_abk . " " . $urh_name;
                        }
                        # resizeImage($inputFile, 800, 600, $outputFile, $copyrightText); // Maximal 800x600
                        if (!resizeImage($inputFile, 800, 600, $outputFile, $copyrightText)) {
                            $eintragen .= "L 0146 Fehler beim Resizing des Bildes. \n";
                            $datei = fopen($dsn, "a");
                            fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                            fclose($datei);
                            $eintragen = "";
                            
                        }
                    } else {
                        # resizeImage($inputFile, 800, 600, $outputFile); // Maximal 800x600
                        if (!resizeImage($inputFile, 800, 600, $outputFile)) {
                            $eintragen .= "L 0131 Fehler beim Resizing des Bildes. \n";
                            $datei = fopen($dsn, "a");
                            fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                            fclose($datei);
                            $eintragen = "";
                            
                        }
                    }
                }
                
                $eintragen .= " L 0137 ende hochladen  $outputFile \n";
                $datei = fopen($dsn, "a");
                fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
                fclose($datei);
                $eintragen = "";
*/
?>
