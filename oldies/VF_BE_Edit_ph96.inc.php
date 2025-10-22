<?php
/**
 * Liste der Veranstaltungsberichte, Wartung, Neueingabe, Anzeige der verfügbaren Fotos
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

$Inc_Arr = array();
$Inc_Arr[] = "VF_BE_Edit_ph96.inc.php";

if ($debug) {echo "<pre class=debug>VF_BE_Edit_ph96.lib.php ist gestarted</pre>";}

require 'common/VF_F_tab_creat.lib.php';


$ar_arr = $dm_arr = $maf_arr = $mag_arr = $muf_arr = $mug_arr = $in_arr = $zt_arr = $arcxr_arr = $mar_arr = array();
VF_tableExist();

echo $Err_Msg ;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='".$neu['vb_flnr']."' />";
echo "<input name='vb_datum' id='vb_datum' type='hidden' value='".$neu['vb_datum']."' />";
# echo "<input name='basis_pfad' id='basis_pfad' type='hidden' value='".$neu['basis_pfad']."' />";
# echo "<input name='zus_pfad' id='zus_pfad' type='hidden' value='".$neu['zus_pfad']."' />";

# var_dump($_SESSION[$module]['Urheber']);echo "<br>L 020 Urheber <br>";
#  echo "veranst_datum   $veranst_datum <br>";
$dm_src_arr = array();
$d_arr = explode("-",$veranst_datum);
$ver_datum = $d_arr[0].$d_arr[1].$d_arr[2];
# echo "ver_datum $ver_datum <br>"; echo "<table class='w3-table-all '>";
echo "<table><tbody>";
echo "<tr><th>Urheber</th><th>Datensatz</th><th>Beschreibung</th><th></th></tr>";
var_dump($dm_arr);
foreach ($dm_arr as $dm_tabs => $val) {
    
    
    $sql_foto = "SELECT * FROM $dm_tabs Where md_aufn_datum='$ver_datum' AND md_dsn_1='' ";

    $return_foto = SQL_QUERY($db,$sql_foto);

    While ($row_foto = mysqli_fetch_assoc($return_foto)) {

        $modRC = modifyRow_96($row_foto,$dm_tabs);
        if ( $modRC === False ) {continue;}
        
        echo "<tr><td>".$row_foto['md_eigner']."</td><td>".$row_foto['md_id']."</td><td>".$row_foto['md_beschreibg']."</td></tr>";
        
    }

}

echo "</tbody></table>";

$tabelle = 'dm_todaten';
$dm_aufn_d = $ver_datum;

$_SESSION[$module]['dm_aufn_d'] = $ver_datum;

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

$md_eigner = $row['md_eigner'];

# $row['dm_eigner'] = "<a href='VF_O_dm_List_Bericht.php?dm_eigner=".$row['dm_eigner']."&dm_aufn_d=".$row['dm_aufn_datum']."'  >$dm_eigner - Fotos ansehen - auswählen,  Bericht erstellen</a>" ;
$row['md_eigner'] = "<input type='checkbox' id='$md_eigner' name='dm_id[]' value='$md_eigner'> $md_eigner - Fotos ansehen - auswählen,  Bericht erstellen" ;

# <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">

return True;
} # Ende von Function modifyRow
?>