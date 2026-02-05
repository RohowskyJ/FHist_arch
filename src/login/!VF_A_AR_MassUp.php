<?php

/**
 * Archivalien- Massupload
 *
 * @author J. Rohowsky  - neu 2024, main change 2025 neues Tabellen-Felder
 *
 */
session_start();

$module = 'ARC';
$tabelle = 'ar_chivdat_';

const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug();

$jq = $jqui  = True; // Laden von jquery und jquery-ui
$BA_AJA = True;
$header = "
<style>
        #preview {
            display: flex;
            flex-direction: column;
        }
        .preview-image {
            margin: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .preview-image img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
";

BA_HTML_header('Archivalien- Mass- Upload',  $header, 'Form', '70em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

initial_debug();

VF_chk_valid();

VF_set_module_p();

var_dump($_POST);
// ============================================================================================================
// Eingabenerfassung und defauls Teil 1 - alle POST Werte werden später in array $neu gestelltt
// ============================================================================================================

if (isset($_POST['phase'])) {
    $phase = $_POST['phase'];
} else {
    $phase = 0;
}
echo "L 076 phase $phase  <br>";
if (! isset($_SESSION['Eigner']['eig_eigner'])) {
    $_SESSION['Eigner']['eig_eigner'] = "";
}

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *         - select_string
 *         - SelectAnzeige Ein: Anzeige der SQL- Anforderung
 *         - SpaltenNamenAnzeige Ein: Anzeige der Apsltennamen
 *         - DropdownAnzeige Ein: Anzeige Dropdown Menu
 *         - LangListe Ein: Liste zum Drucken
 *         - VarTableHight Ein: Tabllenhöhe entsprechend der Satzanzahl
 *         - CSVDatei Ein: CSV Datei ausgeben
 */
if (! isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE'] = array(
        "SelectAnzeige" => "EIN",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige" => "Aus",
        "LangListe" => "Ein",
        "VarTableHight" => "Ein",
        "CSVDatei" => "Aus"
    );
}

if (!isset($Err_Msg)) {
    $Err_Msg = "";
}
# var_dump($_POST);
if ($phase == 3) {
    $ao_num = 0 ;   
    # echo "L 111 ao_num $ao_num; phase $phase <br>";
    $neu = array();
    $neu['ad_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
    $tabelle_a = 'ar_chivdat_';
    foreach ($_POST as $name => $value ) {
        if ($name == "eigner") {
            $neu['ad_eignr'] = $eigner = $value;
            $tabelle_a .= $eigner;
            $return_a = Cr_n_ar_chivdat($tabelle_a);
        }
        if ($name == 'aOrd' ){
            $aOrd = $value;
            $ao_arr = explode('/',$aOrd);
            $neu['ad_sg'] = $ao_arr[0];
            $neu['ad_subsg'] = $ao_arr[1];
        }
        if ($name == 'aoText') {
            # echo "L 0128 value $value <br>";
            
            if (strpos($value,'|') > 2) {
                $t_arr = explode('|',$value);
                $cnt_ta = count($t_arr);
                var_dump($t_arr);
                $neu['ad_lcsg'] = $t_arr[0];
                $neu['ad_lcssg'] = $t_arr[1];
                $neu['ad_lcssg_s0'] = $t_arr[2];
                $neu['ad_lcssg_s1'] = $t_arr[3];
                $neu['ad_sammlg'] = $t_arr[4];
                $neu['ad_beschreibg'] = $t_arr[5];
            } else {    
                $neu['ad_lcsg'] = $neu['ad_lcssg'] = $neu['ad_lcssg_s0'] = $neu['ad_lcssg_s1'] = '00';
                $neu['ad_sammlg'] = "";
                $neu['ad_beschreibg'] = substr($value,6);
            }
 
            $sql = "SELECT * FROM $tabelle_a WHERE  ad_sg='".$neu['ad_sg']."' AND ad_subsg='".$neu['ad_subsg']."' ";
            $res = SQL_QUERY($db,$sql);
            $ao_num = mysqli_num_rows($res);
        
        }
        if (substr($name,0,5) == 'name_' ){
             
            $d_arr = explode('_',$value) ;
            $d_cnt = count($d_arr);
            
            if ($d_cnt >=2 ){
                $neu['ad_doc_date'] = $d_arr[0];
            } else {
                $neu['ad_doc_date'] = 'mist';
            }
            $ao_num++;
            $neu['ad_ao_fortlnr'] = $ao_num ; // feststellen dur einlesen mit aord
            
            $neu['ad_doc_1'] = $value;
   
            $sql = "INSERT INTO $tabelle_a (
                ad_eignr,ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_lcssg_s0,ad_lcssg_s1,ad_ao_fortlnr,ad_sammlg,
                ad_doc_date,ad_type,ad_format,
                ad_keywords,ad_beschreibg,ad_wert_orig,ad_orig_waehrung,ad_wert_kauf,ad_kauf_waehrung,
                ad_wert_besch,ad_besch_waehrung,ad_namen,ad_doc_1,
                ad_doc_2,ad_doc_3,ad_doc_4,ad_isbn,ad_lagerort,
                ad_l_raum,ad_l_kasten,ad_l_fach,
                ad_l_pos_x,ad_l_pos_y,ad_neueigner,ad_uidaend,ad_aenddat
              ) VALUE (
                '$neu[ad_eignr]','$neu[ad_sg]','$neu[ad_subsg]','$neu[ad_lcsg]','$neu[ad_lcssg]','$neu[ad_lcssg_s0]','$neu[ad_lcssg_s1]','$neu[ad_ao_fortlnr]','$neu[ad_sammlg]',
                '$neu[ad_doc_date]','DO','A4',
                '','$neu[ad_beschreibg]','0','','0','',
                '0','0','','$neu[ad_doc_1]',
                '','','','','',
                '','','',
                '','','','$neu[ad_uidaend]',now()
               )";

            if ($debug) {
                echo "<pre class=debug>";
                print_r($return_fi);
                echo "<br> \$sql $sql <br>";
                echo "</pre>";
            }
            
            $result = SQL_QUERY($db, $sql);  
        }

    }
    $phase = 1;
}

if ($phase == 1) {
    if (isset($_POST['eigentuemer_1']) ) {
        VF_Displ_Eig($_POST['eigentuemer_1']);
    }
    require "!VF_A_AR_MassUp_ph1.inc.php";
}

if ($phase == 0) {

        echo "<h2>Eigentümer und Archivordnung auswählen zum Archivalien hochladen</h2>";
        /*
        VF_Eig_Ausw();
           */
        /**VF_Eig_Ausw
         * neuen Eigentümer auswählen
         */
        if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten") {
            if ($_SESSION['Eigner']['eig_eigner'] == "") {
                VF_Auto_Eigent("E");
            }
        } else {
            $_SESSION['Eigner']['eig_eigner'] = $_SESSION['VF_Prim']['eignr'];
        }
  
        /**
         * Parameter für den Aufruf von Multi-Dropdown
         *
         * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
         *
         *
         * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
         * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
         * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
         *
         * @Input-Parm $_POST['Level1...6']
         */
        
        $MS_Txt = array(
            'Auswahl der Obersten (ÖBFV)- Ebene  ',
            'Auswahl der 2. ÖBFV- Ebene ',
            'Auswahl der Lokalen Erweiterung (3. Ebene) ',
            'Auswahl des 2. Erweiterung (4. Ebene)  ',
            'Auswahl des 3. Erweiterung (5. Ebene)  ',
            'Auswahl des 4. Erweiterung (6. Ebene)  '
        );

        $MS_Lvl   = 6; # 1 ... 6
        $MS_Opt   = 2; # 1: SA für Sammlung, 2: AO für Archivordnung
        
        switch ($MS_Opt) {
            case 1:
                $in_val = 'PA_R';
                $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
            case 2:
                $in_val = '07';
                $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
                break;
        }
        
        $titel  = 'Auswahl aus der Archivordnung';
        VF_Multi_Dropdown($in_val,$titel);
        
        echo "<br> <button type='submit' name='phase' value='1' class=green>Auswahl bestätigen</button></p>";
}


BA_HTML_trailer();
?>                         