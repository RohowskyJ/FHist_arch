<?php

/**
 * Liste der Veranstaltungstermine, Wartung
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'OEF';
$module = Module_Name;

const Tabellen_Name = 'va_daten';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = True;
$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['va_id'])) {
    $_SESSION[$module]['va_id'] = $va_id = $_GET['va_id'];
} else {
    $va_id = 0;
}
if (isset($_POST['va_id'])) {
    $va_id = $_POST['va_id'];
}

# ===========================================================================================================
# die HTML Seite ausgeben
# ===========================================================================================================
$Err_Msg = "";

if ($phase == 99) {
    header("Localtion: VF_O_TE_List.php");
}

# ------------------------------------------------------------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------

Tabellen_Spalten_parms($db, Tabellen_Name);
if (is_file($path2ROOT . 'login/common/config_s.ini')) {
    $ini_arr = parse_ini_file($path2ROOT . 'login/common/config_s.ini', True, INI_SCANNER_NORMAL);
}

if ($phase == 0) {
    if ($va_id == "0") {
        $neu = array(
            "va_id" => 0,
            "va_datum" => "",
            "va_begzt" => "",
            'va_dauer' => '',
            'va_end_dat' => '',
            "va_endzt" => "",
            "va_titel" => "",
            "va_beschr" => "",
            "va_kateg" => "",
            "va_anm_erf" => "N",
            "va_inst" => "",
            "va_adresse" => "",
            "va_plz" => "",
            "va_ort" => "",
            "va_staat" => "AT",
            "va_bdld" => "NOE",
            "va_beitrag_m" => "0",
            "va_beitrag_g" => "0",
            "va_admin_email" => $ini_arr['Config']['vema'],
            "va_kontakt" => "",
            "va_umfang" => "",
            "va_link_einladung" => "",
            'va_bild' => '',
            'va_prosp_1' => '',
            'va_prosp_2' => '',
            'va_internet' => '',
            'va_anm_text' => '',
            'va_anmeld_end' => '',
            "va_raum" => "",
            "va_plaetze" => "0",
            "va_warte" => "0",
            "va_akt_pl" => "0",
            "va_wl_pl" => "0",
            "va_anz_anmeld" => "0",
            "va_angelegt" => "",
            "va_ang_uid" => "",
            "va_aenderung" => "",
            "va_aend_uid" => "",
            "va_freigabe" => "",
            "va_frei_uid" => "",
            "va_abschluss" => "",
            "va_ab_uid" => "",
            'va_storno' => '',
            'va_storn_uid' => ''
        );
    } else {
        $sql = "SELECT * FROM va_daten WHERE va_id = '$va_id' ";

        # echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) { # wenn die anzahl der gefundenen Rows nicht = 1 ist -->> Fehler
            if ($num_rows == 0) {
                if ($va_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Veranstaltung mit der va_id Nummer $va_id gefunden</p>";
                }
                goto ende;
                exit();
            }
        }
        $neu = mysqli_fetch_array($result);
    }

    if ($debug) {
        echo '<pre class=debug>';
        echo '<hr>\$neu: ';
        print_r($neu);
        echo '</pre>';
    }
}

// ============================================================================================================
if ($phase == 1) // prüfe die Werte in array $neu <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                  // ============================================================================================================
{
    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }
}

# ====================================================================================================
# Anzeigen
# ====================================================================================================

$header = "
     <script src='" . $path2ROOT . "login/common/javascript/tinymce/tinymce.min.js' referrerpolicy='origin'></script>
         
    <script>
      tinymce.init({
        selector: 'textarea#va_beschr',
        menubar: 'edit format'
         });
    </script>
";

BA_HTML_header('Veranstaltungs Definition', $header, 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_O_TE_Edit_ph0.inc.php');
        break;
    case 1:
        require ('VF_O_TE_Edit_ph1.inc.php');
        break;

        echo "<a href='VF_O_TE_List.php?Act=" . $SESSON[$module]['Act'] . "'>Zurück zur Liste</a>";
        break;

    case 99:
        echo "<a href='VF_O_TE_List.php?Act=" . $SESSON[$module]['Act'] . ">Zurück zur Liste</a>";
        break;
}
ende:
BA_HTML_trailer();
?>
