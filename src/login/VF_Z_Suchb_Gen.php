<?php

/**
 * Neu generieren der Suchbegriffe
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();

$module = 'ADM';

$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path 
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_Suchb_Gen.php";

$debug = False;

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES
setlocale(LC_CTYPE, "de_AT"); // für Klassifizierung und Umwandlung von Zeichen, zum Beispiel strtoupper

VF_chk_valid();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$db = LinkDB('VFH'); // db zur Datenbank

$jq = True;
BA_HTML_Header('Regeneration Findbücher','', 'Form', '75em');

?>

    <h1>Findbücher- Regenerierung</h1>  
    <!--  <button id="fetchButton">Daten abrufen</button> -->

    <div id="status1" style="margin-top: 10px; font-weight: bold;"></div>
    <div id="status2" style="margin-top: 10px; font-weight: bold;"></div>

    <div id="output1"></div>
    <div id="output2"></div>


 <script>
$(document).ready(function() {
        function fetchData() {
            console.log('Started');

            // Erster Ajax-Request für den ersten Prozess
            $.ajax({
                url: 'common/API/VF_Z_Suchb_name_API.php',
                method: 'GET',
                beforeSend: function() {
                    $('#status1').text('Namens-Findbuch Regenerierung wurde gestartet ...');
                },
                success: function(response) {
                    var lines1 = response.split('\n'); // Zeilen aufteilen
                    displayData1(lines1); // Daten in der Liste anzeigen
                    $('#status1').text('Namens-Findbuch Regenerierung wurde erfolgreich beendet.');
                },
                error: function() {
                    $('#status1').text('Fehler beim Regenerieren des Namens-Findbuches.');
                }
            });

            // Zweiter Ajax-Request für den zweiten Prozess
            $.ajax({
                url: 'common/API/VF_Z_Suchb_findb_API.php',
                method: 'GET',
                beforeSend: function() {
                    $('#status2').text('Findbuch Regenerierung wurde gestartet ...');
                },
                success: function(response) {
                    var lines2 = response.split('\n'); // Zeilen aufteilen
                    displayData2(lines2); // Daten in der Liste anzeigen
                    $('#status2').text('Findbuch Regenerierung wurde erfolgreich beendet.');
                },
                error: function() {
                    $('#status2').text('Fehler beim Regenerieren des Findbuches.');
                }
            });
        }

        function displayData1(lines) {
            var $output1 = $('#output1');
            $output1.html('<h2>Namens-Findbuch:</h2><ul></ul>'); // Liste initialisieren
            var $list = $output1.find('ul'); // Zugriff auf die Liste

            $.each(lines, function(index, line) {
                line = $.trim(line); // Leerzeichen am Anfang und Ende entfernen
                if (line !== '') { // Leere Zeilen ignorieren
                    $list.append('<li>' + line + '</li>'); // Element zur Liste hinzufügen
                }
            });
        }

        function displayData2(lines) {
            var $output2 = $('#output2');
            $output2.html('<h2>Findbuch:</h2><ul></ul>'); // Liste initialisieren
            var $list = $output2.find('ul'); // Zugriff auf die Liste

            $.each(lines, function(index, line) {
                line = $.trim(line); // Leerzeichen am Anfang und Ende entfernen
                if (line !== '') { // Leere Zeilen ignorieren
                    $list.append('<li>' + line + '</li>'); // Element zur Liste hinzufügen
                }
            });
        }

        fetchData(); // Automatischer Start des Prozesses
    });
    </script>  
    <?php 

BA_HTML_trailer();

?>