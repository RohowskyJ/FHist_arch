<?php
/**
 * Laden von Daten in ArchivTabellen umd Moven von Dateien in die entsprechenden Verzechnisse
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * Die Daten aus dem Verzeichnis /login/VF_Mass_Upload/files werden n das entsprechnde Archiv-Verzeichnis kopiert
 * Danach werden die Tabellen-Einträge in die entsprechende Tabelle erzeugt.
 * 
 * 
 * @global boolean $debug     Anzeige  von Debug- Informationen: if ($debug) { echo "Text" }
 */

$debug = False; // Debug output Ein/Aus Schalter
console_log("ph2 start");
// Verzeichnis-Datensatz erstellen
$eignr = $_SESSION['Eigner']['eig_eigner'];
console_log($eignr);
$tabelle = "ar_chivdt_$eignr";
console_log($tabelle);
Cr_n_ar_chivdt($tabelle);

// Vorbereitung zum Kopieren/löschen der Dateien

$arr_ak = array('00','00','00','00');

$aord_arr = explode(" ",$_SESSION[$module]['archord']);

$ao_a = explode (".",$aord_arr[0]);
if (isset($ao_a[0]) ) {
    $arr_ak[0] = $ao_a[0];
}
if (isset($ao_a[1])) {
    $arr_ak[1] = $ao_a[1];
}

if (isset($aord_arr[1]) && $aord_arr[1] != '00') {
    $arr_ak[2] = $aord_arr[1];
}
if (isset($aord_arr[2]) && $aord_arr[2] != '00') {
    $arr_ak[3] = $aord_arr[2];
}

$AO_dat = "";
if (isset($arr_ak)) {

    if ($arr_ak[2] == "00") {
        $AO_dat = VF_Displ_Aro($arr_ak[0], $arr_ak[1]);
    } elseif ($arr_ak[3] == "00") {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3]);
    } else {
        $AO_dat = VF_Displ_Arl($arr_ak[0], $arr_ak[1], $arr_ak[2], $arr_ak[3]);
    }
}

$eigner = $_SESSION['Eigner']['eig_eigner'];
$pict_path = "AOrd_Verz/$eigner/" . $arr_ak[0] . "/" . $arr_ak[1];
$_SESSION['AOrd_sel']['pict_path'] = $pict_path;

$basis_Pfad = "VF_Upload";

$files_in = array();

$files_in = scandir($basis_Pfad);

if (is_dir($basis_Pfad)) {
    $files_in = scandir($basis_Pfad);
}

echo "<table class='w3-table-all'>";
echo "<tbody>";

if (! is_file($pict_path) && ! is_dir($pict_path)) {
    mkdir($pict_path, 0777, True);
}

$success = "";

foreach ($files_in as $key => $filename) {
    if ($filename == "." or $filename == "..") {
        continue;
    }
    if ($filename == ".gitignore" or $filename == ".htaccess") {
        continue;
    }
    echo "$filename <b>";
    
    $filename = VF_trans_2_separate($filename);
    
    # if (stripos($filename,$aufn_dat) === false ) {continue;}
    $success = "";
    $src_f = $basis_Pfad . "/" . $filename;
    $targ_f = $_SESSION['AOrd_sel']['pict_path'] . "/" . $filename;
    $cp_ret = copy($src_f, $targ_f);
    if ($cp_ret === True) {
        $success = "kopiert";

        unlink($src_f);

        $doc_dat = substr($filename, 0, 4) . "-" . substr($filename, 4, 2) . "-" . substr($filename, 6, 2);

        $sql = "SELECT * FROM $tabelle where ad_doc_1 = '$filename' ";
        $return = SQL_QUERY($db, $sql);
        print_r($return);
        echo "<br>L 0118: return $sql <br>";

        $type = "";
        if ($arr_ak[0] == "1" && $arr_ak[1] == 01 && $arr_ak[2] == "0" && $arr_ak[3] == "0") {
            $type = "PR";
        }
        if ($arr_ak[0] == "1" && $arr_ak[1] == 01 && $arr_ak[2] == "01" && $arr_ak[3] == "0") {
            $type = "PRBU";
        }
        if (mysqli_num_rows($return) == "0") {

            $arcnewnr = "";
            $arcsg = $arr_ak[0];
            $arcssg = $arr_ak[1];
            $arclcsg = $arr_ak[2];
            $arclcssg = $arr_ak[3];
            
            $sql_nr = "SELECT * FROM `$tabelle`
                WHERE `ad_sg`='$arcsg' AND `ad_subsg`='$arcssg' AND `ad_lcsg`='$arclcsg' AND `ad_lcssg`='$arclcssg' ";
            $return_nr = mysqli_query($db, $sql_nr); // or die("Datenbankabfrage gescheitert. ".mysqli_error($db));
            if ($return_nr) {
                $row = mysqli_fetch_object($return_nr);
                # $flnr = $row->ad_ao_fortlnr;
                $numrow = mysqli_num_rows($return_nr);
                $ad_ao_fortlnr = $numrow + 1;
            } else {
                $ad_ao_fortlnr = 1;
            }

            $sql = "INSERT INTO $tabelle (
                ad_eignr,ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_ao_fortlnr,ad_doc_date,ad_doc_1,ad_type,
                ad_uidaend
              ) VALUE (
                '$eignr','$arr_ak[0]','$arr_ak[1]','$arr_ak[2]','$arr_ak[3]','$ad_ao_fortlnr','$doc_dat','$filename','$type',
                '" . $_SESSION['VF_Prim']['p_uid'] . "'
               )";
            $result = SQL_QUERY($db, $sql);
        }
    } else {
        $success = "nicht kopiert - Fehler";
        die('Kopieren musste abgebrochen werden.');
    }
}

echo "<tr><td>$filename</td><td>$success</td><td>

</td></tr>";
# }

echo "</tbody";
echo "</table>";

# HTML_trailer();

?>