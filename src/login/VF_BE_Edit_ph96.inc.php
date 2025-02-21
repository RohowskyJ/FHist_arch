<?php
/**
 * Liste der Veranstaltungsberichte, Wartung, Neueingabe, Anzeige der verfügbaren Fotos
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph96.lib.php ist gestarted</pre>";}

require 'common/VF_F_tab_creat.lib.php';

$ar_arr = $fo_arr = $fz_arr = $maf_arr = $fm_arr = $muf_arr = $mug_arr = $ge_arr = $mag_arr = $in_arr = $zt_arr = $arcxr_arr = $mar_arr = array();
VF_tableExist();

echo $Err_Msg ;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='".$neu['vb_flnr']."' />";
echo "<input name='vb_datum' id='vb_datum' type='hidden' value='".$neu['vb_datum']."' />";
# echo "<input name='basis_pfad' id='basis_pfad' type='hidden' value='".$neu['basis_pfad']."' />";
# echo "<input name='zus_pfad' id='zus_pfad' type='hidden' value='".$neu['zus_pfad']."' />";

# var_dump($_SESSION[$module]['Urheber']);echo "<br>L 020 Urheber <br>";
#  echo "veranst_datum   $veranst_datum <br>";
$fo_src_arr = array();
$d_arr = explode("-",$veranst_datum);
$ver_datum = $d_arr[0].$d_arr[1].$d_arr[2];
# echo "ver_datum $ver_datum <br>"; echo "<table class='w3-table-all '>";
echo "<table><tbody>";
echo "<tr><th>Urheber</th><th>Datensatz</th><th>Beschreibung</th><th></th></tr>";
var_dump($fo_arr);
foreach ($fo_arr as $fo_tabs => $val) {
    
    
    $sql_foto = "SELECT * FROM $fo_tabs Where fo_aufn_datum='$ver_datum' AND fo_dsn='' ";

    $return_foto = SQL_QUERY($db,$sql_foto);

    While ($row_foto = mysqli_fetch_assoc($return_foto)) {

        $modRC = modifyRow_96($row_foto,$fo_tabs);
        if ( $modRC === False ) {continue;}
        
        echo "<tr><td>".$row_foto['fo_eigner']."</td><td>".$row_foto['fo_id']."</td><td>".$row_foto['fo_begltxt']."</td></tr>";
        
    }

}

echo "</tbody></table>";

$tabelle = 'fo_todaten';
$fo_aufn_d = $ver_datum;

$_SESSION[$module]['fo_aufn_d'] = $ver_datum;

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='0' class='green'>Fotos zur Auswahl anzeigen</button></p>";

echo "<p><a href='VF_BE_List.php?Act=".$_SESSION[$module]['Act']."'>Zurück zur Liste</a></p>";

    
    # =========================================================================================================
if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph96.inc.php beendet</pre>";}


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
 * @global string $path2VF String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow_96(array &$row   # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
    ,$tabelle)
{ global $path2VF,$T_List,$module;

$pict_path = $path2VF."login/AOrd_Verz/";

$fo_eigner = $row['fo_eigner'];

# $row['fo_eigner'] = "<a href='VF_O_FO_List_Bericht.php?fo_eigner=".$row['fo_eigner']."&fo_aufn_d=".$row['fo_aufn_datum']."'  >$fo_eigner - Fotos ansehen - auswählen,  Bericht erstellen</a>" ;
$row['fo_eigner'] = "<input type='checkbox' id='fo_$fo_eigner' name='fo_id' value='$fo_eigner'>$fo_eigner - Fotos ansehen - auswählen,  Bericht erstellen" ;

# <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">

return True;
} # Ende von Function modifyRow
?>