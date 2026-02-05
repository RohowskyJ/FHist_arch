// Funktion zum Formatieren von Bytes in lesbare Einheiten (z.B. KB, MB)
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Funktion zum Setzen des Fortschrittsbalkens
function setProgress(percent) {
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
    }
}

// Funktion zum Aktivieren/Deaktivieren der Fortschrittsanzeige (Spinner)
function setProgressActive(active) {
    const spinner = document.getElementById('progressSpinner');
    if (spinner) {
        spinner.style.display = active ? 'inline-block' : 'none';
    }
}

// Funktion zum Setzen des Status-Textes mit optionaler CSS-Klasse
function setStatus(message, type = 'info') {
    const statusEl = document.getElementById('statusMessage');
    if (statusEl) {
        statusEl.innerHTML = message;
        statusEl.className = type; // z.B. 'error', 'ok', 'info'
    }
}

// Funktion zum Loggen von Nachrichten
function logLine(message, type = 'info') {
    const logEl = document.getElementById('logOutput');
    if (logEl) {
        const line = document.createElement('div');
        line.textContent = message;
        line.className = type; // z.B. 'err', 'ok', 'dim'
        logEl.appendChild(line);
        logEl.scrollTop = logEl.scrollHeight; // Scroll zum Ende
    }
}

// Funktion zum Upload und Import mit echtem Upload-Fortschritt via XMLHttpRequest
function uploadAndImportSQLWithProgress(file) {
    const startBtn = document.getElementById('startBtn');
    if (startBtn) startBtn.disabled = true;

    const formData = new FormData();
	
    formData.append('sql', file);
console.log('formData ',FormData);
    setProgress(0);
    setProgressActive(true);
    setStatus('Import gestartet...', 'info');
    logLine('Starte Upload und Import...', 'info');

    const xhr = new XMLHttpRequest();

    // Upload-Fortschritt
    xhr.upload.addEventListener('progress', (event) => {
        if (event.lengthComputable) {
            const percent = Math.round((event.loaded / event.total) * 100);
            setProgress(percent);
        }
    });

    // Antwort vom Server
    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            setProgressActive(false);
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json.ok) {
                        setProgress(100);
                        setStatus(`Import erfolgreich abgeschlossen. <strong>${json.queries}</strong> SQL-Statements ausgeführt.`, 'ok');
                        logLine('Import erfolgreich.', 'ok');
                        logLine(`Dateigröße: ${formatBytes(json.size || file.size)}`, 'dim');
                        logLine(`Ausgeführte Statements: ${json.queries}`, 'dim');
                        const queryCountLabel = document.getElementById('queryCountLabel');
                        if (queryCountLabel) queryCountLabel.textContent = String(json.queries);
                    } else {
                        setProgress(100);
                        setStatus(`Fehler beim Import: <strong>${json.error || 'Unbekannter Fehler'}</strong>`, 'error');
                        logLine('FEHLER: ' + (json.error || 'Unbekannter Fehler'), 'err');
                        if (typeof json.queries !== 'undefined') {
                            const queryCountLabel = document.getElementById('queryCountLabel');
                            if (queryCountLabel) queryCountLabel.textContent = String(json.queries);
                        }
                    }
                } catch (e) {
                    setStatus('Fehler beim Verarbeiten der Serverantwort.', 'error');
                    logLine('FEHLER: JSON Parsing fehlgeschlagen', 'err');
                }
            } else {
                setStatus(`HTTP-Fehler ${xhr.status} - ${xhr.statusText}`, 'error');
                logLine(`FEHLER: HTTP-Fehler ${xhr.status}`, 'err');
            }
            if (startBtn) startBtn.disabled = false;
        }
    };

    // Fehler beim Netzwerk
    xhr.onerror = () => {
        setProgressActive(false);
        setStatus('Netzwerkfehler beim Upload.', 'error');
        logLine('FEHLER: Netzwerkfehler beim Upload', 'err');
        if (startBtn) startBtn.disabled = false;
    };

    xhr.open('POST', 'common/API/BA_SQL_Import.API.php');
    console.log('formData ',formData);
    xhr.send(formData);
}

// Event-Listener für den Start-Button
document.getElementById('startBtn').addEventListener('click', () => {
	/*
    const host = document.getElementById('hostInput').value.trim();
    const dbname = document.getElementById('dbnameInput').value.trim();
    const user = document.getElementById('userInput').value.trim();
    const pass = document.getElementById('passInput').value;
	*/
    const fileInput = document.getElementById('fileInput');

    if (!fileInput.files.length) {
        alert('Bitte wählen Sie eine SQL-Datei aus.');
        return;
    }
    const file = fileInput.files[0];
console.log('file sel ', file);
    if (!file.name.endsWith('.sql')) {
        alert('Bitte wählen Sie eine gültige SQL-Datei (*.sql) aus.');
        return;
    }

    uploadAndImportSQLWithProgress(file);
});