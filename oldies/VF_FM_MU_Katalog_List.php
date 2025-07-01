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
    
    /* */
    // Error Reporting aktivieren (alle Fehler)
    error_reporting(E_ALL);
    
    // Fehler direkt im Browser anzeigen (z.B. während der Entwicklung)
    ini_set('display_errors', '1');
    
    // Fehler in eine Log-Datei schreiben (empfohlen im Produktivsystem)
    ini_set('log_errors', '1');
    ini_set('error_log', $path2ROOT . "login/e_log/error.log"); // Stelle sicher, dass der Pfad beschreibbar ist
    #$path2ROOT . "login/e_log/" . $date . "_$id.flow";
    /* */
   
    $debug = False; // Debug output Ein/Aus Schalter

    require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
    require $path2ROOT . 'login/common/VF_Const.lib.php';

    require $path2ROOT . 'login/common/BA_Funcs.lib.php';
    require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
    require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
    require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

    $flow_list = True;
    if ($flow_list) {
        flow_add($module,"VF_FM_MU_Katalog_list.php Funct: start" );
    }

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
        $_SESSION[$module]['all_upd'] = False;
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
        if ($response == "" || $response == "Nix") {} else {
            $sammlg = $_SESSION[$module]['sammlung'] = $response;
        }
    }

    # ===========================================================================================
    # Definition der Auswahlmöglichkeiten (mittels radio Buttons)
    # ===========================================================================================
    $T_list_texte = array(
        "Alle" => "Alle bekannten Muskelgezogenen Fzg/Geräte nach Indienststellung (Auswahl)"
    );
    
    # ===========================================================================================================
    # Haeder ausgeben
    # ===========================================================================================================
    $title = "Muskelbewegte Fahrzeuge ";
    ;
    $header = "";
    $VF_logo = 'NEIN';
    $prot = True;
    HTML_header('Katalog Muskelbewegte Fahrzeuge und Geräte ', 'Auswahl', '', 'Admin', '90em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

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

    List_Action_Bar($tabelle, "Katalog- Liste muskelbewegter Fahrzeuge und Geräte", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

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
            $MS_Init = VF_Sel_SA_MU; # VF_Sel_SA_Such|VF_Sel_AOrd
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

    if ($_SESSION[$module]['sammlung'] == "MU_F") {
        $tabelle_g = ""; # "ge_raet";
        $tabelle_m = "mu_fahrzeug";
        $sql_where = "WHERE fm_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";
    }

    if ($_SESSION[$module]['sammlung'] == "MU_G") {
        $tabelle_g = "mu_geraet";
        $sql_where = "WHERE mg_sammlg LIKE '%" . $_SESSION[$module]['sammlung'] . "%' ";
        $tabelle_m = "";
    }
    # cho "L 0191 $tabelle_g $tabelle_m <br>";
    /**
     * Einlesen der vorhandenen ge_raete und fz_muskel Dateien
     *
     * benötigte Tabellen
     *
     * @global array $ge_arr
     * @global array $fm_arr
     */
    $muf_arr = $mug_arr = array();
    $fm_arr = $ge_arr = array();
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
        $i ++;
    }

    #cho "L 0225 $tabelle_m $tabelle_g <br>";
    # var_dump($mug_arr);
    foreach ($eig_arr as $eignr) {

        if ($tabelle_g != "") {
            $tabelle = $tabelle_g . "_" . $eignr;

            if (array_key_exists($tabelle, $mug_arr)) {

                # Zeile n der Ausgabe:
                # echo "<tr><td>$row->fm_eignr<br/>$row->fm_id</td><td>$row->fm_bezeich<br/>$row->fm_indienst, $zustand<br/>$row->fm_herst</td><td>$row->fm_komment</td><td>$Bild</td></tr>";
                # $g_arr [i] indienst|eigentümer,recnr,zustand,bezeichnung,kommentar,hersteller,bild,
                // einlesen der Fzgdaten in Arr
                # $table = "fz_muskel_$eignr";
                $sql = "SELECT * FROM `$tabelle`  $sql_where ORDER BY `mg_id` ASC";
                #echo "L 240: \$sql $sql <br/>";
                $return_ge = SQL_QUERY($db, $sql); // or die( "Zugriffsfehler ".mysqli_error($connect_fz)."<br/>");

                # print_r($return_ge);echo "<br>$sql <br>";
                $indienst = "";
                while ($row = mysqli_fetch_object($return_ge)) {
                     # print_r($row);echo "<br>L 0244 row <br>";
                    $indienst = trim($row->mg_indienst);
                    
                    # echo "L 246 indienst $indienst <br>";
                    if (strlen($indienst) == 4) {
                        # $m_arr[$i] = "$indienst|";
                    } else if (strlen($indienst) > 4) {
                        $d_arr = explode(".", $indienst);
                        if (strlen($d_arr[2]) == 4) {
                            $indienst = $d_arr[2];
                        } elseif (strlen($d_arr[1]) == 4) {
                            $indienst = $d_arr[1];
                        } elseif (strlen($d_arr[0]) == 4) {
                            $indienst = $d_arr[0];
                        }
                    }
                    $g_arr[$i] = "$indienst|$row->mg_eignr|$row->mg_id|$row->mg_bezeich|$row->mg_komment|$row->mg_herst|$row->mg_type|$row->mg_zustand|$row->mg_foto_1|$row->mg_komm_1|$row->mg_sammlg|$tabelle";
                    $i++;
                }
            }
        }

        if ($tabelle_m != "") {

            $tabelle = $tabelle_m . "_" . $eignr;

            if (array_key_exists($tabelle, $muf_arr)) {

                // einlesen der Fzgdaten in Arr

                $sql = "SELECT * FROM `$tabelle`  $sql_where ORDER BY `fm_id` ASC";
 
                $return_fz = SQL_QUERY($db, $sql); // or die( "Zugriffsfehler ".mysqli_error($connect_fz)."<br/>");

                while ($row = mysqli_fetch_object($return_fz)) {
                    $indienst = trim($row->fm_indienst);
                    if (strlen($indienst) == 4) {} else if (strlen($indienst) > 4) {
                        $d_arr = explode(".", $indienst);
                        if (strlen($d_arr[2]) == 4) {
                            $indienst = $d_arr[2];
                        } elseif (strlen($d_arr[1]) == 4) {
                            $indienst = $d_arr[1];
                        } elseif (strlen($d_arr[0]) == 4) {
                            $indienst = $d_arr[0];
                        }
                    }
                    # echo "L 294:  $indienst <br/>";
                    $m_arr[] = "$indienst|$row->fm_eignr|$row->fm_id|$row->fm_bezeich|$row->fm_komment|$row->fm_herst|$row->fm_type|$row->fm_zustand|$row->fm_foto_1|$row->fm_komm_1|$row->fm_sammlg|$tabelle";
                    $i++;
                }
            }
        }
    }
    # var_dump($g_arr);
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
        echo "<div  class='white'>";
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
    if ($line_arr[10] == 'MU_F') {
        $pic_d = 'MuF' ;
    } else {
        $pic_d = 'MuG';
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

HTML_trailer();

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

    return True;
} # Ende von Function modifyRow

?>
