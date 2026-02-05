<?php
/*
Bitte um eine Lösung, wie ich mysql Tabellen (nicht die ganze Datebank, nur einzelne Tabellen oder gruppen) mit php sichern kann
Hier eine ausführliche Lösungsidee, wie Sie mit PHP gezielt einzelne MySQL-Tabellen oder Gruppen von Tabellen sichern können, ohne die gesamte Datenbank zu exportieren.

Lösung: MySQL-Tabellen mit PHP sichern (Export einzelner Tabellen)
1. Grundidee
Mit PHP können Sie eine Verbindung zur MySQL-Datenbank herstellen und dann für jede gewünschte Tabelle:

Die Struktur (CREATE TABLE) exportieren
Die Daten (INSERT INTO) exportieren
Diese Informationen können Sie in eine SQL-Datei schreiben, die später zum Import genutzt werden kann.

2. Beispielskript für den Export einzelner Tabellen mit PHP
Das folgende Beispiel zeigt, wie Sie mit PHP einzelne Tabellen aus einer MySQL-Datenbank exportieren können:

<?php
*/
// Datenbankverbindung
$host = 'localhost';
$user = 'root';
$password = 'b1teller';
$database = 'fharch';

 

// Tabellen, die exportiert werden sollen
#$tables = ['ar_chivdat_1', 'ar_chivdat_124']; // Hier gewünschte Tabellen eintragen

// Verbindung herstellen
$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_error) {
    die('Verbindungsfehler (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$ret_d = $mysqli->query("SHOW DATABASES ");
while ($row_d = $ret_d->fetch_object()) {
    var_dump($row_d);
}

$prefix = "ma_";
$ret_1 = $mysqli->query("SHOW TABLES LIKE '$prefix%'");
$tables = []; // Array initialisieren

while ($row_1 = $ret_1->fetch_object()) {
    // var_dump($row_1); // Zum Debuggen, um den Eigenschaftsnamen zu sehen
    // Angenommen, die Eigenschaft heißt z.B. 'Tables_in_deinedatenbank (ar_%)'
    // Du kannst dynamisch den ersten Wert aus dem Objekt holen:
    # $tableName = reset(get_object_vars($row_1));
    $vars = get_object_vars($row_1);
    $tablename = reset($vars);
    $tables[] = $tablename;
}

var_dump($tables);

$backupSql = "-- MySQL Tabellen Backup\n-- Datenbank: $database\n\n";

foreach ($tables as $table) {
    // Struktur exportieren
    $result = $mysqli->query("SHOW CREATE TABLE `$table`");
    $row = $result->fetch_assoc();
    $backupSql .= "-- Struktur für Tabelle `$table`\n";
    $backupSql .= $row['Create Table'] . ";\n\n";
    
    // Daten exportieren
    $result = $mysqli->query("SELECT * FROM `$table`");
    $numFields = $result->field_count;
    
    $backupSql .= "-- Daten für Tabelle `$table`\n";
    
    while ($row = $result->fetch_assoc()) {
        $vals = array_map(function($val) use ($mysqli) {
            if ($val === null) return "NULL";
            return "'" . $mysqli->real_escape_string($val) . "'";
        }, array_values($row));
            $backupSql .= "INSERT INTO `$table` VALUES(" . implode(", ", $vals) . ");\n";
    }
    $backupSql .= "\n";
}

// Backup in Datei speichern
$fileName = $prefix.'backup_' . date('Ymd_His') . '.sql';
file_put_contents($fileName, $backupSql);

echo "Backup erfolgreich erstellt: $fileName\n";

$mysqli->close();

/*
3. Erläuterungen zum Skript
Verbindung: Es wird eine MySQLi-Verbindung zur Datenbank aufgebaut.
Tabellenliste: Sie definieren im Array $tables die Tabellen, die exportiert werden sollen.
Struktur: Mit SHOW CREATE TABLE wird die Tabellenstruktur abgefragt und in die Backup-Datei geschrieben.
Daten: Alle Daten der Tabelle werden mit SELECT * abgefragt und als INSERT INTO-Statements formatiert.
Datei: Das Backup wird in einer SQL-Datei mit Zeitstempel gespeichert.
Sicherheit: Werte werden mit real_escape_string gesichert, um SQL-Injection im Export zu vermeiden.
4. Erweiterungsmöglichkeiten
Gruppen von Tabellen: Sie können das $tables-Array dynamisch füllen, z.B. nach Namensmuster mit SHOW TABLES LIKE 'prefix_%'.
Kompression: Die Backup-Datei kann mit gzip komprimiert werden, um Speicherplatz zu sparen.
Automatisierung: Das Skript kann per Cronjob regelmäßig ausgeführt werden.
Fehlerbehandlung: Erweiterte Fehlerprüfungen und Logging können hinzugefügt werden.
5. Alternative: Nutzung von PHP-Bibliotheken
Es gibt auch fertige PHP-Bibliotheken und Tools (z.B. phpMyAdmin-Code, mysqldump-php), die erweiterte Funktionen für Backups bieten und auch einzelne Tabellen exportieren können.

Fazit
Mit dem obigen PHP-Skript können Sie gezielt einzelne Tabellen oder eine definierte Gruppe von Tabellen aus Ihrer MySQL-Datenbank sichern, ohne die gesamte Datenbank zu exportieren. Das Skript ist einfach anpassbar und kann in bestehende Backup-Prozesse integriert werden.

Wenn Sie möchten, kann ich Ihnen auch eine erweiterte Version mit zusätzlichen Features erstellen oder bei der Integration helfen.

Falls Sie weitere Fragen haben oder eine spezifische Anpassung wünschen, stehe ich gerne zur Verfügung!
*/