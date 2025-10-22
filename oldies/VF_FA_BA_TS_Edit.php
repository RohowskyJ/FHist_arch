<?php

/**
 * Fahrzeug Daten im Typenschein, Formular
 * 
 * @author Josef Rohowsky - neu 2018
 */
session_start();

const Module_Name = 'F_G';
$module = Module_Name;
$tabelle = 'fz_fz_type';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');


initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================


// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================
if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
if (isset($_GET['ID'])) {
    $ft_id = $_GET['ID'];
} else {
    $ft_id = "";
}
if (isset($_POST['ft_id'])) {
    $fo_id = $_POST['ft_id'];
}

if ($phase == 99) {
    header('Location: VF_FA_FZ_Edit.php?fz_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION['VF_Prim']['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_ty = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($ft_id == 0) {
        $neu['ft_id'] = $ft_id;
        $neu['fz_t_id'] = $fz_id;
        $neu['fz_eignr'] = $_SESSION[$module]['eignr'];
        $neu['fz_herstell_fg'] = $neu['fz_fgtyp'] = $neu['fz_idnummer'] = $neu['fz_fgnr'] = $neu['fz_baujahr'] = $neu['fz_eig_gew'] = "";
        $neu['fz_zul_g_gew'] = $neu['fz_achsl_1'] = $neu['fz_achsl_2'] = $neu['fz_achsl_3'] = $neu['fz_achsl_4'] = $neu['fz_radstand'] = "";
        $neu['fz_spurweite'] = $neu['fz_antrachsen'] = $neu['fz_lenkachsen'] = $neu['fz_lenkhilfe'] = $neu['fz_allrad'] = $neu['fz_bremsanl'] = "";
        $neu['fz_hilfbremsanl'] = $neu['fz_feststellbr'] = $neu['fz_verzoegerg'] = $neu['fz_m_bauform'] = $neu['fz_herst_mot'] = $neu['fz_motornr'] = $neu['fz_hubraum'] = $neu['fz_bohrung'] = $neu['fz_hub'] = $neu['fz_kraftst'] = $neu['fz_gemischaufb'] = $neu['fz_kuehlg'] = "";
        $neu['fz_leistung_kw'] = $neu['fz_leistung_ps'] = $neu['fz_leist_drehz'] = $neu['fz_verbrauch'] = $neu['fz_antrieb'] = $neu['fz_bereifung_1'] = "";
        $neu['fz_bereifung_2'] = $neu['fz_bereifung_3'] = $neu['fz_bereifung_4'] = $neu['fz_getriebe'] = $neu['fz_herst_aufb'] = $neu['fz_aufb_bauart'] = $neu['fz_aufbau'] = $neu['fz_anh_kuppl'] = "";
        $neu['fz_geschwind'] = $neu['fz_sitzpl_zul'] = $neu['fz_sitzpl_1'] = $neu['fz_sitzpl_2'] = $neu['fz_abmessg_mm'] = $neu['fz_heizung'] = "";
        $neu['fz_farbe'] = $neu['fz_'] = $neu['fz_'] = $neu['fz_'] = $neu['fz_'] = $neu['fz_'] = "";
        $neu['fz_aenduid'] = $neu['fz_aenddat'] = "";
    } else {
        $sql = "SELECT * FROM $table_ty ";

        if ($ft_id != '') {
            $sql .= " WHERE ft_id = '$ft_id'";
        }

        $result = SQL_QUERY($db, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der ft_id Nummer $ft_id gefunden</p>";
                }
            }

            HTML_trailer();
            exit();
        }
        $neu = mysqli_fetch_array($result);
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$neu: ';
            print_r($neu);
            echo '</pre>';
        }
    }
}

if ($phase == 1) {

    foreach ($_POST as $name => $value) {
        $neu[$name] = mysqli_real_escape_string($db, $value);
    }

    $neu['fz_t_id'] = $_SESSION[$module]['fz_id'];

    $ft_id = $neu['ft_id'];

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 159: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($ft_id == 0) { # neueingabe
        $neu['fz_eignr'] = $_SESSION[$module]['eignr'];
        $neu['fz_aenduid'] = $_SESSION[$module]['p_uid'];
        $sql = "INSERT INTO $table_ty (
              fz_t_id,fz_eignr,fz_herstell_fg,fz_fgtyp,fz_idnummer,
              fz_fgnr,fz_baujahr,fz_eig_gew,fz_zul_g_gew,fz_achsl_1,fz_achsl_2,
              fz_achsl_3,fz_achsl_4,fz_radstand,fz_spurweite,fz_lenkachsen,fz_antrachsen ,
              fz_lenkhilfe,fz_allrad,fz_bremsanl,fz_hilfbremsanl,fz_feststellbr,fz_verzoegerg,
              fz_m_bauform ,fz_herst_mot,fz_motornr,fz_hubraum,fz_bohrung,fz_hub,
              fz_kraftst,fz_gemischaufb,fz_kuehlg,fz_leistung_kw,fz_leistung_ps,fz_leist_drehz,
              fz_verbrauch,fz_antrieb,fz_bereifung_1,fz_bereifung_2,fz_bereifung_3,fz_bereifung_4,
              fz_getriebe,fz_herst_aufb,fz_anh_kuppl,fz_geschwind,fz_sitzpl_zul,fz_sitzpl_1,
              fz_sitzpl_2,fz_abmessg_mm,fz_heizung,fz_farbe,fz_aenduid
           ) VALUE (
              '$neu[fz_t_id]','$neu[fz_eignr]','$neu[fz_herstell_fg]','$neu[fz_fgtyp]','$neu[fz_idnummer]',
              '$neu[fz_fgnr]','$neu[fz_baujahr]','$neu[fz_eig_gew]','$neu[fz_zul_g_gew]','$neu[fz_achsl_1]','$neu[fz_achsl_2]',
              '$neu[fz_achsl_3]','$neu[fz_achsl_4]','$neu[fz_radstand]','$neu[fz_spurweite]','$neu[fz_antrachsen]','$neu[fz_lenkachsen]',
              '$neu[fz_lenkhilfe]','$neu[fz_allrad]','$neu[fz_bremsanl]','$neu[fz_hilfbremsanl]','$neu[fz_feststellbr]','$neu[fz_verzoegerg]',
              '$neu[fz_m_bauform]','$neu[fz_herst_mot]','$neu[fz_motornr]','$neu[fz_hubraum]','$neu[fz_bohrung]','$neu[fz_hub]',
              '$neu[fz_kraftst]','$neu[fz_gemischaufb]','$neu[fz_kuehlg]','$neu[fz_leistung_kw]','$neu[fz_leistung_ps]','$neu[fz_leist_drehz]',
              '$neu[fz_verbrauch]','$neu[fz_antrieb]','$neu[fz_bereifung_1]','$neu[fz_bereifung_2]','$neu[fz_bereifung_3]','$neu[fz_bereifung_4]',
              '$neu[fz_getriebe]','$neu[fz_herst_aufb]','$neu[fz_anh_kuppl]','$neu[fz_geschwind]','$neu[fz_sitzpl_zul]','$neu[fz_sitzpl_1]',
              '$neu[fz_sitzpl_2]','$neu[fz_abmessg_mm]','$neu[fz_heizung]','$neu[fz_farbe]','$neu[fz_aenduid]'
               )";
        $result = SQL_QUERY($db, $sql);
    }
    $fz_id = $_SESSION[$module]['fz_id'];
    header("Location: VF_FA_FZ_Edit.php?ID=$fz_id");
} else { # update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "fo_ff_abzeich1") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    if ($_SESSION[$module]['all_upd']) {
        $sql = "UPDATE $table_ty SET  $updas WHERE `ft_id`='$ft_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = SQL_QUERY($db, $sql);
    }
    $fz_id = $_SESSION[$module]['fz_id'];
    #header("Location: VF_FA_FZ_Edit.php?ID=$fz_id"); 
}

BA_HTML_header('Typenschein Datenpflege', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BA_TS_Edit_ph0.inc.php');
        break;
}
BA_HTML_trailer();
?>