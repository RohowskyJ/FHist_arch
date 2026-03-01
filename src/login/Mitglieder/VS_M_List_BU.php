<!DOCTYPE html>
<?php
# session_start();
/**
 * Mitglieder Verwaltung Liste
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 * 
 */
session_start();

$_SESSION['VF_Prim'] = ['p_uid'=>'1','all_upd'=>'1'];

#var_dump($_SESSION);

$module = 'ADM-MI';
$sub_mod = "LIST";

$tabelle = 'fv_mitglieder';

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
/*
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VS_M_List.php"; 
*/
/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/FS_Database_CLS.php';
require $path2ROOT . 'login/common/BS_TabSpalt_CLS.php';
require $path2ROOT . 'login/common/BS_Funcs.lib.php';
#require $path2ROOT . 'login/common/BS_List_Funcs.lib.php';
# require $path2ROOT . 'login/common/BS_List_Tabulator.lib.php';
# require $path2ROOT . 'login/common/


#require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
# require $path2ROOT . 'login/common/BS_Funcs.lib.php';
#require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
#require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';

$rootPath = $_SERVER['DOCUMENT_ROOT'];
$header = " <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/tabulator/tabulator.min.css' type='text/css'>
            <link rel='stylesheet' href='" . $path2ROOT . "login/common/css/flatpickr/flatpickr.min.css' type='text/css'>
          ";
# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$ListHead = "Mitglieder- Verwaltung - Administrator ";
$title = "Mitglieder Daten";
$TABU = true;
HTML_header('Mitglieder- Verwaltung', $header, 'Admin', '200em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

# require_once  $path2ROOT . 'login/common/BS_CentralLog_CLS.php' ;
// Logger initialisieren (einmalig, z.B. in Bootstrap)
# $logger = CentralLogger::getInstance(__DIR__.'\login\logs');
# echo __LINE__ ." ". __DIR__."\login\logs <br>";
// var_dump($logger);
#$logger->registerErrorHandlers('default'); // Optional: Default-Modul für Fehler

$moduleId = $module."-".$sub_mod;
// Eigene Meldung mit Modulkennung loggen
# $logger->log('Starte Verarbeitung des Moduls', $moduleId, basename(__FILE__));

// XR_Database mit bestehender PDO-Instanz initialisieren
$DBD = new VF_Database();
# var_dump($DBD);
$pdo = $DBD->getPDO();
# var_dump($pdo);

$flow_list = False;
$_SESSION[$module]['Return'] = False;

#$LinkDB_database  = '';
#$db = LinkDB('VFH'); 
# var_dump($db);
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

# $NeuRec = "momentan ned"; #     "NeuItem" => "<a href='VF_M_Edit.php?ID=0' >Neues Mitglied eingeben</a>"
/*
# $Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle);
$showColumns = [];
switch ($T_List) {
    case "Alle":
    case "Mitgl":
    case "nMitgl":
        $showColumns = array(
            'mi_id',
            'mi_org_typ',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort',
            'mi_gebtag',
            'mi_tel_handy',
            'mi_fax',
            'mi_email',
            'mi_vorst_funct',
            'mi_ref_leit',
            'mi_ref_int_2',
            'mi_ref_int_3',
            'mi_ref_int_4',
            'mi_einv_art',
            'mi_einversterkl',
            'mi_einv_dat',
            'mi_changed_id',
            'mi_changed_at'
        );
        break;

    case "BezL":
        $showColumns = array(
            'mi_id',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort',
            'mi_m_beitr_bez',
            'mi_m_abo_bez',
            'mi_m_abo_ausg'
        );
        break;
    case "BezL_W":
        $showColumns = array(
            'mi_id',
            'mi_mtyp',
            'aktiv',
            'MB',
            'ABO',
            'ausg',
            'mi_name',
            'mi_vname',
            'mi_anschr',
            'mi_tel_handy',
            'mi_email',
            'mi_gebtag',
            'mi_beitritt',
            'mi_austrdat',
            'mi_sterbdat'
        );
        $altTitle['mi_id'] = 'MITGL- NR';
        $altTitle['aktiv'] = 'Aktiv';
        $altTitle['MB'] = 'MB';
        $altTitle['ABO'] = 'ABO';
        $altTitle['Ausg'] = 'Ausg';
        /*
        $Tabellen_Spalten_typ['aktiv'] = 'text';
        $Tabellen_Spalten_typ['MB'] = 'text';
        $Tabellen_Spalten_typ['ABO'] = 'text';
        $Tabellen_Spalten_typ['Ausg'] = 'text';
        $Tabellen_Spalten_MAXLENGTH['aktiv'] = '5';
        $Tabellen_Spalten_MAXLENGTH['MB'] = '5';
        $Tabellen_Spalten_MAXLENGTH['ABO'] = '5';
        $Tabellen_Spalten_MAXLENGTH['Ausg'] = '5';
        * /
        break;
    default:
        $showColumns = array(
            'mi_id',
            'mi_org_typ',
            'mi_org_name',
            'mi_anrede',
            'mi_titel',
            'mi_name' . 'mi_vname',
            'mi_anschr',
            'mi_plz',
            'mi_ort'
        );
}

#$Tabellen_Spalten_style['mi_id'] = $Tabellen_Spalten_style['va_datum'] = $Tabellen_Spalten_style['va_begzt'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>';

switch ($T_List) {
    case "Mitgl":
    case "nMitgl":
    case "MitglA4":
    case "BezL":

    case "Name":
    case "EMail":

    case "AdrList":
        break;

    default:
    /*
     * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
     * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
     * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
     * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
     * /
}
*/
/*
$List_Hinweise .= '</ul></li>';
$T_List = "";
$T_list_texte = [];
List_Action_Bar("fv_mitglieder","Mitglieder- Verwaltung - Administrator ", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben
*/
?>

<div id="control-bar">
    <select id="list-selector" title="Liste auswählen">
        <option value="Alle">Alle Mitglieder</option>
        <option value="Mitgl">Aktive Mitglieder</option>
        <option value="nMitgl">Nicht- Aktive Mitglieder</option>
        <option value="Adrlist">Adressliste</option>
    </select>

    <!-- 
    <input type="text" id="search-input" placeholder="Globale Suche..." title="Suche in der aktuellen Liste" style="flex-grow:1; min-width:200px; padding:5px;" />
     
     
    <button id="add-new-btn" title="Neuen Datensatz hinzufügen">Neu</button>

    <button id="mass-update-btn" title="Massenupdate für ausgewählte Zeilen">Massenupdate</button>
    -->

    <?php 
    if (isset($NeuRec) ) {
        echo "<span style='width: 50em;'>$NeuRec</span>";
    } else {
        echo "<span style='width: 50em;'></span>";
    }
    ?>
    
    <select id="settings-selector" title="Einstellungen">
        <option value="pagination_on">Pagination an</option>
        <option value="pagination_off">Pagination aus</option>
        <option value="debug_on">Debug an</option>
        <option value="debug_off">Debug aus</option>
        <option value="csv_export">CSV Export</option>
        <option value="print">Drucken</option>
    </select>
    
    
 <div class="hints" id="hints">
  <button class="hints__trigger"
          type="button"
          aria-haspopup="dialog"
          aria-expanded="false"
          aria-controls="hints-panel"
          title="Wähle hier die gewünschten Hinweise aus.">
    <span class="hints__label">Hinweise</span>
    <span class="hints__icon" aria-hidden="true">ℹ️</span>
  </button>

  <div class="hints__panel" id="hints-panel" role="dialog" aria-label="Hinweise zur Tabelle">
    <ul class="hints__list">
      <!-- PHP Block beibehalten -->

      <li class="hints__divider" aria-hidden="true"></li>

      <li class="tip">
        <div class="tip__title">Nach Spalteninhalt sortieren <small>(Tabulator.js)</small></div>
        <div class="tip__body">
          <p>So sortierst du die Tabelle nach dem Inhalt einer Spalte:</p>
          <ol>
            <li>Auf den <b>Spaltentitel</b> klicken.</li>
            <li>Erneut klicken, um zwischen <b>aufsteigend</b> und <b>absteigend</b> zu wechseln.</li>
          </ol>
          <p class="tip__note">
            Der Sortierstatus wird im Header durch ein Sortier-Icon/Pfeil dargestellt (je nach Tabulator-Theme/Config).
            Mehrfachsortierung ist möglich, wenn sie in Tabulator aktiviert ist (z. B. per <code>sortMode</code> bzw. Multi-Column Sorting).
          </p>
        </div>
      </li>

      <li class="tip">
        <div class="tip__title">Anzeige von Spalten unterdrücken <small>(Tabulator.js)</small></div>
        <div class="tip__body">
          <p>Spalten können über ein Spalten-Menü ein-/ausgeblendet werden:</p>
          <ol>
            <li>Das <b>Spaltenmenü</b> öffnen (je nach Umsetzung: Rechtsklick auf Header oder Menü-Icon im Header).</li>
            <li><b>Spalte ausblenden</b> auswählen.</li>
          </ol>
          <p class="tip__note">
            Zum Wiederanzeigen nutzt du das gleiche Menü oder eine separate UI (z. B. „Alle Spalten anzeigen“),
            die intern <code>column.show()</code> / <code>column.hide()</code> verwendet.
          </p>
        </div>
      </li>

      <li class="tip">
        <div class="tip__title">Pagination <small>(Tabulator.js)</small></div>
        <div class="tip__body">
          <p>Wenn Pagination aktiviert ist, wird die Tabelle in Seiten aufgeteilt:</p>
          <ul>
            <li>Zwischen Seiten über die <b>Paginierungs-Steuerung</b> wechseln (z. B. „Vor/Zurück“, Seitenzahlen).</li>
            <li>Optional kann die <b>Seitengröße</b> (Anzahl Zeilen pro Seite) über eine Auswahl geändert werden – abhängig von deiner Tabulator-Konfiguration.</li>
          </ul>
          <p class="tip__note">
            Hinweis: Das „Resize-Dreieck rechts unten“ gehört nicht zu Tabulator.js.
            Tabulator steuert Größe/Responsive typischerweise über Containerbreite, <code>layout</code> und responsive Column-Optionen.
          </p>
        </div>
      </li>
    </ul>
  </div>
</div>
</div>


<?php 
echo "<div id='member-table'></div>  ";    

HTML_trailer();

?>