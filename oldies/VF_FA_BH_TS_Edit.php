<?php
/**
 * Fahrzeug Daten im Typenschein, Anhänger, Formular
 *
 * @author Josef Rohowsky - neu 2018 
 */
session_start();

# die SESSION am leben halten
const Module_Name = 'F_M';
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

require $path2ROOT . 'login/common/VF_Funcs.inc.php';
require $path2ROOT . 'login/common/VF_Edit_Funcs.inc.php';
require $path2ROOT . 'login/common/VF_Tabellen_Spalten.inc.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.inc';
require $path2ROOT . 'login/common/VF_Const.inc.php';

VF_initial_debug();

// ============================================================================================================
// Eingabenerfassung und defauls
// ============================================================================================================

$VF_LinkDB_database = '';
$db = VF_LinkDB('Mem');

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
    header('Location: VF_2_FG_Edit_v3.php?fz_id=' . $_SESSION[$module]['fz_id']);
}
$Edit_Funcs_FeldName = False; // Feldname der Tabelle wird nicht angezeigt !!

# --------------------------------------------------------
# Lesen der Daten aus der sql Tabelle
# ------------------------------------------------------------------------------------------------------------
$fz_id = $_SESSION[$module]['fz_id'];

$p_uid = $_SESSION[$module]['p_uid'];

Tabellen_Spalten_parms($db, $tabelle);
$table_ty = $tabelle . "_" . $_SESSION['Eigner']['eig_eigner'];

# -------------------------------------------------------------------------------------------------------
# Überschreibe die Werte in array $neu - weitere Modifikationen in Edit_tn_check_v2.php !
# -------------------------------------------------------------------------------------------------------
if ($phase == 0) {

    if ($ft_id == "NeuItem") {
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

        # $war = $neu;z_fgnr
    } else {
        $sql = "SELECT * FROM $table_ty ";

        if ($ft_id != '') {
            $sql .= " WHERE ft_id = '$ft_id'";
        }

        $result = mysqli_query($db, $sql) or die('Lesen Satz $ft_id nicht möglich: ' . mysqli_error($db));
        $num_rows = mysqli_num_rows($result);
        if ($num_rows !== 1) {
            if ($num_rows == 0) {
                if ($fo_id != '') {
                    echo "<p style='color:red;font-size:150%;font-weight:bold;' >Keine Datensatz mit der ft_id Nummer $ft_id gefunden</p>";
                }
            }

            VF_HTML_trailer();
            exit();
        }
        $war = mysqli_fetch_array($result);
        $neu = $war; # default neu = alt
        if ($debug) {
            echo '<pre class=debug>';
            echo '<hr>$war: ';
            print_r($war);
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

    $uploaddir = "referat4/" . $_SESSION['Eigner']['eig_eigner'] . "/";

    $target1 = "";
    if (! empty($_FILES['uploaddatei_1'])) {
        $pict1 = basename($_FILES['uploaddatei_1']['name']);
        if (! empty($pict1)) {
            $target1 = $uploaddir . basename($_FILES['uploaddatei_1']['name']);
            if (move_uploaded_file($_FILES['uploaddatei_1']['tmp_name'], $target1))
                echo "Datei/Bild 1 geladen!<br><br><br>";
        } else {
            $target1 = "";
        }
    }

    if ($target1 != "") {
        $fn = explode("/", $target1);
        $neu['fo_ff_abzeich'] = $fn[3];
    } else {}

    if ($debug) {
        echo '<pre class=debug>';
        echo 'L 129: <hr>$neu: ';
        print_r($neu);
        echo '</pre>';
    }

    if ($fo_id == 0) { # neueingabe
        $neu['fz_eignr'] = $_SESSION[$module]['eignr'];
        $neu['fz_aenduid'] = $_SESSION[$module]['p_uid'];
        $sql = "INSERT INTO $table_ty (
              fz_t_id,fz_eignr,fz_herstell_fg,fz_fgtyp,fz_idnummer,
              fz_fgnr,fz_baujahr,fz_eig_gew,fz_zul_g_gew,fz_achsl_1,fz_achsl_2,
              fz_achsl_3,fz_radstand,fz_spurweite,fz_lenkachsen,
              fz_bremsanl,fz_feststellbr,fz_verzoegerg,
              fz_bereifung_1,fz_bereifung_2,fz_bereifung_3,
              fz_herst_aufb,fz_anh_kuppl,fz_geschwind,
              fz_abmessg_mm,fz_heizung,fz_farbe,fz_aenduid
           ) VALUE (
              '$neu[fz_t_id]','$neu[fz_eignr]','$neu[fz_herstell_fg]','$neu[fz_fgtyp]','$neu[fz_idnummer]',
              '$neu[fz_fgnr]','$neu[fz_baujahr]','$neu[fz_eig_gew]','$neu[fz_zul_g_gew]','$neu[fz_achsl_1]','$neu[fz_achsl_2]',
              '$neu[fz_achsl_3]','$neu[fz_radstand]','$neu[fz_spurweite]','$neu[fz_lenkachsen]',
              '$neu[fz_bremsanl]','$neu[fz_feststellbr]','$neu[fz_verzoegerg]',
              '$neu[fz_bereifung_1]','$neu[fz_bereifung_2]','$neu[fz_bereifung_3]',
              '$neu[fz_herst_aufb]','$neu[fz_anh_kuppl]','$neu[fz_geschwind]',
              '$neu[fz_abmessg_mm]','$neu[fz_heizung]','$neu[fz_farbe]','$neu[fz_aenduid]'
               )";

        $result = mysqli_query($db, $sql) or die('INSERT nicht möglich: ' . mysqli_error($db));
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

        $sql = "UPDATE $table_ty SET  $updas WHERE `ft_id`='$ft_id'";
        if ($debug) {
            echo '<pre class=debug> L 0127: \$sql $sql </pre>';
        }

        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = mysqli_query($db, $sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
    }
    $fz_id = $_SESSION[$module]['fz_id'];
    header("Location: VF_FA_FG_Edit.php?ID=$fz_id");
}

VF_HTML_header('Typenschein Datenpflege', '', 'Form', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

switch ($phase) {
    case 0:
        require ('VF_FA_BH_TS_Edit_ph0.inc.php');
        break;
}
VF_HTML_trailer();
?>