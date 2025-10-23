<?php
/**
 * Liste der Buchbesprechungen, Wartung, Daten schreiben
 *
 * @author j. Rohowsky - neu 2019
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_O_Bu_Edit_ph1.php ist gestarted</pre>";
}
# var_dump($neu);
if ( $neu['bild_datei_1'] != '') {
    $neu['bu_bild_1'] =  $neu['bild_datei_1'];
}
if ( $neu['bild_datei_2'] != '') {
    $neu['bu_bild_2'] =  $neu['bild_datei_2'];
}
if ( $neu['bild_datei_3'] != '') {
    $neu['bu_bild_3'] =  $neu['bild_datei_3'];
}
if ( $neu['bild_datei_4'] != '') {
    $neu['bu_bild_4'] =  $neu['bild_datei_4'];
}
if ( $neu['bild_datei_5'] != '') {
    $neu['bu_bild_5'] =  $neu['bild_datei_5'];
}
if ( $neu['bild_datei_6'] != '') {
    $neu['bu_bild_6'] =  $neu['bild_datei_6'];
}


if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($bu_id == 0) { # Neuer Datensatz
    $sql = "INSERT INTO bu_echer (
              bu_titel,bu_utitel,bu_author,bu_verlag,bu_isbn,bu_preis,
              bu_seiten,bu_bilder_anz,bu_bilder_art,bu_format,bu_eignr,bu_invnr,
              bu_teaser,bu_text,bu_bild1,bu_bild2,bu_bild3,bu_bild4,
              bu_bild5,bu_text1,bu_text2,bu_text3,bu_text4,bu_text5,
              bu_bew_ges,bu_bew_bild,bu_bew_txt,bu_editor,bu_ed_id,bu_edit_dat,
              bu_frei_stat
              ) VALUE (
               '$neu[bu_titel]','$neu[bu_utitel]','$neu[bu_author]','$neu[bu_verlag]','$neu[bu_isbn]','$neu[bu_preis]',
               '$neu[bu_seiten]','$neu[bu_bilder_anz]','$neu[bu_bilder_art]','$neu[bu_format]','$neu[bu_eignr]','$neu[bu_invnr]',
               '$neu[bu_teaser]','$neu[bu_text]','$neu[bu_bild_1]','$neu[bu_bild_2]','$neu[bu_bild_3]','$neu[bu_bild_4]',
               '$neu[bu_bild_5]','$neu[bu_bild_6]','$neu[bu_text_1]','$neu[bu_text_2]','$neu[bu_text_3]','$neu[bu_text_4]','$neu[bu_text_5]','$neu[bu_text_6]',
               '$neu[bu_bew_ges]','$neu[bu_bew_bild]','$neu[bu_bew_txt]','$neu[bu_editor]','$p_uid',now(),
               '$neu[bu_frei_stat]'
               )";
} else { # Update
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
      
        if (substr($name,0,3) != "bu_") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $sql = "UPDATE `bu_echer` SET `bu_frei_dat`=NOW(),bu_frei_id='$p_uid' $updas WHERE `bu_id`='$bu_id'";
}
# echo "L 072 sql $sql <br>";
$result = SQL_QUERY($db, $sql);
header("Location: VF_O_BU_List.php?Act=" . $_SESSION[$module]['Act']);

if ($debug) {
    echo "<pre class=debug>VFH_O_Bu_Edit_ph1.php beendet</pre>";
}
?>