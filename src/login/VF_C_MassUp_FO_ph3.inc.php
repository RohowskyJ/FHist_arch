<?php
/**
 * Erstellen der Tabellen-Einträg für die hochgeladenen Fotos
 * 
 */

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_FO_ph3.inc.php ist gestarted</pre>";
}
$watermark = $_POST['watermark'] ?? 'N';

$md_beschreibg = $_POST['md_beschreibg'] ?? '';
$md_Urheber = $_POST['md_Urheber'] ?? '';
$md_aufn_datum = $_POST['md_aufn_datum'] ?? '';
$md_eigner = $_POST['urhNr'] ?? '';

foreach ($_POST as $key => $value) {
    
    if (substr($key,0,5) == 'name_' && $key != '') {
        $md_dsn_1 = trim($value);
        
        $f_arr = pathinfo(strtolower($value));
        $ext = $f_arr['extension'];
        if (in_array($ext, ['gif', 'ico', 'jpeg', 'jpg', 'png', 'tiff'])) {
            $media = 'Foto';
        }
        if ($ext == 'md3') {$media = 'Audio';}
        if ($ext == 'md4') {$media = 'Video';}
        
        $sql_add = "INSERT dm_edien_$md_eigner (
                         md_eigner,md_urheber,md_dsn_1,md_aufn_datum,md_beschreibg,md_namen,
                         md_sammlg,md_media,
                         md_aenduid
                      ) VALUE (
                        '$md_eigner','$md_Urheber','$md_dsn_1','$md_aufn_datum','$md_beschreibg','',
                        '','$media',
                        '" . $_SESSION['VF_Prim']['p_uid'] . "'
                      )";
        $result = SQL_QUERY($db, $sql_add);
    }
}

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_FO_ph3.inc.php ist beendet</pre>";
}