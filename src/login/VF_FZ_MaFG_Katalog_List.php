    <?php

    /**
     * Anzeige aller Fahrzeuge/Geräte einer auszuwählenden Sammlung
     *
     * @author Josef Rohowsky - neu 2019
     *
     * change Avtivity:
     *   2019 J. Rohowsky nach B.R.Gaicki - neu
     *   2024-04-07 J. Rohowsky - Liste für alle Geräte und Fahrzeuge gemeinsam
     */
    session_start();

const Module_Name = 'F_G';
$module = Module_Name;
if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = false; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = true;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige" => "Ein",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

$ErrMsg = "";

if (! isset($_SESSION[$module]['sammlung'])) {
    $_SESSION[$module]['sammlung'] = 'MU';
}
if (! isset($_SESSION[$module]['all_upd'])) {
    $_SESSION[$module]['all_upd'] = false;
}

VF_chk_valid();

VF_set_module_p();

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}

if ($phase == 99) {
    header("Location: /login/VF_C_Menu.php");
}

/**
 * Sammlung auswählen, Input- Analyse
 */
if (isset($_POST['level1'])) {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {
    } else {
        $sammlg = $_SESSION[$module]['sammlung'] = $response;
    }
}

# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte = array(
    "Alle" => "Alle bekannten Maschinengetriebenen Fzg/Geräte nach Indienststellung (Auswahl)"
);

# ===========================================================================================================
# Haeder ausgeben
# ===========================================================================================================
$title = "Maschinengetriebene Fahrzeuge oder Geräte";

$header = "";

$prot = true;
BA_HTML_header('Katalog Maschinengetriebene Fahrzeuge und Geräte ', '', 'List', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

List_Prolog($module, $T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Fahrzeug - Details ansehen: Auf die Zahl in Spalte <q>fz_id</q> Klicken.</li>';
switch ($T_List) {
    case "Alle":

        break;

    default:
        /*
         * $List_Hinweise .= '<li>Anmelde Daten ändern: Auf die Zahl in Spalte <q>mi_id</q> Klicken.</li>'
         * . '<li>E-Mail an Mitglied senden: Auf die E-Mail-Adresse in Spalte <q>EMail</q> Klicken.</li>'
         * . '<li>Home Page des Mitglieds ansehen: Auf den Link in Spalte <q>Home_Page</q> Klicken.</li>'
         * . '<li>Forum Teilnehmer Daten ansehen: Auf die Zahl in Spalte <q>lco_email</q> Klicken.</li>';
         */
}
$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle, "Katalog- Liste motorbetrieben Fahrzeuge und Geräte", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
 *
 *
 * @var array $MS_Init Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
 * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
 * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
 *
 * @Input-Parm $_POST['Level1...6']
 */

$MS_Lvl = 1; # 1 ... 6
$MS_Opt = 1; # 1: SA für Sammlung, 2: AO für Archivordnung

$MS_Txt = array(
    'Auswahl der Sammlungs- Type (1. Ebene)  ',
    'Auswahl der Sammlungs- Gruppe (2. Ebene)  ',
    'Auswahl der Untergrupppe (3. Ebene) ',
    'Auswahl des Spezifikation (4. Ebene)  '
);

switch ($MS_Opt) {
    case 1:
        $in_val = '';
        $MS_Init = VF_Sel_SA_MA; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
        /*
         * case 2:
         * $in_val = '07';
         * $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
         * break;
         */
}

$titel = 'Suche nach der Sammlungs- Beschreibung (- oder Änderung der  angezeigten)';
VF_Multi_Dropdown($in_val, $titel);

$tabelle_g = $tabelle_m = "";

/**
 * Einlesen der tajtischen Bezeichnungen in taktb_arr
 */
$taktb_arr = array();
$sql_t = "SELECT * FROM fh_abk ORDER BY ab_abk  ASC ";
$res_t = SQL_QUERY($db, $sql_t);
while ($row_t = mysqli_fetch_object($res_t)) {
    $taktb_arr[$row_t->ab_abk] = $row_t->ab_bezeichn;
}
#var_dump($taktb_arr);
/**
 * Einlesen der Sammlungs- Kürzeln in arr
 */
$sam_arr = array();
$sql_s = "SELECT * FROM fh_sammlung ORDER BY sa_sammlg ";
$res_sa = SQL_QUERY($db, $sql_s);
while ($row_s = mysqli_fetch_object($res_sa)) {
    $sam_arr[$row_s->sa_sammlg] = $row_s->sa_name;
}
#var_dump($sam_arr);

if ($_SESSION[$module]['sammlung'] == "MA_F") {
    $tabelle_g = ""; # "ge_raet";
    $tabelle_m = "ma_fahrzeug";
    $sql_where =  ""; // "WHERE fz_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";

    /**
     * einlesen Hersteller und Aufbauer in arrs
     */
    $herst_arr = $aufb_arr = array();
    $sql_a = "SELECT * FROM fh_firmen ORDER BY fi_name ASC  ";
    $res_a = SQL_QUERY($db, $sql_a);
    while ($row_a = mysqli_fetch_object($res_a)) {
        if ($row_a->fi_funkt == 'F') {
            $herst_arr[$row_a->fi_abk] = $row_a->fi_name;
        } else {
            $aufb_arr[$row_a->fi_abk] = $row_a->fi_name;
        }
    }
    #var_dump($herst_arr);
    #var_dump($aufb_arr);

}

if ($_SESSION[$module]['sammlung'] == "MA_G") {
    $tabelle_g = "ma_geraet";
    $sql_where = "WHERE ge_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";
    $tabelle_m = "";
}
#echo "L 0198 $tabelle_g $tabelle_m <br>";
/**
 * Einlesen der vorhandenen ge_raete und fz_muskel Dateien
 *
 * benötigte Tabellen
 *
 * @global array $ge_arr
 * @global array $fm_arr
 */
$maf_arr = $mag_arr = array();
$muf_arr = $mug_arr = array();

$tables_act = VF_tableExist(); # verfügbare Mandanten- Tabellen
if (! $tables_act) {
    echo "keine Tabellen gefunden - ABBRUCH <br>";
    exit();
}

/**
 * einlesen der Eigentümer in array $eig_arr
 *
 * @var string $sql_eig
 */
$sql_eig = "SELECT ei_id FROM fh_eigentuemer ";
$return_eig = mysqli_query($db, $sql_eig);

$i = 0;
while ($row = mysqli_fetch_assoc($return_eig)) {
    $eig_arr[$i] = $row['ei_id'];
    $i++;
}

foreach ($eig_arr as $eignr) {

    if ($tabelle_g != "") {
        $tabelle = $tabelle_g . "_" . $eignr;

        if (array_key_exists($tabelle, $mag_arr)) {

            // einlesen der Fzgdaten in Arr
            # $table = "fz_muskel_$eignr";
            $sql = "SELECT * FROM `$tabelle`  $sql_where ORDER BY `ge_id` ASC";
            #echo "L 238: \$sql $sql <br/>";
            $return_ge = SQL_QUERY($db, $sql); // or die( "Zugriffsfehler ".mysqli_error($connect_fz)."<br/>");

            # print_r($return_fz);echo "<br>$sql <br>";
            $indienst = "";
            while ($row = mysqli_fetch_object($return_ge)) {
                # print_r($row);echo "<br>L 0244 row <br>";
                $indienst = trim($row->ge_indienst);

                if (strlen($indienst) == 4) {
                    # $m_arr[$i] = "$indienst|";
                } elseif (strlen($indienst) > 4) {

                    if (stripos($indienst, ".") >= 1) {
                        $d_arr = explode(".", $indienst);
                    } else {
                        $d_arr = explode("-", $indienst);
                    }

                    if (strlen($d_arr[2]) == 4) {
                        $indienst = $d_arr[2];
                    } elseif (strlen($d_arr[1]) == 4) {
                        $indienst = $d_arr[1];
                    } elseif (strlen($d_arr[0]) == 4) {
                        $indienst = $d_arr[0];
                    }
                }
                $g_arr[$i] = "$indienst|$row->ge_eignr|$row->ge_id|$row->ge_bezeich|$row->ge_komment|$row->ge_herst|$row->ge_type|$row->ge_zustand|$row->ge_foto_1|$row->ge_komm_1|$row->ge_sammlg|$tabelle";
                $i++;
            }
        }
    }

    if ($tabelle_m != "") {

        $tabelle = $tabelle_m . "_" . $eignr;

        if (array_key_exists($tabelle, $maf_arr)) {

            // einlesen der Fzgdaten in Arr
            # echo "L 0317 module $module <br> ". var_dump($_SESSION[$module] );n
            $sql = "SELECT * FROM `$tabelle`  \n
                    LEFT JOIN fh_sammlung ON $tabelle.fz_sammlg = fh_sammlung.sa_sammlg   \n
                    WHERE  fz_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";

            $return_fz = SQL_QUERY($db, $sql); // or die( "Zugriffsfehler ".mysqli_error($connect_fz)."<br/>");

            $t_daten = "";

            while ($row = mysqli_fetch_object($return_fz)) {
                $indienst = trim($row->fz_indienstst);
                if (strlen($indienst) == 4) {
                } elseif (strlen($indienst) > 4) {
                    if (stripos($indienst, ".")) {
                        $d_arr = explode(".", $indienst);
                    }
                    if (stripos($indienst, "-")) {
                        $d_arr = explode("-", $indienst);
                    }

                    if (isset($d_arr[2]) && strlen($d_arr[2]) == 4) {
                        $indienst = $d_arr[2];
                    } elseif (strlen($d_arr[1]) == 4) {
                        $indienst = $d_arr[1];
                    } elseif (strlen($d_arr[0]) == 4) {
                        $indienst = $d_arr[0];
                    }

                    if (isset($d_arr[2]) && strlen($d_arr[2]) == 4) {
                        $indienst = $d_arr[2];
                    } elseif (strlen($d_arr[1]) == 4) {
                        $indienst = $d_arr[1];
                    } elseif (strlen($d_arr[0]) == 4) {
                        $indienst = $d_arr[0];
                    }

                    $t_daten = "";

                    if ($row->fz_sammlg != 'MA_F-AH') {
                        /**
                         * Techn Daten- Anzeige
                         */
                        /* Dropdown Header */
                        $t_daten_head =  "
                        <div class='w3-dropdown-hover '
                             style='padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 5px; z-index: 3'>
                             <b style='color: blue; text-decoration: underline; text-decoration-style: dotted;'>Technische Daten</b>
                             <div class='w3-dropdown-content w3-bar-block w3-card-4'
                                       style='width: 50em; right: 0'>
                      ";

                        $t_daten_trail = "
                             </div>
                             <!-- w3-dropdown-content -->
                                
                       </div>
                       <!-- w3-dropdown-hover -->
                      ";
                        #var_dump($row);
                        if ($row->fz_motor == ', 0 ccm, , ') {
                            $row->fz_motor = "";
                        }
                        if ($row->fz_besatzung == "0 + 0") {
                            $row->fz_besatzung = "";
                        }
                        $motor = $antrieb = $einbau = $t_daten = '';
                        if ($row->fz_motor != "") {
                            $motor = "Motor : ".$row->fz_motor;
                        }
                        #echo "L 0332 ". $row['fz_antrieb'] ."; <br>";
                        if ($row->fz_antrieb != "" && strlen($row->fz_antrieb) >= 4) {
                            $antrieb = "<br>Antrieb : ".$row->fz_antrieb;
                            if ($row->fz_geschwindigkeit != "") {
                                $antrieb .= "<br>Geschwindigk.: ".$row->fz_geschwindigkeit;
                            }
                        }

                        if ($row->fz_l_pumpe != "" || $row->fz_t_kran != ""  || $row->fz_t_winde != ''   || $row->fz_t_leiter != ''  || $row->fz_t_abschlepp != ''
                            || $row->fz_l_tank != "" || $row->fz_g_atemsch != ""  || $row->fz_t_strom != ""  || $row->fz_t_beleuchtg != ""  || $row->fz_t_geraet != ""
                        ) {
                            $einbau = "<br>Fixe Einbauten : ";
                            if ($row->fz_l_tank != "") {
                                $einbau .= "<br>".$row->fz_l_tank;
                            }
                            if ($row->fz_l_pumpe != '') {
                                $einbau .= "<br>".$row->fz_l_pumpe;
                            }
                            if ($row->fz_t_kran != '') {
                                $einbau .= "<br>".$row->fz_t_kran;
                            }
                            if ($row->fz_t_winde != '') {
                                $einbau .= "<br>".$row->fz_t_winde;
                            }
                            if ($row->fz_t_leiter != '') {
                                $einbau .= "<br>".$row->fz_t_leiter;
                            }
                            if ($row->fz_t_abschlepp != '') {
                                $einbau .= "<br>".$row->fz_t_abschlepp;
                            }
                            if ($row->fz_t_geraet != '') {
                                $einbau .= "<br>".$row->fz_t_geraet;
                            }
                            if ($row->fz_g_atemsch != '') {
                                $einbau .= "<br>".$row->fz_g_atemsch;
                            }
                            if ($row->fz_t_strom != '') {
                                $einbau .= "<br>".$row->fz_t_strom;
                            }
                            if ($row->fz_t_beleuchtg != '') {
                                $einbau .= "<br>".$row->fz_t_beleuchtg;
                            }
                        }
                        if ($motor != "" || $antrieb != "" || $einbau != "") {
                            $t_daten = "<br>".$t_daten_head.$motor.$antrieb.$einbau.$t_daten_trail;
                        }

                    }

                }
                # echo "L 294:  $indienst <br/>";
                $m_arr[] = "$indienst|$row->fz_eignr|$row->fz_id|$row->fz_taktbez|$row->fz_allg_beschr|$row->fz_herstell_fg|$row->fz_aufbauer|$row->fz_zustand|$row->fz_bild_1|$row->fz_b_1_komm|$row->fz_sammlg <br>$row->sa_name|$row->fz_hist_bezeichng $t_daten|$tabelle";
                $i++;
            }
        }
    }
}
# var_dump($m_arr);
/**
 * Zusammenführung der Arrays
 *
 * Sortierung nach Indienststellung
 */

$fzg_arr = array();
# $g_arr & $m_arr -> $fzg_arr
if (isset($m_arr)) {
    sort($m_arr);
    if (count($m_arr) > 0) {
        foreach ($m_arr as $line) {
            $fzg_arr[] = $line;
        }
    }
}
if (isset($g_arr)) {
    sort($g_arr);
    if (count($g_arr) > 0) {
        foreach ($g_arr as $line) {
            $fzg_arr[] = $line;
        }
    }
}

/**
 * Druck der Liste
 */

$line_cnt = count($fzg_arr);

if ($_SESSION['VF_LISTE']['VarTableHight'] == "Ein") {
    if ($line_cnt >= 15) {
        $T_Style = "style='min-height:15cm;'";
    } elseif ($line_cnt >= 10) {
        $T_Style = "style='min-height:6cm;'";
    } else {
        $T_Style = "style='min-height:4cm; '";
    }
} else {
    $T_Style = "style='min-height:15cm; '";
}

if ($_SESSION['VF_LISTE']['LangListe'] == "Aus") {
    echo "<div class='resize'  $T_Style  > ";
} else {
    echo "<div  class='white' style='width: 90em'>";
}
# echo "<div class='resize' style='height: 15cm;'>";
echo "<b>Angezeigte Datensätze: $line_cnt.</b><br>";

echo "<table class='w3-table w3-striped w3-hoverable scroll'
	style='border: 1px solid black; background-color: white; margin: 0px;'>";
?>
<tr>
	<th>Indienst- Stellung</th>
	<th>Eigentümer<br>Fzg.Nr.
	</th>
	<th>Hersteller<br>Aufbauer<br>Zustand
	</th>
	<th>Fahrzeug/Gerät,<br>Typ<br>Beschreibung <br>
	</th>
	<th>Foto</th>
	<th>Sammlung</th>
	<th>Histor. Bez.</th>
</tr>
<?php
foreach ($fzg_arr as $line) {
    $line_arr = explode("|", $line);
    #$m_arr[] = "$indienst|$row->fz_eignr|$row->fz_id|$row->fz_taktbez|$row->fz_allg_beschr|$row->fz_herstell_fg|$row->fz_aufbauer|$row->fz_zustand|$row->fz_bild_1|$row->fz_b_1_komm|$row->fz_sammlg|$row->fz_hist_bezeichng|$tabelle";

    if (substr($line_arr[10], 0, 4) == 'MA_F') {
        $pic_d = 'MaF' ;
    } else {
        $pic_d = 'MaG';
    }

    if ($line_arr[8] == "") {
        $Bild = "";
    } else {

        $pict_path = "AOrd_Verz/$line_arr[1]/$pic_d/";

        $fo_arr = explode("-", $line_arr[8]);
        $cnt_fo = count($fo_arr);

        if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
            $urh = $fo_arr[0]."/";
            $verz = $fo_arr[1]."/";
            if ($cnt_fo > 3) {
                if (isset($fo_arr[3])) {
                    $s_verz = $fo_arr[3]."/";
                }
            }
            $p1 = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$line_arr[8];

            if (!is_file($p1)) {
                $p1 = $pict_path . $line_arr[8];
            }
        } else {
            $p1 = $pict_path . $line_arr[8];
        }

        $Bild = "<a href='$p1' target='Bild ' > <img src='$p1' alter='$p1' width='240px'>&nbsp;</a>";
    }
    #$m_arr[] = "$indienst|$row->fz_eignr|$row->fz_id|$row->fz_taktbez|$row->fz_allg_beschr|$row->fz_herstell_fg|$row->fz_aufbauer|$row->fz_zustand|$row->fz_bild_1|$row->fz_b_1_komm|$row->fz_sammlg|$row->fz_hist_bezeichng|$tabelle";
    $hersteller = $line_arr[5];
    if (isset($herst_arr[$line_arr[5]])) {
        $hersteller = $herst_arr[$line_arr[5]];
    }

    $aufbauer = $line_arr[6];
    if (isset($aufb_arr[$line_arr[5]])) {
        $aufbauer = $aufb_arr[$line_arr[5]];
    }

    $taktbez = $line_arr[3];
    if (isset($taktb_arr[$line_arr[3]])) {
        $taktbez = $taktb_arr[$line_arr[3]];
    }

    $sammlung = $line_arr[11];
    if (isset($sam_arr[$line_arr[11]])) {
        $sammlung = $sam_arr[$line_arr[11]];
    }

    $zustand = VF_Zustand[$line_arr[7]];


    echo "<tr><th>$line_arr[0]</th><td>$line_arr[1]<br>$line_arr[2]</td><td>$hersteller<br>$aufbauer<br>$zustand</td><td>$taktbez<br/>$line_arr[4] </td><td>$Bild<br/>$line_arr[9]</td><td>$line_arr[10]</td><td>$sammlung</td></tr>";
}

echo "  </table></div>";

echo "</fieldset>";

BA_HTML_trailer();

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row, $tabelle)
{
    global $path2ROOT, $T_List, $module;

    $s_tab = substr($tabelle, 0, 8);
    # echo "<br/> L 135: \$s_tab $s_tab <br/>";
    switch ($s_tab) {
        case "fh_eigen":
            $ei_id = $row['ei_id'];
            $row['ei_id'] = "<a href='VF_FM_GE_List.php?ei_id=$ei_id' >" . $ei_id . "</a>";
            break;
        case "mu_fahrz":
            $fm_id = $row['fm_id'];
            $row['fm_id'] = "<a href='VF_FM_MU_Edit.php?fm_id=$fm_id' >" . $fm_id . "</a>";
            if ($row['fm_foto_1'] != "") {
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuF/";

                $fm_foto_1 = $row['fm_foto_1'];

                $fo_arr = explode("-", $fm_foto_1);
                $cnt_fo = count($fo_arr);

                if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                    $urh = $fo_arr[0]."/";
                    $verz = $fo_arr[1]."/";
                    if ($cnt_fo > 3) {
                        if (isset($fo_arr[3])) {
                            $s_verz = $fo_arr[3]."/";
                        }
                    }
                    $p1 = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$fm_foto_1;

                    if (!is_file($p1)) {
                        $p1 = $pict_path . $fm_foto_1;
                    }
                } else {
                    $p1 = $pict_path . $fm_foto_1;
                }



                #           $p1 = $pict_path . $row['fm_foto_1'];

                $row['fm_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'>  $fm_foto_1  </a>";
            }
            break;
        case "mu_gerae":
            $mg_id = $row['mg_id'];
            $row['mg_id'] = "<a href='VF_FM_GE_Edit.php?ge_id=$mg_id' >" . $mg_id . "</a>";
            if ($row['mg_foto_1'] != "") {
                $pict_path = "AOrd_Verz/" . $_SESSION['Eigner']['eig_eigner'] . "/MuG/";

                $mg_foto_1 = $row['mg_foto_1'];
                $p1 = $pict_path . $row['mg_foto_1'];

                $row['mg_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'>  $mg_foto_1  </a>";
            }

            break;
    }

    return true;
} # Ende von Function modifyRow

?>
