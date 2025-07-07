<?php

/**
 * Neu generieren der Suchbegriffe
 * 
 * @author Josef Rohowsky - neu 2018
 * 
 * 
 */
session_start();


const Module_Name = 'ADM';
$module = Module_Name;
$tabelle = '';

const Prefix = '';

/**
 * Angleichung an den Root-Path 
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

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

$prot = True;
BA_HTML_Header('Regeneration Findbücher','', 'Form', '75em');

?>

    <h1>Findbücher- Regenerierung</h1>  
    <!--  <button id="fetchButton">Daten abrufen</button> -->

    <div id="status1" style="margin-top: 10px; font-weight: bold;"></div>
    <div id="status2" style="margin-top: 10px; font-weight: bold;"></div>

    <div id="output1"></div>
    <div id="output2"></div>


 <script>
        function fetchData() {
            console.log('Started');
            
            // Erster Ajax.Request für den ersten Prozess
            new Ajax.Request('common/API/VF_Z_Suchb_name_API.php', {
                method: 'get',
                onLoading: function() {
                    $('status1').update('Namens- Findbuch regenerierung wurde gestartet ...');
                },
                onSuccess: function(response) {
                    var lines1 = response.responseText.split('\n'); // Zeilen aufteilen
                    displayData1(lines1); // Daten in der Liste anzeigen
                    $('status1').update('Namens- Findbuch regenerierung wurde erfolgreich beendet.');
                },
                onFailure: function() {
                    $('status1').update('Fehler beim Regenerieren des Namens- Findbuches.');
                }
            });

            // Zweiter Ajax.Request für den zweiten Prozess
            new Ajax.Request('common/API/VF_Z_Suchb_findb_API.php', {
                method: 'get',
                onLoading: function() {
                    $('status2').update('Findbuch Regenerierung wurde gestartet ...');
                },
                onSuccess: function(response) {
                    var lines2 = response.responseText.split('\n'); // Zeilen aufteilen
                    displayData2(lines2); // Daten in der Liste anzeigen
                    $('status2').update('Findbuch Regenerierung wurde erfolgreich beendet.');
                },
                onFailure: function() {
                    $('status2').update('Fehler beim Regenerieren des Findbuches.');
                }
            });
        }

        function displayData1(lines) {
            var output1 = $('output1');
            output1.update('<h2>Namens- Findbuch:</h2><ul></ul>'); // Liste initialisieren
            var list = output1.down('ul'); // Zugriff auf die Liste

            lines.forEach(function(line) {
                line = line.trim(); // Leerzeichen am Anfang und Ende entfernen
                if (line !== '') { // Leere Zeilen ignorieren
                    var listItem = new Element('li'); // Neues Listenelement erstellen
                    listItem.update(line); // Textinhalt setzen
                    list.insert(listItem); // Element zur Liste hinzufügen
                }
            });
        }

        function displayData2(lines) {
            var output2 = $('output2');
            output2.update('<h2Findbuch:</h2><ul></ul>'); // Liste initialisieren
            var list = output2.down('ul'); // Zugriff auf die Liste

            lines.forEach(function(line) {
                line = line.trim(); // Leerzeichen am Anfang und Ende entfernen
                if (line !== '') { // Leere Zeilen ignorieren
                    var listItem = new Element('li'); // Neues Listenelement erstellen
                    listItem.update(line); // Textinhalt setzen
                    list.insert(listItem); // Element zur Liste hinzufügen
                }
            });
        }

        document.observe("dom:loaded", function() {
            // $('fetchButton').observe('click', fetchData);
             fetchData(); // Automatischer Start des Prozesses
        });
    </script>  
    <?php 

BA_HTML_trailer();

?>