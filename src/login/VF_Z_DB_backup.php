<?php

/**
 * Backup der Datenbank- Tabellen nach Gruppe
 * 
 * @author Josef Rohowsky -  neu 20251205
 * 
 * 
 */
session_start();

$module = 'ADM';
$sub_mod = 'BU';

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
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_DB_backup.php";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
$flow_list = False;

$jq = true;
$BA_SQLImp = true;
$header = "<link rel='stylesheet' href='" . $path2ROOT . "login/common/css/BA_chkbx_multi_sel.css' type='text/css'>
           <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/BA_SQL_Import.css' type='text/css'>
              "; // css für multi chkbox select und SQL Upload

BA_HTML_header('DB BackUp', $header , 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================
$LinkDB_database = "";
$db = LinkDB('VFH'); // Connect zur Datenbank

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header('Location: VF_C_Menu.php');
}

if ($phase == 1) {
    #var_dump($_POST);
    
    if (isset($_POST['db_gruppe']) && $_POST['db_gruppe']) {
        echo "<h3>Erfolgreich erstellte Tabellen- Sicherungen: </h3>";
        foreach ($_POST['db_gruppe'] as $gruppe) {
            $bu_file = table_bu($gruppe);
            echo "$bu_file <br>";
        }
    } else {
        echo "Keine Auswahl getroffen. Neue Eingabe.<br>";
        $phase = 0;
    }

}

# -------------------------------------------------------------------------------------------------------
# Abfrage der Auswahl zur Sicherung
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    /** 
     * Anzeige der Datensicherungen zum Download
     */
    ?>
    <div class='w3-row'>
        <h2>Sicherung der Datenbank- Tabellen</h2>
        <div class='w3-half'> <!-- Aufruf -->
        <fieldset>
        
        <h3>Auswahl durch ankreuzen der entsprechenden Checkbox</h3><br>

        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                  <label class="checkbox-row">
                  <input type="checkbox" class="group-ar" name="db_gruppe[]" value="ar_">
                     <span>Sichern der Archiv-Tabellen, Präfix: ar_* </span>
             </label>
             <!-- 
                       <strong>Archivalien</strong><br>
                           <span>Präfix: <code>ar_*</code></span>
                            -->
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="ar" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="ar" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
         
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-aw" name="db_gruppe[]" value="aw_">
                           <span>Sichern der Ärmelabzeichen-Tabellen, Präfix: aw_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="aw" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="aw" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                      <label class="checkbox-row">
                          <input type="checkbox" class="group-az" name="db_gruppe[]" value="az_">
                            <span>Sichern der Auszeichnungs-Tabellen Präfix: az_*</span>
                      </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="az" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="az" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
   
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-bs" name="db_gruppe[]" value="bs_">
                              <span>Sichern der Marktplatz-Tabellen, Präfix: bs_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="bs" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="bs" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
   
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                          <input type="checkbox" class="group-bu" name="db_gruppe[]" value="bu_">
                             <span>Sichern der Buchbesprechungs-Tabellen, Präfix: bu_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="bu" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="bu" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-dm" name="db_gruppe[]" value="dm_">
                             <span>Sichern der Medien-Tabellen, Präfix: dm_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="dm" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="dm" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                             <input type="checkbox" class="group-fh" name="db_gruppe[]" value="fh_">
                                 <span>Sichern der Verwaltungs-Tabellen: Präfix: fh_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="fh" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="fh" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                              <input type="checkbox" class="group-in" name="db_gruppe[]" value="in_">
                                 <span>Sichern der Inventar-Tabellen, Präfix: in_* </span>
                        </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="inz" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="in" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                          <input type="checkbox" class="group-ma" name="db_gruppe[]" value="ma_">
                              <span>Sichern der Motoris.- Fzg/Geräte- Tabellen (ma_*)</span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="ma" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="ma" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                             <input type="checkbox" class="group-mu" name="db_gruppe[]" value="mu_">
                                 <span>Sicheern der Muskelbew. Fzg/Ger -Tabellen, Präfix: mu_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="mu" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="mu" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                             <input type="checkbox" class="group-pr" name="db_gruppe[]" value="pr_">
                                <span>Sichern der Presseberichte-Tabellen, Präfix: pr_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="pr" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="pr" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-va" name="db_gruppe[]" value="va_">
                                 <span>Sichern der Veranstaltungs-Tabellen, Präfix: va_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="va" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="va" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                             <input type="checkbox" class="group-vt" name="db_gruppe[]" value="vt_">
                                  <span>Sichern der Teinehmer-Tabellen, Präfix: vt_* </span>
                       </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="vt" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="vt" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
        </div>
        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-vb" name="db_gruppe[]" value="vb_">
                                <span>Sichern der Berichte-Tabellen, Präfix: az_ </span>
                       </label>
                  </div>
              
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="vb" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="vb" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>

        
        <div class="group-block">
             <div class="group-header">
                  <div class="group-header-title">
                       <label class="checkbox-row">
                           <input type="checkbox" class="group-zt" name="db_gruppe[]" value="zt_">
                              <span>Sichern der Zeitschriften-Tabellen, Präfix: zt_* </span>
                      </label>
                  </div>
                  <div class="group-actions">
                       <button type="button" class="btn-chip" data-master="zt" data-mode="all">
                            <span class="icon">✔</span> Gruppe
                       </button>
                       <button type="button" class="btn-chip" data-master="zt" data-mode="none">
                             <span class="icon">✕</span> Leeren
                       </button>
                  </div>
             </div>
             
        </div>
   
        <!-- Globale Master-Checkbox -->
        <div class="master-row">
              <label>
              <input type="checkbox" id="check_all">
                   <span>Alle Gruppen markieren</span>
              </label>
              <span class="hint">De-/aktiviert alle oben aufgeführten Checkboxen.</span>
        </div>
        <p><button type='submit' name='phase' value='1' class='green'>Sicherung durchführen</button></p>
        </fieldset>
        </div>  <!-- Ende Aufruf -->
        
        <!-- 
            Anzeige der vorhandenen Sicherungen, darunter Tabellen Importieren
         -->
        <div class='w3-half w3-container'> <!-- Anzeige -->
        <fieldset>
        <h3>Sicherungsdateien, herunteraden durch anklicken</h3>
        <?php 
        $dir = "Downloads/b";
        $files = scandir($dir);
        # var_dump($files);
        foreach ($files as $fname )  {
            if ($fname == "." || $fname == "..") {continue;}
            echo "<a href='$dir/$fname' > $fname </a><br>"; 
        }
        ?>
        <hr width='70%'>
        <!-- Daten Importieren -->
        <h2>SQL Import Upload</h2>

        <form id="importForm" onsubmit="return false;">
          
             <label for="fileInput">SQL-Datei auswählen:</label>
             <input type="file" id="fileInput" name="sql" accept=".sql" required />

             <button id="startBtn" type="button">Import starten</button>
        </form>

         <div id="progressContainer" aria-label="Fortschrittsbalken">
         <div id="progressBar">0%</div>
             <span id="progressSpinner">⏳</span>
         </div>

         <div id="statusMessage" class="info" role="status" aria-live="polite"></div>

         <div>
            <strong>Ausgeführte SQL-Statements:</strong> <span id="queryCountLabel">0</span>
         </div>
   
        <div id="logOutput" aria-live="polite" aria-atomic="false"></div>
        
        </filddset>
        </div>  <!-- Ende Anzeige -->
    </div>

    <?php 
}

BA_HTML_trailer();
?>