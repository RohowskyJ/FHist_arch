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
# require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
require $path2ROOT . 'login/common/VF_M_tab_creat.lib.php';

$flow_list = true;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();


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



$prot = true;
BA_HTML_header('Reorg FF Autos ', '', 'List', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

/**
 * Einlesen der vorhandenen ge_raete und fz_muskel Dateien
 *
 * benötigte Tabellen
 *
 * @global array $ge_arr
 * @global array $fm_arr
 */
$maf_arr = $mag_arr = array();
$fm_arr = $ge_arr = $fz_arr = array();
$m_arr = $g_arr = array();
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

# echo "L 0223 $tabelle_m $tabelle_g <br>";
var_dump($maf_arr);
$tab_maf = "ma_fz_beschr_";
$tab_typ = 'fz_fz_type_';
$tab_fix = 'fz_fixeinb_';
$tab_eignr = 'fz_eigner_';

$tab_ma_fz_neu = 'ma_fahrzeug_';
$tab_ma_eig_neu = 'ma_eigner_';

foreach ($eig_arr as $eignr) {

    // einlesen der ma_fz_beschr_xy Daten
    $tab_m_i = $tab_maf.$eignr;
    $tab_t_i = $tab_typ.$eignr;
    $tab_f_i = $tab_fix.$eignr;
    $tab_e_i = $tab_eignr.$eignr;

    $tab_f_neu = $tab_ma_fz_neu.$eignr;
    $tab_e_neu = $tab_ma_eig_neu.$eignr;

    echo "L 0129 tabnam  $tab_m_i <br>";

    if (array_key_exists($tab_m_i, $maf_arr)) {
        $sql_m_i = "SELECT * FROM $tab_m_i ORDER BY fz_id ASC";
        echo "L 0133 sql_m_i $sql_m_i <br>";
        $ret_m_i = SQL_QUERY($db, $sql_m_i);
        $num_m_i = mysqli_num_rows($ret_m_i);
        if ($num_m_i != 0) {
            while ($row_m_i = mysqli_fetch_object($ret_m_i)) {
                var_dump($row_m_i);
                $sql_t_i = "SELECT * FROM $tab_t_i WHERE fz_t_id = $row_m_i->fz_id";

                $ret_t_i = SQL_QUERY($db, $sql_t_i);
                $row_t_i = mysqli_fetch_object($ret_t_i);
                var_dump($row_t_i);

                if (array_key_exists($tab_f_i, $fz_arr)) {
                    $sql_f_i = "SELECT * FROM $tab_f_i WHERE fz_id =  $row_m_i->fz_id";
                    $ret_f_i = SQL_QUERY($db, $sql_f_i);

                    $tank = $monitor = $pumpe = $kran = $winde = $abschl = $atemsch = $beleucht = $strom = "";
                    while ($row_f_i = mysqli_fetch_object($ret_f_i)) {
                        if (stripos($row_f_i->fz_gername, "pumpe") !== false) {
                            $pumpe = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "monit") !== false) {
                            #$monitor = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "tank") !== false) {
                            $tank = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "kran") !== false) {
                            $kran = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "winde") !== false) {
                            $winde = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "schlepp") !== false) {
                            $abschl = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "atem") !== false) {
                            $atem = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "bel") !== false) {
                            $beleucht = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "pumpe") !== false) {
                            $strom = "$row_f_i->fz_gername, $row_f_i->fz_ger_herst $row_f_i->fz_ger_typ";
                        }
                        if (stripos($row_f_i->fz_gername, "genera") !== false) {
                        }
                    }
                } else {
                    $tank = $monitor = $pumpe = $kran = $winde = $abschl = $atemsch = $beleucht = $strom = "";
                }

                $ctif_juror = $ctif_djahr = "";
                $sql_in = "SELECT * FROM fz_ctif_klass WHERE `fz_eignr`='$eignr' AND `fz_id`='$row_m_i->fz_id' ";
                $return_in = SQL_QUERY($db, $sql_in);

                $num_rows = mysqli_num_rows($return_in);
                if ($num_rows >= 1) {
                    while ($row = mysqli_fetch_object($return_in)) {
                        $ctif_juror = $row->fz_juroren;
                        $ctif_djahr = $row->fz_darstjahr;
                    }

                }


                $sql_n_ma = "INSERT INTO $tab_f_neu (fz_eignr,fz_invnr,fz_sammlg,fz_name,fz_taktbez,fz_baujahr,fz_indienstst,fz_ausdienst,fz_zeitraum,fz_allg_beschr, \n
                       fz_herstell_fg,fz_typ,fz_modell,fz_motor,fz_antrieb,fz_aufbauer,fz_aufb_typ,fz_besatzung, \n
                       fz_bild_1,fz_b_1_komm,fz_bild_2,fz_b_2_komm,fz_bild_3,fz_b_3_komm,fz_bild_4,fz_b_4_komm,  \n
                       fz_zustand,fz_ctif_klass,fz_ctif_date,fz_ctif_juroren,fz_ctif_darst_jahr,fz_beschreibg_det,fz_eigent_freig,fz_verfueg_freig,     \n
                       fz_l_tank,fz_l_monitor,fz_l_pumpe,fz_t_kran,fz_t_winde,fz_t_abschlepp,fz_g_atemsch,fz_t_beleuchtg,fz_t_strom,   \n
                       fz_pruefg,fz_pruefg_id,fz_aenduid,fz_aenddat
                       ) VALUES (
                       '$row_m_i->fz_eignr', '$row_m_i->fz_invnr', '$row_m_i->fz_sammlg', '$row_m_i->fz_name', '$row_m_i->fz_taktbez','$row_m_i->fz_baujahr','$row_m_i->fz_indienstst','$row_m_i->fz_ausdienst','$row_m_i->fz_zeitraum','$row_m_i->fz_komment',     \n
                       '$row_t_i->fz_herstell_fg','$row_t_i->fz_fgtyp','','$row_t_i->fz_m_bauform, $row_t_i->fz_hubraum ccm, $row_t_i->fz_kraftst, $row_t_i->fz_kuehlg','$row_t_i->fz_antrieb $row_t_i->fz_getriebe','$row_t_i->fz_herst_aufb','$row_t_i->fz_aufbau','$row_t_i->fz_sitzpl_1 + $row_t_i->fz_sitzpl_2',
                       '$row_m_i->fz_bild_1','$row_m_i->fz_b_1_komm','$row_m_i->fz_bild_2','$row_m_i->fz_b_2_komm','','','','',     \n
                       '$row_m_i->fz_zustand','$row_m_i->fz_ctifklass','$row_m_i->fz_ctifdate','$ctif_juror','$ctif_djahr','$row_m_i->fz_beschreibg_det','$row_m_i->fz_eigent_freig','$row_m_i->fz_verfueg_freig',    \n
                       '$tank','$monitor','$pumpe','$kran','$winde','$abschl','$atemsch','$beleucht','$strom',
                       '$row_m_i->fz_pruefg','$row_m_i->fz_pruefg_id','$row_m_i->fz_aenduid','$row_m_i->fz_aenddat')
                     ";

                # var_dump($sql_n_ma);

                echo "L 0215 sql_n_ma $sql_n_ma <br>";
                if (Cr_n_ma_fahrzeug($tab_f_neu)) {
                    $ret_ins_ma = SQL_QUERY($db, $sql_n_ma);
                    if (array_key_exists($tab_e_i, $fz_arr)) { # Eigner- Tabelle

                        $sql_e_i = "sELECT * FROM $tab_e_i where fz_id = $row_m_i->fz_id  ";
                        echo "L 0221 sql $sql_e_i <br>";
                        $ret_e_i = SQL_QUERY($db, $sql_e_i);

                        while ($row_e_i = mysqli_fetch_object($ret_e_i)) {
                            if ($row_e_i->fz_docbez == "") {
                                continue;
                            }

                            $sql_e_n = "INSERT INTO $tab_e_neu (    
                                    fz_id, fz_zul_dat,fz_zul_end_dat,fz_zul_namen,fz_uidaend,fz_aenddat
                                ) VALUES (
                                    '$row_e_i->fz_id','$row_e_i->fz_zul_dat','$row_e_i->fz_zul_end_dat','$row_e_i->fz_zuldaten','$row_e_i->fz_uidaend','$row_e_i->fz_aenddat'
                                )";

                            Cr_n_ma_eigner($tab_e_neu);
                            $ret_ma_ei_neu = SQL_QUERY($db, $sql_e_n);
                        }



                    }

                }


            }
        }
    }





}


exit;
### alter code
foreach ($eig_arr as $eignr) {

    if ($tabelle_g != "") {
        $tabelle = $tabelle_g . "_" . $eignr;

        if (array_key_exists($tabelle, $maf_arr)) {

            # Zeile n der Ausgabe:
            # echo "<tr><td>$row->fm_eignr<br/>$row->fm_id</td><td>$row->fm_bezeich<br/>$row->fm_indienst, $zustand<br/>$row->fm_herst</td><td>$row->fm_komment</td><td>$Bild</td></tr>";
            # $g_arr [i] indienst|eigentümer,recnr,zustand,bezeichnung,kommentar,hersteller,bild,
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

            $sql = "SELECT * FROM `$tabelle`  $sql_where ORDER BY `fz_id` ASC";
            # echo "L 0275 sql $sql <br>";
            $return_fz = SQL_QUERY($db, $sql); // or die( "Zugriffsfehler ".mysqli_error($connect_fz)."<br/>");

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
                }
                # echo "L 294:  $indienst <br/>";
                $m_arr[] = "$indienst|$row->fz_eignr|$row->fz_id|$row->fz_taktbez|$row->fz_komment|$row->fz_herstell_fg||$row->fz_zustand|$row->fz_bild_1|$row->fz_b_1_komm|$row->fz_sammlg|$tabelle";
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
	<th>Indienststellung</th>
	<th>Eigentümer<br>Fzg.Nr.
	</th>
	<th>Hersteller<br>Zustand
	</th>
	<th>Fahrzeug/Gerät,<br>Typ<br>Beschreibung <br>
	</th>
	<th>Foto</th>
</tr>
<?php
foreach ($fzg_arr as $line) {
    $line_arr = explode("|", $line);
    # $m_arr[] = "$indienst|$row->fm_eignr|$row->fm_id|$row->fm_bezeich|$row->fm_komment|$row->fm_herst|$row->fm_type|$row->fm_zustand|$row->fm_foto_1|$row->fm_komm_1|$row->fm_sammlg|$tabelle";
    if (substr($line_arr[10], 0, 4) == 'MA_F') {
        $pic_d = 'MaF' ;
    } else {
        $pic_d = 'MaG';
    }

    if ($line_arr[8] == "") {
        $Bild = "";
    } else {

        $pictpath = "AOrd_Verz/$line_arr[1]/$pic_d/";

        $p1 = $pictpath . $line_arr[8];

        $Bild = "<a href='$p1' target='Bild ' > <img src='$p1' alter='$p1' width='240px'>&nbsp;</a>";
    }
    $zustand = VF_Zustand[$line_arr[7]];
    echo "<tr><th>$line_arr[0]</th><td>$line_arr[1]<br>$line_arr[2]</td><td>$line_arr[5]<br>$line_arr[6]<br>$zustand</td><td>$line_arr[3]<br/>$line_arr[4] </td><td>$Bild<br/>$line_arr[9]</td><td>$line_arr[10]</td></tr>";
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
                $p1 = $pict_path . $row['fm_foto_1'];

                $row['fm_foto_1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'>  $fm_foto_1  </a>";
            }
            break;
        case "mu_gerae":
            $ge_id = $row['mg_id'];
            $row['mg_id'] = "<a href='VF_FM_GE_Edit.php?ge_id=$ge_id' >" . $mg_id . "</a>";
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
